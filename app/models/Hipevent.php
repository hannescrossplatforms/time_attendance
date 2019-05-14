<?php

class Hipevent extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
        $this->connection = \Utils::getEngageDbConnection();
    }


    public function test() {
        error_log("Hipevent : test");
        return 1;
    }
    
    public function application() {
        return $this->belongsTo('Application', 'application_code', 'code');
    }

    public function engagebrand() {
        return $this->belongsTo('Engagebrand', 'engagebrand_code', 'code');
    }

    public function trigger() {
        return $this->belongsTo('Trigger', 'trigger_code', 'code');
    }

    public function pushnotification() {
        return $this->belongsTo('Pushnotification', 'pushnotification_id', 'id');
    }

    public function smsnotification() {
        return $this->belongsTo('Smsnotification', 'smsnotification_id', 'id');
    }

    public function emailnotification() {
        return $this->belongsTo('Emailnotification', 'emailnotification_id', 'id');
    }

    public function hipcriterias() {
        return $this->hasMany('Hipcriteria');
    }

    public function venuepositions() {
        return $this->belongsToMany('Venueposition', 'hipevents_venuepositions', 'hipevent_id', 'venueposition_id');
    }

    public function eventtimes() {
        return $this->hasMany('Eventtime');
    }
}
