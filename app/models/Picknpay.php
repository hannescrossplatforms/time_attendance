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

        $startDate = "$start 00.00.00";
        $endDate = "$end 23:59:59";

        //TODO: Where date is today && group by customer uuid(maybe device uuid or something)
        return Picknpay::where('created_at', ">=", $startDate)->where('created_at', "<=", $endDate)->count();
    }

    public static function customerInStoreThisMonth($start, $end){
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

//SHould look like this
        // {
        //     "chart": chartProperties,
        //     "categories": [{
        //         "category": [{"label":"2019-05-27"},{"label":"2019-05-28"}]                                }],
        //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
        // }