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

	public static function showDashboard(){

        $data = array() ;

        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['customer_in_store_today'] = \Picknpay::customerInStoreToday();
        $data['customer_in_store_this_month'] = \Picknpay::customerInStoreThisMonth();

        // $test = array('name'=>'Others');

        // $prevDate = '';
        // $results = \Picknpay::chartCategoriesAsJson('rep7day');

        // foreach ($results as $result) {

        //     if ($prevDate != $result['label']) {
        //         array_push($test, $result);
        //     }
        //     $prevDate = $result['label'];


        // }

        // $data['category'] = $test;
        $data['category'] = \Picknpay::chartCategoriesAsJson('rep7day');
        $data['staff_graph'] = \Picknpay::getChartDwellTimeData('rep7day');

        $data['report_period'] = 'rep7day';

        return \View::make('hippnp.showdashboard')->with('data', $data);

    }

    public function periodchartJsondata(){

        $data = array() ;
        $period = Input::get('period');

        $data['category'] = \Picknpay::chartCategoriesAsJson($period);
        $data['staff_graph'] = \Picknpay::getChartDwellTimeData($period);

        $json = json_encode($data);

        print_r($json);

    }

}