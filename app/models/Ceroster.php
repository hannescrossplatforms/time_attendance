<?php

class Ceroster extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    protected $table = 'ce_roster';

    protected $guarded = array();


}
