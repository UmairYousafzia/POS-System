@extends('layouts.app')
@section('title', 'Expenses')
@section('content')
<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Add Expense</h6></div>
      <div class="card-body">
        <form method="post" action="{{ route('pos.expenses.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" placeholder="Fuel, Rent, Wages...">
          </div>
          <div class="mb-2">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" placeholder="Optional detail">
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Amount</label>
              <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Date</label>
              <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-md-6">
              <label class="form-label">Account</label>
              <select name="account_id" class="form-select" required>
                @foreach($accounts as $a)
                  <option value="{{ $a->id }}">{{ $a->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Method</label>
              <select name="method" class="form-select">
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cheque">Cheque</option>
                <option value="card">Card</option>
                <option value="mobile_wallet">Mobile Wallet</option>
              </select>
            </div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-md-6">
              <label class="form-label">Reference</label>
              <input type="text" name="reference" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Note</label>
              <input type="text" name="note" class="form-control">
            </div>
          </div>
          <div class="mt-3 d-grid">
            <button class="btn btn-primary">Save Expense</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Recent Expenses</h6></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Date</th><th>Category</th><th>Description</th><th>Account</th><th class="text-end">Amount</th></tr></thead>
            <tbody>
              @forelse($expenses as $e)
              <tr>
                <td>{{ $e->date->format('Y-m-d') }}</td>
                <td>{{ $e->category }}</td>
                <td>{{ $e->description }}</td>
                <td>{{ optional($e->account)->name }}</td>
                <td class="text-end">{{ number_format($e->amount,2) }}</td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted">No expenses yet</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{ $expenses->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
