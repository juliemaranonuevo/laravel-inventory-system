@extends('layouts.master')

@section('page_script')
    <script src="/js/normal_user/overview.js"></script>
@endsection

@section('content')

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">Edit</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')<br>
        <div class="row">
            <div class="col-xs-12 col-md-3">
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Update Item [?]</strong></h3>
                    </div>
                    <form action="/inventories/{{ $item->id }}/edit"  method="POST" id="data">
                        @method('PATCH')
                        @csrf
                        <div class="box-body with-border">
                            <div class="{{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name"  style="font-size: 17px;">Item Name <span class="text-danger">*</span></label>
                            <input name="item" type="text" class="form-control form-group" id="name" style="min-width: 100%; font-size: 17px; height: 35px;"
                            value="{{ $item->item_name }}">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" onclick="window.location='/inventories'">Cancel</button>
                                <button form="data" type="submit" class="btn btn-primary pull-right save" style="margin-left:5px;">
                                <i class="loading fa"></i> Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
            </div>
        </div>
    </div>
<!-- /.content-wrapper -->
@endsection