<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bloqueador')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_bloqueado')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_bloqueador', 'id_bloqueado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
