<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Income;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Services\IncomeService;


class IncomeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_income_can_be_created_and_balance_is_updated(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        // Prepare the income data
        $incomeData = [
            'amount' => 100,
            'description' => 'Test Income',
            'user_id' => $user->id,
        ];

        // Create the income
        $incomeService = new IncomeService("test");

        $incomeService->createIncome($incomeData);

        // Check that the income was created
        $income = Income::first();
        $this->assertEquals($incomeData['amount'], $income->amount);

        // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals($incomeData['amount'], $balance);
    }

    // test that income can be updated and wallet balance is updated
    public function test_income_can_be_updated_and_balance_is_updated(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        // Prepare the income data
        $incomeData = [
            'amount' => 100,
            'description' => 'Test Income',
            'user_id' => $user->id,
        ];

        // Create the income
        $incomeService = new IncomeService("test");

        $incomeService->createIncome($incomeData);

        // // Check that the income was created
        $income = Income::first();
        $this->assertEquals($incomeData['amount'], $income->amount);

        // // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals($incomeData['amount'], $balance);

        // // Update the income
        $incomeData = [
            'amount' => 200,
            'description' => 'Test Income',
            'user_id' => $user->id,
        ];        
        $incomeService->updateIncome($incomeData, $income->id);

        // // Check that the income was updated
        $income = Income::first();
        $this->assertEquals($incomeData['amount'], $income->amount);

        // // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals($incomeData['amount'], $balance);
    }

    // test that income can be deleted and wallet balance is updated
    public function test_income_can_be_deleted_and_balance_is_updated(): void
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a wallet for the user
        Wallet::factory()->create(['user_id' => $user->id]);

        // Prepare the income data
        $incomeData = [
            'amount' => 100,
            'description' => 'Test Income',
            'user_id' => $user->id,
        ];

        // Create the income
        $incomeService = new IncomeService("test");

        $incomeService->createIncome($incomeData);

        // // Check that the income was created
        $income = Income::first();
        $this->assertEquals($incomeData['amount'], $income->amount);

        // // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals($incomeData['amount'], $balance);

        // // Delete the income
        $incomeService->deleteIncome($income->id, $user->id);

        // // Check that the income was deleted
        $income = Income::first();
        $this->assertNull($income);

        // // Check that the wallet balance was updated
        $balance = Wallet::where('user_id', $user->id)->first()->balance;

        $this->assertEquals(0, $balance);
    }
}