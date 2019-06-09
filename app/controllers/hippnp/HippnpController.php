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
        $data['total_dwell_time_chart_categories'] = $data['total_dwell_time_chart_data']->map(function($row) {
            return array('label' => $row['created_at']->toDateString());
        });

        $data['total_dwell_time_chart_results'] = array();
        foreach ($data['total_dwell_time_chart_categories'] as $category ) {
            $response = \Picknpay::hannesTestCategoriesInner($category['label']);

            $obj[] = [
                'seriesname' => $category,
                'data' => $response->map(function($row) {
                    return 'value' => $row['value'];
                });
            ];

            // // {"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"} push this into the next line

            //   {created_at: "2019-06-02 10:32:00", category: "Games"}
            //   {created_at: "2019-06-05 10:32:00", category: "Food"}
            //   {created_at: "2019-06-05 10:32:00", category: "Games"}



        array_push($data['total_dwell_time_chart_results'],$obj);



             // "category": [{"label":"2019-05-27"},{"label":"2019-05-28"}]                                }],
    //     //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
    // }[{category: "Food", created_at: "2019-06-05 10:32:00", value: "100", test: "2019-06-05"}
    // {category: "Games", created_at: "2019-06-05 10:32:00", value: "300", test: "2019-06-05"}]





          }


 //   $obj[] = [
        //     'seriesname' => $category,
        //     'data' => 'test'
        // ];
        // // {"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"} push this into the next line

        //   {created_at: "2019-06-02 10:32:00", category: "Games"}
        //   {created_at: "2019-06-05 10:32:00", category: "Food"}
        //   {created_at: "2019-06-05 10:32:00", category: "Games"}


        // $data['chart_data']

        // $data['asdfasdfasdf'] = $data['testing'][0]['category'];

        // foreach ($data['testing'] as $value) {
        //     $data['asdf'] = \Picknpay::hannesTestCategoriesInner($value['category']);
        // }
        // select distinct categories

        //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]



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