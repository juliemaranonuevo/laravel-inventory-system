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
    <div class="container-fluid">
        @include('layouts.message')<br>
        <div class="row">
            <div class="col-xs-12 col-md-3">
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Update Field [?]</strong></h3>
                    </div>
                    <form action="/fields/{{ $field -> id}}/edit"  method="POST" id="data">
                        @method('PATCH')
                        @csrf
                        <div class="box-body with-border">
                            <div class="{{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name"  style="font-size: 17px;">Field Name <span class="text-danger">*</span></label>
                                <input name="name" type="text" class="form-control form-group" id="name" style="min-width: 100%; font-size: 17px; height: 35px;"
                                value="{{ $field -> name }}">
                            </div>
                            <div class="{{ $errors->has('status') ? 'has-error' : '' }}">
                                <label for="status" style="font-size: 17px;">Status<span class="text-danger"> *</span></label>
                                <select class="form-control type" name="status" parsley-trigger="change" required id="status"  style="font-size: 17px; height: 35px;">
                                        <option  value="" disabled selected>Select Status</option>
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>   
                                </select>
                            </div>
                            <br>
                            <label class="collapse" for="type"  style="font-size: 17px;">Field Type <span class="text-danger">*</span></label>
                            <select class="form-control type collapse" name="type" parsley-trigger="change" required id="type"  style="font-size: 17px; height: 35px;">
                                    <option  value="" disabled selected>Select Type</option>
                                    <option value="Text">Text</option>
                                    <option value="Date">Date</option>   
                                    <option value="Number">Number</option>
                                    <option value="Textarea">Textarea</option>   
                                    <option value="Option">Option</option>                                     
                            </select>
                            <script>
                                document.getElementById("status").value = "{{ $field -> status }}";
                                document.getElementById("type").value = '{{ $field -> type }}';
                            </script>
                            <div class="option_div collapse">
                                <br>
                                <span class="h5">Option/s: </span>
                                <div style="width: 100%; max-height: 145px; overflow-y: auto;" class="container1">
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
                                    <span class="h6"><span class="text-danger"> Note:
                                    </span> Special characters are not valid in option field.</span>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div>
                                    @if( $field->type == 'Option' )
                                        <button type="button" class="btn btn-default pull-left" onclick="window.location='/fields/{{ $field -> id }}'">Cancel</button>
                                    @else
                                        <button type="button" class="btn btn-default pull-left" onclick="history.back()">Cancel</button>
                                    @endif
                                    <button form="data" type="submit" class="btn btn-primary pull-right save" style="margin-left:5px;">
                                    <i class="loading fa"></i> Save changes</button>
                                </div>
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