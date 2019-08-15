<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngageBidvestBeacon extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'bidvest_default_checklist';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

}