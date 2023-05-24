<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use App\Models\ExpenseCategory;

class ExpenseService extends WalletService
{
    /**
     * Create a new income and update the wallet balance.
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function createExpense(array $data)
    {
        Expense::create($data);

        $amount = $data['amount'];
        $wallet = Wallet::where('user_id', $data['user_id'])->first();

        $this->updateWalletBalance($wallet, -$amount);

        return "Expense created successfully.";
    }

    /**
     * Update a income and update the wallet balance.
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function updateExpense(array $data, $expenseId)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($expenseId);
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $this->updateWalletBalance($wallet, $expense->amount);

        $expense->update($data);

        $this->updateWalletBalance($wallet, -$expense->amount);

        return "Expense updated successfully.";
    }

    /**
     * Delete the specified income and adjust the wallet balance.
     *
     * @param  int  $incomeId
     * @return void
     */
    public function deleteExpense($expenseId)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($expenseId);
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $this->updateWalletBalance($wallet, $expense->amount);

        $expense->delete();

        return "Expense deleted successfully.";
    }
}
