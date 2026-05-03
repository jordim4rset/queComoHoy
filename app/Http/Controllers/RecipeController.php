<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Para filtrar las recteas por nombre, si esta visible o no y por el tiempo de elaboracion
        $recetas = Recipe::get();

        if (request('name')) {
            $recetas = Recipe::where('name', 'like', '%' . request('name') . '%')->get();
        }

        if (request('visibility') != '') {
            $recetas = Recipe::where('visibility', request('visibility'))->get();
        }

        if (request('time')) {
            $recetas = Recipe::where('time', '<=', request('time'))->get();
        }

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
        $receta = new Recipe();
        $generatedName = $request->file('photo')->store('img/recipes/cover', 'public');
        $receta->name = $request->input('name');
        $receta->description = $request->input('description');
        $receta->time = $request->input('time');
        $receta->tags = $request->input('tags');
        $receta->photo = $generatedName;
        $receta->user_id = auth()->id();
        $receta->visibility = $request->input('visibility') === 'on' ? 1 : 0;

        $receta->save();

        return redirect()->route('recetas.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $receta)
    {
        if (!$receta->visibility) {
            return redirect()->route('recetas.index');
        }
        return view('recipes.show', compact('receta'));
    }

    public function edit(Recipe $receta)
    {
        return view('recipes.edit', compact('receta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $receta)
    {
        $receta->name = $request->input('name');
        $receta->description = $request->input('description');
        $receta->time = $request->input('time');
        $receta->tags = $request->input('tags');
        $receta->visibility = $request->input('visibility') == 'on' ? 1 : 0;

        if ($request->hasFile('image')) {
            $generatedName = $request->file('image')->store('img/recipes/cover', 'public');
            $receta->image = $generatedName;
        }

        $receta->update();

        return redirect()->route('recipes.show', $receta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $receta)
    {
        $receta->delete();
        return redirect()->route('recetas.index');
    }
}
