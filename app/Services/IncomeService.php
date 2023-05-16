<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class IncomeService extends WalletService
{
    /**
     * Create a new income and update the wallet balance.
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function createIncome(array $data)
    {
        $data["user_id"] = Auth::id();

        $income = Income::create($data);

        $amount = $data['amount'];
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $this->updateWalletBalance($wallet, $amount);

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
        $income = Income::where('user_id', Auth::id())->findOrFail($incomeId);        
        $wallet = Wallet::where('user_id', Auth::id())->findOrFail();
    
        $this->updateWalletBalance($wallet, -$income->amount);
    
        $income->update($data);
    
        $this->updateWalletBalance($wallet, $income->amount);
    
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
        $income = Income::where('user_id', Auth::id())->findOrFail($incomeId);
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $wallet->balance -= $income->amount;
        $wallet->save();
        $income->delete();

        return "Income deleted successfully.";
    }
}
