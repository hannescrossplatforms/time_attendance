<?php



class Reports extends Eloquent {

    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    public function generateDailyData($daysback) {
      DB::table('repdailytotals')->delete();
      $stats = new \Statistics();

      // $daysback = 100; //TESTING
      $date = new DateTime(date('Y-m-d'));
      $today =  $date->format('Y-m-d');
      // $date->sub(new DateInterval('P60D')); // TESTING

      $allvenues = \Venue::all();
      $allnasids = array();
      foreach($allvenues as $venue) {
        $thisnasid = preg_replace("/ /", "_", $venue->sitename);
        array_push($allnasids, $thisnasid);
      }

      $remotedbs = \DB::table('remotedbs')->get();
      foreach($remotedbs as $remotedb) {
        error_log("Reports : generateDailyData : remotedb connection = " . $remotedb->dbconnection);
          $connection = $remotedb->dbconnection;

          $date = new DateTime(date('Y-m-d'));
          for($day = $daysback; $day > 0; $day--) {
            $dayAfter =  $date->format('Y-m-d');
            $date->sub(new DateInterval('P1D'));
            $targetDate = $date->format('Y-m-d');

            $venueSessionCounts = $stats->getDailyDataForAllVenues($connection, $targetDate, $dayAfter);
            foreach($venueSessionCounts as $venueSessionCount) {

              $nasid = $this->resolveNasid($venueSessionCount->CalledStationId);
              $brandcode = $this->getBrandcodeFromNasid($nasid);
              $session_count = $venueSessionCount->session_count;
              // $dwell_time = round($venueSessionCount->dwell_time); // Testing storing seconds for 10 percentile
              $dwell_time = round($venueSessionCount->dwell_time / 60);
              $data = ($venueSessionCount->input_data + $venueSessionCount->output_data) / 1048576;

              if(in_array($nasid, $allnasids)) {
                DB::table('repdailytotals')->insert(array( "nasid" => $nasid, "brandcode" => $brandcode,
                  "sessions" => $session_count, "dwelltime" => $dwell_time, "data" => $data, "date" => $targetDate ));
              }
            }
          }
        }
    }

    public function getBrandcodeFromNasid($nasid) {

      $brandname = preg_replace("/(^.*)(_)(.*$)/", "$1", $nasid);

      $brand = DB::table("brands")->where('name', 'like', $brandname . "%")->first();
      // print "------ getBrandcodeFromNasid : $nasid ::: $brandname ------" ;

      if($brand) {
        return $brand->code;
      }
      else {
        return "XXXXXX";
      }
    }

    public function initializeVenueDataTable($tablename, $brandname = null) {

        DB::table($tablename)->delete();

        // $stats = new \Statistics();
        // $venues = $stats->getActiveVenues($brandname);


        if(!$brandname) {
          $criterion = "%";
        } else {

          $criterion = $brandname . "%";
          // error_log("initializeVenueDataTable criterion = $criterion");
        }

        $venues = \Venue::where('ap_active', '=', 1)
                ->where('sitename', 'like', $criterion)
                ->get();
                // ->where('device_type', 'like', 'Mikrotik')


        foreach($venues as $venue) {
          $nasid = preg_replace("/ /", "_", $venue->sitename);
          // print($nasid . "<br>");

          $brand = \Brand::find($venue->brand_id);
          if($brand) {
            $brandcode = $brand->code;
          } else {
            $brandcode = 0;
          }
          $record = array( "nasid" => $nasid, "sitename" => $venue->sitename, "brandcode" => $brandcode );
          DB::table($tablename)->insert($record);
        }

        // dd("initializeVenueDataTable");

    }

    public function getBeginEndDates($reportperiod, $from, $to) {

        if($reportperiod == "rep7day") {

            $date = new \DateTime(date('Y-m-d'));
            $endcurrent =  $date->format('Y-m-d');

            $date->sub(new \DateInterval('P7D')); // TESTING
            $begincurrent = $date->format('Y-m-d');

            $date->sub(new \DateInterval('P1D')); // TESTING
            $endprevious = $date->format('Y-m-d');

            $date->sub(new \DateInterval('P7D')); // TESTING
            $beginprevious = $date->format('Y-m-d');

        } elseif($reportperiod == "repthismonth") {

            $date = new \DateTime(date('Y-m-d'));
            $endcurrent =  $date->format('Y-m-d');

            $date->modify('first day of this month');
            $begincurrent = $date->format('Y-m-d');

            $date = new \DateTime(date('Y-m-d'));
            $date->sub(new \DateInterval('P1M'));
            $endprevious =  $date->format('Y-m-d');

            $date->modify('first day of this month');
            $beginprevious = $date->format('Y-m-d');

        } elseif($reportperiod == "replastmonth") {

            $date = new \DateTime('first day of this month');

            $date->sub(new \DateInterval('P1D'));
            $endcurrent =  $date->format('Y-m-d');

            $date->modify('first day of this month');
            $begincurrent = $date->format('Y-m-d');

            $date->sub(new \DateInterval('P1D'));
            $endprevious = $date->format('Y-m-d');

            $date->modify('first day of this month');
            $beginprevious = $date->format('Y-m-d');

        } else { // Must be a date range

            $datetimeto = new DateTime($to);
            $date = $datetimefrom = new DateTime($from);

            $endcurrent =  $to;
            $begincurrent =  $from;

            $interval = $datetimefrom->diff($datetimeto)->format('%a') + 1;

            $date->sub(new \DateInterval('P1D'));
            $endprevious = $date->format('Y-m-d');

            $date->sub(new \DateInterval('P' . $interval . 'D'));
            $beginprevious = $date->format('Y-m-d');

        }

        $dates = array();
        $dates["begincurrent"] = $begincurrent;
        $dates["endcurrent"] = $endcurrent;
        $dates["beginprevious"] = $beginprevious;
        $dates["endprevious"] = $endprevious;

        return $dates;
    }

    public function agregatedVenueDataSub ($connection, $table, $begincurrent, $endcurrent, $beginprevious, $endprevious, $brandname = null) {

                  // Set the session counts
            $this->setSessionCounts($connection, $table, $begincurrent, $endcurrent, $beginprevious, $endprevious, $brandname);

            // Cycle through all the venues
            $rows = DB::table($table)->get();
            foreach($rows as $row) {

              if($row->calledstationid) {
                // Calculate and set the differences in session count between the two periods
                $this->setDiffAndPercentSessions($table, $row);

                $this->setAllVenueData($connection, $table, $row,  $begincurrent, $endcurrent, $beginprevious, $endprevious);
              }

            }

    }
    public function generateAggregatedVenueData($reportperiod, $from = null, $to = null, $brandname = null, $sitename = null) {

        $dates = $this->getBeginEndDates($reportperiod, $from, $to);
        $begincurrent = $dates["begincurrent"];
        $endcurrent = $dates["endcurrent"];
        $beginprevious = $dates["beginprevious"];
        $endprevious = $dates["endprevious"];

        if($reportperiod == "daterange") {
          $table = "tmp_period_" . $brandname . "_" . $from . "_" . $to ;
        } else {
          $table = $reportperiod;
        }

        $this->initializeVenueDataTable($table, $brandname);

        error_log("Reports : generateAggregatedVenueData : ========= : reportperiod = $reportperiod");
        error_log("Reports : generateAggregatedVenueData : endcurrent = $endcurrent");
        error_log("Reports : generateAggregatedVenueData : begincurrent = $begincurrent");
        error_log("Reports : generateAggregatedVenueData : endprevious = $endprevious");
        error_log("Reports : generateAggregatedVenueData : beginprevious = $beginprevious");
        error_log("Reports : generateAggregatedVenueData : brandname = $brandname");

        $stats = new \Statistics();

        ////// FIX FIX FIX
        ////// Loop through the venues and get the remotedb for each venue
        //////

        if($brandname) {

          $brand = \Brand::where('name', 'like', $brandname . "%")->first();
          $remotedb_id = $brand->remotedb_id;
          $connection = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first()->dbconnection;
          $this->agregatedVenueDataSub ($connection, $table, $begincurrent, $endcurrent, $beginprevious, $endprevious, $brandname);

        } else { // No brand defined so its for all brands

          $remotedbs = \DB::table('remotedbs')->get();
          foreach($remotedbs as $remotedb) {
            $remotedb_id = $remotedb->id;
            $connection = \DB::table('remotedbs')->select("*")->where('id', '=', $remotedb_id)->first()->dbconnection;
            $this->agregatedVenueDataSub ($connection, $table, $begincurrent, $endcurrent, $beginprevious, $endprevious, $brandname);
          }
        }


            // error_log("getNewUsersForVenue xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx connection = $connection");


    }

   public function generateAggregatedVenueData_backup($reportperiod, $from = null, $to = null, $brandname = null, $sitename = null) {

        $dates = $this->getBeginEndDates($reportperiod, $from, $to);
        $begincurrent = $dates["begincurrent"];
        $endcurrent = $dates["endcurrent"];
        $beginprevious = $dates["beginprevious"];
        $endprevious = $dates["endprevious"];

        if($reportperiod == "daterange") {
          $table = "tmp_period_" . $brandname . "_" . $from . "_" . $to ;
        } else {
          $table = $reportperiod;
        }

        $this->initializeVenueDataTable($table, $brandname);

        error_log("Reports : generateAggregatedVenueData : ========= : reportperiod = $reportperiod");
        error_log("Reports : generateAggregatedVenueData : endcurrent = $endcurrent");
        error_log("Reports : generateAggregatedVenueData : begincurrent = $begincurrent");
        error_log("Reports : generateAggregatedVenueData : endprevious = $endprevious");
        error_log("Reports : generateAggregatedVenueData : beginprevious = $beginprevious");
        error_log("Reports : generateAggregatedVenueData : brandname = $brandname");

        $stats = new \Statistics();

        ////// FIX FIX FIX
        ////// Loop through the venues and get the remotedb for each venue
        //////

        $remotedbs = \DB::table('remotedbs')->get();
        foreach($remotedbs as $remotedb) {
            // $connection = "radius_hipspot";
            $connection = $remotedb->dbconnection;

            // Set the session counts
            $this->setSessionCounts($connection, $table, $begincurrent, $endcurrent, $beginprevious, $endprevious, $brandname);

            // Cycle through all the venues
            $rows = DB::table($table)->get();
            foreach($rows as $row) {

              if($row->calledstationid) {
                // Calculate and set the differences in session count between the two periods
                $this->setDiffAndPercentSessions($table, $row);
        // error_log("getNewUsersForVenue YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY");

                // $this->setAllVenueData($connection, $table, $row,  $begincurrent, $endcurrent, $beginprevious, $endprevious);
              }

            }
        }
    }

    public function setAllVenueData($connection, $table, $row,  $begincurrent, $endcurrent, $beginprevious, $endprevious) {
      $stats = new \Statistics();

      // Calculate and set all four columns for the unique sessions
      // error_log("Reports : generateAggregatedVenueData : $previousunique, $currentunique ----- " . $row->calledstationid);
      $previousunique = $stats->getUniqueSessionsForVenue($connection, $row->calledstationid, $beginprevious, $endprevious);
      $currentunique = $stats->getUniqueSessionsForVenue($connection, $row->calledstationid, $begincurrent, $endcurrent);
      if($previousunique || $currentunique) {
        $this->setUniqueData($table, $row, $previousunique, $currentunique);
      }

      // Calculate and set all four columns for the dwell time
      $previousminutes = $stats->getAvgMinutesForVenue($connection, $row->calledstationid, $beginprevious, $endprevious);
      $currentminutes = $stats->getAvgMinutesForVenue($connection, $row->calledstationid, $begincurrent, $endcurrent);
      if($previousminutes || $currentminutes) {
        $this->setMinutesData($table, $row, $previousminutes, $currentminutes);
      }

      // Calculate and set all four columns for the new users
      $previousnewusers = $stats->getNewUsersForVenue($connection, $row->sitename, $beginprevious, $endprevious);
      $currentnewusers = $stats->getNewUsersForVenue($connection, $row->sitename, $begincurrent, $endcurrent);
        // error_log("getNewUsersForVenue =========================== currentnewusers = " . $currentnewusers);
      if($previousnewusers || $currentnewusers) {
        $this->setNewUsersData($table, $row, $previousnewusers, $currentnewusers);
      }

      // Calculate and set all four columns for the data
      $previousdata = $stats->getDataForVenue($connection, $row->calledstationid, $beginprevious, $endprevious);
      $currentdata = $stats->getDataForVenue($connection, $row->calledstationid, $begincurrent, $endcurrent);
      if($previousdata || $currentdata) {
        $this->setDataData($table, $row, $previousdata, $currentdata);
      }
    }

    public function setSessionCounts($connection, $table, $begincurrent, $endcurrent, $beginprevious, $endprevious, $brandname = null, $sitename = null) {

      // error_log("setSessionCounts asasasasas : brandname = $brandname");
      $stats = new \Statistics();

      // Set the current session counts
      $currentVenueSessionCounts = $stats->getDailyDataForAllVenues($connection, $begincurrent, $endcurrent, $brandname, $sitename);
      foreach($currentVenueSessionCounts as $row) {
           $nasid = $this->resolveNasid($row->CalledStationId);
           if($nasid) {
              $record = array( "CalledStationId" => $row->CalledStationId, "currentsessions" => $row->session_count );
              DB::table($table)
              ->where('nasid', 'like', $nasid)
              ->update($record);
           }
      }

      // Set the previous session counts
      $previousVenueSessionCounts = $stats->getDailyDataForAllVenues($connection, $beginprevious, $endprevious, $brandname, $sitename);
      foreach($previousVenueSessionCounts as $row) {
           $nasid = $this->resolveNasid($row->CalledStationId);
           if($nasid) {
              $record = array("CalledStationId" => $row->CalledStationId,  "previoussessions" => $row->session_count );
              DB::table($table)
              ->where('nasid', 'like', $nasid)
              ->update($record);
           }
      }

    }

    public function setNewUsersData($table, $row, $previousnewusers, $currentnewusers) {

        $diffnewusers = $currentnewusers - $previousnewusers;

        if($currentnewusers == 0 || $previousnewusers == 0 ){
          $percentnewusers = $diffnewusers ;
        } else {
          $percentnewusers = 100 * $diffnewusers / $previousnewusers;
        }
        $percentnewusers = round($percentnewusers);

        // error_log("setNewUsersData : ddd : $previousnewusers === $currentnewusers ::::" . $row->nasid);

        $record = array(  "previousnewusers" => $previousnewusers,
                          "currentnewusers" => $currentnewusers,
                          "diffnewusers" => $diffnewusers,
                          "percentnewusers" => $percentnewusers
                          );

        DB::table($table)->where('nasid', 'like', $row->nasid)->update($record);
    }

    public function setMinutesData($table, $row, $previousminutes, $currentminutes) {

        $diffminutes = $currentminutes - $previousminutes;

        if($currentminutes == 0 || $previousminutes == 0 ){
          $percentminutes = $diffminutes ;
        } else {
          $percentminutes = 100 * $diffminutes / $previousminutes;
        }
        $percentminutes = round($percentminutes);

        $record = array(  "previousminutes" => $previousminutes,
                          "currentminutes" => $currentminutes,
                          "diffminutes" => $diffminutes,
                          "percentminutes" => $percentminutes
                          );

        DB::table($table)->where('nasid', 'like', $row->nasid)->update($record);
    }

    public function setUniqueData($table, $row, $previousunique, $currentunique) {

        $diffunique = $currentunique - $previousunique;

        if($currentunique == 0 || $previousunique == 0 ){
          $percentunique = $diffunique ;
        } else {
          $percentunique = 100 * $diffunique / $previousunique;
        }
        $percentunique = round($percentunique);

        $record = array(  "previousunique" => $previousunique,
                          "currentunique" => $currentunique,
                          "diffunique" => $diffunique,
                          "percentunique" => $percentunique
                          );

        DB::table($table)->where('nasid', 'like', $row->nasid)->update($record);
    }

    public function setDataData($table, $row, $previousdata, $currentdata) {

        // error_log("setDataData : $previousdata :::: $currentdata");

        $diffdata = $currentdata - $previousdata;

        if($currentdata == 0 || $previousdata == 0 ){
          $percentdata = $diffdata ;
        } else {
          $percentdata = 100 * $diffdata / $previousdata;
        }
        $percentdata = round($percentdata);

        $record = array(  "previousdata" => $previousdata,
                          "currentdata" => $currentdata,
                          "diffdata" => $diffdata,
                          "percentdata" => $percentdata
                          );

        DB::table($table)->where('nasid', 'like', $row->nasid)->update($record);
    }

    public function setDiffAndPercentSessions($table, $row) {
        // Calculate the differences in session count between the two periods
        $diffsessions = $row->currentsessions - $row->previoussessions;
        $record = array( "diffsessions" => $diffsessions );
        DB::table($table)
          ->where('nasid', 'like', $row->nasid)
          ->update($record);

        // Calculate the percentage differences between the two periods
        if($row->currentsessions == 0 || $row->previoussessions == 0 ){
          $percentsessions = $diffsessions;
        } else {
          $percentsessions = 100 * (($row->currentsessions - $row->previoussessions) / $row->previoussessions);
        }
        $percentsessions = round($percentsessions);

        $record = array( "percentsessions" => $percentsessions );
        DB::table($table)
          ->where('nasid', 'like', $row->nasid)
          ->update($record);
    }


    // This is needed as the calledstationid is sometime the venue sitename and sometimes the mac address
    // This resolves it to the sitename
    public function resolveNasid($CalledStationId) {

      // error_log("resolveNasid : CalledStationId = $CalledStationId");

      $isMacAddress =  ( preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $CalledStationId) );

      if($isMacAddress) {

        $macforsql = preg_replace("/[:-]/", "%", $CalledStationId);

        // $venue = \Venue::where("macaddress", "like", $macforsql)->withTrashed()->first();
        $venue = \Venue::where("macaddress", "like", $macforsql)->first();

        if($venue) {
          $nasid = preg_replace("/ /", "_", $venue->sitename);
        } else {
          $nasid = 0;
        }

        return $nasid;

      } else {

        return $CalledStationId;

      }
    }



    public function getListCustomerUsageFordownload($brandname, $reportperiod, $from, $to, $connection) {

        error_log("getListCustomerUsageFordownload brandname : $brandname ---- reportperiod : $reportperiod");
        $stats = new \Statistics();

        $dates = $this->getBeginEndDates($reportperiod, $from, $to);
        $begin = $dates["begincurrent"] . " 00:00:00";
        $end = $dates["endcurrent"] . " 23:59:59";

        $activeVenues = $stats->getActiveVenues($brandname);

        $rows = \DB::connection($connection)
           ->table("radacct")
           ->join('radcheck', 'radacct.username', '=', 'radcheck.username')
           ->join('secure_profile', 'secure_profile.user_id', '=', 'radcheck.id')
           ->where('AcctStartTime', '>', $begin)
           ->where('AcctStartTime', '<=', $end)
           ->wherein('CalledStationId', $activeVenues)
           ->get();
           // ->where('secure_profile.home_venue', 'like', $brandname . "%")

        // error_log("getListCustomerUsageFordownload : rows " . print_r($rows, true));
        // error_log("getListCustomerUsageFordownload : rows " . sizeof($rows));

        $relativepath = "/userdata/" . $brandname . "_" . $dates["begincurrent"] . "_" . $dates["endcurrent"] . ".csv";
        $fullpath = public_path() . $relativepath;
        error_log("getListCustomerUsageFordownload fullpath : $fullpath");

        $userdatafile = fopen($fullpath, "w") or die("Unable to open file!");
        $contents = "User Name,Login Date/Time,Logout Date/Time,Login Venue\n";
        fwrite($userdatafile, $contents);
        foreach($rows as $row) {
          $contents = $row->UserName . "," . $row->AcctStartTime . "," . $row->AcctStopTime ."," . $row->CalledStationId . "\n";
          fwrite($userdatafile, $contents);
        }
        fclose($userdatafile);

        error_log("getListCustomerUsageFordownload relativepath : $relativepath");
        return $relativepath;
    }

    public function getUserProfileDataForDownload($brandname, $reportperiod, $from, $to, $connection) {

        error_log("getUserProfileDataForDownload brandname : $brandname ---- reportperiod : $reportperiod");

        $dates = $this->getBeginEndDates($reportperiod, $from, $to);
        $begin = $dates["begincurrent"] . " 00:00:00";
        $end = $dates["endcurrent"] . " 00:00:00";

        $rows = \DB::connection($connection)
           ->table("secure_profile")
           ->where('created_at', '>', $begin)
           ->where('created_at', '<', $end)
           ->where('home_venue', 'like', $brandname . "%")
           ->get();

        $relativepath = "/userdata/" . $brandname . "_" . $dates["begincurrent"] . "_" . $dates["endcurrent"] . ".csv";
        $fullpath = public_path() . $relativepath;
        error_log("getUserProfileDataForDownload fullpath : $fullpath");

        $userdatafile = fopen($fullpath, "w") or die("Unable to open file!");
        $contents = "user_id,cellphone,email_address,Home Venue, created_at\n";
        fwrite($userdatafile, $contents);
        foreach($rows as $row) {
          $contents = $row->user_id . "," . $row->cellphone . "," . $row->email_address . "," . $row->home_venue . "," . $row->created_at . "\n";
          fwrite($userdatafile, $contents);
        }
        fclose($userdatafile);

        return $relativepath;
    }

    public function stripOutBrandFromGraphLabels($data) {

        foreach($data as $record) {
          $record->label = preg_replace("/(.*) (.*$)/", "$2", $record->label);
        }

        return $data;
    }

    public function gethighest5Sessions($brandcode, $reportperiod, $brandCodesArray = null) {

        error_log("gethighest5Sessions brandcode : $brandcode ---- reportperiod : $reportperiod");
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();


        if ($brandCodesArray != null) {

          // SELECT * from fiberbox where field REGEXP '1740|1938|1940';
          // $data['brands'] = \Brand::whereRaw('parent_brand = 165 OR id = 165')->get();

          if (\User::isVicinity()) {
            $data = array();
          } else {
            $data = DB::table($reportperiod)
            ->selectRaw('sitename as label, currentsessions as value')
            ->where(function ($query) use($brandCodesArray) {
                           for ($i = 0; $i < count($brandCodesArray); $i++){
                              $query->orwhere('brandcode', 'like',  '%' . $brandCodesArray[$i] .'%');
                           }
                      })
            ->wherein('nasid', $activeVenues)
            ->orderby("currentsessions", "desc")
            ->limit(5)
            ->get();
          }
          

        }
        else {
          
          if (\User::isVicinity()) {
            $data = array();
          } else {
            $data = DB::table($reportperiod)
            ->selectRaw('sitename as label, currentsessions as value')
            ->where('brandcode', 'like', $brandcode)
            ->wherein('nasid', $activeVenues)
            ->orderby("currentsessions", "desc")
            ->limit(5)
            ->get();
          }
          
        
        }



        error_log("gethighest5Sessions : data = " . print_r($data, true));

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Highest 5 Session Count",
            'paletteColors' => "#70ad47",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function gethighest5Uniquedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
              ->selectRaw('sitename as label, currentunique as value')
              ->where('brandcode', 'like', $brandcode)
              ->wherein('nasid', $activeVenues)
              ->orderby("currentunique", "desc")
              ->limit(5)
              ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Highest 5 Unique Count",
            'paletteColors' => "#70ad47",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function gethighest5AvgTimedata($brandcode, $reportperiod) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
              ->selectRaw('sitename as label, currentminutes as value')
              ->where('brandcode', 'like', $brandcode)
              ->wherein('nasid', $activeVenues)
              ->orderby("currentminutes", "desc")
              ->limit(5)
              ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Highest 5 Avg. Dwell Time (Minutes)",
            'paletteColors' => "#70ad47",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getlowest5Sessionsdata($brandcode, $reportperiod, $brandCodesArray = null) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        if ($brandCodesArray != null) {

          if (\User::isVicinity()) {
            $data = array();
          } else {
            $data = DB::table($reportperiod)
            ->selectRaw('sitename as label, currentsessions as value')
            ->where(function ($query) use($brandCodesArray) {
                           for ($i = 0; $i < count($brandCodesArray); $i++){
                              $query->orwhere('brandcode', 'like',  '%' . $brandCodesArray[$i] .'%');
                           }
                      })
            ->wherein('nasid', $activeVenues)
            ->orderby("currentsessions", "asc")
            ->limit(5)
            ->get();
          }
        }
        else {
          if (\User::isVicinity()) {
            $data = array();
          } else {
            $data = DB::table($reportperiod)
          ->selectRaw('sitename as label, currentsessions as value')
          ->where('brandcode', 'like', $brandcode)
          ->wherein('nasid', $activeVenues)
          ->orderby("currentsessions", "asc")
          ->limit(5)
          ->get();
          }
        
        }

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Lowest 5 Session Count",
            'paletteColors' => "#5b9bd5",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getlowest5Uniquedata($brandcode, $reportperiod) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
              ->selectRaw('sitename as label, currentunique as value')
              ->where('brandcode', 'like', $brandcode)
              ->wherein('nasid', $activeVenues)
              ->orderby("currentunique", "asc")
              ->limit(5)
              ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Lowest 5 Unique Count",
            'paletteColors' => "#5b9bd5",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getlowest5AvgTimedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
              ->selectRaw('sitename as label, currentminutes as value')
              ->where('brandcode', 'like', $brandcode)
              ->wherein('nasid', $activeVenues)
              ->orderby("currentminutes", "asc")
              ->limit(5)
              ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Lowest 5 Avg. Dwell Time (Minutes)",
            'paletteColors' => "#5b9bd5",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getbiggestSessionIncreasedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
          ->selectRaw('sitename as label, percentsessions as value')
          ->where('brandcode', 'like', $brandcode)
          ->wherein('nasid', $activeVenues)
          ->orderby("percentsessions", "desc")
          ->limit(5)
          ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Biggest Session Count Increase",
            'paletteColors' => "#70ad47",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'numberSuffix' => "%",
            'theme' => "zune",
            'showPercentValues' => "1"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getbiggestUniquesIncreasedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
              ->selectRaw('sitename as label, percentunique as value')
              ->where('brandcode', 'like', $brandcode)
              ->wherein('nasid', $activeVenues)
              ->orderby("percentunique", "desc")
              ->limit(5)
              ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Biggest Uniques % Increase",
            'paletteColors' => "#70ad47",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'numberSuffix' => "%",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getbiggestAdminDropdata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $chartData = array(
          'chart' => array(
            'subCaption' => "Biggest Admin Use Drop (Gb)",
            'paletteColors' => "#e6e6e6",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'numberSuffix' => "%",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => array(
            array( "label" => "", "" => ""),
            array( "label" => "", "" => ""),
            array( "label" => "", "" => ""),
            array( "label" => "", "" => ""),
            array( "label" => "", "" => "")
            )
        );

        return $chartData;
    }

    public function getbiggestSessionDecreasedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
          ->selectRaw('sitename as label, percentsessions as value')
          ->where('brandcode', 'like', $brandcode)
          ->wherein('nasid', $activeVenues)
          ->orderby("percentsessions", "asc")
          ->limit(5)
          ->get();


        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Biggest Session Count Decrease",
            'paletteColors' => "#5b9bd5",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'numberSuffix' => "%",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getbiggestUniquesDecreasedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = DB::table($reportperiod)
              ->selectRaw('sitename as label, percentunique as value')
              ->where('brandcode', 'like', $brandcode)
              ->wherein('nasid', $activeVenues)
              ->orderby("percentunique", "asc")
              ->limit(5)
              ->get();

        $data = $this->stripOutBrandFromGraphLabels($data);

        $chartData = array(
          'chart' => array(
            'subCaption' => "Biggest Uniques % Decrease",
            'paletteColors' => "#5b9bd5",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'numberSuffix' => "%",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => $data
        );

        return $chartData;
    }

    public function getbiggestAdminIncreasedata($brandcode, $reportperiod) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $chartData = array(
          'chart' => array(
            'subCaption' => "Biggest Admin Use Increase (Gb)",
            'paletteColors' => "#e6e6e6",
            'showYAxisValues' => "0",
            'rotatelabels' => "1",
            'slantlabels' => "1",
            'theme' => "zune"
          ),
          'data' => array(
            array( "label" => "", "" => ""),
            array( "label" => "", "" => ""),
            array( "label" => "", "" => ""),
            array( "label" => "", "" => ""),
            array( "label" => "", "" => "")
            )
        );

        return $chartData;
    }

    public function getTotalSessionCountByDate($from, $to, $brands= null) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $rows = \DB::table("repdailytotals")
               ->selectRaw('date as label, sum(sessions) as value')
               ->wherein('nasid', $activeVenues)
               ->where('date', '>=', $from)
               ->where('date', '<=', $to) // Add where brand in $brand
               ->groupBy('date')
               ->get();

        return $rows;
    }

    public function getTotalDwelltimeCountByDate($from, $to, $brandcode) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $rows = \DB::table("repdailytotals")
               ->selectRaw('date as label, sum(dwelltime) as value')
               ->where('brandcode', 'like', $brandcode)
               ->wherein('nasid', $activeVenues)
               ->where('date', '>=', $from)
               ->where('date', '<=', $to) // Add where brand in $brand
               ->groupBy('date')
               ->get();

        return $rows;
    }

    public function gettotalDwellTimedata($from, $to, $brandcode) {

      error_log("gettotalDwellTimedata from : $from ==== to : $to");

      $data = $this->getTotalDwelltimeCountByDate($from, $to, $brandcode);

      $chartData = array(
        'chart' => array(
          'caption' => "",
          'subCaption' => "",
          'paletteColors' => "#5b9bd5",
          'showYAxisValues' => "0",
          'rotatelabels' => "1",
          'slantlabels' => "1",
          'theme' => "zune"
        ),
        'data' => $data
      );

        return $chartData;
    }

    ////////////////////// VENUE REPORTS ////////////////////////////


    public function getStoreDwellTime($from, $to, $nasid, $brandcodes, $brandonly = null) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();
        // error_log("Parameters for getStoreDwellTime : $nasid ::: $from  ::: $to");

        $data = array();
        $data["avg"] = "";
        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(dwelltime) as total')
               ->where('nasid', 'like', '%' . $nasid . '%')
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->where('dwelltime', '>', 0)
               ->get();
        $data["total"] = $rows[0]->total;

        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(dwelltime) as total, nasid')
               ->wherein('brandcode', $brandcodes)
               ->wherein('nasid', $activeVenues)
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->groupby('nasid')
               ->get();

        $combinedTotal = 0;
        foreach($rows as $row) {
            $combinedTotal = $combinedTotal + $row->total;
        }
        $venueCount = sizeof($rows);
        if($venueCount) {
          if($brandonly) {
            $brandAverage = $combinedTotal;
          } else {
            $brandAverage = $combinedTotal / $venueCount;
          }
          $data["avg"] = $brandAverage;
        } else {
        }


        // error_log("getStoreDwellTime : totaaaaal = " . $data["total"]);
        // error_log("getStoreDwellTime : avg = " . $data["avg"]);

        // $jsonrows = json_encode($rows);
        // error_log("getStoreDwellTime : jason :  $jsonrows");

        return $data;
    }

    public function getTotalWifiSessions($from, $to, $nasid, $brandcodes, $brandonly = null) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();
        // error_log("Parameters for getTotalWifiSessions : $nasid ::: $from  ::: $to");


        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(sessions) as total')
               ->where('nasid', 'like', '%' . $nasid . '%')
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->get();
        $data["total"] = $rows[0]->total;
        // error_log("Parameters for getTotalWifiSessions : total : " . $rows[0]->total);

        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(sessions) as total, nasid')
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->wherein('brandcode', $brandcodes)
               ->wherein('nasid', $activeVenues)
               ->groupby('nasid')
               ->get();

        // error_log("Parameters for getTotalWifiSessions : rows : " . print_r($rows, true));

        $combinedTotal = 0;
        foreach($rows as $row) {
            $combinedTotal = $combinedTotal + $row->total;
        }
        $venueCount = sizeof($rows);
        // error_log("Parameters for getTotalWifiSessions : venueCount : $venueCount");

        if($venueCount) {

          if($brandonly) {
            $data["avg"] = $combinedTotal;
          } else {
            $data["avg"] = $combinedTotal / $venueCount;
          }

        } else {
          $data["avg"] = 0;
        }

        return $data;
    }

    public function getWifiDataTotal($from, $to, $nasid, $brandcodes, $brandonly = null) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(data) as total, avg(data) as avg')
               ->where('nasid', 'like', '%' . $nasid . '%')
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->get();

        $data["total"] = $rows[0]->total / 1024;

        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(data) as total, nasid')
               ->wherein('brandcode', $brandcodes)
               ->wherein('nasid', $activeVenues)
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->groupby('nasid')
               ->get();

        $combinedTotal = 0;
        foreach($rows as $row) {
            $combinedTotal = $combinedTotal + $row->total;
        }
        $venueCount = sizeof($rows);
        if($venueCount) {

          if($brandonly) {
            $data["avg"] = $combinedTotal / 1024;
          } else {
            $brandAverage = $combinedTotal / $venueCount;
            $data["avg"] = $brandAverage / 1024;
          }

        } else {
          $data["avg"] = 0;
        }

        return $data;;
    }

    public function getNumberOfPeople($reportperiod, $from, $to, $nasid, $brandcodes, $brandonly = null) {

        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = array();

        $rows = DB::table($reportperiod)
          ->selectRaw('currentunique as total')
          ->where('nasid', 'like', '%' . $nasid . '%')
          ->get();

        if(sizeof($rows) > 0) {
          $data['total'] = $rows[0]->total;
        } else {
          $data['total'] = 0;
        }

        // error_log("getNumberOfPeople : " . $data['total']);

        if($brandonly) {
          $selectText = 'sum(currentunique) as avg';
        } else {
          $selectText = 'avg(currentunique) as avg';
        }

        $rows = DB::table($reportperiod)
          ->selectRaw($selectText)
               ->wherein('brandcode', $brandcodes)
               ->wherein('nasid', $activeVenues)
          ->get();
        $data['avg'] = $rows[0]->avg;

        return $data;
    }

    public function getFirstTimeUsers($reportperiod, $from, $to, $nasid, $brandcodes, $brandonly = null) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();


        
        $venue = \Venue::whereRaw("LOWER(location) LIKE '%".strtolower($nasid)."%'")->get()->first();
        // $venue = \Venue::where('sitename', 'like', $nasid)->first();

        if ($venue) {
          $macaddress = $venue->macaddress;

          $data = array();

          $rows = DB::table($reportperiod)
            ->selectRaw('currentnewusers as total')
            ->where('nasid', 'like', '%' . $nasid . '%')
            ->orwhere('nasid', 'like', '%' . $macaddress . '%')
            ->get();

          if(sizeof($rows) > 0) {
            $data['total'] = $rows[0]->total;
          } else {
            $data['total'] = 0;
          }

          if($brandonly) {
            $selectText = 'sum(currentnewusers) as avg';
          } else {
            $selectText = 'avg(currentnewusers) as avg';
          }

          $rows = DB::table($reportperiod)
            ->selectRaw($selectText)
            ->wherein('brandcode', $brandcodes)
            ->wherein('nasid', $activeVenues)
            ->get();
          $data['avg'] = $rows[0]->avg;

          // error_log("getFirstTimeUsers : avg = " . $data['avg']);

          return $data;
        }

    }

    public function getAvgDataPerSession($reportperiod, $from, $to, $nasid, $brandcodes) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = array();

        $rows = DB::table($reportperiod)
          ->selectRaw('currentsessions as sessions, currentdata as data')
          ->where('nasid', 'like', '%' . $nasid . '%')
          ->get();

        $data['venue'] = 0;
        if(sizeof($rows) > 0 && $rows[0]->sessions > 0) {
          $data['venue'] = $rows[0]->data / $rows[0]->sessions;
        }

        $brandcode = $this->getBrandcodeFromNasid($nasid);
        $rows = DB::table($reportperiod)
          ->selectRaw('sum(currentsessions) as sessions, sum(currentdata) as data')
          ->wherein('brandcode', $brandcodes)
          ->wherein('nasid', $activeVenues)
          ->get();
        if($rows[0]->sessions > 0) {
          $data['brand'] = $rows[0]->data / $rows[0]->sessions;
        } else {
          $data['brand'] = 0;
        }

        return $data;
    }

    public function getVenueUptimeData($reportperiod, $from, $to, $nasid, $brandname) {

      $data = array();
      // Get the uptime for this venue
      $data["total"] = $this->getVenueUptime($reportperiod, $from, $to, $nasid, $brandname);
        // error_log("getVenueUptimeData : from = " . $from);

      // Calculate the brand average
      $data["avg"] = $this->getBrandUptime($reportperiod, $from, $to, $nasid, $brandname);

      return $data;

    }

    public function getBrandUptime($reportperiod, $from, $to, $nasid, $brandname) {
      $statistics = new \Statistics();
      $activeVenues = $statistics->getActiveVenuesNoMac($brandname);
      $sumUptime = 0;

      foreach($activeVenues as $nasid) {
        $venueUptime = $this->getVenueUptime($reportperiod, $from, $to, $nasid, $brandname);

        $sumUptime = $sumUptime + $venueUptime;
        // error_log("getBrandUptime : sumUptime = " . $sumUptime);
      }
        // error_log("getBrandUptime : number of venues = " . sizeof($activeVenues));

        if (sizeof($activeVenues) != 0) {
          $branduptimepercent = round($sumUptime / sizeof($activeVenues), 2);
        } else {
          $branduptimepercent = 0;
        }

      // error_log("getBrandUptime : branduptimepercent = " . $branduptimepercent);

      return $branduptimepercent;
    }

    public function calculateVenueOpenHours($reportperiod, $from, $to, $nasid, $brandname) {

      // error_log("calculateVenueOpenHours begin");


      ///////////////////////
      // get the list of opening hours in an array ARR["MON"]["OPEN"], ARR["MON"]["CLOSED"] ... ARR["SUN"]["OPEN"], ARR["SUN"]["CLOSED"]
      $sitename = preg_replace("/_/", " ", $nasid);

      $openinghours = \Venue::selectRaw('mon_from, mon_to, tue_from, tue_to, wed_from, wed_to, thu_from, thu_to, fri_from, fri_to, sat_from, sat_to, sun_from, sun_to')
                  ->where('sitename', 'like', $sitename)
                  ->first();

      $dates = $this->getBeginEndDates($reportperiod, $from, $to);
      // error_log("calculateVenueOpenHours : sitename : $sitename");
      // error_log("calculateVenueOpenHours : begincurrent : " . $dates["begincurrent"]);
      // error_log("calculateVenueOpenHours : endcurrent : " . $dates["endcurrent"]);

      $date = $dates["begincurrent"];
      $todate = $dates["endcurrent"];

      $totalHours = 0;

      while (strtotime($date) <= strtotime($todate)) {

          $datetime = new \DateTime(date($date));
          $dayoftheweek = date_format($datetime, 'D');

          $timefrom = $openinghours[strtolower($dayoftheweek . "_from")] . ":00";
          $timeto = $openinghours[strtolower($dayoftheweek . "_to")] . ":00";

          if(!preg_match('/Closed.*$/i', $timefrom)) {

            $f = strtotime($timefrom); $t = strtotime($timeto);
            $configuredHours = abs($t - $f) / 3600;
            if(!$configuredHours) $configuredHours = 9;

          } else {

            $configuredHours = 0;

          }

          // error_log("calculateVenueOpenHours : loop  : $date");
          // error_log("calculateVenueOpenHours : dayoftheweek  : $dayoftheweek");
          // error_log("calculateVenueOpenHours : timefrom  : $timefrom");
          // error_log("calculateVenueOpenHours : timeto  : $timeto");
          // error_log("calculateVenueOpenHours : configuredHours  : $configuredHours");

          $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));

          $totalHours = $totalHours + $configuredHours;

      }

      // error_log("calculateVenueOpenHours : totalhours  : $totalHours");


      return $totalHours;
      // Query the downtimes for the period
      // foreach day in the period
          // Get the day of the week
          // Get the opening hours for the day - keep a count for the period
      // Do the calc
      ///////////////////////

      // Get number of days in the period
      // $datetimeto = new DateTime($endcurrent);
      // $datetimefrom = new DateTime($begincurrent);
      // $numdays = $datetimefrom->diff($datetimeto)->format('%a') + 1;

      // // Get number of daily open hours configured for venue
      // $sitename = preg_replace("/_/", " ", $nasid);
      // error_log("getVenueUptime : sitename = $sitename");

      // $venueTimeFrom = \Venue::where('sitename', 'like', $sitename)->first()->timefrom . ":00";
      // $venueTimeTo = \Venue::where('sitename', 'like', $sitename)->first()->timeto . ":00";

      // $f = strtotime($venueTimeFrom); $t = strtotime($venueTimeTo);
      // $configuredHours = abs($t - $f) / 3600;
      // if(!$configuredHours) $configuredHours = 9;

      // // Calculate number of hours in the date range
      // $dateRangeTotalHours = $numdays * $configuredHours;



    }

    public function getVenueUptime($reportperiod, $from, $to, $nasid, $brandname) {

      // error_log("getVenueUptime : nasid = $nasid");
      // Get begin and end dates of the period
      $dates = $this->getBeginEndDates($reportperiod, $from, $to);
      $begincurrent = $dates["begincurrent"];
      $endcurrent = $dates["endcurrent"];
      $beginprevious = $dates["beginprevious"];
      $endprevious = $dates["endprevious"];

      // Get the number of down periods (period = 15min) in the date range
      $rows = \DB::table('downtime_history')
                  ->selectRaw('sum(periodsdown) as totalperiodsdown')
                  ->where('nasid', 'like', $nasid)
                  ->where('date', '>=', $begincurrent)
                  ->where('date', '<=', $endcurrent)
                  ->get();

      $totalPeriodsDown = $rows[0]->totalperiodsdown;

      // Calculate the number of down hours in the date range
      $totalHoursDown = $totalPeriodsDown / 4;

      $dateRangeTotalHours = $this->calculateVenueOpenHours($reportperiod, $from, $to, $nasid, $brandname);


      // Calculate the uptime percentage
      $upTimePercentage = 100 * round(1 - ($totalHoursDown / $dateRangeTotalHours), 4);

      // error_log("getVenueUptime : reportperiod = $reportperiod");
      // error_log("getVenueUptime : begincurrent = $begincurrent");
      // error_log("getVenueUptime : endcurrent = $endcurrent");
      // error_log("getVenueUptime : from = $from");
      // error_log("getVenueUptime : to = $to");

      return $upTimePercentage;

    }

    public function getCustomersByTimePeriod($reportperiod, $from, $to, $nasid, $brandname, $brandonly = null) {


        $brand = \Brand::where('code', 'like', "%$nasid%")->first();

        $statistics = new \Statistics();

        $durations =  array(
                        array("begin" => "00:00:00", "end" => "01:00:00"),
                        array("begin" => "01:00:00", "end" => "02:00:00"),
                        array("begin" => "02:00:00", "end" => "03:00:00"),
                        array("begin" => "03:00:00", "end" => "04:00:00"),
                        array("begin" => "04:00:00", "end" => "05:00:00"),
                        array("begin" => "05:00:00", "end" => "06:00:00"),
                        array("begin" => "06:00:00", "end" => "07:00:00"),
                        array("begin" => "07:00:00", "end" => "08:00:00"),
                        array("begin" => "08:00:00", "end" => "09:00:00"),
                        array("begin" => "09:00:00", "end" => "10:00:00"),
                        array("begin" => "10:00:00", "end" => "11:00:00"),
                        array("begin" => "11:00:00", "end" => "12:00:00"),
                        array("begin" => "12:00:00", "end" => "13:00:00"),
                        array("begin" => "13:00:00", "end" => "14:00:00"),
                        array("begin" => "14:00:00", "end" => "15:00:00"),
                        array("begin" => "15:00:00", "end" => "16:00:00"),
                        array("begin" => "16:00:00", "end" => "17:00:00"),
                        array("begin" => "17:00:00", "end" => "18:00:00"),
                        array("begin" => "18:00:00", "end" => "19:00:00"),
                        array("begin" => "19:00:00", "end" => "20:00:00"),
                        array("begin" => "20:00:00", "end" => "21:00:00"),
                        array("begin" => "21:00:00", "end" => "22:00:00"),
                        array("begin" => "22:00:00", "end" => "23:00:00"),
                        array("begin" => "23:00:00", "end" => "23:59:59")
                      );

        $category = array(
            array("label" => "00"),
            array("label" => "01"),
            array("label" => "02"),
            array("label" => "03"),
            array("label" => "04"),
            array("label" => "05"),
            array("label" => "06"),
            array("label" => "07"),
            array("label" => "08"),
            array("label" => "09"),
            array("label" => "10"),
            array("label" => "11"),
            array("label" => "12"),
            array("label" => "13"),
            array("label" => "14"),
            array("label" => "15"),
            array("label" => "16"),
            array("label" => "17"),
            array("label" => "18"),
            array("label" => "19"),
            array("label" => "20"),
            array("label" => "21"),
            array("label" => "22"),
            array("label" => "23"),
            );

        $categories = array(array("category" => $category));

        // $sitename = preg_replace("/_/", " ", $nasid);

        // $str = $sitename;
        // $str = ltrim($str, 'X');
        
        $venue = \Venue::where('sitename', 'like', "%$brand->name%")->first();

        if ($venue) {
          $remotedb_id = $venue->remotedb_id;

          // echo "brandname : $brandname";
          $remotedb = \DB::table('remotedbs')->select("dbconnection")->where('id', '=', $remotedb_id)->first();
          $connection = $remotedb->dbconnection;

          $venueData = array(); $venueValues = array();
          $brandData = array(); $brandValues = array();

          $totalVenueSessions = 0;
          $totalBrandSessions = 0;

          foreach($durations as $duration) {

            $venueSessionCount = $statistics->getSessionCountByTimePeriodAndVenue($connection, $nasid, $from, $to, $duration["begin"], $duration["end"]);
            $totalVenueSessions = $totalVenueSessions + $venueSessionCount;
            array_push($venueValues, $venueSessionCount);

            $brandSessionCount = $statistics->getSessionCountByTimePeriodAndBrand($connection, $brandname, $from, $to, $duration["begin"], $duration["end"]);
            $totalBrandSessions = $totalBrandSessions + $brandSessionCount;
            array_push($brandValues, $brandSessionCount);

          }

          if(!$totalVenueSessions) $totalVenueSessions = 1;
          foreach($venueValues as $value) {
            $percentage = round(100 * $value / $totalVenueSessions);
            array_push($venueData, array("value" => $percentage));
          }

          if(!$totalBrandSessions) $totalBrandSessions = 1;
          foreach($brandValues as $value) {
            $percentage = round(100 * $value / $totalBrandSessions);
            array_push($brandData, array("value" => $percentage));
          }

          // error_log("getDwellTimeBySessionDuration : venueData = " . print_r($venueData, true));
          // error_log("getDwellTimeBySessionDuration : brandData = " . print_r($brandData, true));

          $thisvenue = array("seriesname" => "Venue %", "data" => $venueData);
          $brandAverages = array("seriesname" => "Brand %", "data" => $brandData);
          if($brandonly) {
            $dataset = array($brandAverages);
          } else {
            $dataset = array($thisvenue, $brandAverages);
          }

          $chartData = array(
            'chart' => array(
                "caption" => "",
                "subCaption" => "",
                "xAxisname" => "Hour of Day",
                "yAxisname" => "",
                "showYAxisValues" => "0",
                "numberPrefix" => "",
                "numDivLines" => "0",
                "paletteColors" => "#68a2da, #808080, #ed7d31, #f2c500,#f45b00,#8e0000,   #68a2da,#a5a5a5a5",
                "bgColor" => "#ffffff",
                "showBorder" => "0",
                "showHoverEffect" => "1",
                "showCanvasBorder" => "0",
                "usePlotGradientColor" => "0",
                "plotBorderAlpha" => "10",
                "legendBorderAlpha" => "0",
                "legendShadow" => "0",
                "placevaluesInside" => "1",
                "valueFontColor" => "#ffffff",
                "showXAxisLine" => "0",
                "xAxisLineColor" => "#999999",
                "divlineColor" => "#999999",
                "divLineDashed" => "0",
                "showAlternateVGridColor" => "0",
                "subcaptionFontBold" => "0",
                "subcaptionFontSize" => "14",
            ),
            'categories' => $categories,
            'dataset' => $dataset
          );

          return $chartData;
        }

    }

    public function getDwellTimeBySessionDuration($reportperiod, $from, $to, $nasid, $brandname, $brandonly = null) {

      $brand = \Brand::where('code', 'like', "%$nasid%")->first();


        $statistics = new \Statistics();

        $durations =  array(array("min" => 0, "max" => 4),
                      array("min" => 5, "max" => 10),
                      array("min" => 11, "max" => 20),
                      array("min" => 21, "max" => 30),
                      array("min" => 31, "max" => 45),
                      array("min" => 46, "max" => 99));

        $category = array(
            array("label" => ">5"),
            array("label" => "5-10"),
            array("label" => "11-20"),
            array("label" => "21-30"),
            array("label" => "31-45"),
            array("label" => "Over 45")
            );

        $categories = array(array("category" => $category));

        
        // $sitename = preg_replace("/_/", " ", $nasid);

        \Log::info("hannes wifi:brand name= $brand->name");

        // $str = $sitename;
        // $str = ltrim($str, 'X');
        
            



        $venue = \Venue::where('sitename', 'like', "%$brand->name%")->first();

        if ($venue) {
          $remotedb_id = $venue->remotedb_id;
          // echo "brandname : $brandname";
          $remotedb = \DB::table('remotedbs')->select("dbconnection")->where('id', '=', $remotedb_id)->first();
          $connection = $remotedb->dbconnection;

          $venueData = array(); $venueValues = array();
          $brandData = array(); $brandValues = array();

          $totalVenueSessions = 0;
          $totalBrandSessions = 0;

          foreach($durations as $duration) {

            $minseconds = $duration["min"] * 60;
            $maxseconds = $duration["max"] * 60;

            $venueSessionCount = $statistics->getSessionCountByDwellTimeAndVenue($connection, $nasid, $from, $to, $minseconds, $maxseconds);
            $totalVenueSessions = $totalVenueSessions + $venueSessionCount;
            array_push($venueValues, $venueSessionCount);

            $brandSessionCount = $statistics->getSessionCountByDwellTimeAndBrand($connection, $brandname, $from, $to, $minseconds, $maxseconds);
            $totalBrandSessions = $totalBrandSessions + $brandSessionCount;
            array_push($brandValues, $brandSessionCount);
          }

          if(!$totalVenueSessions) $totalVenueSessions = 1;
          foreach($venueValues as $value) {
            $percentage = round(100 * $value / $totalVenueSessions);
            array_push($venueData, array("value" => $percentage));
          }

          if(!$totalBrandSessions) $totalBrandSessions = 1;
          foreach($brandValues as $value) {
            $percentage = round(100 * $value / $totalBrandSessions);
            array_push($brandData, array("value" => $percentage));
          }

          // error_log("getDwellTimeBySessionDuration : venueData = " . print_r($venueData, true));
          // error_log("getDwellTimeBySessionDuration : brandData = " . print_r($brandData, true));

          $thisvenue = array("seriesname" => "Venue %", "data" => $venueData);
          $brandAverages = array("seriesname" => "Brand %", "data" => $brandData);
          if($brandonly) {
            $dataset = array($brandAverages);
          } else {
            $dataset = array($thisvenue, $brandAverages);
          }

          $chartData = array(
            'chart' => array(
                "caption" => "",
                "subCaption" => "",
                "xAxisname" => "Dwell Time Period (mins)",
                "yAxisname" => "",
                "showYAxisValues" => "0",
                "numberPrefix" => "",
                "numDivLines" => "0",
                "paletteColors" => "#68a2da, #808080, #ed7d31, #f2c500,#f45b00,#8e0000,   #68a2da,#a5a5a5a5",
                "bgColor" => "#ffffff",
                "showBorder" => "0",
                "showHoverEffect" => "1",
                "showCanvasBorder" => "0",
                "usePlotGradientColor" => "0",
                "plotBorderAlpha" => "10",
                "legendBorderAlpha" => "0",
                "legendShadow" => "0",
                "placevaluesInside" => "1",
                "valueFontColor" => "#ffffff",
                "showXAxisLine" => "0",
                "xAxisLineColor" => "#999999",
                "divlineColor" => "#999999",
                "divLineDashed" => "0",
                "showAlternateVGridColor" => "0",
                "subcaptionFontBold" => "0",
                "subcaptionFontSize" => "14",
            ),
            'categories' => $categories,
            'dataset' => $dataset
          );

        return $chartData;
      }

    }

    public function getDwellTimeByHour($reportperiod, $from, $to, $nasid, $brandcodes) {
    }


    public function getAvgTimePerSession($reportperiod, $from, $to, $nasid, $brandcodes) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();

        $data = array();

        $rows = DB::table($reportperiod)
          ->selectRaw(' currentminutes as avgminutes ')
          ->where('nasid', 'like', '%' . $nasid . '%')
          ->get();

        $data['venue'] = 0;
        if(sizeof($rows) > 0) {
          $data['venue'] = $rows[0]->avgminutes;
        }

        $brandcode = $this->getBrandcodeFromNasid($nasid);
        $rows = \DB::table("repdailytotals")
               ->selectRaw('sum(sessions) as totalsessions, sum(dwelltime) as totalminutes')
               ->wherein('brandcode', $brandcodes)
               ->wherein('nasid', $activeVenues)
               ->where('date', '>=', $from)
               ->where('date', '<=', $to)
               ->get();

        if($rows[0]->totalminutes > 0) {
          $data['brand'] = $rows[0]->totalminutes / $rows[0]->totalsessions;
        } else {
          $data['brand'] = 0;
        }

        return $data;
    }



    // public function getAvgTimePerSession($reportperiod, $from, $to, $nasid, $brandcodes) {
    //     $statistics = new \Statistics();
    //     $activeVenues = $statistics->getActiveVenues();

    //     $data = array();

    //     $rows = DB::table($reportperiod)
    //       ->selectRaw(' currentminutes as avgminutes ')
    //       ->where('nasid', 'like', '%' . $nasid . '%')
    //       ->get();

    //     $data['venue'] = 0;
    //     if(sizeof($rows) > 0) {
    //       $data['venue'] = $rows[0]->avgminutes;
    //     }

    //     $brandcode = $this->getBrandcodeFromNasid($nasid);

    //     $rows = \DB::table("repdailytotals")
    //              ->wherein('brandcode', $brandcodes)
    //              ->wherein('nasid', $activeVenues)
    //              ->where('date', '>=', $from)
    //              ->where('date', '<=', $to)
    //              ->orderBy('dwelltime')
    //              ->get();

    //     $allessions = sizeof($rows);
    //     $tenPercent = round($allessions * .10);
    //     $bottom = $tenPercent;
    //     $top = $allessions - $tenPercent;
    //     $totalrecords = $top - $bottom; //The number of sessions excluding the top and bottom 10 percent
    //     error_log("getDwellTimeTopBottom10PercentThreshholds : allessions = $allessions");
    //     error_log("getDwellTimeTopBottom10PercentThreshholds : tenPercent = $tenPercent");
    //     error_log("getDwellTimeTopBottom10PercentThreshholds : bottom = $bottom");
    //     error_log("getDwellTimeTopBottom10PercentThreshholds : top = $top");
    //     error_log("getDwellTimeTopBottom10PercentThreshholds : totalrecords = $totalrecords");

    //     $totalminutes = 0; $totalsessions = 0;

    //     for($i=$bottom; $i<=$top; $i++) {
    //       $totalminutes = $totalminutes + $rows[$i]->dwelltime;
    //       $totalsessions = $totalsessions + $rows[$i]->sessions;
    //     }

    //     error_log("getDwellTimeTopBottom10PercentThreshholds : totalminutes = $totalminutes");

    //     if($totalminutes > 0) {
    //       $data['brand'] = round($totalminutes / $totalsessions);
    //       error_log("getDwellTimeTopBottom10PercentThreshholds : data['brand'] = " . $data['brand']);

    //     } else {
    //       $data['brand'] = 0;
    //     }

    //     return $data;
    // }



        // $rows = \DB::table("repdailytotals")
        //        ->selectRaw('sum(sessions) as totalsessions, sum(dwelltime) as totalminutes')
        //        ->wherein('brandcode', $brandcodes)
        //        ->wherein('nasid', $activeVenues)
        //        ->where('date', '>=', $from)
        //        ->where('date', '<=', $to)
        //        ->where('dwelltime', '>=', $threholds["bottom"])
        //        ->where('dwelltime', '<=', $threholds["top"])
        //        ->get();

        // if($rows[0]->totalminutes > 0) {
        //   $data['brand'] = $rows[0]->totalminutes / $rows[0]->totalsessions;
        // } else {
        //   $data['brand'] = 0;
        // }


    public function getAveJamVenueDwellTime($reportperiod, $from, $to, $nasid) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();


      if(!preg_match('/^.*mrp.*$/i', $nasid)) return "No data";

      $from = $from . "%2000:00:00";
      $to = $to . "%2023:59:59";
      $url = 'http://api.doteleven.co/venues/mrp0381?period=custom&start=' . $from . '&end=' . $to . '&min_session=5&max_session=60';
      error_log("getAveJamVenueDwellTime : url : $url ");

      $ch = curl_init($url);

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json'
      ));

      $resultsjson = curl_exec($ch);// json_encode(curl_exec($ch));
      $resultsObj = json_decode($resultsjson);


      $avg_dwelltime = $resultsObj->total->average_session;
      error_log("getAveJamVenueDwellTime : " . print_r($avg_dwelltime, true));

      return $avg_dwelltime;

    }


public function getNewVsReturningForBrand($reportperiod, $from, $to, $brandcode) {
        $statistics = new \Statistics();
        $activeVenues = $statistics->getActiveVenues();


  error_log("getNewVsReturningForBrand : brandcode = $brandcode");

        $data = array();

        $rows1 = DB::table($reportperiod)
          ->selectRaw('sum(currentunique) as total')
          ->where('brandcode', 'like', '%' . $brandcode . '%')
          ->get();


        $rows2 = DB::table($reportperiod)
          ->selectRaw('sum(currentnewusers) as new')
          ->where('brandcode', 'like', '%' . $brandcode . '%')
          ->get();

        $total = $rows1[0]->total;
        $new = $rows2[0]->new;
        $returning = $total - $new;
  error_log("getNewVsReturningForBrand : total = $total");
  error_log("getNewVsReturningForBrand : new = $new");
  error_log("getNewVsReturningForBrand : returning = $returning");

        $a = array("label" => "new", "value" => $new, "showLabel" => "0");
        array_push($data, $a);
        $b = array("label" => "returning", "value" => $returning, "showLabel" => "0");
        array_push($data, $b);
            // "canvasbgColor" => "#E5B725",
            // "canvasbgAlpha" => "0",
        $chartData = array(
          'chart' => array(
            "paletteColors" => "#ed7d31,#68a2da,#f2c500,#f45b00,#8e0000",
            "pieRadius" => "80",
            "bgColor" => "#ffffff",
            "bgAlpha" => "100",
            "showPercentValues" => "1",
            "placeValuesInside" => "1",
            "showPercentInTooltip" => "0",
            "decimals" => "1",
            "captionFontSize" => "14",
            "subcaptionFontSize" => "14",
            "subcaptionFontBold" => "0",
            "toolTipColor" => "#ffffff",
            "toolTipBorderThickness" => "0",
            "toolTipBgColor" => "#000000",
            "toolTipBgAlpha" => "80",
            "toolTipBorderRadius" => "2",
            "toolTipPadding" => "5",
            "showHoverEffect" => "1",
            "showLegend" => "1",
            "legendBgColor" => "#ffffff",
            "legendBorderAlpha" => "0",
            "legendShadow" => "0",
            "legendItemFontSize" => "10",
            "legendItemFontColor" => "#666666",
            "showBorder" => "0",
            "use3DLighting" => "0",
            "showShadow" => "0",
            "enableSmartLabels" => "1",
            "startingAngle" => "0",
            "useDataPlotColorForLabels" => "1"

          ),
          'data' => $data
        );

        return $chartData;
    }

    ////////////////////////////// VENUE CHARTS /////////////////////////////////////

    public function getNewVsReturning($reportperiod, $from, $to, $nasid) {

        $data = array();

        $rows1 = DB::table($reportperiod)
          ->selectRaw('currentunique as total')
          ->where('nasid', 'like', '%' . $nasid . '%')
          ->get();


        $rows2 = DB::table($reportperiod)
          ->selectRaw('currentnewusers as new')
          ->where('nasid', 'like', '%' . $nasid . '%')
          ->get();

        $total = $rows1[0]->total;
        $new = $rows2[0]->new;
        $returning = $total - $new;

        $a = array("label" => "new", "value" => $new, "showLabel" => "0");
        array_push($data, $a);
        $b = array("label" => "returning", "value" => $returning, "showLabel" => "0");
        array_push($data, $b);
            // "canvasbgColor" => "#E5B725",
            // "canvasbgAlpha" => "0",
        $chartData = array(
          'chart' => array(
            "paletteColors" => "#ed7d31,#68a2da,#f2c500,#f45b00,#8e0000",
            "pieRadius" => "80",
            "bgColor" => "#ffffff",
            "bgAlpha" => "100",
            "showPercentValues" => "1",
            "placeValuesInside" => "1",
            "showPercentInTooltip" => "0",
            "decimals" => "1",
            "captionFontSize" => "14",
            "subcaptionFontSize" => "14",
            "subcaptionFontBold" => "0",
            "toolTipColor" => "#ffffff",
            "toolTipBorderThickness" => "0",
            "toolTipBgColor" => "#000000",
            "toolTipBgAlpha" => "80",
            "toolTipBorderRadius" => "2",
            "toolTipPadding" => "5",
            "showHoverEffect" => "1",
            "showLegend" => "1",
            "legendBgColor" => "#ffffff",
            "legendBorderAlpha" => "0",
            "legendShadow" => "0",
            "legendItemFontSize" => "10",
            "legendItemFontColor" => "#666666",
            "showBorder" => "0",
            "use3DLighting" => "0",
            "showShadow" => "0",
            "enableSmartLabels" => "1",
            "startingAngle" => "0",
            "useDataPlotColorForLabels" => "1"

          ),
          'data' => $data
        );

        return $chartData;
    }

    // Ensure that the answers appear in the order in which they were defined in the $answers array
    public function getSortedAnswers($answers, $results) {

      $sortedresults = array();

      foreach($answers as $answer) {

          $row = array("value" => "No value for : " . $answer);

          foreach($results as $result) {
            if($result->answer == $answer) {
              $row["value"] = $result->value;
            }
          }

          array_push($sortedresults, $row);
      }

      return $sortedresults;
    }

    public function getAggregatedAnswersForVenue($reportperiod, $from, $to, $nasid, $answers, $quickie_id) {

        $sitename = preg_replace("/_/", " ", $nasid);
        $venue = \Venue::whereRaw("LOWER(sitename) LIKE '%".strtolower($sitename)."%'")->get()->first();

        if ($venue) {
          $location = $venue->location;
          \Log::info('-------------------------------------AGGREGATED RESULTS FOR '.$location.'-------------------------------------');
          \Log::info('QUICKIE_ID: '.$quickie_id);
          \Log::info('FROM: '.$from);
          \Log::info('TO: '.$to);
          \Log::info('ANSWER: '.join(", ",$answers));


          $results = \DB::connection("hipreports")->table("partner")
             ->select(DB::raw('answer, count(*) AS value'))
             ->where('sitename', 'like', $location)
             ->where('created_at', '>', $from)
             ->where('created_at', '<', $to)
             ->where('quickie_id', '=', $quickie_id)
             ->wherein('answer', $answers)
             ->groupby('answer')
             ->get();


             foreach ($results as $result) {
              \Log::info('answer: '.$result->answer);
              \Log::info('count: '.$result->value);
             }

            //  \Log::info(jsonEncode($results));

             \Log::info('-----------------------------------------------------------------------------------------------------------------');

          return $this->getSortedAnswers($answers, $results);

        }


    }

    public function getAggregatedAnswersForBrand($reportperiod, $from, $to, $nasid, $answers, $brandcodes, $quickie_id, $brandonly = null) {

        error_log("getAggregatedAnswersForBrand : from = $from ::: to = $to");
        error_log("getAggregatedAnswersForBrand : answers : " . print_r($answers, true));
        error_log("getAggregatedAnswersForBrand : brandcodes : " . print_r($brandcodes, true));

        $activeVenueLocations = \Venue::selectRaw('location')
          ->where('ap_active', '=', 1)
          ->where(function ($subquery) use ($brandcodes) {
                // $subquery->where('location', 'like', "thisisadummyvalue");
                foreach($brandcodes as $brandcode) {
                  $subquery->orwhere('location', 'like', "%" . $brandcode . "%");
                }
              })
          ->get();

        $activeVenueLocationsArray = array();
        foreach($activeVenueLocations as $x) {
            array_push($activeVenueLocationsArray, $x->location);
        }
        // $activeVenueLocationsArray = (array)$activeVenueLocations;
        $numvenues = sizeof($activeVenueLocationsArray);

        error_log("getAggregatedAnswersForBrand : activeVenueCount : " . $numvenues );
        // $numvenues = $avcount[0]["count"];

        $sitename = preg_replace("/_/", " ", $nasid);

        // no touchie!!
        $venue = \Venue::whereRaw("LOWER(location) LIKE '%".strtolower($sitename)."%'")->get()->first();
        if ($venue) {
          $location = $venue->location;
          $nastype = substr($location, 0, 9);

          // error_log("lalalalalalalal activeVenueLocationsArray = " . print_r($activeVenueLocationsArray, true));
          $results = \DB::connection("hipreports")->table("partner")
             ->select(DB::raw('answer, count(*) AS value'))
             ->where('created_at', '>', $from)
             ->where('created_at', '<', $to)
             ->where('quickie_id', '=', $quickie_id)
             ->wherein('sitename', $activeVenueLocationsArray)
             ->wherein('answer', $answers)
             ->groupby('answer')
             ->get();

          // Calculate the averages
          foreach ($results as $result) {
            if($numvenues) {
              if($brandonly) { // Retun the total instead of the average
                $result->value = round($result->value);
                error_log("getAggregatedAnswersForBrand : brandonly : " . $result->value);
              } else {
                $result->value = 0;
              }
            }

            return $this->getSortedAnswers($answers, $results);
          }
        } else {
          \Log::error('Venue is null in Reports@getAggregatedAnswersForBrand');
        }


    }

    public function buildDateRangeReportTable($from, $to, $brandname) {

        $tablename = "tmp_period_" . $brandname . "_" . $from . "_" . $to ;
        error_log("buildDateRangeReportTable : " . $from . "_" . $to);

        Schema::dropIfExists($tablename);

        Schema::create($tablename, function($table) {
            $table->increments('id');

            $table->string('nasid');
            $table->string('brandcode');
            $table->string('sitename');
            $table->string('calledstationid');

            $table->integer('currentsessions');
            $table->integer('previoussessions');
            $table->integer('diffsessions');
            $table->float('percentsessions');

            $table->integer('currentunique');
            $table->integer('previousunique');
            $table->integer('diffunique');
            $table->float('percentunique');

            $table->integer('currentnewusers');
            $table->integer('previousnewusers');
            $table->integer('diffnewusers');
            $table->integer('percentnewusers');

            $table->integer('currentminutes');
            $table->integer('previousminutes');
            $table->integer('diffminutes');
            $table->integer('percentminutes');

            $table->integer('currentdata');
            $table->integer('previousdata');
            $table->integer('diffdata');
            $table->integer('percentdata');

            $table->timestamps();
        });
        $this->generateAggregatedVenueData("daterange", $from, $to, $brandname);
    }


    public function getAge($reportperiod, $from, $to, $nasid, $brandcodes, $brandonly = null) {

        error_log("getAge : nasid = $nasid");
        error_log("getAge : brandcodes = " . print_r($brandcodes, true));

        if(preg_match('/.*SAB_.*$/i', $nasid)) {
          $category = array(
            array("label" => "18-25"),
            array("label" => "26-30"),
            array("label" => "31-40"),
            array("label" => "41-50"),
            array("label" => "Over 50")
            );

          $answers = array("18-25", "26-30", "31-40", "41-50", "Over 50");

          $quickie_id = 488;

        } else {

          $category = array(
            array("label" => "Under 18"),
            array("label" => "18-22"),
            array("label" => "23-29"),
            array("label" => "30-45"),
            array("label" => "46-60"),
            array("label" => "Over 60")
            );

          $answers = array("Under 18", "18-22", "23-29", "30-45", "46-60", "Over 60");

          $quickie_id = 90;
        }

        $categories = array(array("category" => $category));
        $txt = "Categories for age are : ".json_encode($categories);

        $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        $thisvenuedata = $this->getAggregatedAnswersForVenue($reportperiod, $from, $to, $nasid, $answers, $quickie_id);
        $txt = "The venuedata are : ".json_encode($thisvenuedata);

        $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        error_log("getAge : thisvenuedata = " . print_r($thisvenuedata, true));
        // $testdata = json_encode("$thisvenuedata");
        // error_log("getAge : testdata = $testdata");

        $brandaveragedata = $this->getAggregatedAnswersForBrand($reportperiod, $from, $to, $nasid, $answers, $brandcodes, $quickie_id, $brandonly);

        \Log::info('---------------------------- Matt Log --------------------------------');
        \Log::info($brandaveragedata);

        \Log::info('---------------------------- End Matt Log --------------------------------');
        // error_log("getAge : brandaveragedata = " . print_r($brandaveragedata, true));


        $thisvenue = array("seriesname" => "This Venue", "data" => $thisvenuedata);
        if($brandonly) {

          $brandaverage = array("seriesname" => "Brand Total", "data" => $brandaveragedata);
          $dataset = array($brandaverage);
        } else {

          $brandaverage = array("seriesname" => "Brand Avg", "data" => $brandaveragedata);
          $dataset = array($thisvenue, $brandaverage);
        }
        $txt = "The Brand Average are : ".json_encode($brandaverage);

        $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        $chartData = array(
          'chart' => array(
              "caption" => "",
              "subCaption" => "",
              "yAxisname" => "",
              "yAxisname" => "",
              "showYAxisValues" => "0",
              "numberPrefix" => "",
              "numDivLines" => "0",
              "paletteColors" => "#68a2da, #808080, #ed7d31, #f2c500,#f45b00,#8e0000,   #68a2da,#a5a5a5a5",
              "bgColor" => "#ffffff",
              "showBorder" => "0",
              "showHoverEffect" => "1",
              "showCanvasBorder" => "0",
              "usePlotGradientColor" => "0",
              "plotBorderAlpha" => "10",
              "legendBorderAlpha" => "0",
              "legendShadow" => "0",
              "placevaluesInside" => "1",
              "valueFontColor" => "#ffffff",
              "showXAxisLine" => "0",
              "xAxisLineColor" => "#999999",
              "divlineColor" => "#999999",
              "divLineDashed" => "0",
              "showAlternateVGridColor" => "0",
              "subcaptionFontBold" => "0",
              "subcaptionFontSize" => "14",
          ),
          'categories' => $categories,
          'dataset' => $dataset
        );
        $txt = "The Chart Data are : ".json_encode($chartData);
        $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        // $x = json_encode($chartData);
        // error_log($x);
        return $chartData;
    }

    public function getGender($reportperiod, $from, $to, $nasid, $brandcodes, $brandonly = null) {


        if(preg_match('/.*SAB_.*$/i', $nasid)) {
          $quickie_id = 489;
        } else {
          $quickie_id = 89;
        }

        $brandaveragedata = array();
        $answers = array("Male", "Female");
        $thisvenuedata = $this->getAggregatedAnswersForVenue($reportperiod, $from, $to, $nasid, $answers, $quickie_id);
        $brandaveragedata = $this->getAggregatedAnswersForBrand($reportperiod, $from, $to, $nasid, $answers, $brandcodes, $quickie_id, $brandonly);

        error_log("getGender : thisvenuedata : " . print_r($thisvenuedata, true));
        error_log("getGender : brandaveragedata : " . print_r($brandaveragedata, true));

        if($brandonly) {

          $category = array(
            array("label" => "Brand Total"),
          );

          $maledata = array(
            array("value" => $brandaveragedata[0]["value"]),
          );

          $femaledata = array(
            array("value" => $brandaveragedata[1]["value"]),
          );

        } else {

          $category = array(
            array("label" => "This Venue"),
            array("label" => "Brand Avg"),
          );

          $maledata = array(
            array("value" => $thisvenuedata[0]["value"]),
            array("value" => $brandaveragedata[0]["value"]),
          );

          $femaledata = array(
            array("value" => $thisvenuedata[1]["value"]),
            array("value" => $brandaveragedata[1]["value"]),
          );

        }

        $categories = array(array("category" => $category));


        $male = array("seriesname" => "Male", "data" => $maledata);
        $female = array("seriesname" => "Female", "data" => $femaledata);
        $dataset = array($male, $female);

        $chartData = array(
          'chart' => array(
              "caption" => "",
              "subCaption" => "",
              "xAxisname" => "",
              "yAxisName" => "",
              "showPercentValues" => "1",
              "showPercentInTooltip" => "0",
              "showYAxisValues" => "1",
              "stack100Percent" => "1",
              "numberPrefix" => "",
              "paletteColors" => "#FFCC29,#ed7d31",
              "bgColor" => "#ffffff",
              "borderAlpha" => "20",
              "showCanvasBorder" => "0",
              "usePlotGradientColor" => "0",
              "plotBorderAlpha" => "10",
              "legendBorderAlpha" => "0",
              "legendShadow" => "0",
              "valueFontColor" => "#ffffff",
              "showXAxisLine" => "1",
              "xAxisLineColor" => "#999999",
              "divlineColor" => "#999999",
              "numDivLines" => "0",
              "divLineDashed" => "1",
              "showAlternateVGridColor" => "0",
              "subcaptionFontBold" => "0",
              "subcaptionFontSize" => "14",
              "showHoverEffect" => "1"
          ),
          'categories' => $categories,
          'dataset' => $dataset
        );

        // $x = json_encode($chartData);
        // error_log($x);
        return $chartData;
    }

    public function getIncome($reportperiod, $from, $to, $nasid, $brandcodes, $brandonly = null) {
       $category = array(
          array("label" => "Less than R30 000"),
          array("label" => "R30 000 - R40 000"),
          array("label" => "More than R40 000"),
          );
        $categories = array(array("category" => $category));

        $answers = array("Less than R30 000", "R30 000 - R40 000", "More than R40 000");
        $thisvenuedata = $this->getAggregatedAnswersForVenue($reportperiod, $from, $to, $nasid, $answers, 143);
        $brandaveragedata = $this->getAggregatedAnswersForBrand($reportperiod, $from, $to, $nasid, $answers, $brandcodes, 143, $brandonly);

        $thisvenue = array("seriesname" => "This Venue", "data" => $thisvenuedata);
        if($brandonly) {
          $brandaverage = array("seriesname" => "Brand Total", "data" => $brandaveragedata);
          $dataset = array($brandaverage);
        } else {
          $brandaverage = array("seriesname" => "Brand Average", "data" => $brandaveragedata);
          $dataset = array($thisvenue, $brandaverage);
        }

        $chartData = array(
          'chart' => array(
              "caption" => "",
              "subCaption" => "",
              "yAxisname" => "",
              "yAxisname" => "",
              "showYAxisValues" => "0",
              "numberPrefix" => "",
              "numDivLines" => "0",
              "paletteColors" => "#68a2da, #808080, #ed7d31, #f2c500,#f45b00,#8e0000,   #68a2da,#a5a5a5a5",
              "bgColor" => "#ffffff",
              "showBorder" => "0",
              "showHoverEffect" => "1",
              "showCanvasBorder" => "0",
              "usePlotGradientColor" => "0",
              "plotBorderAlpha" => "10",
              "legendBorderAlpha" => "0",
              "legendShadow" => "0",
              "placevaluesInside" => "1",
              "valueFontColor" => "#ffffff",
              "showXAxisLine" => "0",
              "xAxisLineColor" => "#999999",
              "divlineColor" => "#999999",
              "divLineDashed" => "0",
              "showAlternateVGridColor" => "0",
              "subcaptionFontBold" => "0",
              "subcaptionFontSize" => "14",

          ),
          'categories' => $categories,
          'dataset' => $dataset
        );

        // $x = json_encode($chartData);
        // error_log($x);
        return $chartData;
    }

    //////////////////////////////////////// DASHBOARD //////////////////////////////////

    public function getWifiDashboardTotalSessions() {

      $statistics = new \Statistics();
      $activeVenues = $statistics->getActiveVenues();

      $brandcodes = array();
      $brand = new \Brand();
      $brands = $brand->getBrandsForUser(\Auth::user()->id);

      foreach($brands as $brand) {
          array_push($brandcodes, $brand->code);
      }

      $rows = \DB::table("repdailytotals")
             ->selectRaw('sum(sessions) as total')
             ->whereIn('brandcode', $brandcodes)
             ->wherein('nasid', $activeVenues)
             ->get();

      return($rows[0]->total);

    }

    public function getWifiDashboardDwelltimeData() {

      $statistics = new \Statistics();
      $activeVenues = $statistics->getActiveVenues();

      $data = array();

      $brandcodes = array();
      $brand = new \Brand();
      $brands = $brand->getBrandsForUser(\Auth::user()->id);

      foreach($brands as $brand) {
          array_push($brandcodes, $brand->code);
      }

      $rows = \DB::table("repdailytotals")
             ->selectRaw('sum(dwelltime) as total, sum(sessions) as sessions')
             ->whereIn('brandcode', $brandcodes)
             ->wherein('nasid', $activeVenues)
             ->get();

      $data["total"] = $rows[0]->total;

      if($rows[0]->sessions == 0) {
        $data["avg"] = 0;
      } else {
        $data["avg"] = $rows[0]->total / $rows[0]->sessions;
      }


      return($data);

    }

    public function getWifiDashboardCurrentSessions() {

      // Get the DB connections
      $remotedbObj = new \Remotedb();
      $remotedb_ids = $remotedbObj->getRemotedbIdsForUser(Auth::user()->id);
      error_log("chhhhhhhhhhhh : " . print_r($remotedb_ids, true));


      $statistics = new \Statistics();
      $activeVenues = $statistics->getActiveVenues();


      // $yesterday_midnight = date('y-m-d H:i:s', strtotime('yesterday'));
      $yesterday_midnight = date('y-m-d H:i:s', strtotime('midnight'));

      $currentsessions = 0;
      foreach($remotedb_ids as $remotedb_id) {
        // For some reason the following doesn't work with an eloquent query - it should
        error_log("getWifiDashboardCurrentSessions : remotedb_id = $remotedb_id");
        $remotedb = \DB::table('remotedbs')->select("dbconnection")->where('id', '=', $remotedb_id)->first();

        if($remotedb) {

          $connection = $remotedb->dbconnection;
          error_log("getWifiDashboardCurrentSessions : remotedb_id = $remotedb_id ::: connection = $connection");

          $date = new DateTime(date('Y-m-d'));
          $today =  $date->format('Y-m-d');

          $rows = \DB::connection($connection)
             ->table("radacct")
             ->select(array(DB::raw('count(*) as count')))
             ->where('AcctStartTime', '>', $yesterday_midnight)
             ->where('AcctStopTime', '=', '0000-00-00 00:00:00')
             ->wherein('CalledStationId', $activeVenues)
             ->get();
             // ->where('AcctStopTime', '<', $yesterday_midnight)

            $currentsessions = $currentsessions + $rows[0]->count;
            // error_log("getWifiDashboardCurrentSessions : currentsessions = $currentsessions");

          } else {
        }

      }
      return $currentsessions;
    }


    public function getWifiDashboardData() {

      $sessions = array();
      $sessions["total"] = $this->getWifiDashboardTotalSessions();
      $sessions["currentsessions"] = $this->getWifiDashboardCurrentSessions();

      $dwelltime = $this->getWifiDashboardDwelltimeData();
      // $dwelltime["total"] = 333;
      // $dwelltime["avg"] = 444;

      $data["sessions"] = $sessions;
      $data["dwelltime"] = $dwelltime;

      return $data;
    }


    public function dropReportsTmpTables() {

      error_log("dropReportsTmpTables");

      $tables = DB::select('SHOW TABLES');

      echo "Dropping tables: " . "<br>";
      foreach ($tables as $table) {

        $tablename = $table->Tables_in_hiphub;

        if(preg_match('/^tmp_period/', $tablename)) {
          echo $tablename . "<br>";
          Schema::dropIfExists($tablename);
        }
      }

      return;
    }


}