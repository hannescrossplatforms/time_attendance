<?php

class EngagePicknPayStaff extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'pnp_staff';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function getStaffWithID($id){
        return EngagePicknPayStaff::find($id);
    }

    public static function getStaffAsArrayWithID($id){
        return EngagePicknPayStaff::where('id', '=', $id)
        ->get();
    }

    public static function getAllStaff(){
        return EngagePicknPayStaff::orderBy('name', 'ASC')->get();
    }

}