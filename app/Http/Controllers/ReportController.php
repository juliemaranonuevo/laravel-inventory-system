<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Category;
use App\Item;
use App\Office;
use App\Sticker;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = [
            'title' => 'Reports',
            'subtitle' => 'Database'
        ];

        $user = Auth::user();

        $categories = Category::all();

        $offices = Office::all();

        return view( 'reports.index', compact( 'user', 'page', 'categories', 'offices') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 
     * Display a report of inventory.
     *
     */
    public function inventory(Request $request)
    {
        $page = [
            'title' => 'Inventory Reports',
            'subtitle' => 'Database'
        ];

        $dateTime = Carbon::now();

        $dateTime = Carbon::parse( $dateTime )->format( 'F d, Y - h:i A' );

        $user = Auth::user();

        $counter = 0;

        //normal user
        if ( $user->user_type == 0 ) {

            $office_name = Office::where( 'id', $user->office_id )->value( 'office_name' );

            if ( $request->category == null ) {

                //get all the items from the database that the category was not selected.
                $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                ->select(
                    'tbl_items.id', 
                    'tbl_items.item_name', 
                    'tbl_items.unit', 
                    'tbl_categories.name', 
                    'tbl_item_quantities.in', 
                    'tbl_item_quantities.out', 
                    'tbl_item_quantities.condemned', 
                    'tbl_item_quantities.balance', 
                    'tbl_categories.sticker'
                )
                ->where('tbl_offices.id', $user->office_id)
                ->get();

            } else {
                
                //get all the items from the database that the category was selected.
                $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                ->select(
                    'tbl_items.id', 
                    'tbl_items.item_name', 
                    'tbl_items.unit', 
                    'tbl_categories.name', 
                    'tbl_item_quantities.in', 
                    'tbl_item_quantities.out', 
                    'tbl_item_quantities.condemned', 
                    'tbl_item_quantities.balance', 
                    'tbl_categories.sticker'
                )
                ->where('tbl_offices.id', $user->office_id)
                ->where('tbl_categories.id', $request->category)
                ->get();

            }
          
            $allrecords = array();
            foreach ( $items as $item ) {

                $allrecords[] = array(
                    "id" => $item->id,
                    "item_name" => $item->item_name,
                    "unit" => $item->unit,
                    "name" => $item->name,
                    "in" => $item->in,
                    "out" => $item->out,
                    "condemned" => $item->condemned,
                    "balance" => $item->balance,
                    "sticker_type" => $item->sticker,
                );
                
                if ( $item->sticker == 1 ) {

                    $existing_stickers = Sticker::where('item_id', $item->id)
                    ->where('office_id', $user->office_id)
                    ->get();
                 
                    foreach ( $existing_stickers as $sticker ) {

                        $allrecords[ $counter ][ 'sticker' ][] = array(
                            "property_number" => $sticker->property_number,
                            "brand_sn" => $sticker->brand_sn,
                            "memo_receipt_employee" => $sticker->memo_receipt_employee,
                            "type" => $sticker->type,
                        );

                    }

                }

                $counter++;
            }

            return view('reports.inventory_report', compact('user', 'page', 'allrecords', 'dateTime', 'office_name'));


        } else {
        //super user
            
            if ( $request->office == null ) {

                $office_name = 'ALL OFFICES';

                if ( $request->category == null ) {

                    //get all the items from the database of all offices that category was not selected.
                    $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.id as office_id', 
                        'tbl_items.id', 
                        'tbl_items.item_name', 
                        'tbl_items.unit', 
                        'tbl_categories.name', 
                        'tbl_item_quantities.in', 
                        'tbl_item_quantities.out', 
                        'tbl_item_quantities.condemned', 
                        'tbl_item_quantities.balance', 
                        'tbl_categories.sticker'
                    )
                    ->get();

                } else {

                    //get all the items from the database of all offices that category was selected.
                    $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.id as office_id', 
                        'tbl_items.id', 
                        'tbl_items.item_name', 
                        'tbl_items.unit', 
                        'tbl_categories.name', 
                        'tbl_item_quantities.in', 
                        'tbl_item_quantities.out', 
                        'tbl_item_quantities.condemned', 
                        'tbl_item_quantities.balance', 
                        'tbl_categories.sticker'
                    )
                    ->where('tbl_categories.id', $request->category)
                    ->get();

                }

                $allrecords = array();
                foreach ( $items as $item ) {

                    $allrecords[] = array(
                        "id" => $item->id,
                        "item_name" => $item->item_name,
                        "unit" => $item->unit,
                        "name" => $item->name,
                        "in" => $item->in,
                        "out" => $item->out,
                        "condemned" => $item->condemned,
                        "balance" => $item->balance,
                        "sticker_type" => $item->sticker,
                    );
                    
                    if ( $item -> sticker == 1 ) {

                        $existing_stickers = Sticker::where('item_id', $item->id)
                        ->where('office_id', $item->office_id)
                        ->get();
                     
                        foreach ( $existing_stickers as $sticker ) {

                            $allrecords[ $counter ][ 'sticker' ][] =array(
                                "office" => $sticker->office,
                                "property_number" => $sticker->property_number,
                                "brand_sn" => $sticker->brand_sn,
                                "memo_receipt_employee" => $sticker->memo_receipt_employee,
                                "type" => $sticker->type,
                            );

                        }

                    }

                    $counter++;

                }

            } else {

                //used to display the office name in report.
                $office_name = Office::where('id', $request->office)->value( 'office_name' );

                if ( $request->category == null ) {

                    //get all the items from the database of an office that category was not selected.
                    $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_items.id', 
                        'tbl_items.item_name', 
                        'tbl_items.unit', 
                        'tbl_categories.name', 
                        'tbl_item_quantities.in', 
                        'tbl_item_quantities.out', 
                        'tbl_item_quantities.condemned', 
                        'tbl_item_quantities.balance', 
                        'tbl_categories.sticker'
                    )
                    ->where('tbl_item_quantities.office_id', $request->office)
                    ->get();

                } else {

                    //get all the items from the database of an office that category was selected.
                    $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_items.id', 
                        'tbl_items.item_name', 
                        'tbl_items.unit', 
                        'tbl_categories.name', 
                        'tbl_item_quantities.in', 
                        'tbl_item_quantities.out', 
                        'tbl_item_quantities.condemned', 
                        'tbl_item_quantities.balance', 
                        'tbl_categories.sticker'
                    )
                    ->where('tbl_categories.id', $request->category)
                    ->where('tbl_item_quantities.office_id', $request->office)
                    ->get();

                }

                $allrecords = array();
                foreach ( $items as $item ) {

                    $allrecords[] = array(
                        "id" => $item->id,
                        "item_name" => $item->item_name,
                        "unit" => $item->unit,
                        "name" => $item->name,
                        "in" => $item->in,
                        "out" => $item->out,
                        "condemned" => $item->condemned,
                        "balance" => $item->balance,
                        "sticker_type" => $item->sticker,
                    );
                    
                    if ( $item -> sticker == 1 ) {

                        $existing_stickers = Sticker::where('item_id', $item->id)
                        ->where('office_id', $request->office)
                        ->get();
                     
                        foreach ( $existing_stickers as $sticker ) {

                            $allrecords[ $counter ][ 'sticker' ][] =array(
                                "office" => $sticker->office,
                                "property_number" => $sticker->property_number,
                                "brand_sn" => $sticker->brand_sn,
                                "memo_receipt_employee" => $sticker->memo_receipt_employee,
                                "type" => $sticker->type,
                            );

                        }

                    }

                    $counter++;

                }

            }

            $ifOfficeSelected = $request->office;

            return view('reports.inventory_report', compact('user', 'page', 'allrecords', 'dateTime', 'office_name', 'ifOfficeSelected'));

        }

    }

    /**
     * 
     * Display a report of audit trail.
     *
     */
    public function auditTrail(Request $request)
    {
        
        if ( !isset($request->dateRange) ) {

           return redirect('/reports');

        } else {

            $page = [
                'title' => 'Audit Trail Reports',
            ];
    
            $dates = explode( ' - ', $request->dateRange );
            $start_date = Carbon::parse( $dates[ 0 ] ); //date range - start
            $end_date = Carbon::parse( $dates[ 1 ]. '23:59:59' );//date range - end
            $dateTime = Carbon::now();
            $dateTime = Carbon::parse( $dateTime )->format( 'F d, Y - h:i:s A' );
    
            $user = Auth::user();
        
            $counter = 0;
    
            //normal user
            if ( $user->user_type == 0 ) {
    
                //used to display the office name in report.
                $office_name = Office::where( 'id', $user->office_id )->value( 'office_name' );
                
                //get all the audit trails from the database of an office.
                $auditTrail_reports = AuditTrail::join('tbl_users', 'tbl_audit_trails.user_id', '=', 'tbl_users.id')
                ->join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                ->select(
                    'tbl_audit_trails.id as auditTrail_id', 
                    'tbl_audit_trails.action', 
                    'tbl_audit_trails.created_at as auditTrail_date', 
                    'tbl_users.email', 
                    'tbl_offices.office_code'
                )
                ->where('tbl_audit_trails.user_id', $user->id)
                ->whereBetween('tbl_audit_trails.created_at', [ $start_date, $end_date ])
                ->orderBy( 'tbl_audit_trails.created_at', 'DESC')
                ->get();
        
                $allrecords = array();
                foreach ( $auditTrail_reports as $auditTrail_report ) {
    
                    $allrecords[] = array(
                        "id" => $auditTrail_report->auditTrail_id,
                        "action" => $auditTrail_report->action,
                        "created_at" => Carbon::parse( $auditTrail_report->auditTrail_date )->format('m-d-Y | h:i:s a'),
                        "email" => $auditTrail_report->email,
                        "office_code" => $auditTrail_report->office_code,
                    );
    
                }
                
                //date range - start
                $start_date = Carbon::parse( $dates[ 0 ] )->format('F d, Y');
    
                //date range - end
                $end_date = Carbon::parse( $dates[ 1 ] )->format('F d, Y');
    
                return view( 'reports.auditTrail_report', compact( 'user', 'page', 'allrecords', 'dateTime', 'office_name', 'start_date', 'end_date' ) );
    
            } elseif ( $user->user_type == 1 ) {
            //super user
    
                if ( $request->office == null ) {
    
                    $office_name = 'ALL OFFICES';
    
                    //get all the audit trails from the database of all offices.
                    $auditTrail_reports = AuditTrail::join('tbl_users', 'tbl_audit_trails.user_id', '=', 'tbl_users.id')
                    ->join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                    ->select(
                        'tbl_audit_trails.id as auditTrail_id', 
                        'tbl_audit_trails.action', 
                        'tbl_audit_trails.created_at as auditTrail_date', 
                        'tbl_users.email', 
                        'tbl_offices.office_code'
                    )
                    ->whereBetween('tbl_audit_trails.created_at', [ $start_date, $end_date ])
                    ->orderBy( 'tbl_audit_trails.created_at', 'DESC')
                    ->get();
                    
                   
                } else {
    
                    //used to display the office name in report.
                    $office_name = Office::where('id', $request->office)->value( 'office_name' );
    
                    //get all the audit trails from the database of selected office.
                    $auditTrail_reports = AuditTrail::join('tbl_users', 'tbl_audit_trails.user_id', '=', 'tbl_users.id')
                    ->join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                    ->select(
                        'tbl_audit_trails.id as auditTrail_id', 
                        'tbl_audit_trails.action', 
                        'tbl_audit_trails.created_at as auditTrail_date', 
                        'tbl_users.email', 
                        'tbl_offices.office_code'
                    )
                    ->where('tbl_offices.id', $request->office)
                    ->whereBetween('tbl_audit_trails.created_at', [ $start_date, $end_date ])
                    ->orderBy( 'tbl_audit_trails.created_at', 'DESC')
                    ->get();
                   
                }
    
                $allrecords = array();
                foreach ( $auditTrail_reports as $auditTrail_report ) {
    
                    $allrecords[] = array(
                        "id" => $auditTrail_report->auditTrail_id,
                        "action" => $auditTrail_report->action,
                        "created_at" => Carbon::parse( $auditTrail_report->auditTrail_date )->format('m-d-Y | h:i:s a'),
                        "email" => $auditTrail_report->email,
                        "office_code" => $auditTrail_report->office_code,
                    );
    
                }
    
                $start_date = Carbon::parse( $dates[ 0 ] )->format('F d, Y');
    
                $end_date = Carbon::parse( $dates[ 1 ] )->format('F d, Y');
    
                $ifOfficeSelected = $request->office;
    
                return view( 'reports.auditTrail_report', compact( 'user', 'page', 'allrecords', 'dateTime', 'office_name', 'start_date', 'end_date', 'ifOfficeSelected' ) );
    
            } 

        }
       
    }
    
}
