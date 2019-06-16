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
        $allCategories = \Picknpay::fetchAllCategories($period);
        $dates = \Picknpay::datesToFetchChartDataFor($period)

        $data['category_list'] = $dates;

        ->map(function($row) {
                return ['label' => $row['created_att']];
            });

        // Sum of all categories

        $finalChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArray = array();

            foreach ( $dates as $date ) {
                // avg
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryName, "SUM");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArray, $empty_array);
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

        if (count($finalChartObject) > 0) {
            $data['category_list_data'] = $finalChartObject[count($finalChartObject)- 1];
        }
        else {
            $data['category_list_data'] = null;
        }

        // Average of all categories

        $finalAverageChartObject = array(); = array();

        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArray = array();

            foreach ( $dates as $date ) {
                // avg
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryName, "AVG");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArray, $empty_array);
                } else {
                    array_push($dataArray, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArray
            ];
            array_push($finalAverageChartObject, $obj);
        }

        if (count($finalAverageChartObject) > 0) {
            $data['category_list_data_average'] = $finalAverageChartObject[count($finalAverageChartObject)- 1];
        }
        else {
            $data['category_list_data_average'] = null;
        }

        $json = json_encode($data);

        print_r($json);

    }

}