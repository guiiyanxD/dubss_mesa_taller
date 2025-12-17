<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\{
    DependenciaEconomica,
    Infraestructura,
    Residencia,
    TenenciaVivienda,
    GrupoFamiliar,
    MiembroFamiliar,
    RespuestaFormulario,
    FormularioSocioEconomico,
    Postulacion,
    Tramite,
    LugarProcedencia,
    TipoOcupacionDependiente,
    TipoDependenciaEconomica,
    RangoIngresoEconomico,
    TipoTenenciaVivienda
};
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class FormularioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            // Obtener datos de ejemplo para el formulario
            $datos = $this->generarDatosFormulario();

            // Crear Dependencia Económica
            $dependencia_economica = DependenciaEconomica::create([
                'id_tipo_ocupacion_dependencia' => $datos['economica']['tipos_ocupacion_dependiente'],
                'id_dependencia_economica' => $datos['economica']['tipo_dependencia'] ?? null,
                'id_rango_ingreso_economico' => $datos['economica']['rango_ingreso'] ?? null,
                'ocupacion' => $datos['economica']['ocupacion'] ?? null,
                'puntaje' => 0.00,
                'puntaje_total' => 0.00,
            ]);

            // Crear Infraestructura
            $infraestructura = Infraestructura::create([
                'cantidad_dormitorios' => $datos['residencia']['cant_dormitorios'] ?? 0,
                'cantidad_banhos' => $datos['residencia']['cant_banhos'] ?? 0,
                'cantidad_salas' => $datos['residencia']['cant_salas'] ?? 0,
                'cantidad_comedor' => $datos['residencia']['cant_comedor'] ?? 0,
                'cantidad_patios' => $datos['residencia']['cant_patios'] ?? 0,
                'puntaje_total' => 0.00,
            ]);

            // Crear Residencia
            $residenciaData = Residencia::create([
                'provincia' => $datos['residencia']['provincia'] ?? null,
                'zona' => $datos['residencia']['zona'] ?? null,
                'calle' => $datos['residencia']['calle'] ?? null,
                'barrio' => $datos['residencia']['barrio'] ?? null,
                'puntaje_total' => 0.00,
            ]);

            // Crear Tenencia de Vivienda
            $tenenciaVivienda = TenenciaVivienda::create([
                'id_tipo_tenencia' => $datos['tenencia']['tipo_tenencia_vivienda'],
                'puntaje' => 0.00,
                'puntaje_total' => 0.00,
            ]);

            // Crear Grupo Familiar
            $grupoFamiliar = GrupoFamiliar::create([
                'tiene_hijos' => $datos['grupo_familiar']['tiene_hijos'] ?? false,
                'cantidad_hijos' => $datos['grupo_familiar']['cantidad_hijos'] ?? 0,
                'cantidad_familiares' => count($datos['grupo_familiar']['miembros']),
                'puntaje' => $this->calcularPuntajeGrupoFamiliar($datos['grupo_familiar']),
                'puntaje_total' => 0.00,
            ]);

            // Crear Miembros Familiares
            foreach ($datos['grupo_familiar']['miembros'] as $miembro) {
                MiembroFamiliar::create([
                    'id_grupo_familiar' => $grupoFamiliar->id,
                    'nombre_completo' => $miembro['nombre_completo'],
                    'parentesco' => $miembro['parentesco'],
                    'edad' => $miembro['edad'],
                    'ocupacion' => $miembro['ocupacion'] ?? null,
                    'observacion' => $miembro['observacion'] ?? null,
                ]);
            }

            // Crear Respuestas del Formulario
            $respuestasFormulario = RespuestaFormulario::create([
                'id_lugar_procedencia' => $datos['lugar_procedencia'] ?? null,
                'id_residencia' => $residenciaData->id,
                'id_infraestructura' => $infraestructura->id,
                'id_dependencia_economica' => $dependencia_economica->id,
                'id_tenencia_vivienda' => $tenenciaVivienda->id,
                'id_grupo_familiar' => $grupoFamiliar->id,
                'tiene_otro_beneficio' => $datos['otro_beneficio'] ?? false,
                'comentario_otro_beneficio' => $datos['comentario_otro_beneficio'] ?? null,
                'puntaje_otro_beneficio' => $datos['otro_beneficio'] ? 2.50 : 0.00,
                'es_discapacitado' => $datos['discapacidad'] ?? false,
                'comentario_discapacitado' => $datos['comentario_discapacidad'] ?? null,
                'puntaje_discapacitado' => $datos['discapacidad'] ? 3.00 : 0.00,
                'comentario_personal' => $datos['comentario_personal'] ?? null,
            ]);

            // Crear Formulario Base
            $formulario = FormularioSocioEconomico::create([
                'id_estudiante' => 1, // ID de usuario de ejemplo
                'fecha_llenado' => Carbon::now(),
                'telefono_referencia' => $datos['telefono_referencia'],
                'edad' => 21, // Edad de ejemplo
                'completado' => true,
                'validado_por' => false,
                'id_respuesta_formulario' => $respuestasFormulario->id,
            ]);

            // Crear Postulación
            $postulacion = Postulacion::create([
                'id_estudiante' => 1, // ID de usuario de ejemplo
                'id_convocatoria' => $datos['id_convocatoria'],
                'id_formulario' => $formulario->id,
                'id_beca' => $datos['id_beca'],
                'fecha_postulacion' => now(),
                'estado_postulado' => 'PENDIENTE',
            ]);

            // Crear Trámite
            $tramite = Tramite::create([
                'id_postulacion' => $postulacion->id,
                'codigo' => 'TRA-' . str_pad($postulacion->id, 6, '0', STR_PAD_LEFT),
                'fecha_creacion' => now(),
                'clasificado' => 'NO',
                'estado_actual' => 1, // 1: PENDIENTE
            ]);

            DB::commit();

            $this->command->info('✅ Formulario de ejemplo creado exitosamente.');
            $this->command->info('📋 Resumen del formulario creado:');
            $this->command->info("   • Formulario ID: {$formulario->id}");
            $this->command->info("   • Postulación ID: {$postulacion->id}");
            $this->command->info("   • Trámite ID: {$tramite->id}");
            $this->command->info("   • Código de trámite: {$tramite->codigo}");
            $this->command->info("   • Fecha de creación: " . now()->format('d/m/Y H:i:s'));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error al crear el formulario: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generar datos de ejemplo para el formulario
     */
    private function generarDatosFormulario()
    {
        // Obtener IDs reales de las tablas (asumiendo que existen)
        $lugarProcedencia = LugarProcedencia::first();
        $tipoOcupacion = TipoOcupacionDependiente::first();
        $tipoDependencia = TipoDependenciaEconomica::first();
        $rangoIngreso = RangoIngresoEconomico::first();
        $tipoTenencia = TipoTenenciaVivienda::first();

        return [
            'ci_estudiante' => '12345678',
            'telefono_referencia' => '77123456',
            'lugar_procedencia' => $lugarProcedencia ? $lugarProcedencia->id : 1,
            'id_convocatoria' => 1, // ID de convocatoria de ejemplo
            'id_beca' => 1, // ID de beca de ejemplo

            'economica' => [
                'tipos_ocupacion_dependiente' => $tipoOcupacion ? $tipoOcupacion->id : 1,
                'tipo_dependencia' => $tipoDependencia ? $tipoDependencia->id : 1,
                'rango_ingreso' => $rangoIngreso ? $rangoIngreso->id : 1,
                'ocupacion' => 'Estudiante Universitario',
            ],

            'residencia' => [
                'provincia' => 'Andrés Ibáñez',
                'zona' => 'Zona Urbana',
                'calle' => 'Calle Murillo',
                'barrio' => 'Barrio Universitario',
                'cant_dormitorios' => 2,
                'cant_banhos' => 1,
                'cant_salas' => 1,
                'cant_comedor' => 1,
                'cant_patios' => 1,
            ],

            'tenencia' => [
                'tipo_tenencia_vivienda' => $tipoTenencia ? $tipoTenencia->id : 1,
            ],

            'grupo_familiar' => [
                'tiene_hijos' => false,
                'cantidad_hijos' => 0,
                'miembros' => [
                    [
                        'nombre_completo' => 'Juan Pérez Mamani',
                        'parentesco' => 'Padre',
                        'edad' => 45,
                        'ocupacion' => 'Comerciante',
                        'observacion' => null,
                    ],
                    [
                        'nombre_completo' => 'María López Quispe',
                        'parentesco' => 'Madre',
                        'edad' => 42,
                        'ocupacion' => 'Ama de casa',
                        'observacion' => null,
                    ],
                    [
                        'nombre_completo' => 'Carlos Pérez López',
                        'parentesco' => 'Hermano',
                        'edad' => 19,
                        'ocupacion' => 'Estudiante',
                        'observacion' => 'Estudiante de colegio',
                    ],
                ],
            ],

            'otro_beneficio' => false,
            'comentario_otro_beneficio' => null,
            'discapacidad' => false,
            'comentario_discapacidad' => null,
            'comentario_personal' => 'Estudiante dedicado con buen rendimiento académico.',
        ];
    }

    /**
     * Calcular puntaje sugerido para grupo familiar
     */
    private function calcularPuntajeGrupoFamiliar($grupoFamiliar)
    {
        $puntajeBase = 10.00;

        // Reducción por cantidad de miembros
        $cantidadMiembros = count($grupoFamiliar['miembros']);
        if ($cantidadMiembros > 3) {
            $extra = $cantidadMiembros - 3;
            $puntajeBase -= ($extra * 0.5);
        }

        // Reducción por hijos
        if ($grupoFamiliar['tiene_hijos']) {
            $puntajeBase -= ($grupoFamiliar['cantidad_hijos'] * 0.3);
        }

        return max(0, round($puntajeBase, 2));
    }

    /**
     * Crear múltiples formularios de ejemplo
     */
    public function crearMultiplesFormularios($cantidad = 3)
    {
        $this->command->info("📝 Creando {$cantidad} formularios de ejemplo...");

        $creados = 0;
        for ($i = 1; $i <= $cantidad; $i++) {
            try {
                $this->run();
                $creados++;
                $this->command->info("✅ Formulario {$i} creado exitosamente.");
            } catch (\Exception $e) {
                $this->command->error("❌ Error en formulario {$i}: " . $e->getMessage());
            }
        }

        $this->command->info("\n📊 Resumen:");
        $this->command->info("   Total intentados: {$cantidad}");
        $this->command->info("   Creados exitosamente: {$creados}");
        $this->command->info("   Fallidos: " . ($cantidad - $creados));
    }
}
