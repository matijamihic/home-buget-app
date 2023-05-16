<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class IncomeService
{
    /**
     * Create a new income and update the wallet balance.
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function createIncome(array $data)
    {
        $income = Income::create($data);

        $user_id = Auth::id();

        $amount = $data['amount'];
        $wallet = Wallet::where('user_id', $user_id)->first();
        $wallet->balance += $amount;
        $wallet->save();

        return $income;
    }

    /**
     * Update a income and update the wallet balance.
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function updateIncome(array $data, $incomeId)
    {
        $income = Income::findOrFail($incomeId);
        $wallet = Wallet::where('user_id', $income->user_id)->first();

        $wallet->balance -= $income->amount;
        $wallet->save();

        $income->update($data);

        $wallet->balance += $income->amount;
        $wallet->save();

        return $income;
    }

    /**
     * Delete the specified income and adjust the wallet balance.
     *
     * @param  int  $incomeId
     * @return void
     */
    public function deleteIncome($incomeId)
    {
        $income = Income::findOrFail($incomeId);
        $wallet = Wallet::where('user_id', $income->user_id)->first();
        $wallet->balance -= $income->amount;
        $wallet->save();
        $income->delete();

        return "Income deleted successfully.";
    }
}
