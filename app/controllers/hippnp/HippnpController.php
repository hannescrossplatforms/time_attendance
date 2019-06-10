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

        $data = array();
        $period = Input::get('period');

        $data['category'] = \Picknpay::chartCategoriesAsJson($period, true);
        $data['staff_graph'] = \Picknpay::getChartTotalDwellTimeData($period);



        $data['total_dwell_time_chart_data'] = \Picknpay::hannesTestCategories();

        $data['first_level_data'] = \Picknpay::firstLevelData()->map(function($row) {
            return array('label' => $row['created_att'])
        });

// "categories": [{
        //     //     "category": [{"label":"2019-06-03"},{"label":"2019-06-04"},{"label":"2019-06-05"},{"label":"2019-06-06"},{"label":"2019-06-07"},{"label":"2019-06-08"},{"label":"2019-06-09"}]                                }],
        //     // "dataset": [{"seriesname":"2019-06-03","data":[{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"}]},{"seriesname":"2019-06-03","data":[{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"}]}]


        // $parentObjects = \Picknpay::hannesTestCategories();

//
        // $dates = \Picknpay::getDatess();
        // $dataArray = array();
        // $dataArray['asdf'] = array();



        // $data['test'] = $dates;
        // $data['test2'] = $dates->map(function($row) {
        //     array_push($data['asdf'],\Picknpay::getDataForDate($row['created_att']));
        // });






        $data['total_dwell_time_chart_categories'] = $data['total_dwell_time_chart_data']->map(function($row) {
            return array('category' => $row['created_at']);
        });

        $data['total_dwell_time_chart_results'] = array();

        // foreach ($data['total_dwell_time_chart_data'] as $row ) { //2 categories(dates)
        //     $looper = $looper + 1;


        //     $response = \Picknpay::hannesTestCategoriesInner($row);

        //     $data['meh'] = $response;
        //     $obj[] = [
        //         'seriesname' => $row['created_att'],
        //         'data' => $response
        //     ];
        //     array_push($data['total_dwell_time_chart_results'],$obj);
        //     // array_push($data['test_dates'],$looper);
        //     // array_push($data['test_dates'],$category);

        //     // "categories": [{
        //     //     "category": [{"label":"2019-06-03"},{"label":"2019-06-04"},{"label":"2019-06-05"},{"label":"2019-06-06"},{"label":"2019-06-07"},{"label":"2019-06-08"},{"label":"2019-06-09"}]                                }],
        //     // "dataset": [{"seriesname":"2019-06-03","data":[{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"}]},{"seriesname":"2019-06-03","data":[{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"}]}]


        // }

        $data['category_avg'] = \Picknpay::chartCategoriesAsJson($period, true);
        $data['staff_graph_avg'] = \Picknpay::getChartAverageDwellTimeData($period);
        $json = json_encode($data);
        print_r($json);

    }

}


// dataSource: {
//     "chart": chartProperties,
//     "categories": [{
//         "category": [{"label":"2019-06-03"},{"label":"2019-06-04"}]                                }],
//     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
// }