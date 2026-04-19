<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public const CATEGORIES = [
        'Carne', 'Pescado', 'Marisco', 'Lacteo', 'Verdura', 'Fruta',
        'Legumbre', 'Setas', 'Cereal', 'Pasta', 'Paneria', 'Especia',
        'Salsa', 'Aceite', 'Picante', 'Bebida', 'Infusion'
    ];
}
