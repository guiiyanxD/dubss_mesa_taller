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
            //$table->unsignedBigInteger('id_formulario_socio_economico');
            $table->unsignedBigInteger('id_lugar_procedencia');
            $table->unsignedBigInteger('id_residencia');
            $table->unsignedBigInteger('id_infraestructura');
            $table->unsignedBigInteger('id_dependencia_economica');
            $table->unsignedBigInteger('id_tenencia_vivienda');
            $table->unsignedBigInteger('id_grupo_familiar');

            $table->boolean('tiene_otro_beneficio')->default(false);
            $table->text('comentario_otro_beneficio')->nullable();
            $table->decimal('puntaje_otro_beneficio', 5, 2)->nullable();
            $table->boolean('es_discapacitado')->default(false);
            $table->text('comentario_discapacitado')->nullable();
            $table->decimal('puntaje_discapacitado', 5, 2)->nullable();
            $table->text('comentario_personal')->nullable();
            $table->timestamps();


            /*
            $table->foreign('id_formulario_socio_economico')
                  ->references('id')
                  ->on('formulario_socio_economico')
                  ->onDelete('set null');
            */

            $table->foreign('id_lugar_procedencia')
                  ->references('id')
                  ->on('lugar_procedencia')
                  ->onDelete('set null');

            $table->foreign('id_residencia')
                  ->references('id')
                  ->on('residencia')
                  ->onDelete('set null');

            $table->foreign('id_infraestructura')
                  ->references('id')
                  ->on('infraestructura')
                  ->onDelete('cascade');

            $table->foreign('id_dependencia_economica')
                  ->references('id')
                  ->on('dependencia_economica')
                  ->onDelete('set null');

            $table->foreign('id_tenencia_vivienda')
                  ->references('id')
                  ->on('tenencia_vivienda')
                  ->onDelete('set null');

            $table->foreign('id_grupo_familiar')
                ->references('id')
                ->on('grupo_familiar')
                ->onDelete('set null');

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
