@extends('layouts.master')

@section('page_script')
    <script src="/js/category.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">{{ $category -> name }}</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-1">
                <a href="/categories" class="btn btn-app btn-block bg-green" title="Back">
                    <i class="fa fa-md fa-mail-reply"></i>
                    Back
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4 pull-right">
                <div class="box box-info">
                    <div class="box-header">
                        <label for="name">Category Name</label><br>
                        <input type="text" class="form-control capitalized" name="name" id="name" value="{{ $category -> name }}" style="min-width: 100%;" readonly>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <label for="status">Status</label> <br>
                        @if ($category -> status == 1)
                            <input type="text" class="form-control" name="status" id="status" value="Enabled" style="min-width: 100%;" readonly>
                        @else
                            <input type="text" class="form-control" name="status" id="status" value="Disabled" style="min-width: 100%;" readonly>
                        @endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="/categories/{{ $category -> id }}/edit">
                            <button type="submit" class="btn btn-warning pull-right"><i class="fa fa-edit"></i> 
                                Edit
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <strong>Field List</strong>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tbl_category" class="table table-bordered
                        table-hover">
                            <thead>
                                <tr>
                                    <th>Field Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($category->category_field as $field)
                                @if ($field->status == 1)
                                    <tr>
                                        <td class="capitalized">
                                            {{ $field->field->name}}
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info"  onclick="window.location='/fields/{{ $field -> field -> id  }}'">
                                                <i class="fa fa-eye"> View </i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
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