<?php

namespace App\Http\Controllers;

use App\Category;
use App\ItemTransaction;
use App\Office;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = [
            'parent' => 'Transaction logs',
            'title' => 'Transaction logs',
            'subtitle' => 'Database'
        ];

        $user = Auth::user();

        $this->validate( $request, [
            'type' => ['required', 'in:0,1']
        ]);

        $withSticker = $request->type;

        $offices = Office::all();

        return view('inventory.transaction_logs.index', compact('user', 'page', 'withSticker', 'offices'));
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

    public function search(Request $request)
    {
        if ( $request->ajax() ) {

            $user = Auth::user();

            //normal user
            if ( $user->user_type == 0 ) {

                //with and without sticker.
                if( $request->hint == 2 ) {

                    //get all the transaction logs from the database of an office using dataTable with ajax.
                    //including the items with and without sticker.
                    //this query is for dashboard dataTable.
                    $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select ( 
                        'tbl_item_quantities.id as quantity_id', 
                        'tbl_items.id as item_id', 
                        'tbl_categories.name', 
                        'tbl_categories.sticker', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_item_transactions.property_number', 
                        'tbl_item_transactions.quantity',
                        'tbl_item_transactions.transaction_date', 
                        'tbl_item_transactions.remarks', 
                        'tbl_item_transactions.updated_at', 
                        'tbl_item_transactions.type'
                    )
                    ->where('tbl_offices.id', $user->office_id)
                    ->get();

                } elseif ( $request->hint == 1 ) {
                //with sticker.

                    //get all the transaction logs of an item with sticker from the database of an office using dataTable with ajax.
                    $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_categories.name', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_item_transactions.property_number as output', 
                        'tbl_item_transactions.transaction_date', 
                        'tbl_item_transactions.remarks', 
                        'tbl_item_transactions.updated_at', 
                        'tbl_item_transactions.type'
                    )
                    ->where('tbl_categories.sticker', 1)
                    ->where('tbl_offices.id', $user->office_id)
                    ->get();

                } elseif ( $request -> hint == 0 ) {
                //without sticker.

                    //get all the transaction logs of an item without sticker from the database of an office using dataTable with ajax.
                    $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_categories.name', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_item_transactions.quantity as output', 
                        'tbl_item_transactions.transaction_date', 
                        'tbl_item_transactions.remarks', 
                        'tbl_item_transactions.updated_at', 
                        'tbl_item_transactions.type'
                    )
                    ->where('tbl_categories.sticker', 0)
                    ->where('tbl_offices.id', $user->office_id)
                    ->get();

                }

            } else {
            //super user

                //with and without sticker.
                if( $request -> hint == 2 ) {

                    //get all the transaction logs from the database of all offices using dataTable with ajax.
                    //including the items with and without sticker
                    //this query is for the dashboard dataTable.
                    $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_item_quantities.id as quantity_id', 
                        'tbl_offices.office_code', 
                        'tbl_items.id as item_id', 
                        'tbl_categories.name', 
                        'tbl_categories.sticker', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_item_transactions.property_number', 
                        'tbl_item_transactions.quantity',
                        'tbl_item_transactions.transaction_date', 
                        'tbl_item_transactions.remarks', 
                        'tbl_item_transactions.updated_at', 
                        'tbl_item_transactions.type'
                    )
                    ->get();
                    
                } elseif ( $request->hint == 1 ) {
                //with sticker.

                    if ( $request->office == null ) {

                        //get all the transaction logs of an item with stickers from the database of all offices using dataTable with ajax.
                        //office input value is null.
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.property_number as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 1)
                        ->get();

                    } else {

                        //get all the transaction logs of an item with stickers from the database using dataTable with ajax.
                        //with selected office
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.property_number as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 1)
                        ->where('tbl_offices.id', $request->office)
                        ->get();

                    }
                  
                } else if($request->hint == 0){
                //without sticker

                    if ( $request->office == null ) {

                        //get all the transaction logs of an item without stickers from the database of all offices using dataTable with ajax.
                        //office input value is null
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.quantity as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 0)
                        ->get();

                    } else {

                        //get all the transaction logs of an item without stickers from the database using dataTable with ajax.
                        //with selected office
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.quantity as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 0)
                        ->where('tbl_offices.id', $request->office)
                        ->get();

                    }

                }

            }

            return datatables( $logs )->make( true );

        } else {

            return redirect('/transaction-logs/select-type');

        }
    }
    
     /**
     * 
     * This is for the 'Dropdown Option' filter by.
     * Option 1 = Category
     * Option 0 = Status
     * 
     */
    public function filterby(Request $request, $id)
    {
        if ( $request -> ajax() ) { 

            //for category
            if ( $id == 1 ) {

                $option  = '<label>Result/s:</label>';
                $option .= '<select  id="result" class="form-control" style="width: 100%;">';
                $option .= '<option value="" selected="selected">All</option>';

                //get all the categories from database for item (with or without sticker).
                $categories = Category::where('sticker', $request->hint)->get(); 

                foreach ( $categories as $category ) {

                    $option .= '<option value = "'. $category->id .'">'. $category->name .'</option>';

                }

                $option .= '</select>';

            } elseif ( $id == 0) {
            //for status

                $option = '<label>Result/s:</label>';
                $option .= '<select  id="result" class="form-control" style="width: 100%;">';
                $option .= '<option value="" selected="selected">All</option>';
                $option .= '<option value = "IN">IN</option>';
                $option .= '<option value = "OUT">OUT</option>';
                $option .= '<option value = "CONDEMNED">CONDEMNED</option>';
                $option .= '</select>';

            }

            return response()->json( [ 'data' => $option ] );

        } 
    }

    public function filterByResult(Request $request, $id)
    {
        if ( $request -> ajax() ) {

            $user = Auth::user();

            //normal user.
            if ( $user->user_type == 0 ) {
               
                //with sticker.
                if ( $request->hint == 1 ) {

                    //get all the transaction logs of all items with stickers through dropdown 'Result/s'.
                    $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                    ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->select(
                        'tbl_categories.name', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_item_transactions.property_number as output', 
                        'tbl_item_transactions.transaction_date', 
                        'tbl_item_transactions.remarks', 
                        'tbl_item_transactions.updated_at', 
                        'tbl_item_transactions.type'
                    )
                    ->where('tbl_categories.sticker', 1)
                    ->where('tbl_offices.id', $user->office_id)
                    ->where('tbl_item_transactions.type', '=', $id)
                    ->orwhere('tbl_categories.id', '=', $id)
                    ->where('tbl_offices.id', $user->office_id)
                    ->get();
                   
                } else {
                //without sticker.

                    //get all the transaction logs of all items without sticker through dropdown 'Result/s'.
                    $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                    ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->select(
                        'tbl_categories.name', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_item_transactions.quantity as output', 
                        'tbl_item_transactions.transaction_date', 
                        'tbl_item_transactions.remarks', 
                        'tbl_item_transactions.updated_at', 
                        'tbl_item_transactions.type'
                    )
                    ->where('tbl_categories.sticker', 0)
                    ->where('tbl_offices.id', $user->office_id)
                    ->where('tbl_item_transactions.type', '=', $id)
                    ->orwhere('tbl_categories.id', '=', $id)
                    ->where('tbl_offices.id', $user->office_id)
                    ->get();
                   
                }

            } else {
            //super user

                if ( $request->office == null ) {

                    //with sticker.
                    if ( $request->hint == 1 ) {

                        //get all the transaction logs of all items with stickers of all offices through dropdown 'Result/s'.
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.property_number as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 1)
                        ->where('tbl_item_transactions.type', '=', $id)
                        ->orwhere('tbl_categories.id', '=', $id)
                        ->get();

                    } else {
                    //without sticker.

                        //get all the transaction logs of all items without sticker of all offices through dropdown 'Result/s'.
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.quantity as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 0)
                        ->where('tbl_item_transactions.type', '=', $id)
                        ->orwhere('tbl_categories.id', '=', $id)
                        ->get();

                    }
                   
                } else {

                    //with sticker.
                    if ( $request->hint == 1 ) {
                     
                        //get all the transaction logs of all items with stickers of an office through dropdown 'Result/s'.
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_offices.office_name', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.property_number as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 1)
                        ->where('tbl_offices.id', $request->office)
                        ->where('tbl_item_transactions.type', '=', $id)
                        ->orwhere('tbl_categories.id', '=', $id)
                        ->where('tbl_offices.id', $request->office)
                        ->get();

                    } else {
                    //without sticker.

                        //get all the transaction logs of all items without sticker of an office through dropdown 'Result/s'.
                        $logs = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                        ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_categories.name', 
                            'tbl_items.item_name', 
                            'tbl_items.stock_keeping_unit', 
                            'tbl_item_transactions.quantity as output', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.remarks', 
                            'tbl_item_transactions.updated_at', 
                            'tbl_item_transactions.type'
                        )
                        ->where('tbl_categories.sticker', 0)
                        ->where('tbl_offices.id', $request->office)
                        ->where('tbl_item_transactions.type', '=', $id)
                        ->orwhere('tbl_categories.id', '=', $id)
                        ->where('tbl_offices.id', $request->office)
                        ->get();

                    }
                   
                }

            }

            return datatables( $logs )->make( true );

        }
    }

    public function selectType()
    {
        $page = [
            'parent' => 'Transaction logs',
            'title' => 'Select type',
            'subtitle' => 'Database'
        ];

        return view('inventory.transaction_logs.select_type', compact('page'));
    }
}
