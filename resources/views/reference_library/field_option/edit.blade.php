@extends('layouts.master')

@section('page_script')
    <script src="/js/FieldOption.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li>Options</li>
        <li class="active">Update</li>
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
                        <h3 class="box-title"><strong>Update Option [?]</strong></h3>
                    </div>
                    <form action="/fields/{{ $field->id}}/options/{{ $fieldOption->id }}/edit"  method="POST" id="data">
                        @method('PATCH')
                        @csrf
                        <div class="box-body with-border">
                            <div class="{{ $errors->has('option') ? 'has-error' : '' }}">
                                <label for="option"  style="font-size: 17px;">Option Name <span class="text-danger">*</span></label>
                                <input name="option" type="text" class="form-control form-group" id="option" style="min-width: 100%; font-size: 17px; height: 35px;"
                                value="{{ $fieldOption->option }}">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div>
                                <button type="button" class="btn btn-default pull-left" onclick="window.location='/fields/{{ $field -> id}}'">Cancel</button>
                                <button form="data" type="submit" class="btn btn-primary pull-right save" style="margin-left:5px;"><i class="loading fa"></i> Save changes</button>
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