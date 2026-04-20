<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            // Carne
            ['name' => 'Pollo', 'category' => 'Carne', 'icon' => 'icono'],
            ['name' => 'Ternera', 'category' => 'Carne', 'icon' => 'icono'],
            ['name' => 'Cerdo', 'category' => 'Carne', 'icon' => 'icono'],
            ['name' => 'Cordero', 'category' => 'Carne', 'icon' => 'icono'],

            // Pescado
            ['name' => 'Salmón', 'category' => 'Pescado', 'icon' => 'icono'],
            ['name' => 'Atún', 'category' => 'Pescado', 'icon' => 'icono'],
            ['name' => 'Merluza', 'category' => 'Pescado', 'icon' => 'icono'],
            ['name' => 'Bacalao', 'category' => 'Pescado', 'icon' => 'icono'],

            // Marisco
            ['name' => 'Gambas', 'category' => 'Marisco', 'icon' => 'icono'],
            ['name' => 'Mejillones', 'category' => 'Marisco', 'icon' => 'icono'],
            ['name' => 'Calamar', 'category' => 'Marisco', 'icon' => 'icono'],

            // Lacteo
            ['name' => 'Leche', 'category' => 'Lacteo', 'icon' => 'icono'],
            ['name' => 'Queso', 'category' => 'Lacteo', 'icon' => 'icono'],
            ['name' => 'Mantequilla', 'category' => 'Lacteo', 'icon' => 'icono'],
            ['name' => 'Yogur', 'category' => 'Lacteo', 'icon' => 'icono'],

            // Verdura
            ['name' => 'Tomate', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Cebolla', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Ajo', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Zanahoria', 'category' => 'Verdura', 'icon' => 'icono'],
            ['name' => 'Pimiento', 'category' => 'Verdura', 'icon' => 'icono'],

            // Fruta
            ['name' => 'Limón', 'category' => 'Fruta', 'icon' => 'icono'],
            ['name' => 'Naranja', 'category' => 'Fruta', 'icon' => 'icono'],
            ['name' => 'Manzana', 'category' => 'Fruta', 'icon' => 'icono'],

            // Legumbre
            ['name' => 'Garbanzos', 'category' => 'Legumbre', 'icon' => 'icono'],
            ['name' => 'Lentejas', 'category' => 'Legumbre', 'icon' => 'icono'],
            ['name' => 'Alubias', 'category' => 'Legumbre', 'icon' => 'icono'],

            // Setas
            ['name' => 'Champiñones', 'category' => 'Setas', 'icon' => 'icono'],
            ['name' => 'Setas Portobello', 'category' => 'Setas', 'icon' => 'icono'],

            // Cereal
            ['name' => 'Arroz', 'category' => 'Cereal', 'icon' => 'icono'],
            ['name' => 'Avena', 'category' => 'Cereal', 'icon' => 'icono'],

            // Pasta
            ['name' => 'Espaguetis', 'category' => 'Pasta', 'icon' => 'icono'],
            ['name' => 'Macarrones', 'category' => 'Pasta', 'icon' => 'icono'],
            ['name' => 'Lasaña', 'category' => 'Pasta', 'icon' => 'icono'],

            // Panaderia
            ['name' => 'Pan', 'category' => 'Paneria', 'icon' => 'icono'],
            ['name' => 'Pan rallado', 'category' => 'Paneria', 'icon' => 'icono'],

            // Especia
            ['name' => 'Sal', 'category' => 'Especia', 'icon' => 'icono'],
            ['name' => 'Pimienta', 'category' => 'Especia', 'icon' => 'icono'],
            ['name' => 'Orégano', 'category' => 'Especia', 'icon' => 'icono'],
            ['name' => 'Comino', 'category' => 'Especia', 'icon' => 'icono'],

            // Salsa
            ['name' => 'Tomate frito', 'category' => 'Salsa', 'icon' => 'icono'],
            ['name' => 'Mayonesa', 'category' => 'Salsa', 'icon' => 'icono'],
            ['name' => 'Ketchup', 'category' => 'Salsa', 'icon' => 'icono'],

            // Aceite
            ['name' => 'Aceite de oliva', 'category' => 'Aceite', 'icon' => 'icono'],
            ['name' => 'Aceite de girasol', 'category' => 'Aceite', 'icon' => 'icono'],

            // Picante
            ['name' => 'Guindilla', 'category' => 'Picante', 'icon' => 'icono'],
            ['name' => 'Jalapeño', 'category' => 'Picante', 'icon' => 'icono'],

            // Bebida
            ['name' => 'Vino blanco', 'category' => 'Bebida', 'icon' => 'icono'],
            ['name' => 'Vino tinto', 'category' => 'Bebida', 'icon' => 'icono'],
            ['name' => 'Cerveza', 'category' => 'Bebida', 'icon' => 'icono'],

            // Infusion
            ['name' => 'Té verde', 'category' => 'Infusion', 'icon' => 'icono'],
            ['name' => 'Manzanilla', 'category' => 'Infusion', 'icon' => 'icono'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
