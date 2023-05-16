<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ExpenseCategory extends Model
{
    use HasFactory; 
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}