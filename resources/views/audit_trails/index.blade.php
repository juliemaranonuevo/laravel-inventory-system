@extends('layouts.master')

@section('page_script')
    <script src="/vendor/AdminLTE/bower_components/datatables/dataTables.buttons.min.js"></script>
    <script src="/vendor/AdminLTE/bower_components/datatables/buttons.flash.min.js"></script>
    <script src="/vendor/AdminLTE/bower_components/datatables/jszip.min.js"></script>
    <script src="/vendor/AdminLTE/bower_components/datatables/vfs_fonts.js"></script>
    <script src="/vendor/AdminLTE/bower_components/datatables/buttons.html5.min.js"></script>
    <script src="/vendor/AdminLTE/bower_components/datatables/buttons.print.min.js"></script>

    @if( $user -> user_type == 0)

        <script src="/js/normal_user/audit_trails.js"></script>

    @else

        <script src="/js/super_admin/audit_trails.js"></script>

    @endif
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
@include('layouts.message')
    <div class="container-fluid">
        <!-- normal user -->
        @if( $user -> user_type == 0)
            <div class="row">
                <!-- Date range -->
                <div class="col-md-6">
                    <label>Date range:</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="reservation">
                    </div>
                    <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="box box-primary">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table style="overflow-x: auto; font-size: 15px;" id="audits" class="table table-bordered
                            table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">#</th>
                                        <th style="width: 200px;">Date - Time</th>
                                        <th style="width: 200px;">User</th>
                                        <th style="width: 600px;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
        <!-- super user -->
            <div class="row">
                <div class="col-md-6">
                    <label for="filter">OFFICE/S</label>
                    <select id="filterByOffice" name="filter" class="form-control" style="width: 100%;">
                        <option value="" selected="selected">All</option>
                        @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Date range -->
                <div class="col-md-6">
                    <label>Date range:</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="reservation">
                    </div>
                    <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="box box-primary">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table style="overflow-x: auto; font-size: 15px;" id="audits" class="table table-bordered
                            table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">#</th>
                                        <th style="width: 100px;">Office</th>
                                        <th style="width: 200px;">Date - Time</th>
                                        <th style="width: 200px;">User</th>
                                        <th style="width: 600px;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- /.content-wrapper -->
@endsection