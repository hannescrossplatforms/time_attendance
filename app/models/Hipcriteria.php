<?php

class Hipcriteria extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
        $this->connection = \Utils::getEngageDbConnection();
    }

    public function application() {
        return $this->belongsTo('Hipevent');
    }

    public function trigger() {
        return $this->belongsTo('App\Trigger', 'trigger_code', 'code');
    }

    public function measure() {
        return $this->belongsTo('Measure', 'measure_code', 'code');
    }
}
