@extends('layouts.master')

    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini">

@section('page_script')
    <script>var type = {{ $type }}</script>
    @if ( $user->user_type == 0 )
        <script src="/js/normal_user/dashboard.js"></script>
    @else
        <script src="/js/super_admin/dashboard.js"></script>
    @endif
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">{{  $page['title'] }}</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-xs-1">
                            <button class="btn btn-sm btn-primary" onclick="window.location='/'">
                                <i class="fa fa-caret-square-o-left"></i> Back
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    @if( $page['title'] == 'In' || $page['title'] == 'Out' || $page['title'] == 'Condemned')
                        <table style="overflow-x: auto; font-size: 15px; white-space: nowrap;" id="sticker" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ( !$user->user_type == 0 )
                                        <th>Office</th>
                                    @endif
                                    <th>Item Name</th>
                                    <th>Stock Keeping Unit</th>
                                    <th style="width: 10%;">Property Number</th>
                                    <th style="width: 8%;">Category</th>
                                    <th style="width: 27%;">Article</th>
                                    <th style="width: 8%;">Brand S/N</th>
                                    <th style="width: 10%;">Remarks</th>
                                    <th style="width: 1%;">Date Of Count</th>
                                    <th>Memo Receipt</th>
                                    <th>Date Encoded</th>
                                </tr>
                            </thead>
                        </table>
                    @else
                        <table style="overflow-x: auto; font-size: 15px;" id="sticker" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ( !$user->user_type == 0 )
                                        <th>Office</th>
                                    @endif
                                    <th>Item Name</th>
                                    <th>Stock Keeping Unit</th>
                                    <th>Category</th>
                                    <th>Unit</th>
                                    <th>Balance</th>
                                    <th>Date Encoded</th>
                                </tr>
                            </thead>
                        </table>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /.content-wrapper -->
@endsection