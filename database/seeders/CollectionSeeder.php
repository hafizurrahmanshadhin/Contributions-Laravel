<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('collections')->insert([
            [
                'id'            => 1,
                'user_id'       => 3,
                'image'         => 'frontend/contributions.png',
                'name'          => 'First Collection',
                'description'   => 'This description is for first collection',
                'target_amount' => 666769.00,
                'deadline'      => '2024-08-30',
                'status'        => 'active',
                'created_at'    => Carbon::create('2024', '09', '24', '17', '19', '08'),
                'updated_at'    => Carbon::create('2024', '09', '24', '17', '19', '08'),
                'deleted_at'    => null,
            ],
            [
                'id'            => 2,
                'user_id'       => 1,
                'image'         => 'frontend/contributions.png',
                'name'          => 'Shadhin666',
                'description'   => 'This description is for second collection',
                'target_amount' => 1700.00,
                'deadline'      => '2024-08-30',
                'status'        => 'active',
                'created_at'    => Carbon::create('2024', '09', '26', '05', '03', '34'),
                'updated_at'    => Carbon::create('2024', '09', '27', '04', '15', '29'),
                'deleted_at'    => null,
            ],
            [
                'id'            => 3,
                'user_id'       => 1,
                'image'         => 'frontend/contributions.png',
                'name'          => 'Second',
                'description'   => 'This description is for third collection',
                'target_amount' => 1700.00,
                'deadline'      => '2024-08-30',
                'status'        => 'active',
                'created_at'    => Carbon::create('2024', '09', '26', '05', '04', '33'),
                'updated_at'    => Carbon::create('2024', '09', '27', '04', '15', '31'),
                'deleted_at'    => null,
            ],
            [
                'id'            => 4,
                'user_id'       => 1,
                'image'         => 'frontend/contributions.png',
                'name'          => 'Ring',
                'description'   => 'Demo description',
                'target_amount' => 15000.00,
                'deadline'      => '2024-08-31',
                'status'        => 'active',
                'created_at'    => Carbon::create('2024', '09', '26', '05', '04', '35'),
                'updated_at'    => Carbon::create('2024', '09', '27', '04', '15', '33'),
                'deleted_at'    => null,
            ],
            [
                'id'            => 5,
                'user_id'       => 3,
                'image'         => 'frontend/contributions.png',
                'name'          => 'My Collection',
                'description'   => 'This description is for My Collection',
                'target_amount' => 17000.00,
                'deadline'      => '2024-10-30',
                'status'        => 'active',
                'created_at'    => Carbon::create('2024', '09', '26', '21', '35', '29'),
                'updated_at'    => Carbon::create('2024', '09', '27', '04', '15', '35'),
                'deleted_at'    => null,
            ],
        ]);
    }
}
