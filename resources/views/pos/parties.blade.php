@extends('layouts.app')
@section('title','Clients & Suppliers')
@section('content')
<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Add Client/Supplier</h6></div>
      <div class="card-body">
        <form method="post" action="{{ route('pos.parties.store') }}">
          @csrf
          <div class="mb-2"><label class="form-label">Type</label><select name="type" class="form-select" required><option value="customer">Customer</option><option value="supplier">Supplier</option></select></div>
          <div class="mb-2"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
          <div class="mb-2"><label class="form-label">Company</label><input name="company" class="form-control"></div>
          <div class="mb-2"><label class="form-label">Phone</label><input name="phone" class="form-control"></div>
          <div class="mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
          <div class="mb-2"><label class="form-label">Address</label><input name="address" class="form-control"></div>
          <div class="row g-2">
            <div class="col-md-6"><label class="form-label">Opening Balance</label><input type="number" step="0.01" name="opening_balance" class="form-control" value="0"></div>
            <div class="col-md-6"><label class="form-label">Balance Type</label><select name="balance_type" class="form-select"><option value="">None</option><option value="receivable">Receivable</option><option value="payable">Payable</option></select></div>
          </div>
          <div class="mt-3 d-grid"><button class="btn btn-primary">Save</button></div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Customers</h6></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Name</th><th>Phone</th><th>Company</th><th></th></tr></thead>
            <tbody>
              @foreach($customers as $c)
              <tr>
                <td>{{ $c->name }}</td><td>{{ $c->phone }}</td><td>{{ $c->company }}</td>
                <td class="text-end">
                  <form method="post" action="{{ route('pos.parties.destroy',$c) }}" onsubmit="return confirm('Delete?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-header"><h6 class="mb-0">Suppliers</h6></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Name</th><th>Phone</th><th>Company</th><th></th></tr></thead>
            <tbody>
              @foreach($suppliers as $s)
              <tr>
                <td>{{ $s->name }}</td><td>{{ $s->phone }}</td><td>{{ $s->company }}</td>
                <td class="text-end">
                  <form method="post" action="{{ route('pos.parties.destroy',$s) }}" onsubmit="return confirm('Delete?')">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
