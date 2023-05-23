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
        Income::create($data);

        $amount = $data['amount'];
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $this->updateWalletBalance($wallet, $amount);

        return "Income deleted successfully.";
    }

    /**
     * Update a income and update the wallet balance.
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function updateIncome(array $data, $incomeId)
    {
        $income = Income::where('user_id', $data["user_id"])->findOrFail($incomeId);        
        $wallet = Wallet::where('user_id', $data["user_id"])->first();
    
        $this->updateWalletBalance($wallet, -$income->amount);
    
        $income->update($data);
    
        $this->updateWalletBalance($wallet, $income->amount);
    
        return "Income deleted successfully.";
    }

    /**
     * Delete the specified income and adjust the wallet balance.
     *
     * @param  int  $incomeId
     * @return void
     */
    public function deleteIncome($incomeId, $userId)
    {
        $income = Income::where('user_id', $userId)->findOrFail($incomeId);
        $wallet = Wallet::where('user_id', $userId)->first();

        $wallet->balance -= $income->amount;
        $wallet->save();
        $income->delete();

        return "Income deleted successfully.";
    }
}
