<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Income;
use App\Models\Wallet;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\Wallet::factory()->create([
            'user_id' => 1,
            'balance' => 1000,
        ]);

        \App\Models\Income::factory()->create([
            'user_id' => 1,
            'amount' => 1000,
            'description' => 'Salary',
        ]);
    }
}
