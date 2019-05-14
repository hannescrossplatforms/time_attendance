
<?php

class Citie extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function province() {
        return $this->belongsTo('Province');
    }

    public function media() {
        return $this->hasMany('Media');
    }


    public function getcities($province_id) {

        $cities =  \DB::table('cities')
                ->where('province_id', '=', $province_id)
                ->get();

        return($cities);

    }

    public function advertMedia() {
        return $this->hasMany('Advertmedia');
    }

}