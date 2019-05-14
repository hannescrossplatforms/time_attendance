<?php

class Server extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table

    public function venues() {
        return $this->hasMany('Venue');
    }

    public function remotedb() {
        return $this->belongsTo('Remotedb');
    }


    public function getServersForProduct($product_id, $hostname=null, $brandstr=null) {


        $hostname =  "%" . $hostname . "%";
        $brandstr =  "%" . $brandstr . "%";

        $user =  \Auth::user();

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();  
        } else {
            error_log("getBrands : NOT superadmin");
            $allowedbrands = $user->brands;  
        }

        $allowedbrand_ids = array();
        foreach($allowedbrands as $allowedbrand) {
            error_log("getServersForProduct : " . $allowedbrand['name']);
            array_push($allowedbrand_ids, $allowedbrand['id']);
        }

        $servers =  \DB::table('servers')
                ->join('remotedbs', 'servers.remotedb_id', '=', 'remotedbs.id')
                ->join('brands', 'brands.remotedb_id', '=', 'remotedbs.id')
                ->join('countries', 'countries.id', '=', 'brands.countrie_id')
                ->select(array("servers.id as serverId", "servers.hostname as serverHostname","brands.id as brandId", 
                               "brands.name as brandName", "countries.name as countryName")) 
                ->where('brands.hipwifi', '=', 1)
                ->where('hostname', 'like', $hostname)
                ->where('brands.name', 'like', $brandstr)
                ->whereIn('brands.id', $allowedbrand_ids)
                ->orderBy('servers.id','ASC')
                ->orderBy('brands.id','ASC')
                ->orderBy('countries.id','ASC')
                ->get();

                // ->wherein('remotedbs.id', $remotedb_ids)

        return $servers;
    }

}