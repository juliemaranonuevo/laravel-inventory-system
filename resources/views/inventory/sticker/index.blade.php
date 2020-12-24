@extends('layouts.master')

    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini">

@section('page_script')
    @if ( $user->user_type == 0)
        <script src="/js/normal_user/sticker.js"></script>
    @else
        <script src="/js/super_admin/sticker.js"></script>
    @endif
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    @if ( $user->user_type == 0)
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li>{{ $page['parent'] }}</li>
            <li>{{ $sticker -> item -> item_name }}</li>
            <li class="active">Stickers</li>
        </ol>
        <br>
    @else
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li>{{ $page['parent'] }}</li>
            <li>{{ $sticker -> office }}</li>
            <li>{{ $sticker -> item -> item_name }}</li>
            <li class="active">Stickers</li>
        </ol>
        <br>
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-1">
                <a href="/inventories" class="btn btn-app btn-block bg-green" title="Back">
                    <i class="fa fa-md fa-mail-reply"></i>
                    Back
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Sticker/s</strong></h3> - {{ $sticker -> item -> item_name }}
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table-sticker" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 12%;">Property Number</th>
                                    <th style="width: 27%;">Article</th>
                                    <th style="width: 10%;">Brand S/N</th>
                                    <th style="width: 10%;">Remarks</th>
                                    <th style="width: 10%;">Date Of Count</th>
                                    <th style="width: 10%;">Memo Receipt</th>
                                    <th style="width: 9%;">Type</th>
                                    <th style="width: 15%;">Date Encoded</th>
                                    @if ( !$user->user_type == 1)
                                        <th>Action</th>
                                    @endif
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