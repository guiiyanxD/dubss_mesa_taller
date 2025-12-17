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
        Schema::create('formulario_socio_economico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('validado_por')->nullable();
            $table->date('fecha_llenado');
            $table->boolean('completado')->default(false);
            $table->timestamps();

            $table->foreign('id_estudiante')
                  ->references('id_usuario')
                  ->on('estudiante')
                  ->onDelete('cascade');

            $table->foreign('validado_por')
                  ->references('id_usuario')
                  ->on('personal_administrativo')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario_socio_economico');
    }
};
