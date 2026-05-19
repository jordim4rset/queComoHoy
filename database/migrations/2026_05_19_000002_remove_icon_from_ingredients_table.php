<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('ingredients', 'icon')) {
            Schema::table('ingredients', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('ingredients', 'icon')) {
            Schema::table('ingredients', function (Blueprint $table) {
                $table->string('icon')->default('img/ingredientes/cover/default.png')->after('normalized_name');
            });
        }
    }
};
