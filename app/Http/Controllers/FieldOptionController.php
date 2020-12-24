<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Field;
use App\FieldOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FieldOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, Field $field)
    {
        $this->validate( $request, [
            'option.*' => 'min:2|distinct|regex:/^[a-zA-Z0-9-\s -]*$/'
        ]);
        
        DB::beginTransaction();

        try {

            $option = $request->all();

            //loop the options input.
            for ( $i = 0; $i < count( $option['option'] ); $i++ ) {
    
                if( empty( $option['option'][$i] ) ) continue;

                    //add new option.
                    $fieldOption = new FieldOption;
                    $fieldOption->option = $option[ 'option' ][ $i ];
                    $fieldOption->field_id = $field->id;
                    $fieldOption->save();
    
            }

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = 'Newly created option/s (' . implode(  ", ", $option['option'] ) . ').';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();
    
            DB::commit();

            $response = back()->with( 'success', 'New option/s added successfully!' );
           
        } catch ( \Exception $e ) {
            
            DB::rollBack();

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
     * @param  \App\Inventory_model\FieldOption  $FieldOption
     * @return \Illuminate\Http\Response
     */
    public function show(FieldOption $FieldOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory_model\FieldOption  $FieldOption
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field, FieldOption $fieldOption)
    {
        $page = [
            'parent' => 'Fields',
            'title' => 'Update Option',
            'subtitle' => 'Database'
        ];

        return view( 'reference_library.field_option.edit', compact( 'field', 'fieldOption', 'page' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory_model\FieldOption  $FieldOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field, FieldOption $fieldOption)
    {
        //if input option and option from db are the same.
        if ( $fieldOption->option == $request->option ) {

            return redirect('fields/'.$field -> id.'');

        } else {

            $this->validate( $request, [
                'option' => ['required', 'min:2','regex:/^[a-zA-Z0-9-\s]*$/','unique:tbl_field_options'],
            ]);

            DB::beginTransaction();

            try {

                //save the user action.
                $auditTrail = new AuditTrail;
                $auditTrail->action = 'Update option name from '. $fieldOption->option .' to '. $request->option .'.';
                $auditTrail->user_id = Auth::user()->id;
                $auditTrail->save();

                //updates an option name.
                $fieldOption->option = $request->option;
                $fieldOption->save();

                DB::commit();

                $response = redirect( 'fields/'. $field->id )->with( 'success', 'Option name updated successfully!' );
            
            } catch ( \Exception $e ) {
                
                DB::rollBack();

                $response = back()->withErrors( $e->getMessage() );
        
            }
            
            return $response; 

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory_model\FieldOption  $FieldOption
     * @return \Illuminate\Http\Response
     */
    public function destroy(FieldOption $fieldOption)
    {
        //
    }
}
