@extends('auth.layout.app')
@section('title', 'Login')

@section('content')

        <div class="card">
            <div class="card-body">
                <div class="border p-4 rounded">
                   
                    <div class="login-separater text-center mb-4"> <span>SIGN IN WITH EMAIL</span>
                        <hr/>
                    </div>
                    <div class="form-body">
                        <form method="POST" action="{{ route('login.store') }}" class="row g-3">
                            @csrf


                            <div class="col-12">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" placeholder="Email Address">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>



                            <div class="col-12">
                                <label for="password" class="form-label">Enter Password</label>
                                <div class="input-group" id="password">
                                    <input type="password" name="password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="password" value="12345678" placeholder="Enter Password"> <a href="#" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="remember_me" type="checkbox" id="remember_me" checked>
                                    <label class="form-check-label"  for="remember_me">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">	<a href="{{ route('password.request') }}">Forgot Password ?</a>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>






@endsection
