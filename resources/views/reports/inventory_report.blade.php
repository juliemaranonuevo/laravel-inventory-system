@extends('layouts.reports')

@section('page_title', $page['title'])

@section('page_css')
    <link rel="stylesheet" href="/css/reports.css">
@endsection

@section('content')
    <body>
        <table style="width: 100%;" id="header">
            <br>
            <tr>
                <td style="width: 25%;">
                    <img class="pull-right" src="/img/" alt="seal" style="height:100px; width: 100px;"/>
                </td>
                <td style="width: 50%; text-align:center;">
                    <p style="font-size: 11pt; font-family: Sans Serif;">
                        Republic of the Country <br>
                        REGION - State<br>
                        Province of </p> 
                    </td>
                <td style="width: 25%;">
                </td>
            </tr>
        </table>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center" style="font-size: 16pt; font-family: Sans Serif; font-weight: bold;">
                        INVENTORY REPORT
                    </p>
                </div>
            </div>
            <div class="row">
                <br>
                <br>
                <div class="col-md-12">
                    <table style="width: 100%;" >
                        <tr>
                            <td style="width: 10%;">
                                <p class="pull-left" style="font-size: 12pt; font-family: Sans Serif;">
                                    Office:
                                </p>
                            </td>
                            <td style="width: 45%;">
                                <p class="text-left" style="font-size: 10pt; font-family:  Sans Serif; border-bottom: 1px solid;">
                                    {{ $office_name }}
                                </p>
                            </td>
                            <td style="width: 40%;"> 
                                <p class="text-left" style="font-size: 10pt; font-family: Sans Serif; border-bottom: 1px solid;">
                                    Date Validity as of: {{ $dateTime }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 10%;">
                                <p class="pull-left" style="font-size: 12pt; font-family: Sans Serif;">
                                    Address:
                                </p>
                            </td>
                            <td style="width: 45%;" colspan="2">
                                <p class="text-left" style="font-size: 10pt; font-family:  Sans Serif; border-bottom: 1px solid;">
                                    PROVINCIAL CAPITOL COMPOUND, POBLACION 1 SANTA CRUZ, LAGUNA
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">    
                    @php
                        $records = $allrecords;
                        $stickers = $allrecords;
                        $record_per_page = 20;
                        $number_of_page = ceil(count($records)/$record_per_page);
                        $count = 0;
                    @endphp

                    @for($x=0;$x<$number_of_page;$x++)
                    
                    <table style="width: 100%; " id="body">
                        <thead>
                            <tr style="height: 40px;">
                                <th style="width: 2%;"></th>
                                <th style="width: 15%;" colspan="2">Item Name</th>
                                <th style="width: 11%;" colspan="2">Unit</th>
                                <th style="width: 12.98%;" colspan="2">Category</th>
                                <th style="width: 10%;" colspan="2">In</th>
                                <th style="width: 10%;" colspan="2">Out</th>
                                <th style="width: 10%;" colspan="2">Condemned</th>
                                <th style="width: 10%;" colspan="2">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($y=0;$y<$record_per_page;$y++)

                                @if( $count == count( $records ) )

                                    @break

                                @else
                                    <tr>
                                        <td class="text-center">{{ $count+1 }}</td>
                                        <td class="text-left" colspan="2">{{ $records[$count]['item_name'] }}</td>
                                        <td class="text-left" colspan="2">{{ $records[$count]['unit'] }}</td>
                                        <td class="text-left" colspan="2">{{ $records[$count]['name'] }}</td>
                                        <td class="text-right" colspan="2">{{ $records[$count]['in'] }}</td>
                                        <td class="text-right" colspan="2">{{ $records[$count]['out'] }}</td>
                                        <td class="text-right" colspan="2">
                                        @if($records[$count]['sticker_type'] == 1)
                                            {{ $records[$count]['condemned'] }}
                                        @else
                                            N/A
                                        @endif
                                        </td>
                                        <td class="text-right" colspan="2">{{ $records[$count]['balance'] }}</td>
                                    </tr>
                                    @if( isset( $stickers[ $count ][ 'sticker' ] ) )
                                        <tr>
                                            <td colspan="15" style="width: 102%; border: 0px white;">
                                                @php
                                                    $stickers_count = count($stickers[$count]['sticker']);
                                                    $stickers_per_item = 44;
                                                    $number_of_page = ceil($stickers_count/$stickers_per_item);
                                                    $stickers_counter = 0;
                                                @endphp

                                                @for($x=0;$x<$number_of_page;$x++)

                                                    <table id="tbl-sticker" style="width: 100.1%;   border: 1px solid black;">    
                                                        <thead>    
                                                            <tr class="tr-sticker">
                                                                <th style="width: 2.3%;"></th>
                                                                <th style="width: 3.3%;"></th>
                                                                @if( $user -> user_type == 0 || $ifOfficeSelected != null)
                                                                    <th style="width: 28.2%;">Property Number</th>
                                                                    <th style="width: 27.84%;">Brand S/N</th>
                                                                    <th style="width: 26.64%;">M.R.</th>
                                                                    <th>Type</th>
                                                                @else
                                                                    <th style="width: 21.9%;">Property Number</th>
                                                                    <th style="width: 22.1%;">Brand S/N</th>
                                                                    <th style="width: 18.54%;">M.R.</th>
                                                                    <th style="width: 13.5%;">Type</th>
                                                                    <th>Office</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @for($z=0;$z<$stickers_per_item;$z++)
                                                                @if($stickers_counter == $stickers_count)

                                                                    @break

                                                                @else
                                                                
                                                                    <tr class="tr-sticker">
                                                                        <td></td>
                                                                        <td class="text-center">{{ $stickers_counter+1 }}</td>
                                                                        <td class="text-left">{{ $stickers[$count]['sticker'][$stickers_counter]['property_number'] }}</td>
                                                                        <td class="text-left">{{ $stickers[$count]['sticker'][$stickers_counter]['brand_sn'] }}</td>
                                                                        <td class="text-left">{{ $stickers[$count]['sticker'][$stickers_counter]['memo_receipt_employee'] }}</td>
                                                                        <td class="text-left">{{ $stickers[$count]['sticker'][$stickers_counter]['type'] }}</td>
                                                                        @if( $user -> user_type != 0)
                                                                            @if($ifOfficeSelected == null)
                                                                                <td class="text-left">{{ $stickers[$count]['sticker'][$stickers_counter]['office'] }}</td>
                                                                            @endif
                                                                        @endif
                                                                    </tr>
                                                                    
                                                                @endif
                                                                @php $stickers_counter++; @endphp
                                                            @endfor
                                                        </tbody>
                                                    </table>
                                                    <div class="page-break"></div>
                                                @endfor
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                @php $count++; @endphp
                            @endfor
                            <br>
                        </tbody>
                    </table>
                    <div class="page-break"></div>
                    @endfor
                </div>
            </div>
        </div>
    </body>
@endsection
