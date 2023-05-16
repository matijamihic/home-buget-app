<?php

namespace App\Http\Controllers;

use App\Models\Wallet;

class WalletController extends Controller
{
    /**
     * Display a state of the wallet.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user_id = auth()->id();
        $wallet = Wallet::where('user_id', $user_id)->first();

        return response()->json($wallet);
    }
}
