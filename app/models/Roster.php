<?php

class Roster extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    protected $table = 'roster';

    protected $guarded = array();


}
