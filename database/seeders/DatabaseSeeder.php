<?php

namespace Database\Seeders;

use Database\Seeders\CollectionSeeder;
use Database\Seeders\DynamicPageSeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\SystemSettingsSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            UserSeeder::class,
            SystemSettingsSeeder::class,
            DynamicPageSeeder::class,
            CollectionSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
