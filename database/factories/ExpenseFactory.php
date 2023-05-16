<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {

        $date = $this->faker->dateTimeBetween('-1 month', 'now');
        return [
            'user_id' => null,
            'expense_category_id' => null,
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->sentence,
            'date' => $date,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
