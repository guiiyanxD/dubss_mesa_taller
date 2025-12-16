<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoDependenciaController extends Controller
{
    public function index()
    {
        return view('admin.tipo_dependencia.index');
    }

    public function create()
    {
        return view('admin.tipo_dependencia.create');
    }

    public function edit($id)
    {
        return view('admin.tipo_dependencia.edit', compact('id'));
    }

    public function show($id)
    {
        return view('admin.tipo_dependencia.show', compact('id'));
    }

    public function store(Request $request)
    {
        // Lógica para almacenar un nuevo Tipo de Dependencia
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar un Tipo de Dependencia existente
    }

    public function destroy($id)
    {
        // Lógica para eliminar un Tipo de Dependencia
    }
}
