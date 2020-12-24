@extends('layouts.master')

@section('page_script')
    <script src="/js/super_admin/offices.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">Update</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <!-- general form elements disabled -->
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Update Office</strong></h3>
                    </div>
                    <form action="/offices/{{ $office->id }}/edit" method="POST"  class="data" id="data">
                        @method('PATCH')
                        @csrf
                        <div class="box-body">
                            <div class="box-body with-border">
                                <div class="{{ $errors->has('office_name') ? 'has-error' : '' }}">
                                    <label for="name">Office Name <span class="text-danger">*</span></label>
                                    <input name="office_name" type="text" class="form-control form-group" id="office_name" 
                                    placeholder="Enter Office Name" style="min-width: 100%;" required value="{{ $office->office_name }}">
                                </div>
                                <div class="{{ $errors->has('office_code') ? 'has-error' : '' }}">
                                    <label for="name">Office Code <span class="text-danger">*</span></label>
                                    <input name="office_code" type="text" class="form-control form-group" id="office_code" 
                                    placeholder="Enter Office Code" style="min-width: 100%;" required value="{{ $office->office_code }}">
                                </div>
                                <div class="{{ $errors->has('telephone') ? 'has-error' : '' }}">
                                    <label for="name">Contact Number <span class="text-danger">*</span></label>
                                    <input name="telephone" type="text" class="form-control form-group" id="telephone" 
                                    placeholder="Enter Contact Number" style="min-width: 100%;" required value="{{ $office->telephone }}">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div>
                                <button type="button" class="btn btn-default" onclick="window.location='/offices'">Cancel</button>
                                <button form="data" type="submit" class="btn btn-primary save pull-right" style="margin-left:5px;"><i class="loading fa "></i><span>  Update</span></button>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                    <!-- /.box -->
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
<!-- /.content-wrapper -->
@endsection