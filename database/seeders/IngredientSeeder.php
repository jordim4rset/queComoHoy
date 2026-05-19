<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Pollo', 'category' => 'Carne'],
            ['name' => 'Ternera', 'category' => 'Carne'],
            ['name' => 'Cerdo', 'category' => 'Carne'],
            ['name' => 'Salmon', 'category' => 'Pescado'],
            ['name' => 'Atun', 'category' => 'Pescado'],
            ['name' => 'Bacalao', 'category' => 'Pescado'],
            ['name' => 'Gambas', 'category' => 'Marisco'],
            ['name' => 'Mejillones', 'category' => 'Marisco'],
            ['name' => 'Leche', 'category' => 'Lacteo'],
            ['name' => 'Queso', 'category' => 'Lacteo'],
            ['name' => 'Yogur', 'category' => 'Lacteo'],
            ['name' => 'Tomate', 'category' => 'Verdura'],
            ['name' => 'Cebolla', 'category' => 'Verdura'],
            ['name' => 'Ajo', 'category' => 'Verdura'],
            ['name' => 'Zanahoria', 'category' => 'Verdura'],
            ['name' => 'Lechuga', 'category' => 'Verdura'],
            ['name' => 'Manzana', 'category' => 'Fruta'],
            ['name' => 'Platano', 'category' => 'Fruta'],
            ['name' => 'Naranja', 'category' => 'Fruta'],
            ['name' => 'Lentejas', 'category' => 'Legumbre'],
            ['name' => 'Garbanzos', 'category' => 'Legumbre'],
            ['name' => 'Alubias', 'category' => 'Legumbre'],
            ['name' => 'Champiñones', 'category' => 'Setas'],
            ['name' => 'Arroz', 'category' => 'Cereal'],
            ['name' => 'Macarrones', 'category' => 'Pasta'],
            ['name' => 'Espaguetis', 'category' => 'Pasta'],
            ['name' => 'Pan', 'category' => 'Paneria'],
            ['name' => 'Sal', 'category' => 'Especia'],
            ['name' => 'Pimienta', 'category' => 'Especia'],
            ['name' => 'Oregano', 'category' => 'Especia'],
            ['name' => 'Tomate frito', 'category' => 'Salsa'],
            ['name' => 'Mayonesa', 'category' => 'Salsa'],
            ['name' => 'Aceite de oliva', 'category' => 'Aceite'],
            ['name' => 'Agua', 'category' => 'Bebida'],
            ['name' => 'Cafe', 'category' => 'Infusion'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
