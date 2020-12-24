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
        <li class="active">{{ $category->name }}</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <strong>Update [?]</strong>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="POST" action="/categories/{{ $category->id }}/edit" id="data">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4 class="box-title"><strong>Category Name</strong></h4>
                                    <input type="text" class="form-control capitalized" name="name" required value="{{ $category -> name }}" style="min-width: 100%;">
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <h4 class="box-title"><strong>Category Status</strong></h4>
                                    <select id="status" name="status" class="form-control typeChange col-md-12">
                                        <option  value="" disabled selected>Select Status</option>
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option> 
                                    </select>
                                </div>
                                <script>
                                    document.getElementById("status").value = "{{ $category -> status }}";
                                </script>
                            </div>
                            <br>
                            <div style="width: 100%; max-height: 466px; overflow-y: auto;">
                                <table style="width: 100%;" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td style="width: 20%;">Check</td>
                                            <td>Available Fields</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $category->category_field as $field)
                                            <tr>
                                                <td class="text-center" style="width: 20%;">
                                                    <input type="checkbox" name="check_fields[]" value="{{ $field->id  }}" class="minimal" {{ $field->status ? 'checked' : '' }}>
                                                </td>
                                                <td class="capitalized">
                                                    {{ $field->field->name  }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach( $uncheck_items as $uncheck_item)
                                            <tr>
                                                <td class="text-center" style="width: 20%;">
                                                    <input type="checkbox" name="uncheck_fields[]" value="{{ $uncheck_item->id  }}" class="minimal">
                                                </td>
                                                <td class="capitalized">
                                                    {{ $uncheck_item->name  }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <button type="button" class="btn btn-default pull-left" onclick="window.location='/categories'">Cancel</button>
                            <button form="data" type="submit" class="btn btn-success pull-right save"><i class="loading fa fa-save"></i> Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /.content-wrapper -->
@endsection