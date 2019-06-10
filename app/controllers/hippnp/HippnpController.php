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
        $staff_graph = array();
        $dates_series = array();
        $my_asshole = array();
        $data = array();
        $period = Input::get('period');
        $categories = \Picknpay::fetchCategories();

        $data['category'] = \Picknpay::chartCategoriesAsJson($period, true);
        $data['staff_graph'] = \Picknpay::getChartTotalDwellTimeData($period);

        $asddates = \Picknpay::firstLevelData()
        ->map(function($row) {
                $thing = $row['created_att'];
                return "$thing";
            })
            ->toArray();
        $dates = \Picknpay::firstLevelData()
        ->map(function($row) {
                return ['label' => $row['created_att']];
            });

            $data_array = array();
            $complete_object = array();




            // if ($dates_series == null) {
            //     $dates_series = array();
            // }
            // $dates_series = array_push($dates_series, $date['created_att']);

            foreach ( $dates as $date ) {

            // foreach ( $categories as $cat ) {

                // $categoryName = $cat->category;

                $my_asshole = array();



            // foreach ( $dates as $date ) {
            // foreach($categories as $key=>$cat) {


                // foreach($array as $key=>$value) {
                //     // do stuff
                // }


                // foreach ( $dates as $date ) {
                // $my_asshole = array();
                // $staff_graph = array_push({value: $staff_graph, });


                // $data['ffs'] = $cat->category;
                // $data['ffssss'] = $date['created_att'];
                $current_date = $date['label'];
                $dataForCategoryDates = DB::select(DB::raw("SELECT sum(CAST(dwell_time AS UNSIGNED))AS value, category FROM picknpay WHERE DATE_FORMAT(created_at,'%Y-%m-%d') = '$current_date' GROUP BY category, DATE_FORMAT(created_at,'%Y-%m-%d') ORDER BY DATE_FORMAT(created_at,'%Y-%m-%d')"))
                ->map(function($row) {
                        return [
                            'seriesname' => $row->category,
                            'data' => $row->value
                        ]
                    });
                // $obj[] = [
                //     'seriesname' => $categoryName,
                //     'data' => $dataForCategoryDates
                // ];
                array_push($my_asshole, $obj);





            // }
        // }


        // $data['correctlyInst'] = $obj;
        // array_push($complete_object, $obj);
        // $my_asshole = array_push($my_asshole, $obj);


        };

        $data['complete'] = $my_asshole;

        $data['mypenis'] = $dates_series;
        $data['myasshole'] = $my_asshole;
        $data['first_level_data'] = $dates;

        $data['total_dwell_time_chart_data'] = \Picknpay::hannesTestCategories();

        // $data['first_level_data'] = \Picknpay::firstLevelData()->map(function($row) {
        //     return array('label' => $row['created_att']);
        // });

        // foreach ( $data['first_level_data'] as $row ) {
        //     $var = $row['label'];

        //     $response = \Picknpay::secondLevelData($var);



        //     $response->map(function($row) {
        //         $seriesName = $row['category'];

        //     });


        //     $response
        //     array_push($mibum,\Picknpay::secondLevelData($var));

        // }

        // $data['mibum'] = $mibum;



    // 0: {category: "Food", value: "100"}
    // 1: {category: "Games", value: "160"}


// "categories": [{
        //     //     "category": [{"label":"2019-06-03"},{"label":"2019-06-04"},{"label":"2019-06-05"},{"label":"2019-06-06"},{"label":"2019-06-07"},{"label":"2019-06-08"},{"label":"2019-06-09"}]                                }],
        //     // "dataset": [{"seriesname":"2019-06-03","data":[{"food":"0"},{"games":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"},{"value":"0"}]},{"seriesname":"2019-06-03","data":[{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"},{"value":"1"}]}]


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