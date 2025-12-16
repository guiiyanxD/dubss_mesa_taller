<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RangoIngresoEconomico;

class RangoIngresoEconomicoController extends Controller
{
    public function index()
    {
        $rangos = RangoIngresoEconomico::orderBy('id')->get();

        return Inertia::render('Admin/RangoIngresoEconomico/Index', [
            'rangos' => $rangos,
            'message' => [],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/RangoIngresoEconomico/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rango_nombre' => 'required|string|max:100',
            'puntaje' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
        ]);

        RangoIngresoEconomico::create($request->all());

        return redirect()->route('admin.rangos-ingreso.index')
            ->with('success', 'Rango de ingreso económico creado exitosamente.');
    }

    public function habilitar(Int $id)
    {
        $rango = RangoIngresoEconomico::findOrFail($id);
        $rango->activo = !$rango->activo;
        $rango->save();

        return redirect()
            ->route('admin.rangos-ingreso.index')
            ->with('message', 'El estado del rango de ingreso económico ha sido actualizado correctamente.');
    }

    public function destroy(Int $id)
    {
        $rangoIngresoEconomico = RangoIngresoEconomico::findOrFail($id);
        $rangoIngresoEconomico->delete();
        return redirect()->route('admin.rangos-ingreso.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Rango de ingreso económico eliminado exitosamente.'
            ]);
    }
}
