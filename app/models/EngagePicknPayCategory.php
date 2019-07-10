<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngagePicknPayCategory extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'pnp_category';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
        $this->table = $table;
    }

}