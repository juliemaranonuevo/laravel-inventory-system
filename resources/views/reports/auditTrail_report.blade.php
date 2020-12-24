@extends('layouts.reports')

@section('page_title', $page['title'])

@section('page_css')
    <link rel="stylesheet" href="/css/reports.css">
@endsection

@section('content')
    <body>
        <div class="container-fluid">
            <div class="header">
                <table style="width: 100%;">
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
                    <tr>
                        <td colspan="3">
                            <p class="text-center" style="font-size: 16pt; font-family: Sans Serif; font-weight: bold;">
                                AUDIT TRAIL REPORT
                            </p>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                <table style="width: 100%;" id="header2">
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
                    <tr>
                        <td style="width: 10%;">
                            <p class="pull-left" style="font-size: 12pt; font-family: Sans Serif;">
                                Date:
                            </p>
                        </td>
                        <td style="width: 45%;" colspan="2">
                            <p class="text-left" style="font-size: 10pt; font-family:  Sans Serif; border-bottom: 1px solid;">
                                {{ $start_date .' to '. $end_date }}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="report-container">
                @php
                    $records = $allrecords;
                    $record_per_page = 26;
                    $number_of_page = ceil(count($records)/$record_per_page);
                    $count = 0;
                @endphp
                @for($x=0;$x<$number_of_page;$x++)
                    <table style="width: 100%;" id="body">
                        <thead>
                            <tr style="height: 40px;">
                                <th style="width: 1%;"></th>
                                <th style="width: 10%;">Date - Time</th>
                                <th style="width: 10%;">User</th>
                                <th style="width: 15%;">Action</th>
                                @if( $user -> user_type != 0 )
                                    @if( $ifOfficeSelected == null )
                                        <th style="width: 2%;">Office</th>
                                    @endif
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @for($y=0;$y<$record_per_page;$y++)
                            
                                @if($count == count($records))

                                    @break

                                @else

                                    <tr>
                                        <td class="text-center">{{ $count+1 }}</td>
                                        <td class="text-left">{{ $records[$count]['created_at'] }}</td>
                                        <td class="text-left">{{ $records[$count]['email'] }}</td>
                                        <td class="text-left">{{ $records[$count]['action'] }}</td>
                                        @if( ! $user -> user_type == 0 && $ifOfficeSelected == null)
                                            <td class="text-left">{{ $records[$count]['office_code'] }}</td>
                                        @endif
                                    </tr>

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
    </body>
@endsection
