<?php

namespace App\Http\Controllers;

use App\ItemQuantity;
use App\Sticker;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get the user_type and office_id of a user that logged in.
        $user = Auth::user();

        $page = [
            'parent' => 'Dashboard',
            'title' => 'Dashboard',
            'subtitle' => 'Control Panel'
        ];

        //normal user
        if ( $user->user_type == 0 ) {

            //get the sum of all available non-sticker items.
            $nosticker = ItemQuantity::join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
            ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
            ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
            ->where('tbl_categories.sticker', 0)
            ->where('tbl_offices.id', $user->office_id)
            ->sum('tbl_item_quantities.balance');

            //get the IN count of all items with sticker.
            $withsticker_in = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
            ->where('tbl_offices.id', $user->office_id)
            ->where('tbl_stickers.type', 'IN')
            ->get();

            //get the OUT count of all items with sticker.
            $withsticker_out = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
            ->where('tbl_offices.id', $user->office_id)
            ->where('tbl_stickers.type', 'OUT')
            ->get();

            //get the CONDEMNED count of all items with sticker.
            $withsticker_condemned = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
            ->where('tbl_offices.id', $user->office_id)
            ->where('tbl_stickers.type', 'CONDEMNED')
            ->get();

        } else {
        //super user
            
            //get the sum of all available non-sticker items from all offices.
            $nosticker = ItemQuantity::join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
            ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
            ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
            ->where('tbl_categories.sticker', 0)
            ->sum('tbl_item_quantities.balance');

            //get the IN count of all items with sticker from all offices.
            $withsticker_in = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
            ->where('tbl_stickers.type', 'IN')
            ->get();

            //get the OUT count of all items with sticker from all offices.
            $withsticker_out = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
            ->where('tbl_stickers.type', 'OUT')
            ->get();

            //get the CONDEMNED count of all items with sticker from all offices.
            $withsticker_condemned = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
            ->where('tbl_stickers.type', 'CONDEMNED')
            ->get();

        }

        return view( 'dashboard.index', compact( 'user', 'page', 'nosticker', 'withsticker_in', 'withsticker_out', 'withsticker_condemned' ) );

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
       
        if ( $id == 'in' ) {

            $title = 'In';

            $type = 0; //if the dashboard 'In' info was clicked. This is for dashboard.js 'dataTable ajax'

        } elseif ( $id == 'out' ) {

            $title = 'Out';

            $type = 1; //if the dashboard 'Out' info was clicked. This is for dashboard.js 'dataTable ajax'

        } elseif ( $id == 'condemned' ) {

            $title = 'Condemned';

            $type = 2; //if the dashboard 'Condemned' info was clicked. This is for dashboard.js 'dataTable ajax'

        } elseif ( $id == 'no_sticker' ) {

            $title = 'No Sticker';

            $type = 3; //if the dashboard 'No sticker' info was clicked. This is for dashboard.js 'dataTable ajax'

        } 

        $page = [
            'parent' => 'Dashboard',
            'title' => $title,
            'subtitle' => 'Database'
        ];

        //get the user_type of a user that logged in.
        $user = Auth::user();

        return view( 'dashboard.show', compact( 'user', 'page', 'type' ) );

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
        //get the user_type of a user that logged in.
        $user = Auth::user();

        if ( $request->ajax() ) { 

            //normal user
            if ( $user->user_type == 0 ) {

                //type 0 is IN type - dashboard/show.blade.php
                if ( $request->type == 0 ) {

                    //get all the 'IN' items that has sticker from an office - normal_user/dashboard.js 'dataTable ajax'.
                    $stickers = Sticker::join('tbl_items', 'tbl_stickers.item_id', '=', 'tbl_items.id')
                    ->join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_stickers.property_number', 
                        'tbl_categories.name',
                        'tbl_stickers.article', 
                        'tbl_stickers.brand_sn', 
                        'tbl_stickers.remarks', 
                        'tbl_stickers.date_count', 
                        'tbl_stickers.memo_receipt_employee', 
                        'tbl_stickers.updated_at'
                    )
                    ->where('tbl_offices.id', $user->office_id)
                    ->where('tbl_stickers.type', 'IN')
                    ->get();

                    return datatables( $stickers )->make( true );

                } elseif ( $request->type == 1 ) {
                //1 is for OUT type - dashboard/show.blade.php

                    //get all the 'OUT' items that has sticker from an office - normal_user/dashboard.js 'dataTable ajax'.
                    $stickers = Sticker::join('tbl_items', 'tbl_stickers.item_id', '=', 'tbl_items.id')
                    ->join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_stickers.property_number', 
                        'tbl_categories.name',
                        'tbl_stickers.article', 
                        'tbl_stickers.brand_sn', 
                        'tbl_stickers.remarks', 
                        'tbl_stickers.date_count', 
                        'tbl_stickers.memo_receipt_employee', 
                        'tbl_stickers.updated_at'
                    )
                    ->where('tbl_offices.id', $user->office_id)
                    ->where('tbl_stickers.type', 'OUT')
                    ->get();

                    return datatables( $stickers )->make( true );

                } elseif ( $request->type == 2 ) {
                //2 is for CONDEMNED type - dashboard/show.blade.php

                    //get all the 'CONDEMNED' items that has sticker from an office - normal_user/dashboard.js 'dataTable ajax'.
                    $stickers = Sticker::join('tbl_items', 'tbl_stickers.item_id', '=', 'tbl_items.id')
                    ->join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_stickers.property_number', 
                        'tbl_categories.name',
                        'tbl_stickers.article', 
                        'tbl_stickers.brand_sn', 
                        'tbl_stickers.remarks', 
                        'tbl_stickers.date_count', 
                        'tbl_stickers.memo_receipt_employee', 
                        'tbl_stickers.updated_at'
                    )
                    ->where('tbl_offices.id', $user->office_id)
                    ->where('tbl_stickers.type', 'CONDEMNED')
                    ->get();

                    return datatables( $stickers )->make( true );

                } elseif ( $request->type == 3 ) {
                //3 is for non-sticker type - dashboard/show.blade.php

                    //get all the 'non-sticker' items that has sticker from an office - normal_user/dashboard.js 'dataTable ajax'.
                    $no_stickers = ItemQuantity::join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->where('tbl_offices.id', $user->office_id)
                    ->where('tbl_item_quantities.balance', '!=', 0)
                    ->where('tbl_categories.sticker', '=', 0)
                    ->get();

                    return datatables( $no_stickers )->make( true );

                }

            } else {
            //super user

                //type 0 is IN type - dashboard/show.blade.php
                if ( $request->type == 0 ) {

                    //get all the 'IN' items that has sticker from all offices - super_admin/dashboard.js dataTables with ajax.
                    $stickers = Sticker::join('tbl_items', 'tbl_stickers.item_id', '=', 'tbl_items.id')
                    ->join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_stickers.property_number', 
                        'tbl_categories.name',
                        'tbl_stickers.article', 
                        'tbl_stickers.brand_sn', 
                        'tbl_stickers.remarks', 
                        'tbl_stickers.date_count', 
                        'tbl_stickers.memo_receipt_employee', 
                        'tbl_stickers.updated_at'
                    )
                    ->where('tbl_stickers.type', 'IN')
                    ->get();

                    return datatables( $stickers )->make( true );

                } elseif ( $request->type == 1 ) {
                //1 is for OUT type - dashboard/show.blade.php

                    //get all the 'OUT' items that has sticker from all offices - super_admin/dashboard.js dataTables with ajax.
                    $stickers = Sticker::join('tbl_items', 'tbl_stickers.item_id', '=', 'tbl_items.id')
                    ->join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_stickers.property_number', 
                        'tbl_categories.name',
                        'tbl_stickers.article', 
                        'tbl_stickers.brand_sn', 
                        'tbl_stickers.remarks', 
                        'tbl_stickers.date_count', 
                        'tbl_stickers.memo_receipt_employee', 
                        'tbl_stickers.updated_at'
                    )
                    ->where('tbl_stickers.type', 'OUT')
                    ->get();

                    return datatables( $stickers )->make( true );

                } elseif ( $request->type == 2 ) {
                //2 is for CONDEMNED type - dashboard/show.blade.php

                    //get all the 'CONDEMNED' items that has sticker from all offices - super_admin/dashboard.js dataTables with ajax.
                    $stickers = Sticker::join('tbl_items', 'tbl_stickers.item_id', '=', 'tbl_items.id')
                    ->join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_items.item_name', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_stickers.property_number', 
                        'tbl_categories.name',
                        'tbl_stickers.article', 
                        'tbl_stickers.brand_sn', 
                        'tbl_stickers.remarks', 
                        'tbl_stickers.date_count', 
                        'tbl_stickers.memo_receipt_employee', 
                        'tbl_stickers.updated_at'
                    )
                    ->where('tbl_stickers.type', 'CONDEMNED')
                    ->get();

                    return datatables( $stickers )->make( true );

                } elseif ( $request->type == 3 ) {
                // 3 is for non-sticker type - dashboard/show.blade.php

                    //get all the 'non-sticker' items from all offices - super_admin/dashboard.js dataTables with ajax.
                    $no_stickers = ItemQuantity::join('tbl_items', 'tbl_item_quantities.item_id', '=', 'tbl_items.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->where('tbl_item_quantities.balance', '!=', 0)
                    ->where('tbl_categories.sticker', '=', 0)
                    ->get();

                    return datatables( $no_stickers )->make( true );

                }

            }

        }

    }

}
