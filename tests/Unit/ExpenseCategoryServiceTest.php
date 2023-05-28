<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ExpenseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Services\ExpenseCategoryService;
use App\Models\Expense;
use App\Services\ExpenseService;
use App\Models\Wallet;

class ExpenseCategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_category_can_be_created(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Prepare the expense category data
        $expenseCategoryData = [
            "name" => "Gas",
            'user_id' => $user->id,
        ];

        // Create the expense category
        $expenseCategoryService = new ExpenseCategoryService("test");

        $expenseCategoryService->createExpenseCategory($expenseCategoryData);

        // Check that the expense category was created
        $expenseCategory = ExpenseCategory::first();

        $this->assertEquals(strtolower($expenseCategoryData['name']), $expenseCategory->name);
    }

    public function test_expense_category_can_be_deleted_and_all_expenses_in_that_category_are_deleted(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Prepare the expense category data
        $expenseCategoryData = [
            "name" => "Gas",
            'user_id' => $user->id,
        ];

        // Create the expense category
        $expenseCategoryService = new ExpenseCategoryService("test");

        $expenseCategoryService->createExpenseCategory($expenseCategoryData);

        // Check that the expense category was created
        $expenseCategory = ExpenseCategory::first();

        $this->assertEquals(strtolower($expenseCategoryData['name']), $expenseCategory->name);

        // Prepare the expense data
        $expenseData = [
            "description" => "Gas",
            "amount" => 200,
            "expense_category_id" => $expenseCategory->id,
            'user_id' => $user->id,
        ];

        // Create the expense
        $expenseService = new ExpenseService("test");

        $expenseService->createExpense($expenseData);

        // Check that the expense was created
        $expense = Expense::first();

        $this->assertEquals($expenseData['amount'], $expense->amount);

        // Delete the expense category
        $expenseCategoryService->deleteExpenseCategory($expenseCategory->id);

        // Check that the expense category was deleted
        $expenseCategory = ExpenseCategory::first();

        $this->assertNull($expenseCategory);

        // // Check that the expense was deleted
        $expense = Expense::first();

        $this->assertNull($expense);
    }

}
