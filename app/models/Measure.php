<?php

class Measure extends Eloquent {

    protected $connection = 'hipengage';
    public function __construct() { 
        $this->connection = \Utils::getEngageDbConnection();
    }

    public $timestamps = false;

    public function triggers() {
        // This should work with a simple belongsToMany relationship
        // however this is causing a memory overflow
        // return $this->belongsToMany('\Trigger');
        
        $triggers = \DB::connection("hipengage")
            ->table('triggers')
            ->join('measure_trigger', function($join)
            {
                $join->on('triggers.id', '=', 'measure_trigger.triggers_id')
                     ->where('measure_trigger.measure_id', 'like', $this->id);
            })
            ->get();

        return $measures;
    }

    public function operators() {
        return $this->hasMany('\Operator', 'measure_code', 'code');
    }

    public function listvalues() {
        return $this->hasMany('\Listvalue', 'measure_code', 'code');
    }

    public function hipcriterias() {
        return $this->hasMany('\Hipcriteria', 'measure_code', 'code');
    }
}
