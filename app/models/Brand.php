
<?php

class Brand extends Eloquent {


    protected $dates = ['deleted_at'];

    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    // public function brands() {
    //     return $this->belongsToOne('User', 'users_levels', 'level_id', 'user_id');
    // }


    public function users() {
        return $this->belongsToMany('User');
    }

    public function medias() {
        return $this->hasMany('Media');
    }

    public function venues() {
        return $this->hasMany('Venue');
    }

    public function remotedb() {
        return $this->belongsTo('Remotedb');
    }

    public function isp() {
        return $this->belongsTo('Isp');
    }

    public function countrie() {
        return $this->belongsTo('Countrie');
    }

    public function insertUserGroupInHipWifi($groupname, $limit_type, $limit, $connection) {

        error_log("insertUserGroupInHipWifi : Brand : connection : $connection ");
        error_log("insertUserGroupInHipWifi : Brand : setUserGroupInHipWifi : $groupname : $limit");

        // Delete all entries from radgroupcheck and radgroupreply
        \DB::connection($connection)->table("radgroupcheck")->where('groupname', 'like', $groupname)->delete();
        \DB::connection($connection)->table("radgroupreply")->where('groupname', 'like', $groupname)->delete();

        // Add common attriibutes
        $record = array( "groupname" => $groupname, "attribute" => "HZ-Limit-Type", "op" => ":=", "value" => $limit_type );
        \DB::connection($connection)->table("radgroupcheck")->insert($record);

        $record = array( "groupname" => $groupname, "attribute" => "Idle-Timeout", "op" => "=", "value" => 450 );
        \DB::connection($connection)->table("radgroupreply")->insert($record);

        $record = array( "groupname" => $groupname, "attribute" => "Framed-Protocol", "op" => ":=", "value" => 1 );
        \DB::connection($connection)->table("radgroupreply")->insert($record);

        if($limit_type == "time") {
            ////////////// Add the time based limits /////////////////
            $secondsLimit = $limit * 60;

            $record = array( "groupname" => $groupname, "attribute" => "Max-Daily-Session", "op" => ":=", "value" => $secondsLimit );
            \DB::connection($connection)->table("radgroupcheck")->insert($record);

            $record = array( "groupname" => $groupname, "attribute" => "Session-Timeout", "op" => "=", "value" => $secondsLimit );
            \DB::connection($connection)->table("radgroupreply")->insert($record);

            $record = array( "groupname" => $groupname, "attribute" => "Max-All-Session", "op" => "=", "value" => $secondsLimit );
            \DB::connection($connection)->table("radgroupreply")->insert($record);

            $record = array( "groupname" => $groupname, "attribute" => "Max-Daily-Session", "op" => "=", "value" => $secondsLimit );
            \DB::connection($connection)->table("radgroupreply")->insert($record);

        } else {
            ////////////// Add the data based limits /////////////////
            $OctectsLimit = $limit * 1024 * 1024;

            $record = array( "groupname" => $groupname, "attribute" => "Max-Octets", "op" => ":=", "value" => $OctectsLimit );
            \DB::connection($connection)->table("radgroupcheck")->insert($record);

            $record = array( "groupname" => $groupname, "attribute" => "Service-Type", "op" => ":=", "value" => 2 );
            \DB::connection($connection)->table("radgroupreply")->insert($record);

            $record = array( "groupname" => $groupname, "attribute" => "Mikrotik-Total-Limit", "op" => "=", "value" => $OctectsLimit );
            \DB::connection($connection)->table("radgroupreply")->insert($record);

            $record = array( "groupname" => $groupname, "attribute" => "ChilliSpot-Max-Total-Octets", "op" => "=", "value" => $OctectsLimit );
            \DB::connection($connection)->table("radgroupreply")->insert($record);
        }
    }

    public function updateUserGroupInHipWifi($groupname, $limit_type, $limit, $connection) {

        error_log("updateUserGroupInHipWifi : Brand : connection : $connection ");
        error_log("updateUserGroupInHipWifi : $groupname : $limit : $limit_type ");

        if($limit_type == "time") {
            $limit_type_num = 1;
            $secondsLimit = $limit * 60;
            $OctectsLimit = 999999999999;

        } else {
            $limit_type_num = 0;
            $secondsLimit = 999999999999;
            $OctectsLimit = $limit * 1024 * 1024;
        }

        $record = array( "value" => $limit_type_num );
        \DB::connection($connection)->table("radgroupcheck")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "HZ-Limit-Type")
        ->update($record);

        ////////////// Update the time based limits /////////////////
        $record = array( "value" => $secondsLimit );

        \DB::connection($connection)->table("radgroupcheck")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "Max-Daily-Session")
        ->update($record);

        \DB::connection($connection)->table("radgroupreply")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "Session-Timeout")
        ->update($record);

        \DB::connection($connection)->table("radgroupreply")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "Max-All-Session")
        ->update($record);

        \DB::connection($connection)->table("radgroupreply")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "Max-Daily-Session")
        ->update($record);


        ////////////// Update the data based limits /////////////////
        $record = array( "value" => $OctectsLimit );

        \DB::connection($connection)->table("radgroupcheck")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "Max-Octets")
        ->update($record);

        \DB::connection($connection)->table("radgroupreply")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "Mikrotik-Total-Limit")
        ->update($record);

        \DB::connection($connection)->table("radgroupreply")
        ->where('groupname', 'like', $groupname)
        ->where('attribute', 'like', "ChilliSpot-Max-Total-Octets")
        ->update($record);
    }

    public function getBrandsForEngage() {

        $user =  \Auth::user();

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();
        } else {
            error_log("getBrands : NOT superadmin");
            $allowedbrands = $user->brands;
            // error_log("getBrands for user : allowedbrands " . print_r($allowedbrands[0][0], true));
        }

        $allowedbrand_ids = array();
        foreach($allowedbrands as $allowedbrand) {
            array_push($allowedbrand_ids, $allowedbrand['id']);
        }

        $brands = \DB::table('brands')
                ->select("*")
                ->where('brands.hipengage', '=', 1)
                ->whereIn('id', $allowedbrand_ids)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get();

        return $brands;
    }

    public function getBrandsForProduct($productname) {

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

        // hipwifi and tna
        $brands = null;

        if($productname == "hipwifi and tna") {

            $brands = \DB::table('brands')
                ->select("*")
                ->whereraw('hipwifi = 1 OR hiptna = 1')
                ->whereIn('id', $allowedbrand_ids)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get();
        }
        else {
            $brands = \DB::table('brands')
            ->select("*")
            ->where($productname, '=', 1)
            ->whereIn('id', $allowedbrand_ids)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
        }

        return $brands;
    }

    public function getEngageBrandsForUser($user_id) {

        if(\User::hasAccess("superadmin")) {
            $brands = \Brand::where('brands.hipengage', '=', 1)->orderBy('name', 'asc')->get();
        } else {

            $brands =  \DB::table('brands')
                    ->join('brand_user', 'brands.id', '=', 'brand_user.brand_id')
                    ->select("brands.*")
                    ->where('brand_user.user_id', '=', $user_id)
                    ->where('brands.hipengage', '=', 1)
                    ->whereNull('deleted_at')
                    ->orderBy('brands.name','ASC')
                    ->get();
        }

        return $brands;
    }


    public function getWifiBrandsForUser($user_id, $active_status = null) {

        error_log("getWifiBrandsForUser");

        if(\User::hasAccess("superadmin")) {
            $query = \Brand::orderBy('name', 'asc')->where('hipwifi', '=', 1);
            if($active_status == "active") {
                $query->where('wifi_activated', '=', 1); // Only get the inactive brands
            } else if ($active_status == "inactive") {
                $query->where('wifi_activated', '=', 0); // Only get the inactive brands
            } // Else get all brands
        } else {

            $query = \Brand::join('brand_user', 'brands.id', '=', 'brand_user.brand_id')
                    ->select("brands.*")
                    ->where('brand_user.user_id', '=', $user_id)
                    ->whereNull('deleted_at')
                    ->where('brands.hipwifi', '=', 1);

            if($active_status == "active") {
                $query->where('brands.wifi_activated', '=', 1); // Only get the inactive brands
            } else if ($active_status == "inactive") {
                $query->where('brands.wifi_activated', '=', 0); // Only get the inactive brands
            } // Else get all brands

            $query->orderBy('brands.name','ASC');

        }
        $brands = $query->get();

        return $brands;
    }

    public function getJamBrandsForUser($user_id, $active_status = null) {

        error_log("getJamBrandsForUser");

        if(\User::hasAccess("superadmin")) {
            $query = \Brand::orderBy('name', 'asc')->where('hipjam', '=', 1);
            if($active_status == "active") {
                $query->where('jam_activated', '=', 1); // Only get the inactive brands
            } else if ($active_status == "inactive") {
                $query->where('jam_activated', '=', 0); // Only get the inactive brands
            } // Else get all brands
        } else {

            $query = \Brand::join('brand_user', 'brands.id', '=', 'brand_user.brand_id')
                    ->select("brands.*")
                    ->where('brand_user.user_id', '=', $user_id)
                    ->whereNull('deleted_at')
                    ->where('brands.hipjam', '=', 1);

            if($active_status == "active") {
                $query->where('brands.jam_activated', '=', 1); // Only get the inactive brands
            } else if ($active_status == "inactive") {
                $query->where('brands.jam_activated', '=', 0); // Only get the inactive brands
            } // Else get all brands

            $query->orderBy('brands.name','ASC');

        }
        $brands = $query->get();

        return $brands;
    }


    public function getBrandsForUser($user_id) {

        if (\User::isVicinity()) {
            $brands = \Brand::whereRaw('parent_brand = 165 AND id != 165')->get();
        } else {
            if(\User::hasAccess("superadmin")) {
                $brands = \Brand::orderBy('name', 'asc')->get();
            } else {
                $brands = \Brand::join('brand_user', 'brands.id', '=', 'brand_user.brand_id')
                        ->select("brands.*")
                        ->where('brand_user.user_id', '=', $user_id)
                        ->whereNull('deleted_at')
                        ->orderBy('brands.name','ASC')
                        ->get();
    
            }
        }

        return $brands;
    }

    public function getBrandCodesForUser() {

      $brandcodes = array();
      $brands = $this->getBrandsForUser(\Auth::user()->id);

      foreach($brands as $brand) {
          array_push($brandcodes, $brand->code);
      }

      return $brandcodes;

    }

    public function importBrandsFromRadius($remotedb_id) {

        $remodtedb = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first();
        $connection = $remodtedb->dbconnection;
        error_log("importBrandsFromRadius : connection : $connection ");

        $nastypes = \DB::connection($connection)->table("nastype")->get();

        foreach($nastypes as $nastype) {

            $brand = new \Brand();
            $brand->name = $nastype->type;
            $brand->code = substr ( $nastype->type , 3,6 );
            $brand->isp_id = 1;
            $brand->remotedb_id = 1;
            $brand->countrie_id = 1;
            $brand->welcome = "Welcome to HipZone Free Wifi";
            $brand->login_process = "full";
            $brand->ssid = "HipZone Free Wifi";
            $brand->uru = $nastype->uru;
            $brand->limit = "200";
            $brand->hipwifi = 1;

            $brand->save();

        }

    }

}