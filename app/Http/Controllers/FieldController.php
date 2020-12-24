<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Field;
use App\FieldOption;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Input\Input;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = [
            'parent' => 'Fields',
            'title' => 'Fields',
            'subtitle' => 'Control Panel'
        ];

        $fields = Field::all();

        return view( 'reference_library.field.index', compact( 'fields', 'page' ) );
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
            'name' => ['required', 'min:3', 'regex:/^[a-zA-Z-\s]*$/', 'unique:tbl_fields'],
            'type' => ['required', 'in:Text,Date,Number,Textarea,Option'],
        ]);

        DB::beginTransaction();

        try {
            
            //add new field into database.
            $field = new Field;
            $field->name = $request->name;
            $field->type = $request->type;
            $field->save();

            //check if the request type is Option.
            if( $request->type == 'Option' ) {
    
                $this->validate( $request, [
                    'option.*' => 'min:2|distinct|regex:/^[a-zA-Z0-9-\s -]*$/'
                ]);
                
                $option = $request->all();
                
                //loop the options input.
                for ($i = 0; $i < count( $option['option'] ); $i++) {
    
                    if( empty( $option[ 'option' ][ $i ] ) ) continue;
                    
                        //add new option.
                        $field_option = new FieldOption;
                        $field_option->option = $option[ 'option' ][ $i ];
                        $field_option->field_id = $field->id;
                        $field_option->save();

                }
    
                $action = 'Added new field '. $request->name .' with option/s ('. implode( ", ", $option['option']) .').';
    
            } else {
    
                $action = 'Added new field '. $request->name .'.';
    
            }
    
            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = $action;
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = back()->with( 'success', 'New field added successfully!' );
           
        } catch ( \Exception $e ) {
            
            DB::rollBack();

            $response = back()->withErrors( $e->getMessage() );
      
        }
        
        return $response; 

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        $page = [
            'parent' => 'Fields',
            'title' => 'Update field',
            'subtitle' => 'Database'
        ];

        return view( 'reference_library.field.show', compact( 'field', 'page' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        $page = [
            'parent' => 'Fields',
            'title' => 'Update field',
            'subtitle' => 'Database'
        ];

        return view( 'reference_library.field.edit', compact( 'field', 'page' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $this->validate( $request, [
            'name' => [ 'required', 'min:3', 'regex:/^[a-zA-Z-\s]*$/'],
            'status' => [ 'required', 'in:0,1' ]
        ]);

        if ( $request->status == 1 ) {

            $status = 'Enabled';

        } else {

            $status = 'Disabled';

        }
        //check if the field name and status versus from request were the same.
        if ( $field->status == $request->status && $field->name == $request->name ) {

            return redirect( 'fields/' );

        } elseif ( $field->status != $request->status && $field->name == $request->name ) {
        //check if the field status and request were not the same.

            $action = $field->name .' field status has been '. $status .'.';

        } elseif ( $field->status == $request->status && $field->name != $request->name ) {
        //check if the field name and request were not the same.

            $action = 'Field name '. $field->name .' has been changed to '. $request->name .'.';

        } else {
        //check if were not the same.

            $action = 'Field name '. $field->name .' has been changed to '. $request->name .' and status to '. $status .'.';

        }
        
        DB::beginTransaction();

        try {

            //update the field name and status.
            $field->name = $request->name;
            $field->status = $request->status;
            $field->save();

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = $action;
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();
        
            DB::commit();

            $response =  redirect( '/fields' )->with( 'success', 'Field has been updated successfully!' );
           
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
