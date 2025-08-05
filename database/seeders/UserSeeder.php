<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $users = [
            [
                'id'                => 1,
                'name'              => 'shadhin',
                'email'             => 'shadhin666@gmail.com',
                'password'          => Hash::make('12345678'),
                'phone'             => '1234567890',
                'address'           => 'Dhaka, Bangladesh',
                'avatar'            => 'backend/images/profile/profile-1.png',
                'email_verified_at' => '2024-08-26 23:18:42',
                'role'              => 'user',
                'created_at'        => now(),
                'updated_at'        => now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'name'              => 'admin',
                'email'             => 'admin@admin.com',
                'password'          => Hash::make('12345678'),
                'phone'             => '01911223344',
                'address'           => 'Dhaka, Bangladesh',
                'avatar'            => 'frontend/contributions.png',
                'email_verified_at' => '2024-08-26 23:19:47',
                'role'              => 'admin',
                'created_at'        => now(),
                'updated_at'        => now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 3,
                'name'              => 'user',
                'email'             => 'user@user.com',
                'password'          => Hash::make('12345678'),
                'phone'             => '01234567891',
                'address'           => 'Dhaka, Bangladesh',
                'avatar'            => 'backend/images/profile/profile-image.png',
                'email_verified_at' => '2024-08-26 23:39:57',
                'role'              => 'user',
                'created_at'        => now(),
                'updated_at'        => now(),
                'deleted_at'        => null,
            ],
        ];
        DB::table('users')->insert($users);
    }
}
