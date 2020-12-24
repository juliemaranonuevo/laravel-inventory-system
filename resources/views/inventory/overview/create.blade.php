@extends('layouts.master')

@section('page_script')
    <script src="/js/normal_user/overview.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li class="active">Create</li>
    </ol>
    <br>
@endsection

@section('page_css')
    <!-- Sticker form css -->
    <link rel="stylesheet" href="/css/stickerForm.css">
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <div class="row">
            <form action="{{ route('inventory.item.store') }}"  method="POST" id="data">
                @csrf

                @if( $category -> sticker )
                <div class="col-xs-12 col-md-6">
                @else
                <div class="col-xs-12 col-md-6 col-md-offset-3"> 
                @endif
                    <div class="box box-info">
                        <div class="box-header with-border" style="font-size: 17px;">
                            <h3 class="box-title"><strong>New Item Form</strong></h3>
                        </div>
                        <div class="box-body with-border">
                            <div class="form-group">
                                <label for="category" style="font-size: 17px;">Category Name</label>
                                <input id="category" name="category" type="text" class="form-control" placeholder="Enter Category Name" 
                                value ="{{ $category -> name }}" readonly>
                                <input id="category_id" name="category_id" class="form-control" type="hidden" value="{{ $category -> id }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="item" style="font-size: 17px;">Name<span class="text-danger"> *</span></label>
                                <select id="item" name="item" class="form-control select2 underline" style="width: 100%;" required>
                                    <option value="" selected="selected" Disabled>Enter Or Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->stock_keeping_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- text input -->
                            <div class="form-group {{ $errors->has('unit') ? 'has-error' : '' }}">
                                <label for="unit" style="font-size: 17px;">Unit<span class="text-danger"> *</span></label>
                                <input id="unit" name="unit" type="text" class="form-control" placeholder="Enter Unit Name" required value="{{ old('unit') }}">
                            </div>
                            @if( !$category -> sticker )
                                <div class="form-group {{ $errors->has('in') ? 'has-error' : '' }}">
                                    <label for="quantity" style="font-size: 17px;">Quantity<span class="text-danger"> *</span></label>
                                    <input id="quantity" name="in" type="number" class="form-control" placeholder="Enter Quantity" required min="1"
                                    onkeypress="return NumbersOnly(event)" value="{{old('in')}}">
                                </div>
                            @endif
                            
                            @php

                                $fields = $allFields;
                                $options = $allFields;
                                $field_count = count( $fields );
                                $count = 0;
                                
                            @endphp

                            @for ( $x=0; $x < $field_count; $x++ )
                                
                                @if ( $count == $field_count )

                                    @break

                                @endif

                                @if ( $fields[$count]['type'] == 'Date' )

                                    <!-- date -->
                                    <div class="form-group {{ $errors->has('date.'.$x) ? 'has-error' : '' }}">
                                        <label  for="date" style="font-size: 17px;">{{ $fields[$count]['name'] }}<span class="text-danger"> *</span></label>
                                        <input id="{{ $fields[$count]['name'] }}" name="date[]" type="date" class="form-control" placeholder="Enter {{ $fields[$count]['name'] }}" 
                                        required value="{{old('date.'.$x)}}">
                                        <input id="date_id" name="date_id[]" type="hidden" class="form-control" value="{{ $fields[$count]['id'] }}" required readonly>
                                    </div>

                                @endif

                                @if ( $fields[$count]['type'] == 'Number' )

                                    <!-- number input -->
                                    <div class="form-group {{ $errors->has('number.'.$x) ? 'has-error' : '' }}">
                                        <label for="number" style="font-size: 17px;">{{ $fields[$count]['name'] }}<span class="text-danger"> *</span></label>
                                        <input id="{{ $fields[$count]['name'] }}" name="number[]" type="number" class="form-control" placeholder="Enter {{ $fields[$count]['name'] }}" 
                                        required onkeypress="return NumbersOnly(event)" min="1" value="{{old('number.'.$x)}}">
                                        <input id="number_id" name="number_id[]" type="hidden" class="form-control" value="{{ $fields[$count]['id'] }}" required readonly min="0">
                                    </div>

                                @endif

                                @if ( $fields[$count]['type'] == 'Text' )

                                    <!-- text input -->
                                    <div class="form-group {{ $errors->has('text.'.$x) ? 'has-error' : '' }}">
                                        <label for="text" style="font-size: 17px;">{{ $fields[$count]['name'] }}<span class="text-danger"> *</span></label>
                                        <input id="{{ $fields[$count]['name'] }}" name="text[]" type="text" class="form-control" 
                                        placeholder="Enter {{ $fields[$count]['name'] }}" required value="{{old('text.'.$x)}}">
                                        <input id="text_id" name="text_id[]" type="hidden" class="form-control" value="{{ $fields[$count]['id'] }}" required readonly>
                                    </div>
                                    
                                @endif

                                @if ( $fields[$count]['type'] == 'Textarea' )

                                    <!-- textarea -->
                                    <div class="form-group {{ $errors->has('textarea.'.$x) ? 'has-error' : '' }}">
                                        <label for="textarea" style="font-size: 17px;">{{ $fields[$count]['name'] }}<span class="text-danger"> *</span></label>
                                        <textarea id="{{ $fields[$count]['name'] }}" name="textarea[]" class="form-control" rows="3" placeholder="Enter Item {{ $fields[$count]['name'] }}"
                                        style="max-width: 100%; min-width: 100%; max-height: 100px; min-height: 100px; resize: none;" required>{{old('textarea.'.$x)}}</textarea>
                                        <input id="textarea_id" name="textarea_id[]" type="hidden" class="form-control" value="{{ $fields[$count]['id'] }}" required readonly>
                                    </div>

                                @endif

                                @if ( $fields[$count]['type'] == 'Option' )
                                    
                                    @php

                                        $option_counter = 0;
                                        $option_count = count( $fields[$count]['option'] );
                                        
                                    @endphp

                                    <!-- option -->
                                    <div class="form-group {{ $errors->has('option.'.$x) ? 'has-error' : '' }}">
                                        <label for="option" style="font-size: 17px;">{{ $fields[$count]['name'] }}<span class="text-danger"> *</span></label>
                                        <select class="form-control type" name="option[]" parsley-trigger="change" required 
                                        id="{{ $fields[$count]['name'] }}"  style="height: 35px;">
                                            <option  value="" disabled selected>Select {{ $fields[$count]['name'] }}</option>
                                            @for ( $y=0; $y<$option_count; $y++ )
                                            
                                                @if ( $option_counter == $option_count )

                                                    @break

                                                @else

                                                    <option value="{{ $fields[$count]['option'][ $option_counter ]['option'] }}"
                                                    {{ in_array( $fields[$count]['option'][ $option_counter ]['option'], old("option") ?: []) ? "selected": "" }} >
                                                        {{ $fields[$count]['option'][ $option_counter ]['option'] }}
                                                    </option> 

                                                    @php $option_counter++; @endphp

                                                @endif
                                            
                                            @endfor
                                        </select>
                                        <input id="text" name="option_id[]" type="hidden" class="form-control" value="{{ $fields[$count]['id'] }}" required readonly>
                                    </div>
                                
                                @endif

                                @php $count++; @endphp
                                
                            @endfor
                            <div id="Existing"></div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-left">
                                <button form="data" type="submit" class="btn btn-primary save" style="margin-left:5px;"><i class="loading fa fa-save"></i><span>  Save</span></button>
                                <button type="button" class="btn btn-default" onclick="window.location='/inventories'">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                @if( $category -> sticker )
                <div class="col-xs-12 col-md-6">
                    <div class="box">
                        <!-- Sticker Form -->
                        <div class="form-group" style="border: 1px solid #D3D3D3">
                            <div style="border: 10px solid white">
                                <div style="border: 5px solid blue">
                                    <div class="container-fluid">
                                        <div class="text-center">
                                            <br>
                                            <strong style="font-size: 17px;">Name of the Company</strong><br>
                                            <strong style="font-size: 17px;">EQUIPMENT-INVENTORY</strong>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td style="width: 130px;">
                                                        <label for="office" style="font-size: 17px;">Office:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('office') ? 'has-error' : '' }}">
                                                        <input id="office" name="office" type="text" class="form-control underline" placeholder="Enter Office" required 
                                                        value="{{ $office }}" readonly><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="property_number" style="font-size: 17px;">Property No.:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('property_number') ? 'has-error' : '' }}">
                                                        <input id="property_number" name="property_number" type="text" class="form-control underline" 
                                                        placeholder="Enter Property Number" required value="{{ old('property_number') }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="article" style="font-size: 17px;">Article:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('article') ? 'has-error' : '' }}">
                                                        <textarea id="article" name="article" class="form-control" rows="3" placeholder="Enter Article"
                                                        style="max-width: 100%; min-width: 100%; max-height: 80px; min-height: 80px; resize: none;" required>{{ old('article') }}</textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="brand_sn" style="font-size: 17px;">Brand/S.N.:</label>
                                                    </td>
                                                    <td class="{{ $errors->has('brand_sn') ? 'has-error' : '' }}">
                                                        <input id="brand_sn" name="brand_sn" type="text" class="form-control underline" placeholder="Enter Brand/S.N." value="{{ old('brand_sn') }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="remarks" style="font-size: 17px;">Remarks:</label>
                                                    </td>
                                                    <td class="{{ $errors->has('remarks') ? 'has-error' : '' }}">
                                                        <input id="remarks" name="remarks" type="text" class="form-control underline" placeholder="Enter Remarks" value="{{ old('remarks') }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="date_count" style="font-size: 17px;">Date of Count:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('date_count') ? 'has-error' : '' }}">
                                                        <input id="date_count" name="date_count" type="number" class="form-control underline" pattern="[0-9]" placeholder="Enter Count"
                                                        required min="2000" value="{{ old('date_count') }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="memo_receipt_employee" style="font-size: 17px;">M.R.:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('memo_receipt_employee') ? 'has-error' : '' }}">
                                                        <input id="memo_receipt_employee" name="memo_receipt_employee" type="text" class="form-control underline" placeholder="Enter M. R." value="{{ old('memo_receipt_employee') }}">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
    <br>
    <br>
<!-- /.content-wrapper -->
@endsection