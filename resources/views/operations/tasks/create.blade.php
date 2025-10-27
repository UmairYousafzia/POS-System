@extends('layouts.app')
@section('title', 'User Profile')
@section('content')

    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">Tasks</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Give Task</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{route('operations.tasks.index')}}"  class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Create Task</h6>
        <hr/>
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <form method="post" action="{{ route('operations.tasks.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="user_id">Projects<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-4 text-secondary select2 ">
                                        <select class="single-select @error('project_id') is-invalid @enderror  " id="project_id" name="project_id" >
                                            <option value="" selected>---select Project---</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                            @endforeach

                                        </select>
                                        @error('project_id')
                                            <div class="invalid-feedback">
                                                {{'Project Filed Is Required'}}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="name">Task Name<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-4 text-secondary">
                                        <input type="text" name="task" id="task" class="form-control @error('task') is-invalid @enderror"  value="{{old('task')}}" placeholder="">

                                        @error('task')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">  <label for="email">Start Date<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-4 text-secondary">

                                        <input type="date" id="start_date" name="start_date" class="form-control   @error('start_date') is-invalid @enderror" value="{{old('start_date')}}"   placeholder="Enter Start Date...">
                                        @error('start_date')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="end_date">End Date<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-4 text-secondary">
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror " id="end_date" name="end_date" value="{{old('e_date')}}" placeholder="Enter End Date...">
                                        @error('end_date')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="user_id">Owners<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-4 text-secondary select2 ">
                                        <select class="single-select @error('user_id') is-invalid @enderror " id="user_id" name="user_id[]" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}"  {{ (is_array(old('user_id')) and in_array($user->id, old('user_id'))) ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                        <div class="invalid-feedback">
                                            {{'Owners Filed Required'}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">

                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/gh/msurguy/MultiSelectTag@latest/dist/multiselect.min.js"></script>

    <script>
        new MultiSelectTag('user_id');
    </script>
@endsection
