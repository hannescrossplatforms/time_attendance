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
        $data['category'] = \Picknpay::chartCategoriesAsJson();
        $data['staff_graph'] = \Picknpay::getChartDwellTimeData();

        $data['report_period'] = 'asdf';

        // $staff_graph = array($staff_present, $staff_absent);
        // $data['staff_graph'] = $staff_graph;

        return \View::make('hippnp.showdashboard')->with('data', $data);

	}

}