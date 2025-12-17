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
        Schema::create('dependencia_economica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_ocupacion_dependencia');
            $table->unsignedBigInteger('id_dependencia_economica');
            $table->unsignedBigInteger('id_rango_ingreso_economico');
            $table->string('ocupacion', 100);
            $table->decimal('puntaje', 3, 1)->nullable();
            $table->decimal('puntaje_total', 3, 1)->nullable();
            $table->timestamps();

            $table->foreign('id_tipo_ocupacion_dependencia')
                  ->references('id')
                  ->on('tipo_ocupacion_dependiente')
                  ->onDelete('set null');

            $table->foreign('id_dependencia_economica')
                ->references('id')
                ->on('tipo_dependencia_economica')
                ->onDelete('set null');

            $table->foreign('id_rango_ingreso_economico')
                ->references('id')
                ->on('tipo_dependencia_economica')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependencia_economica');
    }


};
