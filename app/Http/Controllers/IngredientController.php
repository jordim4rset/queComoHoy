<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::get();
        return view('ingredients.index', compact("ingredients"));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request)
    {

        $ingr = new Ingredient();
        $ingr->name = $request->input('name');

        //Aqui tenias un problema de
        if ($request->hasFile('icon')) {
            $generatedName = $request->file('icon')->store('img/ingredientes/cover', 'public');
            $ingr->icon = $generatedName;
        }
        $ingr->category = $request->input('category');
        $ingr->save();

        return redirect()->route('ingredientes.index');
    }

    public function show(Ingredient $ingrediente)
    {
        return view('ingredients.show', compact('ingrediente'));
    }

    public function edit(Ingredient $ingrediente)
    {
        return view('ingredients.edit', compact('ingrediente'));
    }

    public function update(Request $request, Ingredient $ingrediente)
    {
        if ($request->hasFile('icon')) {
            $generatedName = $request->file('icon')->store('img/ingredientes/cover', 'public');
            $ingrediente->icon = $generatedName;
        }
        $ingrediente->name = $request->input('name');
        $ingrediente->category = $request->input('category');
        $ingrediente->save();

        return redirect()->route('ingredientes.show', $ingrediente);
    }

    public function destroy(Ingredient $ingrediente)
    {
        $ingrediente->delete();
        return redirect()->route('ingredientes.index');
    }
}
