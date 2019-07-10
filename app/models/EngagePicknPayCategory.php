<?php

class EngagePicknPayCategory extends Eloquent {



    public $timestamps = false;

    protected $connection = 'hipengage';
    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }
    
    public function getCategories($id) {
        return $this::where('store_id', $id);
    }
}



