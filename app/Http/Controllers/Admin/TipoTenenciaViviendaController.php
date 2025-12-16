<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoTenenciaVivienda;
use Inertia\Inertia;

class TipoTenenciaViviendaController extends Controller
{
    public function index()
    {
        $tiposTenencia = TipoTenenciaVivienda::orderBy('nombre')->get();

        return Inertia::render('Admin/TipoTenenciaVivienda/Index', [
            'tipos' => $tiposTenencia,
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo tipo de tenencia.
     */
    public function create()
    {
        return Inertia::render('Admin/TipoTenenciaVivienda/Create');
    }

    /**
     * Almacena un nuevo tipo de tenencia en la base de datos.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:50|unique:tipo_tenencia_vivienda,nombre',
            'documento_adjuntar' => 'required|string|max:50',
            'puntaje' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
        ]);

        $nuevo = TipoTenenciaVivienda::create($request->all());
         return redirect()->route('admin.tipos-tenencia.index')
            ->with('success', 'Tipo de tenencia de vivienda creado exitosamente.');
    }

    public function destroy(TipoTenenciaVivienda $tipoTenenciaVivienda)
    {
        $tipoTenenciaVivienda->delete();

        return redirect()->route('admin.tipos-tenencia.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Tipo de tenencia eliminado exitosamente.'
            ]);
    }
    public function habilitar(Int $id)
    {
        $tipoTenenciaVivienda = TipoTenenciaVivienda::findOrFail($id);
        $tipoTenenciaVivienda->activo = !$tipoTenenciaVivienda->activo;
        $tipoTenenciaVivienda->save();

        return redirect()->route('admin.tipos-tenencia.index')
            ->with('flash', [
                'type' => 'success',
                'message' => 'Estado del tipo de tenencia actualizado exitosamente.'
        ]);

    }
}
