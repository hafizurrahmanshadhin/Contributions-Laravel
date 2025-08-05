<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('system_settings')->insert([
            [
                'id'             => 1,
                'title'          => 'Contributions',
                'email'          => 'admin@admin.com',
                'system_name'    => 'Contributions',
                'copyright_text' => '©Contributions',
                'logo'           => 'frontend/contributions.png',
                'favicon'        => 'frontend/contributions.png',
                'description'    => '<p>Contributions is a mobile application designed to simplify the process of collecting money from groups. It aims to make group payments easy and efficient, whether it’s for group gifts, event contributions, shared expenses, or any other group activity requiring financial collaboration.</p>',
                'created_at'     => '2024-08-31 05:08:04',
                'updated_at'     => '2024-08-31 05:08:04',
                'deleted_at'     => null,
            ],
        ]);
    }
}
