@extends('layouts.app')
@section('title', 'User Profile')
@section('content')

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                @php($avatar = auth()->user()->avatar)
                                <img id="profile-preview" src="{{ $avatar ? asset('storage/'.$avatar) : asset('assets/images/avatars/avatar-1.png') }}" alt="Profile" class="rounded-circle p-1 bg-primary" width="110" onerror="this.onerror=null;this.src='{{ asset('assets/images/avatars/avatar-1.png') }}';">
                               
                                <div class="mt-3">
                                    <h4>{{auth()->user()->name}}</h4>
                                    <p class="text-secondary mb-1">{{auth()->user()->role}}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0"><label for="name">Name<sup class="text-danger">*</sup></label></h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') ?: auth()->user()->name }}" placeholder="Enter name...">

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

                                        <input type="email" class="form-control @error('email') is-invalid @enderror " id="email" name="email" value="{{ old('email') ?: auth()->user()->email }}" placeholder="Enter email...">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">

                                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <script>
                        document.getElementById('image').addEventListener('change', function(e){
                            const file = e.target.files && e.target.files[0];
                            if(!file) return;
                            const reader = new FileReader();
                            reader.onload = function(evt){
                                const img = document.getElementById('profile-preview');
                                if (img && evt.target.result) {
                                    img.src = evt.target.result;
                                }
                            }
                            reader.readAsDataURL(file);
                        });
                    </script>
            </div>
        </div>
    </div>


@endsection








