@extends('layouts.app')

@section('content')
<div class="container"  style="text-align:center">
    <div class="row justify-content-center">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">

                <div class="well" style="margin-top:80px">
                    <h2 style="color:#4185dc; font-weight:bold; font-family:Times New Roman">Register</h2>
                    <hr>

                    <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                        @csrf

                        @if ($errors->has('name'))
                            <div class="invalid-feedback" role="alert" style="color:red">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                        <div class="input-group" style="margin-bottom:20px;">
                            <span class="input-group-addon" style="color:#4185dc; font-size:18px"><i class="	glyphicon glyphicon-user"></i></span>
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus  style="font-size:16px; height:44px">
                            
                        </div>

                        <!-- <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->
                        @if ($errors->has('email'))
                            <div class="invalid-feedback" role="alert" style="color:red">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                        <div class="input-group" style="margin-bottom:20px;">
                            <span class="input-group-addon" style="color:#4185dc; font-size:18px"><i class="	glyphicon glyphicon-envelope"></i></span>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required  style="font-size:16px; height:44px">
                            
                        </div>

                        <!-- <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->
                        @if ($errors->has('password'))
                            <div class="invalid-feedback" role="alert" style="color:red">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif

                        <div class="input-group" style="margin-bottom:20px">
                            <span class="input-group-addon" style="color:#4185dc; font-size:18px"><i class="	glyphicon glyphicon-lock"></i></span>
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required  style="font-size:16px; height:44px">
                            
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

                        <div class="input-group" style="margin-bottom:20px">
                            <span class="input-group-addon" style="color:#4185dc; font-size:18px"><i class="	glyphicon glyphicon-ok-sign"></i></span>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required style="font-size:16px; height:44px">
                        </div>

                        <!-- <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div> -->

                        <div class="form-group row mb-0">
                            <!-- <div class="col-md-6 offset-md-4"> -->
                            <button type="submit" class="btn btn-primary" style="width:90%; margin-top:20px">
                                    {{ __('Register') }}
                                </button>
                            <!-- </div> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
