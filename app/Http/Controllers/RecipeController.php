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
        return view("recipes.index", compact('recetas'));
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
        $generatedName = $request->file('photo')->store('img/recipes/cover','public');
        $receta = new Recipe();
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
        if($receta->visibility == false){
            return redirect()->route('recipes.index');
        }else{
            return view("recipes.show");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        return view("recipes.edit", compact('recipe'));
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
        return redirect()->route('recipes.show', compact('receta'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('recipes.index');
    }
}
