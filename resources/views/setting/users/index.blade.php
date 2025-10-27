@extends('layouts.app')
@section('title', 'Users  Data')
@section('content')
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Users</div>
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
                            <a href="{{route('settings.users.create')}}"  class="btn btn-primary">Add Users</a>
                        </div>
                    @endcan
                </div>
            </div>
            <!--end breadcrumb-->
            <h6 class="mb-0 text-uppercase">Users List</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created By</th>
                                    @if(auth()->user()->can('user.edit') || auth()->user()->can('user.delete'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)

                               <tr>
                                   <td>{{$user->name}}</td>
                                   <td>{{$user->email}}</td>
                                   <td>
                                       @foreach($user->roles as $role)
                                           <span class="badge bg-primary p-2 ">{{$role->name}}</span>
                                       @endforeach
                                   </td>
                                   <td>{{$user->createBy->name ?? ''}}</td>
                                   @if(auth()->user()->can('user.edit') || auth()->user()->can('user.delete'))
                                       <td class="d-flex gap-4">
                                           @can('user.edit')
                                               <a href="{{route('settings.users.edit',$user->id)}}" class="btn btn-primary"> Edit</a>
                                           @endcan
                                           @can('user.delete')
                                               @if(auth()->id() !== $user->id)
                                                   <form action="{{route('settings.users.destroy',$user->id)}}" method="post">
                                                       @csrf
                                                       @method('DELETE')

                                                       <button type="submit" class="btn btn-danger">Delete</button>
                                                   </form>
                                               @endif
                                           @endcan
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
        $(document).ready(function() {
            $('#example').DataTable({

                "paging" : true,
                "pagingType": "full_numbers"

            });


        });
    </script>

@endsection
