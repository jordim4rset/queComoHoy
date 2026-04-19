<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::get();
        return view('ingredients.index', compact("ingredients"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //$generatedName = $request->file('icon')->store('img/ingredientes/cover','public');
        $ingr = new Ingredient();
        $ingr->name = $request->input('name');
        $ingr->icon = 'icono';//$generatedName;
        $ingr->category = $request->input('category');
        $ingr->save();

        return redirect()->route('ingredientes.store');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingr)
    {
        return view('ingredients.show', compact("ingr"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingr)
    {
        return view('ingredients.edit', compact("ingr"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingr)
    {
        $generatedName = $request->file('icon')->store('img/ingredientes/cover','public');
        $ingr->name = $request->input('name');
        $ingr->category = $request->input('category');
        $ingr->icon = $generatedName;
        $ingr->category = $request->input('category');
        $ingr->update();

        return redirect()->route('ingredientes.show', $ingr);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingr)
    {
        $ingr->delete();
        return redirect()->route('ingredientes.index');
    }
}
