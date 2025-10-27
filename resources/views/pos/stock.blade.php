@extends('layouts.app')
@section('title','Stock On Hand')
@section('content')
<div class="card">
  <div class="card-header"><h6 class="mb-0">Stock On Hand</h6></div>
  <div class="card-body">
    <form class="row g-2 mb-3">
      <div class="col-md-4">
        <select name="warehouse_id" class="form-select">
          <option value="">All Warehouses</option>
          @foreach($warehouses as $w)
            <option value="{{ $w->id }}" @selected(($filters['warehouse_id'] ?? '') == $w->id)>{{ $w->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <select name="product_id" class="form-select">
          <option value="">All Products</option>
          @foreach($products as $p)
            <option value="{{ $p->id }}" @selected(($filters['product_id'] ?? '') == $p->id)>{{ $p->name }} ({{ $p->sku }})</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-outline-secondary">Filter</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead><tr><th>Product</th><th>Warehouse</th><th class="text-end">Stock</th></tr></thead>
        <tbody>
          @forelse($rows as $r)
            <tr>
              <td>{{ $r['product']->name }}</td>
              <td>{{ optional($r['warehouse'])->name }}</td>
              <td class="text-end">{{ number_format($r['stock'],3) }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted">No rows</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
