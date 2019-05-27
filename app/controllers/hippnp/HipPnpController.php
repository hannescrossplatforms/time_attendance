<?php

use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Session;

class HippnpController extends \BaseController {

	public function showDashboard($instance = null)
    {


        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";

        // if(\Hipauth::hasAllPermissions(array("TNA_CE", "TNA_IM"))) {
        //     Session::put('availableInstances', "BOTH");
        // } else if (\Hipauth::hasAnyPermissions(array("TNA_IM"))) {
        //     Session::put('availableInstances', "IM");
        //     $instance = "IM";
        // } else if (\Hipauth::hasAnyPermissions(array("TNA_CE"))) {
        //     Session::put('availableInstances', "CE");
        //     $instance = "CE";
        // } else if (\Hipauth::hasAnyPermissions(array("NR0001","NR0002"))) {
        //     Session::put('availableInstances', "NR_BOTH");
        // } else if (\Hipauth::hasAnyPermissions(array("NR0001"))) {
        //     Session::put('availableInstances', "NR01");
        //     $instance = "NR01";
        // } else if (\Hipauth::hasAnyPermissions(array("NR0002"))) {
        //     Session::put('availableInstances', "NR02");
        //     $instance = "NR02";
        // }
        // // Session::put('availableInstances', "NR01");
        // // $instance = "NR01";
        // // ANUSHA - CURRENTLY THE ABOVE WILL SET availableInstances to 'BOTH'
        // // TO TEST A SCENARIO WHERE THE USER ONLY HAS ON INSTANCE THEN COMMENT OUT THE NEXT 2 LINES
        // // Session::put('availableInstances', "IM");
        // // $instance = "IM";


        // if($instance) {

        //     Session::put('currentInstance', $instance);
        //     if($instance == "NR01" || $instance == "NR02" ) {
        //         return Redirect::action('hiptna\HiptnaController@showNrInstanceDashboard');
        //     } else {
		// 		//Here it comes
        //         return Redirect::action('hiptna\HiptnaController@showInstanceDashboard');
        //     }


        // } else {

            // Session::put('currentInstance', 'NONE');
            return \View::make('hiptna.showmaindashboard')->with('data', $data);

        // }
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
