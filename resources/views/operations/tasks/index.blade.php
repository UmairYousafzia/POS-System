@extends('layouts.app')
@section('title', 'Users  Data')
@section('content')
    <!--breadcrumb-->
  @if(session()->has('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Task</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Task List</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('project.create')
                <div class="btn-group">
                    <a href="{{route('operations.tasks.create')}}"  class="btn btn-primary">Add Task</a>
                </div>
            @endcan
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Task List</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Task Name</th>
                            <th>Owners</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Created By</th>
                            @if(auth()->user()->can('project.edit') || auth()->user()->can('project.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{$task->projects->name}}</td>
                            <td>{{$task->task}}</td>
                                <td>
                                      @foreach($task->users as $user)
                                                <span class="badge bg-primary p-2 ">{{$user->name}}</span>
                                      @endforeach
                                </td>
                            <td>{{$task->start_date}}</td>
                            <td>{{$task->end_date}}</td>
                            <td>{{$task->createBy->name}}</td>
                            <td class="d-flex">
                                <a href="{{route('operations.tasks.edit', $task->id)}}" class="btn btn-primary mx-3">Edit</a>

                                <form action="{{route('operations.tasks.destroy', $task->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class=" btn btn-danger">Delete</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection
@section('script')

@endsection
