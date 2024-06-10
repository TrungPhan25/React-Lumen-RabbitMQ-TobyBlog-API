<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(30)
            ->create([
                'password' => Hash::make('Admin@12345'),
            ]);

        // DB::table('users')->insert([
        //     'name' => 'User 1',
        //     'email' => 'user1@gmail.com',
        //     'password' => Hash::make('Admin@12345')
        // ]);
    }
}
