<?php

class Beaconmessage extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}

    public $timestamps = false;

}
