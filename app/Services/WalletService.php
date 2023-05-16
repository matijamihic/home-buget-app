<?php

namespace App\Services;

class WalletService
{
    protected function updateWalletBalance($wallet, $amount)
    {
        $wallet->balance += $amount;
        $wallet->save();
    }
}
