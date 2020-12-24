<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Category;
use App\CategoryField;
use App\Field;
use App\FieldOption;
use App\Item;
use App\ItemField;
use App\ItemQuantity;
use App\ItemTransaction;
use App\Office;
use App\Sticker;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page = [
            'parent' => 'Overview',
            'title' => 'Overview',
            'subtitle' => 'Control Panel'
        ];

        $user = Auth::user();
        
        $offices = Office::all();

        return view( 'inventory.overview.index', compact( 'user', 'page', 'offices' ) );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $page = [
            'parent' => 'Overview',
            'title' => 'Add Item',
            'subtitle' => 'Database'
        ];

        $user = Auth::user();

        //get all the category fields.
        $CategoryFields = CategoryField::where( 'category_id', $request->category_id )
        ->where( 'status', 1 )
        ->get();

        $counter = 0;

        //get all the registered fields of a category and show at item form.
        $allFields = array();
        foreach ( $CategoryFields as $CategoryField ) { 

            $allFields[] = array(
                // "id" => $CategoryField->field->id,
                "id" => $CategoryField->id,
                "name" => $CategoryField->field->name,
                "type" => $CategoryField->field->type,
            );

            //if field type is option
            if ( $CategoryField->field->type == 'Option' ) {

                $options = FieldOption::where('field_id', $CategoryField->field->id)->get();

                foreach ( $options as $option ) {

                    $allFields[ $counter ][ 'option' ][] = array(
                        "id" => $option->id,
                        "option" => $option->option,
                    );

                }

             }

             $counter++;

        }

        //get the office code
        $office = Office::find( $user->office_id )->office_code;

        //get the data of the selected category.
        $category = category::find( $request->category_id );
    
        //get all the items of the selected category.
        $items = Item::where( 'category_id', $request->category_id )->get();
        
        return view( 'inventory.overview.create', compact( 'category', 'items', 'page', 'office', 'allFields' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //used to check if the input data of a category is existing from the database and will return 404 if the input data is unknown.
        //And used to distinguish if the category has sticker or not.
        $category = Category::find( $request->category_id );

        //with sticker.
        if( $category->sticker ) {

            $this->validate( $request, [
                'item' => ['required'],
                'unit' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'date.*' => ['required', 'date', 'after:1/1/2000'],
                'number.*' => ['required', 'numeric', 'not_in:0'],
                'text.*' => ['required'],
                'textarea.*' => ['required'],
                'option.*' => ['required'],
                'office' => ['required', 'regex:/^[a-zA-Z0-9 -\/ \s]*$/'],
                'property_number' => ['required', 'min:5', 'unique:tbl_stickers', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'article' => ['required'],
                'brand_sn' => ['nullable', 'unique:tbl_stickers', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
                'remarks' => ['nullable', 'regex:/^[a-zA-Z0-9-.\/\s]*$/'],
                'date_count' => ['required', 'numeric', 'not_in:0'],
                'memo_receipt_employee' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/'],
            ] );

        } else {
        //without sticker.
    
            $this->validate( $request, [
                'item' => [ 'required' ],
                'unit' => [ 'required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/' ],
                'date.*' => [ 'required', 'date', 'after:1/1/2000' ],
                'number.*' => [ 'required', 'numeric', 'not_in:0' ],
                'text.*' => ['required'],
                'textarea.*' => ['required'],
                'option.*' => [ 'required' ]
            ] );
                
        }
            
        $office_id = Auth::user()->office_id;

        DB::beginTransaction();

        try {

            $item = Item::find( $request->item );

            //check if item is exist.
            if ( $item == null ) {
             
                $item_exist = Item::where( 'stock_keeping_unit', $request->item .' ('. $request->unit .')' )->value('id');
          
                if ( $item_exist == null ) {
    
                    $item = new Item;
                    $item->item_name = $request->item;
                    $item->stock_keeping_unit = $request->item.' ('. $request->unit .')';
                    $item->unit = $request->unit;
                    $item->category_id = $category->id;
                    $item->save();
    
                } else {
                    
                    $this->validate( $request, [
                        'item' => [ 'required', 'unique:tbl_items' ],
                    ]);
    
                }
              
            } 

            if( $category->sticker ) {

                //it returns an action and save to auditTrail
                $result = $this->ItemWithSticker( $request, $item, $office_id ); 

            } else {

                //it returns an action and save to auditTrail
                $result = $this->ItemWithOutSticker( $request, $item, $office_id ); 

            }

            $this->ItemFields( $request, $item, $category);

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = $result;
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = back()->with('success','New item added successfully!');

        } catch ( \Exception $e ) {
                
            DB::rollBack();
            //this error code is for duplicate entry
            if ( $e->getCode() == 23000 ) {

                $response = back()->withErrors( $e->errorInfo[2] );

            } else {

                $response = back()->withErrors( $e->getMessage() );

            }
            
        }

        return $response; 

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
    public function edit(Item $item)
    {
        $page = [
            'parent' => 'Overview',
            'title' => 'Update Item',
            'subtitle' => 'Database'
        ];

        return view( 'inventory.overview.edit', compact( 'item', 'page' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->validate( $request, [
            'item' => [ 'required' ], 
        ]);

        DB::beginTransaction();

        try {

             //if the category hasn't wheels.
             if ( $item->category->name != 'Vehicle' && $item->category->name != 'Bus' && $item->category->name != 'Motorcycle' ) {
    
                $arrayFields = array();
                
                //used to get the field Option type of a category.
                foreach ( $item->category->category_field as $field ) {
                    
                    //if the category has Option type then this block of code will be executed.
                    if ( $field->Field->type == 'Option' ) {

                        foreach ( $field->Field->option as $option) {
                            
                            //get all the value of the field type.
                            $itemFields = ItemField::where( 'value', $option->option )
                            ->where( 'item_id', $item->id )
                            ->get();
                            
                            //loop and the values and set as an array.
                            foreach ( $itemFields as $itemField ) {

                                $arrayFields[] = $itemField->value;

                            }

                        }

                    }

                }
                
                //an array as string value.
                $arrayFields = implode( ",", $arrayFields );
            
                if ( empty( $arrayFields ) ) {

                    //it updates the item unit only if the $item_fields was empty.
                    $item->item_name = $request->item;
                    $item->stock_keeping_unit = $request->item . ' ('. $item->unit .')';
                    $item->save();
                    
                } else {

                    //it updates the item unit only if the $item_fields was not empty.
                    $item->item_name = $request->item;
                    $item->stock_keeping_unit = $request->item . ' ('. $item->unit .', '. $arrayFields .')';
                    $item->save();
                    
                }

            } else {
            //if the category has wheels.

                //updates the item unit only.
                $item->item_name = $request->item;
                $item->stock_keeping_unit = $request->item . ' ('. $item->unit .')';
                $item->save();

            }

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = $action = 'Item name '. $item->item_name .' has been changed to '. $request->item .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = redirect( '/inventories' )->with('success','Item name has been updated successfully!');

        } catch ( \Exception $e ) {
                
            DB::rollBack();

            $response = back()->withErrors( $e->getMessage() );
            
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

    /**
     * Select the available categories
     *
     * @return \Illuminate\Http\Response
     */
    public function select_category()
    {
        $page = [
            'parent' => 'Overview',
            'title' => 'Select Category',
            'subtitle' => 'Database'
        ];

        $categories = Category::where( 'status', 1 )->get();
        
        return view( 'inventory.overview.select_category', compact( 'categories', 'page' ) );
    }
    
     /**
     *
     * check if the item sku is already exist in the database and if it has, 
     * it will execute this block of codes that will be appeared in item form.
     * 
     */
    public function checkifexist(Request $request, $item, Category $category)
    {
        if ( $request->ajax() ) {

            //if the category hasn't wheels.
            if ($category->name != 'Vehicle' && $category->name != 'Bus' && $category->name != 'Motorcycle') {

                //get the data of an item.
                $item = Item::find( $item );

                //if an item is null.
                if ( $item != null ) {

                    $li = '<script>';

                        //get the field names and value and used as ID and value of document.getElement.
                        foreach ( $item->item_field as $field_value ) {

                            $fieldName = $field_value->category_field->field->name;
                            $li .= 'document.getElementById("'. $fieldName .'").value = "'. $field_value->value .'";';
                            $li .= 'document.getElementById("'. $fieldName .'").setAttribute("readOnly","readOnly");';

                        }

                        $li .= 'document.getElementById("unit").value = "'. $item->unit .'";';
                        $li .= 'document.getElementById("unit").setAttribute("readOnly","readOnly");';

                        //pluck all id.
                        $itemField = ItemField::where( 'item_id', $item->id )
                        ->pluck( 'category_field_id' )
                        ->all();
                                
                        //get all the category_field that the itemfield doesn't have.
                        $categoryFields = CategoryField::whereNotIn( 'id', $itemField )
                        ->where( 'category_id', $category->id )
                        ->get();

                        foreach ( $categoryFields as $categoryField ) {
            
                            $fieldName = $categoryField->field->name;
                            $li .= 'document.getElementById("'. $fieldName .'").selectedIndex = "0";';
                            $li .= 'document.getElementById("'. $fieldName .'").value = "";';
                            $li .= 'document.getElementById("'. $fieldName .'").removeAttribute("readOnly");';
    
                        }
                            
                    $li .= '</script>';

                    return response()->json([ 'data' => $li ]); 

                } else {
                   
                    $categoryFields = CategoryField::where( 'category_id', $category->id )->get();
                    
                    $li = '<script>';

                        //clear all fields if an input item is not exist.
                        foreach ( $categoryFields as $categoryField ) {

                            $fieldName = $categoryField->field->name;
                            $li .= 'document.getElementById("'.$fieldName .'").selectedIndex = "0";';
                            $li .= 'document.getElementById("'.$fieldName .'").value = "";';
                            $li .= 'document.getElementById("'.$fieldName .'").removeAttribute("readOnly");';

                        }

                        $li .= 'document.getElementById("unit").value = "";';
                        $li .= 'document.getElementById("unit").removeAttribute("readOnly");';

                    $li .= '</script>';

                    return response()->json([ 'data' => $li ]);

                }

            }
       }

    }

    /**
     * for items that with sticker
     *
     */
    public function ItemWithSticker(Request $request, $item, $office_id) 
    {
        //get the id of item_quantity
        $item_quantity_id = ItemQuantity::where( 'item_id', $request->item )
        ->Where( 'office_id' , $office_id ) 
        ->orderBy( 'id', 'DESC' )
        ->value('id');
        
        //if the item quantity is not null
        if ( $item_quantity_id != null ) {

            //update the current value of item_quantity
            $item_quantity = ItemQuantity::find( $item_quantity_id );  
            $item_quantity->in = 1 + $item_quantity->in;
            $item_quantity->out = $item_quantity->out;
            $item_quantity->condemned = $item_quantity->condemned;
            $item_quantity->balance = 1 + $item_quantity->balance;
            $item_quantity->save();

        } else {
        //if the item quantity is null

            //insert new value of item_quantity
            $item_quantity = new ItemQuantity;
            $item_quantity->in = 1;
            $item_quantity->out = 0;
            $item_quantity->condemned = 0;
            $item_quantity->balance = 1;
            $item_quantity->item_id = $item->id;
            $item_quantity->office_id = $office_id;
            $item_quantity->save();
          
        }
        
        //insert new sticker of item.
        $sticker = new Sticker;
        $sticker->office = $request-> office;
        $sticker->property_number = $request->property_number;
        $sticker->article = $request->article;
        $sticker->brand_sn = $request->brand_sn;
        $sticker->remarks = $request->remarks;
        $sticker->date_count = $request->date_count;
        $sticker->memo_receipt_employee = $request->memo_receipt_employee;
        $sticker->type = 'IN';
        $sticker->item_id = $item->id;
        $sticker->office_id = $office_id;
        $sticker->save();

        $action = 'Added new item '. $item->item_name .' with property number '. $request->property_number .'.';

        //insert new transaction of an item by default of IN type.
        $transactions = new ItemTransaction;
        $transactions->property_number = $request->property_number;
        $transactions->type = 'IN';
        $transactions->quantity = 1;
        $transactions->transaction_date = now();
        $transactions->item_quantity_id = $item_quantity->id;
        $transactions->save();

        return $action;

    }

     /**
     * 
     * for the items without sticker
     *
     */
    public function ItemWithOutSticker(Request $request, $item, $office_id) 
    {
        $this->validate( $request, [
            'in' => [ 'required', 'numeric', 'regex:/^[1-9][0-9]*$/', 'not_in:0' ],
        ]);

        //get the id of item_quantity
        $item_quantity_id = ItemQuantity::where( 'item_id', $request->item )
        ->Where( 'office_id' , $office_id ) 
        ->orderBy( 'id', 'DESC' )
        ->value('id');

        //if the item quantity is not null
        if ( $item_quantity_id != null ) {

            //update the current value of item_quantity
            $item_quantity = ItemQuantity::find( $item_quantity_id );  
            $item_quantity->in = $request->in + $item_quantity->in;
            $item_quantity->out = $item_quantity->out;
            $item_quantity->condemned = $item_quantity->condemned;
            $item_quantity->balance = $request->in + $item_quantity->balance;
            $item_quantity->save();

            $action = 'Added additional quantity of '. $item->item_name .' with the total count of '. $request->in .'';

        } else {
        //if the item quantity is null

            //insert new value of item_quantity
            $item_quantity = new ItemQuantity;
            $item_quantity->in = $request->in;
            $item_quantity->out = 0;
            $item_quantity->condemned = 0;
            $item_quantity->balance = $request->in;
            $item_quantity->item_id = $item->id;
            $item_quantity->office_id = $office_id;
            $item_quantity->save();

            $action = 'Added new item '. $item->item_name .' with the total count of '. $request->in .'.';

        }
        
        //insert new transaction of an item by default of IN type.
        $transactions = new ItemTransaction;
        $transactions->type = 'IN';
        $transactions->quantity = $request->in;
        $transactions->transaction_date = now();
        $transactions->item_quantity_id = $item_quantity->id;
        $transactions->save();

        return $action;

    }

    /**
     *
     *  Store a newly created fields in storage.
     *
     */
    public function ItemFields(Request $request, $item, $category) 
    {
        //array of category fields.
        $fields = array( 
            array ( 
                'field' => 'date',
                'field_id' => 'date_id',
            ),
            array ( 
                'field' => 'number',
                'field_id' => 'number_id',
            ),
            array ( 
                'field' => 'text',
                'field_id' => 'text_id',
            ),
            array ( 
                'field' => 'textarea',
                'field_id' => 'textarea_id',
            ),
            array ( 
                'field' => 'option',
                'field_id' => 'option_id',
            ),
        );

        
        $input_fields = $request->all();
        //this is the for loop of category fields that user needs to fill out.
        for ( $i = 0; $i<count( $fields ); $i++ ) {

            if( !empty( $input_fields [ $fields[ $i ] [ 'field' ] ] ) ) {

                $count = 0;
                //looping of fields that has input
                for ( $x = 0; $x<count( $input_fields[ $fields[ $i ][ 'field' ] ] ); $x++ ) {

                    //used to check if the itemfields of an item are already exist in the database.
                    $item_fields = ItemField::where( 'item_id', $item->id )
                    ->Where( 'category_field_id', $input_fields[ $fields[ $i ][ 'field_id'] ][$x] ) 
                    ->first();
                    
                    //if item field is null
                    if ( $item_fields == null ) {

                        //add new item field value
                        $item_field = new ItemField;
                        $item_field->value = $input_fields[ $fields[ $i ][ 'field' ] ][$x];
                        $item_field->category_field_id =  $input_fields[ $fields[ $i ][ 'field_id'] ][$x];
                        $item_field->item_id = $item->id;
                        $item_field->save();

                    }

                    $count++;
                    
                }

                //if the field that has input is option.
                if ( $fields[ $i ] [ 'field' ] == 'option' ) {

                    //if the category hasn't wheels.
                    if ( $category->name != 'Vehicle' && $category->name != 'Bus' && $category->name != 'Motorcycle' ) {

                        //update the stock keeping unit
                        $list = $input_fields[ $fields[$i]['field']  ];
                        $item->item_name =  $item->item_name;
                        $item->stock_keeping_unit =  $item->item_name. ' ('. $item->unit .', '. implode( ", ", $list ) . ')';
                        $item->id = $item->id;
                        $item->save();

                    } else {
                    //if the category has wheels.
                    
                        //update the stock keeping unit
                        $item->item_name =  $item->item_name;
                        $item->stock_keeping_unit =  $item->item_name. ' ('. $item->unit .')';
                        $item->id = $item->id;
                        $item->save();

                    }

                }

            }

        }
      
    }

     /**
     *
     *  get all the items using dataTable ajax
     *
     */
    public function search(Request $request)
    {
        
        if ( $request->ajax() ) {

            $user = Auth::user();

            //normal user.
            if ( $user->user_type == 0 ) {

                //get all the items of an office using dataTables with ajax.
                $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                ->select(
                    'tbl_offices.office_code', 
                    'tbl_items.id', 
                    'tbl_items.stock_keeping_unit', 
                    'tbl_categories.name', 
                    'tbl_item_quantities.id as quantity_id', 
                    'tbl_item_quantities.in', 
                    'tbl_item_quantities.out', 
                    'tbl_item_quantities.condemned', 
                    'tbl_item_quantities.balance', 
                    'tbl_categories.sticker'
                )
                ->where('tbl_offices.id', $user->office_id)
                ->get();

            } else {
            //Super user

                if ( $request->office == null ) {

                    //get all the items of all offices using dataTables 
                    $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_offices.id as office', 
                        'tbl_items.id', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_categories.name', 
                        'tbl_item_quantities.id as quantity_id', 
                        'tbl_item_quantities.in', 
                        'tbl_item_quantities.out', 
                        'tbl_item_quantities.condemned',
                        'tbl_item_quantities.balance', 
                        'tbl_categories.sticker'
                    )
                    ->get();

                } else {

                    //get all the items of a selected office using dataTables ajax 
                    $items = Item::join('tbl_item_quantities', 'tbl_items.id', '=', 'tbl_item_quantities.item_id')
                    ->join('tbl_offices', 'tbl_item_quantities.office_id', '=', 'tbl_offices.id')
                    ->join('tbl_categories', 'tbl_items.category_id', '=', 'tbl_categories.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_items.id', 
                        'tbl_items.stock_keeping_unit', 
                        'tbl_categories.name', 
                        'tbl_item_quantities.id as quantity_id', 
                        'tbl_item_quantities.in', 
                        'tbl_item_quantities.out', 
                        'tbl_item_quantities.condemned', 
                        'tbl_item_quantities.balance', 
                        'tbl_categories.sticker'
                    )
                    ->where('tbl_offices.id',  $request->office)
                    ->get();

                }
            
            }

            return datatables( $items )->make( true );

        }

    }

}