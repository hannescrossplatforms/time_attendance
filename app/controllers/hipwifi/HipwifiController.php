<?php

namespace hipwifi;

// use BaseController;
//This is a one line change to test merge

class HipwifiController extends \BaseController {

    public function showDashboard() 
    {
       
        $brand = new \Brand();
        $brands = $brand->getBrandsForUser(\Auth::user()->id);
        $brandnames = array();

        foreach ($brands as $brand) {
            $name = $brand->name;
            array_push($brandnames, $name);
        }

        
        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $checkVenues = new \Mikrotik();
        $writeVenueStatusToDb = $checkVenues->getAllVenueMonitoringData();      
        $activeVenueCount = array();
        $onlineVenueCount = array();
        $offlineVenueCount = array();
        foreach ($brands as $brand) {
            $activeVenuesPerBrand = \DB::table('venues')->where('location', 'like', '%'.$brand->code.'%')->where('ap_active', '=', 1)->where('device_type', '=', 'Mikrotik')->whereNull('deleted_at')->count();
            $onlineVenuesPerBrand = \DB::table('venues')->where('location', 'like', '%'.$brand->code.'%')->where('status', '=', 'online')->where('ap_active', '=', 1)->whereNull('deleted_at')->count();
            $offlineVenuesPerBrand = \DB::table('venues')->where('location', 'like', '%'.$brand->code.'%')->where('status', '=', 'offline')->where('ap_active', '=', 1)->whereNull('deleted_at')->count();
            array_push($activeVenueCount, $activeVenuesPerBrand);
            array_push($onlineVenueCount, $onlineVenuesPerBrand);
            array_push($offlineVenueCount, $offlineVenuesPerBrand);
        }
        
        
        return \View::make('hipwifi.showdashboard')->with(['data' => $data, 'brands' => $brandnames, 'count' => $activeVenueCount, 'onlinevenues' => $onlineVenueCount, 'offlinevenues' => $offlineVenueCount]);
    }

    public function showBrands($json = null)
    {
        error_log("showBrands wifi");

        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $brand = new \Brand();
        $wifibrands = $brand->getWifiBrandsForUser(\Auth::user()->id, "active");

        $data['brandsStruct'] = $wifibrands;    
        $brandsJason = json_encode($wifibrands);
        $data['brandsJason'] = $brandsJason;

        $data['brands'] = $wifibrands;
        // print_r($brands);
        $data['currentMenuItem'] = "Brand Management";

        if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($brandsJason);

        } else {
            return \View::make('hipwifi.showbrands')->with('data', $data);
        }
    }

    public function getInactiveBrands()
    {
        error_log("getInactiveBrands wifi");
        $data = array();
        $brand = new \Brand();
        $wifibrands = $brand->getWifiBrandsForUser(\Auth::user()->id, "inactive");
        $brandsJason = json_encode($wifibrands);

        return \Response::json($brandsJason);
    }

    function getFieldConfiguration($brand = null) 
    {

        $data = array();

        if($brand) { 

            $data['register_field'] = $brand->register_field;

            $data['f1_display'] = $brand->f1_display;
            $data['f1_placeholder'] = $brand->f1_placeholder;
            $data['f1_type'] = $brand->f1_type;

            $data['f2_display'] = $brand->f2_display;
            $data['f2_placeholder'] = $brand->f2_placeholder;
            $data['f2_type'] = $brand->f2_type;

            $data['f3_display'] = $brand->f3_display;
            $data['f3_placeholder'] = $brand->f3_placeholder;
            $data['f3_type'] = $brand->f3_type;

            $data['f4_display'] = $brand->f4_display;
            $data['f4_agegate'] = $brand->f4_agegate;
            $data['f4_type'] = $brand->f4_type;

            $data['f5_display'] = $brand->f5_display;
            $data['f5_placeholder'] = $brand->f5_placeholder;
            $data['f5_type'] = $brand->f5_type;

            $data['f6_display'] = $brand->f6_display;
            $data['f6_placeholder'] = $brand->f6_placeholder;
            $data['f6_url'] = $brand->f6_url;
            $data['f6_type'] = $brand->f6_type;

            $data['f7_display'] = $brand->f7_display;
            $data['f7_placeholder'] = $brand->f7_placeholder;
            $data['f7_type'] = $brand->f7_type;

            $data['f8_display'] = $brand->f8_display;
            $data['f8_placeholder'] = $brand->f8_placeholder;
            $data['f8_type'] = $brand->f8_type;

        } else { // Use defaults            

            $data['register_field'] = 1;

            $data['f1_display'] = "show";
            $data['f1_placeholder'] = "";
            $data['f1_type'] = "cellphone";

            $data['f2_display'] = "show";
            $data['f2_placeholder'] = "";
            $data['f2_type'] = "email";

            $data['f3_display'] = "hide";
            $data['f3_placeholder'] = "";
            $data['f3_type'] = "voucher";  
            
            $data['f4_display'] = "hide";
            $data['f4_agegate'] = "";
            $data['f4_type'] = "voucher"; 

            $data['f5_display'] = "hide";
            $data['f5_placeholder'] = "";
            $data['f5_type'] = "facebook"; 

            $data['f6_display'] = "hide";
            $data['f6_placeholder'] = "";
            $data['f6_url'] = "";
            $data['f6_type'] = "custombutton";

            $data['f7_display'] = "hide";
            $data['f7_placeholder'] = "";
            $data['f7_type'] = "instagram";

            $data['f8_display'] = "hide";
            $data['f8_placeholder'] = "";
            $data['f8_type'] = "twitter";            
        }

        // Build array lists for select dropdowns - currently not used
        $cellphone_select = array("value" => "cellphone", "text" => "Mobile Number", "selected" => "");
        $email_select = array("value" => "email", "text" => "Email Address", "selected" => "");
        $voucher_select = array("value" => "voucher", "text" => "Voucher Number", "selected" => "");

        $data['f1_select'] = $data['f2_select'] = $data['f3_select'] = array();

        $data['f1_select']['cellphone'] = $data['f2_select']['cellphone']   = $data['f3_select']['cellphone']   = $cellphone_select;
        $data['f1_select']['email']     = $data['f2_select']['email']       = $data['f3_select']['email']       = $email_select;
        $data['f1_select']['voucher']   = $data['f2_select']['voucher']     = $data['f3_select']['voucher']     = $voucher_select;

        $data['f1_select'][$data['f1_type']]['selected'] = "selected";
        $data['f2_select'][$data['f2_type']]['selected'] = "selected";
        $data['f3_select'][$data['f3_type']]['selected'] = "selected";

        return $data;

    }

    public function constructBrandRecord($brand, $input, $newrecord)
    {

        if($newrecord) {
            // $brand->isp_id = \Input::get('isp_id');
            $brand->remotedb_id = \Input::get('remotedb_id');
            // $brand->auth_token = exec("uuidgen -r");
        }
        error_log("constructBrandRecord : auth_token : " . $brand->auth_token);

        // $brand->name = \Input::get('name');
        // $brand->code = \Input::get('code');
        $brand->countrie_id = \Input::get('countrie_id');
        $brand->login_process = \Input::get('login_process');
        $brand->welcome = \Input::get('welcome');
        $brand->ssid = \Input::get('ssid');
        $brand->uru = \Input::get('uru');
        $brand->limit_type = \Input::get('limit_type');
        $brand->limit = \Input::get('limit');
        $brand->terms = \Input::get('terms');
        $brand->terms_type = \Input::get('terms_type');
        // $brand->hipwifi = \Input::get('hipwifi');
        $brand->wifi_activated = 1;
        $brand->zonein_btn_text = \Input::get('zonein_btn_text');


        $brand->register_field = \Input::get('register_field');
        $brand->f1_display = \Input::get('f1_display');
        $brand->f1_placeholder = \Input::get('f1_placeholder');
        $brand->f1_type = \Input::get('f1_type');
        $brand->f2_display = \Input::get('f2_display');
        $brand->f2_placeholder = \Input::get('f2_placeholder');
        $brand->f2_type = \Input::get('f2_type');
        $brand->f3_display = \Input::get('f3_display');
        $brand->f3_placeholder = \Input::get('f3_placeholder');
        $brand->f3_type = \Input::get('f3_type');
        $brand->f4_display = \Input::get('f4_display');
        $brand->f4_agegate = \Input::get('f4_agegate');
        $brand->f4_type = \Input::get('f4_type');
        $brand->f5_display = \Input::get('f5_display');
        $brand->f5_placeholder = \Input::get('f5_placeholder');
        $brand->f5_type = \Input::get('f5_type');
        $brand->f6_display = \Input::get('f6_display');
        $brand->f6_placeholder = \Input::get('f6_placeholder');
        $brand->f6_url = \Input::get('f6_url');
        $brand->f6_type = \Input::get('f6_type');
        $brand->f7_display = \Input::get('f7_display');
        $brand->f7_placeholder = \Input::get('f7_placeholder');
        $brand->f7_type = \Input::get('f7_type');
        $brand->f8_display = \Input::get('f8_display');
        $brand->f8_placeholder = \Input::get('f8_placeholder');
        $brand->f8_type = \Input::get('f8_type');

        $brand->firstname_capture = \Input::get('firstname_capture');
        $brand->firstname_display = \Input::get('firstname_display');

        $brand->sm_text = \Input::get('sm_text');
        $brand->sm_color = \Input::get('sm_color');
        $brand->sm_buttonsize = \Input::get('sm_buttonsize');

        if(!$brand->f1_display) $brand->f1_display = "hide";
        if(!$brand->f2_display) $brand->f2_display = "hide";
        if(!$brand->f3_display) $brand->f3_display = "hide";
        if(!$brand->f4_display) $brand->f4_display = "hide";
        if(!$brand->f5_display) $brand->f5_display = "hide";
        if(!$brand->f6_display) $brand->f6_display = "hide";
        if(!$brand->f7_display) $brand->f7_display = "hide";
        if(!$brand->f8_display) $brand->f8_display = "hide";
        if($brand->terms_type != "custom") $brand->terms = "";

        // if(\Input::get('hiprm') == "on") { $brand->hiprm = 1; } else { $brand->hiprm = 0; }
        // if(\Input::get('hipjam') == "on") { $brand->hipjam = 1; } else { $brand->hipjam = 0; }
        // if(\Input::get('hipengage') == "on") { $brand->hipengage = 1; } else { $brand->hipengage = 0; }
        if(\Input::get('userdatabtn') == "on") { $brand->userdatabtn = 1; } else { $brand->userdatabtn = 0; }
        if(\Input::get('logindatabtn') == "on") { $brand->logindatabtn = 1; } else { $brand->logindatabtn = 0; }

        return $brand;
    }

    public function constructNastypeRecord($type, $brand) 
    {

        error_log("brand->firstname_capture = " . $brand->firstname_capture);
        error_log("brand->firstname_display = " . $brand->firstname_display);

        $record = array(
            "type" => $type,
            "uru" => $brand->uru,
            "welcomemessage" => $brand->welcome,
            "terms" => $brand->terms,
            "terms_type" => $brand->terms_type,
            "register_field" => $brand->register_field,
            "f1_display" => $brand->f1_display,
            "f1_placeholder" => $brand->f1_placeholder,
            "f1_type" => $brand->f1_type,
            "f2_display" => $brand->f2_display,
            "f2_placeholder" => $brand->f2_placeholder,
            "f2_type" => $brand->f2_type,
            "f3_display" => $brand->f3_display,
            "f3_placeholder" => $brand->f3_placeholder,
            "f3_type" => $brand->f3_type,
            "f4_display" => $brand->f4_display,
            "f4_agegate" => $brand->f4_agegate,
            "f4_type" => $brand->f4_type,
            "f5_display" => $brand->f5_display,
            "f5_placeholder" => $brand->f5_placeholder,
            "f5_type" => $brand->f5_type,
            "f6_display" => $brand->f6_display,
            "f6_placeholder" => $brand->f6_placeholder,
            "f6_url" => $brand->f6_url,
            "f6_type" => $brand->f6_type,
            "f7_display" => $brand->f7_display,
            "f7_placeholder" => $brand->f7_placeholder,
            "f7_type" => $brand->f7_type,
            "f8_display" => $brand->f8_display,
            "f8_placeholder" => $brand->f8_placeholder,
            "f8_type" => $brand->f8_type,
            "firstname_capture" => $brand->firstname_capture,
            "firstname_display" => $brand->firstname_display,
            "sm_text" => $brand->sm_text,
            "sm_color" => $brand->sm_color,
            "sm_buttonsize" => $brand->sm_buttonsize,
            "firstname_capture" => (int)$brand->firstname_capture,
            "firstname_display" => (int)$brand->firstname_display,
            "zonein_btn_text" => $brand->zonein_btn_text,
            "auth_token" => $brand->auth_token,
        );

        return $record;

    }

    public function addBrand()
    {
        error_log('addBrand xxx');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = array();

        $data['edit'] = false;

        $servers = \Server::All();
        $data['allservers'] = $servers;
        $data['serverArray'] = array();

        $data['data_checked'] = "checked";
        $data['time_checked'] = "";

        $data['custom_terms_checked'] = "";
        $data['standard_terms_checked'] = "checked";

        $data['firstname_capture'] = 0;
        $data['firstname_display'] = 0;

        $data['sm_color'] = "#FFFFFF";
        $data['sm_text'] = "";
        $data['sm_buttonsize'] = "small";

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $data['field_configuration'] = $this->getFieldConfiguration();

        $data['databases'] = \Remotedb::All();

        $fullreg = array("value" => "full", "text" => "Full Registration", "selected" => "");
        $noreg = array("value" => "none", "text" => "Zero Registration", "selected" => "");
        $data['login_process'] = array();
        array_push($data['login_process'], $fullreg);
        array_push($data['login_process'], $noreg);

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = new \Brand();

        return \View::make('hipwifi.addbrand')->with('data', $data);
    }

    // // public function addBrandSave()
    // {

    //     error_log('addBrandSave');
    //     $brand = new \Brand();

    //     $input = \Input::all();
    //     $input["servercount"]= sizeof(\Input::get('server_ids'));

    //     $name = \Input::get("name");
    //     $exists = \Brand::where("name", "like", $name)->withTrashed()->first();
    //     if(! is_null($exists)) {
    //         $exists->forceDelete();
    //     } 

    //     $messages = array(
    //         'name.required' => 'The brand name is required',
    //         'name.unique' => 'The brand name is already taken',
    //         'name.alpha_num_dash_spaces' => 'The full name can only contain letters, numbers, dashes and spaces',
    //         'code.required' => 'The brand code is required',
    //         'code.unique' => 'The brand code is already taken',
    //         'code.size' => 'The brand code must be 6 characters in length',
    //         'welcome.required' => 'The welcome message is required',
    //         'ssid.required' => 'The SSID is required',
    //         'uru.required' => 'The user redirection url is required',
    //         'uru.url' => 'The format of the user redirection url is invalid',
    //         'limit.required' => 'The user data limit is required',
    //         // 'servercount.array_not_null' => 'You must select at least one server',
    //     );

    //     $rules = array(
    //         'name'          => 'required|alpha_num_dash_spaces|unique:brands',  
    //         'code'          => 'required|unique:brands|size:6', 
    //         'welcome'       => 'required',  
    //         'ssid'          => 'required',  
    //         'uru'           => 'url|required',  
    //         'limit'         => 'required',   
    //         // 'servercount'   => 'array_not_null',                       
    //     );

    //     $validator = \Validator::make($input, $rules, $messages);
    //     if ($validator->fails()) {
    //         $messages = $validator->messages();
    //         return \Redirect::to('hipwifi_addbrand')->withErrors($validator)->withInput();

    //     } else {        

    //         $brand = $this->constructBrandRecord($brand, $input, true);
    //         $brand->save();

    //         // Copy brand to hipengage
    //         $engagebrand = new \Engagebrand();
    //         $engagebrand->name = $brand->name;
    //         $engagebrand->code = $brand->code;
    //         $engagebrand->auth_token = $brand->auth_token;
    //         $engagebrand->active = $brand->hipengage;
    //         $engagebrand->save();

    //         $user = \Auth::user();
    //         $user->brands()->attach($brand->id);

    //         // Insert the record in the nastype table on the radius server
    //         $ispcode = \Isp::find($brand->isp_id)->code;
    //         $type = $ispcode . $brand->code;

    //         $record = $this->constructNastypeRecord($type, $brand);
    //         $connection = $brand->remotedb->dbconnection;
    //         \DB::connection($connection)->table("nastype")->insert($record);

    //         $brand->insertUserGroupInHipWifi($brand->code, $brand->limit_type, $brand->limit, $connection);
    //     }

    //     return \Redirect::route('hipwifi_showbrands');
    // }




private function getErrorActivateMessages() {
    return array(
            'welcome.required' => 'The welcome message is required',
            'ssid.required' => 'The SSID is required',
            'uru.required' => 'The user redirection url is required',
            'uru.url' => 'The format of the user redirection url is invalid',
            'limit.required' => 'The user data limit is required',
            // 'servercount.array_not_null' => 'You must select at least one server',
        );
}

private function getActivateRules() {
    return array(
            'welcome'       => 'required',  
            'ssid'          => 'required',  
            'uru'           => 'url|required',  
            'limit'         => 'required',   
            // 'servercount'   => 'array_not_null',                       
        );
}



 public function activateBrandSave()
    {

        error_log('activateBrandSave');
        $id = \Input::get('id');
        $brand = \Brand::find($id);
        $is_activation = \Input::get('is_activation');

        $input = \Input::all();
        $input["servercount"]= sizeof(\Input::get('server_ids'));

        $messages = $this->getErrorActivateMessages();
        $rules = $this->getActivateRules();

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return \Redirect::to('hipwifi_activatebrand/' . $id)->withErrors($validator)->withInput();

        } else {        

            $brand = $this->constructBrandRecord($brand, $input, true);
            $brand->save();

            $user = \Auth::user();
            $user->brands()->attach($brand->id);

            // Insert the record in the nastype table on the radius server
            $ispcode = \Isp::find($brand->isp_id)->code;
            $type = $ispcode . $brand->code;

            $record = $this->constructNastypeRecord($type, $brand);
            $connection = $brand->remotedb->dbconnection;
            \DB::connection($connection)->table("nastype")->insert($record);

            $brand->insertUserGroupInHipWifi($brand->code, $brand->limit_type, $brand->limit, $connection);
        }

        return \Redirect::route('hipwifi_showbrands');
    }














    public function activateBrand($id)
    {
        error_log(' In activateBrand aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = \Brand::find($id);

        if($data['brand']->hipwifi) $data['brand']->hipwifi = "checked";
        if($data['brand']->hiprm) $data['brand']->hiprm = "checked";
        if($data['brand']->hipjam) $data['brand']->hipjam = "checked";
        if($data['brand']->hipengage) $data['brand']->hipengage = "checked";
        if($data['brand']->userdatabtn) $data['brand']->userdatabtn = "checked";
        if($data['brand']->logindatabtn) $data['brand']->logindatabtn = "checked";

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $data['currentMenuItem'] = "Brand Management";
        // $data['edit'] = true;

        $data['edit'] = false;
        $data['is_activation'] = true;

        $servers = \Server::All();
        $data['allservers'] = $servers;
        $data['serverArray'] = array();

        $data['data_checked'] = "checked";
        $data['time_checked'] = "";

        $data['custom_terms_checked'] = "";
        $data['standard_terms_checked'] = "checked";

        $data['firstname_capture'] = 0;
        $data['firstname_display'] = 0;

        $data['sm_color'] = "#FFFFFF";
        $data['sm_text'] = "";
        $data['sm_buttonsize'] = "small";

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $data['field_configuration'] = $this->getFieldConfiguration();

        $data['databases'] = \Remotedb::All();

        $fullreg = array("value" => "full", "text" => "Full Registration", "selected" => "");
        $noreg = array("value" => "none", "text" => "Zero Registration", "selected" => "");
        $data['login_process'] = array();
        array_push($data['login_process'], $fullreg);
        array_push($data['login_process'], $noreg);

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        return \View::make('hipwifi.addbrand')->with('data', $data);
    }

    public function editBrand($id)
    {
        error_log(' In editBrand bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = \Brand::find($id);
        $data['database'] = $data['brand']->remotedb;

        if($data['brand']->login_process == "full") {
            $fullselected = "selected";
            $noneselected = "";
        } else {
            $fullselected = "";
            $noneselected = "selected";
        }

        if($data['brand']->limit_type == "data") {
            $data['data_checked'] = "checked";
            $data['time_checked'] = "";
        } else {
            $data['data_checked'] = "";
            $data['time_checked'] = "checked";
        } 

        if($data['brand']->terms_type == "custom") {
            $data['custom_terms_checked'] = "checked";
            $data['standard_terms_checked'] = "";
        } else {
            $data['custom_terms_checked'] = "";
            $data['standard_terms_checked'] = "checked";
        } 

        $data['field_configuration'] = $this->getFieldConfiguration($data['brand']);

        $fullreg = array("value" => "full", "text" => "Full Registration", "selected" => $fullselected);
        $noreg = array("value" => "none", "text" => "Zero Registration", "selected" => $noneselected);
        $data['login_process'] = array();
        array_push($data['login_process'], $fullreg);
        array_push($data['login_process'], $noreg);


        if($data['brand']->hiprm) $data['brand']->hiprm = "checked";
        if($data['brand']->hipjam) $data['brand']->hipjam = "checked";
        if($data['brand']->hipengage) $data['brand']->hipengage = "checked";
        if($data['brand']->userdatabtn) $data['brand']->userdatabtn = "checked";
        if($data['brand']->logindatabtn) $data['brand']->logindatabtn = "checked";

        $data['sm_buttonsize'] = $data['brand']->sm_buttonsize; //Doing this because for some reason couldn't read $data['brand'] in the view 

        $data['firstname_capture'] = $data['brand']->firstname_capture;
        $data['firstname_display'] = $data['brand']->firstname_display;

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $data['currentMenuItem'] = "Brand Management";
        $data['edit'] = true;
        $data['is_activation'] = false;

        return \View::make('hipwifi.addbrand')->with('data', $data);
    }

    public function editBrandSave()
    {
        $id = \Input::get('id');
        $brand = \Brand::find($id);
        $is_activation = \Input::get('is_activation');

        $input = \Input::all();
        $input["servercount"]= sizeof(\Input::get('server_ids'));

        $messages = array(
            'name.required' => 'The brand name is required',
            'name.unique' => 'The brand name is already taken',
            'name.alpha_num_dash_spaces' => 'The full name can only contain letters, numbers, dashes and spaces',
            'code.required' => 'The brand code is required',
            'code.unique' => 'The brand code is already taken',
            'code.size' => 'The brand code must be 6 characters in length',
            'welcome.required' => 'The welcome message is required',
            'ssid.required' => 'The SSID is required',
            'uru.required' => 'The user redirection url is required',
            'limit.required' => 'The user data limit is required',
            // 'servercount.array_not_null' => 'You must select at least one server',
        );

            // 'name'      => 'required|alpha_num_dash_spaces|unique:brands,name,'.$id,  
            // 'code'      => 'required|size:6|unique:brands,code,'.$id, 
        $rules = array(
            'welcome'   => 'required',  
            'ssid'      => 'required',  
            'uru'       => 'required',  
            'limit'     => 'required',   
            // 'servercount'    => 'array_not_null',                       
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            if($is_activation) {
                return \Redirect::to('hipwifi_activatebrand/'.$id)->withErrors($validator)->withInput();
            } else {
                return \Redirect::to('hipwifi_editbrand/'.$id)->withErrors($validator)->withInput();
            }


        } else { 

            $brand = $this->constructBrandRecord($brand, $input, false);

            // Update brand status in hipengage
            // $engagebrand = \Engagebrand::where('code', 'like', $brand->code)->first();

            // if($engagebrand) {
            //     $engagebrand->active = $brand->hipengage;
            // } else {
            //     $brand->auth_token = exec("uuidgen -r");
            //     $engagebrand = new \Engagebrand();
            //     $engagebrand->name = $brand->name;
            //     $engagebrand->code = $brand->code;
            //     $engagebrand->auth_token = $brand->auth_token;
            //     $engagebrand->active = $brand->hipengage;
            // }
            
            $brand->save();
            // $engagebrand->save();

            $oldbrandcode = \Input::get('oldbrandcode');
            $ispcode = \Isp::find($brand->isp_id)->code;
            $oldtype = $ispcode . $oldbrandcode;
            $type = $ispcode . $brand->code;

            $record = $this->constructNastypeRecord($type, $brand);
            $connection = $brand->remotedb->dbconnection;
            \DB::connection($connection)->table("nastype")->where('type', $oldtype)->update($record);

            error_log("editBrandSave : limit_type : " . $brand->limit_type);
            $brand->insertUserGroupInHipWifi($brand->code, $brand->limit_type, $brand->limit, $connection);

            $remotedb_id = $brand->remotedb->id;
            $venue = new \Venue();
            $venue->synchToRadius($remotedb_id);


            // Update brand auth token in hipengage
            $engagebrand = \Engagebrand::where('code', 'like', $brand->code)->first();
            if($engagebrand) {
                $engagebrand->auth_token = $brand->auth_token;
                $engagebrand->save();
            }             

        }

        return \Redirect::route('hipwifi_showbrands');
    }

    public function deleteBrand($id)
    {
        error_log("deleteBrand");
        $brand = \Brand::find($id);
        $connection = $brand->remotedb->dbconnection;

        if($brand) {

            $brand->users()->detach();

            $brand->medias()->delete();

            $brand->venues()->delete();

            \Engagebrand::where('code', 'like', $brand->code)->delete();

            $brand->delete(); 

            // Delete the nastypes and naslookups in hipwifi
            \DB::connection($connection)->table("nastype")->where('type', 'like' , "%" . $brand->code)->delete();
            \DB::connection($connection)->table("naslookup")->where('location', 'like' , "___" . $brand->code . "%")->delete();
        }

        return \Redirect::route('hipwifi_showbrands', ['json' => 1]);
    }

    public function showUsers($json = null)
    {

        $data = array();
        $data['currentMenuItem'] = "Brand Administrators";

        $userObj = new \User();
        $data["user"] = $userObj;
        $filter = \Input::get('filter');
        $usersStruct = array();

        $fullname = \Input::get('fullname');
        $fullname =  "%" . $fullname . "%";
        $email = \Input::get('email');
        $email =  "%" . $email . "%";

        $criteria = array('fullname'=>$fullname, 'email'=>$email);

        $usersStruct = $userObj->getUsersData(1, $criteria);

        $usersJason = json_encode($usersStruct);
        // echo $usersJason;
        $data['usersStruct'] = $usersStruct;
        $data['usersJason'] = $usersJason;

        if($json) {
            return \Response::json($usersJason);
        } else {
            return \View::make('hipwifi.showusers')->with('data', $data);            
        }

    }


    public function addUser()
    {
        $data = array();
        $data['user'] =  $user = new \User();
        $data['edit'] = false;
        $data['currentMenuItem'] = "Brand Administrators";

        $levels = \Level::All();
        $level_names = array();
        foreach($levels as $level) {
            $level_names[$level->code] = $level->name;
        }
        $data['level_names'] = $level_names;

        $brands = \Brand::All();
        $data['allbrands'] = $brands;
        $data['brandArray'] = array(); //TAKE THIS OUT WHEN DNE

        $data['products']['HipWifi'] = false;
        $data['products']['HipRM'] = false;
        $data['products']['HipJAM'] = false;

        $data['countries'] = \Countrie::All();

        $data['user']->level_code = "Brand Admin";

        foreach($data['allbrands'] as $brand) {
            error_log("============= " . $brand->name);
        }

        return \View::make('hipwifi.adduser')->with('data', $data);
    }


    public function addUserSave()
    {
        $user = new \User();
        $password = \Input::get('password');

        $input = \Input::all();
        $input["brandcount"]= sizeof(\Input::get('brand_ids'));

        $messages = array(
            'email.email' => 'The email address is invalid',
            'fullname.alpha_num_dash_spaces' => 'The full name must only contain letters, numbers, dashes and spaces',
            'password.alpha_num' => 'The password must only contain letters and numbers',
            'password.min:6' => 'The password must contain at least 6 characters',
            'brandcount.array_not_null' => 'You must select at least one brand',
            'productcount.array_not_null' => 'You must select at least one product',
        );

        $rules = array(
            'fullname'      => 'required|alpha_num_dash_spaces',  
            'email'         => 'required|email|unique:users',  
            'password'      => 'alpha_num|min:6',                       
            'brandcount'    => 'array_not_null',                       
            'productcount'  => 'array_not_null',                       
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_adduser')->withErrors($validator)->withInput();

        } else {

            $user->fullname = \Input::get('fullname');
            $user->email = \Input::get('email');
            $user->password = \Hash::make($password);
            $user->level_code = \Input::get('level_code');
            $user->save();

            $brand_ids = \Input::get('brand_ids');
            foreach($brand_ids as $brand_id) {
                $user->brands()->attach($brand_id);
            }

            $product_id = 1;
            $user->products()->attach($product_id);
        }


        return \Redirect::route('hipwifi_showusers');
    }

    public function editUser($id)
    {
        $data = array();
        $data['user'] = \User::find($id);
        $data['edit'] = true;
        $data['currentMenuItem'] = "Brand Administrators";

        // $level = \User::find($id)->level;

        $levels = \Level::All();
        $level_names = array();
        foreach($levels as $level) {
            $level_names[$level->code] = $level->name;
        }
        $data['level_names'] = $level_names;

        $data['brandArray'] = array();
        $brands = \Brand::All();
        $data['allbrands'] = $brands;
        foreach ($data['user']->brands as $brand) {   
            $countrie_id = $brand->countrie_id;
            $countrie = \Countrie::find($countrie_id);
            $data['brandArray'][$brand->id]["name"] = $brand->name;   
            $data['brandArray'][$brand->id]["countrie"] = $countrie["name"];   
        }

        $data['products']['HipWifi'] = false;
        $data['products']['HipRM'] = false;
        $data['products']['HipJAM'] = false;

        $data['permissions']['ques_rw'] = false;
        $data['permissions']['media_rw'] = false;
        $data['permissions']['uru_rw'] = false;
        $data['permissions']['rep_rw'] = false;
        
        $data['countries'] = \Countrie::All();

        foreach ($data['user']->products as $product) {
            $data['products'][$product->name] = true;
        }

        // foreach ($data['user']->permissions as $permission) {
        //     $data['permissions'][$permission->name] = true;
        // }

        return \View::make('hipwifi.adduser')->with('data', $data);
    }


    public function editUserSave()
    {
        $id = \Input::get('id');
        $input = \Input::all();
        $input["brandcount"]= sizeof(\Input::get('brand_ids'));
        $password = \Input::get('password');
        $user = \User::find($id);

        $messages = array(
            'email.email' => 'The email address is invalid',
            'fullname.alpha_num_dash_spaces' => 'The full name can only contain letters, numbers, dashes and spaces',
            'brandcount.array_not_null' => 'You must select at least one brand',
            'productcount.array_not_null' => 'You must select at least one product',
        );

        $rules = array(
            'fullname'      => 'required|alpha_num_dash_spaces',  
            'email'         => 'required|email|unique:users,email,'.$id,
            'brandcount'    => 'array_not_null',                       
            'productcount'  => 'array_not_null',                       
        );

        if($password) {
            $messages['password.min:6'] = 'The password must contain at least 6 characters';
            $messages['password.alpha_num'] = 'The password must only contain letters and numbers';
            $rules['password'] = 'alpha_num|min:6';
        }


        error_log("editUserSave : fullname " . $input["fullname"]);
        error_log("editUserSave : email " . $input["email"]);

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_edituser/' . $id)->withErrors($validator)->withInput();

        } else {
     
            $user->fullname = \Input::get('fullname');
            $user->email = \Input::get('email');
            if($password) {
                $user->password = \Hash::make($password); 
            }
            $user->level_code = \Input::get('level_code');

            error_log("level_code : " . $user->level_code);
            $user->save();

            $user->brands()->detach();
            $brand_ids = \Input::get('brand_ids');
            if($brand_ids) {   
                foreach($brand_ids as $brand_id) {
                    $user->brands()->attach($brand_id);
                }
            }
            
            }

            // $user->permissions()->detach();
            // $permission_ids = \Input::get('permission_ids');
            // if($permission_ids) {
            //     foreach($permission_ids as $permission_id) {
            //         $user->permissions()->attach($permission_id);
            //         error_log("permission_ids " . $permission_id);
            //     }
            // }
    

      return \Redirect::route('hipwifi_showusers');  
    }

    public function deleteUser($id)
    {
        error_log("deleteUser");
        $user = \User::find($id);

        if($user) {
            error_log("deleteUser 10");
            $user->brands()->detach();

            $user->products()->detach();

            $user->permissions()->detach();

            $user->delete();  
        }

        return \Redirect::route('hipwifi_showusers', ['json' => 1]);
    }

    ///////////////////////////////// Servers ///////////////////////////////////////

    public function showServers($json = null)
    {
        error_log("showServers");

        $data = array();
        $data['currentMenuItem'] = "Server Management";
        $server = new \Server();
        $servers = $server->getServersForProduct(1);

        $data['serversStruct'] = $servers;
        $serversJason = json_encode($servers);
        $data['serversJason'] = $serversJason;

        $data['servers'] = $servers;
        $data['currentMenuItem'] = "Server Management";

        if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($serversJason);

        } else {
            return \View::make('hipwifi.showservers')->with('data', $data);
            
        }
    }

    public function addServer()
    {
        $data = array();
        $data['server'] =  new \Server();
        $data['currentMenuItem'] = "Server Management";
        $data['edit'] = false;

        $data['databases'] = \Remotedb::All();
        $data['currentMenuItem'] = "Server Management";        

        return \View::make('hipwifi.addserver')->with('data', $data);
    }

    public function addServerSave()
    {
        error_log('addServerSave');
        $server = new \Server();

        $input = \Input::all();
        $input["brandcount"]= sizeof(\Input::get('brand_ids'));

        $messages = array(
            'hostname.required' => 'The server host name is required',
            'hostname.unique' => 'The server host name is already taken',
        );

        $rules = array(
            'hostname'          => 'required|unique:servers',    
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return \Redirect::to('hipwifi_addserver')->withErrors($validator)->withInput();

        } else {  

            $server->hostname = \Input::get('hostname');
            $server->notes = \Input::get('notes');
            $server->remotedb_id = \Input::get('remotedb_id');
            $server->save();
        }

        return \Redirect::route('hipwifi_showservers');
    }

    public function editServer($id)
    {
        error_log('editServer');
        $data = array();
        $data['server'] = \Server::find($id);
        $data['currentMenuItem'] = "Server Management";
        $data['edit'] = true;

        $data['database'] = $data['server']->remotedb;

        return \View::make('hipwifi.addserver')->with('data', $data);
    }

    public function editServerSave()
    {
        $id = \Input::get('id');
        $server =  \Server::find($id);


        $input = \Input::all();
        $input["brandcount"]= sizeof(\Input::get('brand_ids'));

        $messages = array(
            'hostname.required' => 'The server host name is required',
            'hostname.unique' => 'The server host name is already taken',
            // 'brandcount.array_not_null' => 'You must select at least one brand',
        );

        $rules = array(
            'hostname'          => 'required|unique:servers',    
            // 'brandcount'   => 'array_not_null',                       
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_addserver')->withErrors($validator)->withInput();

        } else { 

            $server->hostname = \Input::get('hostname');
            $server->notes = \Input::get('notes');

            $server->save();

            // $server->brands()->detach();
            // $brand_ids = \Input::get('brand_ids');
            // if($brand_ids) {
            //     foreach($brand_ids as $brand_id) {
            //         $server->brands()->attach($brand_id);
            //     }
            // }
        }

        return \Redirect::route('hipwifi_showservers');
    }

    public function deleteServer($id)
    {
        error_log("deleteServer");
        $server = \Server::find($id);

        if($server) {
            $server->delete();  
        }

        return \Redirect::route('hipwifi_showservers', ['json' => 1]);
    }

    /////////////////////// Venues /////////////////////////

    public function getInactiveVenues()
    {
        error_log("getInactiveVenues wifi");
        $data = array();
        $venue = new \Venue();
        // if (\User::isVicinity()) {
        //     $vicinity_brands = \Brand::whereRaw('id = 165 OR parent_brand = 165')->get();
        //     $vbrands = array();
        //     foreach ($vicinity_brands as $brand) {
        //         array_push($vbrands, $brand->id);
        //     }
        //     $vbrandsarray = implode(",", $vbrands);
        //     $venues = \Venue::whereRaw("brand_id IN ($vbrandsarray) AND jam_activated = 0")->get();

        // } else {
            $venues = $venue->getVenuesForUser('hipwifi', 1, null, null, "inactive");
        // }
        $venuesJason = json_encode($venues);

        return \Response::json($venuesJason);
    }

    public function showVenues($json = null)
    {
        error_log("showVenues");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        // $venues = \Venue::all();
        $venue = new \Venue();
        $venues = $venue->getVenuesForUser('hipwifi', 1, null, null, "active");

        foreach($venues as $venue) {
            if($venue->ap_active == 0) {
                $venue["status"] = '<span style="color:red">Inactive</span>';
            } else {
                $venue["status"] = '<span style="color:green">Active</span>';
            }
            if($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
        }
        
        $data['venuesJason'] = json_encode($venues);
        error_log("showVenues venuesJason = " . $data['venuesJason']);

        $data['currentMenuItem'] = "Venue Management";

        if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($data['venuesJason']);

        } else {
            error_log("showDashboard : returning NON json" );
            return \View::make('hipwifi.showvenues')->with('data', $data);
            
        }
    }

    public function deployRsc($id)
    {
        error_log("deployRsc");
        $dir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['id'] = $id;
        $data['message'] = "";

        $data['venue'] = \Venue::find($id);
        $data['venue']["sitename"] = preg_replace("/ /", "_",$data['venue']->sitename); 
        $rscfile =  $dir->value . "deployment/exports/" .  $data['venue']["sitename"] . ".rsc"; 
        if (file_exists($rscfile)) {
            $data['rscconfig'] = file($rscfile);
        }  else {
            $data['rscconfig'] = ["Configuration file not found!"];
        }

           

        
        foreach($data['venue'] as $key => $value) { error_log("TTT : $key => $value"); };

        return \View::make('hipwifi.deployrsc')->with('data', $data);
    }


    public function deployRscSave()
    {

        $data = array();
        $data['currentMenuItem'] = "Venue Management";

        $id = \Input::get('id');
        error_log("deployRsc id = $id");

        $overridersc = \Input::get('overridersc');
        error_log("deployRsc overridersc : $overridersc");

        $data['venue'] = \Venue::find($id);

        $scripttext = \Input::get('scripttext');
        // error_log("deployRsc : scripttext = $scripttext");

        $mikrotik = new \Mikrotik();
        $data['message'] = $mikrotik->deployRsc($data['venue'], $scripttext, $overridersc);

        $data['rscconfig'] = ['Newly deployed configuration will become active and displayed in 5 minutes.', 'Please check back in 5 minutes.'];

        return \View::make('hipwifi.deployrsc')->with('data', $data);
    }

    public function showRunningRsc()
    {
        $id = \Input::get('id');
       
        //$dir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        //$rscfile =  $dir->value . "deployment/" .  $sitenameFormatted . ".rsc";
        //$readrsc = file($rscfile);
        return $sitename;
    }


    public function addVenue()
    {
        error_log("addVenue");

        // $response = file_get_contents('http://dev.doteleven.co/kauai_seapoint');
        // error_log("TESTING API : $response");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = false;
        $data['adminssidpresent'] = 0; 
        $data['numadminwifi'] = 0;
        $data['submitbutton'] = '';
        $data['bypass'] = array();
        $data['adminssid1'] = '';
        $data['adminssid2'] = '';
        $data['adminssid3'] = '';

        $data['venue'] = new \Venue();

        $othervars = array("name" => "Other", "selected" => "selected=\"selected\"");
        $mikvars = array("name" => "Mikrotik", "selected" => "");
        $data["device_types"] = array($othervars, $mikvars);

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipwifi');

        $data['ap_active_checked'] = "checked";
        $data['ap_inactive_checked'] = "";

        return \View::make('hipwifi.addvenue')->with('data', $data);
    }







    public function activateVenue($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = false;
        $data['is_activation'] = true;

        $data['venue'] = \Venue::find($id);
        //dd($id);
        //dd($data['venue']);
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $macaddress = $data['venue']->macaddress;
       // $data['configfile'] =  $mikrotikdir->value . "deployment/" . $macaddress .  "_951-2n.rsc";
        $data['rscfilemodtime'] =  \DB::table('venues')->where('id', '=', $id)->pluck('rscfilemodtime');
        $data['submitbutton'] = '';
        $data['device_type'] = $data['venue']->device_type;
        
        if ($data['device_type'] == 'Mikrotik'){
          // if(file_exists($data['configfile'])) {
                $now = time();
                    if (($now -  $data['rscfilemodtime']) > 300) {
                        $data['submitbutton'] = 'on';
                        }
                    else {
                             $data['submitbutton'] = 'off';
                            }
            //}
         }
        else {
            $data['submitbutton'] = 'on';
        }

        

        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]); 
        foreach($data['venue'] as $key => $value) { error_log("TTT : $key => $value"); };
        $data['bypass'] = array();
        for ($i=1; $i <= 10; $i++) { 
            $mac = 'bypassmac'.$i;
            $comment = 'bypasscomment'.$i;
            $bypassmac = $data['venue']->$mac;
            $bypasscomment = $data['venue']->$comment;
            $array = array($bypassmac, $bypasscomment);
            array_push($data['bypass'], $array);
           }
        $data["adminssid1"] = $data['venue']->adminssid1;
        $data["adminssid2"] = $data['venue']->adminssid2;
        $data["adminssid3"] = $data['venue']->adminssid3;
        //$data["adminwifi"] = array($data["adminssid1"], $data["adminssid2"], $data["adminssid3"]);
        /*$data["numadminwifi"] = 0; 
        $i = 0;
        foreach ($data["adminwifi"] as $configuredwifi) {
             if  (!is_null($configuredwifi)){
             $i += 1;
           }
        $data["numadminwifi" ] = $i;
        }*/
        
        
        if($data['venue']->ap_active == 1) {
            $data['ap_active_checked'] = "checked";
            $data['ap_inactive_checked'] = "";
        } else {
            $data['ap_active_checked'] = "";
            $data['ap_inactive_checked'] = "checked";
        }
        
        if($data['venue']->device_type == "Mikrotik") {
            $mikvars = array("name" => "Mikrotik", "selected" => "selected=\"selected\"");
            $othervars = array("name" => "Other", "selected" => "");
        } else {
            $mikvars = array("name" => "Mikrotik", "selected" => "");
            $othervars = array("name" => "Other", "selected" => "selected=\"selected\"");
        }
        $data["device_types"] = array($othervars, $mikvars);

        $servers = \Server::All();
        $data['allservers'] = $servers;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipwifi');


        $encoded = json_encode($data);
        return \View::make('hipwifi.addvenue')->with('data', $data);
    }


public function activateVenueSave()
    {

        $messages = array(
            'macaddress.macaddress_format' => 'The MAC address format is incorrect AA:BB:CC:DD:EE:FF',
        );

        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $id = \Input::get('id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $macaddress = \Input::get('macaddress');
        $connection = \Brand::find($brand_id)->remotedb->dbconnection;


        $input['sitename'] = $sitename;
        $timefrom = \Input::get('timefrom');
        $timeto = \Input::get('timeto');

        

        error_log( "addVenueSave : timefrom = $timefrom ======= timeto = $timeto");

        // $sitename_exists = \Venue::where("sitename", "like", $sitename)->withTrashed()->first();
        // if(! is_null($sitename_exists)) {
        //     $sitename_exists->forceDelete();
        // } 
        // $macaddress_exists = \Venue::where("macaddress", "like", $macaddress)->withTrashed()->first();
        // if(! is_null($macaddress_exists)) {
        //     $macaddress_exists->forceDelete();
        // } 

        $ssid = \Input::get('ssid');
        error_log("editVenueSave : 111 ssid : $ssid");
        if( !$ssid || $ssid == "") {
            $ssid = \Brand::find($brand_id)->ssid;
        } else {
            $ssid = \Input::get('ssid');
        }

        $rules = array(
            // 'sitename'       => 'required|alpha_num_dash_spaces|unique:venues',                        
            'macaddress'     => 'required|macaddress_format|unique:venues',          
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_activatevenue/' . $id)->withErrors($validator)->withInput();

        } else {
            $utils = new \Utils();
            
            $venue = \Venue::find($id);
            error_log("activateVenueSave : id = $id");
            error_log("activateVenueSave : sitename = " . $sitename);
            error_log("activateVenueSave : venue = " . print_r($venue, true));
            error_log("activateVenueSave : venue sitename = " . $venue->sitename);
            // $venue->sitename = $input['sitename'];
            // $venue->location = $input['location'];
            // $venue->countrie_id = $input['countrie_id'];
            // $venue->province_id = $input['province_id'];
            // $venue->citie_id = $input['citie_id'];
            // $venue->brand_id = $input['brand_id'];
            // $venue->isp_id = \Brand::find($venue->brand_id)->isp_id;
            $remotedb_id = \Brand::find($venue->brand_id)->remotedb_id;
            $venue->remotedb_id = $remotedb_id;
            $venue->macaddress = $input['macaddress'];
            $venue->ssid = $ssid;
            $venue->device_type = $input['device_type'];
            $venue->wifi_activated = 1;
            // $venue->latitude = $input['latitude'];
            // $venue->longitude = $input['longitude'];
            // $venue->address = $input['address'];
            $venue->server_id = $input['server_id'];
            $venue->ap_active = \Input::get('ap_active');
            $venue->mon_from = $input['mon_from'];
            $venue->mon_to = $input['mon_to'];
            $venue->tue_from = $input['tue_from'];
            $venue->tue_to = $input['tue_to'];
            $venue->wed_from = $input['wed_from'];
            $venue->wed_to = $input['wed_to'];
            $venue->thu_from = $input['thu_from'];
            $venue->thu_to = $input['thu_to'];
            $venue->fri_from = $input['fri_from'];
            $venue->fri_to = $input['fri_to'];
            $venue->sat_from = $input['sat_from'];
            $venue->sat_to = $input['sat_to'];
            $venue->sun_from = $input['sun_from'];
            $venue->sun_to = $input['sun_to'];
            // $venue->contact = $input['contact'];
            // $venue->notes = $input['notes'];
            $venue->statuscomment = $input['statuscomment'];
            $venue->save();

            $venue = $venue->refreshMediaLocation($venue);
            $venue->insertVenueInRadius($venue, $remotedb_id);

           // Update the AP
           if($input['device_type'] == "Mikrotik") {
               $mikrotik = new \Mikrotik();
               $mikrotik->addVenue($venue);
               // $macaddress = \Input::get('macaddress');
               // $mikrotik->genTabletposcode($macaddress);

                //check if the device has got any tabletpos printer configured then modify the file that it will use to monitor them.
           //      $printers = new \Tabletposprinter();
           //      $printers = $printers->getPrintersForVenue($id);
           //      if($printers){
           //          $macaddress = \Input::get('macaddress');
           //          $mikrotik->genTabletposRsc($printers, $macaddress);
           //      }
           }

        }

        return \Redirect::route('hipwifi_showvenues');
    }










    public function addVenueSave()
    {
        $data['edit'] = true;
        $data['is_activation'] = false;

        $messages = array(
            'macaddress.macaddress_format' => 'The MAC address format is incorrect AA:BB:CC:DD:EE:FF',
        );

        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $macaddress = \Input::get('macaddress');
        $connection = \Brand::find($brand_id)->remotedb->dbconnection;

        $input['sitename'] = $sitename;
        $timefrom = \Input::get('timefrom');
        $timeto = \Input::get('timeto');


        error_log( "addVenueSave : timefrom = $timefrom ======= timeto = $timeto");

        $sitename_exists = \Venue::where("sitename", "like", $sitename)->first();
        if(! is_null($sitename_exists)) {
            $sitename_exists->forceDelete();
        } 
        $macaddress_exists = \Venue::where("macaddress", "like", $macaddress)->first();
        if(! is_null($macaddress_exists)) {
            $macaddress_exists->forceDelete();
        } 

        $ssid = \Input::get('ssid');
        error_log("editVenueSave : 111 ssid : $ssid");
        if( !$ssid || $ssid == "") {
            $ssid = \Brand::find($brand_id)->ssid;
        } else {
            $ssid = \Input::get('ssid');
        }

        $rules = array(
            'sitename'       => 'required|alpha_num_dash_spaces|unique:venues',                        
            'macaddress'     => 'required|macaddress_format|unique:venues',          
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_addvenue')->withErrors($validator)->withInput();

        } else {
            $utils = new \Utils();
            
            $venue = \Venue::find(\Input::get('id'));
            $venue->sitename = $input['sitename'];
            $venue->location = $input['location'];
            $venue->countrie_id = $input['countrie_id'];
            $venue->province_id = $input['province_id'];
            $venue->citie_id = $input['citie_id'];
            $venue->brand_id = $input['brand_id'];
            $venue->isp_id = \Brand::find($venue->brand_id)->isp_id;
            $remotedb_id = \Brand::find($venue->brand_id)->remotedb_id;
            $venue->remotedb_id = $remotedb_id;
            $venue->macaddress = $input['macaddress'];
            $venue->ssid = $ssid;
            $venue->device_type = $input['device_type'];
            $venue->server_id = $input['server_id'];
            $venue->tue_to = $input['tue_to'];
            $venue->wed_to = $input['wed_to'];
            $venue->thu_from = $input['thu_from'];
            $venue->thu_to = $input['thu_to'];
            $venue->fri_from = $input['fri_from'];
            $venue->fri_to = $input['fri_to'];
            $venue->sat_from = $input['sat_from'];
            $venue->sat_to = $input['sat_to'];
            $venue->sun_from = $input['sun_from'];
            $venue->sun_to = $input['sun_to'];
            $venue->statuscomment = $input['statuscomment'];
            $venue->save();

            $venue = $venue->refreshMediaLocation($venue);
            $venue->insertVenueInRadius($venue, $remotedb_id);

           // Update the AP
           if($input['device_type'] == "Mikrotik") {
                $mikrotik = new \Mikrotik();
                $mikrotik->addVenue($venue);


           }
        }

        return \Redirect::route('hipwifi_showvenues');
    }

    public function editVenue($id)
    {
        \Log::info("HANNES EDIT VENUE: id = $id");
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = true;
        $data['is_activation'] = false;
        \Log::info("HANNES EDIT VENUE: id = $id");
        $data['venue'] = \Venue::find($id);
        //dd($id);
        //dd($data['venue']);
        \Log::info("HANNES EDIT VENUE: id = $id");
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $macaddress = $data['venue']->macaddress;
       // $data['configfile'] =  $mikrotikdir->value . "deployment/" . $macaddress .  "_951-2n.rsc";
        $data['rscfilemodtime'] =  \DB::table('venues')->where('id', '=', $id)->pluck('rscfilemodtime');
        $data['submitbutton'] = '';
        $data['device_type'] = $data['venue']->device_type;
        
        if ($data['device_type'] == 'Mikrotik'){
          // if(file_exists($data['configfile'])) {
                $now = time();
                    if (($now -  $data['rscfilemodtime']) > 300) {
                        $data['submitbutton'] = 'on';
                        }
                    else {
                             $data['submitbutton'] = 'off';
                            }
            //}
         }
        else {
            $data['submitbutton'] = 'on';
        }
        
        
        
        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]); 
        foreach($data['venue'] as $key => $value) { error_log("TTT : $key => $value"); };
        $data['bypass'] = array();
        for ($i=1; $i <= 10; $i++) {
            $mac = 'bypassmac'.$i;
            $comment = 'bypasscomment'.$i;
            $bypassmac = $data['venue']->$mac;
            $bypasscomment = $data['venue']->$comment;
            $array = array($bypassmac, $bypasscomment);
            array_push($data['bypass'], $array);
           }
        $data["adminssid1"] = $data['venue']->adminssid1;
        $data["adminssid2"] = $data['venue']->adminssid2;
        $data["adminssid3"] = $data['venue']->adminssid3;
        //$data["adminwifi"] = array($data["adminssid1"], $data["adminssid2"], $data["adminssid3"]);
        /*$data["numadminwifi"] = 0; 
        $i = 0;
        foreach ($data["adminwifi"] as $configuredwifi) {
             if  (!is_null($configuredwifi)){
             $i += 1;
           }
        $data["numadminwifi" ] = $i;
        }*/
        
        
        if($data['venue']->ap_active == 1) {
            $data['ap_active_checked'] = "checked";
            $data['ap_inactive_checked'] = "";
        } else {
            $data['ap_active_checked'] = "";
            $data['ap_inactive_checked'] = "checked";
        }
        
        if($data['venue']->device_type == "Mikrotik") {
            $mikvars = array("name" => "Mikrotik", "selected" => "selected=\"selected\"");
            $othervars = array("name" => "Other", "selected" => "");
        } else {
            $mikvars = array("name" => "Mikrotik", "selected" => "");
            $othervars = array("name" => "Other", "selected" => "selected=\"selected\"");
        }
        $data["device_types"] = array($othervars, $mikvars);

        $servers = \Server::All();
        $data['allservers'] = $servers;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipwifi');

        //include Tabletpos printers configured for the venue;
        $printer = new \Tabletposprinter();
        $data['tabprinters'] = $printer->getPrintersForVenue($id);


        $encoded = json_encode($data);
        return \View::make('hipwifi.addvenue')->with('data', $data);
    }

    
    public function editVenueSave()
    {
        //Hannes hier
        $utils = new \Utils();
        $id = \Input::get('id');
        $servers = \Server::All();
        $old_sitename = \Input::get('old_sitename');
        error_log("old_sitename : $old_sitename");
        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $input['sitename'] = $sitename;
        $timefrom = \Input::get('timefrom');
        $timeto = \Input::get('timeto');

        $remotedb_id = \Brand::find($brand_id)->remotedb_id;

        $ssid = \Input::get('ssid');
        error_log("editVenueSave : 111 ssid : $ssid");
        if( !$ssid || $ssid == "") {
            $ssid = \Brand::find($brand_id)->ssid;
        } else {
            $ssid = \Input::get('ssid');
        }
        error_log("editVenueSave : 222 ssid : $ssid");

        $macaddress = \Input::get('macaddress');
            // 'sitename'       => 'required|alpha_num_dash_spaces|unique:venues,sitename,'.$id,                        
            // 'macaddress'     => 'required|macaddress_format|unique:venues,macaddress,'.$id,
        $rules = array(
            'adminssid1'     => 'min:5|max:20', 
            'firstnetworkpassword'      => 'min:8|max:20',
            'adminssid2'     => 'min:5|max:20',
            'secondnetworkpassword'      => 'min:8|max:20',
            'adminssid3'     => 'min:5|max:20', 
            'thirdnetworkpassword'      => 'min:8|max:20',
            'bypassmac0' => 'macaddress_format',
            'bypassmac1' => 'macaddress_format',
            'bypassmac2' => 'macaddress_format',
            'bypassmac3' => 'macaddress_format',
            'bypassmac4' => 'macaddress_format',
            'bypassmac5' => 'macaddress_format',
            'bypassmac6' => 'macaddress_format',
            'bypassmac7' => 'macaddress_format',
            'bypassmac8' => 'macaddress_format',
            'bypassmac9' => 'macaddress_format',
            );

        $validator = \Validator::make($input, $rules);
            
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipwifi_editvenue/'.$id)->withErrors($validator)->withInput();
        } else {

            $id = \Input::get('id');
            $venue =  \Venue::find($id);


            // Delete deleted venues

            $macs_to_delete = \Input::get('delete_macs');
            \Log::info("HANNES MACS TO DELETE: $macs_to_delete");
            $macs_array = explode(',', $macs_to_delete);

            foreach ($macs_array as $bypassmac){ 
                $bypassmacarray = [$venue->bypassmac1, $venue->bypassmac2, $venue->bypassmac3, $venue->bypassmac4, $venue->bypassmac5, 
                                        $venue->bypassmac6, $venue->bypassmac7, $venue->bypassmac8, $venue->bypassmac9, $venue->bypassmac10];
                $mikrotik = new \Mikrotik();
                $mikrotik->deletebypassmac($venue, $bypassmac);
                for ($i=0; $i <=9; $i++) { 
                    if ($bypassmacarray[$i] == $bypassmac){
                        $index = $i + 1;
                        \DB::table('venues')->where('id', '=', $id)->update(array('bypassmac'.$index => NULL, 'bypasscomment'.$index => NULL));
                    }
                
                }

            }

            

            // Done with deleting deleted venues











            $adminssid1check = $venue->adminssid1;
            $adminssid2check= $venue->adminssid2;
            $adminssid3check = $venue->adminssid3;
            // $venue->sitename = $sitename;
            $venue->location = \Input::get('location');
            // $venue->macaddress = \Input::get('macaddress');
            $venue->ssid = $ssid;
            $venue->device_type = $input['device_type'];
            $venue->latitude = \Input::get('latitude');
            $venue->longitude = \Input::get('longitude');
            $venue->address = \Input::get('address');
            $venue->server_id = \Input::get('server_id');
            $venue->ap_active = \Input::get('ap_active');
            $venue->contact = \Input::get('contact');
            $venue->notes = \Input::get('notes');
            $venue->mon_from = $input['mon_from'];
            $venue->mon_to = $input['mon_to'];
            $venue->tue_from = $input['tue_from'];
            $venue->tue_to = $input['tue_to'];
            $venue->wed_from = $input['wed_from'];
            $venue->wed_to = $input['wed_to'];
            $venue->thu_from = $input['thu_from'];
            $venue->thu_to = $input['thu_to'];
            $venue->fri_from = $input['fri_from'];
            $venue->fri_to = $input['fri_to'];
            $venue->sat_from = $input['sat_from'];
            $venue->sat_to = $input['sat_to'];
            $venue->sun_from = $input['sun_from'];
            $venue->sun_to = $input['sun_to'];
            $venue->statuscomment = $input['statuscomment'];
            if (empty($adminssid1check) &&  $input['adminssid1'] !== ""){
                $venue->adminssid1 = $input['adminssid1'];
                $venue->password1 = $input['firstnetworkpassword'];
                $venue->type1 = $input['type1'];
            }

            if (empty($adminssid2check) &&  $input['adminssid2'] !== ""){
                $venue->adminssid2 = $input['adminssid2'];
                $venue->password2 = $input['secondnetworkpassword'];
                $venue->type2 = $input['type2'];
            }

            if (empty($adminssid3check) &&  $input['adminssid3'] !== ""){
                $venue->adminssid3 = $input['adminssid3'];
                $venue->password3 = $input['thirdnetworkpassword'];
                $venue->type3 = $input['type3'];
            }
            if($input['bypassmac0'] !== "") {
            $venue->bypassmac1 = $input['bypassmac0'];
            $venue->bypasscomment1 = $input['bypasscomment0'];
            }
            
            if($input['bypassmac1'] !== "") {
            $venue->bypassmac2 = $input['bypassmac1'];
            $venue->bypasscomment2 = $input['bypasscomment1'];
            }
             if($input['bypassmac2'] !== "") {
            $venue->bypassmac3 = $input['bypassmac2'];
            $venue->bypasscomment3 = $input['bypasscomment2'];
            }
            if($input['bypassmac3'] !== "") {
            $venue->bypassmac4 = $input['bypassmac3'];
            $venue->bypasscomment4 = $input['bypasscomment3'];
            }
             if($input['bypassmac4'] !== "") {
            $venue->bypassmac5 = $input['bypassmac4'];
            $venue->bypasscomment5 = $input['bypasscomment4'];
            }

             if($input['bypassmac5'] !== "") {
            $venue->bypassmac6 = $input['bypassmac5'];
            $venue->bypasscomment6 = $input['bypasscomment5'];
            }
            if($input['bypassmac6'] !== "") {
            $venue->bypassmac7= $input['bypassmac6'];
            $venue->bypasscomment7 = $input['bypasscomment6'];
            }

            if($input['bypassmac7'] !== "") {
            $venue->bypassmac8 = $input['bypassmac7'];
            $venue->bypasscomment8 = $input['bypasscomment7'];
            }

             if($input['bypassmac8'] !== "") {
            $venue->bypassmac9 = $input['bypassmac8'];
            $venue->bypasscomment9 = $input['bypasscomment8'];
            }
             if($input['bypassmac9'] !== "") {
            $venue->bypassmac10 = $input['bypassmac9'];
            $venue->bypasscomment10 = $input['bypasscomment9'];
            }
            $venue->save();

            $venue = $venue->refreshMediaLocation($venue);
            $venue->updateVenueInRadius($venue, $remotedb_id);
        }
       
        // Update the AP
            if($input['device_type'] == "Mikrotik") {
               $mikrotik = new \Mikrotik();
               $mikrotik->updateVenue($venue, $old_sitename);
               // admin wifi config writing to mac-address.rsc process begins.
               for($i=1; $i<=3; $i++){
                    $adminssid = 'adminssid' . $i;
                    $password = 'password' . $i;
                    $type = 'type' . $i;
                    if($input[$adminssid] !== "") {
                        \Log::info("HANNES PASSWORD 2!!!! $venue->$password");
                        $this->addAdminWifi($venue->id, $venue->$adminssid, $venue->$password, $venue->$type, $number = $i);
                    }
                }
                    //Bypass mac-address config writing to mac-address.rsc process begins
                    //HERE HANNES
                    

                // $shouldsave = true;
                // for($i=0; $i<=9; $i++){
                    
                //     $bypass = 'bypassmac' . $i;
                //     $value = $input[$bypass];

                //     if ($value != "" && $value != null){
                //         \Log::info("HANNES INPUT IS: $value");
                //         if($venue->bypassmac1 == $value || 
                //         $venue->bypassmac2 == $value || 
                //         $venue->bypassmac3 == $value || 
                //         $venue->bypassmac4 == $value || 
                //         $venue->bypassmac5 == $value || 
                //         $venue->bypassmac6 == $value || 
                //         $venue->bypassmac7 == $value || 
                //         $venue->bypassmac8 == $value || 
                //         $venue->bypassmac9 == $value || 
                //         $venue->bypassmac10 == $value){
                //             $shouldsave = false;
                //             \Log::info("HANNES SHOULD NOT SAVE");
                //         }
                //         else {
                //             $shouldsave = true;
                //             \Log::info("HANNES SHOULD SAVE");
                //         }
                //     }
                // }
                
                // $shouldsave = true;

                // for($i=1; $i<=3; $i++){

                //     $bypass = 'bypassmac' . $i;
                //     $value = $input[$bypass];
                //     \Log::info("HANNES TESTING: ORIGINAL VALUE IS: $value");
                //     if($value != null && $value != "") {

                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac1");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac2");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac3");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac4");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac5");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac6");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac7");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac8");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac9");
                //         \Log::info("HANNES TESTING: value: $value, bypass is $venue->bypassmac10");
                //         if($venue->bypassmac1 == $value || 
                //         $venue->bypassmac2 == $value || 
                //         $venue->bypassmac3 == $value || 
                //         $venue->bypassmac4 == $value || 
                //         $venue->bypassmac5 == $value || 
                //         $venue->bypassmac6 == $value || 
                //         $venue->bypassmac7 == $value || 
                //         $venue->bypassmac8 == $value || 
                //         $venue->bypassmac9 == $value || 
                //         $venue->bypassmac10 == $value){
                //             $shouldsave = false;
                //         }

                //     }

                // }
                    $nottosave = null;
                    for($i=0; $i<=9; $i++){
                        \Log::info("HANNES ALL INPUTS AT POS: ".$input["bypassmac$i"]." ");
                        if($input["bypassmac$i"] != null && $input["bypassmac$i"] != "") {
                            if ($input["bypassmac$i"] == $venue->bypassmac1 || 
                            $input["bypassmac$i"] == $venue->bypassmac2 || 
                            $input["bypassmac$i"] == $venue->bypassmac3 || 
                            $input["bypassmac$i"] == $venue->bypassmac4 || 
                            $input["bypassmac$i"] == $venue->bypassmac5 || 
                            $input["bypassmac$i"] == $venue->bypassmac6 || 
                            $input["bypassmac$i"] == $venue->bypassmac7 || 
                            $input["bypassmac$i"] == $venue->bypassmac8 || 
                            $input["bypassmac$i"] == $venue->bypassmac9 || 
                            $input["bypassmac$i"] == $venue->bypassmac10){
                                $nottosave = $input["bypassmac$i"];
                            }    
                        }
                    }
                        

                $value = $input['bypassmac0'];
                if($venue->bypassmac1 && $nottosave && $venue->bypassmac1 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac1, $venue->bypasscomment1);
                }
                $value = $input['bypassmac1'];
                if($venue->bypassmac2 && $nottosave && $venue->bypassmac2 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac2, $venue->bypasscomment2);
                }
                $value = $input['bypassmac2'];
                if($venue->bypassmac3 && $nottosave && $venue->bypassmac3 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac3, $venue->bypasscomment3);
                }
                $value = $input['bypassmac3'];
                if($venue->bypassmac4 && $nottosave && $venue->bypassmac4 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac4, $venue->bypasscomment4);
                }
                $value = $input['bypassmac4'];
                if($venue->bypassmac5 && $nottosave && $venue->bypassmac5 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac5, $venue->bypasscomment5);
                }
                $value = $input['bypassmac5'];
                if($venue->bypassmac6 && $nottosave && $venue->bypassmac6 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac6, $venue->bypasscomment6);
                }
                $value = $input['bypassmac6'];
                if($venue->bypassmac7 && $nottosave && $venue->bypassmac7 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac7, $venue->bypasscomment7);
                }
                $value = $input['bypassmac7'];
                if($venue->bypassmac8 && $nottosave && $venue->bypassmac8 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac8, $venue->bypasscomment8);
                }
                $value = $input['bypassmac8'];
                if($venue->bypassmac9 && $nottosave && $venue->bypassmac9 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac9, $venue->bypasscomment9);
                }
                $value = $input['bypassmac9'];
                if($venue->bypassmac10 && $nottosave && $venue->bypassmac10 != $nottosave) {
                    $mikrotik->addMacAddressBypass($venue, $venue->bypassmac10, $venue->bypasscomment10);
                }

                for($i=0; $i<=9; $i++){
                    
                    \Log::info("HANNES IN LOOP AT POS: $i");
                    $bypass = 'bypassmac' . $i;
                    $k = $i + 1;
                    $mac = 'bypassmac' . $k;
                    $comment = 'bypasscomment'. $k;
                    
                    \Log::info("HANNES IN LOOP input bypass: ". $input[$bypass] ." and should add mac address");

                    if($input[$bypass] !== "") {
                        \Log::info("HANNES IN LOOP and will save");
                        $mikrotik->addMacAddressBypass($venue, $venue->$mac, $venue->$comment);
                    }

                }

                // check if the device has got any tabletpos printer configured then modify the file that it will use to monitor them.
                $printers = new \Tabletposprinter();
                $printers = $printers->getPrintersForVenue($id);
                if($printers){
                  $macaddress = \Input::get('macaddress');
                  $mikrotik->genTabletposRsc($printers, $macaddress);
                }
                   
           }
            return \Redirect::route('hipwifi_showvenues');
}      


    
    
     public function disableVenue($id)
    {
        error_log("disableVenue");
        $venue = \Venue::find($id);
        $brand_id = $venue->brand_id;
        $remotedb_id = \Brand::find($brand_id)->remotedb_id;
        $media = new \Media();

        if($venue) {

            \Venue::where("id", "=", $id)->update(['wifi_activated' => 0, 'macaddress' => '']);
            $venue->deleteVenueInRadius($venue, $remotedb_id);
            $media->where("venue_id", "=", $id)->delete();
            $mikrotik = new \Mikrotik();
            $mikrotik->deleteVenue($venue);
        }

        return \Redirect::route('hipwifi_showvenues', ['json' => 1, ]);
    }

      public function addAdminWifi($id, $adminssid, $password, $type, $number)
    {
        switch ($number) {
            case '1':
                $filename = "add_admin_wifi1.rsc";
                break;

            case '2':
                $filename = "add_admin_wifi2.rsc";
                break;

            case '3':
                $filename = "add_admin_wifi3.rsc";
                break;
        }

        $venue = \Venue::find($id);
        \Log::info("HANNES PASSWORD 1!!!! $password");
        
        $mikrotik = new \Mikrotik();
        $mikrotik->modifyAdminWifiTemplate($adminssid, $password, $type, $filename, $venue);
    }

     public function deleteadminssid($id, $adminssid)
    {

        $venue = \Venue::find($id);
        $adminssid1 = $venue->adminssid1;
        $adminssid2 = $venue->adminssid2;
        $adminssid3 = $venue->adminssid3;

        $mikrotik = new \Mikrotik();
        $mikrotik->deleteadminssid($adminssid, $venue);

        switch ($adminssid) {
            case "$adminssid1":
                 \DB::table('venues')->where('id', '=', $id)->update(array('adminssid1' => NULL));
                 \DB::table('venues')->where('id', '=', $id)->update(array('password1' => NULL));
                 \DB::table('venues')->where('id', '=', $id)->update(array('type1' => NULL));
                 
                break;

            case "$adminssid2":
                 \DB::table('venues')->where('id', '=', $id)->update(array('adminssid2' => NULL));
                 \DB::table('venues')->where('id', '=', $id)->update(array('password2' => NULL));
                 \DB::table('venues')->where('id', '=', $id)->update(array('type2' => NULL));
                break;

            case "$adminssid3":
                 \DB::table('venues')->where('id', '=', $id)->update(array('adminssid3' => NULL));
                 \DB::table('venues')->where('id', '=', $id)->update(array('password3' => NULL));
                 \DB::table('venues')->where('id', '=', $id)->update(array('type3' => NULL));
                break;
            }

             return \Redirect::to('hipwifi_editvenue/'.$id);


        

    }

    public function deletemacbypass($id, $bypassmac)
    {
        //Hannes hier
        $venue = \Venue::find($id);
        $bypassmacarray = [$venue->bypassmac1, $venue->bypassmac2, $venue->bypassmac3, $venue->bypassmac4, $venue->bypassmac5, 
                                        $venue->bypassmac6, $venue->bypassmac7, $venue->bypassmac8, $venue->bypassmac9, $venue->bypassmac10];
        $mikrotik = new \Mikrotik();
        $mikrotik->deletebypassmac($venue, $bypassmac);
        for ($i=0; $i <=9; $i++) { 
            if ($bypassmacarray[$i] == $bypassmac){
                $index = $i + 1;
                \DB::table('venues')->where('id', '=', $id)->update(array('bypassmac'.$index => NULL, 'bypasscomment'.$index => NULL));
            }
           
        }
        return \Redirect::to('hipwifi_editvenue/'.$id);
    }
       

    
     public function redeployMikrotikVenue($id)    {
        error_log("redeployMikrotikVenue");
        $venue = \Venue::find($id);

        if($venue) {
            $mikrotik = new \Mikrotik();
            $mikrotik->addVenue($venue);
        }

        return \Redirect::route('hipwifi_showvenues');
    }


    // public function deployRsc($id)
    // {
    //     error_log("deployRsc");
    //     $dir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
    //     $data = array();
    //     $data['currentMenuItem'] = "Venue Management";
    //     $data['id'] = $id;
    //     $data['message'] = "";
    //     $data['venue'] = \Venue::find($id);
    //     $data['venue']["sitename"] = preg_replace("/ /", "_",$data['venue']->sitename); 
    //     $rscfile =  $dir->value . "deployment/exports" .  $data['venue']["sitename"] . ".rsc"; 
    //     if (file_exists($rscfile)) {
    //         $data['rscconfig'] = file($rscfile);
    //     }  else {
    //         $data['rscconfig'] = ["Configuration file not found!"];
    //     }

           

        
    //     foreach($data['venue'] as $key => $value) { error_log("TTT : $key => $value"); };

    //     return \View::make('hipwifi.deployrsc')->with('data', $data);
    // }


    // public function deployRscSave()
    // {

    //     $data = array();
    //     $data['currentMenuItem'] = "Venue Management";

    //     $id = \Input::get('id');
    //     error_log("deployRsc id = $id");

    //     $overridersc = \Input::get('overridersc');
    //     error_log("deployRsc overridersc : $overridersc");

    //     $data['venue'] = \Venue::find($id);
    //     $data['rscconfig'] = ["Just deployed script will take 5 minutes to become active.", "Please go back to the venue management page and check back after 5 minutes."];

    //     $scripttext = \Input::get('scripttext');
    //     // error_log("deployRsc : scripttext = $scripttext");

    //     $mikrotik = new \Mikrotik();
    //     $data['message'] = $mikrotik->deployRsc($data['venue'], $scripttext, $overridersc);

    //     return \View::make('hipwifi.deployrsc')->with('data', $data);
    // }
      

    public function editWifiuser($id)
    {
        $data = array();
        $data['currentMenuItem'] = "User Management";
        $data['edit'] = true;
        $data['insight'] = false;

        return \View::make('hipwifi.addwifiuser')->with('data', $data);
    }

    public function addTabletposPrinter(){
        $data = \Input::all();
        $printername = $data['printername'];
        $printerip = $data['printerip'];
        $venueid = $data['venueid'];
        $messages = ["printername.alpha_num_dash" => "Printername can only be alphanumeric", "printerip.ip" => "ip address given is not correct"];

        $rules = ["printername" => 'required|alpha_num_dash', "printerip" => "required|ip"];
        $validator = \Validator::make($data, $rules, $messages);
        if ($validator->fails()){
            $message = array('msg' =>$validator->messages(), 'status' => '422');
             return $message;
        }
        else{
                $printer = new \Tabletposprinter();
                $entryid = $printer->addPrinter($printername, $printerip, $venueid);
                if (!$entryid){
                    $msg = array();
                    $msg[0] = "A printer with such name or ip already exists for this venue";
                    $message = array('msg' => $msg, 'status' => '423');
                    return $message;
                }
                 else{
                        $insertedprinter = $printer->getPrinter($entryid);
                        $row = '<tr>
                                        <td><input type=text name="name'.$entryid.'"  value="'.$insertedprinter->name.'"></td>
                                        <td><input type=text name="ipaddr'.$entryid.'" value="'.$insertedprinter->ipaddress.'"></td>
                                        <td><a href="javascript:void(0);"  dbid="'.$entryid.'" class="btn btn-small btn-default">Update</a></td>
                                        <td><a href="javascript:void(0);"  dbid="'.$entryid.'" class="btn btn-small btn-default btn-danger">Delete</a></td>
                                        </tr>';
                                    
                        return $row;
                 }
            }

      

    }

    public function editTabletposPrinter(){
        error_log("entering editTabletposPrinter function");
        $data = \Input::all();
        //dd($data);
        $printerid = $data['id'];
        $newprintername = $data['printername'];
        $newprinterip = $data['printerip'];
        $venueid = $data['venueid'];
        $messages = ["printername.alpha_num_dash" => "Printername can only be alphanumeric", "printerip.ip" => "ip address given is not correct"];


        $rules = ["printername" => 'required|alpha_num_dash', "printerip" => "required|ip"];
        $validator = \Validator::make($data, $rules, $messages);
        if ($validator->fails()){
            $message = array('msg' =>$validator->messages(), 'status' => '422');
            return $message;
        }
        else{
                $printer = new \Tabletposprinter();
                error_log("entering the Tableposprintermodel");

                $entryid = $printer->editPrinter($printerid, $newprintername, $newprinterip, $venueid);
                if (!$entryid){
                    $msg = array();
                    $msg[0] = "A printer with such name and ip already exists for this venue";
                    $message = array('msg' => $msg, 'status' => '423');
                    return $message;
                }
                else{
                    return "true";
                }
        }


    }

    public function deleteTabletposPrinter(){
        $input = \Input::all();
        $id = $input['id'];
        $printer = new \Tabletposprinter();
        $deleteprinter = $printer->deletePrinter($id);
        if($deleteprinter){
            return "true";
        }
    }


 


}


   

  

   

   
  
