<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Services\ExpenseService;


class ExpenseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_can_be_created_and_balance_is_updated(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        $expenseCategory = ExpenseCategory::factory()->create();

        // Prepare the expense data
        $expenseData = [
            "description" => "Gas",
            "amount" => 88,
            "expense_category_id" => $expenseCategory->id,
            'user_id' => $user->id,
        ];

        // Create the expense
        $expenseService = new ExpenseService("test");

        $expenseService->createExpense($expenseData);

        // Check that the expense was created
        $expense = Expense::first();

        $this->assertEquals($expenseData['amount'], $expense->amount);

        // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals($expenseData['amount'], -$balance);
    }

    //test that expense can be updated and wallet balance is updated

    public function test_expense_can_be_updated_and_balance_is_updated(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        // Create an expense category\
        $expenseCategory = ExpenseCategory::factory()->create();

        // Prepare the expense data
        $expenseData = [
            "description" => "Gas",
            "amount" => 100,
            "expense_category_id" => $expenseCategory->id,
            'user_id' => $user->id,
        ];

        // Create the expense
        $expenseService = new ExpenseService("test");

        $expenseService->createExpense($expenseData);

        $expense = Expense::orderBy('id', 'desc')->first();
        
        // Update the expense
        $expenseData = [
            "description" => "Gas and window washer fluid",
            "amount" => 200,
        ];
        
        $expenseService->updateExpense($expenseData, $expense->id);

        // Check that the expense was updated
        $expense = Expense::first();
        $this->assertEquals($expenseData['amount'], $expense->amount);
        $this->assertEquals($expenseData['description'], $expense->description);


        // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals($expenseData['amount'], -$balance);
    }

    //test that expense can be deleted and wallet balance is updated
    public function test_that_expense_can_be_deleted_and_balance_is_updated()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        // Create an expense category\
        $expenseCategory = ExpenseCategory::factory()->create();

        // Prepare the expense data
        $expenseData = [
            "description" => "Gas",
            "amount" => 100,
            "expense_category_id" => $expenseCategory->id,
            'user_id' => $user->id,
        ];

        // Create the expense
        $expenseService = new ExpenseService("test");

        $expenseService->createExpense($expenseData);

        $expense = Expense::orderBy('id', 'desc')->first();

        // Delete the expense
        $expenseService->deleteExpense($expense->id);

        // Check that the expense was deleted
        $expense = Expense::first();
        $this->assertNull($expense);

        // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals(0, $balance);
    }
}