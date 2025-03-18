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
            ['name' => 'Colégio Batista', 'description' => 'Descontos em mensalidades.', 'photo' => json_encode(['url' => 'storage/photos/colegio_batista.jpg']), 'site' => 'https://www.colegiobatista.com.br', 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3222-3344', 'email' => 'contato@colegiobatista.com.br', 'whatsapp' => '(98) 98765-4321'],

            ['name' => 'Colégio Marista Araçagy', 'description' => 'Apoio educacional.', 'photo' => json_encode(['url' => 'storage/photos/colegio_marista_aracagy.jpg']), 'site' => 'https://www.marista.edu.br', 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3233-4455', 'email' => 'secretaria@marista.edu.br', 'whatsapp' => '(98) 98876-5432'],

            ['name' => 'Escola Crescimento', 'description' => 'Descontos em mensalidades.', 'photo' => json_encode(['url' => 'storage/photos/escola_crescimento.jpg']), 'site' => 'https://www.escolacrescimento.com.br', 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3244-5566', 'email' => 'contato@escolacrescimento.com.br', 'whatsapp' => '(98) 98987-6543'],

            ['name' => 'Colégio Upaon-Açu', 'description' => 'Bolsas de estudo.', 'photo' => json_encode(['url' => 'storage/photos/colegio_upaon_acu.jpg']), 'site' => 'https://www.upaonacu.com.br', 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3255-6677', 'email' => 'matriculas@upaonacu.com.br', 'whatsapp' => '(98) 99098-7654'],

            ['name' => 'Escola Divina Providência', 'description' => 'Convênio educacional.', 'photo' => json_encode(['url' => 'storage/photos/divina_providencia.jpg']), 'site' => null, 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3267-7788', 'email' => 'contato@divinaprovidencia.com.br', 'whatsapp' => '(98) 99123-4567'],

            ['name' => 'Instituto Divina Pastora', 'description' => 'Suporte educacional.', 'photo' => json_encode(['url' => 'storage/photos/divina_pastora.jpg']), 'site' => null, 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3278-9988', 'email' => 'secretaria@divinapastora.com.br', 'whatsapp' => '(98) 99678-5432'],

            ['name' => 'Instituto Educar', 'description' => 'Bolsas e capacitações.', 'photo' => json_encode(['url' => 'storage/photos/instituto_educar.jpg']), 'site' => 'https://www.institutoeducar.com.br', 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3278-8899', 'email' => 'matricula@institutoeducar.com.br', 'whatsapp' => '(98) 99234-5678'],

            ['name' => 'Escola São José', 'description' => 'Desenvolvimento estudantil.', 'photo' => json_encode(['url' => 'storage/photos/escola_sao_jose.jpg']), 'site' => null, 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3289-1122', 'email' => 'contato@escolasaojose.com.br', 'whatsapp' => '(98) 99765-3210'],

            ['name' => 'Universidade UNDB', 'description' => 'Cursos superiores.', 'photo' => json_encode(['url' => 'storage/photos/universidade_undb.jpg']), 'site' => 'https://www.undb.edu.br', 'category' => 'Educação e Capacitação', 'is_active' => true, 'phone' => '(98) 3221-9876', 'email' => 'contato@undb.edu.br', 'whatsapp' => '(98) 98765-3210'],

            ['name' => 'Coife Odonto Cohama', 'description' => 'Odontologia especializada.', 'photo' => json_encode(['url' => 'storage/photos/coife_odonto.jpg']), 'site' => 'https://www.coifeodonto.com.br', 'category' => 'Saúde e Bem Estar', 'is_active' => true, 'phone' => '(98) 3266-7788', 'email' => 'cohama@coifeodonto.com.br', 'whatsapp' => '(98) 99109-8765'],

            ['name' => 'Riso', 'description' => 'Atendimento odontológico.', 'photo' => json_encode(['url' => 'storage/photos/riso.jpg']), 'site' => 'https://www.riso.com.br', 'category' => 'Saúde e Bem Estar', 'is_active' => true, 'phone' => '(98) 3322-5566', 'email' => 'contato@riso.com.br', 'whatsapp' => '(98) 99654-3321'],

            ['name' => 'Unimed Seguros', 'description' => 'Planos de saúde.', 'photo' => json_encode(['url' => 'storage/photos/unimed_seguros.jpg']), 'site' => 'https://www.unimed.com.br', 'category' => 'Plano de Saúde', 'is_active' => true, 'phone' => '(98) 3212-8899', 'email' => 'atendimento@unimed.com.br', 'whatsapp' => '(98) 99812-4455'],

            ['name' => 'AABB', 'description' => 'Lazer e esportes.', 'photo' => json_encode(['url' => 'storage/photos/aabb.jpg']), 'site' => 'https://www.aabb.com.br', 'category' => 'Lazer e Entretenimento', 'is_active' => true, 'phone' => '(98) 3232-4455', 'email' => 'contato@aabb.com.br', 'whatsapp' => '(98) 99123-6789']
        ];


        foreach ($agreements as $agreement) {
            Agreements::create($agreement);
        }
    }
}
