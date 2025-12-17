<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LugarProcedencia;
use Inertia\Inertia;

class LugarProcedenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lugares = LugarProcedencia::orderBy('nombre_lugar')->get();

        return Inertia::render('Admin/LugarProcedencia/Index', [
            'lugares' => $lugares,
            'flash' => session('flash', [])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/LugarProcedencia/Create');
    }

    public function getLugaresActivosParaFormulario()
    {
        $lugares = LugarProcedencia::where('activo', true)
            ->orderBy('nombre_lugar')
            ->get();

        return response()->json([
            'success' => true,
            'lugares_procedencia' => $lugares
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_lugar' => 'required|string|max:255|unique:lugar_procedencia,nombre_lugar',
            'puntaje' => 'required|numeric|min:0|max:999.99',
            'activo' => 'boolean'
        ]);

        LugarProcedencia::create($validated);

        return redirect()->route('admin.lugar-procedencia.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Lugar de procedencia creado exitosamente.'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LugarProcedencia $lugarProcedencia)
    {
        return Inertia::render('Admin/LugarProcedencia/Edit', [
            'lugar' => $lugarProcedencia
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LugarProcedencia $lugarProcedencia)
    {
        $validated = $request->validate([
            'nombre_lugar' => 'required|string|max:255|unique:lugar_procedencia,nombre_lugar,' . $lugarProcedencia->id,
            'puntaje' => 'required|numeric|min:0|max:999.99',
            'activo' => 'boolean'
        ]);

        $lugarProcedencia->update($validated);

        return redirect()->route('admin.lugar-procedencia.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Lugar de procedencia actualizado exitosamente.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $lugarProcedencia = LugarProcedencia::findOrFail($id);
        $lugarProcedencia->delete();

        return redirect()->route('admin.lugar-procedencia.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Lugar de procedencia eliminado exitosamente.'
            ]);
    }

    public function habilitar(Int $id)
    {
        $lugarProcedencia = LugarProcedencia::findOrFail($id);
        $lugarProcedencia->activo = !$lugarProcedencia->activo;
        $lugarProcedencia->save();

        return redirect()->route('admin.lugar-procedencia.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Lugar de procedencia habilitado exitosamente.'
            ]);
    }
}
