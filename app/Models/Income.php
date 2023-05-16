<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'amount',
    ];

    /**
     * Get the user associated with the income.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
