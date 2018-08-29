@extends('layouts.app')

@section('content')
<div class="container" style="text-align:center">
    <div class="row justify-content-center">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">

                <div class="well" style="margin-top:80px">
                    <h2 style="color:#4185dc; font-weight:bold; font-family:Times New Roman">Login</h2>
                    <hr>
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert" style="color:red">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        <div class="input-group" style="margin-bottom:20px;">
                            <span class="input-group-addon" style="color:#4185dc; font-size:18px"><i class="	glyphicon glyphicon-user"></i></span>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus  style="font-size:16px; height:44px">
                            
                        </div>
                        <!-- <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="input-group" style="margin-bottom:20px">
                            <span class="input-group-addon" style="color:#4185dc; font-size:18px"><i class="	glyphicon glyphicon-lock"></i></span>
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required  style="font-size:16px; height:44px">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <!-- <div class="col-md-6 offset-md-4"> -->
                                <div class="form-check">
                                    <div style="display: inline-block;">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    <div style="display: inline-block;">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                </div>
                            <!-- </div> -->
                        </div>

                        <div class="form-group row">
                            <!-- <div class="col-md-8 offset-md-4"> -->
                                <button type="submit" class="btn btn-primary" style="width:90%; margin-top:20px">
                                    {{ __('Login') }}
                                </button>

                                <!-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a> -->
                            <!-- </div> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
