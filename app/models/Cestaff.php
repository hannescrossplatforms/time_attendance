<?php

class Cestaff extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    protected $table = 'ce_staff';

    protected $guarded = array();


}
