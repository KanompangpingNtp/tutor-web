<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'แอดมิน',
                'password' => Hash::make('123456789'),
                'level' => '1',
            ]
        );

        User::updateOrCreate(
            ['email' => 'tutor@example.com'],
            [
                'name' => 'ผู้สอน',
                'password' => Hash::make('123456789'),
                'level' => '2',
            ]
        );
    }
}
