<?php

class EngagePicknPayCategory extends Eloquent {




    protected $connection = 'hipengage';
    // public function __construct() {
    //     $this->connection = \Utils::getEngageDbConnection();
//     }
    
    public function getCategories($id) {
        return $this::where('store_id', $id);
    }
}


// protected $connection = 'hipengage';
// public function __construct() {
//     $this->connection = \Utils::getEngageDbConnection();
// }

// public function events() {
//     return $this->hasMany('App\Hipevent', 'engagebrand_code', 'code');
// }

// public function devices() {
//     return $this->hasMany('App\Device', 'engagebrand_code', 'code');
// }

