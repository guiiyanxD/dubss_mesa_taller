<?php

namespace App\Http\Controllers\Admin;

use App\Models\TipoOcupacionDependiente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class TipoOcupacionDependienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = TipoOcupacionDependiente::orderBy('nombre')->get();

        return Inertia::render('Admin/TipoOcupacionDependiente/Index', [
            'tipos' => $tipos,
            'flash' => session('flash', [])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/TipoOcupacionDependiente/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'archivo_adjuntar' => 'required|string|max:255',
            'puntaje' => 'nullable|numeric|min:0|max:999.99',
            'activo' => 'boolean'
        ]);

        TipoOcupacionDependiente::create($validated);

        return redirect()->route('admin.tipo-ocupacion-dependiente.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Tipo de ocupación creado exitosamente.'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoOcupacionDependiente $tipoOcupacionDependiente)
    {
        return Inertia::render('Admin/TipoOcupacionDependiente/Edit', [
            'tipo' => $tipoOcupacionDependiente
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoOcupacionDependiente $tipoOcupacionDependiente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'archivo_adjuntar' => 'required|string|max:255',
            'puntaje' => 'nullable|numeric|min:0|max:999.99',
            'activo' => 'boolean'
        ]);

        $tipoOcupacionDependiente->update($validated);

        return redirect()->route('admin.tipo-ocupacion-dependiente.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Tipo de ocupación actualizado exitosamente.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoOcupacionDependiente $tipoOcupacionDependiente)
    {
        $tipoOcupacionDependiente->delete();

        return redirect()->route('admin.tipo-ocupacion-dependiente.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Tipo de ocupación eliminado exitosamente.'
            ]);
    }

    public function habilitar(Int $id)
    {
        $tipoOcupacionDependiente = TipoOcupacionDependiente::findOrFail($id);
        $tipoOcupacionDependiente->activo = !$tipoOcupacionDependiente->activo;
        $tipoOcupacionDependiente->save();

        return redirect()->route('admin.tipo-ocupacion-dependiente.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Tipo de ocupación habilitado exitosamente.'
            ]);
    }
}
