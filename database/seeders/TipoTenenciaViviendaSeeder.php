<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoTenenciaVivienda;

class TipoTenenciaViviendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposTenencia = [
            [
                'nombre' => 'Herencia',
                'documento_adjuntar' => 'Fotocopia simple de documento de transferencia',
                'puntaje' => 10.00,
            ],
            [
                'nombre' => 'De los padres',
                'documento_adjuntar' => 'Fotocopia simple de pago de impuesto de cualquier gestión',
                'puntaje' => 8.50,
            ],
            [
                'nombre' => 'Cedida',
                'documento_adjuntar' => 'Nota aclaratoria con las firmas que corresponda',
                'puntaje' => 7.00,
            ],
            [
                'nombre' => 'Anticrético',
                'documento_adjuntar' => 'Fotocopia simple de contrato de anticresis vigente',
                'puntaje' => 6.50,
            ],
            [
                'nombre' => 'Alquiler',
                'documento_adjuntar' => 'Fotocopia simple de respaldo de alquiler',
                'puntaje' => 5.00,
            ]
        ];

        foreach ($tiposTenencia as $tipo) {
            TipoTenenciaVivienda::create($tipo);
        }

        $this->command->info('Se crearon ' . count($tiposTenencia) . ' tipos de tenencia de vivienda.');
    }
}
