<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\ItemQuantity;
use App\Sticker;
use App\ItemTransaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $Item_transaction_id
     * @return \Illuminate\Http\Response
     */
    // public function index($Item_transaction_id)
    public function index(ItemQuantity $itemQuantity)
    {
        $page = [
            'parent' => 'Overview',
            'title' => 'Transactions',
            'subtitle' => 'Database'
        ];

        $user = Auth::user();
        
        //check if the item quentity office_id is not equal to user office_id
        if ( $itemQuantity->office_id !== $user->office_id ) {

            abort( 403 );

        } else {

            //get all the item transactions.
            $Item_transactions = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id' )
            ->where('tbl_item_transactions.item_quantity_id', $itemQuantity->id)
            ->where('tbl_item_quantities.office_id', $user->office_id)
            ->orderBy('tbl_item_transactions.id', 'DESC')
            ->get();

            if ( empty( $Item_transactions ) ) { 

                abort( 404 );
    
            }

        }

        return view( 'inventory.transaction.index', compact( 'Item_transactions', 'itemQuantity', 'page' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ItemTransaction $itemTransaction)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ItemQuantity $itemQuantity)
    {   
        //with sticker
        if ( $itemQuantity->item->category->sticker == 1 ) {

            $status = array();

            foreach( $itemQuantity->item->Sticker as $key ) {

                $status[] = $key->property_number;

            }

            //used for validation
            $items = implode( ",", $status );
        
            $this->validate( $request, [
                'type' => [ 'required', 'in:IN,OUT,CONDEMNED' ],
                'property_number' => [ 'required','array','min:1','in:'. $items .'' ],
                'transaction_date' => [ 'required', 'date', 'after:1/1/2000' ],
                'remarks' => [ 'required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/' ],
            ] );
            
            $property_number = $request->all();
            
            //used for return back with custom error.
            for ( $i = 0; $i<count( $property_number[ 'property_number' ] ); $i++ ) {

                foreach( $itemQuantity->item->sticker as $sticker ) {

                    if( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == $request->type ) {

                        if ( $request->type == 'IN' ) {

                            return back()->with( 'custom_error_message', "Sorry, the item with property number " 
                            . $property_number[ 'property_number' ][ $i ] . " is still available! <a href='/inventories/select_category'>
                            Click here to add new item.</a>" );

                        } elseif ( $request->type == 'OUT' ) {

                            return back()->with('custom_error_message',"Sorry, the item with property number "
                            . $property_number['property_number'][$i] ." is already use! <a href='/inventories/select_category'>
                            Click here to add new item.</a>");

                        } elseif ( $request->type == 'CONDEMNED' ) {

                            return back()->with('custom_error_message',"Sorry, the item with property number "
                            . $property_number[ 'property_number' ][ $i ] ." is already CONDEMNED! <a href='/inventories/select_category'>
                            Click here to add new item.</a>");

                        }

                    }

                }

            }

            //functions
            if ( $request->type == 'IN' ) {

                $result = $this->in( $request, $itemQuantity );

            } elseif ( $request->type == 'OUT' ) {

                $result = $this->out( $request, $itemQuantity );

            } elseif ( $request->type == 'CONDEMNED' ) {

                $result = $this->condemned( $request, $itemQuantity );

            }


            if ( $result == true ) {
                
                return back()->with( 'success', 'New transaction added successfully!' );

            } else {

                return $result;
                
            }

        } else {
        //without sticker

            $this->validate( $request, [
                'quantity' => ['required','not_in:0'],
                'type' => ['required','in:IN,OUT'],
                'transaction_date' => ['required', 'date', 'after:1/1/2000'],
            ] );
        
            $result = $this->NoSticker( $request, $itemQuantity );

            if ( $result == true ) {
                
                return back()->with( 'success', 'New transaction added successfully!' );

            } else {

                return $result;
                
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\ItemTransaction  $ItemTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTransaction $ItemTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\ItemTransaction  $ItemTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTransaction $ItemTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\ItemTransaction  $ItemTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTransaction $ItemTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Item_transaction  $Item_transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemTransaction $ItemTransaction)
    {
        //
    }
    
    /**
     * 
     * Item in with sticker
     *
     */
    public function in(Request $request, $itemQuantity)
    {

        DB::beginTransaction();

        try {

            $property_number = $request->all();
                    
            $condemn_count = 0; $out_count = 0;

            //looping of selected property numbers
            for ( $i = 0; $i < count( $property_number[ 'property_number' ] ); $i++ ) {

                foreach ( $itemQuantity->item->sticker as $sticker ) {

                    if ( $sticker->property_number ==  $property_number[ 'property_number' ][ $i ] && $sticker->type != 'IN' ) {

                        //if property number type is condemned, the condemn_count will increment.
                        if ( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == 'CONDEMNED' ) {

                            ++$condemn_count;
    
                        }
    
                        //if property number type is out, the out_count will increment.
                        if ( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == 'OUT' ) {
    
                            ++$out_count;
    
                        }

                        //update the sticker type.
                        $sticker->type = 'IN';
                        $sticker->save();

                    }

                }

                //add new transactions
                $itemTransaction = new ItemTransaction;
                $itemTransaction->property_number = $property_number[ 'property_number' ][ $i ];
                $itemTransaction->type = $request->type;
                $itemTransaction->quantity = 1;
                $itemTransaction->transaction_date = $request->transaction_date;
                $itemTransaction->remarks = $request->remarks;
                $itemTransaction->item_quantity_id = $itemQuantity->id;
                $itemTransaction->save();

            }

            //operations
            $out = $itemQuantity->out - $out_count;

            $condemned = $itemQuantity->condemned - $condemn_count; 

            $balance = count( $property_number[ 'property_number' ] ) + $itemQuantity->balance;

            //update the current quantity
            $itemQuantity->in = $itemQuantity->in;
            $itemQuantity->out = $out;
            $itemQuantity->condemned = $condemned;
            $itemQuantity->balance = $balance;
            $itemQuantity->save();

            $selected_items = implode( ",", $property_number[ 'property_number' ] );

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = 'Property number/s ('. $selected_items .') in item '. $itemQuantity->item->item_name .' added to '. $request->type .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = true;

        } catch ( \Exception $e ) {
                
            DB::rollBack();

            $response = back()->withErrors( $e->getMessage() );
            
        }

        return $response; 

    }

    /**
     * 
     * Item out with sticker
     *
     */
    public function out(Request $request, $itemQuantity)
    {
        
        DB::beginTransaction();

        try {

            $property_number = $request->all();

            $condemn_count = 0; $in_count = 0;

            //looping of selected property numbers
            for ( $i = 0; $i < count( $property_number[ 'property_number' ] ); $i++ ) {

                foreach ( $itemQuantity->item->sticker as $sticker ) {

                    if ( $sticker->property_number ==  $property_number[ 'property_number' ][ $i ] && $sticker->type != 'OUT' ) {

                        //if property number type is condemned, the condemn_count will increment.
                        if ( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == 'CONDEMNED' ) {

                            ++$condemn_count;
    
                        }
    
                        //if property number type is in, the in_count will increment.
                        if ( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == 'IN' ) {
    
                            ++$in_count;
    
                        }

                        //update the sticker type.
                        $sticker->type = 'OUT';
                        $sticker->save();

                    }

                }

                //add new transactions
                $itemTransaction = new ItemTransaction;
                $itemTransaction->property_number = $property_number[ 'property_number' ][ $i ];
                $itemTransaction->type = $request->type;
                $itemTransaction->quantity = 1;
                $itemTransaction->transaction_date = $request->transaction_date;
                $itemTransaction->remarks = $request->remarks;
                $itemTransaction->item_quantity_id = $itemQuantity->id;
                $itemTransaction->save();

            }

            //operations
            $out = count( $property_number[ 'property_number' ] ) + $itemQuantity->out;

            $condemned = $itemQuantity->condemned - $condemn_count; 

            $balance = $itemQuantity->balance - $in_count;

            //update the current quantity
            $itemQuantity->in = $itemQuantity->in;
            $itemQuantity->out = $out;
            $itemQuantity->condemned = $condemned;
            $itemQuantity->balance = $balance;
            $itemQuantity->save();

            $selected_items = implode( ",", $property_number[ 'property_number' ] );

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = 'Property number/s ('. $selected_items .') in item '. $itemQuantity->item->item_name .' added to '. $request->type .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = true;

        } catch ( \Exception $e ) {
                
            DB::rollBack();

            $response = back()->withErrors( $e->getMessage() );
            
        }

        return $response; 

    }

    /**
     * 
     * Item condemned with sticker
     *
     */
    public function condemned(Request $request, $itemQuantity)
    {
        
        DB::beginTransaction();

        try {

            $property_number = $request->all();

            $in_count = 0; $out_count = 0;

            //looping of selected property numbers
            for ( $i = 0; $i < count( $property_number[ 'property_number' ] ); $i++ ) {

                foreach ( $itemQuantity->item->sticker as $sticker ) {

                    if ( $sticker->property_number ==  $property_number[ 'property_number' ][ $i ] && $sticker->type != 'CONDEMNED' ) {

                        //if property number type is in, the in_count will increment.
                        if ( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == 'IN' ) {

                            ++$in_count;
    
                        }
    
                        //if property number type is out, the out_count will increment.
                        if ( $sticker->property_number == $property_number[ 'property_number' ][ $i ] && $sticker->type == 'OUT' ) {
    
                            ++$out_count;
    
                        }

                        //update the sticker type.
                        $sticker->type = 'CONDEMNED';
                        $sticker->save();

                    }

                }

                //add new transactions
                $itemTransaction = new ItemTransaction;
                $itemTransaction->property_number = $property_number[ 'property_number' ][ $i ];
                $itemTransaction->type = $request->type;
                $itemTransaction->quantity = 1;
                $itemTransaction->transaction_date = $request->transaction_date;
                $itemTransaction->remarks = $request->remarks;
                $itemTransaction->item_quantity_id = $itemQuantity->id;
                $itemTransaction->save();

            }

            //operations
            $out = $itemQuantity->out - $out_count;

            $condemned = count( $property_number[ 'property_number' ] ) + $itemQuantity->condemned;

            $balance = $itemQuantity->balance - $in_count;

            //update the current quantity
            $itemQuantity->in = $itemQuantity->in;
            $itemQuantity->out = $out;
            $itemQuantity->condemned = $condemned;
            $itemQuantity->balance = $balance;
            $itemQuantity->save();

            $selected_items = implode( ",", $property_number[ 'property_number' ] );

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = 'Property number/s ('. $selected_items .') in item '. $itemQuantity->item->item_name .' added to '. $request->type .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = true;

        } catch ( \Exception $e ) {
                
            DB::rollBack();

            $response = back()->withErrors( $e->getMessage() );
            
        }

        return $response; 

    }

    /**
     * 
     * Item in and out without sticker
     *
     */
    public function NoSticker(Request $request, $itemQuantity)
    {

        DB::beginTransaction();

        try {

            if ( $request -> type == 'IN' ) {

                //update quantity.
                $itemQuantity->in = $itemQuantity->in + $request->quantity;
                $itemQuantity->out = $itemQuantity->out;
                $itemQuantity->balance = $itemQuantity->balance + $request->quantity;
                $itemQuantity->save();

            } else {
                
                if ( $itemQuantity->balance >= $request->quantity ) {

                    //update quantity.
                    $itemQuantity->in = $itemQuantity->in;
                    $itemQuantity->out = $itemQuantity->out + $request->quantity;
                    $itemQuantity->balance = $itemQuantity->balance - $request->quantity; 
                    $itemQuantity->save();

                } elseif ( $itemQuantity->balance == 0 ) {
                    
                    return back() -> withErrors( 'Sorry, the remaining balance is zero!' );

                } else {

                    $this->validate( $request, [
                        'quantity' => [ 'lt:balance' ]
                    ]);

                }
                
            }

            //add new transactions
            $itemTransaction = new ItemTransaction;
            $itemTransaction->type = $request->type;
            $itemTransaction->quantity = $request->quantity;
            $itemTransaction->transaction_date = $request->transaction_date;
            $itemTransaction->remarks = $request->remarks;
            $itemTransaction->item_quantity_id = $itemQuantity->id;
            $itemTransaction->save();

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = ''.$request->quantity.' ('. $itemQuantity->item->unit .'/s) of item '. $itemQuantity->item->item_name .' added to '. $request->type .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = true;

        } catch ( \Exception $e ) {
                
            DB::rollBack();

            $response = back()->withErrors( $e->getMessage() );
            
        }

        return $response; 

    }

    /**
     * 
     * overview - modal
     *
     */
    public function search(Request $request, ItemQuantity $itemQuantity)
    {

        if ( $request->ajax() ) {

            $user = Auth::user();
         
            //normal user
            if ( $user->user_type == 0 ) { 

                //with sitcker
                if ( $itemQuantity->item->category->sticker == 1 ) {

                    if ( $request->status == 'IN' ) {

                        //get all the transactions of items with sticker including all transaction types 
                        $transactions = Sticker::where( 'item_id', $itemQuantity->item->id )
                        ->where( 'office_id', $user->office_id )
                        ->orderBy( 'id', 'ASC' )
                        ->get(); 

                    } else {

                        //get all the transactions of items with sticker depending on which transaction type has selected.
                        $transactions = Sticker::where( 'item_id', $itemQuantity->item->id )
                        ->where( 'office_id', $user->office_id )
                        ->where( 'type', $request->status )
                        ->orderBy( 'id', 'ASC' )
                        ->get();

                    } 
                    
                } else {
                //without sitcker

                    if ( $request -> status ) {

                        //get all the transactions of none sticker items depending on which transaction type has selected.
                        $transactions = ItemTransaction::where( 'item_quantity_id', $itemQuantity->id )
                        ->where( 'type', $request->status )
                        ->orderBy( 'id', 'ASC' )
                        ->get(); 

                    } 
                   
                }

            } else {
            //super user

                //with sitcker
                if( $itemQuantity->item->category->sticker == 1 ) {

                    if ( $request->status == 'IN' ) {

                        //get all the transactions of items with sticker including all transaction types of an office.
                        $transactions = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                        ->where('tbl_stickers.item_id', $itemQuantity->item->id)
                        ->where('tbl_offices.office_code', $itemQuantity->office->office_code)
                        ->orderBy('tbl_stickers.id', 'ASC')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_stickers.property_number', 
                            'tbl_stickers.created_at as created_dateTime', 
                            'tbl_stickers.updated_at as updated_dateTime', 
                            'tbl_stickers.type'
                        )
                        ->get(); 

                    } else {

                        //get all the transactions of items with sticker of an office depending on which transaction type has selected.
                        $transactions = Sticker::join('tbl_offices', 'tbl_stickers.office_id', '=', 'tbl_offices.id')
                        ->where('tbl_stickers.item_id', $itemQuantity->item->id)
                        ->where('tbl_offices.office_code', $itemQuantity->office->office_code)
                        ->where('type', $request->status )
                        ->orderBy('tbl_stickers.id', 'ASC')
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_stickers.property_number', 
                            'tbl_stickers.created_at as created_dateTime', 
                            'tbl_stickers.updated_at as updated_dateTime', 
                            'tbl_stickers.type'
                        )
                        ->get(); 

                    } 

                } else {
                //without sitcker

                    if ( $request->status ) {

                        //get all the transactions of items without sticker of an office depending on which transaction type has selected.
                        $transactions = ItemTransaction::join('tbl_item_quantities', 'tbl_item_transactions.item_quantity_id', '=', 'tbl_item_quantities.id')
                        ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                        ->where('tbl_item_transactions.item_quantity_id', $itemQuantity->id)
                        ->where('tbl_offices.office_code', $itemQuantity->office->office_code)
                        ->where('tbl_item_transactions.type', $request->status)
                        ->select(
                            'tbl_offices.office_code', 
                            'tbl_item_transactions.quantity', 
                            'tbl_item_transactions.transaction_date', 
                            'tbl_item_transactions.created_at as created_dateTime', 
                            'tbl_item_transactions.type'
                        )
                        ->get(); 

                    } 
                    
                }

            }

            return datatables( $transactions )->make( true );

        }

    }

}