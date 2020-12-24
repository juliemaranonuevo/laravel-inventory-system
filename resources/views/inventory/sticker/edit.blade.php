@extends('layouts.master')

@section('page_script')
    <script src="/js/normal_user/sticker.js"></script>
@endsection

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>{{ $page['parent'] }}</li>
        <li>{{ $sticker->item->item_name }}</li>
        <li class="active">{{ $sticker->property_number }}</li>
    </ol>
    <br>
@endsection

@section('page_css')
    <!-- Sticker form -->
    <link rel="stylesheet" href="/css/stickerForm.css">
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.message')<br>
        <div class="row">
            <form action="/sticker/{{ $sticker->id }}/edit"  method="POST" id="data">
                @method('PATCH')
                @csrf
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="box">
                        <!-- Sticker Form -->
                        <div class="form-group" style="border: 1px solid #D3D3D3">
                            <div style="border: 10px solid white">
                                <div style="border: 5px solid blue">
                                    <div class="container-fluid">
                                        <div class="text-center">
                                        <br>
                                            <strong style="font-size: 17px;">Provincial Government 0f Laguna</strong>
                                            <br>
                                            <strong style="font-size: 17px;">EQUIPMENT-INVENTORY</strong>
                                        </div><br>
                                        <div class="form-group">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td style="width: 130px;">
                                                        <label for="office" style="font-size: 17px;">Office:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('office') ? 'has-error' : '' }}">
                                                        <input id="office" name="office" type="text" class="form-control underline capitalized" placeholder="Enter Office" required value="{{ $sticker->office }}" readonly><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="property_number" style="font-size: 17px;">Property No.:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('property_number') ? 'has-error' : '' }}">
                                                        <input id="property_number" name="property_number" type="text" class="form-control underline capitalized" 
                                                        placeholder="Enter Property Number" required value="{{ $sticker->property_number }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="article" style="font-size: 17px;">Article:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('office') ? 'has-error' : '' }}">
                                                        <textarea id="article" name="article" class="form-control capitalized" rows="3" placeholder="Enter Article"
                                                        style="max-width: 100%; min-width: 100%; max-height: 80px; min-height: 80px; resize: none;" required>{{ $sticker->article }}</textarea>
                                                        <br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="brand_sn" style="font-size: 17px;">Brand/S.N.:</label>
                                                    </td>
                                                    <td class="{{ $errors->has('brand_sn') ? 'has-error' : '' }}">
                                                        <input id="brand_sn" name="brand_sn" type="text" class="form-control underline capitalized" placeholder="Enter Brand/S.N." value="{{ $sticker->brand_sn }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="remarks" style="font-size: 17px;">Remarks:</label>
                                                    </td>
                                                    <td class="{{ $errors->has('remarks') ? 'has-error' : '' }}">
                                                        <input id="remarks" name="remarks" type="text" class="form-control underline capitalized" placeholder="Enter Remarks" value="{{ $sticker->remarks }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="date_count" style="font-size: 17px;">Date of Count:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('date_count') ? 'has-error' : '' }}">
                                                        <input id="date_count" name="date_count" type="number" class="form-control underline" pattern="[0-9]" placeholder="Enter Count"
                                                        required min="2000" value="{{ $sticker->date_count }}"><br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="memo_receipt_employee" style="font-size: 17px;">M.R.:<span class="text-danger"> *</span></label>
                                                    </td>
                                                    <td class="{{ $errors->has('memo_receipt_employee') ? 'has-error' : '' }}">
                                                        <input id="memo_receipt_employee" name="memo_receipt_employee" type="text" class="form-control underline capitalized" placeholder="Enter M. R." value="{{ $sticker->memo_receipt_employee }}">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <button form="data" type="submit" class="btn btn-primary save pull-right" style="margin-left:5px;"><i class="loading fa fa-save"></i><span>  Update</span></button>
                            </div>
                            <div class="pull-left">
                                <button type="button" class="btn btn-default" onclick="window.location='/inventories'">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br>
    <br>
    <!-- /.content-wrapper -->
@endsection