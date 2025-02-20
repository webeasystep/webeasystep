<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PagesSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('ar_SA');
        $faker->addProvider(new \Faker\Provider\Lorem($faker));

        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'page_link' => $faker->unique()->slug,
                'title_ar' => $faker->sentence(3),
                'title_en' => $faker->sentence(3),
                'desc_en'  => $faker->text,
                'desc_ar' => $faker->realText(200, 2), // Generate Arabic text
                'content_en'    => $faker->paragraphs(3, true),
                'content_ar' => $faker->realText(500, 2), // Generate Arabic text
                'active'        => $faker->randomElement([0, 1]),
                'show_home'     => $faker->randomElement([0, 1]),
                'sort' => $i,
                'parent_id' => 0,
            ];
        }

        $this->db->table('pages')->insertBatch($data);
    }
}
