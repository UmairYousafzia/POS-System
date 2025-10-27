<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_type','payable_id','account_id','method','amount','paid_at','reference','note'
    ];

    protected $casts = [
        'paid_at' => 'date'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
