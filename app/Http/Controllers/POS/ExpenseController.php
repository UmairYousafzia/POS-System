<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('name')->get();
        $expenses = Expense::with('account')->orderByDesc('date')->paginate(20);
        return view('pos.expenses', compact('accounts','expenses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'method' => 'required|in:cash,bank_transfer,cheque,card,mobile_wallet',
            'reference' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $expense = Expense::create([
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'date' => $data['date'],
            'account_id' => $data['account_id'],
        ]);

        Payment::create([
            'payable_type' => 'expense',
            'payable_id' => $expense->id,
            'account_id' => $data['account_id'],
            'method' => $data['method'],
            'amount' => $data['amount'],
            'paid_at' => $data['date'],
            'reference' => $data['reference'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('pos.expenses.index')->with('success','Expense recorded');
    }
}
