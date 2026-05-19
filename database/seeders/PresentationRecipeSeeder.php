<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresentationRecipeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'presentacion@quecomohoy.test'],
            [
                'name' => 'Chef Presentacion',
                'username' => 'chef_presentacion',
                'password' => 'password',
                'rol' => 'member',
                'chefpoints' => 400,
            ]
        );

        foreach ($this->recipes() as $index => $recipeData) {
            $imagePath = $this->storeCover($recipeData, $index);

            $recipe = Recipe::where('name', $recipeData['name'])
                ->where('user_id', $user->id)
                ->first() ?? new Recipe();

            $recipe->name = $recipeData['name'];
            $recipe->description = $recipeData['description'];
            $recipe->time = $recipeData['time'];
            $recipe->tags = $recipeData['tags'];
            $recipe->visibility = 1;
            $recipe->image = $imagePath;
            $recipe->user_id = $user->id;

            if (Schema::hasColumn('recipes', 'video')) {
                $recipe->video = null;
            }

            $recipe->save();
            $recipe->ingredients()->sync($this->ingredientSyncData($recipeData['ingredients']));
        }
    }

    /**
     * @param array<int, array{name: string, quantity: float|int|null, unit: string|null, category: string}> $ingredients
     * @return array<int, array{quantity: float|int|null, unit: string|null}>
     */
    private function ingredientSyncData(array $ingredients): array
    {
        $syncData = [];

        foreach ($ingredients as $ingredientData) {
            $ingredient = $this->firstOrCreateIngredient(
                $ingredientData['name'],
                $ingredientData['category']
            );

            $syncData[$ingredient->id] = [
                'quantity' => $ingredientData['quantity'],
                'unit' => $ingredientData['unit'],
            ];
        }

        return $syncData;
    }

    private function firstOrCreateIngredient(string $name, string $category): Ingredient
    {
        $normalizedName = $this->normalize($name);

        $ingredient = Ingredient::where('normalized_name', $normalizedName)
            ->orWhere('name', $name)
            ->first() ?? new Ingredient();

        $ingredient->name = $name;
        $ingredient->normalized_name = $normalizedName;
        $ingredient->category = $category;
        $ingredient->save();

        return $ingredient;
    }

    /**
     * @param array{name: string, tags: string} $recipeData
     */
    private function storeCover(array $recipeData, int $index): string
    {
        $slug = Str::slug($recipeData['name']);
        $path = "img/recipes/presentation/{$slug}.svg";
        $palette = $this->palettes()[$index % count($this->palettes())];

        Storage::disk('public')->put($path, $this->coverSvg(
            $recipeData['name'],
            $recipeData['tags'],
            $palette,
            $slug
        ));

        return $path;
    }

    /**
     * @param array{from: string, to: string, accent: string} $palette
     */
    private function coverSvg(string $name, string $tags, array $palette, string $slug): string
    {
        $safeName = e($name);
        $safeTags = e(str_replace(',', ' / ', $tags));
        $dish = $this->dishIllustration($slug);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800" role="img" aria-label="{$safeName}">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$palette['from']}"/>
      <stop offset="100%" stop-color="{$palette['to']}"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="18" stdDeviation="18" flood-color="#1d1d1d" flood-opacity="0.22"/>
    </filter>
  </defs>
  <rect width="1200" height="800" fill="url(#bg)"/>
  <circle cx="1010" cy="130" r="210" fill="#ffffff" opacity="0.14"/>
  <circle cx="190" cy="690" r="240" fill="#ffffff" opacity="0.11"/>
  <rect x="120" y="90" width="960" height="620" rx="46" fill="#fff8ea" opacity="0.95" filter="url(#shadow)"/>
  <rect x="185" y="560" width="830" height="92" rx="28" fill="#ffffff" opacity="0.78"/>
  {$dish}
  <text x="600" y="618" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="800" fill="#2f2418">{$safeName}</text>
  <text x="600" y="660" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="22" font-weight="700" fill="#7a6044">{$safeTags}</text>
</svg>
SVG;
    }

    private function dishIllustration(string $slug): string
    {
        return match ($slug) {
            'gazpacho-andaluz' => $this->bowl('#c83f2b', [
                $this->cube(450, 315, '#7bbf5a'),
                $this->cube(530, 275, '#f7d04a'),
                $this->cube(610, 330, '#ffffff'),
                $this->slice(690, 290, '#2f9e44'),
                '<path d="M450 390 C520 360 600 420 690 380" fill="none" stroke="#f8d978" stroke-width="14" stroke-linecap="round"/>',
            ]),
            'tortilla-de-patata' => $this->plate([
                '<ellipse cx="600" cy="350" rx="250" ry="150" fill="#f2bf45"/>',
                '<ellipse cx="600" cy="350" rx="210" ry="116" fill="#f7d777" opacity="0.95"/>',
                $this->cube(505, 315, '#fff0a6'),
                $this->cube(585, 390, '#f5d86a'),
                $this->cube(675, 320, '#fff0a6'),
                '<path d="M430 360 C520 300 660 300 770 365" fill="none" stroke="#d78d1d" stroke-width="10" stroke-linecap="round" opacity="0.45"/>',
            ]),
            'lentejas-con-verduras' => $this->bowl('#8b5a2b', [
                $this->circle(475, 330, 16, '#cf7d32'),
                $this->circle(530, 380, 13, '#d6a44b'),
                $this->circle(595, 320, 15, '#a04e2e'),
                $this->circle(680, 365, 14, '#cf7d32'),
                $this->leaf(720, 310, '#5f9f3a'),
            ]),
            'ensalada-de-garbanzos' => $this->plate([
                $this->leaf(470, 315, '#63a85a'),
                $this->leaf(535, 385, '#4d9b50'),
                $this->circle(590, 340, 20, '#e0b45c'),
                $this->circle(650, 385, 18, '#e0b45c'),
                $this->slice(705, 320, '#d94735'),
                '<path d="M500 425 C580 375 650 450 735 390" fill="none" stroke="#d9eef8" stroke-width="28" stroke-linecap="round"/>',
            ]),
            'arroz-con-verduras' => $this->plate([
                '<ellipse cx="600" cy="370" rx="245" ry="120" fill="#f4ead1"/>',
                $this->cube(485, 345, '#e9533f'),
                $this->cube(565, 405, '#f0aa3e'),
                $this->cube(645, 335, '#4ea75a'),
                $this->cube(725, 385, '#d94635'),
                $this->leaf(590, 310, '#3f8f4f'),
            ]),
            'salmon-al-horno' => $this->plate([
                '<path d="M405 360 C515 265 720 265 815 360 C710 455 515 455 405 360Z" fill="#f26b54"/>',
                '<path d="M465 355 C560 310 665 310 760 355" fill="none" stroke="#ffb0a0" stroke-width="18" stroke-linecap="round"/>',
                '<circle cx="470" cy="360" r="9" fill="#4a241c"/>',
                '<ellipse cx="735" cy="410" rx="76" ry="42" fill="#e7c874"/>',
                $this->slice(510, 445, '#f9d85e'),
            ]),
            'pollo-con-quinoa' => $this->bowl('#f0d19a', [
                '<path d="M470 350 q48 -62 108 0 q-44 52 -108 0Z" fill="#d58845"/>',
                '<path d="M620 330 q52 -58 120 6 q-50 52 -120 -6Z" fill="#d58845"/>',
                $this->circle(548, 407, 10, '#f7efe0'),
                $this->circle(590, 380, 9, '#f7efe0'),
                $this->cube(700, 405, '#d94735'),
                $this->leaf(495, 300, '#4b9b56'),
            ]),
            'pasta-con-atun' => $this->plate([
                '<path d="M410 370 C500 310 530 430 610 365 S720 300 795 372" fill="none" stroke="#f4d06f" stroke-width="30" stroke-linecap="round"/>',
                '<path d="M430 420 C520 360 555 465 650 405 S720 355 790 420" fill="none" stroke="#f4d06f" stroke-width="24" stroke-linecap="round"/>',
                $this->circle(540, 345, 22, '#c7342c'),
                $this->circle(660, 410, 22, '#c7342c'),
                '<path d="M610 340 l85 -34 l-15 72Z" fill="#c8d7dd"/>',
            ]),
            'pisto-manchego' => $this->pan('#c84a32', [
                $this->cube(465, 335, '#4ea75a'),
                $this->cube(550, 390, '#f0aa3e'),
                $this->cube(635, 320, '#d94635'),
                $this->cube(710, 380, '#6ba84f'),
                $this->leaf(590, 285, '#3f8f4f'),
            ]),
            'crema-de-calabacin' => $this->bowl('#9ccf72', [
                '<path d="M500 355 C555 330 640 420 700 350" fill="none" stroke="#fff7c6" stroke-width="16" stroke-linecap="round"/>',
                $this->slice(490, 310, '#5aaa52'),
                $this->slice(690, 390, '#5aaa52'),
                $this->cube(600, 330, '#f0e6a8'),
            ]),
            'tacos-de-pollo' => $this->plate([
                $this->taco(480, 360),
                $this->taco(650, 360),
                $this->cube(515, 330, '#d98943'),
                $this->cube(690, 330, '#d98943'),
                $this->leaf(560, 310, '#4b9b56'),
                $this->leaf(740, 312, '#4b9b56'),
            ]),
            'curry-de-garbanzos' => $this->bowl('#d9902f', [
                $this->circle(500, 340, 18, '#e5c36a'),
                $this->circle(570, 390, 16, '#e5c36a'),
                $this->circle(655, 330, 18, '#e5c36a'),
                $this->circle(720, 385, 15, '#e5c36a'),
                '<ellipse cx="750" cy="450" rx="86" ry="38" fill="#f4ead1"/>',
            ]),
            'bacalao-con-tomate' => $this->plate([
                '<ellipse cx="600" cy="390" rx="250" ry="88" fill="#c7352d"/>',
                '<path d="M440 350 C545 285 690 285 780 355 C695 430 545 425 440 350Z" fill="#f1eadf"/>',
                '<circle cx="490" cy="350" r="8" fill="#4a4038"/>',
                $this->leaf(705, 310, '#4b9b56'),
                $this->cube(565, 438, '#e8ded0'),
            ]),
            'ensalada-de-couscous' => $this->plate([
                '<ellipse cx="600" cy="375" rx="230" ry="115" fill="#e8d49d"/>',
                $this->circle(505, 330, 15, '#d94735'),
                $this->circle(595, 405, 13, '#e0b45c'),
                $this->cube(690, 345, '#7bbf5a'),
                $this->leaf(745, 405, '#4b9b56'),
            ]),
            'wok-de-ternera' => $this->pan('#2f3136', [
                '<path d="M470 350 q65 -55 135 8 q-68 55 -135 -8Z" fill="#8b3f2a"/>',
                '<path d="M620 405 q75 -52 135 10 q-72 50 -135 -10Z" fill="#8b3f2a"/>',
                $this->cube(545, 415, '#f0aa3e'),
                $this->cube(690, 330, '#d94635'),
                $this->leaf(610, 305, '#4b9b56'),
            ]),
            'fajitas-vegetales' => $this->plate([
                $this->taco(500, 365, '#e8c675'),
                $this->taco(670, 365, '#e8c675'),
                $this->cube(520, 325, '#d94635'),
                $this->cube(615, 405, '#f0aa3e'),
                $this->cube(705, 320, '#4ea75a'),
                '<path d="M545 362 C610 322 670 420 735 370" fill="none" stroke="#f7f0bd" stroke-width="18" stroke-linecap="round"/>',
            ]),
            'merluza-con-patata' => $this->plate([
                '<path d="M410 360 C520 280 715 285 815 365 C700 445 515 440 410 360Z" fill="#f4f0e6"/>',
                '<circle cx="470" cy="360" r="8" fill="#4a4038"/>',
                '<ellipse cx="630" cy="435" rx="64" ry="36" fill="#d9b96e"/>',
                '<ellipse cx="745" cy="418" rx="54" ry="32" fill="#d9b96e"/>',
                $this->slice(520, 430, '#f9d85e'),
            ]),
            'bowl-de-arroz-y-pollo' => $this->bowl('#f3e6c7', [
                '<path d="M460 350 q55 -55 120 5 q-60 48 -120 -5Z" fill="#d58845"/>',
                '<path d="M615 390 q50 -48 112 6 q-54 45 -112 -6Z" fill="#d58845"/>',
                $this->leaf(510, 420, '#4b9b56'),
                $this->circle(675, 330, 20, '#d94735'),
                $this->circle(590, 330, 9, '#fffaf0'),
            ]),
            'sopa-de-pollo' => $this->bowl('#e0b85a', [
                '<path d="M470 360 C520 330 560 390 610 360 S700 330 750 365" fill="none" stroke="#f3d88a" stroke-width="20" stroke-linecap="round"/>',
                $this->cube(515, 405, '#d58845'),
                $this->cube(635, 330, '#d58845'),
                $this->circle(700, 405, 14, '#cf7d32'),
                $this->leaf(560, 310, '#5f9f3a'),
            ]),
            'tostas-de-aguacate' => $this->plate([
                '<rect x="420" y="310" width="330" height="150" rx="34" fill="#b87735"/>',
                '<rect x="445" y="330" width="280" height="110" rx="28" fill="#d9a15c"/>',
                '<ellipse cx="540" cy="382" rx="80" ry="48" fill="#6bad55"/>',
                '<ellipse cx="655" cy="380" rx="80" ry="48" fill="#6bad55"/>',
                '<circle cx="610" cy="360" r="38" fill="#fff4dc"/>',
                '<circle cx="610" cy="360" r="18" fill="#f2b33d"/>',
            ]),
            default => $this->plate([
                $this->circle(520, 350, 48, '#d94735'),
                $this->circle(625, 390, 52, '#f0aa3e'),
                $this->leaf(705, 330, '#4b9b56'),
            ]),
        };
    }

    /**
     * @param array<int, string> $items
     */
    private function plate(array $items): string
    {
        return '<ellipse cx="600" cy="395" rx="330" ry="185" fill="#ede5d8"/>'
            . '<ellipse cx="600" cy="380" rx="280" ry="145" fill="#ffffff"/>'
            . implode('', $items);
    }

    /**
     * @param array<int, string> $items
     */
    private function bowl(string $fill, array $items): string
    {
        return '<ellipse cx="600" cy="435" rx="330" ry="112" fill="#d4c4ad"/>'
            . '<path d="M310 360 C330 545 870 545 890 360Z" fill="#ffffff"/>'
            . '<ellipse cx="600" cy="360" rx="300" ry="112" fill="#ece0cf"/>'
            . '<ellipse cx="600" cy="350" rx="250" ry="82" fill="' . $fill . '"/>'
            . implode('', $items);
    }

    /**
     * @param array<int, string> $items
     */
    private function pan(string $fill, array $items): string
    {
        return '<path d="M340 450 C420 550 780 550 860 450 L810 380 C725 450 470 450 390 380Z" fill="#343434"/>'
            . '<ellipse cx="600" cy="360" rx="285" ry="95" fill="' . $fill . '"/>'
            . implode('', $items);
    }

    private function taco(int $x, int $y, string $fill = '#e9bd62'): string
    {
        return '<path d="M' . ($x - 82) . ' ' . ($y + 52) . ' Q' . $x . ' ' . ($y - 88) . ' ' . ($x + 82) . ' ' . ($y + 52) . 'Z" fill="' . $fill . '"/>'
            . '<path d="M' . ($x - 66) . ' ' . ($y + 36) . ' Q' . $x . ' ' . ($y - 44) . ' ' . ($x + 66) . ' ' . ($y + 36) . '" fill="none" stroke="#b8792f" stroke-width="8" stroke-linecap="round"/>';
    }

    private function cube(int $x, int $y, string $fill): string
    {
        return '<rect x="' . $x . '" y="' . $y . '" width="44" height="34" rx="8" fill="' . $fill . '"/>';
    }

    private function circle(int $x, int $y, int $radius, string $fill): string
    {
        return '<circle cx="' . $x . '" cy="' . $y . '" r="' . $radius . '" fill="' . $fill . '"/>';
    }

    private function slice(int $x, int $y, string $fill): string
    {
        return '<path d="M' . $x . ' ' . ($y - 42) . ' A42 42 0 0 1 ' . ($x + 42) . ' ' . $y . ' L' . $x . ' ' . $y . 'Z" fill="' . $fill . '"/>';
    }

    private function leaf(int $x, int $y, string $fill): string
    {
        return '<path d="M' . $x . ' ' . $y . ' C' . ($x + 78) . ' ' . ($y - 54) . ' ' . ($x + 118) . ' ' . ($y + 18) . ' ' . ($x + 28) . ' ' . ($y + 52) . ' C' . ($x - 24) . ' ' . ($y + 24) . ' ' . ($x - 14) . ' ' . ($y - 22) . ' ' . $x . ' ' . $y . 'Z" fill="' . $fill . '"/>';
    }

    private function normalize(string $value): string
    {
        return Str::of($value)
            ->ascii()
            ->lower()
            ->squish()
            ->toString();
    }

    /**
     * @return array<int, array{from: string, to: string, accent: string}>
     */
    private function palettes(): array
    {
        return [
            ['from' => '#f8b84e', 'to' => '#e86d3f', 'accent' => '#ff9f1c'],
            ['from' => '#5fbf8f', 'to' => '#246b55', 'accent' => '#2ec4b6'],
            ['from' => '#f4d35e', 'to' => '#cc5a1d', 'accent' => '#ee964b'],
            ['from' => '#9bd1e5', 'to' => '#3066be', 'accent' => '#5dade2'],
            ['from' => '#f7a8a8', 'to' => '#a53860', 'accent' => '#ef476f'],
        ];
    }

    /**
     * @return array<int, array{name: string, description: string, time: int, tags: string, ingredients: array<int, array{name: string, quantity: float|int|null, unit: string|null, category: string}>}>
     */
    private function recipes(): array
    {
        return [
            [
                'name' => 'Gazpacho andaluz',
                'description' => 'Sopa fria de tomate, pepino y pimiento para dias de calor.',
                'time' => 15,
                'tags' => 'mediterranea,fria,verdura',
                'ingredients' => [
                    ['name' => 'Tomate', 'quantity' => 600, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pepino', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pimiento', 'quantity' => 100, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Aceite de oliva', 'quantity' => 30, 'unit' => 'ml', 'category' => 'Aceite'],
                ],
            ],
            [
                'name' => 'Tortilla de patata',
                'description' => 'Clasico casero con huevo, patata y cebolla pochada.',
                'time' => 35,
                'tags' => 'espanola,casera,huevo',
                'ingredients' => [
                    ['name' => 'Huevo', 'quantity' => 5, 'unit' => 'ud', 'category' => 'Lacteo'],
                    ['name' => 'Patata', 'quantity' => 500, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Cebolla', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Aceite de oliva', 'quantity' => 40, 'unit' => 'ml', 'category' => 'Aceite'],
                ],
            ],
            [
                'name' => 'Lentejas con verduras',
                'description' => 'Guiso suave de legumbre con hortalizas y pimenton.',
                'time' => 45,
                'tags' => 'legumbre,guiso,saludable',
                'ingredients' => [
                    ['name' => 'Lentejas', 'quantity' => 300, 'unit' => 'g', 'category' => 'Legumbre'],
                    ['name' => 'Zanahoria', 'quantity' => 140, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pimiento', 'quantity' => 100, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pimenton', 'quantity' => 5, 'unit' => 'g', 'category' => 'Especia'],
                ],
            ],
            [
                'name' => 'Ensalada de garbanzos',
                'description' => 'Garbanzos con tomate, pepino y atun en una comida rapida.',
                'time' => 12,
                'tags' => 'ensalada,legumbre,rapida',
                'ingredients' => [
                    ['name' => 'Garbanzos', 'quantity' => 300, 'unit' => 'g', 'category' => 'Legumbre'],
                    ['name' => 'Tomate', 'quantity' => 180, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pepino', 'quantity' => 100, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Atun', 'quantity' => 120, 'unit' => 'g', 'category' => 'Pescado'],
                ],
            ],
            [
                'name' => 'Arroz con verduras',
                'description' => 'Arroz meloso con calabacin, pimiento y zanahoria.',
                'time' => 32,
                'tags' => 'arroz,verdura,facil',
                'ingredients' => [
                    ['name' => 'Arroz', 'quantity' => 260, 'unit' => 'g', 'category' => 'Cereal'],
                    ['name' => 'Calabacin', 'quantity' => 180, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pimiento', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Zanahoria', 'quantity' => 100, 'unit' => 'g', 'category' => 'Verdura'],
                ],
            ],
            [
                'name' => 'Salmon al horno',
                'description' => 'Salmon jugoso con limon, patata y hierbas aromaticas.',
                'time' => 28,
                'tags' => 'pescado,horno,proteina',
                'ingredients' => [
                    ['name' => 'Salmon', 'quantity' => 300, 'unit' => 'g', 'category' => 'Pescado'],
                    ['name' => 'Patata', 'quantity' => 300, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Limon', 'quantity' => 1, 'unit' => 'ud', 'category' => 'Fruta'],
                    ['name' => 'Oregano', 'quantity' => 3, 'unit' => 'g', 'category' => 'Especia'],
                ],
            ],
            [
                'name' => 'Pollo con quinoa',
                'description' => 'Bowl de pollo, quinoa y verduras para comer completo.',
                'time' => 30,
                'tags' => 'pollo,bowl,saludable',
                'ingredients' => [
                    ['name' => 'Pollo', 'quantity' => 250, 'unit' => 'g', 'category' => 'Carne'],
                    ['name' => 'Quinoa', 'quantity' => 180, 'unit' => 'g', 'category' => 'Cereal'],
                    ['name' => 'Calabacin', 'quantity' => 150, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Tomate', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                ],
            ],
            [
                'name' => 'Pasta con atun',
                'description' => 'Pasta sencilla con atun, tomate y oregano.',
                'time' => 20,
                'tags' => 'pasta,atun,rapida',
                'ingredients' => [
                    ['name' => 'Espaguetis', 'quantity' => 240, 'unit' => 'g', 'category' => 'Pasta'],
                    ['name' => 'Atun', 'quantity' => 160, 'unit' => 'g', 'category' => 'Pescado'],
                    ['name' => 'Tomate frito', 'quantity' => 180, 'unit' => 'g', 'category' => 'Salsa'],
                    ['name' => 'Oregano', 'quantity' => 3, 'unit' => 'g', 'category' => 'Especia'],
                ],
            ],
            [
                'name' => 'Pisto manchego',
                'description' => 'Verduras pochadas con tomate y aceite de oliva.',
                'time' => 40,
                'tags' => 'verdura,espanola,casera',
                'ingredients' => [
                    ['name' => 'Tomate', 'quantity' => 400, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Calabacin', 'quantity' => 250, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pimiento', 'quantity' => 180, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Cebolla', 'quantity' => 150, 'unit' => 'g', 'category' => 'Verdura'],
                ],
            ],
            [
                'name' => 'Crema de calabacin',
                'description' => 'Crema ligera con calabacin, patata y queso suave.',
                'time' => 25,
                'tags' => 'crema,verdura,cena',
                'ingredients' => [
                    ['name' => 'Calabacin', 'quantity' => 500, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Patata', 'quantity' => 180, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Cebolla', 'quantity' => 80, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Queso', 'quantity' => 40, 'unit' => 'g', 'category' => 'Lacteo'],
                ],
            ],
            [
                'name' => 'Tacos de pollo',
                'description' => 'Tortillas rellenas de pollo salteado, pimiento y salsa.',
                'time' => 25,
                'tags' => 'pollo,tacos,facil',
                'ingredients' => [
                    ['name' => 'Pollo', 'quantity' => 300, 'unit' => 'g', 'category' => 'Carne'],
                    ['name' => 'Pan', 'quantity' => 4, 'unit' => 'ud', 'category' => 'Paneria'],
                    ['name' => 'Pimiento', 'quantity' => 140, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Salsa picante', 'quantity' => 20, 'unit' => 'g', 'category' => 'Picante'],
                ],
            ],
            [
                'name' => 'Curry de garbanzos',
                'description' => 'Garbanzos especiados con leche y arroz blanco.',
                'time' => 30,
                'tags' => 'legumbre,curry,vegetal',
                'ingredients' => [
                    ['name' => 'Garbanzos', 'quantity' => 320, 'unit' => 'g', 'category' => 'Legumbre'],
                    ['name' => 'Leche', 'quantity' => 200, 'unit' => 'ml', 'category' => 'Lacteo'],
                    ['name' => 'Arroz', 'quantity' => 180, 'unit' => 'g', 'category' => 'Cereal'],
                    ['name' => 'Curry', 'quantity' => 8, 'unit' => 'g', 'category' => 'Especia'],
                ],
            ],
            [
                'name' => 'Bacalao con tomate',
                'description' => 'Bacalao suave cocinado con salsa de tomate casera.',
                'time' => 35,
                'tags' => 'pescado,tomate,casera',
                'ingredients' => [
                    ['name' => 'Bacalao', 'quantity' => 320, 'unit' => 'g', 'category' => 'Pescado'],
                    ['name' => 'Tomate frito', 'quantity' => 250, 'unit' => 'g', 'category' => 'Salsa'],
                    ['name' => 'Cebolla', 'quantity' => 100, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Ajo', 'quantity' => 2, 'unit' => 'ud', 'category' => 'Verdura'],
                ],
            ],
            [
                'name' => 'Ensalada de couscous',
                'description' => 'Couscous con tomate, pepino, garbanzos y hierbas.',
                'time' => 18,
                'tags' => 'ensalada,cereal,fresca',
                'ingredients' => [
                    ['name' => 'Couscous', 'quantity' => 220, 'unit' => 'g', 'category' => 'Cereal'],
                    ['name' => 'Tomate', 'quantity' => 160, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pepino', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Garbanzos', 'quantity' => 180, 'unit' => 'g', 'category' => 'Legumbre'],
                ],
            ],
            [
                'name' => 'Wok de ternera',
                'description' => 'Ternera salteada con verduras y arroz en pocos minutos.',
                'time' => 22,
                'tags' => 'ternera,wok,rapida',
                'ingredients' => [
                    ['name' => 'Ternera', 'quantity' => 280, 'unit' => 'g', 'category' => 'Carne'],
                    ['name' => 'Arroz', 'quantity' => 180, 'unit' => 'g', 'category' => 'Cereal'],
                    ['name' => 'Zanahoria', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Pimiento', 'quantity' => 120, 'unit' => 'g', 'category' => 'Verdura'],
                ],
            ],
            [
                'name' => 'Fajitas vegetales',
                'description' => 'Fajitas de verduras salteadas con queso y especias.',
                'time' => 20,
                'tags' => 'vegetal,rapida,cena',
                'ingredients' => [
                    ['name' => 'Pan', 'quantity' => 4, 'unit' => 'ud', 'category' => 'Paneria'],
                    ['name' => 'Pimiento', 'quantity' => 180, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Cebolla', 'quantity' => 140, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Queso', 'quantity' => 80, 'unit' => 'g', 'category' => 'Lacteo'],
                ],
            ],
            [
                'name' => 'Merluza con patata',
                'description' => 'Merluza al vapor con patata, limon y aceite de oliva.',
                'time' => 27,
                'tags' => 'pescado,ligera,vapor',
                'ingredients' => [
                    ['name' => 'Merluza', 'quantity' => 320, 'unit' => 'g', 'category' => 'Pescado'],
                    ['name' => 'Patata', 'quantity' => 320, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Limon', 'quantity' => 1, 'unit' => 'ud', 'category' => 'Fruta'],
                    ['name' => 'Aceite de oliva', 'quantity' => 20, 'unit' => 'ml', 'category' => 'Aceite'],
                ],
            ],
            [
                'name' => 'Bowl de arroz y pollo',
                'description' => 'Arroz, pollo, tomate y lechuga en formato bowl.',
                'time' => 24,
                'tags' => 'bowl,pollo,arroz',
                'ingredients' => [
                    ['name' => 'Arroz', 'quantity' => 220, 'unit' => 'g', 'category' => 'Cereal'],
                    ['name' => 'Pollo', 'quantity' => 260, 'unit' => 'g', 'category' => 'Carne'],
                    ['name' => 'Tomate', 'quantity' => 140, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Lechuga', 'quantity' => 90, 'unit' => 'g', 'category' => 'Verdura'],
                ],
            ],
            [
                'name' => 'Sopa de pollo',
                'description' => 'Sopa reconfortante con pollo, fideos y verduras.',
                'time' => 35,
                'tags' => 'sopa,pollo,caliente',
                'ingredients' => [
                    ['name' => 'Pollo', 'quantity' => 220, 'unit' => 'g', 'category' => 'Carne'],
                    ['name' => 'Macarrones', 'quantity' => 120, 'unit' => 'g', 'category' => 'Pasta'],
                    ['name' => 'Zanahoria', 'quantity' => 110, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Agua', 'quantity' => 1000, 'unit' => 'ml', 'category' => 'Bebida'],
                ],
            ],
            [
                'name' => 'Tostas de aguacate',
                'description' => 'Pan tostado con aguacate, tomate y huevo.',
                'time' => 10,
                'tags' => 'desayuno,rapida,tosta',
                'ingredients' => [
                    ['name' => 'Pan', 'quantity' => 2, 'unit' => 'ud', 'category' => 'Paneria'],
                    ['name' => 'Aguacate', 'quantity' => 1, 'unit' => 'ud', 'category' => 'Fruta'],
                    ['name' => 'Tomate', 'quantity' => 80, 'unit' => 'g', 'category' => 'Verdura'],
                    ['name' => 'Huevo', 'quantity' => 1, 'unit' => 'ud', 'category' => 'Lacteo'],
                ],
            ],
        ];
    }
}
