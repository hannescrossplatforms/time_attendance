<?php

class Remotedb extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    // protected $table = 'remotedbs';
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table

    protected $table = 'remotedbs';
    
    public function brands() {
        return $this->hasMany('Brand');
    }

    public function servers() {
        return $this->hasMany('Server');
    }

    public function getRemotedbIdsForUser($user_id) {
        $brand = new \Brand();
        $brands = $brand->getBrandsForUser($user_id);

        $remotedb_ids = array();

        foreach($brands as $brand) {

            error_log("getRemotedbIdsForUser : name=" . $brand->name . " : id=" . $brand->id);

            if(!in_array($brand->remotedb_id , $remotedb_ids)) {
                array_push($remotedb_ids, $brand->remotedb_id);
            }
        }

        return($remotedb_ids);
    }

}