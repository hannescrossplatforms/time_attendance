<?php

class Beacon extends Eloquent {

    // $utils = new \Utils();

    public $timestamps = false;

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    public function venueposition() {
        return $this->belongsto('\Venueposition', 'venueposition_id', 'id');
    }
}
