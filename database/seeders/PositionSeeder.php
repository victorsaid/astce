<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Auditor de Controle Externo'],
            ['name' => 'Técnico de Controle Externo'],
            ['name' => 'Auxiliar de Controle Externo'],
        ];

        foreach ($positions as $position) {
            \App\Models\Position::create($position);
        }
    }
}
