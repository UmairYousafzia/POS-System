@extends('layouts.app')
@section('title','Locations')
@section('content')
<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Add Location</h6></div>
      <div class="card-body">
        <form method="post" action="{{ route('pos.locations.store') }}">
          @csrf
          <div class="mb-2"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
          <div class="mb-2"><label class="form-label">Address</label><input name="address" class="form-control"></div>
          <div class="mb-2"><label class="form-label">City</label><input name="city" class="form-control"></div>
          <div class="mt-2 d-grid"><button class="btn btn-primary">Save</button></div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><h6 class="mb-0">Locations</h6></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Name</th><th>Address</th><th>City</th><th></th></tr></thead>
            <tbody>
              @foreach($locations as $loc)
              <tr>
                <td>{{ $loc->name }}</td>
                <td>{{ $loc->address }}</td>
                <td>{{ $loc->city }}</td>
                <td class="text-end">
                  <form action="{{ route('pos.locations.destroy',$loc) }}" method="post" onsubmit="return confirm('Delete?')">
                    @csrf @method('delete')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $locations->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
