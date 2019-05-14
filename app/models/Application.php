<?php

class Application extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}

	public $primaryKey  = 'code';

    public function triggers() {
        return $this->hasMany('Trigger', 'application_code', 'code');
    }

    public function event() {
        return $this->hasMany('Hipevent', 'application_code', 'code');
    }
}
