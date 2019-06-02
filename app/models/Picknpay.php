<?php

class Picknpay extends Eloquent {


    protected $connection = 'hiphub';
    public function __construct() {
    	$this->connection = \Utils::getHiphubDbConnection();
	}

    protected $table = 'picknpay';

    public static function customerInStoreToday(){

        $start = \Carbon\Carbon::now()->format('Y-m-d');
        $end = \Carbon\Carbon::now()->format('Y-m-d');

        $dateRange = Picknpay::getDateForPeriodAndTimeOfDay('today');

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // $startDate = "$start 00.00.00";
        // $endDate = "$end 23:59:59";



        //TODO: Where date is today && group by customer uuid(maybe device uuid or something)
        return Picknpay::where('created_at', ">=", $startDate)->where('created_at', "<=", $endDate)->count();
    }

    public static function customerInStoreThisMonth(){
        //TODO: Where date is this month && group by customer uuid(maybe device uuid or something)
        return Picknpay::all()->count();
    }

    public static function chartCategoriesAsJson(){
        //TODO: Pass store name in here and filter according to store.
        // return Picknpay::orderBy('id', 'ASC')->select('category')->groupBy('category')->get()->toJson();
        return Picknpay::orderBy('id', 'ASC')->select('category', 'created_at')->groupBy('category')->get()->map(function($row) {
            // return $row;
            return array('label' => $row['created_at']->toDateString());
        });

    }

    public static function getChartDwellTimeData(){
        //should return array
        //TODO: Pass store name in here and filter according to store.
        return Picknpay::orderBy('id', 'ASC')
        ->select('category', DB::raw('sum(dwell_time) dwell_time'))
        ->groupBy('category')
        ->get()->map(function($row){

            return array('seriesname' => $row['category'],
            'data' => array(
                    "value" => (int)((int)$row['dwell_time'] / 60)
                ));

        });

    }

}

    public static function getDateForPeriodAndTimeOfDay($period){

        $returnValue = '';

        if($period == 'today'){

            $date = date('Y-m-d',strtotime('today'));

            $returnValue = array('startDate' => "$date 00.00.00";
            $returnValue = array('endDate' => "$date 23:59:59";
            $returnValue = array('period' => "today";

        }else if($period == 'rep7day'){

            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');

            $returnValue = array('startDate' => "$start 00.00.00";
            $returnValue = array('endDate' => "$end 23:59:59";
            $returnValue = array('period' => "week";

        }else if($period == 'repthismonth'){

            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d');

            $returnValue = array('startDate' => "$start 00.00.00";
            $returnValue = array('endDate' => "$end 23:59:59";
            $returnValue = array('period' => "month";

        }else if($period == 'replastmonth'){

            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));

            $returnValue = array('startDate' => "$start 00.00.00";
            $returnValue = array('endDate' => "$end 23:59:59";
            $returnValue = array('period' => "month";

        }else if($period == 'daterange'){

            $start = Input::get('start');
            $end = Input::get('end');

            $returnValue = array('startDate' => "$start 00.00.00";
            $returnValue = array('endDate' => "$end 23:59:59";
            $returnValue = array('period' => "date";

        }else if($period == 'custom'){
            $start = Input::get('start');
            $end = Input::get('end');

            $returnValue = array('startDate' => "$start 00.00.00";
            $returnValue = array('endDate' => "$end 23:59:59";
            $returnValue = array('period' => "date";

        }

        return $returnValue;

    }

//SHould look like this
        // {
        //     "chart": chartProperties,
        //     "categories": [{
        //         "category": [{"label":"2019-05-27"},{"label":"2019-05-28"}]                                }],
        //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
        // }