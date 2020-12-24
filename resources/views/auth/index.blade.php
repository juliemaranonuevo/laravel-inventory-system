@extends('layouts.auth')

@section('head_title', 'Login')

@section('content')
    @include('layouts.message')
    <form method="POST" action="{{ route('login') }}" id="login">
        {{ csrf_field() }}
        <div class="form-group has-feedback" style="margin-bottom: 10px;">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <span class="fa fa-at form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback" style="margin-bottom: 10px;">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{ old('password') }}" required>
            <span class="fa fa-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-xs-12">
                <button type="submit" class="btn btn-success btn-block btn-flat sign-in"><i class="loading fa"></i>Sign In</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
@endsection