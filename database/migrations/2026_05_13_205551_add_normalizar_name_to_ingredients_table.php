<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ingredients', 'normalized_name')) {
            Schema::table('ingredients', function (Blueprint $table) {
                $table->string('normalized_name', 100)
                    ->nullable()
                    ->after('name');
            });
        }

        $usedNames = [];

        $ingredients = DB::table('ingredients')
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

        foreach ($ingredients as $ingredient) {
            $baseName = Str::of($ingredient->name)
                ->ascii()
                ->lower()
                ->squish()
                ->toString();

            if ($baseName === '') {
                $baseName = 'ingrediente-' . $ingredient->id;
            }

            $normalizedName = $baseName;

            if (in_array($normalizedName, $usedNames)) {
                $normalizedName = $baseName . '-' . $ingredient->id;
            }

            $usedNames[] = $normalizedName;

            DB::table('ingredients')
                ->where('id', $ingredient->id)
                ->update([
                    'normalized_name' => $normalizedName,
                ]);
        }

        Schema::table('ingredients', function (Blueprint $table) {
            $table->unique('normalized_name');
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropUnique(['normalized_name']);
            $table->dropColumn('normalized_name');
        });
    }
};
