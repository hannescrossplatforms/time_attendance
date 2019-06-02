<?php

class Picknpay extends Eloquent {


    protected $connection = 'hiphub';
    public function __construct() {
    	$this->connection = \Utils::getHiphubDbConnection();
	}

    protected $table = 'picknpay';

    public static function customerInStoreToday(){
        //TODO: Where date is today && group by customer uuid(maybe device uuid or something)
        return Picknpay::all()->count();
    }

    public static function customerInStoreThisMonth(){
        //TODO: Where date is this month && group by customer uuid(maybe device uuid or something)
        return Picknpay::all()->count();
    }

    public static function chartCategoriesAsJson(){
        //TODO: Pass store name in here and filter according to store.
        // return Picknpay::orderBy('id', 'ASC')->select('category')->groupBy('category')->get()->toJson();
        return Picknpay::orderBy('id', 'ASC')->select('category')->groupBy('category')->get()->map(function($row) {
            // return $row['category'];

            return array(
                'latitude' => $row['lat'],
                'longitude' => $row['lng'],
                'icon' => './images/' . $row['busColor'] . '.png'
            );
        });

        //SHould look like this
        // {
        //     "chart": chartProperties,
        //     "categories": [{
        //         "category": [{"label":"2019-05-27"},{"label":"2019-05-28"}]                                }],
        //     "dataset": [{"seriesname":"Staff At Work","data":[{"value":"0"},{"value":"0"}]},{"seriesname":"Staff Not At Work","data":[{"value":"1"},{"value":"1"}]}]
        // }



    }

    public static function getChartDwellTimeData(){
        //should return array
        //TODO: Pass store name in here and filter according to store.
        return Picknpay::orderBy('id', 'ASC')
        ->select('category', DB::raw('sum(dwell_time) dwell_time'))
        ->groupBy('category')
        ->get()->map(function($row){
            return (int)$row['dwell_time'];
        });

    }


    // $num = $mystuff->groupBy('dateDay')->map(function ($row) {
    //     return $row->sum('n');
    // });

    // $data['staff_week'] = json_encode(array(
        //         "client" => array(
        //            "build" => "1.0",
        //            "name" => "xxxxxx",
        //            "version" => "1.0"
        //         ),
        //         "protocolVersion" => 4,
        //         "data" => array(
        //            "distributorId" => "xxxx",
        //            "distributorPin" => "xxxx",
        //            "locale" => "en-US"
        //         )
        //    ));



}
