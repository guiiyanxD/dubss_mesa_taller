<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RangoIngresoEconomico;
use Illuminate\Support\Facades\DB;

class RangoIngresoEconomicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        RangoIngresoEconomico::truncate();


        $rangos = [
            [
                'rango_nombre' => 'Menor de Bs. 2500.-',
                'puntaje' => 15.00,
                'activo' => true,
            ],
            [
                'rango_nombre' => 'De Bs. 2501.- a Bs 4000.-',
                'puntaje' => 10.00,
                'activo' => true,
            ],
            [
                'rango_nombre' => 'De Bs. 4001.- a Bs. 6000.-',
                'puntaje' => 5.00,
                'activo' => true,
            ],
            [
                'rango_nombre' => 'Más de Bs. 6000.-',
                'puntaje' => 0.00,
                'activo' => true,
            ],
        ];

        // Insertar los datos en la base de datos
        foreach ($rangos as $rango) {
            RangoIngresoEconomico::create($rango);
        }

        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
