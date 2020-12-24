<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Office;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = [
            'parent' => 'Users',
            'title' => 'Users',
            'subtitle' => 'Database'
        ];

        $offices = Office::all();

        return view('users.index', compact('page', 'offices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = [
            'parent' => 'Users',
            'title' => 'Create user',
            'subtitle' => 'Database'
        ];

        $offices = Office::all();

        return view('users.create', compact('page', 'offices'));
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
            'employee_number' => ['required', 'string', 'max:255', 'unique:tbl_users'],
            'name' => ['required', 'string', 'max:255', 'unique:tbl_users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_users'],
            'office' => ['required'],
        ]);
        
        DB::beginTransaction();

        try {

            //add new user
            $user = new User;
            $user->employee_number = $request->employee_number;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt('000000');
            $user->office_id = $request->office;
            $user->user_type = 0;
            $user->save();

            //save the user action.
            $auditTrail = new AuditTrail;
            $auditTrail->action = 'Added new user '. $user->name .' with employee no. '. $user->employee_number .'.';
            $auditTrail->user_id = Auth::user()->id;
            $auditTrail->save();

            DB::commit();

            $response = redirect('/users')->with('success','New User added successfully!'); 

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $page = [
            'parent' => 'Users',
            'title' => 'Update user',
            'subtitle' => 'Database'
        ];

        $offices = Office::all();
        
        return view('users.edit', compact('page', 'offices', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate( $request, [
            'employee_number' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'office' => ['required'],
        ]);

        //if nothing change
        if ( $user->employee_number == $request->employee_number && $user->name == $request->name 
            && $user->email == $request->email && $user->office_id == $request->office ) {

            return back(); 

        } else {

            DB::beginTransaction();

            try {
                //update the user information
                $user->employee_number = $request->employee_number;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->office_id = $request->office;
                $user->password = bcrypt('000000');
                $user->save();

                //save the user action.
                $auditTrail = new AuditTrail;
                $auditTrail->action = 'User '.$request->name.' with employee  '.$request->employee_number.' has been updated.';
                $auditTrail->user_id = Auth::user()->id;
                $auditTrail->save();

                DB::commit();

                $response = redirect('/users')->with('success','User updated successfully!');  

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

            if ( $request->office == null ) {

                //get all the users from the database of all offices using dataTables with ajax.
                $users = User::join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                ->select(
                    'tbl_users.id', 
                    'tbl_users.email', 
                    'tbl_users.employee_number', 
                    'tbl_users.name', 
                    'tbl_users.created_at as created_dateTime', 
                    'tbl_users.updated_at as updated_dateTime',  
                    'tbl_offices.office_code'
                )
                ->get();

            } else {

                //get all the users from the database of selected office using dataTables with ajax.
                $users = User::join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                ->select(
                    'tbl_users.id', 
                    'tbl_users.email', 
                    'tbl_users.employee_number', 
                    'tbl_users.name', 
                    'tbl_users.created_at as created_dateTime', 
                    'tbl_users.updated_at as updated_dateTime',  
                    'tbl_offices.office_code'
                )
                ->where('tbl_users.office_id','=', $request->office)
                ->get();

            }

            return datatables( $users )->make( true );

        } else {

            return redirect('/users');

        }

    }
}
