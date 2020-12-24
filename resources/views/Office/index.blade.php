@extends('layouts.master')

@section('page_script')
    <script src="/js/super_admin/offices.js"></script>
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
            <div class="col-xs-12 col-md-4 pull-right">
                <div class="box box-info">
                    <form method="POST" action="{{ route('office.store') }}" class="data" id="data">
                        @csrf
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Add New Office</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body with-border">
                            <div class="{{ $errors->any('office_name') ? 'has-error' : '' }}">
                                <label for="name">Office Name <span class="text-danger">*</span></label>
                                <input name="office_name" type="text" class="form-control form-group" id="office_name" 
                                placeholder="Enter Office Name" style="min-width: 100%;" required value="{{old('office_name')}}">
                            </div>
                            <div class="{{ $errors->any('office_code') ? 'has-error' : '' }}">
                                <label for="name">Office Code <span class="text-danger">*</span></label>
                                <input name="office_code" type="text" class="form-control form-group" id="office_code" 
                                placeholder="Enter Office Code" style="min-width: 100%;" required value="{{old('office_code')}}">
                            </div>
                            <div class="{{ $errors->any('telephone') ? 'has-error' : '' }}">
                                <label for="name">Contact Number <span class="text-danger">*</span></label>
                                <input name="telephone" type="number" class="form-control form-group" id="telephone" 
                                placeholder="Enter Contact Number" style="min-width: 100%;" required value="{{old('telephone')}}">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button form="data" type="submit" class="btn btn-primary pull-right save"><i class="loading fa fa-save"></i><span>  Save</span></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Office/s</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table-offices" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th style="width:1%;">#</th>
                                    <th style="width:30%;">Office Name</th>
                                    <th style="width:10%;">Office Code</th>
                                    <th style="width:10%;">Contact</th>
                                    <th style="width:3%;">Action</th>
                                </tr>
                            </thead>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
@endsection

