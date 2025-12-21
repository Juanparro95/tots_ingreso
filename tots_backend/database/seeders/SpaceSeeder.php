<?php

namespace Database\Seeders;

use App\Models\Space;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        $spaces = [
            [
                'name' => 'Sala de Reuniones A',
                'description' => 'Sala pequeña perfecta para reuniones de hasta 6 personas',
                'capacity' => 6,
                'location' => 'Piso 1, Ala Norte',
                'hourly_rate' => 50.00,
            ],
            [
                'name' => 'Sala de Reuniones B',
                'description' => 'Sala mediana equipada con proyector y pizarra',
                'capacity' => 12,
                'location' => 'Piso 1, Ala Sur',
                'hourly_rate' => 75.00,
            ],
            [
                'name' => 'Auditorio Principal',
                'description' => 'Gran auditorio con capacidad para 100 personas, equipado con sistema de sonido profesional',
                'capacity' => 100,
                'location' => 'Piso 2',
                'hourly_rate' => 200.00,
            ],
            [
                'name' => 'Sala de Conferencias',
                'description' => 'Espacio moderno para videoconferencias y presentaciones',
                'capacity' => 20,
                'location' => 'Piso 1, Ala Este',
                'hourly_rate' => 100.00,
            ],
            [
                'name' => 'Sala de Capacitación',
                'description' => 'Espacio versátil para cursos y talleres',
                'capacity' => 30,
                'location' => 'Piso 3',
                'hourly_rate' => 120.00,
            ],
        ];

        foreach ($spaces as $space) {
            Space::create($space);
        }
    }
}
