<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no','party_id','warehouse_id','subtotal','discount','tax','shipping','total','paid','due','date','status'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
