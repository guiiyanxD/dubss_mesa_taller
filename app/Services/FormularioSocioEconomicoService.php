<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\FormularioSocioEconomico;
use App\Models\Postulacion;
use App\Models\Tramite;
use App\Models\User;
use App\Models\DependenciaEconomica;
use App\Models\Infraestructura;
use App\Models\Residencia;
use App\Models\RespuestaFormulario;
use Carbon\Carbon;
use App\Models\TenenciaVivienda;
use App\Models\GrupoFamiliar;
use App\Models\MiembroFamiliar;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FormularioSocioEconomicoService
{
    /**
     * Guarda el formulario completo y genera la postulación y el trámite.
     */
    public function registrarFormularioCompleto(array $datos): array
    {
        //return dd($datos);
        return DB::transaction(function () use ($datos) {


            /*$estudiante = Estudiante::whereHas('usuario', function ($query) use ($datos) {
                $query->where('ci', $datos['ci_estudiante']);
            })->first();

            if (!$estudiante) {
                throw ValidationException::withMessages([
                    'ci_estudiante' => "No se encontró un estudiante registrado con el CI: {$datos['ci_estudiante']}"
                ]);
            }*/

            $dependencia_economica = DependenciaEconomica::create([
                'id_tipo_ocupacion_dependencia' => $datos['economica']['tipos_ocupacion_dependiente'],
                'id_dependencia_economica' => $datos['economica']['tipo_dependencia'] ?? null,
                'id_rango_ingreso_economico' => $datos['economica']['rango_ingreso'] ?? null,
                'ocupacion' => $datos['economica']['ocupacion'] ?? null,
                'puntaje' => 0.00,
                'puntaje_total' => 0.00,
            ]);

            $infraestructura = Infraestructura::create([
                'cantidad_dormitorios' => $datos['residencia']['cant_dormitorios'] ?? 0,
                'cantidad_banhos' => $datos['residencia']['cant_banhos'] ?? 0,
                'cantidad_salas' => $datos['residencia']['cant_salas'] ?? 0,
                'cantidad_comedor' => $datos['residencia']['cant_comedor'] ?? 0,
                'cantidad_patios' => $datos['residencia']['cant_patios'] ?? 0,
                'puntaje_total' => 0.00,
            ]);

            $residenciaData = Residencia::create([
                'provincia' => $datos['residencia']['provincia'] ?? null,
                'zona' => $datos['residencia']['zona'] ?? null,
                'calle' => $datos['residencia']['calle'] ?? null,
                'barrio' => $datos['residencia']['barrio'] ?? null,
                'puntaje_total' => 0.00,
            ]);

            $tenenciaVivienda = TenenciaVivienda::create([
                'id_tipo_tenencia' => $datos['tenencia']['tipo_tenencia_vivienda'],
                'puntaje' => 0.00,
                'puntaje_total' => 0.00,
            ]);

            $grupoFamiliar = GrupoFamiliar::create([
                'tiene_hijos' => $datos['grupo_familiar']['tiene_hijos'] ?? false,
                'cantidad_hijos' => $datos['grupo_familiar']['cantidad_hijos'] ?? 0,
                'cantidad_familiares' => count($datos['grupo_familiar']['miembros']),
            ]);

            for( $i = 0; $i < count($datos['grupo_familiar']['miembros']); $i++) {
                MiembroFamiliar::create([
                    'id_grupo_familiar' => $grupoFamiliar->id,
                    'nombre_completo' => $datos['grupo_familiar']['miembros'][$i]['nombre_completo'],
                    'parentesco' => $datos['grupo_familiar']['miembros'][$i]['parentesco'],
                    'edad' => $datos['grupo_familiar']['miembros'][$i]['edad'],
                    'ocupacion' => $datos['grupo_familiar']['miembros'][$i]['ocupacion'] ?? null,
                    'observacion' => $datos['grupo_familiar']['miembros'][$i]['observacion'] ?? null,
                ]);

            }

            $respuestasFormulario = RespuestaFormulario::create([
                'id_lugar_procedencia' => $datos['lugar_procedencia'] ?? null,
                'id_residencia' => $residenciaData->id,
                'id_infraestructura' => $infraestructura->id,
                'id_dependencia_economica' => $dependencia_economica->id,
                'id_tenencia_vivienda' => $tenenciaVivienda->id,
                'id_grupo_familiar' => $grupoFamiliar->id,
                'tiene_otro_beneficio' => $datos['otro_beneficio'] ?? false,
                'comentario_otro_beneficio' => $datos['comentario_otro_beneficio'] ?? null,
                'puntaje_otro_beneficio' => 0.00,
                'es_discapacitado' => $datos['discapacidad'] ?? false,
                'comentario_discapacitado' => $datos['comentario_discapacidad'] ?? null,
                'puntaje_discapacitado' => 0.00,
                'comentario_personal' => $datos['comentario_personal'] ?? null,
            ]);

            // 2. Guardar Formulario Base
            $formulario = FormularioSocioEconomico::create([
                'id_estudiante' => auth()->user()->id,
                'fecha_llenado' => Carbon::now(),
                'telefono_referencia' => $datos['telefono_referencia'],
                'edad' => auth()->user()->fecha_nacimiento ? Carbon::parse(auth()->user()->fecha_nacimiento)->age : null,
                'completado' => true,
                'validado_por' => false,
                'id_respuesta_formulario' => $respuestasFormulario->id,
            ]);


            // ---------------------------------------------------------
            // 7. CREAR POSTULACIÓN AUTOMÁTICA
            // ---------------------------------------------------------

            // Verificamos si ya existe una postulación para esta convocatoria para no duplicar
            $existePostulacion = Postulacion::where('id_estudiante', auth()->user()->id)
                ->where('id_convocatoria', $datos['id_convocatoria'])
                ->exists();

            if ($existePostulacion) {
                 throw ValidationException::withMessages([
                    'general' => "El estudiante ya tiene una postulación registrada para esta convocatoria."
                ]);
            }

            $postulacion = Postulacion::create([
                'id_estudiante' => auth()->user()->id,
                'id_convocatoria' => $datos['id_convocatoria'],
                'id_formulario' => $formulario->id,
                'id_beca' => $datos['id_beca'],
                'fecha_postulacion' => now(),
                'estado_postulado' => 'PENDIENTE', // Estado inicial
            ]);

            // ---------------------------------------------------------
            // 8. CREAR TRÁMITE (Para que el operador lo vea)
            // ---------------------------------------------------------
            // Asumimos estado 1 = PENDIENTE según tus ejemplos anteriores
            $tramite = Tramite::create([
                'id_postulacion' => $postulacion->id,
                'codigo' => 'TRA-' . str_pad($postulacion->id, 6, '0', STR_PAD_LEFT), // Generador simple de código
                'fecha_creacion' => now(),
                'clasificado' => 'NO',
                'estado_actual' => 1, // 1: ID del estado PENDIENTE
            ]);

            Log::info("Formulario, Postulación y Trámite creados exitosamente para CI: {$datos['ci_estudiante']}");

            return [
                'formulario_id' => $formulario->id,
                'postulacion_id' => $postulacion->id,
                'tramite_id' => $tramite->id
            ];
        });
    }
}
