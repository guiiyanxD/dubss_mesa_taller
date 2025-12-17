<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\TipoDependenciaEconomica;

class TipoDependenciaEconomicaController extends Controller
{
    public function index()
    {
        $tipos = TipoDependenciaEconomica::all();
        return Inertia::render('Admin/TipoDependenciaEconomica/Index', [
            'tipos' => $tipos,
        ]);
    }

    public function getTiposActivosParaFormulario()
    {
        $tiposActivos = TipoDependenciaEconomica::where('activo', true)->get();
        return response()->json([
            'success' => true,
            'tipo_dependencia_economica' => $tiposActivos
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TipoDependenciaEconomica/Create');
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'puntaje' => 'nullable|numeric|min:0|max:999.99',
            'activo' => 'boolean'
        ]);

        TipoDependenciaEconomica::create($validated);

        return redirect()->route('admin.tipos-dependencia.index');
    }
    public function destroy($id)
    {
        $tipo = TipoDependenciaEconomica::findOrFail($id);
        $tipo->delete();


        return redirect()->route('admin.tipos-dependencia.index');
    }

    public function habilitar($id)
    {
        $tipo = TipoDependenciaEconomica::findOrFail($id);
        $tipo->activo = !$tipo->activo;
        $tipo->save();

        return redirect()->route('admin.tipos-dependencia.index');
    }
}
