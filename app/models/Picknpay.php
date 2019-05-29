<?php

class Picknpay extends Eloquent {


    protected $connection = 'hiphub';
    public function __construct() {
    	$this->connection = \Utils::getHiphubDbConnection();
	}

    protected $table = 'picknpay';

    public function customerInStoreToday()
    {
        return Picknpay::all()->count();
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
