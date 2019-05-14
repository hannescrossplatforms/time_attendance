<?php

class TestController extends BaseController {

    /**
     * Show the profile for the given user.
     */
    public function showMessage($param)
    {
        $message = "This is  message from TestController with param = $param";

        return View::make('test')->with('message', $message);
    }


    public function showUsers()
    {
        $userObj = new Admin("radius");
        $users = $userObj->getAdmins();
        // $users = Admin::all();

        return View::make('test')->with('users', $users);
    }

    public function general_test()
    {
        echo "This is the general test method";
        // $utils = new \Utils();
        $connection = \Utils::getEngageDbConnection();
        echo " ======== connection = $connection =========== ";

        $beaconcount = \Beacon::get()->count();
        echo "beaconcount = $beaconcount ===========";

        dd();
    }

}