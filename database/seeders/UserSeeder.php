<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'user1',
                'password' => password_hash('user1', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ],

            [
                'username' => 'user2',
                'password' => password_hash('user2', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ],

            [
                'username' => 'user3',
                'password' => password_hash('user3', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ]
            ]);
    }
}
