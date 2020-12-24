<?php

namespace App\Http\Controllers;

use App\AuditTrail;
use App\Office;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditTrailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = [
            'parent' => 'Audit trails',
            'title' => 'Audit trails',
            'subtitle' => 'Database'
        ];

        $user = Auth::user();

        $offices = Office::all();

        return view('audit_trails.index', compact('page', 'user', 'offices'));
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
     * @param  \App\Model\AuditTrail  $AuditTrail
     * @return \Illuminate\Http\Response
     */
    public function show(AuditTrail $auditTrail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\AuditTrail  $AuditTrail
     * @return \Illuminate\Http\Response
     */
    public function edit(AuditTrail $auditTrail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\AuditTrail  $AuditTrail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuditTrail $auditTrail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\AuditTrail  $AuditTrail
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditTrail $auditTrail)
    {
        //
    }

    public function search(Request $request)
    {

        if ( $request->ajax() ) {

            //get the office_id and user_type of user that logged in.
            $user = Auth::user();

            //noraml user.
            if ( $user->user_type == 0 ) {

                //get all the office audit logs of the user that logged in using dataTable ajax.
                $auditTrails = AuditTrail::join('tbl_users', 'tbl_audit_trails.user_id', '=', 'tbl_users.id')
                ->join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                ->select(
                    'tbl_audit_trails.action', 
                    'tbl_audit_trails.updated_at', 
                    'tbl_users.email'
                )
                ->whereBetween('tbl_audit_trails.created_at', [ $request->startDate, $request->endDate ])
                ->where('tbl_offices.id', $user->office_id)
                ->get();

            } else {
            //super user.

                if ( $request->office == null ) {

                    //get all the audit logs from all offices using dataTable ajax.
                    $auditTrails = AuditTrail::join('tbl_users', 'tbl_audit_trails.user_id', '=', 'tbl_users.id')
                    ->join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_audit_trails.action', 
                        'tbl_audit_trails.created_at as created_dateTime', 
                        'tbl_users.email'
                    )
                    ->whereBetween('tbl_audit_trails.created_at', [ $request->startDate, $request->endDate ])
                    ->get();

                } else {

                    //get all the audit logs of the selected office using dataTable ajax.
                    $auditTrails = AuditTrail::join('tbl_users', 'tbl_audit_trails.user_id', '=', 'tbl_users.id')
                    ->join('tbl_offices', 'tbl_users.office_id', '=', 'tbl_offices.id')
                    ->select(
                        'tbl_offices.office_code', 
                        'tbl_audit_trails.action', 
                        'tbl_audit_trails.created_at as created_dateTime', 
                        'tbl_users.email'
                    )
                    ->where('tbl_offices.id', $request->office)
                    ->whereBetween('tbl_audit_trails.created_at', [ $request->startDate, $request->endDate ])
                    ->get();

                }

            }

            return datatables( $auditTrails )->make( true );

        } else {

            return redirect("/audit-trails");

        }
    }
}