<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'class' => 'CodeIgniter\Shield\Config\Auth',
                'key' => 'allowRegistration',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'CodeIgniter\Shield\Config\Auth',
                'key' => 'allowMagicLinkLogins',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'twitter',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'instagram',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'contactPhones',
                'value' => '0966000000',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'contactAddress',
                'value' => 'ryddah Sudi Arabia',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'contactEmail',
                'value' => 'amd@dt4it.com',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'theme',
                'value' => 'ADMINLTE',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'title',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'company_name_ar',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'company_name_en',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'keywords_ar',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'keywords_en',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'site_desc_ar',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'site_desc_en',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'video_link',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'phone',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'mobile',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'address_ar',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'address_en',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'facebook',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'tiktok',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
            [
                'class' => 'Config\App',
                'key' => 'snapchat',
                'value' => '',
                'type' => 'string',
                'context' => '',
            ],
        ];

        // Insert data into the settings table
        $this->db->table('settings')->insertBatch($data);
    }
}
