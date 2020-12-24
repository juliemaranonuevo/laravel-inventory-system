@extends('layouts.master')

@section('page_script')
    <script src="/js/super_admin/users.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li  class="active">{{ $page['parent'] }}</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <form method="POST" action="/users/change-password" class="form-horizontal uppercase" id="data">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="current_password" class="control-label">CURRENT PASSWORD</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" alt="Current Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="new_password" class="control-label">NEW PASSWORD</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" alt="New Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="retype_password" class="control-label">RE-TYPE NEW PASSWORD</label>
                                    <input type="password" class="form-control" id="retype_password" name="retype_password" alt="Re-type Password">
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="col-sm-12">
                                    <button form="data" type="submit" class="btn btn-success"><i class="loading fa fa-save"></i> SAVE CHANGES</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection