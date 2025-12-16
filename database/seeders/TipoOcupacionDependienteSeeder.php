<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoOcupacionDependiente;

class TipoOcupacionDependienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposOcupacion = [
            [
                'nombre' => 'Asalariado formal',
                'archivo_adjuntar' => 'Fotocopia de boleta de pago',
                'puntaje' => 8.50,
            ],
            [
                'nombre' => 'Asalariado informal',
                'archivo_adjuntar' => 'Declaración jurada',
                'puntaje' => 6.00,
            ],
            [
                'nombre' => 'Comerciante mayorista',
                'archivo_adjuntar' => 'Fotocopia del NIT',
                'puntaje' => 9.00,
            ],
            [
                'nombre' => 'Rentista',
                'archivo_adjuntar' => 'Fotocopia de Boleta de',
                'puntaje' => 7.50,
            ],
            [
                'nombre' => 'Comerciante minorista',
                'archivo_adjuntar' => 'Fotocopia de NIT o carnet de socio o nota especificando la característica del comercio',
                'puntaje' => 7.00,
            ],
            [
                'nombre' => 'Agricultor',
                'archivo_adjuntar' => 'Fotocopia de carnet de socio o nota especificando la característica del agricultor',
                'puntaje' => 6.50,
            ]
        ];

        foreach ($tiposOcupacion as $tipo) {
            TipoOcupacionDependiente::create($tipo);
        }


        $tipos = TipoOcupacionDependiente::all();
        $this->command->table(
            ['ID', 'Nombre', 'Archivo a Adjuntar', 'Puntaje', 'Estado'],
            $tipos->map(function($tipo) {
                return [
                    $tipo->id,
                    $tipo->nombre,
                    substr($tipo->archivo_adjuntar, 0, 30) . '...',
                    $tipo->puntaje,
                    $tipo->activo ? '✅ Activo' : '❌ Inactivo'
                ];
            })->toArray()
        );
    }
}
