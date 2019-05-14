<?php

class Eventtime extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}

    public function hipevent() {
        return $this->belongsTo('Hipevent');
    }

}
