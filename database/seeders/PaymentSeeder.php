<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('payments')->insert([
            [
                'id'             => 1,
                'user_id'        => null,
                'collection_id'  => 1,
                'name'           => 'Unknown User',
                'amount'         => 100.00,
                'transaction_id' => 'pi_3Q3F0kRrSTkHftfa1cfdmxqc',
                'created_at'     => Carbon::create('2024', '09', '26', '04', '33', '29'),
                'updated_at'     => Carbon::create('2024', '09', '26', '04', '33', '29'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 2,
                'user_id'        => null,
                'collection_id'  => 1,
                'name'           => 'Test User',
                'amount'         => 100.00,
                'transaction_id' => 'pi_3Q3F25RrSTkHftfa1SpWc16k',
                'created_at'     => Carbon::create('2024', '09', '26', '04', '34', '52'),
                'updated_at'     => Carbon::create('2024', '09', '26', '04', '34', '52'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 3,
                'user_id'        => 1,
                'collection_id'  => 1,
                'name'           => 'Shadhin666',
                'amount'         => 50.75,
                'transaction_id' => 'txn_91271',
                'created_at'     => Carbon::create('2024', '09', '26', '04', '53', '07'),
                'updated_at'     => Carbon::create('2024', '09', '26', '04', '53', '07'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 4,
                'user_id'        => 1,
                'collection_id'  => 1,
                'name'           => null,
                'amount'         => 150.75,
                'transaction_id' => 'txn_912713',
                'created_at'     => Carbon::create('2024', '09', '26', '05', '16', '12'),
                'updated_at'     => Carbon::create('2024', '09', '26', '05', '16', '12'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 5,
                'user_id'        => null,
                'collection_id'  => 1,
                'name'           => 'Test User',
                'amount'         => 123.00,
                'transaction_id' => 'pi_3Q3UiXRrSTkHftfa0CqShPnx',
                'created_at'     => Carbon::create('2024', '09', '26', '21', '19', '45'),
                'updated_at'     => Carbon::create('2024', '09', '26', '21', '19', '45'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 6,
                'user_id'        => null,
                'collection_id'  => 1,
                'name'           => 'Hafizur Rahman Shadhin',
                'amount'         => 100000.00,
                'transaction_id' => 'pi_3Q3UuwRrSTkHftfa10ROJqP9',
                'created_at'     => Carbon::create('2024', '09', '26', '21', '32', '34'),
                'updated_at'     => Carbon::create('2024', '09', '26', '21', '32', '34'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 7,
                'user_id'        => 3,
                'collection_id'  => 2,
                'name'           => null,
                'amount'         => 175.75,
                'transaction_id' => 'txn_9127131',
                'created_at'     => Carbon::create('2024', '09', '26', '21', '56', '59'),
                'updated_at'     => Carbon::create('2024', '09', '26', '21', '56', '59'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 8,
                'user_id'        => 3,
                'collection_id'  => 2,
                'name'           => null,
                'amount'         => 975.75,
                'transaction_id' => 'txn_91271312',
                'created_at'     => Carbon::create('2024', '09', '26', '21', '57', '18'),
                'updated_at'     => Carbon::create('2024', '09', '26', '21', '57', '18'),
                'deleted_at'     => null,
            ],
            [
                'id'             => 9,
                'user_id'        => 3,
                'collection_id'  => 3,
                'name'           => null,
                'amount'         => 1975.75,
                'transaction_id' => 'txn_912713121',
                'created_at'     => Carbon::create('2024', '09', '26', '21', '57', '44'),
                'updated_at'     => Carbon::create('2024', '09', '26', '21', '57', '44'),
                'deleted_at'     => null,
            ],
        ]);
    }
}
