@extends('layouts.master')

@section('page_script')
    @if ( $user->user_type == 0)
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
        <li class="active">{{ $page['parent'] }}</li>
    </ol>
    <br>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $nosticker }}</h3>

                        <p>No sticker (Balance)</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="/info/no_sticker" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $withsticker_in->count() }}</h3>

                        <p>In Item/s</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <a href="/info/in" class="small-box-footer sticker" id="IN">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $withsticker_out->count() }}</h3>

                        <p>Out Item/s</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <a href="/info/out" class="small-box-footer sticker" id="OUT">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $withsticker_condemned->count() }}</h3>

                        <p>Condemned Item/s</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-ban"></i>
                    </div>
                    <a href="/info/condemned" class="small-box-footer sticker" id="CONDEMNED">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <div class="box box-primary">
            <div class="box-header">
                <i class="ion ion-clipboard"></i>
                <h3 class="box-title">Logs</h3>
                <button type="button" class="btn btn-primary pull-right" onclick="window.location='/transaction-logs/select-type'"><i class="fa fa-search"></i> Advance Search</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="transaction" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if ( !$user->user_type == 0 )
                                <th>Office</th>
                            @endif
                            <th>Item Name</th>
                            <th>Stock Keeping Unit</th>
                            <th>Category</th>
                            <th>Property Number</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Date Recorded</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-body -->
          </div>
    </section>
    <!-- /.content -->
<!-- /.content-wrapper -->
@endsection
