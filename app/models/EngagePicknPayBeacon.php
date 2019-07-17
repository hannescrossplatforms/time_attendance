<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngagePicknPayBeacon extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'pnp_beacon';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
    }

}