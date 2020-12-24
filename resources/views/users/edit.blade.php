@extends('layouts.master')

@section('page_script')
    <script src="/js/super_admin/users.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">Update</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <!-- general form elements disabled -->
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                            <h3 class="box-title"><strong>Update User</strong></h3>
                    </div>
                    <form action="/users/{{ $user->id }}/edit" method="POST"  class="form-horizontal data" id="data">
                        @method('PATCH')
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="employee_number" class="col-md-4  control-label">{{ __('Employee Number') }}</label>
                                <div class="col-md-6 @error('employee_number') has-error @enderror">
                                    <input id="employee_number" type="text" class="form-control" 
                                    name="employee_number" value="{{ $user->employee_number }}" required autocomplete="employee_number" autofocus
                                    placeholder="Enter Employee Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-md-4  control-label">{{ __('Name') }}</label>
                                <div class="col-md-6 @error('name') has-error @enderror">
                                    <input id="name" type="text" class="form-control" 
                                    name="name" value="{{ $user->name }}" required autocomplete="name" autofocus
                                    placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">{{ __('E-Mail Address') }}</label>
                                <div class="col-md-6  @error('email') has-error @enderror">
                                    <input id="email" type="email" class="form-control" 
                                    name="email" value="{{ $user->email  }}" required autocomplete="email"
                                    placeholder="Enter Email Address">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="office" class="col-md-4  control-label">{{ __('Office') }}</label>
                                <div class="col-md-6">
                                    <select id="office" name="office" class="form-control" style="width: 100%;">
                                        <option value="" selected="selected" Disabled>Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        document.getElementById("office").value = "{{ $user->office_id }}";
                                    </script>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div>
                                <button type="button" class="btn btn-default" onclick="window.location='/users'">Cancel</button>
                                <button form="data" type="submit" class="btn btn-primary save pull-right" style="margin-left:5px;"><i class="loading fa "></i><span>  Update</span></button>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                    <!-- /.box -->
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
<!-- /.content-wrapper -->
@endsection