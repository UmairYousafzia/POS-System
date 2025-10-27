@extends('layouts.app')
@section('title','Products')
@section('content')
<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Add Product</h6></div>
      <div class="card-body">
        <form method="post" action="{{ route('pos.products.store') }}">
          @csrf
          <div class="mb-2"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
          <div class="mb-2"><label class="form-label">SKU</label><input name="sku" class="form-control" required></div>
          <div class="mb-2"><label class="form-label">Barcode</label><input name="barcode" class="form-control"></div>
          <div class="row g-2">
            <div class="col-md-6"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="">None</option>@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Unit</label><select name="unit_id" class="form-select"><option value="">None</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select></div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-md-6"><label class="form-label">Cost</label><input type="number" step="0.01" name="cost_price" class="form-control" value="0"></div>
            <div class="col-md-6"><label class="form-label">Price</label><input type="number" step="0.01" name="sale_price" class="form-control" value="0"></div>
          </div>
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
          <div class="mt-3 d-grid"><button class="btn btn-primary">Save</button></div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Products</h6></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead><tr><th>Name</th><th>SKU</th><th>Unit</th><th class="text-end">Price</th><th></th></tr></thead>
            <tbody>
              @foreach($products as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->sku }}</td>
                <td>{{ optional($p->unit)->short_name }}</td>
                <td class="text-end">{{ number_format($p->sale_price,2) }}</td>
                <td class="text-end">
                  <form method="post" action="{{ route('pos.products.destroy',$p) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('delete')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $products->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
