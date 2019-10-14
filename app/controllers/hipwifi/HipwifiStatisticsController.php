<?php

namespace hipwifi;

// use BaseController;

class HipwifiStatisticsController extends \BaseController {

    public function showStatistics($json = null)
    {

        $data = array();
        $data['currentMenuItem'] = "Statistics";

		$sitename = \Input::get('sitename');
        $from = \Input::get('from');
        $to = \Input::get('to');

        // Default to todays date
        if(!$from or $from == "") {
	        $from  = date("Y-m-d");
	        $to  = date("Y-m-d");
        } 

        $data["from"] = $from;
        $data["to"] = $to;

        $from  = $from . " 00:00:00";
        $too  = $to . "  23:59:59";        	

        $stats = new \Statistics();

        $statsdata = $stats->getStatsData($sitename, $from, $to);

        $data['statsdata'] = json_encode($statsdata);

        if($json) {
            error_log("showStatistics : returning json : $from : $to" );
            return \Response::json($data['statsdata']);

        } else {
            error_log("showStatistics : returning NON json : " . $data["from"]);
            return \View::make('hipwifi.showstatistics')->with('data', $data);
            
        }
    }

    public function requestStatsData() {
        $stats = new \Statistics();
        
        $sitename = \Input::get('sitename');
        $from = \Input::get('from');
        $to = \Input::get('to');

        // Default to todays date
        if(!$from or $from == "") {
	        $from  = date("Y-m-d");
	        $to  = date("Y-m-d");
        } 

        $statsdata = $stats->getStatsData($sitename, $from, $to);
        header('Content-type: application/json');
        return \Response::json(json_encode( $statsdata ));
    }
}