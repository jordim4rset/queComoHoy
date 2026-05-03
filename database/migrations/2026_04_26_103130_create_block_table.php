<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('bloquea', function (Blueprint $table) {
        $table->integer('id_bloqueador');
        $table->integer('id_bloqueado');
    });
}

public function down()
{
    Schema::dropIfExists('bloquea');
}
};

//He hecho otra migracion porque el nombre estaba en español y debe de ser en ingles.
