<?php

class Picknpay extends Eloquent {


    protected $connection = 'hiphub';
    public function __construct() {
    	$this->connection = \Utils::getEngageDbConnection();
	}

    protected $table = 'picknpay';


}
