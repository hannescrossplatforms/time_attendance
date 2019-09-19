<?php

class EngageBidvestStaff extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest_staff';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function getStaffWithID($id){
        return EngageBidvestStaff::find($id);
    }

    public static function getStaffAsArrayWithID($id){
        return EngageBidvestStaff::where('id', '=', $id)
        ->get();
    }

    public static function getAllStaff(){
        return EngageBidvestStaff::all();
    }

}