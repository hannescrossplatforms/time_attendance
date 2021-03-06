<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Media extends Eloquent implements UserInterface, RemindableInterface {

use UserTrait, RemindableTrait;

    protected $dates = ['deleted_at'];

    //////////// NOT USING SOFT DELETES FOR MEDIA ///////

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'medias';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */


    public function brand() {
        return $this->belongsTo('Brand');
    }

    public function countrie() {
        return $this->belongsTo('Countrie');
    }

    public function province() {
        return $this->belongsTo('Province');
    }
    public function citie() {
        return $this->belongsTo('Citie');
    }
    public function venue() {
        return $this->belongsTo('Venue');
    }
    
    public function getMedias() {

        $user =  \Auth::user();

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();  
        } else {
            error_log("getBrands : NOT superadmin");
            $allowedbrands = $user->brands;  
        }

        $allowedbrand_ids = array();
        foreach($allowedbrands as $allowedbrand) {
            array_push($allowedbrand_ids, $allowedbrand['id']);
        }

        $medias = \Media::whereIn('brand_id', $allowedbrand_ids)->get();

        return $medias;
	}

    public function getLoginmedia($id){

        $loginmedias = \Media::where('brand_id', $id)->get();

        return $loginmedias;
    }

}















