<?php

class Trigger extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
        $this->connection = \Utils::getEngageDbConnection();
    }
    
    public function application() {
        return $this->belongsTo('\Application', 'application_code', 'code');
    }

    public function measures() {
        // This should work with a simple belongsToMany relationship
        // however this is causing a memory overflow
        // return $this->belongsToMany('\Measure');
        
        $measures = \DB::connection("hipengage")
            ->table('measures')
            ->join('measure_trigger', function($join)
            {
                $join->on('measures.id', '=', 'measure_trigger.measure_id')
                     ->where('measure_trigger.trigger_id', 'like', $this->id);
            })
            ->get();

        return $measures;

    }

    public function events() {
        return $this->hasMany('Hipevent', 'trigger_code', 'code');
    }

    public function hipcriterias() {
        return $this->hasMany('Hipcriteria', 'trigger_code', 'code');
    }
}
