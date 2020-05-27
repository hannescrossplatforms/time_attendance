<?php

namespace hipreports;
use Input;

// use BaseController;

class HipreportsController extends \BaseController {

    public function showDashboard() {
        error_log('Reports : showDashboard');
        $data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('hipreports.showdashboard')->with('data', $data);
    }

    public function hipreports_hipwifi_logdowntime() {

        $nasid = \Input::get('nasid');
        $date = \Input::get('date');
        $time = \Input::get('time');

        $now = new \DateTime();

        if(!$date) $date = $now->format('Y-m-d');
        if(!$time) $time = $now->format('H:i:s');

        \DB::table('downtime_periods')->insert(array( "nasid" => $nasid, "date" => $date, "time" => $time));
        die();

        $data = array();
        $data["message"] = "hipreports_hipwifi_logdowntime completed : $date :::: $time :::: $nasid";

        return \View::make('simpleview')->with('data', $data);
    }

    public function hipreports_hipwifi_generatedowntimehistory() {

        $data = array();
        $data["message"] = "hipreports_hipwifi_generatedowntimehistory completed";

        $now = new \DateTime();
        $start = new \DateTime("2016-03-01");
        $diff = $now->diff($start);
        $days = $diff->d;

        \DB::table("downtime_history")->truncate();

        $datetime = new \DateTime(date('Y-m-d'));
        for($day = $days; $day > 0; $day--) {
            $datetime->sub(new \DateInterval('P1D'));
            $date = $datetime->format('Y-m-d');

            $allvenues = \Venue::all();
            foreach($allvenues as $venue) {
                $nasid = preg_replace("/ /", "_", $venue->sitename);

                // $dayoftheweek = $datetime->date("D");
                $dayoftheweek = date_format($datetime, 'D');
                echo $dayoftheweek . " === " . $nasid  . "<br>";
                $timefrom = $venue[strtolower($dayoftheweek . "_from")] . ":00";
                $timeto = $venue[strtolower($dayoftheweek . "_to")] . ":00";

                error_log("hipreports_hipwifi_generatedowntimehistory : from : $timefrom : " );
                error_log("hipreports_hipwifi_generatedowntimehistory : to : $timeto : ");

                if($timefrom != "Closed") {
                error_log("hipreports_hipwifi_generatedowntimehistory : nasid : $nasid : ");

                    $rows = \DB::table("downtime_periods")
                       ->select(array(\DB::raw('count(*) as count')))
                       ->where('nasid', 'like', $nasid)
                       ->where('date', '=', $date)
                       ->where('time', '>=', $timefrom)
                       ->where('time', '<=', $timeto)
                       ->get();

                    $periodsdown = $rows[0]->count;

                    if($periodsdown > 0) {
                        error_log("hipreports_hipwifi_generatedowntimehistory : periodsdown : $periodsdown : ");
                        \DB::table('downtime_history')->insert(array( "nasid" => $nasid, "date" => $date, "periodsdown" => $periodsdown));
                    }
                }
            }
        }

        die();

        return \View::make('simpleview')->with('data', $data);
    }

    public function hipWifi()
    {
        $data = array();
        $data['currentMenuItem'] = "REPORTS";

        $brandObj = new \Brand();
        $reportObj = new \Reports();

        $data['brands'] = $brandObj->getBrandsForProduct('hipwifi');
        error_log("HipreportsController : hipWifi " . print_r($data['brands'], 1));

        $brand_id = \Input::get('brand_id');
        if(!$brand_id) { $brand_id = $data['brands'][0]->id; }

        $data['brand_id'] = $brand_id;

        $reportperiod = \Input::get('reportperiod');
        $data['reportperiod'] = $reportperiod;

        // Dashboard
        $dashboarddata = $reportObj->getWifiDashboardData();
        $data['dashboarddata'] = json_encode($dashboarddata);

        // Venues

        $venuebrandcompare = \Input::get('venuebrandcompare');
        $data['venuebrandcompare'] = $venuebrandcompare;

        $venue = new \Venue();
        $venues = $venue->getVenuesForUser(null, null, "Mikrotik");
        foreach($venues as $venue) {
            if($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
        }
        $data['venuesJason'] = json_encode($venues);

        // Statistcs
        $sitename = \Input::get('sitename');
        // Default to todays date
        $from  = date("Y-m-d");
        $to  = date("Y-m-d");

        $data["from"] = $from;
        $data["to"] = $to;

        $from  = $from . " 00:00:00";
        $too  = $to . "  23:59:59";

        $stats = new \Statistics();

        // Taking out statistics temporarily 13-03-2017

        // $statsdata = $stats->getStatsData($sitename, $from, $to);
        $statsdata = null;

        $data['statsdata'] = json_encode($statsdata);
        // print_r($statsdata);


        return \View::make('hipreports.hipwifi')->with('data', $data);
    }

    public function hipreports_hipwifi_statistcsjson() {


        $sitename = \Input::get('sitename');
        $from = \Input::get('from');
        $to = \Input::get('to');
        $brand_id = \Input::get('brand_id');

        // Default to todays date
        if(!$from or $from == "") {
            $from  = date("Y-m-d");
            $to  = date("Y-m-d");
        }

        $from  = $from . " 00:00:00";
        $too  = $to . "  23:59:59";

        error_log("hipreports_hipwifi_statistcsjson $sitename : $from : $to" );

        $stats = new \Statistics();
        $statsdata = $stats->getStatsData($sitename, $from, $to, $brand_id);


        // error_log("hipreports_hipwifi_statistcsjson : " . print_r($statsdata, true));

        return \Response::json($statsdata);
    }

    ////////////////////////////////// DASHBOARD //////////////////////////////////

    public function hipreports_hipwifi_dashboarddatajson() {

        error_log("hipreports_hipwifi_dashboarddatajson");
        $reportObj = new \Reports();

        $data = array();

        $data["sessions"] = $reportObj->getWifiDashboardSessions();
        $data["dwelltime"] = $reportObj->getWifiDashboardDwellTime();

        return \Response::json($data);
    }

    ////////////////////////////////// BRAND //////////////////////////////////

    public function hipreports_hipwifi_downloaduserprofiledata() {

        error_log("hipreports_hipwifi_downloaduserprofiledata");
        $reportObj = new \Reports();
        $data = array();
        $brand_id = \Input::get('brand_id');
        $brandcode = \Brand::find($brand_id)->code;
        $brandname = \Brand::find($brand_id)->name;
        $reportperiod = \Input::get('reportperiod');
        $from = \Input::get('from');
        $to = \Input::get('to');

        $connection = \Brand::find($brand_id)->remotedb->dbconnection;
        $filepath = $reportObj->getUserProfileDataForDownload($brandname, $reportperiod, $from, $to, $connection);
        error_log("hipreports_hipwifi_downloaduserprofiledata . filepath = $filepath");

        // return $filepath;
        return \Response::json($filepath);

    }

    public function hipreports_hipwifi_downloadlistcustomerusage() {

        error_log("hipreports_hipwifi_listcustomerusage");
        $reportObj = new \Reports();
        $data = array();
        $brand_id = \Input::get('brand_id');
        $brandcode = \Brand::find($brand_id)->code;
        $brandname = \Brand::find($brand_id)->name;
        $reportperiod = \Input::get('reportperiod');
        $from = \Input::get('from');
        $to = \Input::get('to');

        $connection = \Brand::find($brand_id)->remotedb->dbconnection;
        $filepath = $reportObj->getListCustomerUsageFordownload($brandname, $reportperiod, $from, $to, $connection);

        // return $filepath;
        return \Response::json($filepath);

    }


    public function hipreports_hipwifi_branddatajsonsingle() {

        $reportObj = new \Reports();
        $data = array();
        $brand_id = \Input::get('brand_id');
        $brandcode = \Brand::find($brand_id)->code;
        $brandname = \Brand::find($brand_id)->name;

        \Log::info("hipreports_hipwifi_branddatajsonsingle : brandname = $brandname");

        \Log::info("hipreports_hipwifi_branddatajsonsingle : brandname = $brandname");
        $reportperiod = \Input::get('reportperiod');
        $from = \Input::get('from');
        $to = \Input::get('to');

        if($reportperiod == "daterange") {
          $reportperiod = "tmp_period_" . $brandname . "_" . $from . "_" . $to ;
          \Log::info("hipreports_hipwifi_branddatajsonsingle : reportperiod = $reportperiod");
        }

        $queryname = \Input::get('queryname');

        \Log::info("hipreports_hipwifi_branddatajsonsingle : queryName = $queryname");

        \Log::info("hipreports_hipwifi_branddatajsonsingle : reportperiod : $reportperiod");
        \Log::info("hipreports_hipwifi_branddatajsonsingle : brand_id : $brand_id");
        \Log::info("hipreports_hipwifi_branddatajsonsingle : brand_id : $brand_id");
        \Log::info("hipreports_hipwifi_branddatajsonsingle : from : $from");
        \Log::info("hipreports_hipwifi_branddatajsonsingle : to : $to");

        // $data["highest5Sessions"] = $reportObj->gethighest5Sessions($brandcode, $reportperiod);
        // $data["highest5Uniquedata"] = $reportObj->gethighest5Uniquedata($brandcode, $reportperiod);
        // $data["highest5AvgTimedata"] = $reportObj->gethighest5AvgTimedata($brandcode, $reportperiod);
        // $data["lowest5Sessionsdata"] = $reportObj->getlowest5Sessionsdata($brandcode, $reportperiod);
        // $data["lowest5Uniquedata"] = $reportObj->getlowest5Uniquedata($brandcode, $reportperiod);
        // $data["lowest5AvgTimedata"] = $reportObj->getlowest5AvgTimedata($brandcode, $reportperiod);
        // $data["biggestSessionIncreasedata"] = $reportObj->getbiggestSessionIncreasedata($brandcode, $reportperiod);
        // $data["biggestUniquesIncreasedata"] = $reportObj->getbiggestUniquesIncreasedata($brandcode, $reportperiod);
        // $data["biggestAdminDropdata"] = $reportObj->getbiggestAdminDropdata($brandcode, $reportperiod);
        // $data["biggestSessionDecreasedata"] = $reportObj->getbiggestSessionDecreasedata($brandcode, $reportperiod);
        // $data["biggestUniquesDecreasedata"] = $reportObj->getbiggestUniquesDecreasedata($brandcode, $reportperiod);
        // $data["biggestAdminIncreasedata"] = $reportObj->getbiggestAdminIncreasedata($brandcode, $reportperiod);
        // $data["totalDwellTimedata"] = $reportObj->gettotalDwellTimedata($from, $to, $brandcode);

        // Get Brand Data Averages For Demographics and usage
        $nasid = $brandcode;

        \Log::info("hipreports_hipwifi_branddatajsonsingle : nasid = $nasid");
        // if(preg_match('/.*SAB.*$/i', $brandcode)) {
        //     $nasid = "1GBSab_Rajaraja";
        // } else {
        //     $nasid = "DUMMY2_donotdelete";
        // }

        $brandcodes = array($brandcode);

        \Log::info("hipreports_hipwifi_branddatajsonsingle : queryName is: = $queryname");

        if ($queryname == "builddaterangereporttable") {
            $data = $reportObj->buildDateRangeReportTable($from, $to, $brandname);

        } else if ($queryname == "age") {
            $txt = "The Query name is: Age";
            $txt .= "\n ReportPeriod: ".$reportperiod;
            $txt .= "\n from: ".$from;
            $txt .= "\n to: ".$to;
            $txt .= "\n brandcode: ".$brandcode;

            $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

            $data = $reportObj->getAge($reportperiod, $from, $to, $nasid, $brandcodes, 1);

            $txt = "The age returned was : ".json_encode($data);

            \Log::info("hipreports_hipwifi_branddatajsonsingle : age report filtered data = $txt");

            $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        } else if ($queryname == "gender") {
            $data = $reportObj->getGender($reportperiod, $from, $to, $nasid, $brandcodes, 1);
        } else if ($queryname == "income") {
            $data = $reportObj->getIncome($reportperiod, $from, $to, $nasid, $brandcodes, 1);
        } else if ($queryname == "newvsreturning") {
            $data = $reportObj->getNewVsReturningForBrand($reportperiod, $from, $to, $brandcode);
        } else if ($queryname == "avgjamvenuedwelltime") {
            $data = $reportObj->getAveJamVenueDwellTime($reportperiod, $from, $to, $nasid);
        } else if ($queryname == "brandtotalstoredwelltime") {
            $data = $reportObj->getStoreDwellTime($from, $to, $nasid, $brandcodes, 1);
        } else if ($queryname == "brandtotalwifisessions") {
            $data = $reportObj->getTotalWifiSessions($from, $to, $nasid, $brandcodes, 1);
        } else if ($queryname == "brandtotalwifidatatotal") {
            $data = $reportObj->getWifiDataTotal($from, $to, $nasid, $brandcodes, 1);
        } else if ($queryname == "brandtotalnumberofpeople") {
            $data = $reportObj->getNumberOfPeople($reportperiod, $from, $to, $nasid, $brandcodes, 1);
        } else if ($queryname == "brandtotalfirsttimeusers") {
            $data = $reportObj->getFirstTimeUsers($reportperiod, $from, $to, $nasid, $brandcodes, 1, true);
        } else if ($queryname == "brandvenueavgdatapersession") {
            $data = $reportObj->getAvgDataPerSession($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "brandvenueavgtimepersession") {
            $data = $reportObj->getAvgTimePerSession($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "dwelltimebysessionduration") {
            $data = $reportObj->getDwellTimeBySessionDuration($reportperiod, $from, $to, $nasid, $brandname, 1);
        } else if ($queryname == "dwelltimebyhour") {
            $data = $reportObj->getCustomersByTimePeriod($reportperiod, $from, $to, $nasid, $brandname, 1);
        } else if ($queryname == "brandavguptime") {
            $data = $reportObj->getBrandUptime($reportperiod, $from, $to, $nasid, $brandname, 1);
        }

        return \Response::json($data);

    }

    public function hipreports_hipwifi_branddatajson() {


        $reportObj = new \Reports();
        $data = array();
        $brand_id = \Input::get('brand_id');
        $brandcode = \Brand::find($brand_id)->code;
        $brandname = \Brand::find($brand_id)->name;
        $reportperiod = \Input::get('reportperiod');
        $from = \Input::get('from');
        $to = \Input::get('to');

        if($reportperiod == "daterange") {
          $reportperiod = "tmp_period_" . $brandname . "_" . $from . "_" . $to ;
        }

        error_log("hipreports_hipwifi_branddatajson : reportperiod : $reportperiod");
        error_log("hipreports_hipwifi_branddatajson : brand_id : $brand_id");
        error_log("hipreports_hipwifi_branddatajson : brand_id : $brand_id");
        error_log("hipreports_hipwifi_branddatajson : from : $from");
        error_log("hipreports_hipwifi_branddatajson : to : $to");

        $data["highest5Sessions"] = $reportObj->gethighest5Sessions($brandcode, $reportperiod);
        $data["highest5Uniquedata"] = $reportObj->gethighest5Uniquedata($brandcode, $reportperiod);
        $data["highest5AvgTimedata"] = $reportObj->gethighest5AvgTimedata($brandcode, $reportperiod);
        $data["lowest5Sessionsdata"] = $reportObj->getlowest5Sessionsdata($brandcode, $reportperiod);
        $data["lowest5Uniquedata"] = $reportObj->getlowest5Uniquedata($brandcode, $reportperiod);
        $data["lowest5AvgTimedata"] = $reportObj->getlowest5AvgTimedata($brandcode, $reportperiod);
        $data["biggestSessionIncreasedata"] = $reportObj->getbiggestSessionIncreasedata($brandcode, $reportperiod);
        $data["biggestUniquesIncreasedata"] = $reportObj->getbiggestUniquesIncreasedata($brandcode, $reportperiod);
        $data["biggestAdminDropdata"] = $reportObj->getbiggestAdminDropdata($brandcode, $reportperiod);
        $data["biggestSessionDecreasedata"] = $reportObj->getbiggestSessionDecreasedata($brandcode, $reportperiod);
        $data["biggestUniquesDecreasedata"] = $reportObj->getbiggestUniquesDecreasedata($brandcode, $reportperiod);
        $data["biggestAdminIncreasedata"] = $reportObj->getbiggestAdminIncreasedata($brandcode, $reportperiod);
        $data["totalDwellTimedata"] = $reportObj->gettotalDwellTimedata($from, $to, $brandcode);

        return \Response::json($data);

    }

    ////////////////////////////////// VENUE //////////////////////////////////

    public function hipreports_hipwifi_venuedatajsonsingle() {

        error_log("hipreports_hipwifi_venuedatajsonsingle");
        $reportObj = new \Reports();

        $data = array();
        $venue_id = \Input::get('venue_id');
        error_log("hipreports_hipwifi_venuedatajsonsingle venue_id : $venue_id");

        $sitename = \Venue::find($venue_id)->sitename;
        $nasid = preg_replace("/ /", "_", $sitename);
        error_log("hipreports_hipwifi_venuedatajsonsingle :xxxxxxxxxxxxxxxxxxxxxxxx sitename : $sitename");
        error_log("hipreports_hipwifi_venuedatajsonsingle :xxxxxxxxxxxxxxxxxxxxxxxx nasid : $nasid");


        $reportperiod = \Input::get('reportperiod');
        $from = \Input::get('from');
        $to = \Input::get('to');
        $queryname = \Input::get('queryname');

        // $venuebrandcompare = \Input::get('venuebrandcompare');
        error_log("hipreports_hipwifi_venuedatajsonsingle queryname : $queryname" );
        // error_log("hipreports_hipwifi_venuedatajsonsingle reportperiod : $reportperiod" );

        // NOTE FOR WHEN WE IMPLEMENT ORGANISATIONS - i.e. groups of brands:
        // If the brand belongs to an organisation then we compare to all venues within the organisation, else we compare to venues within the brand.

        $brandname = preg_replace("/(^.*)(_)(.*$)/", "$1", $nasid);

        $brand = \DB::table("brands")->where('name', 'like', $brandname . "%")->first();

        $brandcodes = array($brand->code);

        if($reportperiod == "daterange") {
          $reportperiod = "tmp_period_" . $brandname . "_" . $from . "_" . $to ;
        }
        error_log("hipreports_hipwifi_venuedatajsonsingle :reportperiod  : $reportperiod");

        if ($queryname == "builddaterangereporttable") {
            $data = $reportObj->buildDateRangeReportTable($from, $to, $brandname);
        } else if ($queryname == "venuedetails") {
            error_log("hipreports_hipwifi_venuedatajsonsingle venuedetails : sitename : $sitename");
            $data["sitename"] = $sitename;
        } else if ($queryname == "age") {
            $txt = "The Query name is: ".$queryname;
            $txt .= "\n ReportPeriod: ".$reportperiod;
            $txt .= "\n from: ".$from;
            $txt .= "\n to: ".$to;
            $txt .= "\n nasid: ".$nasid;
            $txt .= "\n brandcodes: ".json_encode($brandcodes);

            $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

            $data = $reportObj->getAge($reportperiod, $from, $to, $nasid, $brandcodes);

            $txt = "The age returned was : ".json_encode($data);
            $myfile = file_put_contents('hipreports_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        } else if ($queryname == "gender") {
            $data = $reportObj->getGender($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "income") {
            $data = $reportObj->getIncome($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "avgjamvenuedwelltime") {
            $data = $reportObj->getAveJamVenueDwellTime($reportperiod, $from, $to, $nasid);
        } else if ($queryname == "newvsreturning") {
            $data["graph"] = $reportObj->getNewVsReturning($reportperiod, $from, $to, $nasid);   
            $data["firsttimeusers"] = $reportObj->getFirstTimeUsers($reportperiod, $from, $to, $nasid, $brandcodes);
            $data["numberofpeople"] = $reportObj->getNumberOfPeople($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "storedwelltime") {
            $data = $reportObj->getStoreDwellTime($from, $to, $nasid, $brandcodes);
        } else if ($queryname == "totalwifisessions") {
            $data = $reportObj->getTotalWifiSessions($from, $to, $nasid, $brandcodes);
        } else if ($queryname == "wifidatatotal") {
            $data = $reportObj->getWifiDataTotal($from, $to, $nasid, $brandcodes);
        } else if ($queryname == "numberofpeople") {
            $data = $reportObj->getNumberOfPeople($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "firsttimeusers") { 
            $data = $reportObj->getFirstTimeUsers($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "avgdatapersession") {
            $data = $reportObj->getAvgDataPerSession($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "avgtimepersession") {
            $data = $reportObj->getAvgTimePerSession($reportperiod, $from, $to, $nasid, $brandcodes);
        } else if ($queryname == "dwelltimebysessionduration") {
            $data = $reportObj->getDwellTimeBySessionDuration($reportperiod, $from, $to, $nasid, $brandname);
        } else if ($queryname == "dwelltimebyhour") {
            $data = $reportObj->getCustomersByTimePeriod($reportperiod, $from, $to, $nasid, $brandname);
        } else if ($queryname == "uptime") {
            $data = $reportObj->getVenueUptimeData($reportperiod, $from, $to, $nasid, $brandname);
        }

        return \Response::json($data);
    }

    public function hipreports_hipwifi_venuedatajson() {

        error_log("hipreports_hipwifi_venuedatajson");
        $reportObj = new \Reports();

        $data = array();
        $venue_id = \Input::get('venue_id');
        error_log("hipreports_hipwifi_venuedatajson venue_id : $venue_id");

        $sitename = \Venue::find($venue_id)->sitename;
        $nasid = preg_replace("/ /", "_", $sitename);
        error_log("hipreports_hipwifi_venuedatajson :xxxxxxxxxxxxxxxxxxxxxxxx sitename : $sitename");
        error_log("hipreports_hipwifi_venuedatajson :xxxxxxxxxxxxxxxxxxxxxxxx nasid : $nasid");


        $reportperiod = \Input::get('reportperiod');
        $from = \Input::get('from');
        $to = \Input::get('to');

        // $venuebrandcompare = \Input::get('venuebrandcompare');
        // error_log("hipreports_hipwifi_venuedatajson venuebrandcompare : $venuebrandcompare" );
        // error_log("hipreports_hipwifi_venuedatajson reportperiod : $reportperiod" );

        // NOTE FOR WHEN WE IMPLEMENT ORGANISATIONS - i.e. groups of brands:
        // If the brand belongs to an organisation then we compare to all venues within the organisation, else we compare to venues within the brand.

        // if($venuebrandcompare == "thisbrandonly") {
            $brandname = preg_replace("/(^.*)(_)(.*$)/", "$1", $nasid);
            $brand = \DB::table("brands")->where('name', 'like', $brandname . "%")->first();
            $brandcodes = array($brand->code);
        // } else {
        //     $brandObj = new \Brand();
        //     $brandcodes = $brandObj->getBrandCodesForUser(\Auth::user()->id);
        // }
        error_log("hipreports_hipwifi_venuedatajson brandcodes : " . print_r($brandcodes, true));

        $data["sitename"] = $sitename;
        $data["age"] = $reportObj->getAge($reportperiod, $from, $to, $nasid, $brandcodes);
        $data["gender"] = $reportObj->getGender($reportperiod, $from, $to, $nasid, $brandcodes);
        $data["income"] = $reportObj->getIncome($reportperiod, $from, $to, $nasid, $brandcodes);
        $data["avgjamvenuedwelltime"] = $reportObj->getAveJamVenueDwellTime($reportperiod, $from, $to, $nasid);
        $data["newvsreturning"] = $reportObj->getNewVsReturning($reportperiod, $from, $to, $nasid);
        // echo "<pre>" . print_r($data["newvsreturning"]) . "</pre>";

        $data["storedwelltime"] = $reportObj->getStoreDwellTime($from, $to, $nasid, $brandcodes);
        $data["totalwifisessions"] = $reportObj->getTotalWifiSessions($from, $to, $nasid, $brandcodes);
        $data["wifidatatotal"] = $reportObj->getWifiDataTotal($from, $to, $nasid, $brandcodes);
        $data["numberofpeople"] = $reportObj->getNumberOfPeople($reportperiod, $from, $to, $nasid, $brandcodes);
        $data["firsttimeusers"] = $reportObj->getFirstTimeUsers($reportperiod, $from, $to, $nasid, $brandcodes);
        $data["avgdatapersession"] = $reportObj->getAvgDataPerSession($reportperiod, $from, $to, $nasid, $brandcodes);
        $data["avgtimepersession"] = $reportObj->getAvgTimePerSession($reportperiod, $from, $to, $nasid, $brandcodes);

        return \Response::json($data);
    }

///////////////////////////////////////////////////////////////

// Name: hipwifi_Brand_Pdf_Download

// Purpose:  To convert hipwifi_brand to a pdf format and enable download option
// using Dom pdf

// It receive html content and pdf name from the form submission, pass it to the  // download preview page and to the download page. Enable pdf download using Dom  // pdf.

// Created at 15-10-2016 by Prajeesh

// Last updated at 26-10-2016 by Prajeesh

//////////////////////////////////////////////////////////////

    public function hipwifi_Brand_Pdf_Download()
    {
        $input_data                 =       Input::all();
        $data                       =       array();
        $data['currentMenuItem'] = "Dashboard";
        $data['fusionchartElementOne'] =  $input_data['myPageone'];
        $data['fusionchartElementTwo'] =  $input_data['myPagetwo'];
        $data['fusionchartElementThree'] =  $input_data['myPagethree'];
        $data['report_name']             = $input_data['report_name'];

        if(isset($input_data['printtoken'])) {

            $data['totalWifiSessions'] = $input_data['totalWifiSessions'];
            $data['wifiDataTotal'] = $input_data['wifiDataTotal'];
            $data['avgNumberofPeople'] = $input_data['avgNumberofPeople'];
            $data['avgFirstTimeUsers'] = $input_data['avgFirstTimeUsers'];
            $data['avgDataPerSession'] = $input_data['avgDataPerSession'];
            $data['avgTimePerSession'] = $input_data['avgTimePerSession'];

            // return \View::make('hipreports.hipwifi_brand_download', $data);
            $dompdf = \PDF::loadView('hipreports.hipwifi_brand_download', $data);
//            $pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'portrait');
//            $pdf->get_option('enable_css_float');
            //$filename               =       $data['report_name'].".pdf";
            $filename = preg_replace( "/\s+/", " ", $data['report_name'].".pdf" );
            $filename = str_ireplace(" ", "_", $filename);

//            $filename           =       "graphview".strtotime(date('h:i:s')).".pdf";
            return $dompdf->download($filename);
           // $pdf = $dompdf->output();
           // $file_location = base_path()."/public/fc_images/pdfreport/".$filename;
           // file_put_contents($file_location,$pdf);
        } else {

            $data['printButtonToken']   =   TRUE;
            return \View::make('hipreports.hipwifi_brand_download_preview', $data);
        }
    }

    ///////////////////////////////////////////////////////////////

// Name: hipwifi_Venue_Pdf_Download

// Purpose:  To convert hipwifi_Venue to a pdf format and enable download option
// using Dom pdf

// It receive html content and pdf name from the form submission, pass it to the  // download preview page and to the download page. Enable pdf download using Dom  // pdf.

// Created at 31-10-2016 by Prajeesh

// Last updated at 2-11-2016 by Prajeesh

//////////////////////////////////////////////////////////////

    public function hipwifi_Venue_Pdf_Download()
    {
        $input_data                 =       Input::all();
        $data                       =       array();
        $data['currentMenuItem'] = "Dashboard";
        $data['fusionchartElementOne'] =  $input_data['myVenuePageone'];
        $data['fusionchartElementTwo'] =  $input_data['myVenuePagetwo'];
        $data['fusionchartElementThree'] =  $input_data['myVenuePagethree'];
        $data['report_name_venue']             = $input_data['report_name_venue'];

        if(isset($input_data['printtoken'])) {

            $data['totalWifiSessions'] = $input_data['totalWifiSessions'];

            $data['avgWifiSessions'] = $input_data['avgWifiSessions'];

            $data['wifiDataTotal'] = $input_data['wifiDataTotal'];

            $data['avgWifiDataTotal'] = $input_data['avgWifiDataTotal'];

            $data['totalNumberofPeople'] = $input_data['totalNumberofPeople'];

            $data['avgNumberofPeople'] = $input_data['avgNumberofPeople'];

            $data['avgFirstTimeUsers'] = $input_data['avgFirstTimeUsers'];

            $data['venueAvgDataPerSession'] = $input_data['venueAvgDataPerSession'];

            $data['totalTirstTimeUsers'] = $input_data['totalTirstTimeUsers'];

            $data['brandAvgDataPerSession'] = $input_data['brandAvgDataPerSession'];

            $data['venueAvgTimePerSession'] = $input_data['venueAvgTimePerSession'];

            $data['brandAvgTimePerSession'] = $input_data['brandAvgTimePerSession'];

            //return \View::make('hipreports.hipwifi_venue_download', $data);
            $dompdf = \PDF::loadView('hipreports.hipwifi_venue_download', $data);
//            $pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'portrait');
//            $pdf->get_option('enable_css_float');
            //$filename               =       $data['report_name'].".pdf";
            $filename = preg_replace( "/\s+/", " ", $data['report_name_venue'].".pdf" );
            $filename = str_ireplace(" ", "_", $filename);

//            $filename           =       "graphview".strtotime(date('h:i:s')).".pdf";
            return $dompdf->download($filename);
           // $pdf = $dompdf->output();
           // $file_location = base_path()."/public/fc_images/pdfreport/".$filename;
           // file_put_contents($file_location,$pdf);
        } else {

            //die($data);

            $data['printButtonToken']   =   TRUE;
            return \View::make('hipreports.hipwifi_venue_download_preview', $data);
        }
    }

    public function convertSvgToImage()
    {
        $data                       =       array();
        $input_data                 =       Input::all();

        $fusionchart_spans                  =   $input_data['fusionchartspans'];
        $chart_svg                          =   "";
        $images                         =   array();
        $svgs                           =   array();
        $i                              =   0;
        //converting svg code to image
        foreach($fusionchart_spans as $key => $charts) {
            $i++;
            $chart_svg                 .=       $charts;
            $path                       =       base_path()."/public/fc_images/svg_temp/";
            $fileName                   =       $key.strtotime(date('H:i:s'));
            $svg_file                   =       fopen($path.$fileName.".svg","w");
            fwrite($svg_file, $charts);
            fclose($svg_file);
            $svgs["img_".$key]               =   $fileName;
        }


/////////////for dev1 pdfinvestigation /////////////
            $svgpath                    =   base_path().'/public/fc_images/svg_temp/';
            $imgpath                    =   base_path().'/public/fc_images/image_temp/';
        foreach($svgs as $key => $val) {
            //shell excution for svg to image
            $cmd                        =   'inkscape -f '.$svgpath.$val.'.svg -e '.$imgpath.$val.'.png';
            shell_exec($cmd);
            $images[$key]               =   $val.".png";
            unlink($svgpath.$val.'.svg');
        }

        $response                       =   array('status' => "success" , 'result_img' => $images);
        print_r(json_encode($response));exit;

    }

}