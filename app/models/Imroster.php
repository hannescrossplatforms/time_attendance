<?php

class Imroster extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
	
    protected $table = 'im_roster';

    protected $guarded = array();


}
