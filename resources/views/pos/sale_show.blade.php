@extends('layouts.app')
@section('title', 'Sale ' . $sale->invoice_no)
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Invoice {{ $sale->invoice_no }}</h5>
    <a href="{{ route('pos.sell') }}" class="btn btn-outline-secondary btn-sm">New Sale</a>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-4">
        <div><strong>Customer:</strong> {{ optional($sale->party)->name ?? 'Walk-in' }}</div>
        <div><strong>Date:</strong> {{ $sale->date->format('Y-m-d') }}</div>
        <div><strong>Status:</strong> {{ ucfirst($sale->status) }}</div>
      </div>
      <div class="col-md-4">
        <div><strong>Warehouse:</strong> {{ optional($sale->warehouse)->name }}</div>
        <div><strong>Total:</strong> {{ number_format($sale->total,2) }}</div>
        <div><strong>Paid:</strong> {{ number_format($sale->paid,2) }}</div>
        <div><strong>Due:</strong> {{ number_format($sale->due,2) }}</div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Price</th>
            <th class="text-end">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sale->items as $i => $item)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->product->name }}</td>
            <td class="text-end">{{ number_format($item->quantity,3) }}</td>
            <td class="text-end">{{ number_format($item->price,2) }}</td>
            <td class="text-end">{{ number_format($item->total,2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr><th colspan="4" class="text-end">Subtotal</th><th class="text-end">{{ number_format($sale->subtotal,2) }}</th></tr>
          <tr><th colspan="4" class="text-end">Discount</th><th class="text-end">{{ number_format($sale->discount,2) }}</th></tr>
          <tr><th colspan="4" class="text-end">Tax</th><th class="text-end">{{ number_format($sale->tax,2) }}</th></tr>
          <tr><th colspan="4" class="text-end">Shipping</th><th class="text-end">{{ number_format($sale->shipping,2) }}</th></tr>
          <tr><th colspan="4" class="text-end">Grand Total</th><th class="text-end">{{ number_format($sale->total,2) }}</th></tr>
        </tfoot>
      </table>
    </div>

    <hr/>
    <h6>Payments</h6>
    <div class="table-responsive">
      <table class="table table-sm">
        <thead>
          <tr><th>Date</th><th>Account</th><th>Method</th><th class="text-end">Amount</th><th>Reference</th></tr>
        </thead>
        <tbody>
          @forelse($sale->payments as $p)
          <tr>
            <td>{{ $p->paid_at->format('Y-m-d') }}</td>
            <td>{{ optional($p->account)->name }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$p->method)) }}</td>
            <td class="text-end">{{ number_format($p->amount,2) }}</td>
            <td>{{ $p->reference }}</td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted">No payments yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($sale->due > 0)
    <form class="row g-2" method="post" action="{{ route('pos.payments.sale', $sale) }}">
      @csrf
      <div class="col-md-2"><input type="date" name="paid_at" class="form-control" value="{{ date('Y-m-d') }}" required></div>
      <div class="col-md-3">
        <select name="account_id" class="form-select" required>
          @foreach(\App\Models\Account::orderBy('name')->get() as $a)
            <option value="{{ $a->id }}">{{ $a->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="method" class="form-select">
          <option value="cash">Cash</option>
          <option value="bank_transfer">Bank Transfer</option>
          <option value="cheque">Cheque</option>
          <option value="card">Card</option>
          <option value="mobile_wallet">Mobile Wallet</option>
        </select>
      </div>
      <div class="col-md-2"><input type="number" step="0.01" name="amount" class="form-control" value="{{ $sale->due }}" required></div>
      <div class="col-md-3 d-grid"><button class="btn btn-success">Record Payment</button></div>
    </form>
    @endif
  </div>
</div>
@endsection
