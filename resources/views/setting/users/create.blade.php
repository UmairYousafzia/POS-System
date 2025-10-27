@extends('layouts.app')
@section('title', 'User Profile')
@section('content')

    <div class="container">
        <div id = "message">

        </div>
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">Users</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Creat Users</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{route('settings.users.index')}}"  class="btn btn-primary">Back</a>
                </div>
            </div>

        </div>
        <h6 class="mb-0 text-uppercase">Create User List</h6>
        <hr/>
        <div class="main-body">
            <div class="row">

                <div class="col-lg-12">
                    <form id = "myform">
                        @csrf

                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="name">Name<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{old('name')}}" placeholder="Enter name...">

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
                                    <div class="col-sm-9 text-secondary">

                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"   placeholder="Enter email...">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="role_id">Select Role<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary select2 ">
                                        <select class="single-select @error('role') is-invalid @enderror" id="role_id" name="role" >
                                            <option value="" selected>---Role user---</option>
                                            @foreach($roles as $role)
                                                <option  value="{{$role->name}}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="role">Password<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="show_hide_password">
                                        <input type="password" class="form-control  @error('password') is-invalid @enderror "  id="show_hide_password" name="password"  placeholder="Enter Password....">
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="password_confirmation" class="form-label">Confirm Password <sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="password_confirmation">
                                        <input type="password"  name="password_confirmation"   id="password_confirmation"  class="form-control  @error('password_confirmation') is-invalid @enderror "  placeholder="Confirm Password" >

                                        @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">

                                        <button type="submit" id = "submit" class="btn btn-primary px-4">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
@endsection
@section('script')
        <script>
            $(document).ready(function (){
                $("#submit").click(function (e){
                    e.preventDefault();
                    var form = $("#myform")[0];
                    var data = new FormData(form);

                    $.ajax({
                        url : "{{route('settings.users.store')}}" ,
                        type : "post",
                        data : data,
                        processData : false,
                        contentType : false,

                        success : function (data){
                            toastr.success("Good Job!");

                        },
                        error : function (e) {
                            toastr.warning("error");
                        }

                    });

                });

            });
        </script>

@endsection






