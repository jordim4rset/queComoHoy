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
        //$generatedName = $request->file('icon')->store('img/ingredientes/cover','public');
        $ingr = new Ingredient();
        $ingr->name = $request->input('name');
        $ingr->icon = 'icono';//$generatedName;
        $ingr->category = $request->input('category');
        $ingr->save();

        return redirect()->route('ingredientes.index');
    }

    public function show(Ingredient $ingr)
    {
        return view('ingredients.show', compact("ingr"));
    }

    public function edit(Ingredient $ingr)
    {
        return view('ingredients.edit', compact("ingr"));
    }

    public function update(Request $request, Ingredient $ingr)
    {
        if ($request->hasFile('icon')) {
            $generatedName = $request->file('icon')->store('img/ingredientes/cover','public');
            $ingr->icon = $generatedName;
        }
        $ingr->name = $request->input('name');
        $ingr->category = $request->input('category');
        $ingr->update();

        return redirect()->route('ingredientes.show', $ingr);
    }

    public function destroy(Ingredient $ingr)
    {
        $ingr->delete();
        return redirect()->route('ingredientes.index');
    }
}
