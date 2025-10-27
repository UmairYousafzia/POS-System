<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function storeForSale(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'method' => 'required|in:cash,bank_transfer,cheque,card,mobile_wallet',
            'amount' => 'required|numeric|min:0.01',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string',
            'note' => 'nullable|string',
        ]);
        $sale->payments()->create($data);
        $sale->paid = $sale->payments()->sum('amount');
        $sale->due = max(0, $sale->total - $sale->paid);
        $sale->save();
        return redirect()->route('pos.sales.show', $sale)->with('success','Payment recorded');
    }

    public function storeForPurchase(Request $request, Purchase $purchase)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'method' => 'required|in:cash,bank_transfer,cheque,card,mobile_wallet',
            'amount' => 'required|numeric|min:0.01',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string',
            'note' => 'nullable|string',
        ]);
        $purchase->payments()->create($data);
        $purchase->paid = $purchase->payments()->sum('amount');
        $purchase->due = max(0, $purchase->total - $purchase->paid);
        $purchase->save();
        return redirect()->back()->with('success','Payment recorded');
    }
}
