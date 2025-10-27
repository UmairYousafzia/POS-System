@extends('layouts.app')
@section('title', 'Users  Data')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Roles</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Users List</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('user.create')

                <div class="btn-group">
                    <a href="{{route('settings.roles.create')}}" class="btn btn-primary">Add Role</a>
                </div>

            @endcan
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Role List</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width: 100%;  ">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Permission</th>
                        @if(auth()->user()->can('role.edit') || auth()->user()->can('role.delete'))
                            <th>Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($roles as $role)
                        <tr>
                            <td>{{$role->name}}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-primary p-2 ">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            @if(auth()->user()->can('role.edit') || auth()->user()->can('role.delete'))

                                <td class="d-flex">
                                    @if($role->id===1)
                                        <span class="badge bg-warning p-2 "> You can't Edit and Delete</span>
                                    @else

                                        @can('role.edit')
                                            <a href="{{route('settings.roles.edit', $role->id)}}" class="btn btn-primary mx-2">Edit</a>
                                        @endcan
                                        @can('role.delete')
                                            <form action="{{route('settings.roles.destroy',$role->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        @endcan

                                    @endif
                                </td>

                            @endif

                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>

        @endsection

        @section('script')
            <script>
                $(document).ready(function () {
                    $('#example').DataTable({ order: [2, 'asc']}  );


                });


            </script>

@endsection
