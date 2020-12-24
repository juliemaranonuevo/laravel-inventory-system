@extends('layouts.master')

@section('page_script')
    <script src="/js/field.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">{{ $field -> name }}</li>
    </ol>
    <br>
@endsection

@section('content')
@if( $field->type == 'Option')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-1">
                <a href="/fields" class="btn btn-app btn-block bg-green" title="Back">
                    <i class="fa fa-md fa-mail-reply"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
@endif
    <div class="container-fluid">
        @include('layouts.message')
        @if($field->type == 'Option')
        <div class="row">
            <div class="col-xs-12 col-md-4 pull-right">
                <div class="box box-info">
                    <div class="box-body with-border">
                        <label for="name">Field Name</label>
                        <input name="name" type="text" class="form-control form-group" id="name" style="min-width: 100%;"
                        value="{{ $field -> name }}" readonly>
                        <label class="collapse" for="type">Type</label>
                        <input name="type" type="type" class="form-control form-group collapse" id="type" style="min-width: 100%;" 
                        value="{{ $field -> type }}" readonly>
                        <label for="status">Status</label>
                        <input name="status" type="text" class="form-control form-group" id="status" style="min-width: 100%;" 
                        value="@if($field -> status) Enabled @else  Disabled @endif" readonly>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a class="pull-right" href="/fields/{{ $field -> id}}/edit">Edit</a>
                    </div>
                </div>
                <div class="box box-info">
                    <form method="POST" action="/fields/{{ $field -> id}}" class="data" id="data">
                        @csrf
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Add New Option [?]</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body with-border">
                            <div class="option_div">
                                <span class="h5">Option/s: </span>
                                <div style="width: 100%; max-height: 210px; overflow-y: auto;" class="container1">
                                    <div style="margin-top: 5px;">
                                        <table style="width: 100%">
                                            <tr>
                                                <td style="width: 90%;">
                                                    <input type="text" class="form-control" name="option[]" placeholder="Optional">
                                                </td>
                                                <td style="width: 10%;" class="text-center">
                                                    <a href="#" class="delete">
                                                        <i class="fa fa-plus text-success add_form_field"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <span class="h6"><span class="text-danger"> Note:</span> Special characters are not valid in option field.</span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button form="data" type="submit" class="btn btn-primary pull-right save"><i class="loading fa fa-save"></i><span>  Save</span></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Field Options</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>Option Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($field -> option as $option)
                                <tr>
                                    <td class="capitalized">
                                        {{ $option->option }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" onclick="window.location='/fields/{{ $field -> id}}/options/{{ $option -> id}}/edit'">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @else
        <br>
        <div class="col-xs-12 col-md-3">
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="box box-info">
                <div class="box-header with-border" style="font-size: 17px;">
                    <h3 class="box-title"><strong>Update Field [?]</strong></h3>
                </div>
                <div class="box-body with-border">
                    <label for="name" style="font-size: 17px;">Field Name</label>
                    <input name="name" type="text" class="form-control form-group" id="name" style="min-width: 100%; font-size: 20px; height: 35px;"
                    value="{{ $field -> name }}" readonly>
                    <label class="collapse" for="type" style="font-size: 17px;">Type</label>
                    <input name="type" type="type" class="form-control form-group collapse" id="type" style="min-width: 100%; font-size: 20px; height: 35px;" 
                    value="{{ $field -> type }}" readonly>
                    <label for="status" style="font-size: 17px;">Status</label>
                    <input name="status" type="text" class="form-control form-group" id="status" style="min-width: 100%; font-size: 20px; height: 35px;" 
                    value="@if($field -> status) Enabled @else  Disabled @endif" readonly>
                </div>
                    <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick="history.back()">Cancel</button>
                    <button class="btn btn-sm btn-warning pull-right" onclick="window.location='/fields/{{ $field -> id}}/edit'">
                        <i class="fa fa-edit"></i> Edit
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
        </div>
        @endif
    </div>
    <div class="modal fade" id="modal-default">
        <div class="modal-diaslog" style="width:28%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Default Modal</h4>
                </div>
                <div class="modal-body">
                    <span id="form_result_edit"></span>
                    <form action="" method="POST" data-parsley-validate novalidate enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="form-group">
                            <label  for="name">Field Name*</label>
                            <input type="text" name="name" required placeholder="Field Name" class="form-control" value="">
                        </div>                                       
                        <div class="form-group">
                            <label for="status_edit">Status*</label>                                           
                            <select class="form-control" name="status" parsley-trigger="change" required id="status_edit">
                                <option  value="" disabled selected>Select the status</option>
                                <option value="Enabled">Enable</option>
                                <option value="Disabled">Disable</option>                                           
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<!-- /.content-wrapper -->
@endsection