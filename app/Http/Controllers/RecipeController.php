<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Event;

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
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string'],
            'time' => ['nullable', 'numeric', 'min:0'],
            'tags' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.id' => ['required_with:ingredients', 'integer', 'exists:ingredients,id'],
            'ingredients.*.quantity' => ['nullable', 'numeric', 'min:0'],
            'ingredients.*.unit' => ['nullable', 'string', 'max:30'],
        ]);

        $user = $request->user();

        $receta = new Recipe();

        if ($request->hasFile('image')) {
            $generatedName = $request->file('image')->store('img/recipes/cover', 'public');
            $receta->image = $generatedName;
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

        $receta->load(['user', 'ingredients']);

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
    public function update(Request $request, Recipe $receta)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string'],
            'time' => ['nullable', 'numeric', 'min:0'],
            'tags' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.id' => ['required_with:ingredients', 'integer', 'exists:ingredients,id'],
            'ingredients.*.quantity' => ['nullable', 'numeric', 'min:0'],
            'ingredients.*.unit' => ['nullable', 'string', 'max:30'],
        ]);

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
            fn ($tag) => trim(mb_strtolower($tag)),
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
