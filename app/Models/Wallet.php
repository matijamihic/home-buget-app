<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    /**
     * Get the user associated with the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}