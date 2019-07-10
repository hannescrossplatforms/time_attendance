<?php
// https://stackoverflow.com/questions/38459308/how-to-connect-another-database-with-model-in-laravel
class EngagePicknPayCategory extends Eloquent {

    protected $connection = 'hipengage';
    protected $table = 'pnp_category';

    public function __construct() {
        $this->connection = \Utils::getEngageDbConnection();
        $this->table = $table;
    }

    // public function categories($id) {

    //     return $this->belongsto('\EngagePicknPayCategory', 'store_id');
    // }

    // public function categories() {
    //     return $this->hasMany('\PnpCategory', 'venueposition_id', 'id');
    // }
}


// $beacon = \Beacon::where('venueposition_id', '=', $venueposition->id)->first();