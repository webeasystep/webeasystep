<?php

namespace App\Database\Seeds;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        // generate data dummy users
        $request = \Config\Services::request();
        $faker = Factory::create('ar_SA');

        // Seed data into auth_groups and auth_groups_users first
        $defaultGroups = [
            [
                'group_name' => 'SuperAdmin',
                'title' => 'ادارة الموقع',
                'description' => 'لديه جميع الصلاحيات',
            ],
            [
                'group_name' => 'User',
                'title' => 'المستخدمين',
                'description' => 'المستخدمين ليس لديهم اي صلاحيات داخل لوحة التحكم',
            ],
        ];
        $this->db->table('auth_groups')->insertBatch($defaultGroups);

        // Seed data into users table
        $data = [
            [
                'username'  => $faker->unique()->userName, // Generate a unique username
                'full_name' => $faker->name,
                'address_ar' => $faker->realText(50), // Generate Arabic text
                'address_en' => $faker->address,
                'gender'    => $faker->randomElement(['male', 'female']),
                'status'    => $faker->randomElement([1, 0]),
                'active'    => $faker->randomElement([1, 0]),
            ],
            [
                'username'  => $faker->unique()->userName, // Generate another unique username
                'full_name' => $faker->name,
                'address_ar' => $faker->realText(50), // Generate Arabic text
                'address_en' => $faker->address,
                'gender'    => $faker->randomElement(['male', 'female']),
                'status'    => $faker->randomElement([1, 0]),
                'active'    => $faker->randomElement([1, 0]),
            ]
        ];
        $this->db->table('users')->insertBatch($data);

        // generate data dummy auth_identities
        $defaultIdentities = [
            [
                'user_id' => 1,
                'type' => 'email_password',
                'secret'  => 'amd@dt4it.com',
                'secret2'=> password_hash('123456', PASSWORD_DEFAULT),
                'force_reset' => 0,
            ],
            [
                'user_id' => 2,
                'type' => 'email_password',
                'secret'  => 'admin@gmail.com',
                'secret2'=> password_hash('546321', PASSWORD_DEFAULT),
                'force_reset' => 0,
            ],

        ];
        /* email_password, magic-link,email_2fa,email_activate */
        $this->db->table('auth_identities')->insertBatch($defaultIdentities);

        // generate data dummy auth_permissions
        $defaultPermissions = [
            [
                'permission_name' => 'users.create',
                'title' => 'اضافة مستخدم',
            ],
            [
                'permission_name' => 'users.edit',
                'title' => 'تعديل مستخدم',
            ],
            [
                'permission_name' => 'users.delete',
                'title' => 'حذف مستخدم',
            ],
            [
                'permission_name' => 'users.manage',
                'title' => 'ادارة المستخدمين',
            ],
            [
                'permission_name' => 'users.show',
                'title' => 'مشاهدة مستخدم',
            ],
            [
                'permission_name' => 'settings.manage',
                'title' => 'ادارة اعدادات',
            ],
            [
                'permission_name' => 'settings.edit',
                'title' => 'تعديل اعدادات',
            ],
            [
                'permission_name' => 'settings.delete',
                'title' => 'حذف اعدادات',
            ],
            [
                'permission_name' => 'settings.show',
                'title' => 'مشاهدة اعدادات',
            ],
            // Add more default permissions as necessary
        ];
        $this->db->table('auth_permissions')->insertBatch($defaultPermissions);

        // generate data dummy auth_permissions_users
        $defaultData = [
            [
                'group_id' => 2,
                'permission' => 0,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 1,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 3,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 4,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 5,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 6,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 7,
                'user_id' => 1,
            ],
            [
                'group_id' => 2,
                'permission' => 8,
                'user_id' => 1,
            ],
        ];
        $this->db->table('auth_permissions_users')->insertBatch($defaultData);


        // generate data dummy auth_groups_users
        $defaultGroupUsers = [
            [
                'user_id' => 1,
                'group' => 'superadmin',
                'created_at' => [ 'type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'),],
            ],
            [
                'user_id' => 2,
                'group' => 'users',
                 'created_at' => [ 'type'    => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'),],
            ],
        ];
        $this->db->table('auth_groups_users')->insertBatch($defaultGroupUsers);

    }
}
