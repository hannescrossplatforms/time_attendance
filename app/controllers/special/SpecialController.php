<?php

namespace special;

class SpecialController extends \BaseController {

    /**
     * Show the profile for the given user.
     */
    // public $menuitem = "hipwifi";

    public function synchVenues()
    {
    	error_log("synchVenues");
    	$data = array();

    	$data['databases'] = \Remotedb::All();

        return \View::make('special.synchvenues')->with('data', $data);
    }

    public function generateReportsData() {

        $reportObj = new \Reports();
        $reportObj->generateDailyData(365);
        $reportObj = null;
        $reportObj = new \Reports();
        $reportObj->generateAggregatedVenueData("rep7day");
        $reportObj = null;
        $reportObj = new \Reports();
        $reportObj->generateAggregatedVenueData("repthismonth");
        $reportObj = null;
        $reportObj = new \Reports();
        $reportObj->generateAggregatedVenueData("replastmonth");
        $reportObj = null;
        $reportObj = new \Reports();

        $data['Message'] = "Report data has been generated";

        error_log("generateReportsData COMPLETED SUCCESSFULLY");

        return \View::make('special.generatereportsdata')->with('data', $data);

    }

    public function dropReportsTmpTables() {
        $reportObj = new \Reports();

        $reportObj->dropReportsTmpTables();

        $data['Message'] = "Reports tmp tables have been dropped";

        return \View::make('special.dropteportstmptables')->with('data', $data);
    }

    public function synchVenuesExecute()
    {
    	error_log("synchVenuesExecute");

    	$data = array();
    	$messages = array();

    	$remotedb_id = \Input::get('remotedb_id');
    	$synch = \Input::get('synch');

        error_log("synchVenuesExecute : $remotedb_id : $synch");

        $venue = new \Venue();

        if($synch == "to") {
            error_log("synchVenuesExecute : TO");

            $venue->synchToRadius($remotedb_id);

        } elseif ($synch == "from") {
            error_log("synchVenuesExecute : FROM");

            $messages =$venue->synchOldLocationCodesFromRadius($remotedb_id);

        } else {
            error_log("synchVenuesExecute : Invalid synch flag");

    		array_push($messages, "Invalid synch flag");

    	}

    	array_push($messages, "First message");
    	array_push($messages, "Second message");

        return \Response::json($messages);
    }

    public function importBrands()
    {
        error_log("importBrands");
        $data = array();

        $data['databases'] = \Remotedb::All();

        return \View::make('special.importbrands')->with('data', $data);
    }

    public function importBrandsExecute()
    {

        $remotedb_id = \Input::get('remotedb_id');
        
        error_log("importBrandsExecute remotedb_id : " . $remotedb_id);

        $brand = new \Brand();

        $brand->importBrandsFromRadius($remotedb_id);

        $messages = "Brands imported";

        return \Redirect::route('special_importbrands');
    }


}