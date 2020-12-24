@extends('layouts.master')

@section('page_script')
    <script src="/js/normal_user/transaction.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">Add</li>
    </ol>
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <div class="col-xs-1">
                <a href="/inventories" class="btn btn-app btn-block bg-green" title="Back">
                <i class="fa fa-md fa-mail-reply"></i>
                    Back
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4 pull-right">
                <div class="small-box @if($itemQuantity->balance == 0) bg-red @else bg-green @endif">
                    <div class="inner">
                        <h3>{{ $itemQuantity->balance }}</h3>
                        <p>Remaining Balance</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>Add New Transaction</strong></h3>
                        <span> - {{ $itemQuantity->item->stock_keeping_unit }}</span>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if($itemQuantity->item->category->sticker == 1)
                            <form method="POST" action="/inventories/{{ $itemQuantity->id}}" class="data" id="data">
                                @csrf
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body with-border">
                                    <div id="output" class="form-group output {{ $errors->has('property_number') ? 'has-error' : '' }}">
                                        <label for="property_number" style="font-size: 17px;">Property Number<span class="text-danger"> *</span></label>
                                        <select name="property_number[]" class="form-control select2" multiple="multiple" data-placeholder="Select an item"
                                        style="width: 100%;" required>
                                        @foreach ($itemQuantity->item->sticker as $sticker)
                                            @if ( $sticker->office_id == Auth::user()->office_id)
                                                <option value="{{ $sticker->property_number }}">{{ $sticker->property_number }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class=" form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label for="type">Action <span class="text-danger">*</span></label>
                                        <select class="form-control type" name="type" parsley-trigger="change" required id="type">
                                            <option  value="" disabled selected>Select an action</option>
                                            <option value="IN">IN</option>
                                            <option value="OUT">OUT</option>
                                            <option value="CONDEMNED">CONDEMN</option>
                                        </select>
                                    </div>

                                    <div class="form-group {{ $errors->has('transaction_date') ? 'has-error' : '' }}">
                                        <label for="transaction_date">Date <span class="text-danger">*</span></label>
                                        <input name="transaction_date" type="date" class="form-control form-group" id="transaction_date" 
                                        style="min-width: 100%;" required value="{{old('transaction_date')}}">
                                    </div>

                                    <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                        <label for="remarks">Remarks</label>
                                        <input name="remarks" type="text" class="form-control form-group" id="name" 
                                        placeholder="Enter Field Name" style="min-width: 100%;" required value="{{old('remarks')}}">
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button form="data" type="submit" class="btn btn-primary pull-right save"><i class="loading fa fa-save"></i><span>  Save</span></button>
                                </div>
                            </form>
                        @else
                            <form method="POST" action="/inventories/{{ $itemQuantity->id}}" class="data" id="data">
                                @csrf
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body with-border">
                                    <div class="{{ $errors->has('quantity') ? 'has-error' : '' }}">
                                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                        <input name="quantity" type="number" class="form-control form-group" id="quantity" 
                                        placeholder="Enter Quantity" style="min-width: 100%;" required value="{{old('quantity')}}">
                                    </div>
                                    <div class=" form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label for="type">Action <span class="text-danger">*</span></label>
                                        <select class="form-control type" name="type" parsley-trigger="change" required id="type">
                                            <option  value="" disabled selected>Select Type</option>
                                            <option value="IN">IN</option>
                                            <option value="OUT">OUT</option>
                                        </select>
                                    </div>
                                    <div class="form-group {{ $errors->has('transaction_date') ? 'has-error' : '' }}">
                                        <label for="transaction_date">Date <span class="text-danger">*</span></label>
                                        <input name="transaction_date" type="date" class="form-control form-group" id="transaction_date" 
                                        style="min-width: 100%;" required value="{{old('transaction_date')}}">
                                    </div>
                                    <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                        <label for="remarks">Remarks</label>
                                        <input name="remarks" type="text" class="form-control form-group" id="name" 
                                        placeholder="Enter Field Name" style="min-width: 100%;" required value="{{old('remarks')}}">
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button form="data" type="submit" class="btn btn-primary pull-right save"><i class="loading fa fa-save"></i><span>  Save</span></button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-xs-12 col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>List of Transactions</strong></h3>
                        <span> - {{ $itemQuantity->item->stock_keeping_unit }} </span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if($itemQuantity->item->category->sticker == 1)
                            <table style="overflow-x: auto; font-size: 15px;" id="table" class="table table-bordered
                                table-hover">
                                    <thead>
                                        <tr>
                                            <th>Property Number</th>
                                            <th>Last Transaction Date</th>
                                            <th>Remarks</th>
                                            <th>Date Encoded</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach ($Item_transactions as $item)
                                    <tr>
                                        <td class="capitalized">
                                            {{ $item->property_number }}
                                        </td>
                                        <td class="capitalized">
                                            {{ date('m-d-Y', strtotime( $item->transaction_date )) }}
                                        </td>
                                        <td class="capitalized">
                                            {{ $item->remarks }}
                                        </td>
                                        <td class="capitalized">
                                            {{ $item->created_at->format('m-d-Y - H:i:s') }}
                                        </td>
                                        <td class="capitalized">
                                            <mark style="@if( $item->type == 'IN' ) background-color: green; color: white; @elseif( $item->type == 'OUT' ) 
                                            background-color: orange; color: white; @else background-color: red; color: white; @endif">
                                                {{ $item->type }}
                                            </mark>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <table style="overflow-x: auto; font-size: 15px;" id="table" class="table table-bordered
                                table-hover">
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Last Transaction Date</th>
                                        <th>Remarks</th>
                                        <th>Date Encoded</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Item_transactions as $item)
                                    <tr>
                                        <td class="capitalized">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="capitalized">
                                            {{ date('m-d-Y', strtotime( $item->transaction_date )) }}
                                        </td>
                                        <td class="capitalized">
                                            {{ $item->remarks }}
                                        </td>
                                        <td class="capitalized">
                                            {{ $item->created_at->format('m-d-Y - H:i:s') }}
                                        </td>
                                        <td class="capitalized">
                                            <mark style="@if($item -> type == 'IN') background-color: green; color: white;
                                            @else background-color: orange; color: white; @endif">
                                                {{ $item->type }}
                                            </mark>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /.content-wrapper -->
@endsection