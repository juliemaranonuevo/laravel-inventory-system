@extends('layouts.master')

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">Category</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')<br>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Category Selection</strong></h3>
                    </div>
                    <form action="{{ route('inventory.item.create')}}"  method="POST">
                        @csrf
                        <div class="box-body with-border">
                            <label for="category_id" style="font-size: 17px;">Category Name<span class="text-danger"> *</span></label>
                            <select class="form-control type" name="category_id" parsley-trigger="change" required id="category_id"  style="font-size: 17px; height: 35px;">
                                    <option  value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div>
                                <button type="button" class="btn btn-default" onclick="window.location='/inventories'">Cancel</button>
                                <button type="submit" class="btn btn-primary pull-right" style="margin-left:5px;">Continue</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
@endsection