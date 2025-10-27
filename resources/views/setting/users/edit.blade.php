@extends('layouts.app')
@section('title', 'User Profile')
@section('content')


    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">Users</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Update Users</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{route('settings.users.index')}}"  class="btn btn-primary">Back</a>
                </div>
            </div>

        </div>
        <h6 class="mb-0 text-uppercase">Update User List</h6>
        <hr/>
        <div class="main-body">
            <div class="row">


                <div class="col-lg-12">
                    <form method="post" action="{{route('settings.users.update',$user -> id)}}">
                        @csrf
                        @method('put')
            

                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="name">Name<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') ?: $user->name }}" placeholder="Enter name...">

                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">  <label for="email">Email address<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-4 text-secondary">

                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?: $user->email }}"  placeholder="Enter email...">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">  <label for="email">Role<sup class="text-danger">*</sup></label></h6>
                                    </div>

                                    <div class="col-sm-4 text-secondary select2 ">
                                        <select class="single-select @error('role') is-invalid @enderror" id="role_id" name="role" >
                                            <option value="">---select role---</option>
                                            @foreach($roles as $role)
                                                @foreach($user->roles as $rol)
                                                    <option  value="{{ $role->name }}" {{ old('role', $rol->name) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        @error('role')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">

                                        <button type="submit" class="btn btn-primary px-4">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


@endsection








