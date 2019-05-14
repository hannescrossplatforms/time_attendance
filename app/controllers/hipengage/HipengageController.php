<?php

namespace hipengage;
use Session;

// use BaseController;

class HipengageController extends \BaseController {
    public function showDashboard()
    {

        $data = array();
        $data['currentMenuItem'] = "Dashboard";

        return \View::make('hipengage.showdashboard')->with('data', $data);
    }

    public function showCampaigns()
    {
        $data = array();

        $data['campaigns'] = "";
        $data['currentMenuItem'] = "Campaign Management";

        return \View::make('hipengage.showcampaigns')->with('data', $data);
    }

    public function addCampaign()
    {
        error_log('addCampaign');
        $data = array();
        $data['currentMenuItem'] = "Campaign Management";
        $data['edit'] = false;
        $data['campaign'] = false;

        return \View::make('hipengage.addcampaign')->with('data', $data);
    }

    public function addCampaignSave()
    {

        return \Redirect::route('hipengage_showcampaigns');
    }

    public function editCampaign($id)
    {
        error_log('editCampaign');
        $data = array();
        $data['campaign'] = new \Campaign();

        $data['currentMenuItem'] = "Campaign Management";
        $data['edit'] = true;

        return \View::make('hipengage.addcampaign')->with('data', $data);
    }

    public function editCampaignSave()
    {

        return \Redirect::route('hipengage_showcampaigns');
    }

    public function showResults()
    {
        $data = array();

        $data['results'] = "";
        $data['currentMenuItem'] = "Campaign Results";

        return \View::make('hipengage.showresults')->with('data', $data);
    }

 

    public function showEvents($json = null)
    {
        // Set the currentInstance to null. This is used to determine which engage database to use.
        Session::put('currentInstance', null);

        error_log("showEvents :  json = $json");
        $data = array();

        $data['events'] = "";
        $data['currentMenuItem'] = "Event Manager";

        $utils = new \Utils();
        $allowedbrandcodes = $utils->getAllowedEngageBrandcodes();
        $events = \Hipevent::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        $events =  $this->addBrandnameToObjectArray($events);

        $today  = date("Y-m-d");

        foreach($events as $event) {

            // Get the active eventschedule record if exists
            $eventschedule = \Eventschedule::where('hipevent_id', '=', $event->id) ->first();
            if($eventschedule) {
                $event['schedulebegin'] = $eventschedule->begin; 
                $event['scheduleend'] = ' - ' . $eventschedule->end;  
            } else {
                $event['schedulebegin'] = "No schedule configured"; 
                $event['scheduleend'] = "";    
            }

            $eventstatus = \Eventschedule::where('hipevent_id', '=', $event->id)
                                ->where('begin', "<=", $today)
                                ->where('end', ">=", $today)
                                ->first();

            $activeHtml = "<span style='color:green'>Active</span>";
            $inactiveHtml = "<span style='color:red'>Inactive</span>";
            // Check the day of the week
            if($eventstatus) {
                $event['status'] = $activeHtml;
                // $dayofweek = date('l', strtotime($today));
                // if ($dayofweek == "Monday" && !$event->monday) $event['status'] = $inactiveHtml;
                // if ($dayofweek == "Tuesday" && !$event->tuesday) $event['status'] = $inactiveHtml;
                // if ($dayofweek == "Wednesday" && !$event->wednesday) $event['status'] = $inactiveHtml;
                // if ($dayofweek == "Thursday" && !$event->thursday) $event['status'] = $inactiveHtml;
                // if ($dayofweek == "Friday" && !$event->friday) $event['status'] = $inactiveHtml;
                // if ($dayofweek == "Saturday" && !$event->saturday) $event['status'] = $inactiveHtml;
                // if ($dayofweek == "Sunday" && !$event->sunday) $event['status'] = $inactiveHtml;
            } else {
                $event['status'] = $inactiveHtml;
            }

            $daysofweek = "";
            if($event->monday) $daysofweek = "Mon ";
            if($event->tuesday) $daysofweek = $daysofweek . "Tue ";
            if($event->wednesday) $daysofweek = $daysofweek . "Wed ";
            if($event->thursday) $daysofweek = $daysofweek . "Thu ";
            if($event->friday) $daysofweek = $daysofweek . "Fri ";
            if($event->saturday) $daysofweek = $daysofweek . "Sat ";
            if($event->sunday) $daysofweek = $daysofweek . "Sun ";

            $event['daysofweek'] = $daysofweek;
        }
        // error_log("showEvents :  events = " . print_r($events, true));

        $data['eventsStruct'] = $events;
        $eventsJason = json_encode($events);
        $data['eventsJason'] = $eventsJason;

        $data['events'] = $events;

        if($json) {
            error_log("showEvents : returning json" );
            return \Response::json($eventsJason);

        } else {
            return \View::make('hipengage.showevents')->with('data', $data);
            
        }

    }

    public function addEvent()
    {
        error_log('addEvent');
        $data = array();
        $data['currentMenuItem'] = "Event Manager";
        $data['edit'] = 0;
        $data["eventjson"] = 0;
        $data["criteriajson"] = 0;


        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['previewurl'] = $assetsserver->value . 'hipengage/images/';

        $data['allcountries'] = \Countrie::All();

        $event = new \Hipevent();
        $data["event"] = $event;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForEngage();
        $data['eventArray'] = 0;

        $eventschedules = array();
        $data["eventschedulesjson"] = json_encode($eventschedules);

        $eventtimes = array();
        $data["eventtimesjson"] = json_encode($eventtimes);

        $data["isvenuelevel"] = 0;
        $data["venuepositions"] = "{}";

        $data["rmanswers"] = 0;
        $data["rmquestion"] = 0;
        $data["quickie_id"] = 0;    

        return \View::make('hipengage.addevent')->with('data', $data);
    }

    public function editEvent($id)
    {
        error_log('editEvent');

        $data = array();
        $data['event'] = new \Hipevent();

        $data['currentMenuItem'] = "Event Manager";
        $data['edit'] = 1;

        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['previewurl'] = $assetsserver->value . 'hipengage/images/';

        $event = \Hipevent::find($id);

        $brand = \Engagebrand::where("code", "like", $event->engagebrand_code)->first();
        $event->brand = $brand;

        $criterias = \Hipcriteria::where("hipevent_id", "=", $id)->get();
        foreach($criterias as $criteria) {
            // error_log("measure_name " . $criteria->measure->name);
            $criteria["measure_name"] = $criteria->measure->name;
        }
        $data["criteriajson"] = json_encode($criterias);

        $eventschedules = \Eventschedule::where("hipevent_id", "=", $id)->first();
        $data["eventschedulesjson"] = json_encode($eventschedules);

        $eventtimes = \Eventtime::where("hipevent_id", "=", $id)->get();
        $data["eventtimesjson"] = json_encode($eventtimes);

        $data["locationmatchcode"] = $locationmatchcode = $event->locationmatchcode;

        if(preg_match('/^.........XXXXXXXXXX........$/', $locationmatchcode)) {
            $data["isvenuelevel"] = 0;
        } else {
            $data["isvenuelevel"] = 1;
        }

        $data["venuepositions"] = json_encode($event->venuepositions);
        // error_log("venuepositions : " . print_r($data["venuepositions"], true));

        $data["event"] = $event;

        $data["trigger_name"] = $event->trigger->name;

        $rmquestion = \Quickie::where('id', '=', $event->quickie_id)->first(); 
        if($rmquestion) {
            $data["rmquestion"] = $rmquestion->question;
            $data["quickie_id"] = $event->quickie_id;

            $rmanswers = \Hipcriteria::where('hipevent_id', '=', $id)->get();
            // foreach($rmanswers as $a) { error_log("editEventeditEventeditEventeditEvent " . $a->value); }
            $data["rmanswers"] = json_encode($rmanswers);

        } else {
            $data["rmquestion"] = "No question - not RM";
            $data["rmanswers"] = 0;
            $data["quickie_id"] = 0;            
        }  

        $data["eventjson"] = json_encode($data["event"]);

        return \View::make('hipengage.addevent')->with('data', $data);
    }

    public function getPositionWildcards() {

        error_log("positions : " . print_r(\Input::get('venueposition_ids'), true));
        $position_wildcards = \Input::get('position_wildcards');
        $venueposition_ids = \Input::get('venueposition_ids');

        if($venueposition_ids) {
            $position_wildcards = "";
            foreach($venueposition_ids as $venueposition_id) {
                // Get the position name
                $positionName = \Venueposition::where('id', 'like', $venueposition_id)->first()->name;
                $position_wildcards = "$position_wildcards $positionName";
            }
            if($position_wildcards == "") $position_wildcards = "*";
        }   
        error_log("addEventSave position_wildcards = $position_wildcards");

        return  $position_wildcards;    
    }

    public function savePositionIds($hipeventObj) {
        $hipeventObj->venuepositions()->detach();
        $venueposition_ids = \Input::get('venueposition_ids');
        if($venueposition_ids) {
            foreach($venueposition_ids as $venueposition_id) {
                $hipeventObj->venuepositions()->attach($venueposition_id); 
            }
            $hipeventObj->save();
        } 
    }

    public function addEventSave()
    {
        error_log("addEventSave : 10");
        error_log("name : " . \Input::get('name'));

        $input = \Input::all();

        $data = \Request::getContent();
        $json = json_decode($data);
        $brand_id = \Input::get('brand_id');
        $engagebrand_code = \Brand::find($brand_id)->code;

        $criterias = \Input::get('criterias');
        error_log("application_code : " . \Input::get('application_code'));
        error_log("trigger_code : " . \Input::get('trigger_code'));

        $hipeventObj = new \Hipevent();
        $hipeventObj->name = \Input::get('name');
        $hipeventObj->locationmatchcode = \Input::get('locationmatchcode');
        $hipeventObj->engagebrand_code = $engagebrand_code;
        $hipeventObj->application_code = \Input::get('application_code');
        $hipeventObj->trigger_code = \Input::get('trigger_code');
        $hipeventObj->position_wildcards = $this->getPositionWildcards();
        $hipeventObj->pushnotification_id = \Input::get('pushnotification_id');
        $hipeventObj->apinotification_id = \Input::get('apinotification_id');
        $hipeventObj->smsnotification_id = \Input::get('smsnotification_id');
        $hipeventObj->emailnotification_id = 0; // \Input::get('emailnotification_id');
        $hipeventObj->notification_type_primary = \Input::get('notification_type_primary');
        $hipeventObj->logicaloperator = \Input::get('logicaloperator');

        if(\Input::get('quickie_id')) { $hipeventObj->quickie_id = \Input::get('quickie_id'); } else { $hipeventObj->quickie_id = 0; } 

        $hipeventObj->monday = \Input::get('monday');
        $hipeventObj->tuesday = \Input::get('tuesday');
        $hipeventObj->wednesday = \Input::get('wednesday');
        $hipeventObj->thursday = \Input::get('thursday');
        $hipeventObj->friday = \Input::get('friday');
        $hipeventObj->saturday = \Input::get('saturday');
        $hipeventObj->sunday = \Input::get('sunday');

        $hipeventObj->frequency_count = \Input::get('frequency_count');
        $hipeventObj->frequency_interval = \Input::get('frequency_interval');
        $hipeventObj->frequency_count1 = \Input::get('frequency_count1');
        $hipeventObj->frequency_interval1 = \Input::get('frequency_interval1');
        $hipeventObj->frequency_count2 = \Input::get('frequency_count2');
        $hipeventObj->frequency_interval2 = \Input::get('frequency_interval2');
        $hipeventObj->delay = \Input::get('delay');
        $hipeventObj->delay_interval = \Input::get('delay_interval');

        $hipeventObj->signalstrength = \Input::get('signalstrength');

        $hipeventObj->save();

        $this->savePositionIds($hipeventObj);

        foreach($criterias as $criteria) {

// echo "criteria : " . print_r($criteria, true);
            $criteriaObj = new \Hipcriteria();
            $criteriaObj->hipevent_id = $hipeventObj->id;
            $criteriaObj->trigger_code = $hipeventObj->trigger_code;
            $criteriaObj->measure_code = $criteria["measure_code"];
            $criteriaObj->operator = $criteria["operator"];
            $criteriaObj->value = $criteria["value"];
            $criteriaObj->period = $criteria["period"];
            $criteriaObj->numperiods = $criteria["numperiods"];
            $criteriaObj->save();
        }

        $schedulebegin = \Input::get('schedulebegin');
        $scheduleend = \Input::get('scheduleend');
        \Eventschedule::where("hipevent_id", "=", $hipeventObj->id)->delete();
        error_log("editEventSave schedulebegin = " . $schedulebegin);
        $eventscheduleObj = new \Eventschedule();
        $eventscheduleObj->hipevent_id = $hipeventObj->id;
        $eventscheduleObj->begin = $schedulebegin;
        $eventscheduleObj->end = $scheduleend;
        $eventscheduleObj->save();

        $eventtimes = \Input::get('eventtimes');
        \Eventtime::where("hipevent_id", "=", $hipeventObj->id)->delete();
        error_log("eventtimes : " . print_r($eventtimes, true));

        foreach($eventtimes as $eventtime) {

            if($eventtime) {

                error_log("timefrom : " . $eventtime["timefrom"]);
                $eventtimeObj = new \Eventtime();
                $eventtimeObj->hipevent_id = $hipeventObj->id;
                $eventtimeObj->timefrom = $eventtime["timefrom"];
                $eventtimeObj->timeto = $eventtime["timeto"];
 
                $eventtimeObj->save();

            }
        }

        return 1;
    }

    public function editEventSave()
    {
        $event_id = \Input::get('event_id');
        $event = \Hipevent::find($event_id);
        $event->smsnotification_id = \Input::get('smsnotification_id');
        $event->emailnotification_id = 0 ; //\Input::get('emailnotification_id');
        $event->pushnotification_id = \Input::get('pushnotification_id');
        $event->apinotification_id = \Input::get('apinotification_id');
        $event->emailnotification_id = \Input::get('emailnotification_id');
        $event->mgrnotification_id = \Input::get('mgrnotification_id');
        $event->notification_type_primary = \Input::get('notification_type_primary');
        $event->logicaloperator = \Input::get('logicaloperator');
        $event->position_wildcards = $this->getPositionWildcards();


        $event->monday = \Input::get('monday');
        $event->tuesday = \Input::get('tuesday');
        $event->wednesday = \Input::get('wednesday');
        $event->thursday = \Input::get('thursday');
        $event->friday = \Input::get('friday');
        $event->saturday = \Input::get('saturday');
        $event->sunday = \Input::get('sunday');

        $event->frequency_count = \Input::get('frequency_count');
        $event->frequency_interval = \Input::get('frequency_interval');
        $event->frequency_count1 = \Input::get('frequency_count1');
        $event->frequency_interval1 = \Input::get('frequency_interval1');
        $event->frequency_count2 = \Input::get('frequency_count2');
        $event->frequency_interval2 = \Input::get('frequency_interval2');
        $event->delay = \Input::get('delay');
        $event->delay_interval = \Input::get('delay_interval');

        $event->signalstrength = \Input::get('signalstrength');

        $event->save();

        // $event->venuepositions()->detach();
        // $venueposition_ids = \Input::get('venueposition_ids');

        // if($venueposition_ids) {
        //     foreach($venueposition_ids as $venueposition_id) {
        //         $event->venuepositions()->attach($venueposition_id);
        //     }
        // }
        $this->savePositionIds($event);


        $event->save();

        $criterias = \Input::get('criterias');
        \Hipcriteria::where("hipevent_id", "=", $event_id)->delete();

        foreach($criterias as $criteria) {

            if($criteria) {

                $criteriaObj = new \Hipcriteria();
                $criteriaObj->hipevent_id = $event_id;
                $criteriaObj->trigger_code = $event->trigger_code;
                $criteriaObj->measure_code = $criteria["measure_code"];
                $criteriaObj->operator = $criteria["operator"];
                $criteriaObj->value = $criteria["value"];
                $criteriaObj->period = $criteria["period"];
                $criteriaObj->numperiods = $criteria["numperiods"];
                $criteriaObj->save();

            }
        }

        $schedulebegin = \Input::get('schedulebegin');
        $scheduleend = \Input::get('scheduleend');
        \Eventschedule::where("hipevent_id", "=", $event_id)->delete();
        error_log("editEventSave schedulebegin = " . $schedulebegin);
        $eventscheduleObj = new \Eventschedule();
        $eventscheduleObj->hipevent_id = $event_id;
        $eventscheduleObj->begin = $schedulebegin;
        $eventscheduleObj->end = $scheduleend;
        $eventscheduleObj->save();
 

        $eventtimes = \Input::get('eventtimes');
        \Eventtime::where("hipevent_id", "=", $event->id)->delete();
        error_log("eventtimes : " . print_r($eventtimes, true));
        
        foreach($eventtimes as $eventtime) {

            if($eventtime) {

                error_log("timefrom : " . $eventtime["timefrom"]);
                $eventtimeObj = new \Eventtime();
                $eventtimeObj->hipevent_id = $event_id;
                $eventtimeObj->timefrom = $eventtime["timefrom"];
                $eventtimeObj->timeto = $eventtime["timeto"];
 
                $eventtimeObj->save();

            }
        }

        return 1;
    }

    public function deleteEvent($id)
    {
        error_log("deleteEvent");
        $event = \Hipevent::find($id);

        if($event) {
        error_log("deleteEvent 10");

            $event->hipcriterias()->delete();
            $event->delete(); 

        }

        return \Redirect::route('hipengage_showevents', ['json' => 1]);
    }







/////////////////////////////////////////////////

    public function getPushNotificationsForUser() {

        $user = Auth::user();

        if($user->level_code == "superadmin") {
            return $venues;
        }
                   
        $brands = $user->brands;
        $brand_ids = array();
        foreach($brands as $brand) {
            error_log("getNotificationsForUser : brand name " . $brand->name);    
            array_push($brand_ids, $brand->id);   
        }

        // Get events for brands that the user belongs to
        
        // $notifications = \Pushotification::all();
        // $filteredVenues = array();
        // foreach($notifications as $notifications) {
        //     if(in_array($notifications->brand_id, $brand_ids)) {
        //         error_log("getVenuesForUser : venue name " . $venue->name);    
        //         array_push($filteredVenues, $venue);
        //     }
        // }
    }



    // This adds brand name to a list of hipevents or any type of notification (push, sms, email, api, mgr)
    public function addBrandnameToObjectArray($list) {

        foreach($list as $item) {
            $item->brandname = \Brand::where('code', 'like', $item->engagebrand_code)->first()->name;
        }

        return $list;
    }


    public function showNotifications($json = null)
    {
        $data = array();

        $data['notifications'] = array();
        $data['currentMenuItem'] = "Notification Manager";

        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['previewurl'] = $assetsserver->value . 'hipengage/images/';

        $utils = new \Utils();
        $allowedbrandcodes = $utils->getAllowedEngageBrandcodes();

        $pusnotifications = \Pushnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        $pusnotifications =  $this->addBrandnameToObjectArray($pusnotifications);
        $data['notifications']['pusnotifications'] = $pusnotifications;
        $pusnotificationsJson = json_encode($pusnotifications);
        $data['notifications']['pusnotificationsJson'] = $pusnotificationsJson;

        $smsnotifications = \Smsnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        $smsnotifications =  $this->addBrandnameToObjectArray($smsnotifications);
        $data['notifications']['smsnotifications'] = $smsnotifications;
        $smsnotificationsJson = json_encode($smsnotifications);
        $data['notifications']['smsnotificationsJson'] = $smsnotificationsJson;

        $emailnotifications = \Emailnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        $emailnotifications =  $this->addBrandnameToObjectArray($emailnotifications);
        $data['notifications']['emailnotifications'] = $emailnotifications;
        $emailnotificationsJson = json_encode($emailnotifications);
        $data['notifications']['emailnotificationsJson'] = $emailnotificationsJson;

        $apinotifications = \Apinotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        $apinotifications =  $this->addBrandnameToObjectArray($apinotifications);
        $data['notifications']['apinotifications'] = $apinotifications;
        $apinotificationsJson = json_encode($apinotifications);
        $data['notifications']['apinotificationsJson'] = $apinotificationsJson;

        $mgrnotifications = \Mgrnotification::whereIn('engagebrand_code', $allowedbrandcodes)->get();
        $mgrnotifications =  $this->addBrandnameToObjectArray($mgrnotifications);
        $data['notifications']['mgrnotifications'] = $mgrnotifications;
        $mgrnotificationsJson = json_encode($mgrnotifications);
        $data['notifications']['mgrnotificationsJson'] = $mgrnotificationsJson;

        if($json) {
            error_log("showEvents : returning json" );
            return \Response::json($data['notifications']);

        } else {
            return \View::make('hipengage.shownotifications')->with('data', $data);
            
        }
    }


    public function selectAddNotification($type) {

        $data['currentMenuItem'] = "Notification Manager";
        $data['edit'] = 0;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForEngage();

        if($type == "push") {
            $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
            $data['previewurl'] = $assetsserver->value . 'hipengage/images/';
            return \View::make('hipengage.addpush')->with('data', $data);

        } else if ($type == "sms") {
            return \View::make('hipengage.addsms')->with('data', $data);

        } else if ($type == "email") {
            return \View::make('hipengage.addemail')->with('data', $data);

        } else if ($type == "api") {
            return \View::make('hipengage.addapi')->with('data', $data);

        } else if ($type == "mgr") {
            return \View::make('hipengage.addmgr')->with('data', $data);

        }
    }

    public function editPush($id)
    {
        error_log('editPush');

        $data = array();

        $data['currentMenuItem'] = "Notification Manager";
        $data['edit'] = 1;
        $data['id'] = $id;

        $notification = \Pushnotification::find($id);
        $brand = \Engagebrand::where("code", "like", $notification->engagebrand_code)->first();
        $notification->brand = $brand;
        echo "notification->brand = " . $brand->code;

        $data["notification"] = $notification;
        $data["notificationjson"] = json_encode($data["notification"]);

        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['previewurl'] = $assetsserver->value . 'hipengage/images/';

        return \View::make('hipengage.addpush')->with('data', $data);
    }


    public function editSms($id)
    {
        error_log('editSms');

        $data = array();

        $data['currentMenuItem'] = "Notification Manager";
        $data['edit'] = 1;
        $data['id'] = $id;

        $notification = \Smsnotification::find($id);
        $brand = \Engagebrand::where("code", "like", $notification->engagebrand_code)->first();
        $notification->brand = $brand;
        echo "notification->brand = " . $brand->code;

        $data["notification"] = $notification;
        $data["notificationjson"] = json_encode($data["notification"]);

        return \View::make('hipengage.addsms')->with('data', $data);
    }

    public function editEmail($id)
    {
        error_log('editEmail');

        $data = array();

        $data['currentMenuItem'] = "Notification Manager";
        $data['edit'] = 1;
        $data['id'] = $id;

        $notification = \Emailnotification::find($id);
        $brand = \Engagebrand::where("code", "like", $notification->engagebrand_code)->first();
        $notification->brand = $brand;
        echo "notification->brand = " . $brand->code;

        $data["notification"] = $notification;
        $data["notificationjson"] = json_encode($data["notification"]);

        return \View::make('hipengage.addemail')->with('data', $data);
    }

    public function editApi($id)
    {
        error_log('editApi');

        $data = array();

        $data['currentMenuItem'] = "Notification Manager";
        $data['edit'] = 1;
        $data['id'] = $id;

        $notification = \Apinotification::find($id);
        $brand = \Engagebrand::where("code", "like", $notification->engagebrand_code)->first();
        $notification->brand = $brand;

        $data["notification"] = $notification;
        $data["notificationjson"] = json_encode($data["notification"]);

        return \View::make('hipengage.addapi')->with('data', $data);

    }

    public function hipengage_testapi(){
        error_log("hipengage_testapi inputs : " . print_r(\Input::All(), true));

        return \Response::json(\Input::All());

    }

    public function hipengage_sendtestapinotification(){

        error_log("hipengage_sendtestapinotification auth : " . \Input::get('auth'));
        error_log("hipengage_sendtestapinotification url : " . \Input::get('url'));

        $now = new \DateTime();
        $nowtext = $now->format('Y-m-d H:i:s');

        // if(!$date) $date = $now->format('Y-m-d');

        $data = array(
          "auth" => \Input::get('auth'),
          "event_id" => "This is the event_id",
          "event_name" => "This is the event name",
          "device_id" => "This is the device_id",
          "created_at" => $nowtext,
          );

        $jsonData = json_encode($data);
        $jsonData = stripslashes($jsonData);

        $ch = curl_init(\Input::get('url'));                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'                                                                     
        ));                                                                                                                   
 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);

        error_log("returning response : " . print_r($response, true));

        return \View::make('hipengage.sendtestapinotification')->with('data', $response);
    }

    public function editMgr($id)
    {
        error_log('editMgr');

        $data = array();

        $data['currentMenuItem'] = "Notification Manager";
        $data['edit'] = 1;
        $data['id'] = $id;

        $notification = \Mgrnotification::find($id);
        $brand = \Engagebrand::where("code", "like", $notification->engagebrand_code)->first();
        $notification->brand = $brand;
        echo "notification->brand = " . $brand->code;

        $data["notification"] = $notification;
        $data["notificationjson"] = json_encode($data["notification"]);

        return \View::make('hipengage.addmgr')->with('data', $data);
    }

    public function addApiSave()
    {
        error_log("addApiSave : 10");

        $brand_id = \Input::get('brand_id');
        $returnvals = array();
        
        $apinotificationObj = new \Apinotification();
        $apinotificationObj->name = \Input::get('name');
        $apinotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $apinotificationObj->auth = \Input::get('auth');
        $apinotificationObj->url = \Input::get('url');

        if($apinotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $apinotificationObj->id;

        } else {

            $returnvals["status"] = 0;

        }

        return $returnvals;
    }

    public function editApiSave()
    {
        error_log("editApiSave : 10");

        $returnvals = array();
        
        $apinotificationObj = \Apinotification::find(\Input::get('id'));
        $apinotificationObj->name = \Input::get('name');
        $apinotificationObj->auth = \Input::get('auth');
        $apinotificationObj->url = \Input::get('url');

        if($apinotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $apinotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }

        return $returnvals;
    }



/////////////////////////////////////////////////

    public function addSmsSave()
    {

        $returnvals = array();
        
        $brand_id = \Input::get('brand_id');
        error_log("addSmsSave : brand_id = " . $brand_id);
        $smsnotificationObj = new \Smsnotification();
        $smsnotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $smsnotificationObj->name = \Input::get('name');
        $smsnotificationObj->survey_nastype = \Input::get('survey_nastype');
        $smsnotificationObj->survey_q1 = \Input::get('survey_q1');
        $smsnotificationObj->survey_q2 = \Input::get('survey_q2');
        $smsnotificationObj->message = \Input::get('message');

        if($smsnotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $smsnotificationObj->id;

        } else {

            $returnvals["status"] = 0;

        }

        return $returnvals;
    }

    public function editSmsSave()
    {
        error_log("editSmsSave : id = " . \Input::get('id'));

        $returnvals = array();
        
        $smsnotificationObj = \Smsnotification::find(\Input::get('id'));
        $smsnotificationObj->name = \Input::get('name');
        $smsnotificationObj->survey_nastype = \Input::get('survey_nastype');
        $smsnotificationObj->survey_q1 = \Input::get('survey_q1');
        $smsnotificationObj->survey_q2 = \Input::get('survey_q2');
        $smsnotificationObj->message = \Input::get('message');

        if($smsnotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $smsnotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }

        return $returnvals;
    }








    public function addMgrSave()
    {

        $returnvals = array();
        
        $brand_id = \Input::get('brand_id');
        error_log("addMgrSave : brand_id = " . $brand_id);
        $mgrnotificationObj = new \Mgrnotification();
        $mgrnotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $mgrnotificationObj->name = \Input::get('name');
        $mgrnotificationObj->cellphone = \Input::get('cellphone');
        $mgrnotificationObj->message = \Input::get('message');

        if($mgrnotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $mgrnotificationObj->id;

        } else {

            $returnvals["status"] = 0;

        }

        return $returnvals;
    }

    public function editMgrSave()
    {
        error_log("editMgrSave : id = " . \Input::get('id'));

        $returnvals = array();
        
        $mgrnotificationObj = \Mgrnotification::find(\Input::get('id'));
        $mgrnotificationObj->name = \Input::get('name');
        $mgrnotificationObj->cellphone = \Input::get('cellphone');
        $mgrnotificationObj->message = \Input::get('message');

        if($mgrnotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $mgrnotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }

        return $returnvals;
    }















    public function addEmailSave()
    {

        $returnvals = array();
        
        $brand_id = \Input::get('brand_id');
        error_log("addEmailSave : brand_id = " . $brand_id);
        $emailnotificationObj = new \Emailnotification();
        $emailnotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $emailnotificationObj->name = \Input::get('name');
        $emailnotificationObj->subject = \Input::get('subject');
        $emailnotificationObj->message = \Input::get('message');

        if($emailnotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $emailnotificationObj->id;

        } else {

            $returnvals["status"] = 0;

        }

        return $returnvals;
    }

    public function editEmailSave()
    {
        error_log("editEmailSave : id = " . \Input::get('id'));

        $returnvals = array();
        
        $emailnotificationObj = \Emailnotification::find(\Input::get('id'));
        $emailnotificationObj->name = \Input::get('name');
        $emailnotificationObj->subject = \Input::get('subject');
        $emailnotificationObj->message = \Input::get('message');

        if($emailnotificationObj->save()) {

            $returnvals["status"] = 1;
            $returnvals["id"] = $emailnotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }
        return $returnvals;
    }


    public function addPushSave()
    {
        error_log("addPushSave : 10");

        $returnvals = array();
        $input = \Input::all();
        $data = \Request::getContent();
        $json = json_decode($data);
        
        $pushnotificationObj = new \Pushnotification();

        $brand_id = \Input::get('brand_id');
        $pushnotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $pushnotificationObj->name = \Input::get('name');
        $pushnotificationObj->message = \Input::get('message');
        // $pushnotificationObj->image_url = \Input::get('image_url');
        $pushnotificationObj->sound = \Input::get('sound');
        $pushnotificationObj->vibrate = \Input::get('vibrate');
        $pushnotificationObj->preload = \Input::get('preload');

        if($pushnotificationObj->save()) {

            $mb_ext = \Input::get("mb_ext");
            $id = $pushnotificationObj->id;

            error_log("addPushSave : xxx mb_ext = $mb_ext");
            error_log("addPushSave : id = $id");

            $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
            $fullpath = $assetsdir->value . 'hipengage/images/';

            // Get Mobile File ///////////////////////////////////////////
            $sourceFullName = $fullpath . 'preview' . '.' . $mb_ext; 
            $destFullName = $fullpath . $id . '.' . $mb_ext; 
            error_log("addMediaSave: MB : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            \File::move($sourceFullName, $destFullName);
        
            $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
            $pushnotificationObj->image_url = $assetsserver->value . 'hipengage/images/' . $id . '.' . $mb_ext;
            $pushnotificationObj->save();

            $returnvals["status"] = 1;
            $returnvals["id"] = $pushnotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }

        return $returnvals;
    }

    public function editPushSave()
    {
        error_log("editPushSave : 10 : id = " . \Input::get('id'));

        $returnvals = array();
        
        $pushnotificationObj = \Pushnotification::find(\Input::get('id'));
        $pushnotificationObj->name = \Input::get('name');
        $pushnotificationObj->message = \Input::get('message');
        // $pushnotificationObj->image_url = \Input::get('image_url');
        //     error_log("addPushSave : image_url : " . $pushnotificationObj->image_url);
        $pushnotificationObj->sound = \Input::get('sound');
        $pushnotificationObj->vibrate = \Input::get('vibrate');
        $pushnotificationObj->preload = \Input::get('preload');

        if($pushnotificationObj->save()) {

            $mb_ext = \Input::get("mb_ext");
            $id = $pushnotificationObj->id;

            $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
            $fullpath = $assetsdir->value . 'hipengage/images/';

            // Get Mobile File ///////////////////////////////////////////
            $sourceFullName = $fullpath . 'preview' . '.' . $mb_ext; 
            $destFullName = $fullpath . $id . '.' . $mb_ext; 
            error_log("addMediaSave: MB : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            error_log("addMediaSave: MB : mb_ext ===" . $mb_ext . "===");

            if(file_exists($sourceFullName) && $mb_ext != "") {
                error_log("moving file ");
                \File::move($sourceFullName, $destFullName);

                $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
                $pushnotificationObj->image_url = $assetsserver->value . 'hipengage/images/' . $id . '.' . $mb_ext;
                $pushnotificationObj->save();
            }

            $returnvals["status"] = 1;
            $returnvals["image_url"] = $pushnotificationObj->image_url;
            $returnvals["id"] = $pushnotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }

        return $returnvals;
    }


    public function deletePush($id) {
        \Pushnotification::find($id)->delete();
        return 1;
    }
 
    public function deleteSms($id) {
        \Smsnotification::find($id)->delete();
        return 1;
    }
 
    public function deleteEmail($id) {
        \Emailnotification::find($id)->delete();
        return 1;
    }
    public function deleteApi($id) {
        \Apinotification::find($id)->delete();
        return 1;
    }   

    /////////////////////////////////////////

    public function showEmailtool()
    {
        $data = array();

        $data['currentMenuItem'] = "Bulk Email Tool";

        return \View::make('hipengage.showemailtool')->with('data', $data);
    }

    public function showSmstool()
    {
        $data = array();

        $data['currentMenuItem'] = "Bulk Sms Tool";

        return \View::make('hipengage.showsmstool')->with('data', $data);
    }

    public function showEngagebrands($json = null)
    {
        error_log("showEngagebrands");

        $data = array();
        $data['currentMenuItem'] = "Engage Brands";
        $brand = new \Brand();
        $brands = $brand->getEngageBrandsForUser(\Auth::user()->id);

        $data['brandsStruct'] = $brands;
        $brandsJason = json_encode($brands);
        $data['brandsJason'] = $brandsJason;

        $data['brands'] = $brands;

        if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($brandsJason);

        } else {
            return \View::make('hipengage.showengagebrands')->with('data', $data);
            
        }
    }
    

    ////////////// Monitoring /////////////////////

    public function venueMonitoring($json = null)
    {
        error_log("Engage showVenues");

        $data['currentMenuItem'] = "Venue Monitoring";

        return \View::make('hipengage.venuemonitoring')->with('data', $data);

    }

    /////////////////////// Venues /////////////////////////
    public function showVenues($json = null)
    {
        error_log("Engage showVenues");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        // $venues = \Venue::all();
        $venue = new \Venue();
        $venues = $venue->getVenuesForUser("hipengage");


        // error_log("showVenues : " . print_r($venues));
        foreach($venues as $venue) {
            if($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]); 
        }
        
        $data['venuesJason'] = json_encode($venues);

        $data['currentMenuItem'] = "Device Positions";

        if($json) {
            error_log("Engage showVenues : returning json" );
            return \Response::json($data['venuesJason']);

        } else {
            error_log("Engage showVenues : returning NON json" );
            return \View::make('hipengage.showvenues')->with('data', $data);
            
        }
    }


    // This will go away once there is a top level menu item for venue creation
    public function addVenue()
    {
        error_log("Engage addVenue");

        $data = array();
        $data['currentMenuItem'] = "Device Positions";
        $data['edit'] = false;

        $data['venue'] = new \Venue();

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipengage');

        return \View::make('hipengage.addvenue')->with('data', $data);
    }

    public function addVenueSave()
    {

        $messages = array(
            'macaddress.macaddress_format' => 'The MAC address format is incorrect AA:BB:CC:DD:EE:FF'
        );

        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $connection = \Brand::find($brand_id)->remotedb->dbconnection;
        $input['sitename'] = $sitename;

        $sitename_exists = \Venue::where("sitename", "like", $sitename)->first();
        if(! is_null($sitename_exists)) {
            $sitename_exists->forceDelete();
        } 

        $rules = array(
            'sitename'       => 'required|alpha_num_dash_spaces|unique:venues',                        
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipengage_addvenue')->withErrors($validator)->withInput();

        } else {
            
            $utils = new \Utils();
            
            $venue = new \Venue();
            $venue->sitename = $input['sitename'];
            $venue->location = $input['location'];
            $venue->countrie_id = $input['countrie_id'];
            $venue->province_id = $input['province_id'];
            $venue->citie_id = $input['citie_id'];
            $venue->brand_id = $input['brand_id'];
            $venue->isp_id = \Brand::find($venue->brand_id)->isp_id;
            $remotedb_id = \Brand::find($venue->brand_id)->remotedb_id;
            $venue->remotedb_id = $remotedb_id;
            $venue->latitude = $input['latitude'];
            $venue->longitude = $input['longitude'];
            $venue->address = $input['address'];
            $venue->contact = $input['contact'];
            $venue->notes = $input['notes'];
            $venue->save();

            $venue = $venue->refreshMediaLocation($venue);
            // $venue->insertVenueInRadius($venue, $remotedb_id);

        }

        return \Redirect::route('hipengage_showvenues');
    }

    // Right now its doing both edit and add - the idea is that it will just do edit
    public function editVenuePositions($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = true;

        $data['venue'] = \Venue::find($id);
        $data['brandcode'] = \Venue::find($id)->brand->code;
        error_log( "data brandcode : " . $data['brandcode'] );
        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]); 
        foreach($data['venue'] as $key => $value) { error_log("TTT : $key => $value"); };


        return \View::make('hipengage.addvenuepositions')->with('data', $data);
    }
}