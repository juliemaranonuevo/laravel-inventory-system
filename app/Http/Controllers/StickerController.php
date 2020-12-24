<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Item;
use App\ItemQuantity;
use App\ItemTransaction;
use App\Sticker;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StickerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index($id, $quantity_id)
    public function index(Item $item, ItemQuantity $itemQuantity)
    {
        $page = [
            'parent' => 'Overview',
            'title' => 'Stickers',
            'subtitle' => 'Database'
        ];

        $user = Auth::user();

        //normal user
        if ( $user->user_type == 0 ) {

            //chek if the item_quantity office_id is not equal to user office_id and abort 403
            if ( $itemQuantity->office_id !== $user->office_id ) {

                return redirect('/inventories');

            } else {

                //get all stickers of an office.
                $sticker = Sticker::join('tbl_item_quantities', 'tbl_stickers.office_id', '=', 'tbl_item_quantities.office_id')
                ->where('tbl_stickers.item_id', $item->id)
                ->where('tbl_stickers.office_id', $user->office_id)
                ->first();

                if ( empty( $sticker ) ) { 

                    abort( 404 );
        
                }

            }

        } else {
        //super user

            //get all stickers of selected office.
            $sticker = Sticker::join('tbl_item_quantities', 'tbl_stickers.office_id', '=', 'tbl_item_quantities.office_id')
            ->where('tbl_stickers.item_id', $item->id)
            ->where('tbl_stickers.office_id', $itemQuantity->office_id)
            ->first();

            if ( empty( $sticker ) ) { 

                abort( 404 );

            }

        }

        return view('inventory.sticker.index', compact('user', 'page', 'sticker'));

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
    public function edit(Sticker $sticker)
    {
        $page = [
            'parent' => 'Overview',
            'title' => 'Update sticker',
            'subtitle' => 'Database'
        ];

        return view('inventory.sticker.edit', compact('sticker', 'page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sticker $sticker)
    {
        //if nothing change
        if ( $sticker->office == $request->office && $sticker->property_number == $request->property_number 
            && $sticker->article == $request->article && $sticker->brand_sn == $request->brand_sn 
            && $sticker->remarks == $request->remarks && $sticker->date_count == $request->date_count 
            && $sticker->memo_receipt_employee == $request->memo_receipt_employee ) {
            
            $ItemQuantity = ItemQuantity::where('item_id', $sticker->item_id)
            ->where('office_id', $sticker->office_id)
            ->first();

            $response = redirect( '/inventories/'. $ItemQuantity->item_id .'/stickers/'. $ItemQuantity->id );

        } else {
    
            $this->validate( $request, [
                'office' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'property_number' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'article' => ['required'],
                'brand_sn' => ['nullable', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'remarks' => ['nullable', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'date_count' => ['required', 'numeric', 'not_in:0'],
                'memo_receipt_employee' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/']
            ]);

            DB::beginTransaction();

            try {
                
                //update if the current ItemTransaction property number and the request propertry number were not the same.
                $transaction_stickers = ItemTransaction::where( 'property_number', $sticker->property_number )->firstOrFail();
                $transaction_stickers->property_number = $request->property_number;
                $transaction_stickers->save();
                
                //update if the sticker fields were changed.
                $sticker->office = $request->office;
                $sticker->property_number = $request->property_number;
                $sticker->article = $request->article;
                $sticker->brand_sn = $request->brand_sn;
                $sticker->remarks = $request->remarks;
                $sticker->date_count = $request->date_count;
                $sticker->memo_receipt_employee = $request->memo_receipt_employee;
                $sticker->save();

                //save the user action.
                $auditTrail = new AuditTrail;
                $auditTrail->action = 'Sticker property number '. $request->property_number .' has been updated.';
                $auditTrail->user_id = Auth::user()->id;
                $auditTrail->save();

                DB::commit();
                
                $output = 'Sticker property number '. $request->property_number .' has been updated successfully!';

                //use to get the item_quantity id and item_id that will use to redirect.
                $ItemQuantity = ItemQuantity::where('item_id', $sticker->item_id)
                ->where('office_id', $sticker->office_id)
                ->first();

                $response = redirect('/inventories/'. $ItemQuantity->item_id .'/stickers/'. $ItemQuantity->id )->with('success', $output);

            } catch ( \Exception $e ) {
                
                DB::rollBack();
                //this error code is for duplicate entry
                if ( $e->getCode() == 23000 ) {

                    $response = back()->withErrors( $e->errorInfo[2] );

                } else {

                    $response = back()->withErrors( $e->getMessage() );

                }
                
            }

        }

        return $response; 
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

    public function search(Request $request, Item $item, ItemQuantity $itemQuantity)
    {
        if ( $request->ajax() ) {

            $stickers = Sticker::where('item_id', $item->id)
            ->where('office_id', '=', $itemQuantity->office_id)
            ->get();
        
            return datatables( $stickers )->make( true );

        } else {

            return redirect('/inventories/'.  $item->id .'/stickers/'. $itemQuantity->id);
        }

    }

}
