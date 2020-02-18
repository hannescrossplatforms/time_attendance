<?php

namespace hipwifi;

// use BaseController;

class HipwifiMonitoringController extends \BaseController {

    public function showMonitoring($json = null)
    {

        $data = array();
        $data['currentMenuItem'] = "Venue Monitoring";

        $mikrotik = new \Mikrotik();
        $venues = $mikrotik->getVenueMonitoringForUser();
        // Add code do delete comments for all online venues

        $data['venuesJson'] = json_encode($venues);
        //dd($data['venuesJson']);

        if($json) {
            error_log("showMonitoring : returning json" );
            return \Response::json($data['venuesJason']);

        } else {
            error_log("showMonitoring : returning NON json" );
            return \View::make('hipwifi.showmonitoring')->with('data', $data);
            
        }
        //dd($data);

        return \View::make('hipwifi.showmonitoring')->with('data', $data);
    }

    public function populateMonitoringPage() {

        $data = array();
        $data['currentMenuItem'] = "Venue Monitoring";

        $mikrotik = new \Mikrotik();
        $venues = $mikrotik->getVenueMonitoringForUser();
        // Add code do delete comments for all online venues

        $data['venuesJson'] = json_encode($venues);
        //dd($data['venuesJson']);

        // if($json) {
        //     error_log("showMonitoring : returning json" );
        //     return \Response::json($data['venuesJason']);

        // } else {
        //     error_log("showMonitoring : returning NON json" );
        //     return \View::make('partials.wifi_monitoring_page')->with('data', $data);
            
        // }

        return \View::make('partials.wifi_monitoring_page')->with('data', $data);
    }

    public function showTabletposPrinters(){
        $data = \Input::all();
        //dd($data);
        $venueid = $data["venueid"];
        $printer = new \Tabletposprinter();
        $printers = $printer->getPrintersForVenue($venueid);
        $modprinterarray = array();
        foreach ($printers as $printer) {
            $updatetime = $printer->updated_at;
            $unixtime = strtotime($updatetime);
            $mik = new \Mikrotik();
            $timedispformat = $mik->secondsToTime(time() - $unixtime);
            $printer->lastseen = $timedispformat;
            array_push($modprinterarray, $printer);
        }
        return json_encode($modprinterarray);
    }
}