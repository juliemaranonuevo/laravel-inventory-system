@extends('layouts.master')

    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini">

@section('page_script')
    @if ( $user -> user_type == 0 )
        <script src="/js/normal_user/overview.js"></script>
    @else
        <script src="/js/super_admin/overview.js"></script>
    @endif
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">{{ $page['parent'] }}</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        @if ( $user -> user_type == 0 )
            <div class="row">
                <div class="col-xs-2">
                    <a href="{{ route('inventory.select.category')}}" class="btn btn-app btn-block bg-green" title="Add new item">
                        <i class="fa fa-md fa-plus"></i>
                        Add New Item
                    </a>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6">
                    <label for="filter">OFFICE/S</label>
                    <select id="filterByOffice" name="filter" class="form-control filterByOffice" style="width: 100%;">
                        <option value="" selected="selected">All</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <button id="refresh" class="btn btn-primary save pull-right refresh" style="margin-top:25px;"> Refresh</button>
                </div>
            </div>
            <br>
        @endif
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Item Name/s</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ( !$user->user_type == 0 )
                                        <th>Office</th>
                                    @endif
                                    <th>Item Name</th>
                                    <th class="">Category</th>
                                    <th class="text-center">In</th>
                                    <th class="text-center">Out</th>
                                    <th class="text-center">Condemned</th>
                                    <th class="text-center">Balance</th>
                                    <th style="width:330px;">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table-modal" class="table table-bordered
                            table-hover">
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button id="close" type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
<!-- /.content-wrapper -->
@endsection