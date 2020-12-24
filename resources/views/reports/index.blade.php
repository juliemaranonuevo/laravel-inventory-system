@extends('layouts.master')

@section('page_script')
    <script src="/js/reports.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Reports</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Inventory</strong></h3>
                    </div>
                    <form id="TheInventoryForm" action="/reports/inventory"  method="POST" target="TheInventoryWindow">
                        @csrf
                        <div class="box-body with-border">
                            <div class="col-md-12">
                                @if ( !$user->user_type == 0)
                                    <div class="form-group">
                                        <label for="office" style="font-size: 17px;">Office<span class="text-danger"> *</span></label>
                                        <select id="office" name="office" class="form-control select2 underline" style="width: 100%;">
                                            <option value="" selected="selected">All</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="category" style="font-size: 17px;">Category<span class="text-danger"> *</span></label>
                                    <select id="category" name="category" class="form-control select2 underline" style="width: 100%;">
                                        <option value="" selected="selected">All</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                                        <button id="inventoryReports" type="submit" class="btn btn-app btn-block">
                                            <i class="fa fa-file-text-o"></i> Generate Reports
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Audit Trails</strong></h3>
                    </div>
                    <form id="auditTrailForm" action="/reports/audit-trail"  method="POST" target="TheAuditTrailWindow">
                        @csrf
                        <div class="box-body with-border">
                            <!-- Date range -->
                            <div class="col-md-12">
                                @if ( !$user->user_type == 0)
                                    <div class="form-group">
                                        <label for="office" style="font-size: 17px;">Office<span class="text-danger"> *</span></label>
                                        <select id="office" name="office" class="form-control select2 underline" style="width: 100%;">
                                            <option value="" selected="selected">All</option>
                                            @foreach ($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Date range:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="dateRange" type="text" class="form-control pull-right" id="reservation">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                                        <button id="auditTrailReports" type="submit" class="btn btn-app btn-block">
                                            <i class="fa fa-file-text-o"></i> Generate Reports
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <!-- /.content-wrapper -->
@endsection