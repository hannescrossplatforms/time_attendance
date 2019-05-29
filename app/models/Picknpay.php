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

    public static function chartDwellTime(){
        return Picknpay::select('category as category')->toJson();
    }




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
