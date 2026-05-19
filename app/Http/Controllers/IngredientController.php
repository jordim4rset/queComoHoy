<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Services\IngredientAiValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    private const INGREDIENTS_PER_PAGE = 20;

    public function index(Request $request)
    {
        $query = Ingredient::query()->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $ingredients = $query
            ->paginate(self::INGREDIENTS_PER_PAGE)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('ingredients.partials.ingredient-cards', compact('ingredients'))->render(),
                'next_page_url' => $ingredients->nextPageUrl(),
            ]);
        }

        return view('ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request, IngredientAiValidator $validator)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'category' => ['required', 'in:' . implode(',', Ingredient::CATEGORIES)],
        ]);

        $originalName = $this->prepareIngredientName($request->input('name'));
        $normalizedName = $this->normalize($originalName);

        if (Ingredient::where('normalized_name', $normalizedName)->exists()) {
            return back()->withErrors([
                'name' => 'Este ingrediente ya existe.',
            ])->withInput();
        }

        $aiResult = $validator->validate($originalName);

        if (!$aiResult['is_food']) {
            return back()->withErrors([
                'name' => 'El texto introducido no parece ser un alimento valido. ' . $aiResult['reason'],
            ])->withInput();
        }

        $correctedName = Str::limit($aiResult['corrected_name'], 30, '');
        $correctedNormalizedName = $this->normalize($correctedName);

        if ($correctedNormalizedName === '') {
            return back()->withErrors([
                'name' => 'La IA no devolvio un nombre de ingrediente valido.',
            ])->withInput();
        }

        if (Ingredient::where('normalized_name', $correctedNormalizedName)->exists()) {
            return back()->withErrors([
                'name' => 'Este ingrediente ya existe con el nombre corregido.',
            ])->withInput();
        }

        $category = in_array($aiResult['category'], Ingredient::CATEGORIES)
            ? $aiResult['category']
            : $request->input('category');

        $ingr = new Ingredient();
        $ingr->name = $correctedName;
        $ingr->normalized_name = $correctedNormalizedName;

        $ingr->category = $category;
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
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'category' => ['required', 'in:' . implode(',', Ingredient::CATEGORIES)],
        ]);

        $normalizedName = $this->normalize($request->input('name'));

        $exists = Ingredient::where('normalized_name', $normalizedName)
            ->where('id', '!=', $ingrediente->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'name' => 'Este ingrediente ya existe.',
            ])->withInput();
        }

        $ingrediente->name = $request->input('name');
        $ingrediente->normalized_name = $normalizedName;
        $ingrediente->category = $request->input('category');
        $ingrediente->save();

        return redirect()->route('ingredientes.show', $ingrediente);
    }

    public function destroy(Ingredient $ingrediente)
    {
        $ingrediente->delete();

        return redirect()->route('ingredientes.index');
    }

    public function searchForRecipe(Request $request)
    {
        $search = $this->normalize($request->input('q', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $ingredients = Ingredient::where('normalized_name', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->limit(10)
            ->get([
                'id',
                'name',
                'normalized_name',
                'category',
            ]);

        return response()->json($ingredients);
    }

    public function storeFromRecipe(Request $request, IngredientAiValidator $validator)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
        ]);

        $originalName = $this->prepareIngredientName($request->input('name'));
        $normalizedName = $this->normalize($originalName);

        $existingIngredient = Ingredient::where('normalized_name', $normalizedName)->first();

        if ($existingIngredient) {
            return response()->json([
                'success' => true,
                'ingredient' => $existingIngredient,
                'message' => 'El ingrediente ya existía.',
            ]);
        }

        $aiResult = $validator->validate($originalName);

        if (!$aiResult['is_food']) {
            return response()->json([
                'success' => false,
                'message' => 'El texto introducido no parece ser un alimento válido.',
                'reason' => $aiResult['reason'],
            ], 422);
        }

        $correctedName = Str::limit($aiResult['corrected_name'], 30, '');
        $correctedNormalizedName = $this->normalize($correctedName);

        if ($correctedNormalizedName === '') {
            return response()->json([
                'success' => false,
                'message' => 'La IA no devolvio un nombre de ingrediente valido.',
            ], 422);
        }

        $existingCorrectedIngredient = Ingredient::where('normalized_name', $correctedNormalizedName)->first();

        if ($existingCorrectedIngredient) {
            return response()->json([
                'success' => true,
                'ingredient' => $existingCorrectedIngredient,
                'message' => 'El ingrediente ya existía con el nombre corregido.',
            ]);
        }

        $category = in_array($aiResult['category'], Ingredient::CATEGORIES)
            ? $aiResult['category']
            : 'Verdura';

        $ingredient = Ingredient::create([
            'name' => $correctedName,
            'normalized_name' => $correctedNormalizedName,
            'category' => $category,
        ]);

        return response()->json([
            'success' => true,
            'ingredient' => $ingredient,
            'message' => 'Ingrediente creado correctamente.',
        ]);
    }

    private function normalize(string $value): string
    {
        return Str::of($value)
            ->ascii()
            ->lower()
            ->squish()
            ->toString();
    }

    private function prepareIngredientName(string $value): string
    {
        $name = Str::of($value)
            ->squish()
            ->toString();

        $replacements = [
            '/\bse\b/i' => 'de',
            '/\bcerdi\b/i' => 'cerdo',
            '/\bechuga\b/i' => 'pechuga',
            '/\bpexuga\b/i' => 'pechuga',
            '/\bavo\b/i' => 'pavo',
            '/\bpolli\b/i' => 'pollo',
            '/\bvoqueron(es)?\b/i' => 'boqueron',
        ];

        return preg_replace(array_keys($replacements), array_values($replacements), $name);
    }
}
