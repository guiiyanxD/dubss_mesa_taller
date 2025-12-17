<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaFormulario extends Model
{
    use HasFactory;
    protected $table = 'respuesta_formulario';

    protected $fillable = [
        // 'id_formulario_socio_economico',
        'id_lugar_procedencia',
        'id_residencia',
        'id_infraestructura',
        'id_dependencia_economica',
        'id_tenencia_vivienda',
        'id_grupo_familiar',
        'tiene_otro_beneficio',
        'comentario_otro_beneficio',
        'puntaje_otro_beneficio',
        'es_discapacitado',
        'comentario_discapacitado',
        'puntaje_discapacitado',
        'comentario_personal'
    ];

    protected $casts = [
        'tiene_otro_beneficio' => 'boolean',
        'es_discapacitado' => 'boolean',
        'puntaje_otro_beneficio' => 'decimal:2',
        'puntaje_discapacitado' => 'decimal:2'
    ];

    protected $attributes = [
        'tiene_otro_beneficio' => false,
        'es_discapacitado' => false
    ];

    /**
     * Relación con LugarProcedencia
     */
    public function lugarProcedencia()
    {
        return $this->belongsTo(LugarProcedencia::class, 'id_lugar_procedencia');
    }

    /**
     * Relación con Residencia
     */
    public function residencia()
    {
        return $this->belongsTo(Residencia::class, 'id_residencia');
    }

    /**
     * Relación con Infraestructura
     */
    public function infraestructura()
    {
        return $this->belongsTo(Infraestructura::class, 'id_infraestructura');
    }

    /**
     * Relación con DependenciaEconomica
     */
    public function dependenciaEconomica()
    {
        return $this->belongsTo(DependenciaEconomica::class, 'id_dependencia_economica');
    }

    /**
     * Relación con TenenciaVivienda
     */
    public function tenenciaVivienda()
    {
        return $this->belongsTo(TenenciaVivienda::class, 'id_tenencia_vivienda');
    }

    /**
     * Relación con GrupoFamiliar
     */
    public function grupoFamiliar()
    {
        return $this->belongsTo(GrupoFamiliar::class, 'id_grupo_familiar');
    }

    /**
     * Calcular puntaje total
     */
    public function calcularPuntajeTotal()
    {
        $total = 0;

        // Puntaje de lugar de procedencia
        if ($this->lugarProcedencia) {
            $total += $this->lugarProcedencia->puntaje;
        }

        // Puntaje de residencia
        if ($this->residencia) {
            $total += $this->residencia->puntaje ?? 0;
        }

        // Puntaje de infraestructura
        if ($this->infraestructura) {
            $total += $this->infraestructura->puntaje ?? 0;
        }

        // Puntaje de dependencia económica
        if ($this->dependenciaEconomica) {
            $total += $this->dependenciaEconomica->puntaje ?? 0;
        }

        // Puntaje de tenencia de vivienda
        if ($this->tenenciaVivienda) {
            $total += $this->tenenciaVivienda->puntaje ?? 0;
        }

        // Puntaje de grupo familiar
        if ($this->grupoFamiliar) {
            $total += $this->grupoFamiliar->puntaje ?? 0;
        }

        // Puntaje de otro beneficio
        if ($this->puntaje_otro_beneficio) {
            $total += $this->puntaje_otro_beneficio;
        }

        // Puntaje de discapacidad
        if ($this->puntaje_discapacitado) {
            $total += $this->puntaje_discapacitado;
        }

        return round($total, 2);
    }

    /**
     * Obtener resumen de respuestas
     */
    public function getResumenAttribute()
    {
        $resumen = [];

        if ($this->lugarProcedencia) {
            $resumen[] = "Lugar: " . $this->lugarProcedencia->nombre_lugar . " (" . $this->lugarProcedencia->puntaje . " pts)";
        }

        if ($this->es_discapacitado) {
            $resumen[] = "Discapacidad: Sí (" . ($this->puntaje_discapacitado ?? 0) . " pts)";
        }

        if ($this->tiene_otro_beneficio) {
            $resumen[] = "Otro beneficio: Sí (" . ($this->puntaje_otro_beneficio ?? 0) . " pts)";
        }

        return $resumen;
    }
}
