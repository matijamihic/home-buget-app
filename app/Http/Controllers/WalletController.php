<?php

namespace App\Http\Controllers;

use App\Models\Wallet;

class WalletController extends Controller
{
    /**
     * @OA\Get(
     *     path="/wallet",
     *     summary="Get wallet information",
     *     @OA\Response(response="200", description="Wallet information"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show()
    {
        $user_id = auth()->id();
        $wallet = Wallet::where('user_id', $user_id)->first();

        return response()->json($wallet);
    }
}
