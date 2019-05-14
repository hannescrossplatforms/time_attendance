<?php

class Isp extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    // protected $table = 'remotedbs';
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function venues() {
        return $this->hasMany('Venue');
    }

}