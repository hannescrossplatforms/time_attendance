<?php

class Timeandattendance extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    protected $table = 'timeandattendance';


}
