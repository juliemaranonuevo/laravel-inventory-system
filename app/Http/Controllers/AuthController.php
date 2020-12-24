<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.index');
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
            'email' => 'required',
            'password' => 'required'
        ]);
        
        if( !Auth::attempt( request(['email', 'password']) ) ) {

            return back()->withErrors('Invalid email or password')->withInput( $request->all );

        }

        //save the user action.
        $auditTrail = new AuditTrail;
        $auditTrail->action = 'Logged In.';
        $auditTrail->user_id = Auth::user()->id;
        $auditTrail->save();

        return redirect('/');
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
        $this->validate( $request, [
            'password' => 'required'
        ]);
        
        $email = Auth::user()->email;

        if( !Auth::attempt( ['email' => $email, 'password' => request('password')]  ) ) {

            return back()->withErrors('Invalid email or password');

        }

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //save the user action.
        $auditTrail = new AuditTrail;
        $auditTrail->action = 'Logged Out.';
        $auditTrail->user_id = Auth::user()->id;
        $auditTrail->save();

        Auth::logout();

        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword()
    {
        $page = [
            'parent' => 'Change Password',
            'title' => 'Change Password',
            'subtitle' => 'User Maintenance'
        ];

        return view('auth.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate( $request, [
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'retype_password' => 'required|min:6|same:new_password'
        ]);

        $user = Auth::user();

        if( Hash::check( $request->input('current_password'), $user->password ) ) {

            if( !Hash::check( $request->input('new_password'), $user->password ) ) {

                DB::beginTransaction();

                try { 
                    
                    //update password
                    $user->password = Hash::make( $request->new_password );
                    $user->save();

                    //save user action.
                    $auditTrail = new AuditTrail;
                    $auditTrail->action = 'User password has been updated.';
                    $auditTrail->user_id = Auth::user()->id;
                    $auditTrail->save();

                    DB::commit();

                    $response = redirect('/users/change-password')->with( 'success', "Your password has been updated successfully." );
                   
                } catch ( \Exception $e ) {
                    
                    DB::rollBack();
        
                    $response = back()->withErrors( $e->getMessage() );
              
                }
                
                return $response; 

            } else {

                return back()->withErrors("Current and New Passwords are the same!");
                
            }

        } else {

            return back()->withErrors("Invalid current password!");
            
        }
    }
}
