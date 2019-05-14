<?php

class Device extends Eloquent
{
	protected $connection = 'hipengage';
	public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}
//  	protected $fillable = ['token','isActive'];
//     public function user(){
 
//         return $this->belongsTo('App\User');
//     }

//     public function device() {
//         return $this->hasMany('App\Hipevent', 'engagebrand_code', 'code');
//     }
}
