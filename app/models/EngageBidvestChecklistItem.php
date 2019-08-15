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
        ->whereNull('day_for_checklist_item')
        ->whereraw("room_id = '$roomID'")
        ->get();

    }

}