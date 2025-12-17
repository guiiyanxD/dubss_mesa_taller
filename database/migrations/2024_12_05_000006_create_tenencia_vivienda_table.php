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
        Schema::create('tenencia_vivienda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_tenencia');
            $table->decimal('puntaje', 5, 2)->nullable();
            $table->decimal('puntaje_total', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_tipo_tenencia')
                  ->references('id')
                  ->on('tipo_tenencia_vivienda')
                  ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenencia_vivienda');
    }
};
