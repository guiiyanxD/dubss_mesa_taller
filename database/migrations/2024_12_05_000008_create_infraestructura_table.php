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
        Schema::create('infraestructura', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad_dormitorios');
            $table->integer('cantidad_banhos');
            $table->integer('cantidad_salas');
            $table->integer('cantidad_comedor');
            $table->integer('cantidad_patios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infraestructura');
    }
};
