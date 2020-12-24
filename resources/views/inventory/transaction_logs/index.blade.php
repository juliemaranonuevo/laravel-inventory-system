@extends('layouts.master')

    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini">

@section('page_script')
    @include('layouts.dataTables')
    <script>var hint = {{ $withSticker }}</script>
    @if ( $user -> user_type == 0)
        <script src="/js/normal_user/transaction_logs.js"></script>
    @else
        <script src="/js/super_admin/transaction_logs.js"></script>
    @endif
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">{{ $page['parent'] }}</li>
        @if($withSticker == 1)
            <li class="active">with stickers</li>
        @else
            <li class="active">without stickers</li>
        @endif
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        @if ( !$user->user_type == 0 )
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
            </div>
            <br>
        @endif
        <div class="row">
            <div class="col-md-3">
                <label for="filter">Filtered by:</label>
                <select id="filter" name="filter" class="form-control" style="width: 100%;">
                <option value="" selected="selected">All</option>
                <option value="1">Category</option>
                <option value="0">Status</option>
                </select>
            </div>
            <div class="col-md-3">
                <div id="resulthere">
                    <label>Result/s:</label>
                    <select id="result" class="form-control" style="width: 100%;" disabled>
                    <option value="" selected="selected">All</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 ">
                <button id="refresh" class="btn btn-primary save pull-right" style="margin-top:25px;" onclick="document.location.reload(true)"> Refresh</button>
                <button id="refresh" class="btn btn-default save pull-right" style="margin-top:25px; margin-right:4px;" 
                onclick="window.location='/transaction-logs/select-type'"> Change Type</button>
            </div>
        </div>
        <br>
        @if($withSticker == 1)
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                        <i class="ion ion-clipboard"></i>
                            <h3 class="box-title"><strong>Logs</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form id="try" action="/spreadsheet" method="POST">
                                @csrf
                                <table style="overflow-x: auto; font-size: 15px;" id="transaction" class="table table-bordered
                                table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            @if ( !$user->user_type == 0)
                                                <th>Office</th>
                                            @endif
                                            <th>Item Name</th>
                                            <th>Stock Keeping Unit</th>
                                            <th>Category</th>
                                            <th>Property Number</th>
                                            <th>Last Transaction Date</th>
                                            <th>Remarks</th>
                                            <th>Date Encoded</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="ion ion-clipboard"></i>
                            <h3 class="box-title"><strong>Logs</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table style="overflow-x: auto; font-size: 15px;" id="transaction" class="table table-bordered
                            table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @if ( !$user->user_type == 0)
                                            <th>Office</th>
                                        @endif
                                        <th>Item Name</th>
                                        <th>Stock Keeping Unit</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Last Transaction Date</th>
                                        <th>Remarks</th>
                                        <th>Date Encoded</th>
                                        <th>Status</th>
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