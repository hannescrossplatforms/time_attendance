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

        $looper = 0;

        $data['total_dwell_time_chart_data'] = \Picknpay::hannesTestCategories();
        $data['total_dwell_time_chart_categories'] = $data['total_dwell_time_chart_data']->map(function($row) {
            return array('label' => $row['created_at']->toDateString());
        });

        $data['total_dwell_time_chart_results'] = array();
        $data['test_dates'] = array();
        foreach ($data['total_dwell_time_chart_categories'] as $category ) { //2 categories(dates)
            $looper = $looper + 1;

            $response = \Picknpay::hannesTestCategoriesInner($category['label']);
            $obj[] = [
                'seriesname' => $category,
                'data' => $response
            ];
            array_push($data['total_dwell_time_chart_results'],$obj);
            array_push($data['test_dates'],$looper);
            array_push($data['test_dates'],$category);

            // "categories": [{
            //     "category": [{"label":"2019-06-03"},{"label":"2019-06-04"},{"label":"2019-06-05"},{"label":"2019-06-06"},{"label":"2019-06-07"},{"label":"2019-06-08"},{"label":"2019-06-09"}]                                }],
            // "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"}]}]
            //"dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
        }

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