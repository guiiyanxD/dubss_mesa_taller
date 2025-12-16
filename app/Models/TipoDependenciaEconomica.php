<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDependenciaEconomica extends Model
{
    use HasFactory;

    protected $table = 'tipo_dependencia_economica';

    protected $fillable = [
        'nombre',
        'puntaje',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'puntaje' => 'decimal:2',
    ];
}
