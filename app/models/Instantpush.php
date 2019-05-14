<?php

class Instantpush extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    protected $table = 'instantpushes';

    protected $guarded = array();


}
