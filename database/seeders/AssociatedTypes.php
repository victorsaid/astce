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
            ['name' => 'Efetivo', 'abble_vote' => 'true'],
            ['name' => 'Comissionado', 'abble_vote' => 'false'],
            ['name' => 'Aposentado', 'abble_vote' => 'false'],
            ['name' => 'Terceirizado', 'abble_vote' => 'false'],
        ];

        foreach ($associatedTypes as $associatedType) {
            \App\Models\AssociatedType::create($associatedType);
        }
    }
}
