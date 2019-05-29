<?php

namespace hippnp;
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

	public function showDashboard()
    {

		// $currentInstance = Session::get('currentInstance');
        $data = array() ;


        // $staff_absent = \Timeandattendance::select( $staff_table.'.surname',$staff_table.'.firstnames' , DB::raw('COUNT( CASE WHEN timeandattendance.attendance != "present" THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.attendance', '!=', "present")->where('timeandattendance.instance', '=', $currentInstance )->groupBy('timeandattendance.external_user_id')->get()->toArray();

        $data['test'] = Timeandattendance::table('picknpay')->get().count();




        $data['staff_week'] = json_encode(array(
            "client" => array(
               "build" => "1.0",
               "name" => "xxxxxx",
               "version" => "1.0"
            ),
            "protocolVersion" => 4,
            "data" => array(
               "distributorId" => "xxxx",
               "distributorPin" => "xxxx",
               "locale" => "en-US"
            )
       ));
        return \View::make('hippnp.showdashboard')->with('data', $data);

	}

}