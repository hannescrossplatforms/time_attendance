
<?php

class Province extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table

    public function countries() {
        return $this->belongsTo('Countrie');
    }

    public function cities() {
        return $this->hasMany('Citie');
    }

    public function media() {
        return $this->hasMany('Media');
    }

    public function getprovinces($countrie_id) {

        $provinces =  \DB::table('provinces')
                ->where('countrie_id', '=', $countrie_id)
                ->get();

        return($provinces);

    }

    public function advertmedia(){
        return $this->hasMany('Advertmedia');
    }

}