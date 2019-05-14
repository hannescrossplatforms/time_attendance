<?php

class Operator extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    public $timestamps = false;

    public function measure() {
        return $this->belongsTo('App\Measure', 'measure_code', 'code');
    }

}
