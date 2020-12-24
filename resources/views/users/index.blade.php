@extends('layouts.master')

@section('page_script')
    <script src="/js/super_admin/users.js"></script>
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
            <div class="col-md-6">
                <button id="refresh" class="btn bg-green save pull-right refresh" style="margin-top:25px; margin-left:5px;" 
                onclick="window.location='/users/create'"> <i class="fa fa-md fa-plus"></i>  Add New User</button>
                <button id="refresh" class="btn btn-primary save pull-right refresh" style="margin-top:25px;"> Refresh</button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Office/s</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table-users" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:12%;">Employee Number</th>
                                    <th style="width:15%;">Name</th>
                                    <th style="width:20%;">Email</th>
                                    <th style="width:10%;">Office</th>
                                    <th style="width:12%;">Created_at</th>
                                    <th style="width:12%;">Updated_at</th>
                                    <th style="width:5%;">Action</th>
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

