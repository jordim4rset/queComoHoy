<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

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
        return view("recipes.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        if (!$receta->visibility && $receta->user_id !== $user->id) {
            return redirect()->route('recetas.index');
        }

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

        return view('recipes.edit', compact('receta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $receta)
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

        $receta->save();

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
}
