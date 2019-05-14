<?php

class Pushnotification extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}


}
