<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Teachers
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => 'Teacher ' . $i,
                'email' => 'teacher' . $i . '@test.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'snumber' => 1000 + $i, 
            ]);
        }

        // Seed Students
        for ($i = 1; $i <= 50; $i++) {
            DB::table('users')->insert([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@test.com',
                'password' => Hash::make('password'),  
                'role' => 'student',
                'snumber' => 2000 + $i, 
            ]);
        }
    }
}
