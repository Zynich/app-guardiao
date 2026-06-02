<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Buraco na Via',          'priority' => 'alta',    'sla_hours' => 48,  'description' => 'Buracos, crateras ou afundamentos em vias públicas e calçadas.'],
            ['name' => 'Iluminação Pública',     'priority' => 'media',   'sla_hours' => 72,  'description' => 'Postes apagados, lâmpadas queimadas ou falhas na iluminação urbana.'],
            ['name' => 'Semáforo Inoperante',    'priority' => 'alta',    'sla_hours' => 12,  'description' => 'Semáforo apagado, piscando ou com defeito.'],
            ['name' => 'Árvore Caída',           'priority' => 'urgente', 'sla_hours' => 4,   'description' => 'Árvore caída sobre via, calçada ou rede elétrica.'],
            ['name' => 'Poda de Árvore',         'priority' => 'baixa',   'sla_hours' => 168, 'description' => 'Árvores com galhos obstruindo passagem, iluminação ou rede elétrica.'],
            ['name' => 'Lixo Acumulado',         'priority' => 'media',   'sla_hours' => 48,  'description' => 'Descarte irregular ou acúmulo de lixo em vias e terrenos públicos.'],
            ['name' => 'Entulho Irregular',      'priority' => 'media',   'sla_hours' => 72,  'description' => 'Descarte irregular de entulho, resíduos de construção ou materiais volumosos.'],
            ['name' => 'Calçada Danificada',     'priority' => 'baixa',   'sla_hours' => 120, 'description' => 'Calçadas com buracos, desnivelamentos ou irregularidades que oferecem risco.'],
            ['name' => 'Vazamento de Água',      'priority' => 'alta',    'sla_hours' => 24,  'description' => 'Vazamentos em ruas, calçadas ou redes de água pública.'],
            ['name' => 'Esgoto a Céu Aberto',    'priority' => 'alta',    'sla_hours' => 24,  'description' => 'Esgoto exposto em vias públicas ou áreas de uso comum.'],
            ['name' => 'Vandalismo',             'priority' => 'media',   'sla_hours' => 48,  'description' => 'Pichações, depredações ou danos ao patrimônio público.'],
            ['name' => 'Obras Irregulares',      'priority' => 'media',   'sla_hours' => 72,  'description' => 'Obras sem licença ou obstruindo vias públicas indevidamente.'],
            ['name' => 'Animais Abandonados',    'priority' => 'media',   'sla_hours' => 48,  'description' => 'Animais em situação de abandono ou risco em vias públicas.'],
            ['name' => 'Alagamento',             'priority' => 'urgente', 'sla_hours' => 6,   'description' => 'Pontos de alagamento ou enchentes em vias públicas.'],
            ['name' => 'Outros',                 'priority' => 'media',   'sla_hours' => 72,  'description' => 'Demais ocorrências não enquadradas nas categorias anteriores.'],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
