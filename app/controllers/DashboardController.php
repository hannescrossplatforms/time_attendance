<?php

class DashboardController extends BaseController {

    /**
     * Show the profile for the given user.
     */
    // public $menuitem = "hipwifi";

    public function showDashboard()
    {
        $message = "This is a message from DashboardController";
        $data = array();
        $data["user"] = new \User();
        $data['currentMenuItem'] = "";

        return View::make('dashboard')->with('data', $data);
    }

}