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
    $recetas = Recipe::get();
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
        $generatedName = $request->file('photo')->store('img/recipes/cover','public');
        $receta->name = $request->input('name');
        $receta->description = $request->input('description');
        $receta->time = $request->input('time');
        $receta->tags = $request->input('tags');
        $receta->photo = $generatedName;
        if($request->input('visibility') == 'on'){
            $receta->visibility = 1;
        }else{
            $receta->visibility = 0;
        }
        $receta->save();

        return redirect()->route('recipes.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $receta)
{
    if (!$receta->visibility) {
        return redirect()->route('recipes.index');
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
        $generatedName = $request->file('photo')->store('img/recipes/cover','public');

        $receta->name = $request->input('name');
        $receta->description = $request->input('description');
        $receta->time = $request->input('time');
        $receta->tags = $request->input('tags');
        $receta->image = $generatedName;

        if($request->input('visibility') == 'on'){
            $receta->visibility = 1;
        }else{
            $receta->visibility = 0;
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
    return redirect()->route('recipes.index');
}
}
