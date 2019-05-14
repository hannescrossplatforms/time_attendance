<?php

class Venueposition extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
    	$this->connection = \Utils::getEngageDbConnection();
	}

    public $timestamps = false;

    public function beacons() {
        return $this->hasMany('\Beacon', 'venueposition_id', 'id');
    }

    public function hipevents() {
        return $this->belongsToMany('\Hipevent', 'hipevents_venuepositions', 'venueposition_id', 'hipevent_id');
    }
}
