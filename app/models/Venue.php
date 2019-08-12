<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class Venue extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

    protected $dates = ['deleted_at'];

	protected $fillable = array('location');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'venues';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */


    public function server() {
        return $this->belongsTo('Server');
    }

    public function isp() {
        return $this->belongsTo('Isp');
    }

    public function brand() {
        return $this->belongsTo('Brand');
    }

    public function media() {
        return $this->hasOne('Media');
    }

    public function advertmedia() {
        return $this->hasMany('Advertmedia');
    }

    public function getvenues($isp_id, $brand_id, $citie_id) {

        error_log("chchchchch $brand_id   $citie_id");

                // ->where('isp_id', '=', $isp_id)
        $venues =  \DB::table('venues')
                ->where('brand_id', '=', $brand_id)
                ->where('citie_id', '=', $citie_id)
                ->whereNull('deleted_at')
                ->get();

        return($venues);

    }

    public function getVenuesForUser($productname = null, $showall = null, $device_type = null, $brand_id = null, $active_status = null, $search=null, $userPassed=null) {

        error_log("getVenuesForUser : productname = $productname");
        error_log("getVenuesForUser : active_status = $active_status");

        $user = Auth::user();
        if (!$user){
            $user = $userPassed;
        }
        if(!$device_type) $device_type = "%";

        $query = \Venue::join('brands', 'venues.brand_id', '=', 'brands.id')
                ->select(array("venues.*"))
                ->orderBy('venues.sitename','ASC');

        if($productname) {
            $prodcolumn = 'brands.' . $productname;
            $query->where($prodcolumn, '=' ,1);

            if($active_status == "active") {
                $activated_operator = "=";
            } else if($active_status == "inactive") {
                $activated_operator = "!=";
            } else {
                $activated_operator = "<="; // a bit of a hack, but this just makes sure that all rows are returned
            }

            if($productname == "hipwifi") {
                $query->where('brands.wifi_activated', "=", 1);
                $query->where('venues.wifi_activated', $activated_operator, 1);
            } else if($productname == "hiprm") {
                $query->where('brands.rm_activated', "=", 1);
                $query->where('venues.rm_activated', $activated_operator, 1);
            } else if($productname == "hipjam") {
                error_log("getVenuesForUser : jam_activated = $activated_operator");
                $query->where('brands.jam_activated', "=", 1);
                $query->where('venues.jam_activated', $activated_operator, 1);
                if ($search){
                    $query->where("venues.sitename", "like", '%'.$search.'%');
                }

            } else if($productname == "hipengage") {
                $query->where('brands.engage_activated', "=", 1);
                $query->where('venues.engage_activated', $activated_operator, 1);
            }
        }

        if($brand_id) {
            $query->where('brands.id' , '=', $brand_id);
        }

        // By default show only the wifi venues with active APs
        if($productname == "hipwifi") {
            if(!$showall) {
                $query->where("ap_active" , "=", 1);
                $query->where('device_type', 'like', $device_type);
            }
        }


       /* if($productname == "hipjam") {
            $query->where("track_enabled" , "=", 1);
        }
        */

        $venues = $query->get();

        if($user->level_code == "superadmin") {
            //dd($venues);
            return $venues;

        }

        // if($productname == "hipjam") {
        //     return $venues;
        // }

        $brands = $user->brands;
        $brand_ids = array();
        foreach($brands as $brand) {
            error_log("getVenuesForUser : brand name " . $brand->name);
            array_push($brand_ids, $brand->id);
        }

        $filteredVenues = array();
        foreach($venues as $venue) {
            if(in_array($venue->brand_id, $brand_ids)) {
                error_log("getVenuesForUser : venue name " . $venue->name);
                array_push($filteredVenues, $venue);
            }
        }



        return $filteredVenues;
    }


    public function refreshMediaLocations() {

    	$venues = \Venue::All();

    	foreach ($venues as $venue) {
            $this->refreshMediaLocation($venue);
    	}
    }

    public function refreshMediaLocation($venue) {


        $location = $venue->location;

        error_log("refreshMediaLocation location = $location");

        $isp_code = substr ( $location , 0,3 );
        $brand_code = substr ( $location , 3,6 );
        $venue_code = substr ( $location , 9,10 );
        $citie_code = substr ( $location , 19,3 );
        $province_code = substr ( $location , 22,3 );
        $countrie_code = substr ( $location , 25,2 );

        $venue_level_media = $location;
        $citie_level_media = $isp_code . $brand_code . "XXXXXXXXXX" . $citie_code . $province_code . $countrie_code;
        $province_level_media = $isp_code . $brand_code . "XXXXXXXXXX" . $province_code . "XXX" . $countrie_code;
        $countrie_level_media = $isp_code . $brand_code . "XXXXXXXXXX" . "XXX" . "XXX" . $countrie_code;

        $media = new \Media();
        $venue_record = $media->select("*")->where('location', 'like', $venue_level_media)->first();
        $citie_record = $media->select("*")->where('location', 'like', $citie_level_media)->first();
        $province_record = $media->select("*")->where('location', 'like', $province_level_media)->first();
        $countrie_record = $media->select("*")->where('location', 'like', $countrie_level_media)->first();

        $record = array("medialocation" => $venue->location);

        if($venue_record) {
            $this->where('location', $location)->update(["medialocation" => $venue_level_media]);
            error_log("MATCH venue : $location  $venue_level_media : " . $venue_record->location);
        } elseif ($citie_record) {
            $this->where('location', $location)->update(["medialocation" => $citie_level_media]);
            error_log("MATCH citie : $location  $citie_level_media : " . $citie_record->location);
        } elseif ($province_record) {
            $this->where('location', $location)->update(["medialocation" => $province_level_media]);
            error_log("MATCH province : $location  $province_level_media : " . $province_record->location);
        } elseif ($countrie_record) {
            $this->where('location', $location)->update(["medialocation" => $countrie_level_media]);
            // $this->where('location', $location)->update(["dt_ext" => $media->dt_ext]);
            // $this->where('location', $location)->update(["mb_ext" => $media->mb_ext]);
            error_log("MATCH country : $location  $countrie_level_media : " . $countrie_record->location);
        } else {
            $this->where('location', $location)->update(["medialocation" => "HIPXXXXXXXXXXXXXXXXXXXXXXZA"]);
            error_log("No Match : ");
        }

        // Refresh the venue with values in the DB
        return \Venue::find($venue->id);

    }

    public function constructRadiusVenueRecord($media, $venue) {
        error_log("constructRadiusVenueRecord : 10");

        $dt_ext = $media->dt_ext;
        $mb_ext = $media->mb_ext;
        $login_process = $media->brand->login_process;
        $welcome_flag = $media->welcome_flag;
        $welcome_message = $media->brand->welcome;
        $ef_group_pos = $media->ef_group_pos;
        $ef_transparency = $media->ef_transparency;
        $ef_colour = $media->ef_colour;
        $ef_outline_text_colour = $media->ef_outline_text_colour;
        $zonein_gap = $media->zonein_gap;
        $zonein_btn_colour = $media->zonein_btn_colour;
        $zone_txt_colour = $media->zone_txt_colour;
        $faq_colour = $media->faq_colour;
        $logo_choice = $media->logo_choice;

        $connect_btn_enabled = $media->connect_btn_enabled;

        if(!$media->connect_btn_colour) {
            $connect_btn_colour = "#00AB4F";
            $connect_text_colour = "#FFFFFF";
            $connect_btn_offset_from_top = 200;
        } else {
            $connect_btn_colour = $media->connect_btn_colour;
            $connect_text_colour = $media->connect_text_colour;
            $connect_btn_offset_from_top = $media->connect_btn_offset_from_top;
        }
        error_log("constructRadiusVenueRecord : 20 : connect_btn_colour = $connect_btn_colour");
        error_log("constructRadiusVenueRecord : 20 : zone_txt_colour = $zone_txt_colour");


        $record = array(
                "sitename" => $venue->sitename,
                "location" => $venue->location,
                "medialocation" => $venue->medialocation,
                "dt_ext" => $dt_ext,
                "mb_ext" => $mb_ext,
                "login_process" => $login_process,
                "welcome_flag" => $welcome_flag,
                "welcome_message" => $welcome_message,
                "ef_group_pos" => $ef_group_pos,
                "ef_transparency" => $ef_transparency,
                "ef_colour" => $ef_colour,
                "ef_outline_text_colour" => $ef_outline_text_colour,
                "zonein_gap" => $zonein_gap,
                "zonein_btn_colour" => $zonein_btn_colour,
                "zone_txt_colour" => $zone_txt_colour,
                "faq_colour" => $faq_colour,
                "logo_choice" => $logo_choice,
                "connect_btn_enabled" => $connect_btn_enabled,
                "connect_btn_colour" => $connect_btn_colour,
                "connect_text_colour" => $connect_text_colour,
                "connect_btn_offset_from_top" => $connect_btn_offset_from_top,
                "macaddress" => $venue->macaddress,
                "latitude" => $venue->latitude,
                "longitude" => $venue->longitude,
                "address" => $venue->address,
                "contact" => $venue->contact
            );

        error_log("constructRadiusVenueRecord : 30");
        return $record;
    }

    public function setMediaDefaults($venue) {
            $media = new \Media();
            $media->brand_id = $venue->brand_id;
            $media->dt_ext = "jpg";
            $media->mb_ext = "jpg";
            $media->bg_colour = "#00B551";
            $media->welcome_flag = 0;
            $media->ef_group_pos = 25;
            $media->ef_transparency = 80;
            $media->ef_colour = "#23E07B";
            $media->ef_outline_text_colour = "#FFFFFF";
            $media->zonein_gap = 30;
            $media->zonein_btn_colour = "#00AB4F";
            $media->zone_txt_colour = "#FFFFFF";
            $media->faq_colour = "#FFFFFF";
            $media->logo_choice = "white";

            $media->connect_btn_enabled = 0;
            $media->connect_btn_colour = "#00AB4F";
            $media->connect_text_colour = "#FFFFFF";
            $media->connect_btn_offset_from_top = 200;

            return $media;
    }





/// BEGIN HIPRM ADVERT FUNCTIONS

    public function insertVenueInHiprm($venue) {

        error_log("insertVenueInHiprm 01 : sitename = " . $venue->sitename);

        $media = \Advertmedia::where('location', 'like', $venue->medialocation)->first();

        error_log("insertVenueInHiprm 02 : ");
        $medianame = "xxxxxx";
        if($media) {
            $medianame = $media->medianame;
            error_log("insertVenueInHiprm type = " . $media->type);
            // $media = $this->setHiprmMediaDefaults($venue);
        }

        error_log("insertVenueInHiprm 10 : medianame = $medianame");

        $record = $this->constructHiprmVenueRecord($medianame, $venue);

        error_log("insertVenueInHiprm 20 : sitename = " . $venue->sitename);

        \DB::connection("hiprm_db")->table("venues")->insert($record);

        error_log("insertVenueInHiprm 30 : sitename = " . $venue->sitename);
    }

    public function updateVenueInHiprm($venue) {

        $media = \Advertmedia::where('location', 'like', $venue->medialocation)->first();

        if(!$media) {
            $media = $this->setHiprmMediaDefaults($venue);
        }

        $record = $this->constructHiprmVenueRecord($media, $venue);
        \DB::connection("hiprm_db")
                ->table("venues")
                ->where("macaddress", "like", $venue->macaddress)
                ->update($record);
    }

    public function deleteVenueInHiprm($venue) {

        \DB::connection("hiprm_db")
                ->table("venues")
                ->where("macaddress", "like", $venue->macaddress)
                ->delete();
    }

    public function synchToHiprm() {

        error_log("synchToHiprm 10");
        // $testing =  \DB::connection("rmstag_hiprm")->table("nastype")->whereNull('updated_at')->get();
        // $testing =  \DB::connection("hubstag_radius")->table("nastype")->whereNull('updated_at')->get();

        // Truncate the venue table on hiprm
        \DB::connection("hiprm_db")->table("venues")->truncate();

        $venues =  \DB::table('venues')
                ->whereNull('deleted_at')
                ->get();

        foreach($venues as $venue) {
            $this->insertVenueInHiprm($venue);
        }

    }

    public function refreshAdvertMediaNames() {

        $venues = \Venue::All();
        // $venues = \Venue::where('location', 'like', '%clicks%')->get();

        foreach ($venues as $venue) {
            $this->refreshAdvertMediaName($venue);
        }
    }

    public function refreshAdvertMediaName($venue) {


        error_log("refreshAdvertMediaName location = " . $venue->location);

        $location = $venue->location;

        $isp_code = substr ( $location , 0,3 );
        $brand_code = substr ( $location , 3,6 );
        $venue_code = substr ( $location , 9,10 );
        $province_code = substr ( $location , 19,3 );
        $citie_code = substr ( $location , 22,3 );
        $countrie_code = substr ( $location , 25,2 );

        $venue_level_media = $location;
        $citie_level_media = $isp_code . $brand_code . "XXXXXXXXXX" . $citie_code . $province_code . $countrie_code;
        $province_level_media = $isp_code . $brand_code . "XXXXXXXXXX" . $province_code . "XXX" . $countrie_code;
        $countrie_level_media = $isp_code . $brand_code . "XXXXXXXXXX" . "XXX" . "XXX" . $countrie_code;
        // error_log("province_level_media : $province_level_media ");

        $media = new \Advertmedia();
        $venue_record = $media->select("*")->where('location', 'like', $venue_level_media)->first();
        $citie_record = $media->select("*")->where('location', 'like', $citie_level_media)->first();
        $province_record = $media->select("*")->where('location', 'like', $province_level_media)->first();
        $countrie_record = $media->select("*")->where('location', 'like', $countrie_level_media)->first();

        $record = array("advertMediaName" => $venue->location);

        if($venue_record) {
            $this->where('location', $location)->update(["advertMediaName" => $venue_record->medianame]);
            error_log("MATCH venue : $location  $venue_level_media : " . $venue_record->medianame);
        } elseif ($citie_record) {
            $this->where('location', $location)->update(["advertMediaName" => $citie_record->medianame]);
            error_log("MATCH citie : $location  $citie_level_media : " . $citie_record->medianame);
        } elseif ($province_record) {
            $this->where('location', $location)->update(["advertMediaName" => $province_record->medianame]);
            error_log("MATCH province : $location  $province_level_media : " . $province_record->medianame);
        } elseif ($countrie_record) {
            $this->where('location', $location)->update(["advertMediaName" => $countrie_record->medianame]);
            error_log("MATCH country : $location  $countrie_level_media : " . $countrie_record->medianame);
            // $this->where('location', $location)->update(["dt_ext" => $media->dt_ext]);
            // $this->where('location', $location)->update(["mb_ext" => $media->mb_ext]);
        } else {
            $this->where('location', $location)->update(["advertMediaName" => "no advert"]);
            error_log("No Match : ");
        }

        // Refresh the venue with values in the DB
        return \Venue::find($venue->id);

    }

    public function setHiprmMediaDefaults($venue) {
            $media = new \Advertmedia(); //!!! CHANGE THIS TO advermedia
            // $media->brand_id = $venue->brand_id;
            // $media->dt_ext = "jpg";
            // $media->mb_ext = "jpg";
            // $media->bg_colour = "#00B551";
            // $media->welcome_flag = 0;
            // $media->ef_group_pos = 25;
            // $media->ef_transparency = 80;
            // $media->ef_colour = "#23E07B";
            // $media->ef_outline_text_colour = "#FFFFFF";
            // $media->zonein_gap = 30;
            // $media->zonein_btn_colour = "#00AB4F";
            // $media->zone_txt_colour = "#FFFFFF";
            // $media->faq_colour = "#FFFFFF";
            // $media->logo_choice = "white";

            return $media;
    }

    public function constructHiprmVenueRecord($medianame, $venue) {
        error_log("constructHiprmVenueRecord : venue->sitename : " . $venue->sitename);
        error_log("constructHiprmVenueRecord : venue->location : " . $venue->location);
        error_log("constructHiprmVenueRecord : venue->advertmedianame : " . $venue->advertmedianame);
        error_log("constructHiprmVenueRecord : venue->macaddress : " . $venue->macaddress);

        $record = array(
                "sitename" => $venue->sitename,
                "location" => $venue->location,
                "medianame" => $venue->advertmedianame,
                "macaddress" => $venue->macaddress,
                "dt_ext" => "n/a",
                "mb_ext" => "n/a",
                "uru" => "n/a",
            );

        return $record;
    }

/// END HIPRM ADVERT FUNCTIONS













    public function insertVenueInRadius($venue, $remotedb_id) {

        $remodtedb = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first();
        $connection = $remodtedb->dbconnection;
        error_log("Venue : insertVenueInRadius : connection : $connection");

        $media = \Media::where('location', 'like', $venue->medialocation)->first();
        if(!$media) {
            error_log("insertVenueInRadius medialocation = " . $venue->medialocation);
            $media = $this->setMediaDefaults($venue);
        }

        $record = $this->constructRadiusVenueRecord($media, $venue);

        \DB::connection($connection)->table("naslookup")->insert($record);

    }

    public function updateVenueInRadius($venue, $remotedb_id) {

        $remodtedb = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first();
        $connection = $remodtedb->dbconnection;
        $media = \Media::where('location', 'like', $venue->medialocation)->first();

        if(!$media) {
            $media = $this->setMediaDefaults($venue);
        }

        $record = $this->constructRadiusVenueRecord($media, $venue);
        \DB::connection($connection)
                ->table("naslookup")
                ->where("macaddress", "like", $venue->macaddress)
                ->update($record);
    }

    public function deleteVenueInRadius($venue, $remotedb_id) {

        $remodtedb = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first();
        $connection = $remodtedb->dbconnection;

        \DB::connection($connection)
                ->table("naslookup")
                ->where("macaddress", "like", $venue->macaddress)
                ->delete();
    }

    public function synchToRadius($remotedb_id) {

    	$remodtedb = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first();
    	$connection = $remodtedb->dbconnection;

    	// Truncate the naslookup table on radius
    	\DB::connection($connection)->table("naslookup")->truncate();

    	$venues =  \DB::table('venues')
                ->where('remotedb_id', '=', $remotedb_id)
                ->whereNull('deleted_at')
                ->get();

    	foreach($venues as $venue) {
            $this->insertVenueInRadius($venue, $remotedb_id);
    	}

    }


    public function synchOldLocationCodesFromRadius($remotedb_id) {

    	// Delete all entries for this remote db
		error_log("synchOldLocationCodesFromRadius : remotedb_id : $remotedb_id ");

    	$remodtedb = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first();
        $connection = $remodtedb->dbconnection;
		error_log("synchOldLocationCodesFromRadius : connection : $connection ");
        $sites = \DB::connection($connection)->table("naslookup")->get();
        error_log(print_r($sites, true));
        $affected = DB::table('venues')->where('remotedb_id', '=', $remotedb_id)->delete();
	    $messages = array();

        foreach($sites as $site) {

	        $oldlocation = $site->location;
			error_log("synchOldLocationCodesFromRadius : oldlocation  : $oldlocation ");

	        $isp_code = substr ( $oldlocation , 0,3 );
	        $brand_code = substr ( $oldlocation , 3,6 );
	        $venue_code = substr ( $oldlocation , 9,10 );
	        $citie_code = substr ( $oldlocation , 19,3 );
	        $countrie_code = substr ( $oldlocation , 22,2 );

	        $brand = \DB::table('brands')->select("*")->where('code', 'like', $brand_code)->first();
	        if ($brand) { $brand_id = $brand->id; } else { $brand_id = 0; };

	        $server = \DB::table('servers')->select("*")->where('remotedb_id', '=', $remotedb_id)->first();
	        if ($server) { $server_id = $server->id; } else { $server_id = 0; };

	        if($citie_code == "DBN") $citie_code = "DUR";
	        if($citie_code == "JHB") $citie_code = "JNB";
	        if($citie_code == "BLO") $citie_code = "BFN";
	        if($citie_code == "OTH") $citie_code = "PLZ";
	        if($citie_code == "PTA") $citie_code = "PTY";

	        $citie = \DB::table('cities')->select("*")->where('code', 'like', $citie_code)->first();
	        if ($citie) {
	        	$citie_id = $citie->id;
	        	$province_id = $citie->province_id;
	        } else {
	        	$citie_id = 0;
	        	$province_id = 0;
	        };

	        $province = \DB::table('provinces')->select("*")->where('id', '=', $province_id)->first();
	        if ($province) {
	        	$countrie_id = $province->countrie_id;
	        	$province_code = $province->code;
	        } else {
	        	$countrie_id = 0;
	        };

	        if($citie_id == 0 || $province_id == 0 || $countrie_id == 0 || $brand_id == 0) {

	        	array_push($messages, $oldlocation . " not added");
	        	error_log("exception : " + $site->sitename + " : $oldlocation : citie_id = $citie_id :: province_id = $province_id :: countrie_id = $countrie_id :: brand_id = $brand_id " );
	        } else {


		        $newlocation = $isp_code . $brand_code . $venue_code . $citie_code . $province_code . $countrie_code;
		        error_log("synchOldLocationCodesFromRadius : adding " . $site->sitename . " " . $newlocation);

		        // $venue = new \Venue();
		        $venue = \Venue::firstOrNew(array('location' => $newlocation));

		        $venue->sitename = $site->sitename;
		        $venue->location = $newlocation;
		        $venue->remotedb_id = $remotedb_id;
		        $venue->countrie_id = $countrie_id;
		        $venue->province_id = $province_id;
		        $venue->citie_id = $citie_id;
		        $venue->brand_id = $brand_id;
		        $venue->macaddress = $site->macaddress;
		        $venue->latitude = $site->latitude;
		        $venue->longitude = $site->longitude;
		        $venue->address = $site->address;
		        $venue->contact = $site->contact;
		        $venue->server_id = $server_id;

		        $venue->save();

	        }
        };

        return $messages;
    }

    public function getUserData(){

        $user = Auth::user();
        return $user->level_code;

    }

}
