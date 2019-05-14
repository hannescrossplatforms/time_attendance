<?php



class Statistics extends Eloquent {
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');


    public function getStatsData($sitesearch = null, $from = null, $to = null, $brand_id = null) {

        $data = array();

        $venue = new \Venue();
        $venues = $venue->getVenuesForUser(null, null, null, $brand_id);

        // $this->getBiData();

        $numvenues = 0;
        $data['totalsessionstotal'] = 0;
        $data['uniqueuserstotal'] = 0;
        $data['newuserstotal'] = 0;
        $dwelltimetotal = 0;
        $data['datausedtotal'] = 0;
        $data['venues'] = array();

        foreach($venues as $venue) {

        	if (preg_match("/$sitesearch/i", $venue->sitename)) {

            $numvenues++;

		        $remotedb_id = $venue->brand->remotedb_id;

            // error_log("getStatsData : remotedb_id = $remotedb_id ::: brand = " . $venue->brand->name);
		        $connection = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first()->dbconnection;

        		$data['venues'][$venue->sitename] = array();
        		$data['venues'][$venue->sitename]['totalsessions'] = $this->getTotalSessionsForVenue($connection, $venue, $from, $to);
        		$data['venues'][$venue->sitename]['uniqueusers'] = $this->getTotalUniqueUsersForVenue($connection, $venue, $from, $to);
        		$data['venues'][$venue->sitename]['newusers'] = $this->getTotalNewUsersForVenue($connection, $venue, $from, $to);
        		$data['venues'][$venue->sitename]['dwelltime'] = $this->getTotalDwellTimeForVenue($connection, $venue, $from, $to);
        		$data['venues'][$venue->sitename]['dataused'] = $this->getTotalDataUsedForVenue($connection, $venue, $from, $to);

            $data['totalsessionstotal'] += $data['venues'][$venue->sitename]['totalsessions'];
            $data['uniqueuserstotal'] += $data['venues'][$venue->sitename]['uniqueusers'];
            $data['newuserstotal'] += $data['venues'][$venue->sitename]['newusers'];
            $dwelltimetotal += $data['venues'][$venue->sitename]['dwelltime'];
            $data['datausedtotal'] += $data['venues'][$venue->sitename]['dataused'];

          }
        }

        if($numvenues) {
          $data['dwelltimetotalaverage'] = round($dwelltimetotal / $numvenues);
        } else {
          $data['dwelltimetotalaverage'] = 0;
        }

        // error_log("Statistics : getStatsData : sitename : totalsessionstotal : " . $data['totalsessionstotal']);
        return $data;
    }

    public function getBiData() {


        $bi = new \Bi();
        $bi->set_constants();

            // Get report
        $path = "report/usage/hipzone.net/realm";
        $list = $bi->call_api($path);

        $list = array_slice($list, 1); // Remove the first row with the headers
        //var_dump($list);
        $this->totals = array_pop($list);

        $result = array();
        $zz = 0;
        foreach ($list as $x) {
            $zz++;
            $x[6] = $x[5] - $x[4];
            // error_log($x[0] . " : " . $x[1] . " : " . $x[2] . " : " . $x[3] . " : " . $x[4] ."  " . $x[5] . " = " . $x[6]);
            $user_id = $x[0];
            array_push($result, $x);
        }
        // error_log("biActions : executeIndex : size of result : " . sizeof($result));

        if(sizeof($this->result) < 1) {
            $this->error_message = "No data returned from DataPathway";
        } else {
            $this->error_message = "";
        }
    }


    function getActiveVenuesNoMac($brandname = null, $sitename = null) {


        if(!$brandname) {
          $criterion = "%";
        } else {
          $criterion = $brandname . "%";
        }

        $activeVenues = \Venue::where('ap_active', '=', 1)
                ->where('sitename', 'like', $criterion)
                ->where('device_type', 'like', 'Mikrotik')
                ->get();

        $venues = array();
        foreach($activeVenues as $venue) {
          $sitename = preg_replace("/[ ]/", "_", $venue->sitename); 
          array_push($venues, $sitename);
        }

        return $venues;

    }

    function getActiveVenues($brandname = null, $sitename = null) {


        if(!$brandname) {
          $criterion = "%";
        } else {
          $criterion = $brandname . "%";
        }

        // error_log("getActiveVenuesgetActiveVenuesgetActiveVenuesgetActiveVenues : criterion = $criterion");

        $activeVenues = \Venue::where('ap_active', '=', 1)
                ->where('sitename', 'like', $criterion)
                ->get();
                // ->where('device_type', 'like', 'Mikrotik')

        $venues = array();
        foreach($activeVenues as $venue) {
          $sitename = preg_replace("/[ ]/", "_", $venue->sitename); 
          $macaddress = preg_replace("/[:]/", "-", $venue->macaddress); // Because dashes are used on radius
          // $macaddress = preg_replace("/[:-]/", "%", $venue->macaddress); // Macaddresses have either : or - ... reoplace with % for SQL search
          array_push($venues, $sitename);
          array_push($venues, $macaddress); // This is because some of the radacct->calledstationid records are MAC addresses
        }

        // error_log(implode(",", $venues));

        return $venues;

    }

    public function getDailyDataForAllVenues_new($connection, $from, $to, $brandname = null, $sitename = null) {

        // error_log("getDailyDataForAllVenues  : $from - $to");
        $results = array();
        $activeVenues = $this->getActiveVenues($brandname, $sitename);

        foreach($activeVenues as $activeVenue) {

          // select all records from radacct (from- to) where called_station_id like $activeVenue sort by AcctSessionTime
          $rows = \DB::connection($connection)
                 ->table("radacct")
                 ->where('AcctStartTime', '>=', $from)
                 ->where('AcctStartTime', '<=', $to)
                 ->where('CalledStationId', 'like', $activeVenue)
                 ->orderBy('AcctSessionTime')
                 ->get();

          // Get the range of records (session_count) which comprise the middle 80% (see Reports line 1616)
          $rowcount = sizeof($rows);
          
          if($rowcount > 0){

            if($rowcount > 10) {
              $tenPercent = round($rowcount * .10);
              $bottom = $tenPercent;
              $top = $rowcount - $tenPercent;

            } else {
              $tenPercent = round($rowcount * .10);
              $bottom = 0;
              $top = $rowcount - 1;              
            }

            // The number of sessions excluding the top and bottom 10 percent
            $record = array();
            $record["session_count"] = $top - $bottom; 
            $record["dwell_time"] = $record["input_data"] = $record["output_data"] = 0;
            $record["CalledStationId"] = $activeVenue;
            // $record["last_session"] = ""; // I don't think that this is needed

            // Sum up the AcctSessionTime (dwell_time) for the number of records which comprise the middle 80% 
            for($i=$bottom; $i<=$top; $i++) {
              $record["dwell_time"] = $record["dwell_time"] + $rows[$i]->AcctSessionTime;
              $record["input_data"] = $record["input_data"] + $rows[$i]->AcctInputOctets;
              $record["output_data"] = $record["output_data"] + $rows[$i]->AcctOutputOctets;
            }
            array_push($results, (object)$record);
          }
        }

        return $results;
    }


    public function getDailyDataForAllVenues($connection, $from, $to, $brandname = null, $sitename = null) {

        // error_log("getDailyDataForAllVenues  : $from - $to");

        $activeVenues = $this->getActiveVenues($brandname, $sitename);

        $rows = \DB::connection($connection)
               ->table("radacct")
               ->select(array('CalledStationId', DB::raw('count(*) as session_count'), DB::raw('sum(AcctSessionTime) as dwell_time'), 
                        DB::raw('sum(AcctInputOctets) as input_data'), DB::raw('sum(AcctOutputOctets) as output_data'), 
                        DB::raw('max(AcctStartTime) as last_session')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->wherein('CalledStationId', $activeVenues)
               ->groupBy('CalledStationId')
               ->get();


        return $rows;

    }

    public function getDailyUniqueDataForAllVenues($connection, $from, $to) {

        $activeVenues = $this->getActiveVenues();

        $rows = \DB::connection($connection)
               ->table("radacct")
               ->select(array('CalledStationId', DB::raw('count(*) as session_count'), DB::raw('sum(AcctSessionTime) as dwell_time'), 
                        DB::raw('sum(AcctInputOctets) as input_data'), DB::raw('sum(AcctOutputOctets) as output_data'), 
                        DB::raw('max(AcctStartTime) as last_session')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->wherein('CalledStationId', $activeVenues)
               ->groupBy('CalledStationId', 'username')
               ->get();

        return $rows;

    }

    
    public function getSessionCountByTimePeriodAndVenue($connection, $calledstationid, $from, $to, $beginperiod, $endperiod) {

              // error_log("getSessionCountByTimePeriodAndVenue : $connection, $calledstationid, $from, $to, $beginperiod, $endperiod");

              $data = \DB::connection($connection)
               ->table("radacct")
               ->select(array(DB::raw('count(*) as count')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->whereRaw('HOUR(acctstarttime) between "' . $beginperiod . '" AND "' . $endperiod . '"')
               ->where('CalledStationId', 'like', $calledstationid)
               ->get();

              $count = $data[0]->count;

              // error_log("getSessionCountByTimePeriodAndVenue : count = $count");

        return $count; 
    }


    public function getSessionCountByTimePeriodAndBrand($connection, $brandname, $from, $to, $beginperiod, $endperiod) {

              // error_log(" getSessionCountByDwellTimeAndVenue : $connection, $brandname, $from, $to");

              $activeVenues = $this->getActiveVenues();

              $data = \DB::connection($connection)
               ->table("radacct")
               ->select(array(DB::raw('count(*) as count')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->whereRaw('HOUR(acctstarttime) between "' . $beginperiod . '" AND "' . $endperiod . '"')
               ->where('CalledStationId', 'like', $brandname . "%")
               ->wherein('CalledStationId', $activeVenues)
               ->get();

              $count = $data[0]->count;

              $numVenues = sizeof($count);

              $averageSessionCount = $count / $numVenues;

              // error_log("getSessionCountByDwellTimeAndVenue : count = $count");

        return $averageSessionCount; 
    }


    public function getSessionCountByDwellTimeAndVenue($connection, $calledstationid, $from, $to, $minseconds, $maxseconds) {

              // error_log("getSessionCountByDwellTimeAndVenue : $connection, $calledstationid, $from, $to");

              $data = \DB::connection($connection)
               ->table("radacct")
               ->select(array(DB::raw('count(*) as count')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->where('AcctSessionTime', '>=', $minseconds)
               ->where('AcctSessionTime', '<=', $maxseconds)
               ->where('CalledStationId', 'like', $calledstationid)
               ->get();

              $count = $data[0]->count;

              // error_log("getSessionCountByDwellTimeAndVenue : count = $count");

        return $count; 
    }

    public function getSessionCountByDwellTimeAndBrand($connection, $brandname, $from, $to, $minseconds, $maxseconds) {

              // error_log("getSessionCountByDwellTimeAndVenue : $connection, $brandname, $from, $to");

              $activeVenues = $this->getActiveVenues();

              $data = \DB::connection($connection)
               ->table("radacct")
               ->select(array(DB::raw('count(*) as count')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->where('AcctSessionTime', '>=', $minseconds)
               ->where('AcctSessionTime', '<=', $maxseconds)
               ->where('CalledStationId', 'like', $brandname . "%")
               ->wherein('CalledStationId', $activeVenues)
               ->get();

              $count = $data[0]->count;

              $numVenues = sizeof($count);

              $averageSessionCount = $count / $numVenues;

              // error_log("getSessionCountByDwellTimeAndVenue : count = $count");

        return $averageSessionCount; 
    }

    public function getAvgMinutesForVenue($connection, $calledstationid, $from, $to) {

      // error_log("getMinutesForVenue : $connection, $calledstationid, $from, $to");
              $data = \DB::connection($connection)
               ->table("radacct")
               ->select(array(DB::raw('avg(AcctSessionTime) as dwelltime')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->where('CalledStationId', 'like', $calledstationid)
               ->get();

        $minutes = $data[0]->dwelltime / 60;

        return $minutes; 
    }


    public function getDataForVenue($connection, $calledstationid, $from, $to) {
                        // DB::raw('avg(AcctInputOctets) as avg_input_data'), 
                        // DB::raw('avg(AcctOutputOctets) as avg_output_data'))

              $rows = \DB::connection($connection)
               ->table("radacct")
               ->select(DB::raw('sum(AcctInputOctets) as total_input_data'), DB::raw('sum(AcctOutputOctets) as total_output_data'))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->where('CalledStationId', 'like', $calledstationid)
               ->get();

              // $returnVals = array();
              $total = ($rows[0]->total_input_data + $rows[0]->total_output_data) / 1048576;
              // $returnVals['avg'] = $rows[0]->avg_input_data + $rows[0]->avg_output_data;

        return $total;

    }

    public function getUniqueSessionsForVenue($connection, $calledstationid, $from, $to) {

      // error_log("getUniqueSessionsForVenue : $connection, $calledstationid, $from, $to");
              $data = \DB::connection($connection)
               ->table("radacct")
               ->select(array(DB::raw('count(distinct username) as unique_count')))
               ->where('AcctStartTime', '>=', $from)
               ->where('AcctStartTime', '<=', $to)
               ->where('CalledStationId', 'like', $calledstationid)
               ->get();

        return $data[0]->unique_count; 
    }

    public function getTotalSessionsForVenue($connection, $venue, $from, $to) {
        

      // error_log("statistcs : getTotalSessionsForVenue : " . $venue->sitename . " : from : $from === to : $to : ");

      $nasid = preg_replace("/ /", "_", $venue->sitename);
        $mac_dashes = preg_replace("/:/", "-", $venue->macaddress);
        $mac_colons = preg_replace("/-/", ":", $venue->macaddress);

		  $radacctRecords = \DB::connection($connection)->table("radacct")
				 ->where('ACCTSTARTTIME', '>', $from)
				 ->where('ACCTSTOPTIME', '<', $to)
				 ->where(function ($query) use ($nasid, $mac_dashes, $mac_colons) {
            		$query->where('calledstationid', 'like', $nasid)
                 		  ->orWhere('calledstationid', 'like', $mac_dashes)
                 		  ->orWhere('calledstationid', 'like', $mac_colons);
            		})
		  		 ->get();

		return count($radacctRecords);
    }


    public function getTotalUniqueUsersForVenue($connection, $venue, $from, $to) {

    	$nasid = preg_replace("/ /", "_", $venue->sitename);
        $mac_dashes = preg_replace("/:/", "-", $venue->macaddress);
        $mac_colons = preg_replace("/-/", ":", $venue->macaddress);

		    $radacctRecords = \DB::connection($connection)->table("radacct")
				 ->select('username')
				 ->distinct()
				 ->where('ACCTSTARTTIME', '>', $from)
				 ->where('ACCTSTOPTIME', '<', $to)
				 ->where(function ($query) use ($nasid, $mac_dashes, $mac_colons) {
            		$query->where('calledstationid', 'like', $nasid)
                 		  ->orWhere('calledstationid', 'like', $mac_dashes)
                 		  ->orWhere('calledstationid', 'like', $mac_colons);
            		})
		  		 ->get();

		    return count($radacctRecords);
    }

    public function getNewUsersForVenue($connection, $sitename, $from, $to) {

          $nasid = preg_replace("/ /", "_", $sitename);
          $macaddress = \Venue::where('sitename', 'like', $nasid)->first()->macaddress;
          $macforsql = preg_replace("/[:-]/", "%", $macaddress);
          $device_type = \Venue::where('sitename', 'like', $nasid)->first()->device_type;
          if($device_type == "Other") $nasid = $macforsql; // Use mac if its a non mikrotik device

          $radcheckRecords = \DB::connection($connection)->table("radacct")
             ->join('radcheck', 'radcheck.username', '=', 'radacct.username')
             ->join('secure_profile', 'radcheck.id', '=', 'secure_profile.user_id')
             ->select('radacct.username')
             ->distinct()
             ->where('radcheck.created_at', '>=', $from)
             ->where('radcheck.created_at', '<=', $to)
             ->where('radacct.calledstationid', 'like', $nasid)
             ->where('radacct.calledstationid', '<>', '')
             ->where('radacct.username', '<>', '')
             ->where('radacct.AcctStopTime', '<>', '0000-00-00 00:00:00')
             ->get();

        // foreach($radcheckRecords as $r) error_log("getNewUsersForVenue username = " . $r->username);


        $numrecords = count($radcheckRecords);
        error_log("getNewUsersForVenue sitename = " . $sitename);
        error_log("getNewUsersForVenue numrecords = " . $numrecords);
        // $numrecords = $numrecords -1;
        return $numrecords;
           // return count($radcheckRecords) - 1;

              // $data = \DB::connection($connection)
              //  ->table("secure_profile")
              //  ->select(array(DB::raw('count(*) as newusers')))
              //  ->where('created_at', '>=', $from)
              //  ->where('created_at', '<=', $to)
              //  ->where('home_venue', 'like', $sitename)
              //  ->get();

        // return $data[0]->newusers;

    }


    public function getTotalNewUsersForVenue($connection, $venue, $from, $to) {

    	$nasid = preg_replace("/ /", "_", $venue->sitename);
        $mac_dashes = preg_replace("/:/", "-", $venue->macaddress);
        $mac_colons = preg_replace("/-/", ":", $venue->macaddress);

		  $radcheckRecords = \DB::connection($connection)->table("radacct")
				 ->join('radcheck', 'radcheck.username', '=', 'radacct.username')
				 ->select('radacct.username')
				 ->distinct()
				 ->where('radcheck.created_at', '>', $from)
				 ->where('radcheck.created_at', '<', $to)
				 ->where(function ($query) use ($nasid, $mac_dashes, $mac_colons) {
            		$query->where('calledstationid', 'like', $nasid)
                 		  ->orWhere('calledstationid', 'like', $mac_dashes)
                 		  ->orWhere('calledstationid', 'like', $mac_colons);
            		})
		  		 ->get();

		return count($radcheckRecords);
    }

    public function getTotalDwellTimeForVenue($connection, $venue, $from, $to) {

    	$nasid = preg_replace("/ /", "_", $venue->sitename);
        $mac_dashes = preg_replace("/:/", "-", $venue->macaddress);
        $mac_colons = preg_replace("/-/", ":", $venue->macaddress);

		$record = \DB::connection($connection)->table("radacct")
				 ->select(DB::raw('avg(AcctSessionTime) AS dwelltime'))
				 ->where('ACCTSTARTTIME', '>', $from)
				 ->where('ACCTSTOPTIME', '<', $to)
				 ->where(function ($query) use ($nasid, $mac_dashes, $mac_colons) {
            		$query->where('calledstationid', 'like', $nasid)
                 		  ->orWhere('calledstationid', 'like', $mac_dashes)
                 		  ->orWhere('calledstationid', 'like', $mac_colons);
            		})
		  		 ->get();

  		 $dwelltime = $record["0"]->dwelltime ;

  		 if(!$dwelltime) { 
  		 	$dwelltime = 0; 
  		 } else {
  		 	$dwelltime = round($dwelltime / 60);
  		 }
  		 // error_log("getTotalDwellTimeForVenue : " . $dwelltime);

		return $dwelltime;
    }

    public function getTotalDataUsedForVenue($connection, $venue, $from, $to) {
    	$nasid = preg_replace("/ /", "_", $venue->sitename);
        $mac_dashes = preg_replace("/:/", "-", $venue->macaddress);
        $mac_colons = preg_replace("/-/", ":", $venue->macaddress);

		$record = \DB::connection($connection)->table("radacct")
				 ->select(DB::raw('sum(AcctInputOctets+AcctOutputOctets) AS data'))
				 ->where('ACCTSTARTTIME', '>', $from)
				 ->where('ACCTSTOPTIME', '<', $to)
				 ->where(function ($query) use ($nasid, $mac_dashes, $mac_colons) {
            		$query->where('calledstationid', 'like', $nasid)
                 		  ->orWhere('calledstationid', 'like', $mac_dashes)
                 		  ->orWhere('calledstationid', 'like', $mac_colons);
            		})
		  		 ->get();

  		 $data = $record["0"]->data ;

  		 if(!$data) { 
  		 	$data = 0; 
  		 } else {
  		 	$data = round($data / 1048576);
  		 }
  		 // error_log("getTotalDataUsedForVenue : " . $data);

		return $data;
    }

}