@extends('layouts.master')

@section('page_script')
    <script src="/js/field.js"></script>
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
            <div class="col-xs-12 col-md-4 pull-right">
                <div class="box box-info">
                    <form method="POST" action="{{ route('reference_library.field.store') }}" class="data" id="data">
                        @csrf
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Add New Field</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body with-border">
                            <div class="{{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Field Name <span class="text-danger">*</span></label>
                                <input name="name" type="text" class="form-control form-group" id="name" 
                                placeholder="Enter Field Name" style="min-width: 100%;" required value="{{old('name')}}">
                            </div>
                        <div class="{{ $errors->has('type') ? 'has-error' : '' }}">
                        <label for="type">Field Type <span class="text-danger">*</span></label>
                        <select class="form-control type" name="type" parsley-trigger="change" required id="type">
                            <option  value="" disabled selected>Select Type</option>
                            <option value="Text">Text</option>
                            <option value="Date">Date</option>   
                            <option value="Number">Number</option>
                            <option value="Textarea">Textarea</option>   
                            <option value="Option">Option</option>                                     
                        </select>
                        <script>
                            document.getElementById("type").value = "{{ $errors->any() ? old('type') : '' }}";
                        </script>
                        </div>
                        <div class="option_div {{ $errors->has('option.*') ? '' : 'collapse' }} "><br>
                            <span class="h5">Option/s: </span>
                            <div style="width: 100%; max-height: 210px; overflow-y: auto;" class="container1">
                                <div style="margin-top: 5px;">
                                    <table style="width: 100%">
                                        <tr>
                                            <td style="width: 90%;">
                                                <input type="text" class="form-control is-invalid {{ $errors->has('option.*') ? 'has-warning' : '' }}" 
                                                name="option[]" placeholder="Optional" value="{{old('option.*')}}">
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
                        <h3 class="box-title"><strong>List of Field Names</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table style="overflow-x: auto; font-size: 15px;" id="table" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>Field Name</th>
                                    <th>type</th>
                                    <th class="">Option</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($fields as $field)
                                <tr>
                                    <td class="capitalized">
                                        {{ $field->name }}
                                    </td>
                                    <td class="capitalized">
                                        {{ $field->type }}
                                    </td>
                                    <td class="capitalized">
                                        @if($field->option->count() == 0)
                                            Null
                                        @else
                                            {{ $field->option->count() }}
                                        @endif
                                    </td>
                                    <td class="capitalized">
                                        @if($field->status)
                                            <span class="label label-success">
                                                Enabled 
                                            </span>
                                        @else
                                            <span class="label label-danger ">
                                                Disabled 
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info" onclick="window.location='/fields/{{ $field->id}}'" title="View">
                                            <i class="fa fa-eye"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-danger collapse">
                                            <i class="fa fa-remove"></i>
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
    </div>
<!-- /.content-wrapper -->
@endsection

