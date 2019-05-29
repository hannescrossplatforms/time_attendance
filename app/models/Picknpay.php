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
        return Picknpay::orderBy('id', 'ASC')->select('category')->get()->groupBy('category')->toJson();
    }

    public static function getChartDwellTimeData(){
        //should return array
        //TODO: Pass store name in here and filter according to store.

        // return $this->receipts()
        // ->select('warehouse_id', DB::raw('sum(receipt_stock.quantity) quantity'))
        // ->groupBy('warehouse_id')
        // ->get();



        <?php $array1=array(); ?>


        Picknpay::orderBy('id', 'ASC')
        ->select('category', DB::raw('sum(dwell_time) dwell_time'))
        ->groupBy('category')
        ->get()->map(function($row){
            array_push($array1, $row['dwell_time']);
        });

        return $array1;
        // return Picknpay::orderBy('id', 'ASC')->select('dwell_time', 'category')->get()->groupBy('category');

        // return Picknpay::orderBy('id', 'ASC')->get()->groupBy('category')->map(function($row) {
        //     return $row->sum('dwell_time');
        // });
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
