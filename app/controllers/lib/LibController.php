<?php

namespace lib;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

// use BaseController;

class LibController extends \BaseController {

    public function getServersForDatabase() {

        $remotedb_id = \Input::get('remotedb_id');

        error_log("getServersForDatabase : remotedb_id : $remotedb_id");

        $servers = \DB::table('servers')
                ->select("*")
                ->where('remotedb_id', '=', $remotedb_id)
                ->get();

        return \Response::json($servers);
    }

    public function getBrandsForDatabase() {

        $remotedb_id = \Input::get('remotedb_id');
        $user =  \Auth::user();

        error_log("getBrandsForDatabase : remotedb_id : $remotedb_id");

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();
        } else {
            error_log("getBrandsForDatabase : NOT superadmin");
            $allowedbrands = $user->brands;
        }

        $allowedbrand_ids = array();
        foreach($allowedbrands as $allowedbrand) {
            error_log("getBrandsForDatabase : allowedbrand");
            array_push($allowedbrand_ids, $allowedbrand['id']);
        }

        $brands = \DB::table('brands')
                ->select("*")
                ->where('remotedb_id', '=', $remotedb_id)
                ->whereIn('id', $allowedbrand_ids)
                ->whereNull('deleted_at')
                ->get();

        return \Response::json($brands);
    }

    public function getProvinces($countrie_id)
    {
        error_log("getProvinces");

        $province = new \Province();
        $provinces = $province->getprovinces($countrie_id);
        $provincesJson = json_encode($provinces);
        return \Response::json($provincesJson);
    }

    public function getCities($province_id)
    {
        error_log("getCities");

        $citie = new \Citie();
        $cities = $citie->getcities($province_id);
        $citiesJson = json_encode($cities);
        return \Response::json($citiesJson);
    }

    public function getServers($brand_id)
    {
        error_log("getServers");

        $remotedb_id = \Brand::find($brand_id)->remotedb_id;
        error_log("getServers : remotedb_id : $remotedb_id");
        $servers = \DB::table('servers')->select("*")->where('remotedb_id', '=', $remotedb_id)->get();
        error_log("getServers : xxxxxxxxxxxxxxxxxxxx");
        error_log("getServers : " . print_r($servers, true));
        $serversJson = json_encode($servers);
        return \Response::json($serversJson);
    }

    public function getVenues()
    {
        error_log("getVenues");
        $isp_id = \Input::get('isp_id');
        $brand_id = \Input::get('brand_id');
        $city_id = \Input::get('citie_id');

        error_log("getVenues : isp_id : $isp_id");
        error_log("getVenues : brand_id : $brand_id");
        error_log("getVenues : city_id : $city_id");

        $venue = new \Venue();
        $venues = $venue->getvenues($isp_id, $brand_id, $city_id);
        $venuesJson = json_encode($venues);
        return \Response::json($venuesJson);
    }

    public function isSitenameDuplicate()
    {
        error_log("isSitenameDuplicate");
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $message = "";

        $venue = \Venue::where("sitename", $sitename)->get();
        if ($venue->isEmpty()) { $message = "empty"; } else { $message = "exists"; }

        return \Response::json($message);
    }

    public function isDuplicate()
    {
        error_log("isDuplicate");
        $table = \Input::get('table');
        $column = \Input::get('column');
        $value = \Input::get('value');

        error_log($table);
        error_log($column);
        error_log($value);
        $message = "";

        if($table == "venues") {
            $venue = \Venue::where($column, $value)->get();
            if ($venue->isEmpty()) { $message = "empty"; } else { $message = "exists"; }
        } else {
            $message = "Table " . $table . " does not exist.";
        }
        error_log("message : $message");

        return \Response::json($message);
    }

    public function buildLocationCode()
    {
        error_log("buildLocationCode");

        $brand_id = \Input::get('brand_id');
        $isp_id = \Brand::find($brand_id)->isp_id;
        $sitename = \Input::get('sitename');
        $countrie_id = \Input::get('countrie_id');
        $province_id = \Input::get('province_id');
        $citie_id = \Input::get('citie_id');

        $utils = new \Utils();
        $locationCode = $utils->buildLocationCode($isp_id, $brand_id, $sitename, $countrie_id, $province_id, $citie_id);

        return \Response::json($locationCode);
    }

    public function buildMatchLocationCode()
    {

        $brand_id = \Input::get('brand_id');
        $isp_id = \Brand::find($brand_id)->isp_id;
        $countrie_id = \Input::get('countrie_id');
        $province_id = \Input::get('province_id');
        $citie_id = \Input::get('citie_id');
        $venue_id = \Input::get('venue_id');


        $utils = new \Utils();
        $locationCode = $utils->buildMatchLocationCode($isp_id, $brand_id, $countrie_id, $province_id, $citie_id, $venue_id);

        return \Response::json($locationCode);
    }

    public function filterBeacons($json = null) {
        error_log("lib filterBeacons : 10" );
        $data = array();
        $data['currentMenuItem'] = "Venue Monitoring";

        $venueObj = new \Venue();
        $venues = $venueObj->getVenuesForUser("hipengage");

        $data = array();

        foreach($venues as $venue) {
            // Get the beacons

            error_log("lib filterBeacons : venue->location = " . $venue->location );

            $venuepositions = \Venueposition::where('location', 'like', $venue->location)->get();

            foreach($venuepositions as $venueposition) {

                $beacon = \Beacon::where('venueposition_id', '=', $venueposition->id)->first();

                if($beacon) {

                    $row = array();
                    $row["sitename"] = $venue->sitename;
                    $row["beacon_id"] = $beacon->beacon_id; //
                    $row["position"] = $venueposition->name; // From venuepositions

                    $lastBeaconMessage = \Beaconmessage::where('beacon_id', 'like', "%" . $beacon->beacon_id . "%")->orderBy('created_at', 'desc')->first();
                    if($lastBeaconMessage) {
                        $row["last_checkin"] = $lastBeaconMessage->created_at->format('d M Y - H:i:s');  // Get the last check in from beacon_messages
                        $row["battery_level"] = $lastBeaconMessage->battery_level; // Get the battery level from beacon_messages
                    } else {
                        $row["last_checkin"] = "---";  // Get the last check in from beacon_messages
                        $row["battery_level"] = "999"; // Get the battery level from beacon_messages
                    }
                    array_push($data, $row);

                }
            }
        }

        $data['beaconsJson'] = json_encode($data);

        return \Response::json($data['beaconsJson']);
    }

    public function filterVenues() {

        $sitename = \Input::get('sitename');
        $macaddress = \Input::get('macaddress');

        $sitename =  "%" . $sitename . "%";
        $macaddress =  "%" . $macaddress . "%";

        error_log("filterVenues : $sitename : $macaddress");

        $venues = \Venue::where('sitename', 'like', $sitename)
                    ->where('macaddress', 'like', $macaddress)
                    ->whereNull('deleted_at')
                    ->get();

        foreach($venues as $venue) {
            if($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        return \Response::json($venues);
    }

    public function filterServers() {

        $hostname = \Input::get('hostname');
        $brand = \Input::get('brand');

        $hostname =  "%" . $hostname . "%";
        $brand =  "%" . $brand . "%";

        // error_log("filterServers : $hostname : $brand");

        $server = new \Server();
        $servers = $server->getServersForProduct(1, $hostname, $brand);

        return \Response::json($servers);
    }


    public function getBrands($countrie_id) {

        $user =  \Auth::user();

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();
        } else {
            error_log("getBrands : NOT superadmin");
            $allowedbrands = $user->brands;
        }

        $allowedbrand_ids = array();
        foreach($allowedbrands as $allowedbrand) {
            array_push($allowedbrand_ids, $allowedbrand['id']);
        }

        $brands = \DB::table('brands')
                ->select("*")
                ->where('countrie_id', $countrie_id)
                ->whereIn('id', $allowedbrand_ids)
                ->whereNull('deleted_at')
                ->get();

        return \Response::json($brands);
    }

    public function saveDtMedia() {
        //Hannes image 5
        error_log("saveDtMedia : 10");

        

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $destinationPath = $assetsdir->value . 'hipwifi/images';

        \Log::info("[LibController - saveDtMedia] - destinationPath is: $destinationPath");

        error_log("saveDtMedia : destinationPath : $destinationPath");

        // Save the desktop image
        if(\Input::file('dtimage')) {
            $file = array('dtimage' => \Input::file('dtimage'));
            $extension = \Input::file('dtimage')->getClientOriginalExtension();
            \Log::info("[LibController - saveDtMedia] - extension is: $extension");
            $fileName = "preview" .'-dt.'.$extension;
            \Log::info("[LibController - saveDtMedia] - fileName is: $fileName");
            \Input::file('dtimage')->move($destinationPath, $fileName);
        }

        error_log("saveDtMedia : extension : $extension");
        \Log::info("[LibController - saveDtMedia] - extension is: $extension");
        return "$extension";
    }

    public function savePushMedia() {

        error_log("savePushMedia : 10");

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $destinationPath = $assetsdir->value . 'hipengage/images';

        error_log("savePushMedia : destinationPath : $destinationPath");

        // Save the image
        if(\Input::file('mbimage')) {
            $file = array('mbimage' => \Input::file('mbimage'));
            $extension = \Input::file('mbimage')->getClientOriginalExtension();
            $fileName = "preview" . '.' . $extension;
            error_log("savePushMedia : Saving image : fileName = $fileName");
            \Input::file('mbimage')->move($destinationPath, $fileName);
        }

        error_log("savePushMedia : extension : $extension");

        return "$extension";
    }

    public function savelookupMedia() {

        error_log("savelookupMedia : 10");

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $destinationPath = $assetsdir->value . 'hiptna/images';

        error_log("savelookupMedia : destinationPath : $destinationPath");

        // Save the image
        if(\Input::file('mbimage')) {
            $file = array('mbimage' => \Input::file('mbimage'));
            $extension = \Input::file('mbimage')->getClientOriginalExtension();
            $fileName = "preview" . '.' . $extension;
            error_log("savelookupMedia : Saving image : fileName = $fileName");
            \Input::file('mbimage')->move($destinationPath, $fileName);
        }

        error_log("savelookupMedia : extension : $extension");

        return "$extension";
    }

    public function saveMbMedia() {

        error_log("saveMbMedia : 10");

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $destinationPath = $assetsdir->value . 'hipwifi/images';

        error_log("saveMbMedia : destinationPath : $destinationPath");

        // Save the desktop image
        if(\Input::file('mbimage')) {
            $file = array('mbimage' => \Input::file('mbimage'));
            $extension = \Input::file('mbimage')->getClientOriginalExtension();
            $fileName = "preview" .'-mb.'.$extension;
            \Input::file('mbimage')->move($destinationPath, $fileName);
        }

        error_log("saveMbMedia : extension : $extension");

        return "$extension";
    }

    public function correct_size($photo)
    {
        $maxHeight=1900;
        $maxWidth=1500;
        list($width,$height)=getimagesize($photo);
        if(($width<=$maxWidth) && ($height<=$maxHeight)){
            return 1;
        } else {
            return 0;
        }
    }

    public function saveFpMedia() {

        error_log("saveFbMedia : 10");
        $file = \Input::file('fpimage');
        $validator = \Validator::make(
            [
                'file'      => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                /*'file' => 'required|image|mimes:jpg|max:2048|dimensions:width=500,height=500',*/
                /*'file'          => 'required|image|mimes:jpg|max:2048',*/
                'file'          => 'required|image|max:2048',
                'extension'      => 'required|in:jpg',
            ]
        );
        if ($validator->fails()) {
            //echo "Not Valid";
            return $validator->messages();
        } else if( $this->correct_size(\Input::file('fpimage')) == 0 ){
            $msg = array("file"=>"Image Dimension : 800W X 600H");
            print_r(json_encode($msg));
            die();
        }else{

            //echo 'suces'; die();

            $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
            $destinationPath1 = $assetsdir->value . 'track/images';
            $destinationPath = public_path().'/assets/track/images';
            //$destinationPath = '/home/anusha/public_html/hiphub/public/assets/track/images';
            error_log("saveFbMedia : destinationPath : $destinationPath");

            // Save the floorplan image
            if(\Input::file('fpimage')) {
                $venue = \Input::get('venue_location');
                $file = array('fpimage' => \Input::file('fpimage'));
                $extension = \Input::file('fpimage')->getClientOriginalExtension();
                //$fileName = "preview" .'-fp-'.$venue.'.'.$extension;
                $fileName = $venue.'.'.$extension;
                //\Input::file('fpimage')->move($destinationPath, $fileName); //for testing purpose, now move the file to the /public/assets/track/images , because i cant access to my /var/www/ dir. - Anusha
                \Input::file('fpimage')->move($destinationPath1, $fileName);
            }

            error_log("saveFpMedia : extension : $extension");
            $msg = array('file'=>'success', 'extension'=>$extension);
            print_r(json_encode($msg));
            //return "$extension";
        }
    }

    public function getBrandData() {


        $brand_id = \Input::get('brand_id');

        $arr = array();
        $arr["login_process"] = \Brand::find($brand_id)->login_process;
        $arr["welcome_message"] = \Brand::find($brand_id)->welcome;

        error_log("getBrandData : $brand_id  login_process : " . $arr["login_process"]);

        if($arr["login_process"] == "full") {
            $arr["login_process_name"] = "Full Registration";
        } else {
            $arr["login_process_name"] = "Zero Registration";
        }

        return \Response::json($arr);

    }

    public function getServerUrl() {
        $brand_id = \Input::get('brand_id');
        error_log("libController : getServerUrl : brand_id : " . $brand_id);

        // Get a sampple venue
        // $sitename = \Venue::where("brand_id", "=", $brand_id)->first()->sitename;
        // $nasid = preg_replace("/ /", "_", $sitename);

        // error_log("libController : getServerUrl : nasid : " . $nasid);

        // $venue = new \Venue();
        // $first_nasid = $venue->getFirstNasidForBrand($brand_id);
        $brand = \Brand::find($brand_id);
        $ispcode = \Isp::find($brand->isp_id)->code;

        $remotedb_id = $brand->remotedb_id;
        $hostname = \Server::where("remotedb_id", "=", $remotedb_id)->first()->hostname;
        $hostname = "http://" . $hostname . "/";

        // $hostname = $hostname +

        // login?res=logoff&nasid=kauai_bayside

        // $hostname = "http://hipspot.hipzone.co.za/";
        // $hostname = "http://native.localhost/";


        $returnArray = array("hostname"=>$hostname, "brand"=>$brand, "ispcode"=>$ispcode);

        error_log("getServerUrl :xxxxx $hostname : 30");

        return \Response::json($returnArray);
    }

    public function getEngageApplications() {

        error_log("getEngageApplications");

        $applications = \Application::all();

        return \Response::json(json_encode($applications));
    }

    public function getEngageTriggers($application_code) {

        error_log("getEngageTriggers application_code = $application_code");

        $triggers = \Trigger::where('application_code', 'like', $application_code)->get();

        return \Response::json(json_encode($triggers));
    }

    public function getEngageMeasures($trigger_code) {

        error_log("getEngageMeasures trigger_code = $trigger_code");

        $trigger = \Trigger::where('code', 'like', $trigger_code)->first();


        $measures = $trigger->measures();
        // echo "<pre>";
        // echo print_r($measures);
        // echo "</pre>";
        // dd();
        return \Response::json(json_encode($measures));
    }

    public function getEngageOperators($measure_code) {

        error_log("getEngageOperators measure_code = $measure_code");

        $measure = \Measure::where('code', 'like', $measure_code)->first();
        $operators = \Operator::where('measure_code', 'like', $measure->code)->get();

        // echo "<pre>";
        // echo print_r($measure);
        // echo "</pre>";

        // $operators = $measure->operators();

        return \Response::json(json_encode($operators));
    }





    public function getEngageSmsNotifications() {

        error_log("getEngageSmsNotifications");

        $smsnotifications = \Smsnotification::all();

        return \Response::json(json_encode($smsnotifications));
    }


    public function getEngageSmsNotification($id) {

        error_log("getEngageSmsNotification : id = $id");

        if($id) {

            $smsnotification = \Smsnotification::find($id);

            return \Response::json(json_encode($smsnotification));


        } else {

            return 0;

        }

    }


    public function getEngageEmailNotifications() {

        error_log("getEngageEmailNotifications");

        $emailnotifications = \Emailnotification::all();

        return \Response::json(json_encode($emailnotifications));
    }


    public function getEngageEmailNotification($id) {

        error_log("getEngageEmailNotification : id = $id");

        if($id) {

            $emailnotification = \Emailnotification::find($id);

            return \Response::json(json_encode($emailnotification));


        } else {

            return 0;

        }

    }


    public function lib_sendtestemail() {
        $to =  \Input::get('to');
        $subject = \Input::get('subject');
        $message =  \Input::get('message');
        $headers =  \Input::get('headers');
        error_log("lib_sendtestemail : to = $to");
        error_log("lib_sendtestemail : subject = $subject");
        error_log("lib_sendtestemail : message = $message");
        error_log("lib_sendtestemail : headers = $headers");

        // $returnVals = mail($to,$subject,$message,$headers);

        $returnVals = \Mail::send('testemail', array('key' => 'value'), function($message) use($to, $subject)
        {
            $message->to($to)->subject($subject);
        });

        return \Response::json(json_encode($returnVals));
    }

    public function getEngageApiNotifications() {

        error_log("getEngageApiNotifications");

        $apinotifications = \Apinotification::all();

        return \Response::json(json_encode($apinotifications));
    }


    public function getEngageApiNotification($id) {

        error_log("getEngageApiNotification : id = $id");

        if($id) {

            $apinotification = \Apinotification::find($id);

            return \Response::json(json_encode($apinotification));


        } else {

            return 0;

        }

    }


    public function getEngageMgrNotifications() {

        error_log("getEngageMgrNotifications");

        $mgrnotifications = \Mgrnotification::all();

        return \Response::json(json_encode($mgrnotifications));
    }


    public function getEngageMgrNotification($id) {

        error_log("getEngageMgrNotification : id = $id");

        if($id) {

            $mgrnotification = \Mgrnotification::find($id);

            return \Response::json(json_encode($mgrnotification));


        } else {

            return 0;

        }

    }

    public function getEngageNotifications($type) {

        error_log("getEngageNotifications");
        $utils = new \Utils();
        $allowedbrandcodes = $utils->getAllowedEngageBrandcodes();

        if($type == "push") {
            $notifications = \Pushnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        } else if($type == "sms") {
            $notifications = \Smsnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        } else if($type == "api") {
            $notifications = \Apinotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        } else if($type == "email") {
            $notifications = \Emailnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        } else if($type == "mgr") {
            $notifications = \Mgrnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        }

        return \Response::json(json_encode($notifications));

    }

    public function getEngagePushNotifications() {

        error_log("getEngagePushNotifications");

        $pushnotifications = \Pushnotification::all();

        return \Response::json(json_encode($pushnotifications));
    }

    public function getEngagePushNotification($id) {

        error_log("getEngagePushNotification : id = $id");

        if($id) {

            $pushnotification = \Pushnotification::find($id);

            return \Response::json(json_encode($pushnotification));


        } else {

            return 0;

        }


        // error_log("getEngagePushNotification : name : " . $pushnotification["name"]);

    }

    public function getPositionNames($brandcode) {

        // echo "positionnames : brandcode = $brandcode";

        $positionnames = \Venueposition::selectRaw('distinct(name)')
                                        ->where('location', 'like', "___" . $brandcode . "%")
                                        ->get();

        // echo "positionnames" . print_r($positionnames, true);

        return \Response::json(json_encode($positionnames));
    }

    public function getVenuePositions($location) {

        $venuepositions = \Venueposition::join('beacons', 'venuepositions.id', '=', 'beacons.venueposition_id')
                                            ->selectRaw('venuepositions.*, beacons.beacon_id')
                                            ->where('location', 'like', $location)
                                            ->get();

        return \Response::json(json_encode($venuepositions));
    }

    public function getBeacons($brandcode) {

        // WE WILL NEED TO MAKE THIS DYNAMIC FOR ORGANIZATIONS SOON
        $beacons = \Beacon::where('venueposition_id', '=', 0)
                          ->where('brand_code', 'like', $brandcode)
                          ->get();

        return \Response::json(json_encode($beacons));
    }

    public function saveVenuePosition() {

        $beacon_id = \Input::get('beacon_id');
        $name = \Input::get('venueposition_name');
        $location = \Input::get('location');

        $thisPosition = new \Venueposition();
        $thisPosition->name = $name;
        $thisPosition->location = $location;
        $thisPosition->save();

        // echo "thisPosition id : " . $thisPosition->id;
        $record = array( "venueposition_id" => $thisPosition->id );

        \Beacon::where('beacon_id', 'like', $beacon_id)->update($record);

        $venuepositions = $this->getVenuePositionsForLocation($location);

        return \Response::json(json_encode($venuepositions));
    }

    public function updateVenuePosition() {

        $id = \Input::get('id');
        $name = \Input::get('name');

        // echo "thisPosition id : " . $thisPosition->id;
        $record = array( "name" => $name );

        \Venueposition::where('id', 'like', $id)->update($record);

        return \Response::json(json_encode(1));
    }

    public function deleteVenuePosition() {

        $id = \Input::get('id');
        $location = \Input::get('location');

        $record = array( "venueposition_id" => 0 );
        \Beacon::where('venueposition_id', '=', $id)->update($record);

        \Venueposition::where('id', 'like', $id)->delete();

        $venuepositions = $this->getVenuePositionsForLocation($location);
        // $venuepositions = "{}";

        return \Response::json(json_encode($venuepositions));
    }

    public function getVenuePositionsForLocation($location) {
        $venuepositions = \Venueposition::join('beacons', 'venuepositions.id', '=', 'beacons.venueposition_id')
                                                    ->selectRaw('venuepositions.*, beacons.beacon_id')
                                                    ->where('location', 'like', $location)
                                                    ->get();

        return $venuepositions;
    }

    public function getRmQuickies($brand_id) {

        $brand = \Brand::where('id', '=', $brand_id)->first();

        $quickies = \Quickie::join('quckie_location', 'quickie.id', '=', 'quckie_location.quickie_id')
                ->join('nastype', 'nastype.id', "=", 'quckie_location.nastype_id')
                ->selectRaw('quickie.*')
                ->where('nastype.type', 'like', '%' . $brand->code)
                ->get();

        return \Response::json(json_encode($quickies));

    }

    public function getRmQuickieAnswers($quickie_id) {

        $answers = \Rmresponse::where('quickie_id', '=', $quickie_id)->get();

        return \Response::json(json_encode($answers));

    }


    public function lib_getrmcrossrefcsv_519_520($quickie_id) {
        // $users = \Staff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();

        $file = base_path('public/docs/519_520.csv');
        $output = fopen($file, 'w');
        fputcsv($output, array('dob', 'firstname', 'cellphone', 'email_address', 'home_venue', 'q519', 'q520', 'date', 'Browser', 'OS' ));

        $users = \DB::connection("hipreports")
        ->table("partner")->select(\DB::raw('distinct ispuser'))
        ->where('ispuser', '<>', "")
        ->where('sitename', '=', "HIPBAT001FLOATING01CPTXWCZA")
        ->where('created_at', '<', "2016-10-31 23:59:59")
        ->get();

        $i=0;

        // $remainingusers = array_slice($users, 2250);

        foreach($users as $user) {
            $q519 = $q520 = $date = "";

            // echo($user->ispuser . " === $i <br>");
            // $i++;
            $username = trim(substr($user->ispuser , 3));

            $x519record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 519)->where('ispuser', 'like', $user->ispuser)->first();
            if($x519record) $q519 = $x519record->answer ;
            if($x519record) $date = $x519record->created_at ;

            $x520record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 520)->where('ispuser', 'like', $user->ispuser)->first();
            if($x520record) $q520 = $x520record->answer ;

            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")->where("ispuser", 'like', $user->ispuser)->first();

            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
            $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();

            $csvrecord = array();

            if($openprofilerecord) {
                $csvrecord["dob"] = $openprofilerecord->dob;
            } else {
                $csvrecord["dob"] = "";
            }

            if($secureprofilerecord) {
                $csvrecord["firstname"] = $secureprofilerecord->firstname;
                $csvrecord["cellphone"] = $secureprofilerecord->cellphone;
                $csvrecord["email_address"] = $secureprofilerecord->email_address;
                $csvrecord["home_venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["firstname"] = "";
                $csvrecord["cellphone"] = "";
                $csvrecord["email_address"] = "";
                $csvrecord["home_venue"] = "";
            }

            $csvrecord["q519"] = $q519;
            $csvrecord["q520"] = $q520;
            $csvrecord["date"] = $date;

            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            // echo print_r($csvrecord, true) . "<br>";
            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);

            error_log("lib_getrmcrossrefcsv - done record $i");

            $i++;
        }
    }

    public function lib_getrmcrossrefcsv_530_537($quickie_id) {
        // $users = \Staff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();
        // 530 - 537 x



        $file = base_path('public/docs/massive-' . time() . '.csv');

        $output = fopen($file, 'w');
        fputcsv($output, array('User Name', 'First Name', 'Cell Phone', 'Home Venue', 'Gender', 'Age Range', 'Language', 'City', 'q530', 'q531', 'q532', 'q533', 'q534', 'q535', 'q536', 'q537', 'Browser', 'OS' ));

        $users = \DB::connection("hipreports")
        ->table("partner")->select(\DB::raw('distinct ispuser'))
        ->where('ispuser', '<>', "")
        ->where('sitename', 'like', "%massiv%")
        ->where('created_at', '<', "2017-05-31 23:59:59")
        ->get();

        $i=0;

        $remainingusers = array_slice($users, 2250);

        foreach($users as $user) {
            $q530 = $q531 = $q532 = $q533 = $q534 = $q535 = $q536 = $q537 = "";

            // echo($user->ispuser . " === $i <br>");
            // $i++;
            $username = trim(substr($user->ispuser , 3));

            $x530record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 530)->where('ispuser', 'like', $user->ispuser)->first();
            if($x530record) $q530 = $x530record->answer ;

            $x531record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 531)->where('ispuser', 'like', $user->ispuser)->first();
            if($x531record) $q531 = $x531record->answer ;

            $x532record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 532)->where('ispuser', 'like', $user->ispuser)->first();
            if($x532record) $q532 = $x532record->answer ;

            $x533record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 533)->where('ispuser', 'like', $user->ispuser)->first();
            if($x533record) $q533 = $x533record->answer ;

            $x534record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 534)->where('ispuser', 'like', $user->ispuser)->first();
            if($x534record) $q534 = $x534record->answer ;

            $x535record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 535)->where('ispuser', 'like', $user->ispuser)->first();
            if($x535record) $q535 = $x535record->answer ;

            $x536record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 536)->where('ispuser', 'like', $user->ispuser)->first();
            if($x536record) $q536 = $x536record->answer ;

            $x537record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 537)->where('ispuser', 'like', $user->ispuser)->first();
            if($x537record) $q537 = $x537record->answer ;

            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")->where("ispuser", 'like', $user->ispuser)->first();

            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            if($radcheckrecord) {
                $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
                $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();
            }

            $csvrecord = array();
            $csvrecord["User Name"] = $username;

            if($secureprofilerecord) {
                $csvrecord["First Name"] = $secureprofilerecord->firstname;
                $csvrecord["Cell Phone"] = $secureprofilerecord->cellphone;
                $csvrecord["Home Venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["First Name"] = "";
                $csvrecord["Cell Phone"] = "";
                $csvrecord["Home Venue"] = "";
            }

            if($partnerprofilerecord) {
                $csvrecord["Gender"] = $partnerprofilerecord->q89;
                $csvrecord["Age Range"] = $partnerprofilerecord->q90;
                $csvrecord["Language"] = $partnerprofilerecord->q140;
                $csvrecord["City"] = $partnerprofilerecord->q141;
            } else {
                $csvrecord["Gender"] = "";
                $csvrecord["Age Range"] = "";
                $csvrecord["Language"] = "";
                $csvrecord["City"] = "";
            }

            $csvrecord["q530"] = $q530;
            $csvrecord["q531"] = $q531;
            $csvrecord["q532"] = $q532;
            $csvrecord["q533"] = $q533;
            $csvrecord["q534"] = $q534;
            $csvrecord["q535"] = $q535;
            $csvrecord["q536"] = $q536;
            $csvrecord["q537"] = $q537;

            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            // echo print_r($csvrecord, true) . "<br>";
            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);

            error_log("lib_getrmcrossrefcsv - done record $i");

            // if($i>3) dd();
            $i++;

            // dd();
        }

        error_log("lib_getrmcrossrefcsv : finished");

        // $headers = array(
        // 'Content-Type' => 'text/csv',
        // 'Content-Disposition' => 'attachment; filename="data.csv"',
        // );
        // return Response::download($file, $filename.'.csv', $headers);
    }


    public function lib_getrmcrossrefcsv_hp() {

        $file = base_path('public/docs/hp-' . time() . '.csv');

        $output = fopen($file, 'w');
        fputcsv($output, array('User Name', 'First Name', 'Cell Phone', 'Home Venue', 'Gender', 'Age Range', 'Language', 'City', 'Browser', 'OS' ));

        $users = \DB::connection("hipreports")
        ->table("partner")->select(\DB::raw('distinct ispuser'))
        ->where('ispuser', '<>', "")
        ->where('sitename', 'like', "%HIPHPXXXX%")
        ->where('created_at', '>', "2017-10-01 00:00:00")
        ->where('created_at', '<', "2017-10-31 23:59:59")
        ->get();

        $i=0;

        $remainingusers = array_slice($users, 2250);

        foreach($users as $user) {

            $username = trim(substr($user->ispuser , 3));

            // Get macaddress
            $x = \DB::connection("hipreports")->table("partner")->where('ispuser', 'like', $user->ispuser)->first();
            $macaddress = $x->macaddress;

            // Lookup partner_profile based on macaddress
            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")
                                    ->where("macaddress", 'like', $macaddress)
                                    ->where("q89", '<>', "")
                                    ->first();
            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            if($radcheckrecord) {
                $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
                $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();
            }

            $csvrecord = array();
            $csvrecord["User Name"] = $username;

            if($secureprofilerecord) {
                $csvrecord["First Name"] = $secureprofilerecord->firstname;
                $csvrecord["Cell Phone"] = $secureprofilerecord->cellphone;
                $csvrecord["Home Venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["First Name"] = "";
                $csvrecord["Cell Phone"] = "";
                $csvrecord["Home Venue"] = "";
            }

            if($partnerprofilerecord) {
                $csvrecord["Gender"] = $partnerprofilerecord->q89;
                $csvrecord["Age Range"] = $partnerprofilerecord->q90;
                $csvrecord["Language"] = $partnerprofilerecord->q140;
                $csvrecord["City"] = $partnerprofilerecord->q141;
            } else {
                $csvrecord["Gender"] = "";
                $csvrecord["Age Range"] = "";
                $csvrecord["Language"] = "";
                $csvrecord["City"] = "";
            }

            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);

            error_log("lib_getrmcrossrefcsv - done record $i");

            $i++;

        }

        error_log("lib_getrmcrossrefcsv : finished");

    }

    public function lib_getrmcrossrefcsv_581_582($quickie_id) {

        $file = base_path('public/docs/clicks2-' . time() . '.csv');

        $output = fopen($file, 'w');
        fputcsv($output, array('User Name', 'First Name', 'Cell Phone', 'Home Venue', 'Gender', 'Age Range', 'Language', 'City', 'Income', 'q581', 'q582', 'Browser', 'OS' ));

        $users = \DB::connection("hipreports")
        ->table("partner")->select(\DB::raw('distinct ispuser'))
        ->where('ispuser', '<>', "")
        ->where('sitename', 'like', "%HIPCLICK2%")
        ->where('created_at', '>', "2017-08-31 23:59:59")
        ->get();
        // ->where('sitename', 'like', "%HIPKAUAI%")
        // ->where('created_at', '<', "2017-0-31 23:59:59")

        $i=0;

        $remainingusers = array_slice($users, 2250);

        foreach($users as $user) {
            $q581 = $q582 = "";

            $username = trim(substr($user->ispuser , 3));

            $x581record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 581)->where('ispuser', 'like', $user->ispuser)->first();
            if($x581record) $q581 = $x581record->answer ;

            $x582record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 582)->where('ispuser', 'like', $user->ispuser)->first();
            if($x582record) $q582 = $x582record->answer ;

            // Get macaddress
            $x = \DB::connection("hipreports")->table("partner")->where('ispuser', 'like', $user->ispuser)->first();
            $macaddress = $x->macaddress;

            // Lookup partner_profile based on macaddress
            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")
                                    ->where("macaddress", 'like', $macaddress)
                                    ->where("q89", '<>', "")
                                    ->first();
            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            if($radcheckrecord) {
                $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
                $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();
            }

            $csvrecord = array();
            $csvrecord["User Name"] = $username;

            if($secureprofilerecord) {
                $csvrecord["First Name"] = $secureprofilerecord->firstname;
                $csvrecord["Cell Phone"] = $secureprofilerecord->cellphone;
                $csvrecord["Home Venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["First Name"] = "";
                $csvrecord["Cell Phone"] = "";
                $csvrecord["Home Venue"] = "";
            }

            if($partnerprofilerecord) {
                $csvrecord["Gender"] = $partnerprofilerecord->q89;
                $csvrecord["Age Range"] = $partnerprofilerecord->q90;
                $csvrecord["Language"] = $partnerprofilerecord->q140;
                $csvrecord["City"] = $partnerprofilerecord->q141;
                $csvrecord["Income"] = $partnerprofilerecord->q143;
            } else {
                $csvrecord["Gender"] = "";
                $csvrecord["Age Range"] = "";
                $csvrecord["Language"] = "";
                $csvrecord["City"] = "";
                $csvrecord["Income"] = "";
            }

            $csvrecord["q581"] = $q581;
            $csvrecord["q582"] = $q582;


            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);

            error_log("lib_getrmcrossrefcsv - done record $i");

            $i++;

        }

        error_log("lib_getrmcrossrefcsv : finished");

        // $headers = array(
        // 'Content-Type' => 'text/csv',
        // 'Content-Disposition' => 'attachment; filename="data.csv"',
        // );
        // return Response::download($file, $filename.'.csv', $headers);
    }



    public function lib_getrmcrossrefcsv($quickie_id) {
        // $users = \Staff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();
        // 530 - 537 x



        $file = base_path('public/docs/frontline-' . time() . '.csv');

        $output = fopen($file, 'w');
        fputcsv($output, array('User Name', 'First Name', 'Cell Phone', 'Home Venue', 'Gender', 'Age Range', 'Language', 'City', 'q148', 'q580', 'Browser', 'OS' ));

        $users = \DB::connection("hipreports")
        ->table("partner")->select(\DB::raw('distinct ispuser'))
        ->where('ispuser', '<>', "")
        ->where('sitename', 'like', "%HIPFRTLNE%")
        ->where('created_at', '<', "2017-05-31 23:59:59")
        ->get();

        $i=0;

        $remainingusers = array_slice($users, 2250);

        foreach($users as $user) {
            $q148 = $q580 = $q532 = "";

            // echo($user->ispuser . " === $i <br>");
            // $i++;
            $username = trim(substr($user->ispuser , 3));

            $x148record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 148)->where('ispuser', 'like', $user->ispuser)->first();
            if($x148record) $q148 = $x148record->answer ;

            $x580record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 580)->where('ispuser', 'like', $user->ispuser)->first();
            if($x580record) $q580 = $x580record->answer ;


            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")->where("ispuser", 'like', $user->ispuser)->first();

            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            if($radcheckrecord) {
                $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
                $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();
            }

            $csvrecord = array();
            $csvrecord["User Name"] = $username;

            if($secureprofilerecord) {
                $csvrecord["First Name"] = $secureprofilerecord->firstname;
                $csvrecord["Cell Phone"] = $secureprofilerecord->cellphone;
                $csvrecord["Home Venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["First Name"] = "";
                $csvrecord["Cell Phone"] = "";
                $csvrecord["Home Venue"] = "";
            }

            if($partnerprofilerecord) {
                $csvrecord["Gender"] = $partnerprofilerecord->q89;
                $csvrecord["Age Range"] = $partnerprofilerecord->q90;
                $csvrecord["Language"] = $partnerprofilerecord->q140;
                $csvrecord["City"] = $partnerprofilerecord->q141;
            } else {
                $csvrecord["Gender"] = "";
                $csvrecord["Age Range"] = "";
                $csvrecord["Language"] = "";
                $csvrecord["City"] = "";
            }

            $csvrecord["q148"] = $q148;
            $csvrecord["q580"] = $q580;


            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            // echo print_r($csvrecord, true) . "<br>";
            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);

            error_log("lib_getrmcrossrefcsv - done record $i");

            // if($i>3) dd();
            $i++;

            // dd();
        }

        error_log("lib_getrmcrossrefcsv : finished");

        // $headers = array(
        // 'Content-Type' => 'text/csv',
        // 'Content-Disposition' => 'attachment; filename="data.csv"',
        // );
        // return Response::download($file, $filename.'.csv', $headers);
    }

    public function lib_getrmcrossrefcsv_521_529($quickie_id) {
        // $users = \Staff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();

        $file = base_path('public/docs/massive.csv');
        $output = fopen($file, 'w');
        fputcsv($output, array('User Name', 'First Name', 'Cell Phone', 'Home Venue', 'Gender', 'Age Range', 'Language', 'City', 'q521', 'q522', 'q523', 'q524', 'q526', 'q527', 'q528', 'q529', 'Browser', 'OS' ));

        $users = \DB::connection("hipreports")
        ->table("partner")->select(\DB::raw('distinct ispuser'))
        ->where('ispuser', '<>', "")
        ->where('sitename', 'like', "%sanlam%")
        ->where('created_at', '<', "2016-10-31 23:59:59")
        ->get();

        $i=0;

        $remainingusers = array_slice($users, 2250);

        foreach($remainingusers as $user) {
            $q521 = $q522 = $q523 = $q524 = $q526 = $q527 = $q528 = $q529 = "";

            // echo($user->ispuser . " === $i <br>");
            // $i++;
            $username = trim(substr($user->ispuser , 3));

            $x521record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 521)->where('ispuser', 'like', $user->ispuser)->first();
            if($x521record) $q521 = $x521record->answer ;

            $x522record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 522)->where('ispuser', 'like', $user->ispuser)->first();
            if($x522record) $q522 = $x522record->answer ;

            $x523record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 523)->where('ispuser', 'like', $user->ispuser)->first();
            if($x523record) $q523 = $x523record->answer ;

            $x524record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 524)->where('ispuser', 'like', $user->ispuser)->first();
            if($x524record) $q524 = $x524record->answer ;

            $x526record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 526)->where('ispuser', 'like', $user->ispuser)->first();
            if($x526record) $q526 = $x526record->answer ;

            $x527record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 527)->where('ispuser', 'like', $user->ispuser)->first();
            if($x527record) $q527 = $x527record->answer ;

            $x528record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 528)->where('ispuser', 'like', $user->ispuser)->first();
            if($x528record) $q528 = $x528record->answer ;

            $x529record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 529)->where('ispuser', 'like', $user->ispuser)->first();
            if($x529record) $q529 = $x529record->answer ;

            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")->where("ispuser", 'like', $user->ispuser)->first();

            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
            $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();

            $csvrecord = array();
            $csvrecord["User Name"] = $username;

            if($secureprofilerecord) {
                $csvrecord["First Name"] = $secureprofilerecord->firstname;
                $csvrecord["Cell Phone"] = $secureprofilerecord->cellphone;
                $csvrecord["Home Venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["First Name"] = "";
                $csvrecord["Cell Phone"] = "";
                $csvrecord["Home Venue"] = "";
            }

            if($partnerprofilerecord) {
                $csvrecord["Gender"] = $partnerprofilerecord->q89;
                $csvrecord["Age Range"] = $partnerprofilerecord->q90;
                $csvrecord["Language"] = $partnerprofilerecord->q140;
                $csvrecord["City"] = $partnerprofilerecord->q141;
            } else {
                $csvrecord["Gender"] = "";
                $csvrecord["Age Range"] = "";
                $csvrecord["Language"] = "";
                $csvrecord["City"] = "";
            }

            $csvrecord["q521"] = $q521;
            $csvrecord["q522"] = $q522;
            $csvrecord["q523"] = $q523;
            $csvrecord["q524"] = $q524;
            $csvrecord["q526"] = $q526;
            $csvrecord["q527"] = $q527;
            $csvrecord["q528"] = $q528;
            $csvrecord["q529"] = $q529;

            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            // echo print_r($csvrecord, true) . "<br>";
            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);

            error_log("lib_getrmcrossrefcsv - done record $i");

            // if($i>3) dd();
            $i++;

            // dd();
        }

        error_log("lib_getrmcrossrefcsv : finished");

        // $headers = array(
        // 'Content-Type' => 'text/csv',
        // 'Content-Disposition' => 'attachment; filename="data.csv"',
        // );
        // return Response::download($file, $filename.'.csv', $headers);
    }





    public function lib_getrmcrossrefcsv_old($quickie_id) {

        $x521records = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 521)->get();
        $q521 = $q522 = $q523 = $q524 = "";

        $file = base_path('public/docs/massive.csv');
        $output = fopen($file, 'w');
        fputcsv($output, array('User Name', 'q521', 'q522', 'q523', 'q524', 'Gender', 'Age Range', 'Language', 'City', 'First Name', 'Cell Phone', 'Home Venue', 'Browser', 'OS' ));


        foreach($x521records as $x521record) {
            // $username = preg_replace("/(^.{3}) (.*$)/", "$2", $answer->ispuser);
            $username = trim(substr($x521record->ispuser , 3));
            $q521 = $x521record->answer;

            $x522record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 522)->where('ispuser', 'like', $x521record->ispuser)->first();
            if($x522record) $q522 = $x522record->answer ;

            $x523record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 523)->where('ispuser', 'like', $x521record->ispuser)->first();
            if($x523record) $q523 = $x523record->answer ;

            $x524record = \DB::connection("hipreports")->table("partner")->where('quickie_id', '=', 524)->where('ispuser', 'like', $x521record->ispuser)->first();
            if($x524record) $q524 = $x524record->answer ;

            $partnerprofilerecord = \DB::connection("hipreports") ->table("partner_profile")->where("ispuser", 'like', $x521record->ispuser)->first();
            $radcheckrecord = \DB::connection("tmpspot") ->table("radcheck") ->where("username", 'like', $username)->first();
            $secureprofilerecord = \DB::connection("tmpspot")->table("secure_profile") ->where("user_id", '=', $radcheckrecord->id)->first();
            $openprofilerecord = \DB::connection("tmpspot")->table("open_profiles") ->where("user_id", '=', $radcheckrecord->id)->first();

            $csvrecord = array();
            $csvrecord["User Name"] = $username;
            $csvrecord["q521"] = $q521;
            $csvrecord["q522"] = $q522;
            $csvrecord["q523"] = $q523;
            $csvrecord["q524"] = $q524;

            if($partnerprofilerecord) {
                $csvrecord["Gender"] = $partnerprofilerecord->q89;
                $csvrecord["Age Range"] = $partnerprofilerecord->q90;
                $csvrecord["Language"] = $partnerprofilerecord->q140;
                $csvrecord["City"] = $partnerprofilerecord->q141;
            } else {
                $csvrecord["Gender"] = "";
                $csvrecord["Age Range"] = "";
                $csvrecord["Language"] = "";
                $csvrecord["City"] = "";
            }

            if($secureprofilerecord) {
                $csvrecord["First Name"] = $secureprofilerecord->firstname;
                $csvrecord["Cell Phone"] = $secureprofilerecord->cellphone;
                $csvrecord["Home Venue"] = $secureprofilerecord->home_venue;
            } else {
                $csvrecord["First Name"] = "";
                $csvrecord["Cell Phone"] = "";
                $csvrecord["Home Venue"] = "";
            }

            if($openprofilerecord) {
                $csvrecord["Browser"] = $openprofilerecord->browser;
                $csvrecord["OS"] = $openprofilerecord->os;
            } else {
                $csvrecord["Browser"] = "";
                $csvrecord["OS"] = "";
            }

            // echo print_r($csvrecord, true) . "<br>";
            $rowdata = array_values($csvrecord); // append each row
            fputcsv($output, $rowdata);
        }

        $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="data.csv"',
        );
        return Response::download($file, $filename.'.csv', $headers);


    }

    public function dashboard_details1() {
        /* this is the first of two functions that display the data shown on the hipwifi dashboard page */
        $brand = new \Brand();
        $brands = $brand->getBrandsForUser(\Auth::user()->id);
        $onlineUsers = array();
        $usersPerBrand = array();
        $usersLM = array();
        $usersTM = array();
        $newUsersLM = array();
        $newUsersTM = array();



        foreach ($brands as $brand) {
            $connection = \DB::table('remotedbs')->where('id', "=", $brand->remotedb_id)->pluck('dbconnection');
            $onlineUsersCount = \DB::connection($connection)->table('radacct')->where('calledstationid', 'like', '%'.$brand->code.'%')->where('AcctStopTime', '=', '0000-00-00 00:00:00')->count();
            $userCount = 0;
            try {
                $userCount = \DB::connection($connection)->table('secure_profile')->where('home_venue', 'like', '%'.$brand->name.'%')->count();
            } catch (\Illuminate\Database\QueryException $ex) {
                $userCount = 0;
            }

            $usersPerBrandLM = \DB::table('replastmonth')->where('brandcode', '=', $brand->code)->sum('currentunique');
            $usersPerBrandTM = \DB::table('repthismonth')->where('brandcode', '=', $brand->code)->sum('currentunique');
            $newUsersPerBrandLM = \DB::table('replastmonth')->where('brandcode', '=', $brand->code)->sum('currentnewusers');
            $newUsersPerBrandTM = \DB::table('repthismonth')->where('brandcode', '=', $brand->code)->sum('currentnewusers');
            array_push($onlineUsers, $onlineUsersCount);
            array_push($usersPerBrand, $userCount);
            array_push($usersLM, $usersPerBrandLM);
            array_push($usersTM, $usersPerBrandTM);
            array_push($newUsersLM, $newUsersPerBrandLM);
            array_push($newUsersTM, $newUsersPerBrandTM);
        }

        $totalUsersOnline = array_sum($onlineUsers);
        $totalNoOfUsers = array_sum($usersPerBrand);
        $totalUsersLM = array_sum($usersLM);
        $totalUsersTM = array_sum($usersTM);
        $totalNewUsersLM = array_sum($newUsersLM);
        $totalNewUsersTM = array_sum($newUsersTM);

        $details = array();
        $details["users"] = $totalNoOfUsers;
        $details["onlineusers"] = $totalUsersOnline;
        $details["userslastmonth"] = $totalUsersLM;
        $details["usersthismonth"] = $totalUsersTM;
        $details["newuserslastmonth"] = $totalNewUsersLM;
        $details["newusersthismonth"] = $totalNewUsersTM;

        return $details;

    }

    public function dashboard_details2() {
        /* this is the second of two functions that display the data shown on the hipwifi dashboard page */

        $brand = new \Brand();
        $brands = $brand->getBrandsForUser(\Auth::user()->id);
        $sessionsLM = array();
        $sessionsTM = array();
        $sessionsPerBrandTM = array();
        $avgDwellTimeLM = array();
        $avgDwellTimeTM = array();
        $lmData = array();
        $tmData = array();


        $noOfBrandsPerUser = 0;
        foreach ($brands as $brand) {
            $noOfBrandsPerUser++;
            $sessionsPerBrandLM = \DB::table('replastmonth')->where('brandcode', '=', $brand->code)->sum('currentsessions');
            $sessionsPerBrandTM = \DB::table('repthismonth')->where('brandcode', '=', $brand->code)->sum('currentsessions');

            $lmAvgDwellTime = \DB::table('replastmonth')->where('brandcode', '=', $brand->code)->avg('currentminutes');
            $tmAvgDwellTime = \DB::table('repthismonth')->where('brandcode', '=', $brand->code)->avg('currentminutes');
            $lmDataPerBrand = \DB::table('replastmonth')->where('brandcode', '=', $brand->code)->sum('currentdata');
            $tmDataPerBrand = \DB::table('repthismonth')->where('brandcode', '=', $brand->code)->sum('currentdata');


            array_push($sessionsLM, $sessionsPerBrandLM);
            array_push($sessionsTM, $sessionsPerBrandTM);
            array_push($avgDwellTimeLM, $lmAvgDwellTime);
            array_push($avgDwellTimeTM, $tmAvgDwellTime);
            array_push($lmData, $lmDataPerBrand);
            array_push($tmData, $tmDataPerBrand);
        };

        $totalSessionsLM = array_sum($sessionsLM);
        $totalSessionsTM = array_sum($sessionsTM);
        $totalAvgDwellTimeLM = array_sum($avgDwellTimeLM);
        $totalAvgDwellTimeTM = array_sum($avgDwellTimeTM);
        $totalDataLM = array_sum($lmData);
        $totalDataLMGB = round($totalDataLM/1024);
        $totalDataTM = array_sum($tmData);
        $totalDataTMGB = round($totalDataTM/1024);

        $avgDwellTimeLM_final = round($totalAvgDwellTimeLM/$noOfBrandsPerUser);
        $avgDwellTimeTM_final = round($totalAvgDwellTimeTM/$noOfBrandsPerUser);


        $details = array();

        $details["sessionslastmonth"] = $totalSessionsLM;
        $details["sessionsthismonth"] = $totalSessionsTM;
        $details["avgdwelltimelastmonth"] = $avgDwellTimeLM_final;
        $details["avgdwelltimethismonth"] = $avgDwellTimeTM_final;
        $details["lastmonthdata"] = $totalDataLMGB;
        $details["thismonthdata"] = $totalDataTMGB;
        $details["length"] = $noOfBrandsPerUser;



        return $details;

    }


}