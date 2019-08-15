<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngageBidvestChecklistItem extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest_checklist';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }






    public static function getChecklistItemsForRoom($roomID){

        return EngageBidvestChecklistItem::orderBy('created_at', 'ASC')
        ->whereraw("day_for_checklist_item", "=","0000-00-00 00:00:00")
        ->whereraw("room_id = '$roomID'")
        ->get();

    }

}