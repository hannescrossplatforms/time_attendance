<?php

namespace hiprm;

// use BaseController;

class HiprmController extends \BaseController {

    public function showDashboard()
    {

        $data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('hiprm.showdashboard')->with('data', $data);
    }

    public function showBrands()
    {
        $data = array();
        $brands = \Brand::all();

        $data['brands'] = $brands;
        $data['currentMenuItem'] = "Brand Management";

        return \View::make('hiprm.showbrands')->with('data', $data);
    }

    public function addBrand()
    {
        error_log('addBrand');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['edit'] = false;
        $data['brand'] = false;

        return \View::make('hiprm.addbrand')->with('data', $data);
    }

    public function addBrandSave()
    {
        error_log('addBrandSave');
        $brand = new \Brand();

        $brand->name = \Input::get('name');
        $brand->save();

        return \Redirect::route('hiprm_showbrands');
    }

    public function editBrand($id)
    {
        error_log('editBrand');
        $data = array();
        $data['brand'] = \Brand::find($id);

        $data['currentMenuItem'] = "Brand Management";
        $data['edit'] = true;

        return \View::make('hiprm.addbrand')->with('data', $data);
    }

    public function editBrandSave()
    {
        error_log('editBrandSave');
        $id = \Input::get('id');
        $brand =  \Brand::find($id);

        $brand->name = \Input::get('name');
        $brand->save();

        return \Redirect::route('hiprm_showbrands');
    }

    public function showUsers()
    {
        $data = array();
        error_log("In showUsers");

        $users = \DB::table('users')
            ->join('users_products', function($join)
            {
                $join->on('users.id', '=', 'users_products.user_id')
                     ->where('users_products.product_id', '=', 2);
            })
            ->get();
;
        foreach($users as $user) {
            error_log("wwwww " . $user->fullname);
        }

        $usersStruct = array(); // This is to keep the data structure in line with UserAdmin
        $usersStruct['users'] = $users;

        $data['usersJason'] = json_encode($usersStruct);
        $data['currentMenuItem'] = "Admin Management";

        return \View::make('hiprm.showusers')->with('data', $data);
    }


    public function addUser()
    {
        $data = array();
        $data['user'] =  $user = new \User();
        $data['edit'] = false;
        $data['currentMenuItem'] = "Brand Management";

        $brands = \Brand::All();
        $data['allbrands'] = $brands;
        $data['brandArray'] = array(); //TAKE THIS OUT WHEN DNE

        $data['products']['HipWifi'] = false;
        $data['products']['HipRM'] = false;
        $data['products']['HipJAM'] = false;

        $data['permissions']['ques_rw'] = false;
        $data['permissions']['media_rw'] = false;
        $data['permissions']['uru_rw'] = false;
        $data['permissions']['rep_rw'] = false;

        $data['user']->level_code = "Brand Admin";

        foreach($data['allbrands'] as $brand) {
            error_log("============= " . $brand->name);
        }

        return \View::make('hiprm.adduser')->with('data', $data);
    }

    public function showMedias()
    {

        $data = array();
        $data['currentMenuItem'] = "Media Management";


        return \View::make('hiprm.showmedias')->with('data', $data);
    }

    public function addMedia()
    {
        $data = array();
        $data['currentMenuItem'] = "Media Management";
        $data['edit'] = false;
        $data['media'] = false;

        return \View::make('hiprm.addmedia')->with('data', $data);
    }

    public function editMedia($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Media Management";
        $data['edit'] = true;
        $data['media'] = false;

        return \View::make('hiprm.addmedia')->with('data', $data);
    }

    public function showInsights()
    {

        $data = array();
        $data['currentMenuItem'] = "Insight Management";


        return \View::make('hiprm.showinsights')->with('data', $data);
    }

    public function addInsight()
    {
        $data = array();
        $data['currentMenuItem'] = "Insight Management";
        $data['edit'] = false;
        $data['insight'] = false;

        return \View::make('hiprm.addinsight')->with('data', $data);
    }

    public function editInsight($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Insight Management";
        $data['edit'] = true;
        $data['insight'] = false;

        return \View::make('hiprm.addinsight')->with('data', $data);
    }

    public function showReports()
    {

        $data = array();
        $data['currentMenuItem'] = "Hip Reports";


        return \View::make('hiprm.showreports')->with('data', $data);
    }
}