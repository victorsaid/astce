<?php

namespace Database\Seeders;

use App\Models\Agreements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agreements = [
            [
                'name' => 'Partnership Alpha',
                'description' => 'Agreement with Alpha Corp for technology collaboration.',
                'photo' => json_encode(['url' => 'storage/photos/agreement_alpha.jpg']),
                'site' => 'https://www.alphacorp.com',
                'category' => 'Technology',
                'is_active' => true,
            ],
            [
                'name' => 'Beta Services Deal',
                'description' => 'A strategic deal with Beta Services for marketing campaigns.',
                'photo' => json_encode(['url' => 'storage/photos/agreement_beta.jpg']),
                'site' => 'https://www.betaservices.com',
                'category' => 'Marketing',
                'is_active' => false,
            ],
            [
                'name' => 'Gamma Health Agreement',
                'description' => 'Collaboration with Gamma Health for medical research.',
                'photo' => json_encode(['url' => 'storage/photos/agreement_gamma.jpg']),
                'site' => 'https://www.gammahealth.com',
                'category' => 'Healthcare',
                'is_active' => true,
            ],
        ];

        foreach ($agreements as $agreement) {
            Agreements::create($agreement);
        }
    }
}
