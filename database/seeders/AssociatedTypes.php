<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssociatedTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $associatedTypes = [
            ['name' => 'Efetivo', 'abble_vote' => '1'],
            ['name' => 'Comissionado', 'abble_vote' => '0'],
            ['name' => 'Aposentado', 'abble_vote' => '0'],
            ['name' => 'Terceirizado', 'abble_vote' => '0'],
        ];

        foreach ($associatedTypes as $associatedType) {
            \App\Models\AssociatedType::create($associatedType);
        }
    }
}
