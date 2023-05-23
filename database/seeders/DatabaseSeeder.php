<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        
        $incomeDescriptions = [
            ['Salary', 2000],
            ['Freelance Work', 1000],
            ['Investment Income', 500],
            ['Rental Income', 800],
            ['Other', 300]
        ];
        
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        
        $currentDate = $startDate->copy();
        $incomeSum = 0;
        
        while ($currentDate <= $endDate) {
            foreach ($incomeDescriptions as $description) {
                $incomeAmount = rand($description[1] - 100, $description[1] + 100);
        
                \App\Models\Income::factory()->create([
                    'user_id' => 1,
                    'amount' => $incomeAmount,
                    'description' => $description[0],
                    'date' => $currentDate->copy(),
                ]);
        
                $incomeSum += $incomeAmount;
            }
        
            $currentDate->addMonth();
        }

        $categories = [
            'bills' => 100,
            'rent' => 500,
            'loans' => 200,
            'car' => 300,
            'food' => 150,
            'entertainment' => 50,
            'clothing' => 75,
            'health' => 80,
            'travel' => 200,
            'other' => 100,
        ];
        
        foreach ($categories as $expenseType => $averageAmount) {
            \App\Models\ExpenseCategory::factory()->create([
                'name' => $expenseType,
            ]);
        }
        
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        
        $currentDate = $startDate->copy();
        $expenses_sum = 0;
        
        // Descriptions for recurring expenses
        $recurringDescriptions = [
            ['Electricity', 'bills'],
            ['Water', 'bills'],
            ['Gas', 'bills'],
            ['Internet', 'bills'],
            ['Phone', 'bills'],
            ['Rent', 'rent'],
            ['Car Payment', 'car'],
        ];
        
        // Descriptions for other expenses
        $otherDescriptions = [
            ['Car Insurance', 'car'],
            ['Car Maintenance', 'car'],
            ['Groceries', 'food'],
            ['Eating Out', 'food'],
            ['Movies', 'entertainment'],
            ['Concerts', 'entertainment'],
            ['Clothes', 'clothing'],
            ['Shoes', 'clothing'],
            ['Doctor', 'health'],
            ['Dentist', 'health'],
            ['Medicine', 'health'],
            ['Airfare', 'travel'],
            ['Hotel', 'travel'],
            ['Rental Car', 'travel'],
            ['Gas', 'travel'],
            ['Other', 'other']
        ];
        
        while ($currentDate <= $endDate) {
            // Generate recurring expenses for each month
            foreach ($recurringDescriptions as $description) {
                $category = \App\Models\ExpenseCategory::where('name', $description[1])->first();
                $averageAmount = $categories[$description[1]];
                $expense = rand($averageAmount - 50, $averageAmount + 50);
        
                \App\Models\Expense::factory()->create([
                    'user_id' => 1,
                    'expense_category_id' => $category->id,
                    'amount' => $expense,
                    'description' => $description[0],
                    'date' => $currentDate->copy(),
                ]);
        
                $expenses_sum += $expense;
            }
        
            // Generate other expenses with random dates
            foreach ($otherDescriptions as $description) {
                $category = \App\Models\ExpenseCategory::where('name', $description[1])->first();
                $averageAmount = $categories[$description[1]];
                $expense = rand($averageAmount - 50, $averageAmount + 50);
        
                \App\Models\Expense::factory()->create([
                    'user_id' => 1,
                    'expense_category_id' => $category->id,
                    'amount' => $expense,
                    'description' => $description[0],
                    'date' => $currentDate->copy()->addDays(rand(1, 30)),
                ]);
        
                $expenses_sum += $expense;
            }
        
            $currentDate->addMonth();
        }
 
        $balance = $incomeSum - $expenses_sum;
        \App\Models\Wallet::factory()->create([
            'user_id' => 1,
            'balance' => $balance,
        ]);
    }
}
