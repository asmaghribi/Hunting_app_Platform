<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'name' => 'Admin One',
                'email' => 'admin@gmail.com',
                'phone' => '123456789',
                'adresse' => '123 Admin Street',
                'password' => Hash::make('123456789'),
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'phone' => '987654321',
                'adresse' => '456 Admin Avenue',
                'password' => Hash::make('adminpassword'),
            ],
        ];

        DB::table('admins')->insert($admins);
    }
}
