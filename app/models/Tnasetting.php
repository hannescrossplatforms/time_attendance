<?php

class Tnasetting extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}

    protected $guarded = array();


}
