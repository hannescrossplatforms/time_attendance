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

	public static function showDashboard()
    {

        $data = array() ;

        $data['test'] = \Picknpay::all();
        $data['customerInStoreToday'] = \Picknpay::customerInStoreToday();
        $data['customerInStoreThisMonth'] = \Picknpay::customerInStoreThisMonth();
        $data['report_period']      =   "Report for ".date('Y-m-d',strtotime('last monday'))." to ". date('Y-m-d');

        return \View::make('hippnp.showdashboard')->with('data', $data);

	}

}