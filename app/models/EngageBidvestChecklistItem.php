<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngageBidvestChecklistItem extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest_checklist';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }






    public static function getChecklistItemsForRoom($roomID){

        // $storeRooms = \EngageBidvestCategory::where('store_id', '=', $id)->get();

        return EngageBidvestChecklistItem::orderBy('created_at', 'ASC')
        ->whereraw("day_for_checklist_item IS NULL")
        ->whereraw("room_id = '$roomID'")
        ->get();



    }

}