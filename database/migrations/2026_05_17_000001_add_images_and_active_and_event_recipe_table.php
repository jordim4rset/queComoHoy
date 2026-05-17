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
        Schema::table('events', function (Blueprint $table) {
            $table->string('name', 100)->nullable()->after('id');
            $table->json('images')->nullable()->after('description');
            $table->boolean('active')->default(false)->after('visibility');
        });

        Schema::create('event_recipe', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            $table->foreignId('recipe_id')
                ->constrained('recipes')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('images');
            $table->dropColumn('active');
            $table->dropColumn('name');
        });

        Schema::dropIfExists('event_recipe');
    }
};
