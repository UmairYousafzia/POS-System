@extends('layouts.app')
@section('title','Sales History')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">Sales</h6>
    <a href="{{ route('pos.sell') }}" class="btn btn-sm btn-primary">New Sale</a>
  </div>
  <div class="card-body">
    <form class="row g-2 mb-3" method="get">
      <div class="col-md-3"><input class="form-control" name="invoice_no" placeholder="Invoice #" value="{{ request('invoice_no') }}"></div>
      <div class="col-md-3"><input class="form-control" name="customer" placeholder="Customer" value="{{ request('customer') }}"></div>
      <div class="col-md-2"><input type="date" class="form-control" name="from" value="{{ request('from') }}"></div>
      <div class="col-md-2"><input type="date" class="form-control" name="to" value="{{ request('to') }}"></div>
      <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary">Filter</button></div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead><tr>
          <th>Date</th><th>Invoice</th><th>Customer</th><th>Warehouse</th>
          <th class="text-end">Total</th><th class="text-end">Paid</th><th class="text-end">Due</th><th></th>
        </tr></thead>
        <tbody>
          @forelse($sales as $s)
            <tr>
              <td>{{ \Illuminate\Support\Carbon::parse($s->date)->format('Y-m-d') }}</td>
              <td>{{ $s->invoice_no }}</td>
              <td>{{ optional($s->party)->name ?: 'Walk-in' }}</td>
              <td>{{ optional($s->warehouse)->name }}</td>
              <td class="text-end">{{ number_format($s->total,2) }}</td>
              <td class="text-end">{{ number_format($s->paid,2) }}</td>
              <td class="text-end">{{ number_format($s->due,2) }}</td>
              <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('pos.sales.show',$s) }}">View</a></td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted">No sales found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $sales->links() }}
  </div>
</div>
@endsection
