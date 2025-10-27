<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    public function index()
    {
        $customers = Party::where('type','customer')->orderBy('name')->get();
        $suppliers = Party::where('type','supplier')->orderBy('name')->get();
        return view('pos.parties', compact('customers','suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:customer,supplier',
            'name' => 'required|string',
            'company' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'balance_type' => 'nullable|in:receivable,payable',
        ]);
        Party::create($data);
        return back()->with('success','Saved');
    }

    public function update(Request $request, Party $party)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'company' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);
        $party->update($data);
        return back()->with('success','Updated');
    }

    public function destroy(Party $party)
    {
        $party->delete();
        return back()->with('success','Deleted');
    }
}
