<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ContactUsSeeder extends Seeder
{
    public function run()
    {
        // Create a Faker instance
        $faker = Factory::create('ar_SA');

        $data = [];

        // Generate random data for the contact_us table
        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'module_name' => $faker->sentence(2),
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'subject' => $faker->sentence(4),
                'message' => $faker->paragraph(3),
                'created_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            ];
        }

        // Insert data into the contact_us table
        $this->db->table('contact_us')->insertBatch($data);
    }
}
