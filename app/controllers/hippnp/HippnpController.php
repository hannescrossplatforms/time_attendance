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
        $data['category'] = \Picknpay::chartCategoriesAsJson('rep7day', false);
        $data['staff_graph'] = \Picknpay::getChartTotalDwellTimeData('rep7day');
        $data['category_avg'] = \Picknpay::chartCategoriesAsJson('rep7day', false);
        $data['staff_graph_avg'] = \Picknpay::getChartAverageDwellTimeData('rep7day');

        $data['report_period'] = 'rep7day';

        return \View::make('hippnp.showdashboard')->with('data', $data);

    }

    public function periodchartJsondata(){

        $data = array() ;
        $period = Input::get('period');

        $data['category'] = \Picknpay::chartCategoriesAsJson($period, true);
        $data['staff_graph'] = \Picknpay::getChartTotalDwellTimeData($period);
        $data['category_avg'] = \Picknpay::chartCategoriesAsJson('rep7day', true);
        $data['staff_graph_avg'] = \Picknpay::getChartAverageDwellTimeData($period);
        $json = json_encode($data);
        print_r($json);

    }

}



// Last month
// endDate: "2019-05-31 23:59:59"
// period: "month"
// startDate: "2019-05-01 00.00.00"