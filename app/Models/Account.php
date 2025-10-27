<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['type','name','account_no','opening_balance'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
