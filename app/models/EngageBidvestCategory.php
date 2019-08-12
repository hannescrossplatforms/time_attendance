<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngageBidvestCategory extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest_category';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

    public static function getCategoryWithID($id){
        return EngageBidvestCategory::find($id)->name;
    }

}