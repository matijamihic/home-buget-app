<?php

namespace Database\Seeders;

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

        \App\Models\Income::factory()->create([
            'user_id' => 1,
            'amount' => 2000,
            'description' => 'Salary',
            'date' => \Carbon\Carbon::now()->subDays(rand(1, 30)),
        ]);

        $categories = ['bills', 'rent', 'loans', 'car', 'food', 'entertainment', 'clothing', 'health', 'travel', 'other' ];
        foreach ($categories as $expenseType) {
            \App\Models\ExpenseCategory::factory()->create([
                'name' => $expenseType,
            ]);        
        } 

        $descriptions = [['Electricity', 'bills'], ['Water', 'bills'], ['Gas', 'bills'], ['Internet', 'bills'], ['Phone', 'bills'], ['Rent', 'rent'], ['Car Payment', 'car'], ['Car Insurance', 'car'], ['Car Maintenance', 'car'], ['Groceries', 'food'], ['Eating Out', 'food'], ['Movies', 'entertainment'], ['Concerts', 'entertainment'], ['Clothes', 'clothing'], ['Shoes', 'clothing'], ['Doctor', 'health'], ['Dentist', 'health'], ['Medicine', 'health'], ['Airfare', 'travel'], ['Hotel', 'travel'], ['Rental Car', 'travel'], ['Gas', 'travel'], ['Other', 'other']];
        $expenses_sum = 0;
        foreach ($descriptions as $description) {
            $expense = rand(10, 100);
            \App\Models\Expense::factory()->create([
                'user_id' => 1,
                'expense_category_id' => \App\Models\ExpenseCategory::where('name', $description[1])->first()->id,
                'amount' => $expense,
                'description' => $description[0],
                'date' => \Carbon\Carbon::now()->subDays(rand(1, 30)),
            ]);
            $expenses_sum += $expense;
        }
 
        $balance = 2000 - $expenses_sum;
        \App\Models\Wallet::factory()->create([
            'user_id' => 1,
            'balance' => $balance,
        ]);
    }
}
