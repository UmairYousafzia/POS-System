@extends('layouts.app')
@section('title','Warehouses')
@section('content')
<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Add Warehouse</h6></div>
      <div class="card-body">
        <form method="post" action="{{ route('pos.warehouses.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label">Location</label>
            <select name="location_id" class="form-select" required>
              @foreach($locations as $l)
                <option value="{{ $l->id }}">{{ $l->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
          <div class="mb-2"><label class="form-label">Code</label><input name="code" class="form-control"></div>
          <div class="mt-2 d-grid"><button class="btn btn-primary">Save</button></div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Warehouses</h6></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Name</th><th>Code</th><th>Location</th><th></th></tr></thead>
            <tbody>
              @foreach($warehouses as $w)
              <tr>
                <td>{{ $w->name }}</td>
                <td>{{ $w->code }}</td>
                <td>{{ optional($w->location)->name }}</td>
                <td class="text-end">
                  <form action="{{ route('pos.warehouses.destroy',$w) }}" method="post" onsubmit="return confirm('Delete?')">
                    @csrf @method('delete')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $warehouses->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
