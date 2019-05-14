<?php

namespace hipwifi;

// use BaseController;

class HipwifiWifiUsersController extends \BaseController {

    public function showWifiUsers()
    {

        $data = array();
        $data['currentMenuItem'] = "User Management";

        return \View::make('hipwifi.showwifiusers')->with('data', $data);
    }
}