@extends('layouts.app')
@section('title','Purchases History')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">Purchases</h6>
    <a href="{{ route('pos.purchase') }}" class="btn btn-sm btn-primary">New Purchase</a>
  </div>
  <div class="card-body">
    <form class="row g-2 mb-3" method="get">
      <div class="col-md-3"><input class="form-control" name="invoice_no" placeholder="Invoice #" value="{{ request('invoice_no') }}"></div>
      <div class="col-md-3"><input class="form-control" name="supplier" placeholder="Supplier" value="{{ request('supplier') }}"></div>
      <div class="col-md-2"><input type="date" class="form-control" name="from" value="{{ request('from') }}"></div>
      <div class="col-md-2"><input type="date" class="form-control" name="to" value="{{ request('to') }}"></div>
      <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary">Filter</button></div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead><tr>
          <th>Date</th><th>Invoice</th><th>Supplier</th><th>Warehouse</th>
          <th class="text-end">Total</th><th class="text-end">Paid</th><th class="text-end">Due</th>
        </tr></thead>
        <tbody>
          @forelse($purchases as $p)
            <tr>
              <td>{{ \Illuminate\Support\Carbon::parse($p->date)->format('Y-m-d') }}</td>
              <td>{{ $p->invoice_no }}</td>
              <td>{{ optional($p->party)->name }}</td>
              <td>{{ optional($p->warehouse)->name }}</td>
              <td class="text-end">{{ number_format($p->total,2) }}</td>
              <td class="text-end">{{ number_format($p->paid,2) }}</td>
              <td class="text-end">{{ number_format($p->due,2) }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">No purchases found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $purchases->links() }}
  </div>
</div>
@endsection
