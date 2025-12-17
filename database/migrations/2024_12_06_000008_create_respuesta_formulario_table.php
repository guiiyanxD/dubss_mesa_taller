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
        Schema::create('respuesta_formulario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_formulario_socio_economico');
            $table->timestamps();




            $table->foreign('id_formulario_socio_economico')
                  ->references('id')
                  ->on('formulario_socio_economico')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuesta_formulario');
    }
};
