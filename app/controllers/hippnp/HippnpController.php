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
        $finalChartObject = array();

        $period = Input::get('period');
        $allCategories = \Picknpay::fetchAllCategories($period);
        $dates = \Picknpay::datesToFetchChartDataFor($period)
        ->map(function($row) {
            //TODO: See if you can just use created_at
                return ['label' => $row['created_att']];
            });



        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArray = array();

            foreach ( $dates as $date ) {

                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryName);
                if (count($response) == 0) {
                    $false_array = array(['value' => '0']);
                    array_push($dataArray, $false_array);
                } else {
                    array_push($dataArray, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArray
            ];
            array_push($finalChartObject, $obj);
        }

        $data['category_list'] = $dates;

        if count($finalChartObject > 0) {
            $data['category_list_data'] = $finalChartObject[count($finalChartObject)- 1];
        }
        else {
            $data['category_list_data'] = $finalChartObject[0];
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