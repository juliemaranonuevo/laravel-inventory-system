@extends('layouts.master')

@section('page_script')
    <script src="/js/category.js"></script>
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
                    <form method="POST" action="{{ route('reference_library.category.store') }}" id="data">
                        @csrf
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Add New Category</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body with-border">
                            <div class="form-group">
                                <div class="{{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="cat">Category Name</label>
                                    <input id="cat" type="text" class="form-control capitalized" name="name" 
                                    placeholder="Category Name" style="min-width: 100%;" required 
                                    value="{{ session()->has('error_message') ? Session::get('name') : old('name')  }}">
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('sticker') ? 'has-error' : ''  }}">
                                <label for="sticker">With Sticker?</label>
                                <select id="sticker" name="sticker" class="form-control" style="width: 100%;">
                                    <option value="" selected="selected" Disabled>Choose here</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <script>
                                    document.getElementById("sticker").value = "{{ session()->has('error_message') ? Session::get('sticker') : old('sticker') }}";
                                </script>
                            </div>
                            {{-- loop nung exisiting fields --}}
                            @if($fields->count() != 0)
                                <h5>Fields: </h5>
                                <table style="width: 100%;" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td style="width: 20%;">Check</td>
                                            <td>Available Fields</td>
                                        </tr>
                                    </thead>
                                </table>
                                <div style="width: 100%; max-height: 466px; overflow-y: auto;" class="container1">
                                    <table style="width: 100%;" class="table table-bordered table-hover">
                                        <tbody class="">
                                            @foreach ($fields as $fields)
                                                <tr>
                                                    <td class="text-center" style="width: 20%;"><input type="checkbox" name="field[]" value="{{ $fields -> id }}" 
                                                    class="minimal"></td>
                                                    <td>{{ $fields->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button form="data" type="submit" class="btn btn-primary pull-right save"><i class="loading fa fa-save"></i> Save </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- col-md-4 end --}}
            <div class="col-xs-12 col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Categories</strong></h3>
                        <p>Click the category name to view fields...</p>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tbl_category" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    {{-- <th>Fields Quantity</th> --}}
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            {{-- loop ng categories --}}
                            @foreach ($categories as $categories)
                                <tr>
                                    <td class="capitalized">
                                        {{ $categories->name }}
                                    </td>
                                    {{-- <td class="capitalized">
                                        {{ $categories->fields->count() }}
                                    </td> --}}
                                    @if ($categories->status == 1)
                                        <td><span class="label label-success">Enabled</span></td>
                                    @else
                                        <td><span class="label label-danger">Disabled</span></td>
                                    @endif
                                    <td class="text-center">
                                        <a href="/categories/{{ $categories -> id }}">
                                            <button class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"> View</i>
                                            </button>
                                        </a>
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