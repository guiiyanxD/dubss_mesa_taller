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

    public function obtenerPuntajeTotal()
    {
        $puntajeTotal = 0;

        // 1. Puntaje de lugar de procedencia
        if ($this->lugarProcedencia && $this->lugarProcedencia->puntaje) {
            $puntajeTotal += $this->lugarProcedencia->puntaje;
        }

        // 2. Puntaje de residencia
        if ($this->residencia && $this->residencia->puntaje_total) {
            $puntajeTotal += $this->residencia->puntaje_total;
        }

        // 3. Puntaje de infraestructura
        if ($this->infraestructura && $this->infraestructura->puntaje_total) {
            $puntajeTotal += $this->infraestructura->puntaje_total;
        }

        // 4. Puntaje de dependencia económica
        if ($this->dependenciaEconomica) {
            // Si tiene puntaje_total, usarlo, si no calcularlo
            $puntajeDependencia = $this->dependenciaEconomica->puntaje_total
                ?: $this->calcularPuntajeDependenciaEconomica();
            $puntajeTotal += $puntajeDependencia;
        }

        // 5. Puntaje de tenencia de vivienda
        if ($this->tenenciaVivienda && $this->tenenciaVivienda->puntaje_total) {
            $puntajeTotal += $this->tenenciaVivienda->puntaje_total;
        }

        // 6. Puntaje de grupo familiar
        if ($this->grupoFamiliar && $this->grupoFamiliar->puntaje_total) {
            $puntajeTotal += $this->grupoFamiliar->puntaje_total;
        }

        // 7. Puntaje de otro beneficio (si aplica)
        if ($this->tiene_otro_beneficio && $this->puntaje_otro_beneficio) {
            $puntajeTotal += $this->puntaje_otro_beneficio;
        }

        // 8. Puntaje de discapacidad (si aplica)
        if ($this->es_discapacitado && $this->puntaje_discapacitado) {
            $puntajeTotal += $this->puntaje_discapacitado;
        }

        return round($puntajeTotal, 2);
    }

    /**
     * Calcular puntaje de dependencia económica
     */
    private function calcularPuntajeDependenciaEconomica()
    {
        $puntaje = 0;

        if (!$this->dependenciaEconomica) {
            return $puntaje;
        }

        // Puntaje base del tipo de dependencia
        if ($this->dependenciaEconomica->tipoDependencia &&
            $this->dependenciaEconomica->tipoDependencia->puntaje) {
            $puntaje += $this->dependenciaEconomica->tipoDependencia->puntaje;
        }

        // Puntaje del tipo de ocupación
        if ($this->dependenciaEconomica->tipoOcupacion &&
            $this->dependenciaEconomica->tipoOcupacion->puntaje) {
            $puntaje += $this->dependenciaEconomica->tipoOcupacion->puntaje;
        }

        // Puntaje del rango de ingreso
        if ($this->dependenciaEconomica->rangoIngreso &&
            $this->dependenciaEconomica->rangoIngreso->puntaje) {
            $puntaje += $this->dependenciaEconomica->rangoIngreso->puntaje;
        }

        return round($puntaje, 2);
    }

    /**
     * Obtener desglose detallado del puntaje
     */
    public function obtenerDesglosePuntaje()
    {
        $desglose = [];

        // 1. Lugar de procedencia
        $puntajeLugar = $this->lugarProcedencia ? ($this->lugarProcedencia->puntaje ?? 0) : 0;
        $desglose[] = [
            'concepto' => 'Lugar de Procedencia',
            'valor' => $puntajeLugar,
            'detalle' => $this->lugarProcedencia ? $this->lugarProcedencia->nombre_lugar : 'No especificado'
        ];

        // 2. Residencia
        $puntajeResidencia = $this->residencia ? ($this->residencia->puntaje_total ?? 0) : 0;
        $desglose[] = [
            'concepto' => 'Características de Residencia',
            'valor' => $puntajeResidencia,
            'detalle' => 'Evaluación de ubicación y características'
        ];

        // 3. Infraestructura
        $puntajeInfraestructura = $this->infraestructura ? ($this->infraestructura->puntaje_total ?? 0) : 0;
        $desglose[] = [
            'concepto' => 'Infraestructura de Vivienda',
            'valor' => $puntajeInfraestructura,
            'detalle' => 'Habitaciones, baños, espacios comunes'
        ];

        // 4. Dependencia económica
        $puntajeDependencia = $this->calcularPuntajeDependenciaEconomica();
        $desglose[] = [
            'concepto' => 'Situación Económica',
            'valor' => $puntajeDependencia,
            'detalle' => 'Dependencia económica e ingresos'
        ];

        // 5. Tenencia de vivienda
        $puntajeTenencia = $this->tenenciaVivienda ? ($this->tenenciaVivienda->puntaje_total ?? 0) : 0;
        $desglose[] = [
            'concepto' => 'Tenencia de Vivienda',
            'valor' => $puntajeTenencia,
            'detalle' => $this->tenenciaVivienda ? $this->tenenciaVivienda->tipoTenencia->nombre ?? 'No especificado' : 'No especificado'
        ];

        // 6. Grupo familiar
        $puntajeGrupo = $this->grupoFamiliar ? ($this->grupoFamiliar->puntaje_total ?? 0) : 0;
        $desglose[] = [
            'concepto' => 'Composición Familiar',
            'valor' => $puntajeGrupo,
            'detalle' => $this->grupoFamiliar ?
                "{$this->grupoFamiliar->cantidad_familiares} miembros" :
                'No especificado'
        ];

        // 7. Otro beneficio
        if ($this->tiene_otro_beneficio && $this->puntaje_otro_beneficio) {
            $desglose[] = [
                'concepto' => 'Otro Beneficio Social',
                'valor' => $this->puntaje_otro_beneficio,
                'detalle' => $this->comentario_otro_beneficio ?: 'Beneficio adicional'
            ];
        }

        // 8. Discapacidad
        if ($this->es_discapacitado && $this->puntaje_discapacitado) {
            $desglose[] = [
                'concepto' => 'Discapacidad',
                'valor' => $this->puntaje_discapacitado,
                'detalle' => $this->comentario_discapacitado ?: 'Condición especial'
            ];
        }

        // Calcular total
        $total = array_sum(array_column($desglose, 'valor'));

        return [
            'desglose' => $desglose,
            'total' => round($total, 2),
            'categoria' => $this->determinarCategoria($total)
        ];
    }

    /**
     * Determinar categoría según puntaje
     */
    private function determinarCategoria($puntajeTotal)
    {
        if ($puntajeTotal >= 40) {
            return 'ALTA NECESIDAD';
        } elseif ($puntajeTotal >= 25) {
            return 'MEDIA NECESIDAD';
        } elseif ($puntajeTotal >= 15) {
            return 'BAJA NECESIDAD';
        } else {
            return 'SIN NECESIDAD APARENTE';
        }
    }

    /**
     * Obtener resumen ejecutivo del puntaje
     */
    public function obtenerResumenPuntaje()
    {
        $desglose = $this->obtenerDesglosePuntaje();

        return [
            'puntaje_total' => $desglose['total'],
            'categoria' => $desglose['categoria'],
            'fecha_calculo' => now()->format('d/m/Y H:i:s'),
            'componentes_evaluados' => count($desglose['desglose']),
            'puntaje_maximo_posible' => 100.00,
            'porcentaje' => round(($desglose['total'] / 100) * 100, 2),
            'top_3_componentes' => $this->obtenerTop3Componentes($desglose['desglose'])
        ];
    }

    /**
     * Obtener los 3 componentes con mayor puntaje
     */
    private function obtenerTop3Componentes($desglose)
    {
        usort($desglose, function($a, $b) {
            return $b['valor'] <=> $a['valor'];
        });

        return array_slice($desglose, 0, 3);
    }
}
