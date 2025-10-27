<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['category','description','amount','date','account_id'];

    protected $casts = [
        'date' => 'date'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
