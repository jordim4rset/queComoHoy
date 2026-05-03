<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Pollo', 'category' => 'Carne', 'icon' => 'icono'],
            ['name' => 'Ternera', 'category' => 'Carne', 'icon' => 'icono'],
            ['name' => 'Cerdo', 'category' => 'Carne', 'icon' => 'icono'],
            ['name' => 'Salmon', 'category' => 'Pescado', 'icon' => 'icono'],
            ['name' => 'Atun', 'category' => 'Pescado', 'icon' => 'icono'],
            ['name' => 'Bacalao', 'category' => 'Pescado', 'icon' => 'icono'],
            ['name' => 'Gambas', 'category' => 'Marisco', 'icon' => 'icono'],
            ['name' => 'Mejillones', 'category' => 'Marisco', 'icon' => 'icono'],
            ['name' => 'Leche', 'category' => 'Lacteo', 'icon' => 'icono'],
            ['name' => 'Queso', 'category' => 'Lacteo', 'icon' => 'icono'],
            ['name' => 'Yogur', 'category' => 'Lacteo', 'icon' => 'icono'],
            ['name' => 'Tomate', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Cebolla', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Ajo', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Zanahoria', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Lechuga', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Manzana', 'category' => 'Fruta', 'icon' => 'icono'],
            ['name' => 'Platano', 'category' => 'Fruta', 'icon' => 'icono'],
            ['name' => 'Naranja', 'category' => 'Fruta', 'icon' => 'icono'],
            ['name' => 'Lentejas', 'category' => 'Legumbre', 'icon' => 'icono'],
            ['name' => 'Garbanzos', 'category' => 'Legumbre', 'icon' => 'icono'],
            ['name' => 'Alubias', 'category' => 'Legumbre', 'icon' => 'icono'],
            ['name' => 'Champiñones', 'category' => 'Setas', 'icon' => 'icono'],
            ['name' => 'Arroz', 'category' => 'Cereal', 'icon' => 'icono'],
            ['name' => 'Macarrones', 'category' => 'Pasta', 'icon' => 'icono'],
            ['name' => 'Espaguetis', 'category' => 'Pasta', 'icon' => 'icono'],
            ['name' => 'Pan', 'category' => 'Paneria', 'icon' => 'icono'],
            ['name' => 'Sal', 'category' => 'Especia', 'icon' => 'icono'],
            ['name' => 'Pimienta', 'category' => 'Especia', 'icon' => 'icono'],
            ['name' => 'Oregano', 'category' => 'Especia', 'icon' => 'icono'],
            ['name' => 'Tomate frito', 'category' => 'Salsa', 'icon' => 'icono'],
            ['name' => 'Mayonesa', 'category' => 'Salsa', 'icon' => 'icono'],
            ['name' => 'Aceite de oliva', 'category' => 'Aceite', 'icon' => 'icono'],
            ['name' => 'Agua', 'category' => 'Bebida', 'icon' => 'icono'],
            ['name' => 'Cafe', 'category' => 'Infusion', 'icon' => 'icono'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
