<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Category;
use App\CategoryField;
use App\Field;
use App\FieldOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = [
            'parent' => 'Categories',
            'title' => 'Categories',
            'subtitle' => 'Control Panel'
        ];

        $categories = Category::all();

        $fields = Field::all();

        return view('reference_library.category.index', compact('categories', 'fields', 'page'));
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
        $this->validate( $request, [
            'name' => ['required', 'min:3','regex:/^[a-zA-Z-\s]*$/','unique:tbl_categories'],
            'sticker' => ['required','in:0,1']
        ]);

        $fields = Field::all();
        
        //check if fields count is not equal to 0
        if ( count( $fields ) != 0 ) {

            $CategoryField = $request->all();

            //check if the request CategoryField isset is false
            if ( !isset($CategoryField['field']) ) {

                return back()->withErrors('Please check at least one field.')
                ->with( 'name', $request->name )
                ->with( 'sticker', $request->sticker );

            } else {

                DB::beginTransaction();

                try {
                    //add new category
                    $category = new Category;
                    $category->name = $request->name;
                    $category->sticker = $request->sticker;
                    $category->save();

                    //loop the selected fields
                    for ($i = 0; $i < count( $CategoryField['field'] ); $i++) {

                        if ( empty( $CategoryField['field'][$i] ) ) continue;

                            //add new field of a category
                            $category_field = new CategoryField;
                            $category_field->category_id = $category->id;
                            $category_field->field_id = $CategoryField['field'][$i];
                            $category_field->save();

                    }

                    //save user action
                    $auditTrail = new AuditTrail;
                    $auditTrail->action = 'Added new category '. $request->name .'.';
                    $auditTrail->user_id = Auth::user()->id;
                    $auditTrail->save();

                    DB::commit();
               
                    $response = back()->with('success', 'New category name added successfully!');

                } catch ( \Exception $e ) {
                    
                    DB::rollBack();
                    
                    $response = back()->withErrors( $e->getMessage() );

                }
                
                return $response; 

            }

        } else {

            return back()->with('custom_error_message',"No existing fields, please create fields. <a href='/fields'>Click here</a>");

        }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $page = [
            'parent' => 'Categories',
            'title' => 'Category fields',
            'subtitle' => 'Database'
        ];

        return view('reference_library.category.show', compact('category', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $page = [
            'parent' => 'Categories',
            'title' => 'Category fields',
            'subtitle' => 'Database'
        ];
   
        //get the fields of selected category.
        $pluck_key = CategoryField::where( 'Category_id', $category->id )
        ->pluck( 'field_id' )
        ->all();
        //get the fields that the selected category doesn't have.
        $uncheck_items = Field::whereNotIn( 'id', $pluck_key )->get();
        
        return view('reference_library.category.edit', compact('category', 'uncheck_items', 'page'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate( $request, [
            'name' => ['required', 'min:3','regex:/^[a-zA-Z-\s]*$/'],
            'status' => ['required', 'in:0,1']
        ]);
        
        if ( count( $request->all() ) > 4 ) {

            DB::beginTransaction();

            try {

                //get all the existing category field id of the selected category.
                $category_fields = CategoryField::where( 'Category_id', $category->id )->pluck( 'id' );
              
                if( $category_fields ) {

                    for ($i=0; $i < count( $category_fields ); $i++) {
                        
                        //return all category status to 0 when the user was unchecked their existing checked fields of a category.
                        $category_field = CategoryField::findOrFail( $category_fields[ $i ] );
                        $category_field->status = 0;
                        $category_field->save();
                        
                    }

                }
                
                $fields = $request->all();

                //if uncheck_fields[] has checked.
                if ( $request->has( 'uncheck_fields' ) ) {

                    for ($i = 0; $i < count( $fields['uncheck_fields'] ); $i++)
                    {
                        
                        if ( empty( $fields[ 'uncheck_fields' ][ $i ] ) ) continue;

                            //add new field of a category.
                            $category_field = new CategoryField;
                            $category_field->category_id = $category->id;
                            $category_field->field_id = $fields['uncheck_fields'][$i];
                            $category_field->save();
                            
                    }

                }

                //if check_fields[] has checked.
                if ( $request->has( 'check_fields' ) ) {

                    for ($i = 0; $i < count( $fields[ 'check_fields' ] ); $i++)
                    {

                        if ( empty( $fields[ 'check_fields' ][ $i ] ) ) continue;
                           
                            //return all category status to 1 once the user was checked the fields of a category.
                            $category_field = CategoryField::findOrFail( $fields[ 'check_fields' ][ $i ] );
                            $category_field->status = 1;
                            $category_field->save();
                            
                    }

                }
                
                //used for action.
                if ( $request->status == 1 ) {

                    $status = 'Enabled';
        
                } else {
        
                    $status = 'Disabled';
        
                }

                //if input request and the data from db are the same.
                if ( $category->status == $request->status AND $category->name == $request->name ) {

                    $action = null;

                } elseif ( $category->status != $request->status AND $category->name == $request->name ) {
                //if input request status and status from db are not the same.

                    $action = $category->name .' status has been '. $status .'.';
                    
                } elseif ( $category->status == $request->status AND $category->name != $request->name ) {
                //if input request name and name from db are not the same.

                    $action = 'Category name '. $category->name .' has been changed to '. $request->name .'.';
        
                } else {
                //if inputs are all new.
        
                    $action = 'Category name '. $category->name .' has been changed to '.$request->name.' and status to '.$status.'.';
        
                }

                //execute if the field and action is null
                if ( $action != null ) {
                    
                    //it saves the action of the user.
                    $auditTrail = new AuditTrail;
                    $auditTrail->action = $action;
                    $auditTrail->user_id = Auth::user()->id;
                    $auditTrail->save();

                    //it saves the name and the status of a category.
                    $category->name = $request->name;
                    $category->status = $request->status;
                    $category->save();
                    
                } 

                $response = redirect( '/categories' )->with( 'success', 'Category status has been updated successfully!' );
               
                DB::commit();

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

        } else {

            return back()->withErrors( 'Select at least one field!' );
            
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

}
