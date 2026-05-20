<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Event;
use App\Models\Ingredient;
use App\Http\Requests\RecipeStoreRequest;
use App\Http\Requests\RecipeUpdateRequest;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $query = Recipe::where('user_id', $user->id);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('visibility') && $request->visibility !== '') {
            $query->where('visibility', $request->visibility);
        }

        if ($request->filled('time')) {
            $query->where('time', '<=', $request->time);
        }

        $recetas = $query
            ->get()
            ->sortByDesc('id')
            ->values();

        return view('recipes.index', compact('recetas'));
    }

    public function search(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'user' => ['nullable', 'string', 'max:100'],
            'ingredient' => ['nullable', 'string', 'max:100'],
            'tag' => ['nullable', 'string', 'max:100'],
            'max_time' => ['nullable', 'numeric', 'min:0'],
            'media' => ['nullable', 'in:any,video'],
        ]);

        $recipes = Recipe::with(['user', 'ingredients', 'likes'])
            ->where('visibility', 1)
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('tags', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['user'] ?? null, function ($query, $userSearch) {
                $query->whereHas('user', function ($query) use ($userSearch) {
                    $query
                        ->where('username', 'like', '%' . $userSearch . '%')
                        ->orWhere('name', 'like', '%' . $userSearch . '%');
                });
            })
            ->when($filters['ingredient'] ?? null, function ($query, $ingredientSearch) {
                $query->whereHas('ingredients', function ($query) use ($ingredientSearch) {
                    $query
                        ->where('name', 'like', '%' . $ingredientSearch . '%')
                        ->orWhere('normalized_name', 'like', '%' . $ingredientSearch . '%');
                });
            })
            ->when($filters['tag'] ?? null, function ($query, $tag) {
                $query->where('tags', 'like', '%' . $tag . '%');
            })
            ->when($filters['max_time'] ?? null, function ($query, $maxTime) {
                $query->where('time', '<=', $maxTime);
            })
            ->when(($filters['media'] ?? 'any') === 'video', function ($query) {
                $query->whereNotNull('video');
            })
            ->orderBy('id', 'desc')
            ->get();

        $ingredients = Ingredient::orderBy('name')
            ->limit(80)
            ->get(['name']);

        return view('recipes.search', compact('recipes', 'ingredients', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecipeStoreRequest $request)
    {
        $user = $request->user();

        $receta = new Recipe();

        if ($request->hasFile('image')) {
            $generatedName = $request->file('image')->store('img/recipes/cover', 'public');
            $receta->image = $generatedName;
        }

        if ($request->hasFile('video')) {
            $generatedName = $request->file('video')->store('video/recipes', 'public');
            $receta->video = $generatedName;
        }

        $receta->name = $request->input('name');
        $receta->description = $request->input('description');
        $receta->time = $request->input('time');
        $receta->tags = $request->input('tags');
        $receta->user_id = $user->id;
        $receta->visibility = $request->input('visibility') === 'on' ? 1 : 0;

        $receta->save();

        $this->syncEventsFromTags($request, $receta);
        $this->syncIngredients($request, $receta);

        $points = $receta->visibility ? 20 : 5;
        $user->increment('chefpoints', $points);

        return redirect()->route('recetas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Recipe $receta)
    {
        $user = $request->user();

        if (!$receta->visibility && (!$user || $receta->user_id !== $user->id)) {
            abort(403);
        }

        $receta
            ->load(['user', 'ingredients', 'likes', 'comments.user'])
            ->loadCount('comments');

        return view('recipes.show', compact('receta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Recipe $receta)
    {
        $user = $request->user();

        if ($receta->user_id !== $user->id) {
            abort(403);
        }

        $receta->load('ingredients');

        return view('recipes.edit', compact('receta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecipeUpdateRequest $request, Recipe $receta)
    {
        $user = $request->user();

        if ($receta->user_id !== $user->id) {
            abort(403);
        }

        $receta->name = $request->input('name');
        $receta->description = $request->input('description');
        $receta->time = $request->input('time');
        $receta->tags = $request->input('tags');
        $receta->visibility = $request->input('visibility') === 'on' ? 1 : 0;

        if ($request->hasFile('image')) {
            $generatedName = $request->file('image')->store('img/recipes/cover', 'public');
            $receta->image = $generatedName;
        }

        if ($request->hasFile('video')) {
            $generatedName = $request->file('video')->store('video/recipes', 'public');
            $receta->video = $generatedName;
        }

        $receta->save();

        $this->syncEventsFromTags($request, $receta);
        $this->syncIngredients($request, $receta);

        return redirect()->route('recetas.show', $receta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Recipe $receta)
    {
        $user = $request->user();

        if ($receta->user_id !== $user->id) {
            abort(403);
        }

        $receta->delete();

        return redirect()->route('recetas.index');
    }

    private function syncIngredients(Request $request, Recipe $receta): void
    {
        $syncData = [];

        foreach ($request->input('ingredients', []) as $ingredientData) {
            if (empty($ingredientData['id'])) {
                continue;
            }

            $syncData[$ingredientData['id']] = [
                'quantity' => $ingredientData['quantity'] ?? null,
                'unit' => $ingredientData['unit'] ?? null,
            ];
        }

        $receta->ingredients()->sync($syncData);
    }

    private function syncEventsFromTags(Request $request, Recipe $receta): void
    {
        $tags = array_filter(array_map(
            fn($tag) => trim(mb_strtolower($tag)),
            explode(',', $request->input('tags', ''))
        ));

        if (empty($tags)) {
            $receta->events()->detach();
            return;
        }

        $eventIds = [];

        foreach (Event::all() as $event) {
            $eventName = mb_strtolower($event->name ?? '');
            $eventTitle = mb_strtolower($event->title ?? '');

            if (in_array($eventName, $tags, true) || in_array($eventTitle, $tags, true)) {
                $eventIds[] = $event->id;
            }
        }

        $receta->events()->sync(array_unique($eventIds));
    }
}
