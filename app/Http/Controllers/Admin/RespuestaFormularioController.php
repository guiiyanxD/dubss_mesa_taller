<?php

namespace App\Http\Controllers;

use App\Models\RespuestaFormulario;
use App\Models\LugarProcedencia;
use App\Models\Residencia;
use App\Models\Infraestructura;
use App\Models\DependenciaEconomica;
use App\Models\TenenciaVivienda;
use App\Models\GrupoFamiliar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RespuestaFormularioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $respuestas = RespuestaFormulario::with([
            'lugarProcedencia',
            'residencia',
            'infraestructura',
            'dependenciaEconomica',
            'tenenciaVivienda',
            'grupoFamiliar'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return Inertia::render('RespuestaFormulario/Index', [
            'respuestas' => $respuestas,
            'flash' => session('flash', []),
            'estadisticas' => $this->obtenerEstadisticas()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('RespuestaFormulario/Create', [
            'lugares' => LugarProcedencia::where('activo', true)->orderBy('nombre_lugar')->get(),
            'residencias' => Residencia::where('activo', true)->orderBy('nombre')->get(),
            'infraestructuras' => Infraestructura::where('activo', true)->orderBy('nombre')->get(),
            'dependencias' => DependenciaEconomica::where('activo', true)->orderBy('nombre')->get(),
            'tenencias' => TenenciaVivienda::where('activo', true)->orderBy('nombre')->get(),
            'gruposFamiliares' => GrupoFamiliar::where('activo', true)->orderBy('nombre')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_lugar_procedencia' => 'required|exists:lugar_procedencia,id',
            'id_residencia' => 'required|exists:residencia,id',
            'id_infraestructura' => 'required|exists:infraestructura,id',
            'id_dependencia_economica' => 'required|exists:dependencia_economica,id',
            'id_tenencia_vivienda' => 'required|exists:tenencia_vivienda,id',
            'id_grupo_familiar' => 'required|exists:grupo_familiar,id',
            'tiene_otro_beneficio' => 'boolean',
            'comentario_otro_beneficio' => 'nullable|string|max:500',
            'puntaje_otro_beneficio' => 'nullable|numeric|min:0|max:999.99',
            'es_discapacitado' => 'boolean',
            'comentario_discapacitado' => 'nullable|string|max:500',
            'puntaje_discapacitado' => 'nullable|numeric|min:0|max:999.99',
            'comentario_personal' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $respuesta = RespuestaFormulario::create($validated);

            DB::commit();

            return redirect()->route('respuesta-formulario.show', $respuesta->id)
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'Respuesta guardada exitosamente. Puntaje total: ' . $respuesta->calcularPuntajeTotal() . ' puntos.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('flash', [
                    'type' => 'error',
                    'message' => 'Error al guardar la respuesta: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RespuestaFormulario $respuestaFormulario)
    {
        $respuestaFormulario->load([
            'lugarProcedencia',
            'residencia',
            'infraestructura',
            'dependenciaEconomica',
            'tenenciaVivienda',
            'grupoFamiliar'
        ]);

        return Inertia::render('RespuestaFormulario/Show', [
            'respuesta' => $respuestaFormulario,
            'puntajeTotal' => $respuestaFormulario->calcularPuntajeTotal(),
            'flash' => session('flash', [])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RespuestaFormulario $respuestaFormulario)
    {
        $respuestaFormulario->load([
            'lugarProcedencia',
            'residencia',
            'infraestructura',
            'dependenciaEconomica',
            'tenenciaVivienda',
            'grupoFamiliar'
        ]);

        return Inertia::render('RespuestaFormulario/Edit', [
            'respuesta' => $respuestaFormulario,
            'lugares' => LugarProcedencia::where('activo', true)->orderBy('nombre_lugar')->get(),
            'residencias' => Residencia::where('activo', true)->orderBy('nombre')->get(),
            'infraestructuras' => Infraestructura::where('activo', true)->orderBy('nombre')->get(),
            'dependencias' => DependenciaEconomica::where('activo', true)->orderBy('nombre')->get(),
            'tenencias' => TenenciaVivienda::where('activo', true)->orderBy('nombre')->get(),
            'gruposFamiliares' => GrupoFamiliar::where('activo', true)->orderBy('nombre')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RespuestaFormulario $respuestaFormulario)
    {
        $validated = $request->validate([
            'id_lugar_procedencia' => 'required|exists:lugar_procedencia,id',
            'id_residencia' => 'required|exists:residencia,id',
            'id_infraestructura' => 'required|exists:infraestructura,id',
            'id_dependencia_economica' => 'required|exists:dependencia_economica,id',
            'id_tenencia_vivienda' => 'required|exists:tenencia_vivienda,id',
            'id_grupo_familiar' => 'required|exists:grupo_familiar,id',
            'tiene_otro_beneficio' => 'boolean',
            'comentario_otro_beneficio' => 'nullable|string|max:500',
            'puntaje_otro_beneficio' => 'nullable|numeric|min:0|max:999.99',
            'es_discapacitado' => 'boolean',
            'comentario_discapacitado' => 'nullable|string|max:500',
            'puntaje_discapacitado' => 'nullable|numeric|min:0|max:999.99',
            'comentario_personal' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $respuestaFormulario->update($validated);

            DB::commit();

            return redirect()->route('respuesta-formulario.show', $respuestaFormulario->id)
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'Respuesta actualizada exitosamente. Puntaje total: ' . $respuestaFormulario->calcularPuntajeTotal() . ' puntos.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('flash', [
                    'type' => 'error',
                    'message' => 'Error al actualizar la respuesta: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RespuestaFormulario $respuestaFormulario)
    {
        try {
            DB::beginTransaction();

            $respuestaFormulario->delete();

            DB::commit();

            return redirect()->route('respuesta-formulario.index')
                ->with('flash', [
                    'type' => 'success',
                    'message' => 'Respuesta eliminada exitosamente.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('flash', [
                    'type' => 'error',
                    'message' => 'Error al eliminar la respuesta: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Obtener estadísticas generales
     */
    private function obtenerEstadisticas()
    {
        $totalRespuestas = RespuestaFormulario::count();
        $conDiscapacidad = RespuestaFormulario::where('es_discapacitado', true)->count();
        $conOtroBeneficio = RespuestaFormulario::where('tiene_otro_beneficio', true)->count();

        // Puntaje promedio
        $respuestas = RespuestaFormulario::with([
            'lugarProcedencia',
            'residencia',
            'infraestructura',
            'dependenciaEconomica',
            'tenenciaVivienda',
            'grupoFamiliar'
        ])->get();

        $puntajes = [];
        foreach ($respuestas as $respuesta) {
            $puntajes[] = $respuesta->calcularPuntajeTotal();
        }

        $puntajePromedio = count($puntajes) > 0 ? array_sum($puntajes) / count($puntajes) : 0;

        // Lugar más frecuente
        $lugarMasFrecuente = DB::table('respuesta_formulario as rf')
            ->join('lugar_procedencia as lp', 'rf.id_lugar_procedencia', '=', 'lp.id')
            ->select('lp.nombre_lugar', DB::raw('COUNT(*) as total'))
            ->groupBy('rf.id_lugar_procedencia', 'lp.nombre_lugar')
            ->orderBy('total', 'desc')
            ->first();

        return [
            'total_respuestas' => $totalRespuestas,
            'con_discapacidad' => $conDiscapacidad,
            'con_otro_beneficio' => $conOtroBeneficio,
            'puntaje_promedio' => round($puntajePromedio, 2),
            'lugar_mas_frecuente' => $lugarMasFrecuente ? $lugarMasFrecuente->nombre_lugar : 'N/A',
            'total_lugar_frecuente' => $lugarMasFrecuente ? $lugarMasFrecuente->total : 0
        ];
    }

    /**
     * Exportar respuestas a CSV
     */
    public function exportar(Request $request)
    {
        $respuestas = RespuestaFormulario::with([
            'lugarProcedencia',
            'residencia',
            'infraestructura',
            'dependenciaEconomica',
            'tenenciaVivienda',
            'grupoFamiliar'
        ])->get();

        $filename = 'respuestas_formulario_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($respuestas) {
            $file = fopen('php://output', 'w');

            // Encabezados
            fputcsv($file, [
                'ID',
                'Fecha',
                'Lugar de Procedencia',
                'Puntaje Lugar',
                'Residencia',
                'Infraestructura',
                'Dependencia Económica',
                'Tenencia Vivienda',
                'Grupo Familiar',
                'Tiene Otro Beneficio',
                'Puntaje Otro Beneficio',
                'Es Discapacitado',
                'Puntaje Discapacidad',
                'Puntaje Total',
                'Comentario Personal'
            ]);

            // Datos
            foreach ($respuestas as $respuesta) {
                fputcsv($file, [
                    $respuesta->id,
                    $respuesta->created_at->format('d/m/Y H:i'),
                    $respuesta->lugarProcedencia->nombre_lugar ?? 'N/A',
                    $respuesta->lugarProcedencia->puntaje ?? 0,
                    $respuesta->residencia->nombre ?? 'N/A',
                    $respuesta->infraestructura->nombre ?? 'N/A',
                    $respuesta->dependenciaEconomica->nombre ?? 'N/A',
                    $respuesta->tenenciaVivienda->nombre ?? 'N/A',
                    $respuesta->grupoFamiliar->nombre ?? 'N/A',
                    $respuesta->tiene_otro_beneficio ? 'Sí' : 'No',
                    $respuesta->puntaje_otro_beneficio ?? 0,
                    $respuesta->es_discapacitado ? 'Sí' : 'No',
                    $respuesta->puntaje_discapacitado ?? 0,
                    $respuesta->calcularPuntajeTotal(),
                    $respuesta->comentario_personal ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generar reporte por lugar de procedencia
     */
    public function reportePorLugar()
    {
        $reporte = DB::table('respuesta_formulario as rf')
            ->join('lugar_procedencia as lp', 'rf.id_lugar_procedencia', '=', 'lp.id')
            ->select(
                'lp.nombre_lugar',
                DB::raw('COUNT(*) as total_respuestas'),
                DB::raw('AVG(lp.puntaje) as puntaje_promedio_lugar'),
                DB::raw('SUM(CASE WHEN rf.es_discapacitado = true THEN 1 ELSE 0 END) as total_discapacitados'),
                DB::raw('SUM(CASE WHEN rf.tiene_otro_beneficio = true THEN 1 ELSE 0 END) as total_otros_beneficios')
            )
            ->groupBy('rf.id_lugar_procedencia', 'lp.nombre_lugar')
            ->orderBy('total_respuestas', 'desc')
            ->get();

        return Inertia::render('RespuestaFormulario/ReporteLugar', [
            'reporte' => $reporte,
            'flash' => session('flash', [])
        ]);
    }
}
