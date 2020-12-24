<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Office;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page = [
            'parent' => 'Offices',
            'title' => 'Offices',
            'subtitle' => 'Control panel'
        ];
      
        $offices = Office::all();

        return view( 'office.index', compact( 'page', 'offices' ) );
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
            'office_name' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/', 'unique:tbl_offices'],
            'office_code' => ['required', 'regex:/^[a-zA-Z0-9.\/ \s]*$/', 'unique:tbl_offices'],
            'telephone' => ['required', 'numeric', 'unique:tbl_offices']
        ]);
        
        DB::beginTransaction();

        try {
            
            //add new office
            $office = new Office;
            $office->office_name = $request->office_name;
            $office->office_code = $request->office_code;
            $office->telephone = $request->telephone;
            $office->save();

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = 'Added new office '. $request->office_name .' with office code '. $request->office_code .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = back()->with('success','New office added successfully!');  

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
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        $page = [
            'parent' => 'Offices',
            'title' => 'Update office',
            'subtitle' => 'Database'
        ];

        return view('office.edit', compact('page', 'office'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Office $office)
    {
        $this->validate( $request, [
            'office_name' => ['required', 'string', 'max:255'],
            'office_code' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'numeric'],
        ]);

        if ( $office->office_name == $request->office_name && $office->office_code == $request->office_code 
        && $office->telephone == $request->telephone) {

            return redirect( '/offices' ); 

        } else {

            DB::beginTransaction();

            try {

                //update office details
                $office->office_name = $request->office_name;
                $office->office_code = $request->office_code;
                $office->telephone = $request->telephone;
                $office->save();

                //save the user action.
                $auditTrail = new AuditTrail;
                $auditTrail->action = 'Office '. $request->office_name .' with office '. $request->office_code .' has been updated.';
                $auditTrail->user_id = Auth::user()->id;
                $auditTrail->save();

                DB::commit();

                $response = redirect( '/offices' )->with( 'success','Office updated successfully!' ); 
        
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

            $offices = Office::all();

            return datatables( $offices )->make( true );

        } else {

            return redirect('/offices');

        }

    }
    
}
