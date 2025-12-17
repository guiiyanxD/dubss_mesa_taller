<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LugarProcedencia;

class LugarProcedenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lugaresProcedencia = [
            [
                'nombre_lugar' => 'Santa Cruz',
                'puntaje' => 10.00,
            ],
            [
                'nombre_lugar' => 'Camiri',
                'puntaje' => 7.50,
            ],
            [
                'nombre_lugar' => 'Valle Grande',
                'puntaje' => 8.00,
            ],
            [
                'nombre_lugar' => 'Montero',
                'puntaje' => 9.00,
            ],
            [
                'nombre_lugar' => 'Yapacani',
                'puntaje' => 7.00,
            ],
            [
                'nombre_lugar' => 'San Julian',
                'puntaje' => 6.50,
            ],
            [
                'nombre_lugar' => 'San Ignacio',
                'puntaje' => 8.50,
            ]
        ];

        $insertados = 0;
        $existentes = 0;

        foreach ($lugaresProcedencia as $lugar) {
            $existe = LugarProcedencia::where('nombre_lugar', $lugar['nombre_lugar'])->exists();

            if (!$existe) {
                LugarProcedencia::create($lugar);
                $insertados++;
            } else {
                $existentes++;
            }
        }

    }
}
