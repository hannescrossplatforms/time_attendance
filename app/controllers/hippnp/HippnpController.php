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

        $period = 'rep7day';
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['report_period'] = 'rep7day';
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['customer_in_store_today'] = \Picknpay::customerInStoreToday();
        $data['customer_in_store_this_month'] = \Picknpay::customerInStoreThisMonth();

        //Get all categories for charts to render

        $allCategories = \Picknpay::fetchAllCategories($period, null, null);
        $dates = \Picknpay::datesToFetchChartDataFor($period, null, null)
        ->map(function($row) {
                return ['label' => $row['created_att']];
            });

        $data['category_list'] = $dates;

        // Sum of all categories

        $finalChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArray = array();

            foreach ( $dates as $date ) {
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
        };

        if (count($finalChartObject) > 0) {
            $data['category_list_data'] = json_encode($finalChartObject[count($finalChartObject)- 1]);
        }
        else {
            $data['category_list_data'] = json_encode([]);
        }
        $obj = null;

        // Average of all categories

        $finalAverageChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArrayAvg = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryName, "AVG");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayAvg, $empty_array);
                } else {
                    array_push($dataArrayAvg, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArrayAvg
            ];
            array_push($finalAverageChartObject, $obj);
        };

        if (count($finalAverageChartObject) > 0) {
            $data['category_list_data_average'] = json_encode($finalAverageChartObject[count($finalAverageChartObject)- 1]);
        }
        else {
            $data['category_list_data_average'] = json_encode([]);
        }
        $obj = null;

        return \View::make('hippnp.showdashboard')->with('data', $data);

    }

    public function periodchartJsondata(){

        $data = array();

        $period = Input::get('period');
        $start = Input::get('start');
        $end = Input::get('end');

        if ($start != null && $end != null){
            $allCategories = \Picknpay::fetchAllCategories($period, $start, $end);
            $dates = \Picknpay::datesToFetchChartDataFor($period, $start, $end)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
                });
        }
        else {
            $allCategories = \Picknpay::fetchAllCategories($period, $start, $start);
            $dates = \Picknpay::datesToFetchChartDataFor($period, $start, $start)
            ->map(function($row) {
                    return ['label' => $row['created_att']];
                });
        }




        $data['category_list'] = $dates;

        // Sum of all categories

        $finalChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArray = array();

            foreach ( $dates as $date ) {
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
        };

        if (count($finalChartObject) > 0) {
            $data['category_list_data'] = $finalChartObject[count($finalChartObject)- 1];
        }
        else {
            $data['category_list_data'] = [];
        }

        $obj = null;

        // Average of all categories

        $finalAverageChartObject = array();

        foreach ($allCategories as $category) {
            $categoryName = $category->category;
            $dataArrayAvg = array();

            foreach ( $dates as $date ) {
                $response = \Picknpay::fetchDwellTimeDataForCategoryWithDate($date['label'], $categoryName, "AVG");
                if (count($response) == 0) {
                    $empty_array = array(['value' => '0']);
                    array_push($dataArrayAvg, $empty_array);
                } else {
                    array_push($dataArrayAvg, $response);
                }

            }

            $obj[] = [
                'seriesname' => $categoryName,
                'data' => $dataArrayAvg
            ];
            array_push($finalAverageChartObject, $obj);
        };

        if (count($finalAverageChartObject) > 0) {
            $data['category_list_data_average'] = $finalAverageChartObject[count($finalAverageChartObject)- 1];
        }
        else {
            $data['category_list_data_average'] = [];
        }

        $obj = null;

        $json = json_encode($data);

        print_r($json);

    }

    public static function picknpayCategoryManagement(){

        $picknpayBrand = \Brand::where('name', '=', 'PicknPay')->firstOrFail();
        $venues = $picknpayBrand->venues()->get();

        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';
        $data['venues'] = $venues;
        $data['brand'] = $picknpayBrand;

        return \View::make('hippnp.categorymanagement')->with('data', $data);
    }

    public static function picknpayStoreCategoryManagement($id){
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";
        $data['url'] = 'http://' . $_SERVER['SERVER_NAME'].'/';

        //TODO: Query the categories where store_id on the other table
        // 1390

        // $engageConnection = new \EngagePicknPayCategory();
        // $engageCategories = $engageConnection->getCategories($id);
        $engageCategories = \EngagePicknPayCategory::get();


        // $engageCategories = \DB::connection('hipengage')->table("pnp_category")->get();
        $data['engageCategories'] = $engageCategories;

        // $data['json'] = $id;

        return \View::make('hippnp.storecategorylist')->with('data', $data);

    }

}