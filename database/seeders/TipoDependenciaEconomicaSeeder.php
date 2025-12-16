<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDependenciaEconomica;

class TipoDependenciaEconomicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposDependencia = [
            [
                'nombre' => 'Independiente',
                'puntaje' => 10.00,
            ],
            [
                'nombre' => 'Solo padre',
                'puntaje' => 7.50,
            ],
            [
                'nombre' => 'Solo madre',
                'puntaje' => 7.50,
            ],
            [
                'nombre' => 'De la pareja',
                'puntaje' => 6.00,
            ],
            [
                'nombre' => 'Ambos padres',
                'puntaje' => 8.50,
            ],
            [
                'nombre' => 'Otro familiar',
                'puntaje' => 5.00,
            ]
        ];

        foreach ($tiposDependencia as $tipo) {
            TipoDependenciaEconomica::create($tipo);
        }


        $tipos = TipoDependenciaEconomica::all();
        $this->command->table(
            ['ID', 'Nombre', 'Puntaje', 'Estado'],
            $tipos->map(function($tipo) {
                return [
                    $tipo->id,
                    $tipo->nombre,
                    $tipo->puntaje,
                    $tipo->activo ? '✅ Activo' : '❌ Inactivo'
                ];
            })->toArray()
        );
    }
}
