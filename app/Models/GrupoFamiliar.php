<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoFamiliar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grupo_familiar';

    protected $fillable = [
        'cantidad_hijos',
        'cantidad_familiares',
        'tiene_hijos',
        'puntaje',
        'puntaje_total'
    ];

    protected $casts = [
        'cantidad_hijos' => 'integer',
        'cantidad_familiares' => 'integer',
        'tiene_hijos' => 'boolean',
        'puntaje' => 'decimal:2',
        'puntaje_total' => 'decimal:2'
    ];

    protected $attributes = [
        'cantidad_hijos' => 0,
        'tiene_hijos' => false
    ];

    /**
     * Hook para actualizar tiene_hijos automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Actualiza tiene_hijos basado en cantidad_hijos
            $model->tiene_hijos = $model->cantidad_hijos > 0;

            // Si no se proporciona puntaje_total, calcularlo
            if (empty($model->puntaje_total) && !is_null($model->puntaje)) {
                $model->puntaje_total = $model->calcularPuntajeTotal();
            }
        });

        static::created(function ($model) {
            // Si no se proporcionó puntaje_total al crear, calcularlo
            if (empty($model->puntaje_total) && !is_null($model->puntaje)) {
                $model->update(['puntaje_total' => $model->calcularPuntajeTotal()]);
            }
        });
    }

    /**
     * Calcular el puntaje total basado en cantidad de familiares e hijos
     */
    public function calcularPuntajeTotal()
    {
        $puntajeBase = $this->puntaje ?? 0;

        // Puedes agregar lógica adicional aquí basada en cantidad_familiares
        // Por ejemplo: penalización por muchos familiares o bonificación por pocos
        $factorFamiliares = $this->calcularFactorFamiliares();

        return round($puntajeBase * $factorFamiliares, 2);
    }

    /**
     * Factor basado en cantidad de familiares
     * Ejemplo: menos familiares = mejor puntaje
     */
    private function calcularFactorFamiliares()
    {
        if ($this->cantidad_familiares <= 3) {
            return 1.2; // Bonificación para familias pequeñas
        } elseif ($this->cantidad_familiares <= 5) {
            return 1.0; // Factor neutro
        } elseif ($this->cantidad_familiares <= 8) {
            return 0.8; // Penalización ligera
        } else {
            return 0.6; // Penalización fuerte para familias grandes
        }
    }

    /**
     * Calcular puntaje sugerido basado en cantidad de hijos y familiares
     */
    public function calcularPuntajeSugerido()
    {
        $puntaje = 10.00; // Puntaje base máximo

        // Reducción por cantidad de hijos
        $reduccionHijos = $this->cantidad_hijos * 0.5;
        $puntaje -= $reduccionHijos;

        // Reducción por cantidad de familiares
        if ($this->cantidad_familiares > 5) {
            $familiaresExtra = $this->cantidad_familiares - 5;
            $puntaje -= ($familiaresExtra * 0.3);
        }

        // Asegurar que no sea negativo
        return max(0, round($puntaje, 2));
    }

    /**
     * Obtener resumen del grupo familiar
     */
    public function getResumenAttribute()
    {
        $resumen = [];

        $resumen[] = "Familiares: {$this->cantidad_familiares}";

        if ($this->tiene_hijos) {
            $resumen[] = "Hijos: {$this->cantidad_hijos}";
        } else {
            $resumen[] = "Sin hijos";
        }

        if ($this->puntaje_total) {
            $resumen[] = "Puntaje: {$this->puntaje_total}";
        }

        return implode(' • ', $resumen);
    }

    /**
     * Determinar categoría del grupo familiar
     */
    public function getCategoriaAttribute()
    {
        if ($this->cantidad_familiares <= 3) {
            return 'Familia Pequeña';
        } elseif ($this->cantidad_familiares <= 5) {
            return 'Familia Mediana';
        } elseif ($this->cantidad_familiares <= 8) {
            return 'Familia Grande';
        } else {
            return 'Familia Muy Grande';
        }
    }

    /**
     * Verificar si el grupo familiar es extenso
     */
    public function getEsFamiliaExtensaAttribute()
    {
        return $this->cantidad_familiares > 5;
    }

    /**
     * Obtener promedio de hijos por familia
     */
    public function getPromedioHijosPorFamiliaAttribute()
    {
        if ($this->cantidad_familiares > 0) {
            return round($this->cantidad_hijos / $this->cantidad_familiares, 2);
        }
        return 0;
    }

    /**
     * Scope para familias con hijos
     */
    public function scopeConHijos($query)
    {
        return $query->where('tiene_hijos', true);
    }

    /**
     * Scope para familias sin hijos
     */
    public function scopeSinHijos($query)
    {
        return $query->where('tiene_hijos', false);
    }

    /**
     * Scope para familias por tamaño
     */
    public function scopePorTamanio($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('cantidad_familiares', '>=', $min);
        }

        if ($max !== null) {
            $query->where('cantidad_familiares', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope para familias pequeñas (1-3 miembros)
     */
    public function scopePequeñas($query)
    {
        return $query->whereBetween('cantidad_familiares', [1, 3]);
    }

    /**
     * Scope para familias medianas (4-5 miembros)
     */
    public function scopeMedianas($query)
    {
        return $query->whereBetween('cantidad_familiares', [4, 5]);
    }

    /**
     * Scope para familias grandes (6+ miembros)
     */
    public function scopeGrandes($query)
    {
        return $query->where('cantidad_familiares', '>=', 6);
    }

    /**
     * Relación con respuestas de formulario
     */
    public function respuestasFormulario()
    {
        return $this->hasMany(RespuestaFormulario::class, 'id_grupo_familiar');
    }
}
