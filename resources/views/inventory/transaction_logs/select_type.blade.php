@extends('layouts.master')

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
        @include('layouts.message')<br>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <!-- general form elements disabled -->
                <div class="box box-info">
                    <div class="box-header with-border" style="font-size: 17px;">
                        <h3 class="box-title"><strong>Type Selection</strong></h3>
                    </div>
                    <div class="box-body" >
                        <div class="col-md-6">
                            <a href="/transaction-logs" class="btn btn-app btn-block bg-blue" title="With sticker" style="margin: 0 auto; font-size: 17px;"
                            onclick="event.preventDefault(); document.getElementById('with-sticker').submit();">
                                With Sticker
                            </a>
                        </div>
                        <div  class="col-md-6">
                            <a href="/transaction-logs" class="btn btn-app btn-block bg-blue" title="With out Sticker" style="margin: 0 auto; font-size: 17px;"
                            onclick="event.preventDefault(); document.getElementById('with-out-sticker').submit();">
                                Without Sticker
                            </a>
                        </div>
                    </div>
                    <form id="with-sticker" action="/transaction-logs" method="GET" style="display: none;">
                        <input type="hidden" value="1" name="type">
                    </form>
                    <form id="with-out-sticker" action="/transaction-logs" method="GET" style="display: none;">
                        <input type="hidden" value="0" name="type">
                    </form>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
<!-- /.content-wrapper -->
@endsection