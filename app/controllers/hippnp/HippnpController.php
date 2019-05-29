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

        $data['test'] = DB::table('picknpay')->get();




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