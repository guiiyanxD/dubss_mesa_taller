<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LugarProcedencia extends Model
{
    use HasFactory;

    protected $table = 'lugar_procedencia';

    protected $fillable = [
        'nombre_lugar',
        'puntaje',
        'activo'
    ];

    protected $casts = [
        'puntaje' => 'decimal:2',
        'activo' => 'boolean'
    ];

    protected $attributes = [
        'activo' => true
    ];
}
