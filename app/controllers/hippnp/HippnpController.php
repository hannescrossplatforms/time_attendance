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

        $data = array() ;

        $data['test'] = \Picknpay::all();
        $data['customerInStoreToday'] = \Picknpay::customerInStoreToday();


        return \View::make('hippnp.showdashboard')->with('data', $data);

	}

}