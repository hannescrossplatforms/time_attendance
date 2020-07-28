<?php

namespace hipjam;

use Input;
//use app\lib\Heatmap;
use Session;
use TrackSeenMacAddress;

// use BaseController;

class HipjamController extends \BaseController
{
    // public $layout = 'layouts.angle_layout';
    // SONOFF EWELINK IOT
    public function turnOnVenue($id) {
        $venue = \Venue::find($id);
        $venue->status = 'Online';
        $venue->save();

        $resp = array();
        $resp['status'] = 'success';
        $resp['message'] = 'Greenside turned on';


        return \Response::json($resp);
    }

    public function turnOffVenue($id) {
        $venue = \Venue::find($id);
        $venue->status = 'Offline';
        $venue->save();

        $resp = array();
        $resp['status'] = 'success';
        $resp['message'] = 'Greenside turned off';


        return \Response::json($resp);
    }

    /////////////////////// TRACK API /////////////////////////
    public function sortmymfarray($mac)
    {
      return($mac->mac_address);
    }

    public function uploadSeenMacAddresses() {
        $id = \Input::get('id');
        $mac_addresses = \Input::get('mac_addresses');
        $f_mac = array();
        $f_mac = explode(",", $mac_addresses);
        // $master_array = array();

        $already_seen = \DB::select("
        SELECT
            mac_address
        FROM
            track_seen_mac_address
        WHERE
            date_seen = '".date("Y-m-d")."'
        AND venue_id = ".$id);

        $asd = array_values($already_seen);
        // return \Response::json($asd);
        $already_seen = array_map(array($this, 'sortmymfarray'),$asd);

        $mac_addresses_to_create = array_diff($f_mac, $already_seen);

        foreach ($mac_addresses_to_create as $mac_address)
        {
            \DB::table('track_seen_mac_address')->insert(
                array(
                       'venue_id' => $id,
                       'date_seen' => date("Y-m-d"),
                       'created_at' => date('Y-m-d H:i:s', strtotime('+2 hours')),
                       'updated_at' => date('Y-m-d H:i:s', strtotime('+2 hours')),
                       'mac_address' => $mac_address
                )
           );

        }

        // \TrackSeenMacAddress::insert($master_array);
        // $x = \TrackSeenMacAddress::all();

        $resp = array();
        $resp['status'] = 'success';
        $resp['message'] = count($mac_addresses_to_create).' entries created';

        // $json = json_encode($resp);
        return \Response::json($resp);
        // print_r($json);
    }

    public function venueDetails($venue_id) {
        $venue = \Venue::whereraw("id = $venue_id")->get();
        return \Response::json($venue);
    }

    public function updateAllSensorStatuses() {
        $sensors = \Sensor::all();
        foreach($sensors as $sensor) {
                $sensor->updateSensorStatus();
        }
        $resp = array();
        $resp['status'] = 'success';
        return \Response::json($resp);
    }

    public static function sendMail($to, $subject, $body) {
        $data = array();
        $data['email_text'] = $body;

        $returnVals = \Mail::send('sensor_down_email', $data, function($message) use($to, $subject)
        {
            $message->to($to)->subject($subject);
        });

    }

    public function checkAndSendStatusEmails() {
        // SELECT GROUP_CONCAT(id SEPARATOR ', ') ids FROM venues where send_alert_emails = 1
        $venue_ids = \DB::select("SELECT GROUP_CONCAT(id SEPARATOR ', ') ids FROM venues where send_alert_emails = 1")[0]->ids;
        $sensors = \Sensor::whereRaw('venue_id IN ('.$venue_ids.') AND status != "Online"')->get();
        $resp = array();
        foreach($sensors as $sensor) {
                $venue = \Venue::find($sensor->venue_id);
                $subject = $venue->sitename.' is offline';
                $body = $venue->sitename.' ('.$sensor->mac.') has been offline for '.$sensor->lastreportedin;

                if (isset($venue->alert_email_address_1) && $venue->alert_email_address_1 != '') {
                    \hipjam\HipjamController::sendMail($venue->alert_email_address_1, $subject, $body);
                }
                if (isset($venue->alert_email_address_2) && $venue->alert_email_address_2 != '') {
                    \hipjam\HipjamController::sendMail($venue->alert_email_address_2, $subject, $body);
                }
                if (isset($venue->alert_email_address_3) && $venue->alert_email_address_3 != '') {
                    \hipjam\HipjamController::sendMail($venue->alert_email_address_3, $subject, $body);
                }
        }

        $resp['status'] = 'success';
        $resp['venues'] = $venue_ids;
        $resp['sensors'] = $sensors;
        return \Response::json($resp);
    }



    /////////////////////// -------- /////////////////////////


    public function getBillboardInfoview($id) {
            $venue = \Venue::find($id);
            $data = array();
            $data['avg_basket_value'] = \Brand::find($venue->brand_id)->avg_basket_value;
            $data['conversions'] = \DB::select("SELECT
                                                    count(id) total_conversions
                                                FROM
                                                    track_seen_mac_address tsma
                                                    INNER JOIN ( SELECT DISTINCT
                                                            mac_address mac_addresses_seen_by_venues
                                                        FROM
                                                            track_seen_mac_address tsmaINNER
                                                            INNER JOIN (
                                                                SELECT
                                                                    id linked_venue_id
                                                                FROM
                                                                    venues iv
                                                                WHERE
                                                                    linked_billboard = ".$venue->id.") INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                            WHERE
                                                                tsmaINNER.date_seen = CURDATE()) INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                                WHERE
                                                    tsma.venue_id = ".$venue->id."
                                                    AND tsma.date_seen = CURDATE()")[0]->total_conversions;
            $data['potential_sales'] = $data['conversions'] * $data['avg_basket_value'];

        if ($data['conversions'] != 0) {
            $data['cpa'] = round(($venue->advertising_cost / 31) / $data['conversions'], 2);
        } else {
            $data['cpa'] = 'N/A';
        }

        if ($venue->advertising_cost && $venue->advertising_cost != 0) {
            $data['roi'] = round(($data['potential_sales'] / ($venue->advertising_cost / 31)) * 100, 2);
        } else {
            $data['roi'] = 'N/A';
        }

        $data['site_name'] = $venue->sitename;
        return \Response::json($data);
    }



    public function getVenueInfoview($id) {
        $venue = \Venue::find($id);
        $data = array();
        $data['conversions'] = \DB::select("SELECT
                                                count(id) total_conversions
                                            FROM
                                                track_seen_mac_address tsma
                                                INNER JOIN ( SELECT DISTINCT
                                                        mac_address mac_addresses_seen_by_venues
                                                    FROM
                                                        track_seen_mac_address tsmaINNER
                                                        INNER JOIN (
                                                            SELECT
                                                                id linked_venue_id
                                                            FROM
                                                                venues iv
                                                            WHERE
                                                                linked_billboard = ".$venue->id.") INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                        WHERE
                                                            tsmaINNER.date_seen = CURDATE()) INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                            WHERE
                                                tsma.venue_id = ".$venue->id."
                                                AND tsma.date_seen = CURDATE()")[0]->total_conversions;


    $data['site_name'] = $venue->sitename;
    return \Response::json($data);
}

public static function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}

public function getIds() {
    $type = \Input::get('type');
    $data = array();
    $brands = \DB::select("SELECT GROUP_CONCAT(id) brand_ids FROM brands where parent_brand = 165")[0]->brand_ids;
    if ($type == 'ooh') {
        $data['ids'] = \DB::select("SELECT GROUP_CONCAT(id SEPARATOR ',') ids FROM venues where track_type = 'billboard' AND brand_id IN (".$brands.")")[0]->ids;
    } else {
        $data['ids'] = \DB::select("SELECT GROUP_CONCAT(id SEPARATOR ',') ids FROM venues where track_type != 'billboard' AND brand_id IN (".$brands.") ")[0]->ids;
    }
    return \Response::json($data['ids']);
}

public function getIdsWithName() {
    $locations = \Input::get('location');
    $type = \Input::get('type');
    $data = array();
    $brands = \DB::select("SELECT GROUP_CONCAT(id) brand_ids FROM brands where parent_brand = 165")[0]->brand_ids.',165';
    if ($type == 'ooh') {
        if ($locations != '0' && $locations != '') {
            $data['ids'] = \DB::select("SELECT id, IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1))) name FROM venues WHERE track_type = 'billboard' AND id IN (".$locations.") AND brand_id IN (".$brands.")");
        } else {
            $data['ids'] = \DB::select("SELECT id, IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1))) name FROM venues WHERE track_type = 'billboard' AND brand_id IN (".$brands.")");
        }
    } else {
        if ($locations != '0' && $locations != '') {
            $data['ids'] = \DB::select("SELECT id, IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1))) name FROM venues WHERE track_type != 'billboard' AND id IN (".$locations.") AND brand_id IN (".$brands.")");
        } else {
            $data['ids'] = \DB::select("SELECT id, IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1))) name FROM venues WHERE track_type != 'billboard' AND brand_id IN (".$brands.")");
        }

    }
    return \Response::json($data['ids']);

}

public function getIdsWithRegion() {
    $type = \Input::get('type');
    $group = \Input::get('group');
    $data = array();
    if ($type == 'ooh') {
        if ($group == 'province') {
            $data['data'] = \DB::select("
            SELECT GROUP_CONCAT(IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1)))) venue_name, GROUP_CONCAT(venues.id) ids, cities.name city_name, provinces.name province_name FROM venues
            INNER JOIN cities ON venues.citie_id = cities.id
            INNER JOIN provinces ON venues.province_id = provinces.id
            WHERE
                track_type = 'billboard'
            AND brand_id IN (SELECT id FROM brands where id = 165 OR parent_brand = 165)
            GROUP BY provinces.name
            ");
        } else {
            $data['data'] = \DB::select("
            SELECT GROUP_CONCAT(IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1)))) venue_name, GROUP_CONCAT(venues.id) ids, cities.name city_name, provinces.name province_name FROM venues
            INNER JOIN cities ON venues.citie_id = cities.id
            INNER JOIN provinces ON venues.province_id = provinces.id
            WHERE
                track_type = 'billboard'
            AND brand_id IN (SELECT id FROM brands where id = 165 OR parent_brand = 165)
            GROUP BY cities.name
            ");
        }
    } else {
        if ($group == 'province') {
            $data['data'] = \DB::select("
            SELECT GROUP_CONCAT(IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1)))) venue_name, GROUP_CONCAT(venues.id) ids, cities.name city_name, provinces.name province_name FROM venues
            INNER JOIN cities ON venues.citie_id = cities.id
            INNER JOIN provinces ON venues.province_id = provinces.id
            WHERE
                track_type != 'billboard'
            AND brand_id IN (SELECT id FROM brands where id = 165 OR parent_brand = 165)
            GROUP BY provinces.name
            ");
        } else {
            $data['data'] = \DB::select("
            SELECT GROUP_CONCAT(IF(friendly_brandname IS NULL, sitename, CONCAT(friendly_brandname, ' ', SUBSTRING_INDEX(sitename,' ',-1)))) venue_name, GROUP_CONCAT(venues.id) ids, cities.name city_name, provinces.name province_name FROM venues
            INNER JOIN cities ON venues.citie_id = cities.id
            INNER JOIN provinces ON venues.province_id = provinces.id
            WHERE
                track_type != 'billboard'
            AND brand_id IN (SELECT id FROM brands where id = 165 OR parent_brand = 165)
            GROUP BY cities.name
            ");
        }

    }
    return \Response::json($data['data']);
}

public function getOohSiteData() {
    $date_from = \Input::get('date_from');
    $date_to = \Input::get('date_to');
    $span = \Input::get('span');
    $view = \Input::get('view');

    $period = \hipjam\HipjamController::createDateRangeArray($date_from,$date_to);


    $all = \Venue::all();
    $billboards = \Venue::whereRaw('track_type = "billboard"')->get();
    $raw = array();
    $formatted_data = array();
    $master_array = array();

    foreach ($all as $venue) {
        if ($venue->track_type == 'venue' && isset($venue->linked_billboard) && $venue->linked_billboard != 0) {
            $conversions = \DB::select("SELECT
                                            tsma.date_seen,
                                            count(*) total_conversions
                                        FROM
                                            track_seen_mac_address tsma
                                        INNER JOIN (
                                            SELECT
                                                date_seen,
                                                mac_address
                                            FROM
                                                track_seen_mac_address
                                            WHERE
                                                date_seen BETWEEN '".$date_from."' AND '".$date_to."'
                                            AND venue_id = ".$venue->linked_billboard." -- bbid
                                        ) seen_by_billboard ON tsma.mac_address = seen_by_billboard.mac_address
                                        WHERE tsma.venue_id = ".$venue->id." -- vid
                                        AND tsma.date_seen BETWEEN '".$date_from."' AND '".$date_to."'
                                        AND seen_by_billboard.date_seen < tsma.date_seen GROUP BY tsma.date_seen");
                array_push($raw,$conversions);
        }
    }

    foreach ($period as $value) {
        $formatted_data['date'] = $value;
        foreach ($billboards as $billboard) {
            if (count($raw) != 0) {
                foreach ($raw as $metrics) {
                    foreach ($metrics as $conv) {
                        if ($conv->date_seen == $value) {
                            $formatted_data[$billboard->id.'_conversions'] = $conv->total_conversions;
                        } else {
                            $formatted_data[$billboard->id.'_conversions'] = 0;
                        }
                    }
                }
            } else {
                // $formatted_data[$billboard->id.'_conversions'] = 0;
            }

        }
        array_push($master_array, $formatted_data);
    }
    return \Response::json($master_array);
}



    /////////////////////// Venues /////////////////////////
    public function showDashboard($json = null)
    {
        error_log("showDashboard");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";

        $brandIds = array();
        $userObj = \Auth::user();

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();
        } else {
            error_log("getBrandsForDatabase : NOT superadmin");
            $allowedbrands = $userObj->brands;
        }


        foreach ($allowedbrands as $brand) {
            array_push($brandIds, $brand->id);
        }

        $brandIdsString = implode(",", $brandIds);
        $liveNumberOfBillboardsCount = count(\Venue::whereraw("track_type = 'billboard' AND brand_id IN ($brandIdsString) AND ap_active = 1")->get());

        if (\User::isVicinity()) {
            $vicinity_brands = \Brand::whereRaw('parent_brand IN ('.$brandIdsString.')')->get();
            $vbrands = array();
            foreach ($vicinity_brands as $brand) {
                array_push($vbrands, $brand->id);
            }
            $vbrandsarray = implode(",", $vbrands);
            $liveNumberOfBillboardsCount = count(\Venue::whereraw("(track_type = 'billboard') AND ((brand_id = 165 OR brand_id IN ($vbrandsarray)))")->get());
        } else {
            $vicinity_brands = \Brand::whereRaw('parent_brand IN ('.$brandIdsString.')')->get();
            $liveNumberOfBillboardsCount = count(\Venue::whereraw("(track_type = 'billboard') AND (brand_id IN ($brandIdsString))")->get());
        }

        $avg_distance = \DB::select("
            SELECT
                ROUND(IFNULL(AVG( 6371 * acos (cos ( radians(billboard_latitude))
                                * cos( radians( venue_latitude ) )
                                * cos( radians( venue_logitude ) - radians(billboard_longitude) )
                                + sin ( radians(billboard_latitude) )
                                * sin( radians( venue_latitude ) )
                                )
                            ), 0), 2) as distance
            FROM
                (
                    SELECT
                        (SELECT latitude FROM venues WHERE id = v.linked_billboard) as billboard_latitude,
                        (SELECT longitude FROM venues WHERE id = v.linked_billboard) as billboard_longitude,
                        latitude as venue_latitude,
                        longitude as venue_logitude
                    FROM
                        venues v
                    WHERE
                        linked_billboard IS NOT NULL
                    AND linked_billboard != 0
                ) as inq
        ");
        $data['avg_distance'] = $avg_distance;
        $data['live_number_of_billboards'] = $liveNumberOfBillboardsCount;
        $data['sub_brands'] = $vicinity_brands;

        \Log::info("[HipjamController  showDashboard] - live_number_of_billboards is: $liveNumberOfBillboardsCount");

        if (\User::isVicinity()) {
            $vicinity_brands = \Brand::where('parent_brand', '=', 165)->get();
            $vbrands = array();
            foreach ($vicinity_brands as $brand) {
                array_push($vbrands, $brand->id);
            }
            $vbrandsarray = implode(",", $vbrands);
            $liveNumberOfRetailVenuesCount = count(\Venue::whereraw("(track_type = 'venue' OR track_type IS NULL) AND ((brand_id = 165 OR brand_id IN ($vbrandsarray)) AND jam_activated = true)")->get());
        } else {
            $liveNumberOfRetailVenuesCount = count(\Venue::whereraw("(track_type = 'venue' OR track_type IS NULL) AND (brand_id IN ($brandIdsString) AND jam_activated = true)")->get());
        }

        $data['live_number_of_retail_venues'] = $liveNumberOfRetailVenuesCount;

        $venue = new \Venue();
        // $venues = $venue->getVenuesForUser('hipjam', 1);
        $venues = $venue->getVenuesForUser('hipjam', 1, null, null, "active");
        // $data['billboards'] =  $venues::where('track_type', '=', 'billboard');

        foreach ($venues as $venue) {
            if ($venue->ap_active == 0) {
                $venue["status"] = '<span style="color:red">Inactive</span>';
            } else {
                $venue["status"] = '<span style="color:green">Active</span>';
            }
            if ($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
            //$sitename = strtolower($venue["sitename"]);
            //$sitename = str_replace(" ","-",$sitename);
            /*$venue["apisitename"] = $venue["track_venue_id"] != '' ? $venue["track_venue_id"] : 'no_venue' ;*/
            $venue["apisitename"] = $venue["track_server_location"] != '' ? $venue["track_server_location"] : 'no_venue';
            $venue["track_slugname"] = $venue["track_slug"] != '' ? $venue["track_slug"] : 'no_venue';
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        // $data['venuesJson'] = json_encode($venues);

        $data['currentMenuItem'] = "Dashboard";


        // FILTERS
        $brand_id_filter = Input::get('brand_id');
        $brand_type_filter = Input::get('type');

        if ($brand_id_filter != null) {
            $venues = new \Venue();
            if ($brand_id_filter != 'global') {
                $venues = $venues->whereraw("brand_id = '$brand_id_filter' AND jam_activated = true");
                $data['live_number_of_billboards'] = count(\Venue::whereraw("(track_type = 'billboard') AND ((brand_id = $brand_id_filter) AND jam_activated = true)")->get());
                $data['live_number_of_retail_venues'] = count(\Venue::whereraw("(track_type = 'venue' OR track_type IS NULL) AND ((brand_id = $brand_id_filter) AND jam_activated = true)")->get());
            } else {
                $venues_id_array = array();
                $original_venues = $venues->getVenuesForUser('hipjam', 1, null, null, "active");

                foreach ($original_venues as $v){
                    array_push($venues_id_array, $v->id);
                }


                // $mapped_venues_ids_raw = array_map(create_function('$o', 'return $o->id;'), $original_venues);
                $mapped_venues_ids = implode(',', $venues_id_array);
                $venues = $venues->whereraw("id IN ($mapped_venues_ids)");
            }

            if ($brand_type_filter != 'global') {
                $venues = $venues->whereraw("track_type = '$brand_type_filter'");
            }

            $venues = $venues->get();

        }

        $data['venuesJson'] = json_encode($venues);


        $data['testi'] = $brand_type_filter;

    // FILTERS
    $date_from = \Input::get('date_from');
    $date_to = \Input::get('date_to');

    if ($date_from == null) {
        $date_from = date('Y-m-d',strtotime('last monday'));
    }

    if ($date_to == null) {
        $date_to = date('Y-m-d',strtotime('next sunday'));
    }
    $billboard_objects = \Venue::where('track_type', '=', 'billboard');
    $billboards = \Venue::where('track_type', '=', 'billboard')->pluck('id');
    $venues_with_billboards = \Venue::where('linked_billboard', 'in', $billboards)->pluck('id');
    // ADDITIONAL METRICS
    $data['conversions'] = \DB::select("SELECT
                                            count(id) total_conversions
                                        FROM
                                            track_seen_mac_address tsma
                                            INNER JOIN ( SELECT DISTINCT
                                                    mac_address mac_addresses_seen_by_venues
                                                FROM
                                                    track_seen_mac_address tsmaINNER
                                                    INNER JOIN (
                                                        SELECT
                                                            id linked_venue_id
                                                        FROM
                                                            venues iv
                                                        WHERE
                                                            linked_billboard IN (".$billboards.")) INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                    WHERE
                                                        tsmaINNER.date_seen BETWEEN '$date_from'
                                                        AND '$date_to') INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                        WHERE
                                            tsma.venue_id IN (".$billboards.")
                                            AND tsma.date_seen BETWEEN '$date_from'
                                            AND '$date_to'")[0]->total_conversions;

    $data['conversions_today'] = \DB::select("SELECT
                                            count(id) total_conversions
                                        FROM
                                            track_seen_mac_address tsma
                                            INNER JOIN ( SELECT DISTINCT
                                                    mac_address mac_addresses_seen_by_venues
                                                FROM
                                                    track_seen_mac_address tsmaINNER
                                                    INNER JOIN (
                                                        SELECT
                                                            id linked_venue_id
                                                        FROM
                                                            venues iv
                                                        WHERE
                                                            linked_billboard IN (".$billboards.")) INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                    WHERE
                                                        tsmaINNER.date_seen = CURDATE()) INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                        WHERE
                                            tsma.venue_id IN (".$billboards.")
                                            AND tsma.date_seen  = CURDATE()")[0]->total_conversions;

    $data['strike_time'] = \DB::select("
                                            SELECT
                                                ROUND(AVG(time_taken_to_convert) / 60,2) minutes,
                                                ROUND(AVG(time_taken_to_convert) / 3600,2) hours,
                                                ROUND(AVG(time_taken_to_convert) / 86400,2) days
                                            FROM (
                                                SELECT
                                                    TIMESTAMPDIFF(SECOND, seen_by_billboard, seen_by_venue) time_taken_to_convert
                                                FROM (
                                                    SELECT
                                                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id  IN (".$billboards.") AND date_seen = INQ.date_seen) seen_by_billboard,
                                                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id IN (".$venues_with_billboards.") AND date_seen = INQ.date_seen) seen_by_venue
                                                    FROM (
                                                        SELECT
                                                            count(*) seen_times,
                                                            mac_address,
                                                            date_seen
                                                        FROM
                                                            track_seen_mac_address tsma
                                                        WHERE
                                                            (venue_id IN (".$billboards.") OR venue_id IN (".$venues_with_billboards."))
                                                        AND (date_seen BETWEEN '$date_from' AND '$date_to')
                                                        GROUP BY mac_address,date_seen HAVING seen_times > 1
                                                    ) INQ
                                                ) INQ2 HAVING time_taken_to_convert > 0
                                            ) INQ3
                                            ");

    $data['strike_distance'] = \DB::select("
                                            SELECT
                                                ROUND(IFNULL(AVG( 6371 * acos (cos ( radians(billboard_latitude))
                                                                * cos( radians( venue_latitude ) )
                                                                * cos( radians( venue_logitude ) - radians(billboard_longitude) )
                                                                + sin ( radians(billboard_latitude) )
                                                                * sin( radians( venue_latitude ) )
                                                                )
                                                            ), 0), 2) as distance
                                            FROM
                                                (
                                                    SELECT
                                                        (SELECT latitude FROM venues WHERE id = v.linked_billboard) as billboard_latitude,
                                                        (SELECT longitude FROM venues WHERE id = v.linked_billboard) as billboard_longitude,
                                                        latitude as venue_latitude,
                                                        longitude as venue_logitude
                                                    FROM
                                                        venues v
                                                    WHERE
                                                        linked_billboard in (".$billboards.")
                                                ) as inq
                                        ");

        $data['avg_basket_value'] = \DB::select("SELECT sum(avg_basket_value) total_avg_basket_value FROM brands WHERE id = 165 OR parent_brand = 165")[0]->total_avg_basket_value;
        $data['potential_sales'] = $data['conversions'] * $data['avg_basket_value'];
        $data['advertising_cost'] = \DB::select("SELECT sum(advertising_cost) adv_cost FROM venues WHERE id IN (".$billboards.")")[0]->adv_cost;

        if ($data['conversions'] != 0) {
            $data['cpa'] = round($data['advertising_cost'] / $data['conversions'], 2);
        } else {
            $data['cpa'] = 'N/A';
        }

        if ($venue->advertising_cost && $venue->advertising_cost != 0) {
            $data['roi'] = round(($data['potential_sales'] / $data['advertising_cost']) * 100, 2);
        } else {
            $data['roi'] = 'N/A';
        }

        $data['all_venues'] = $venues;



        if ($json) {
            error_log("showDashboard : returning json");
            return \Response::json($data['venuesJson']);
        } else {
            error_log("showDashboard : returning NON json");
            return \View::make('hipjam.showdashboardlist')->with('data', $data);
        }
    }

    public function showVicinityDashboard($json = null) {
        error_log("showDashboard");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";

        $brandIds = array();
        $userObj = \Auth::user();

        if (\User::hasAccess("superadmin")) {
            $allowedbrands = \Brand::All();
        } else {
            error_log("getBrandsForDatabase : NOT superadmin");
            $allowedbrands = $userObj->brands;
        }


        foreach ($allowedbrands as $brand) {
            array_push($brandIds, $brand->id);
        }

        $brandIdsString = implode(",", $brandIds);
        $liveNumberOfBillboardsCount = count(\Venue::whereraw("track_type = 'billboard' AND brand_id IN ($brandIdsString) AND ap_active = 1")->get());

        if (\User::isVicinity()) {
            $vicinity_brands = \Brand::whereRaw('parent_brand IN ('.$brandIdsString.')')->get();
            $vbrands = array();
            foreach ($vicinity_brands as $brand) {
                array_push($vbrands, $brand->id);
            }
            $vbrandsarray = implode(",", $vbrands);
            $liveNumberOfBillboardsCount = count(\Venue::whereraw("(track_type = 'billboard') AND ((brand_id = 165 OR brand_id IN ($vbrandsarray)))")->get());
        } else {
            $vicinity_brands = \Brand::whereRaw('parent_brand IN ('.$brandIdsString.')')->get();
            $liveNumberOfBillboardsCount = count(\Venue::whereraw("(track_type = 'billboard') AND (brand_id IN ($brandIdsString))")->get());
        }

        $avg_distance = \DB::select("
            SELECT
                ROUND(IFNULL(AVG( 6371 * acos (cos ( radians(billboard_latitude))
                                * cos( radians( venue_latitude ) )
                                * cos( radians( venue_logitude ) - radians(billboard_longitude) )
                                + sin ( radians(billboard_latitude) )
                                * sin( radians( venue_latitude ) )
                                )
                            ), 0), 2) as distance
            FROM
                (
                    SELECT
                        (SELECT latitude FROM venues WHERE id = v.linked_billboard) as billboard_latitude,
                        (SELECT longitude FROM venues WHERE id = v.linked_billboard) as billboard_longitude,
                        latitude as venue_latitude,
                        longitude as venue_logitude
                    FROM
                        venues v
                    WHERE
                        linked_billboard IS NOT NULL
                    AND linked_billboard != 0
                ) as inq
        ");
        $data['avg_distance'] = $avg_distance;
        $data['live_number_of_billboards'] = $liveNumberOfBillboardsCount;
        $data['sub_brands'] = $vicinity_brands;

        \Log::info("[HipjamController  showDashboard] - live_number_of_billboards is: $liveNumberOfBillboardsCount");

        if (\User::isVicinity()) {
            $vicinity_brands = \Brand::where('parent_brand', '=', 165)->get();
            $vbrands = array();
            foreach ($vicinity_brands as $brand) {
                array_push($vbrands, $brand->id);
            }
            $vbrandsarray = implode(",", $vbrands);
            $liveNumberOfRetailVenuesCount = count(\Venue::whereraw("(track_type = 'venue' OR track_type IS NULL) AND ((brand_id = 165 OR brand_id IN ($vbrandsarray)) AND jam_activated = true)")->get());
        } else {
            $liveNumberOfRetailVenuesCount = count(\Venue::whereraw("(track_type = 'venue' OR track_type IS NULL) AND (brand_id IN ($brandIdsString) AND jam_activated = true)")->get());
        }

        $data['live_number_of_retail_venues'] = $liveNumberOfRetailVenuesCount;

        $venue = new \Venue();
        // $venues = $venue->getVenuesForUser('hipjam', 1);
        $venues = $venue->getVenuesForUser('hipjam', 1, null, null, "active");
        // $data['billboards'] =  $venues::where('track_type', '=', 'billboard');

        foreach ($venues as $venue) {
            if ($venue->ap_active == 0) {
                $venue["status"] = '<span style="color:red">Inactive</span>';
            } else {
                $venue["status"] = '<span style="color:green">Active</span>';
            }
            if ($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
            //$sitename = strtolower($venue["sitename"]);
            //$sitename = str_replace(" ","-",$sitename);
            /*$venue["apisitename"] = $venue["track_venue_id"] != '' ? $venue["track_venue_id"] : 'no_venue' ;*/
            $venue["apisitename"] = $venue["track_server_location"] != '' ? $venue["track_server_location"] : 'no_venue';
            $venue["track_slugname"] = $venue["track_slug"] != '' ? $venue["track_slug"] : 'no_venue';
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        // $data['venuesJson'] = json_encode($venues);

        $data['currentMenuItem'] = "Dashboard";


        // FILTERS
        $brand_id_filter = Input::get('brand_id');
        $brand_type_filter = Input::get('type');

        if ($brand_id_filter != null) {
            $venues = new \Venue();
            if ($brand_id_filter != 'global') {
                $venues = $venues->whereraw("brand_id = '$brand_id_filter' AND jam_activated = true");
                $data['live_number_of_billboards'] = count(\Venue::whereraw("(track_type = 'billboard') AND ((brand_id = $brand_id_filter) AND jam_activated = true)")->get());
                $data['live_number_of_retail_venues'] = count(\Venue::whereraw("(track_type = 'venue' OR track_type IS NULL) AND ((brand_id = $brand_id_filter) AND jam_activated = true)")->get());
            } else {
                $venues_id_array = array();
                $original_venues = $venues->getVenuesForUser('hipjam', 1, null, null, "active");

                foreach ($original_venues as $v){
                    array_push($venues_id_array, $v->id);
                }


                // $mapped_venues_ids_raw = array_map(create_function('$o', 'return $o->id;'), $original_venues);
                $mapped_venues_ids = implode(',', $venues_id_array);
                $venues = $venues->whereraw("id IN ($mapped_venues_ids)");
            }

            if ($brand_type_filter != 'global') {
                $venues = $venues->whereraw("track_type = '$brand_type_filter'");
            }

            $venues = $venues->get();

        }

        $data['venuesJson'] = json_encode($venues);


        $data['testi'] = $brand_type_filter;

    // FILTERS
    $date_from = \Input::get('date_from');
    $date_to = \Input::get('date_to');

    if ($date_from == null) {
        $date_from = date('Y-m-d',strtotime('last monday'));
    }

    if ($date_to == null) {
        $date_to = date('Y-m-d',strtotime('next sunday'));
    }
    $billboard_objects = \Venue::where('track_type', '=', 'billboard');
    $billboards = \Venue::where('track_type', '=', 'billboard')->pluck('id');
    $venues_with_billboards = \Venue::where('linked_billboard', 'in', $billboards)->pluck('id');
    // ADDITIONAL METRICS
    $data['conversions'] = \DB::select("SELECT
                                            count(id) total_conversions
                                        FROM
                                            track_seen_mac_address tsma
                                            INNER JOIN ( SELECT DISTINCT
                                                    mac_address mac_addresses_seen_by_venues
                                                FROM
                                                    track_seen_mac_address tsmaINNER
                                                    INNER JOIN (
                                                        SELECT
                                                            id linked_venue_id
                                                        FROM
                                                            venues iv
                                                        WHERE
                                                            linked_billboard IN (".$billboards.")) INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                    WHERE
                                                        tsmaINNER.date_seen BETWEEN '$date_from'
                                                        AND '$date_to') INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                        WHERE
                                            tsma.venue_id IN (".$billboards.")
                                            AND tsma.date_seen BETWEEN '$date_from'
                                            AND '$date_to'")[0]->total_conversions;

    $data['conversions_today'] = \DB::select("SELECT
                                            count(id) total_conversions
                                        FROM
                                            track_seen_mac_address tsma
                                            INNER JOIN ( SELECT DISTINCT
                                                    mac_address mac_addresses_seen_by_venues
                                                FROM
                                                    track_seen_mac_address tsmaINNER
                                                    INNER JOIN (
                                                        SELECT
                                                            id linked_venue_id
                                                        FROM
                                                            venues iv
                                                        WHERE
                                                            linked_billboard IN (".$billboards.")) INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                    WHERE
                                                        tsmaINNER.date_seen = CURDATE()) INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                        WHERE
                                            tsma.venue_id IN (".$billboards.")
                                            AND tsma.date_seen  = CURDATE()")[0]->total_conversions;

    $data['strike_time'] = \DB::select("
                                            SELECT
                                                ROUND(AVG(time_taken_to_convert) / 60,2) minutes,
                                                ROUND(AVG(time_taken_to_convert) / 3600,2) hours,
                                                ROUND(AVG(time_taken_to_convert) / 86400,2) days
                                            FROM (
                                                SELECT
                                                    TIMESTAMPDIFF(SECOND, seen_by_billboard, seen_by_venue) time_taken_to_convert
                                                FROM (
                                                    SELECT
                                                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id  IN (".$billboards.") AND date_seen = INQ.date_seen) seen_by_billboard,
                                                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id IN (".$venues_with_billboards.") AND date_seen = INQ.date_seen) seen_by_venue
                                                    FROM (
                                                        SELECT
                                                            count(*) seen_times,
                                                            mac_address,
                                                            date_seen
                                                        FROM
                                                            track_seen_mac_address tsma
                                                        WHERE
                                                            (venue_id IN (".$billboards.") OR venue_id IN (".$venues_with_billboards."))
                                                        AND (date_seen BETWEEN '$date_from' AND '$date_to')
                                                        GROUP BY mac_address,date_seen HAVING seen_times > 1
                                                    ) INQ
                                                ) INQ2 HAVING time_taken_to_convert > 0
                                            ) INQ3
                                            ");

    $data['strike_distance'] = \DB::select("
                                            SELECT
                                                ROUND(IFNULL(AVG( 6371 * acos (cos ( radians(billboard_latitude))
                                                                * cos( radians( venue_latitude ) )
                                                                * cos( radians( venue_logitude ) - radians(billboard_longitude) )
                                                                + sin ( radians(billboard_latitude) )
                                                                * sin( radians( venue_latitude ) )
                                                                )
                                                            ), 0), 2) as distance
                                            FROM
                                                (
                                                    SELECT
                                                        (SELECT latitude FROM venues WHERE id = v.linked_billboard) as billboard_latitude,
                                                        (SELECT longitude FROM venues WHERE id = v.linked_billboard) as billboard_longitude,
                                                        latitude as venue_latitude,
                                                        longitude as venue_logitude
                                                    FROM
                                                        venues v
                                                    WHERE
                                                        linked_billboard in (".$billboards.")
                                                ) as inq
                                        ");

        $data['avg_basket_value'] = \DB::select("SELECT sum(avg_basket_value) total_avg_basket_value FROM brands WHERE id = 165 OR parent_brand = 165")[0]->total_avg_basket_value;
        $data['potential_sales'] = $data['conversions'] * $data['avg_basket_value'];
        $data['advertising_cost'] = \DB::select("SELECT sum(advertising_cost) adv_cost FROM venues WHERE id IN (".$billboards.")")[0]->adv_cost;

        if ($data['conversions'] != 0) {
            $data['cpa'] = round($data['advertising_cost'] / $data['conversions'], 2);
        } else {
            $data['cpa'] = 'N/A';
        }

        if ($venue->advertising_cost && $venue->advertising_cost != 0) {
            $data['roi'] = round(($data['potential_sales'] / $data['advertising_cost']) * 100, 2);
        } else {
            $data['roi'] = 'N/A';
        }

        $data['all_venues'] = $venues;



        if ($json) {
            error_log("showDashboard : returning json");
            return \Response::json($data['venuesJson']);
        } else {
            error_log("showDashboard : returning NON json");
            return \View::make('hipjam.vicinitydashboard')->with('data', $data);
        }
    }


    public function loadCustomerStatsForDash() {

        $brandIds = array();

        $userObj = \Auth::user();
        $brands = $userObj->brands;

        foreach ($brands as $brand) {
            array_push($brandIds, $brand->id);
        }

        $brandIdsString = implode(",", $brandIds);

        $allVenues = \Venue::whereraw("track_type = 'billboard' AND brand_id IN ($brandIdsString)")->get();

        //INDIVIDUALS EXPOSED CURRENT START

        $individualsExposedCurrent = 0;

        foreach ($allVenues as $venue) {

            $jsonString = file_get_contents("http://tracks03.hipzone.co.za/aggregate/$venue->id?period=now");

            $jsonData = json_decode($jsonString);
            $total = $jsonData->total->total;
            $individualsExposedCurrent += $total;

        }

        \Log::info("[HipjamController  showDashboard] - exposed_visits_today is:  $individualsExposedCurrent");

        $data['individualsExposedCurrent'] = $individualsExposedCurrent;

        //INDIVIDUALS EXPOSED CURRENT END

        //exposed_visits_this_month START
        // INDIVIDUALS EXPOSED TODAY
        $individualsExposedToday = 0;
        $uniquesToday = 0;
        foreach ($allVenues as $venue) {

            $jsonString = file_get_contents("http://tracks03.hipzone.co.za/aggregate/$venue->id?period=today");

            $jsonData = json_decode($jsonString);
            $total = $jsonData->total->total;
            $new = $jsonData->total->new;

            $individualsExposedToday += $total;
            $uniquesToday += $new;

        }

        $data['individuals_exposed_today'] = $individualsExposedToday;
        $data['uniques_today'] = $uniquesToday;
        //exposed_visits_this_monty END

        //UNIQUENESS TODAY START

        //UNIQUENESS TODAY END

        $json = json_encode($data);

        print_r($json);


    }

    public function graphData(){

        $data = array();
        $brandCodes = array();

        $userObj = \Auth::user();
        $brands = $userObj->brands;

        foreach ($brands as $brand) {
            array_push($brandCodes, $brand->code);
        }

        $brandCodesString = implode("|", $brandCodes);

        $reportObj = new \Reports();
        $data = array();
        $reportperiod = "rep7day";

        $data["highest5Sessions"] = $reportObj->gethighest5Sessions(null, $reportperiod, $brandCodesString);
        $data["lowest5Sessionsdata"] = $reportObj->getlowest5Sessionsdata(null, $reportperiod, $brandCodesString);


        print_r(json_encode($data));

    }


    // BEGIN BRAND /////////////////////////////////////////////////////////////


    public function showBrands($json = null)
    {
        error_log("showBrands hipjam");

        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $brand = new \Brand();
        if (\User::isVicinity()) {
            $jambrands = \Brand::where('parent_brand', '=', 165)
                    ->whereNull('deleted_at')
                    ->where('brands.hipjam', '=', 1)
                    ->orderBy('name', 'asc')->get();
        } else {
            $jambrands = $brand->getJamBrandsForUser(\Auth::user()->id, "active");
        }

        $data['brandsStruct'] = $jambrands;
        $brandsJason = json_encode($jambrands);
        $data['brandsJason'] = $brandsJason;

        $data['brands'] = $jambrands;

        if ($json) {
            error_log("showDashboard : returning json");
            return \Response::json($brandsJason);
        } else {
            return \View::make('hipjam.showbrands')->with('data', $data);
        }
    }

    public function getInactiveBrands()
    {
        error_log("getInactiveBrands jam");
        $data = array();
        $brand = new \Brand();
        $jambrands = $brand->getJamBrandsForUser(\Auth::user()->id, "inactive");
        $brandsJason = json_encode($jambrands);

        return \Response::json($brandsJason);
    }

    public function addBrand()
    {
        error_log('addBrand');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['edit'] = false;
        $data['brand'] = false;

        return \View::make('hipjam.addbrand')->with('data', $data);
    }

    public function addBrandSave()
    {

        return \Redirect::route('hipjam_showbrands');
    }

    public function editBrand($id)
    {
        error_log('editBrand');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = \Brand::find($id);

        $data['edit'] = true;
        $data['is_activation'] = false;

        return \View::make('hipjam.addbrand')->with('data', $data);
    }

    public function activateBrand($id)
    {
        error_log('editBrand');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = \Brand::find($id);

        $data['edit'] = false;
        $data['is_activation'] = true;

        return \View::make('hipjam.addbrand')->with('data', $data);
    }


    public function venuesAsJson($auth_id){

        // $auth_id = \Input::get('auth_id');

        if($auth_id == "290a58c3-3ccf-497d-90f4-6f97f4baa368") {
            $venues = \Venue::all();
            print_r(json_encode($venues));
        }
        else {
            print_r("Unauthorized");
        }

    }


    private function getErrorActivateMessages()
    {
        return array(
            'min_engaged_length.required' => 'The engaged customer time is required',
            'max_engaged_length.required' => 'The engaged customer time is required',
            'min_session_length.required' => 'The session time is required',
            'max_session_length.required' => 'The session time is required',
        );
    }

    private function getActivateRules()
    {
        return array(
            'min_engaged_length'          => 'required',
            'max_engaged_length'          => 'required',
            'max_session_length'       => 'required',
            'min_session_length'       => 'required',
        );
    }

    public function updateActivatedVenuesInTrack($input, $brand)
    {

        $venueObj = new \Venue();
        $venues = $venueObj->getVenuesForUser('hipjam', 1, null, $brand->id, "active");
        foreach ($venues as $venue) {
            error_log("updateActivatedVenuesInTrack venue name " . $venue->sitename);
            $this->saveVenueInTrackDb($venue);
        }
    }

    public function editBrandSave()
    {
        error_log('Hipjam editBrandSave');

        $is_activation = \Input::get('is_activation');
        error_log("Hipjam editBrandSave is_activation = $is_activation");
        // $is_activation = false;
        $id = \Input::get('id');
        $brand = \Brand::find($id);
        $input = \Input::all();

        $messages = $this->getErrorActivateMessages();
        $rules = $this->getActivateRules();
        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return \Redirect::to('hipjam_activatebrand/' . $input["id"])->withErrors($validator)->withInput();
        } else {
            // Save in hiphub DB
            $brand->min_engaged_length = $input["min_engaged_length"];
            $brand->max_engaged_length = $input["max_engaged_length"];
            $brand->min_session_length = $input["min_session_length"];
            $brand->max_session_length = $input["max_session_length"];
            $brand->friendly_name = $input["friendly_name"];
            $brand->avg_basket_value = $input["avg_basket_value"];
            $brand->jam_activated = 1;
            $brand->save();

            if (!is_null($brand->friendly_name)) {
                \Venue::where('brand_id', '=', $brand->id)->update(['friendly_brandname' => $brand->friendly_name]);
            }

            // Update in Track DB
            if (!$is_activation) $this->updateActivatedVenuesInTrack($input, $brand);
            return \Redirect::route('hipjam_showbrands');
        }
    }

    // END BRAND /////////////////////////////////////////////////////////////







    public function showUsers()
    {
        $data = array();
        $data['currentMenuItem'] = "User Management";

        return \View::make('hipjam.showusers')->with('data', $data);
    }

    public function addUser()
    {
        $data = array();
        $data['edit'] = false;
        $data['currentMenuItem'] = "User Management";


        return \View::make('hipjam.adduser')->with('data', $data);
    }

    public function editUser($id)
    {
        $data = array();
        $data['edit'] = false;
        $data['currentMenuItem'] = "User Management";


        return \View::make('hipjam.adduser')->with('data', $data);
    }


    public function showVenues($json = null)
    {
        error_log("showVenues");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";

        $venue = new \Venue();

        if (\User::isVicinity()) {
            $vicinity_brands = \Brand::whereRaw('id = 165 OR parent_brand = 165')->get();
            $vbrands = array();
            foreach ($vicinity_brands as $brand) {
                array_push($vbrands, $brand->id);
            }
            $vbrandsarray = implode(",", $vbrands);
            $venues = \Venue::whereRaw("brand_id IN ($vbrandsarray) AND jam_activated = 1 ")->orderBy('sitename', 'ASC')->get();

        } else {
            $venues = $venue->getVenuesForUser('hipjam', 1, null, null, "active");
        }


        foreach ($venues as $venue) {
            if ($venue->ap_active == 0) {
                $venue["status"] = '<span style="color:red">Inactive</span>';
            } else {
                $venue["status"] = '<span style="color:green">Active</span>';
            }
            if ($venue->server) {
                $venue["hostname"] = $venue->server->hostname;
            } else {
                $venue["hostname"] = "Server No longer exists";
            }
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        $data['venuesJson'] = json_encode($venues);

        $data['currentMenuItem'] = "Venue Management";

        $data['user'] = $venue->getUserData();
        $data['is_vicinity'] = \User::isVicinity();
        //print_r($data['user']); die();

        if ($json) {
            error_log("showDashboard : returning json");
            return \Response::json($data['venuesJson']);
        } else {
            error_log("showDashboard : returning NON json");
            return \View::make('hipjam.showvenues')->with('data', $data);
        }


        return \View::make('hipjam.showvenues')->with('data', $data);
    }


    public function getInactiveVenues()
    {
        $data = array();
        $venue = new \Venue();
        // $venues = null;

        if (\User::isVicinity()) {
            $vicinity_brands = \Brand::whereRaw('id = 165 OR parent_brand = 165')->get();
            $vbrands = array();
            foreach ($vicinity_brands as $brand) {
                array_push($vbrands, $brand->id);
            }
            $vbrandsarray = implode(",", $vbrands);
            $venues = \Venue::whereRaw("brand_id IN ($vbrandsarray) AND jam_activated = 0")->get();
        } else {
            $venues = $venue->getVenuesForUser('hipjam', 1, null, null, "inactive");
        }

        $venuesJason = json_encode($venues);
        // error_log("getInactiveVenues jam venues = $venues");

        return \Response::json($venuesJason);
    }

    // ----------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------
    // ############################################ VICINITY AMENDS #############################################
    // ----------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------

    public function vicinityVenue($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = true;
        $data['is_activation'] = false;
        $edit = true;
        $vpnip = new \Vpnip;
        //$data['vpnips']  = $vpnip->getVpnips();
        $venue = \Venue::find($id);
        $data['venue'] = $venue;
        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]);
        foreach ($data['venue'] as $key => $value) {
            error_log("TTT : $key => $value");
        };

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $destinationPath = $assetsdir->value . 'track/images';
        $data['previewurl'] = $destinationPath;

        $sensors =  \Sensor::where("venue_id", "like", $data['venue']["id"])->orderBy('id', 'DESC')->get();
        $data['sensors'] = $sensors;
        $data['sitename'] = $venue->sitename;




        if ($venue->ap_active == true) {
            $data['ap_active_checked'] = "checked";
            $data['ap_inactive_checked'] = "";
        } else {
            $data['ap_active_checked'] = "";
            $data['ap_inactive_checked'] = "checked";
        }
        $data['timezoneselect'] = $this->getTimezoneSelect($data['venue']['timezone']);

        $venue = new \Venue();
        $data['user'] = $venue->getUserData();
        $data['billboards'] = \Venue::where('track_type', '=', "billboard")->get();

        $sanitized_sitename = \Venue::find($id)->sitename;
        $split_for_count = explode(' ', $sanitized_sitename);
            $brand_concat = substr($split_for_count[0], 0, 15);
            $venue_concat = substr($split_for_count[1], 0, 20);
        if ($sensors->count() == 0) {
            $data['sensor_name'] = $brand_concat.$venue_concat;
        } else {
            $data['sensor_name'] = $brand_concat.$venue_concat;
        }

        return \View::make('hipjam.vicinityvenue')->with('data', $data);
    }

    public function vicinityVenueActivate($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = true;
        $data['is_activation'] = false;
        $edit = true;
        $vpnip = new \Vpnip;
        //$data['vpnips']  = $vpnip->getVpnips();
        $data['venue'] = \Venue::find($id);
        $data['old_sitename'] = $data['venue']["sitename"];
        $data['sitename'] = preg_replace("/_| /", '', $data['venue']['sitename']);
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]);
        foreach ($data['venue'] as $key => $value) {
            error_log("TTT : $key => $value");
        };

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $destinationPath = $assetsdir->value . 'track/images';
        $data['previewurl'] = $destinationPath;

        $sensors =  \Sensor::where("venue_id", "like", $data['venue']["id"])->orderBy('id', 'DESC')->get();
        $data['sensors'] = $sensors;


        $data['timezoneselect'] = $this->getTimezoneSelect($data['venue']['timezone']);

        $venue = new \Venue();
        $data['user'] = $venue->getUserData();
        $data['billboards'] = \Venue::where('track_type', '=', "billboard")->get();
        $sanitized_sitename = preg_replace("/[^a-zA-Z]+/", "", \Venue::find($id)->sitename);
        $sanitized_sitename = str_replace("VICINITY", "", $sanitized_sitename);
        if ($venue->ap_active == true) {
            $data['ap_active_checked'] = "checked";
            $data['ap_inactive_checked'] = "";
        } else {
            $data['ap_active_checked'] = "";
            $data['ap_inactive_checked'] = "checked";
        }
        if ($sensors->count() == 0) {
            $data['sensor_name'] = $sanitized_sitename;
        } else {
            $data['sensor_name'] = $sanitized_sitename.$sensors->count();
        }

        return \View::make('hipjam.vicinityvenue')->with('data', $data);
    }

    public function venueConfig($id) {
        $obj = [];
        $venue =  \Venue::find($id);
        $obj['venue'] = $venue;
        $obj['brand'] = $venue->brand;
        return \Response::json($obj);
    }

    public function linkedBillboards() {
        $venues =  \Venue::whereRaw('linked_billboard != 0 AND linked_billboard IS NOT NULL')->get();
        return \Response::json($venues);
    }

    public function updateVicinityVenue()
    {
        $form_data = json_decode(\Input::get("data"));
        $venue =  \Venue::find($form_data->id);
        $venue->jam_activated = 1;

        $venue->track_slug = $form_data->track_slug; // Track Venue ID
        $venue->track_type = $form_data->track_type; // Billboard / Retail
        $venue->linked_billboard = $form_data->linked_billboard; // Venue linked to billboard
        $venue->advertising_cost = $form_data->advertising_cost; // Venue Longitude (GPS co-ord)
        $venue->track_server_location = $form_data->track_server_location; // Vicinity Server ID
        $venue->ap_active = $form_data->ap_active;

        $venue->track_ssid = $form_data->track_ssid; // Track WiFi SSID
        $venue->track_password = $form_data->track_password; // Track WiFi Password

        $venue->latitude = $form_data->latitude; // Venue Latitude (GPS co-ord)
        $venue->longitude = $form_data->longitude; // Venue Longitude (GPS co-ord)

        $venue->timezone = $form_data->timezone; // Time Zone
        $venue->jam_activated = true;
        $result = $venue->save();



        // Sync new venue data with TRACK
        $this->saveVenueInTrackDb($venue);

        print_r($result);
    }

    public function vicinityAddUser() {
        $data = array();
        $data['user'] =  $user = new \User();
        $data['edit'] = false;
        $data['currentMenuItem'] = "User Admin";

        $levels = \Level::All();
        $level_names = array();
        foreach($levels as $level) {
            $level_names[$level->code] = $level->name;
        }
        $data['level_names'] = $level_names;

        $brands = \Brand::All();
        $data['allbrands'] = $brands;
        $data['brandArray'] = array(); //TAKE THIS OUT WHEN DONE

        $data['products']['HipWIFI'] = false;
        $data['products']['HipRM'] = false;
        $data['products']['HipJAM'] = false;
        $data['products']['HipENGAGE'] = false;
        $data['products']['HipREPORTS'] = false;
        $data['products']['HipTnA'] = false;

        $data['permissions']['ques_rw'] = false;
        $data['permissions']['media_rw'] = false;
        $data['permissions']['uru_rw'] = false;
        $data['permissions']['rep_rw'] = false;

        $data['brands'] = \DB::table('brands')
                ->select("*")
                ->where('brands.parent_brand', '=', 165)
                ->orWhere('id', 165)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get();

        $data['venues'] = \Venue::where('brand_id', '=', '165')->get();

        $data['countries'] = \Countrie::All();

        $data['user']->level_code = "Brand Admin";

        // foreach($data['allbrands'] as $brand) {
        //     error_log("============= " . $brand->name);
        // }

        return \View::make('hipjam.vicinityadduser')->with('data', $data);
    }

    public function vicinitySettings()
    {
        // 1 & 45
        $data = array();
        $brand = \Brand::where('id', 165)->first();
        $users = $brand->users;
        $data["users"] = $users;
        $data['currentMenuItem'] = "Settings";



        return \View::make('hipjam.vicinitysettings')->with('data', $data);
    }

    // ----------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------
    // ############################################ VICINITY AMENDS #############################################
    // ----------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------

    public function addVenue()
    {
        error_log("addVenue");

        // $response = file_get_contents('http://dev.doteleven.co/kauai_seapoint');
        // error_log("TESTING API : $response");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = false;

        $data['venue'] = new \Venue();

        $othervars = array("name" => "Other", "selected" => "selected=\"selected\"");
        $mikvars = array("name" => "Mikrotik", "selected" => "");
        $data["device_types"] = array($othervars, $mikvars);

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipjam');

        $data['ap_active_checked'] = "checked";
        $data['ap_inactive_checked'] = "";

        return \View::make('hipjam.addvenue')->with('data', $data);
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
        $macaddress = \Input::get('macaddress');
        $connection = \Brand::find($brand_id)->remotedb->dbconnection;


        $input['sitename'] = $sitename;
        $timefrom = \Input::get('timefrom');
        $timeto = \Input::get('timeto');

        error_log("addVenueSave : timefrom = $timefrom ======= timeto = $timeto");

        $sitename_exists = \Venue::where("sitename", "like", $sitename)->first();
        if (!is_null($sitename_exists)) {
            $sitename_exists->forceDelete();
        }
        $macaddress_exists = \Venue::where("macaddress", "like", $macaddress)->first();
        if (!is_null($macaddress_exists)) {
            $macaddress_exists->forceDelete();
        }

        $ssid = \Input::get('ssid');
        error_log("editVenueSave : 111 ssid : $ssid");
        if (!$ssid || $ssid == "") {
            $ssid = \Brand::find($brand_id)->ssid;
        } else {
            $ssid = \Input::get('ssid');
        }

        $rules = array(
            'sitename'       => 'required|alpha_num_dash_spaces|unique:venues',
            'macaddress'     => 'required|macaddress_format|unique:venues'
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('hipjam_addvenue')->withErrors($validator)->withInput();
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
            $venue->macaddress = $input['macaddress'];
            $venue->ssid = $ssid;
            $venue->device_type = $input['device_type'];
            $venue->latitude = $input['latitude'];
            $venue->longitude = $input['longitude'];
            $venue->address = $input['address'];
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
            $venue->contact = $input['contact'];
            $venue->notes = $input['notes'];
            $venue->statuscomment = $input['statuscomment'];
            $venue->send_alert_emails = $input['send_alert_emails'];
            $venue->alert_email_address_1 = $input['alert_email_address_1'];
            $venue->alert_email_address_2 = $input['alert_email_address_2'];
            $venue->alert_email_address_3 = $input['alert_email_address_3'];
            $venue->save();

            $venue = $venue->refreshMediaLocation($venue);
            $venue->insertVenueInRadius($venue, $remotedb_id);

            // Update the AP
            if ($input['device_type'] == "Mikrotik") {
                $mikrotik = new \Mikrotik();
                $mikrotik->addVenue($venue);
            }
        }

        return \Redirect::route('hipjam_showvenues');
    }

    public function getTimezoneSelect($venuetimezone)
    {
        $utils = new \Utils();
        $timezoneArray = $utils->getTimeZonesArray();
        $timezoneSelect = "";
        if (!$venuetimezone || $venuetimezone == "") $venuetimezone = "Africa/Johannesburg";

        foreach ($timezoneArray as $timezone) {
            $selected_html = "";
            if ($timezone == $venuetimezone) $selected_html =  'selected';
            $timezoneSelect = $timezoneSelect . '<option value="' . $timezone . '" ' . $selected_html . '>' . $timezone . "</option>";
        }

        return $timezoneSelect;
    }

    public function editVenue($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = true;
        $data['is_activation'] = false;
        $vpnip = new \Vpnip;
        //$data['vpnips']  = $vpnip->getVpnips();
        $data['venue'] = \Venue::find($id);
        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]);
        foreach ($data['venue'] as $key => $value) {
            error_log("TTT : $key => $value");
        };

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $destinationPath = $assetsdir->value . 'track/images';
        $data['previewurl'] = $destinationPath;
        $data['billboards'] = \Venue::where('track_type', '=', "billboard")->get();

        $sensors =  \Sensor::where("venue_id", "like", $data['venue']["id"])->orderBy('id', 'DESC')->get();
        $data['sensors'] = $sensors;

        $data['timezoneselect'] = $this->getTimezoneSelect($data['venue']['timezone']);

        $venue = new \Venue();
        $data['user'] = $venue->getUserData();


        // $data['timezoneselect'] = \View::make('partials.timezoneselect');



        /*$server =  \Sensor::where("venue_id", "like", $data['venue']["id"])->where("code", "like", 'server_track')->orderBy('id', 'DESC')->get();*/
        /*$server =  \Sensor::where("venue_id", "like", $data['venue']["id"])->orderBy('id', 'DESC')->get();
        $data['server'] = $server;*/
        //print_r($data['venue']["location"]); die();
        //$data['location'] = $data['venue']["location"];
        // if($data['venue']->ap_active == 1) {
        //     $data['ap_active_checked'] = "checked";
        //     $data['ap_inactive_checked'] = "";
        // } else {
        //     $data['ap_active_checked'] = "";
        //     $data['ap_inactive_checked'] = "checked";
        // }
        //
        // if($data['venue']->device_type == "Mikrotik") {
        //     $mikvars = array("name" => "Mikrotik", "selected" => "selected=\"selected\"");
        //     $othervars = array("name" => "Other", "selected" => "");
        // } else {
        //     $mikvars = array("name" => "Mikrotik", "selected" => "");
        //     $othervars = array("name" => "Other", "selected" => "selected=\"selected\"");
        // }
        // $data["device_types"] = array($othervars, $mikvars);
        //
        // $servers = \Server::All();
        // $data['allservers'] = $servers;
        //
        // $brand = new \Brand();
        // $data['brands'] = $brand->getBrandsForProduct('hipjam');


        return \View::make('hipjam.addvenue')->with('data', $data);
    }

    public function activateVenue($id)
    {
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = false;
        $data['is_activation'] = true;

        $data['venue'] = \Venue::find($id);
        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]);
        foreach ($data['venue'] as $key => $value) {
            error_log("TTT : $key => $value");
        };

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $destinationPath = $assetsdir->value . 'track/images';
        $data['previewurl'] = $destinationPath;
        $data['billboards'] = \Venue::where('track_type', '=', "billboard")->get();
        $data['timezoneselect'] = $this->getTimezoneSelect($data['venue']['timezone']);

        $sensors =  \Sensor::where("venue_id", "like", $data['venue']["id"])->orderBy('id', 'DESC')->get();
        $data['sensors'] = $sensors;

        $venue = new \Venue();
        $data['user'] = $venue->getUserData();

        return \View::make('hipjam.addvenue')->with('data', $data);
    }

    public function activateVenueSave()
    {
        $utils = new \Utils();
        $id = \Input::get('id');
        $servers = \Server::All();
        $old_sitename = \Input::get('old_sitename');
        error_log("old_sitename : $old_sitename");

        $track_venue_id = \Input::get('venue_id');

        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $input['sitename'] = $sitename;


        $remotedb_id = \Brand::find($brand_id)->remotedb_id;


        $id = \Input::get('id');

        return \Redirect::route('hipjam_showvenues');
    }

    public function editVenueSave()
    {
        $utils = new \Utils();
        $id = \Input::get('id');
        $servers = \Server::All();
        $old_sitename = \Input::get('old_sitename');
        error_log("old_sitename : $old_sitename");

        $track_venue_id = \Input::get('venue_id');

        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $input['sitename'] = $sitename;

        $remotedb_id = \Brand::find($brand_id)->remotedb_id;


        $id = \Input::get('id');

        $vpnip = new \Vpnip();
        $vpnip = $vpnip->getVpnip();
        $objReport = new \Sensor();
        $objData = json_decode(\Input::get());

        $objReport->name = $objData->track_name;
        //$objReport->code = 'server_track';
        $objReport->location = $objData->track_location;
        $objReport->queue = $objData->track_queue;
        $objReport->mac =  $objData->mac;
        $objReport->vpnip_id = $vpnip->id;
        $objReport->min_power = $objData->track_min_power;
        $objReport->max_power = $objData->track_max_power;
        $objReport->venue_id = $objData->venue_id;
        $objReport->venue_location = $objData->venue_location;

        createConfigYml($objReport, $update = true, $oldmac = null);



        //no data to update - venue_id is move to the track server side.
        //$venue =  \Venue::find($id);
        //$venue->track_venue_id = $input['venue_id'];
        //$venue->track_slug = $input['venue_id'];
        //$venue->save();

        /*$venue = $venue->refreshMediaLocation($venue);
        $venue->updateVenueInRadius($venue, $remotedb_id);*/

        // Update the naslookup table on the server here

        return \Redirect::route('hipjam_showvenues');
    }

    public function saveVenueInTrackDb($venue)
    {
        // Get the connection
        $brand = \Brand::find($venue->brand_id);
        \Config::set('database.connections.track.host', $venue->track_server_location);
        \DB::purge('track');
        \DB::reconnect('track');
        $record = array(
            "id" => $venue->id,
            "slug" => $venue->track_slug,
            "name" => $venue->sitename,
            "timezone" => $venue->timezone,
            "min_engaged_length" => $brand->min_engaged_length,
            "max_engaged_length" => $brand->max_engaged_length,
            "min_session_length" => $brand->min_session_length,
            "max_session_length" => $brand->max_session_length,
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
        );
        error_log("saveVenueInTrackDb 10");
        \DB::connection('track')->table("venues")->where('id', '=', $venue->id)->delete();
        error_log("saveVenueInTrackDb 20");
        \DB::connection('track')->table("venues")->insert($record);
        error_log("saveVenueInTrackDb 30");
    }


    //update the track server name and id
    public function editVenueServer()
    {
        error_log("editVenueServer 10");
        $objData = json_decode(\Input::get("newrecord"));
        $id = $objData->id;
        $venue =  \Venue::find($id);
        $venue->jam_activated = 1;
        $venue->track_slug = $objData->track_slug;
        $venue->track_server_location = $objData->track_server_location;
        $venue->track_ssid = $objData->track_ssid;
        $venue->track_password = $objData->track_password;
        $venue->timezone = $objData->timezone;
        $result = $venue->save();

        // Update the venues table on the server
        $this->saveVenueInTrackDb($venue);

        print_r($result); // For some reason the success condition in the javascript will only fir if I do this. Not quite sure why.

    }


    public function updateSensordata()
    {

        $objData   = json_decode(\Input::get("newrecord"));
        $objReport = \Sensor::where('id', $objData->updateNum)->first();

        //$objReport->name = $objData->add_name;
        //$objReport->code = $objData->sensor_id;
        //$objReport->mac = $objData->sensor_mac;
        $objReport->venue_location = $objData->venue_location;
        $objReport->xcoord = $objData->x_cordinate;
        $objReport->ycoord = $objData->y_cordinate;
        $save = $objReport->save();

        if ($save) {
            $lastInsertedID = $objData->updateNum; //$objReport->id;

            $reportJson =  \Sensor::where('id', $lastInsertedID)->get();
            foreach ($reportJson as $value) {

                $rows = '<tr id="rowCount' . $value->id . '"><td class="sensor_name"> <div id="add_name' . $value->id . '" class="form-control no-radius" >' . $value->name . '</div> </td><td class="sensor_location"> <div id="sensor_location' . $value->id . '" class="form-control no-radius" >' . $value->location . '</div> </td><td class="sensor_mac"> <div id="sensor_mac' . $value->id . '" class="form-control no-radius" >' . $value->mac . '</div> </td><td class="x_cordinate"> <input id="x_cordinate' . $value->id . '" name="x_cordinate" class="form-control no-radius" value="' . $value->xcoord . '" placeholder="X Cordinate" type="text" readonly> </td><td class="y_cordinate"> <input id="y_cordinate' . $value->id . '" name="y_cordinate" value="' . $value->ycoord . '" class="form-control no-radius" placeholder="Y Cordinate" type="text" readonly> </td><td><a onclick="setxyImage(' . $value->id . ')"  class="btn btn-default btn-delete btn-sm">Set XY</a><a id="addreportuser" onclick="updateRow(' . $value->id . ');" class="btn btn-default btn-delete btn-sm">Update</a><a href="javascript:void(0);" onclick="removeRow(' . $value->id . ');" class="btn btn-default btn-delete btn-sm" >Delete</a></td></tr> ';
            }
        }
        $data = array('row' => $rows);
        print_r(json_encode($data));
    }

    public function updateInsertSensorInTrack($scannerObj)
    {

        // Add scanner details to track db
        $track_server_location = \Venue::find($scannerObj->venue_id)->track_server_location;
        error_log("addSvrScannerdata : track_server_location = $track_server_location");
        error_log("addSvrScannerdata : id = " . $scannerObj->id);

        if (\User::isVicinity()) {
            $track_server_location = 'tracks03.hipzone.co.za';
        }


        // Get the connection
        \Config::set('database.connections.track.host', $track_server_location);
        \DB::purge('track');
        \DB::reconnect('track');

        // Build record. Also need to add:
        // | service_version | string
        // | type            | string
        // | meraki          | integer
        // | meraki_secret   | string
        // | power_offset    | integer
        $record = array(
            "id" => $scannerObj->id,
            "venue_id" => $scannerObj->venue_id,
            "name" => $scannerObj->name,
            "location" => $scannerObj->location,
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
            "mac_address" => $scannerObj->mac,
            "queue" => $scannerObj->queue,
            "min_power" => $scannerObj->min_power,
            "max_power" => $scannerObj->max_power
        );
        \DB::connection('track')->table("scanners")->where('id', '=', $scannerObj->id)->delete();
        \DB::connection('track')->table("scanners")->insert($record);
    }

    public function deleteSensorInTrack($scannerObj)
    {

        $track_server_location = \Venue::find($scannerObj->venue_id)->track_server_location;
        error_log("deleteSensorInTrack : track_server_location = $track_server_location");
        error_log("deleteSensorInTrack : id = " . $scannerObj->id);

        // Get the connection
        \Config::set('database.connections.track.host', $track_server_location);
        \DB::purge('track');
        \DB::reconnect('track');
        \DB::connection('track')->table("scanners")->where('id', '=', $scannerObj->id)->delete();
    }


    public function monitorSensors()
    {
        $data = array();
        $sensor = new \Sensor();
        $data['currentMenuItem'] = "Sensor Monitoring";
        $venue = new \Venue();
        $data['venues'] = $venue->getVenuesForUser('hipjam', null, null, null, "active");
        //dd(count($data['details']['sensors']));
        //$brandnames = array();

        return \View::make('hipjam.showmonitoring')->with('data', $data);
    }

    public function getVenueSensors()
    {
        $input = json_decode(\Input::get("sentData"));
        $sensor = new \Sensor();
        $sensordata = $sensor->getSensorsForVenue($input);
        return $sensordata;
    }



    // DEPRECATED
    // public function addChapSecretEntry($scannername, $vpnip)
    // {
    //     $chapsecentry = file_get_contents('/home/mikrotik/deployment/templates/sensors/chapsecentry');
    //     $first = str_replace("scannername", $scannername, $chapsecentry);
    //     $second = str_replace("vpnip", $vpnip, $first);
    //     $connect = ftp_connect('vpn.hipzone.co.za');
    //     $login  = ftp_login($connect, "sensor", "s3ns0r");
    //     ftp_pasv($connect, true);
    //     ftp_chdir($connect, "/etc/ppp/");
    //     $chapsecret = fopen('/home/mikrotik/deployment/templates/sensors/chapsecret', 'a');
    //     ftp_fget($connect, $chapsecret, 'chap-secrets', FTP_BINARY);
    //     fwrite($chapsecret, $second);
    //     fclose($chapsecret);
    //     $chapsecret2 = fopen('/home/mikrotik/deployment/templates/sensors/chapsecret', 'r');
    //     ftp_fput($connect, 'chap-secrets', $chapsecret2, FTP_BINARY);
    //     unlink('/home/mikrotik/deployment/templates/sensors/chapsecret');
    // }

    public function updateChapSecretEntry($oldscannername, $newscannername)
    {
        $connect = ftp_connect('vpn.hipzone.co.za');
        $login  = ftp_login($connect, "sensor", "s3ns0r");
        ftp_pasv($connect, true);
        ftp_chdir($connect, "/etc/ppp/");
        $path = '/home/mikrotik/deployment/templates/sensors/updatechapsecret';  //create an empty file
        $path2 = '/home/mikrotik/deployment/templates/sensors/updatechapsecret2';
        $updatechapsecret = fopen($path, 'w');                                                     // open the empty file and assign it to an handler
        ftp_fget($connect, $updatechapsecret, 'chap-secrets', FTP_BINARY);             // get the chap-secret file from the vpn server and put in the empty path created
        $file = file_get_contents($path);                                                                  // get the content of the file which now contains the server chap-secret entry
        $update = str_replace($oldscannername, $newscannername, $file);              // replace the old scanner name in the entry with the new scanner name
        $updatechapsecret2 = fopen($path2, 'w');
        fwrite($updatechapsecret2, $update);                                                          // now copy the modify entry into the initially created file and overwrite it
        fclose($updatechapsecret);
        fclose($updatechapsecret2);                                                                            // close the file
        $modchapsecret = fopen($path2, 'r');                                                         // open the file again in read only mode and assign it to an handler
        ftp_fput($connect, 'chap-secrets', $modchapsecret, FTP_BINARY);                 // place the handler back on the vpn server, overwritting the existing chap-secret file
        unlink($path);                                                                                             // delete the file path used on the local server for the manipulations.
        unlink($path2);
    }


    public function createConfigYml($objReport, $update, $oldmac)
    {
        //This function creates the config.yml and batmanvpn files and then places it on the vpn server from where a sensor will fetch it.
        $venue = new \Venue();
        $vpnip = new \Vpnip();
        $connect = ftp_connect('vpn.hipzone.co.za');
        $login  = ftp_login($connect, "sensor", "s3ns0r");
        ftp_pasv($connect, true);  //add this function if behind firewall.
        $tempconfigyml = "/home/mikrotik/deployment/templates/sensors/" . $objReport->mac . "tempyml";
        $tempbatman = "/home/mikrotik/deployment/templates/sensors/" . $objReport->mac . "tempbatman";

        if ($update == true) {
            //dd($oldmac);
            $filepathyml = "sensors/" . $oldmac . ".yml";
            $filepathvpn = "sensors/" . $oldmac . "vpn";
            $delete = ftp_delete($connect, $filepathyml);
            $delete = ftp_delete($connect, $filepathvpn);
            //dd($delete);

        }
        $venueObj = $venue->find($objReport->venue_id);
        $track_slug = $venueObj->track_slug;
        $track_yml_venue = str_replace(' ', '', $venueObj->sitename);
        $configyml = file_get_contents('/home/mikrotik/deployment/templates/sensors/configyml');
        $first = str_replace("venuename", $track_yml_venue, $configyml);

        $second = str_replace("wifissid", $venueObj->track_ssid, $first);
        $third = str_replace("wifipassword", $venueObj->track_password, $second);

        $fourth = str_replace("queuename", $objReport->queue, $third);
        $dest = "sensors/" . $objReport->mac . ".yml";
        file_put_contents($tempconfigyml, $fourth);
        $createdconfigyml =  fopen($tempconfigyml, 'r');

        //modify the batmanvpn file;
        $batmanvpn = file_get_contents('/home/mikrotik/deployment/templates/sensors/batmanvpn');
        $modbatmanvpn = str_replace("scannername", $objReport->name, $batmanvpn);
        file_put_contents($tempbatman, $modbatmanvpn);
        $createdbatmanvpn =  fopen($tempbatman, 'r');
        $destbatman = "sensors/" . $objReport->mac . "vpn";
        ftp_fput($connect, $dest, $createdconfigyml, FTP_BINARY);
        ftp_fput($connect, $destbatman, $createdbatmanvpn, FTP_BINARY);



        ftp_close($connect);
        unlink($tempconfigyml);
        unlink($tempbatman);
        //dd($third);

        //The below line calls the function that creates the chap-secret entry for the sensor on the vpn server.
        // if (!$update) {
        //     $vpnipaddress = $vpnip->getIpAddress($objReport->vpnip_id);
        //     $this->addChapSecretEntry($objReport->name, $vpnipaddress);
        // }

    }

    public function executeKeyGeneration($track_slug)
    {
        $server = "vpn.hipzone.co.za";
        $username = "sensor";
        $port = "1759";
        $command = 'sh /home/sensor/ftp/openvpn/create_keys.bash ' . $track_slug;
        $cmd_string = "ssh -p " . $port . " " . $username . "@" . $server . " " . $command;
        exec($cmd_string, $output);
    }

    public function deleteScannerMeta($scannerObj)
    {
        $macaddress = $scannerObj->mac;
        $filepathyml = "sensors/" . $macaddress . ".yml";
        $filepathvpn = "sensors/" . $macaddress . "vpn";
        $connect = ftp_connect('vpn.hipzone.co.za');
        $login = ftp_login($connect, 'sensor', 's3ns0r');
        ftp_pasv($connect, true);
        // try {
        //     $deleteyml = ftp_delete($connect, $filepathyml);
        //     $deletevpn = ftp_delete($connect, $filepathvpn);
        // }
        // catch (exception $e) {
        //     //code to handle the exception
        // }

        $chapsecentry = file_get_contents('/home/mikrotik/deployment/templates/sensors/chapsecentry');
        $first = str_replace("scannername", $scannerObj->name, $chapsecentry);
        $second = str_replace("vpnip", $scannerObj->vpnip->ip_address, $first);
        $del = fopen('/home/mikrotik/deployment/templates/sensors/chapsecentrydel', 'w');
        fwrite($del, $second);
        fclose($del);
        $main = fopen('/home/mikrotik/deployment/templates/sensors/chap-secretsdel', 'w');
        $main2 = fopen('/home/mikrotik/deployment/templates/sensors/chap-secretsdel2', 'w');
        $remove = file_get_contents('/home/mikrotik/deployment/templates/sensors/chapsecentrydel');
        ftp_chdir($connect, "/etc/ppp/");
        // ftp_fget($connect, $main, 'chap-secrets', FTP_BINARY);
        fclose($main);
        $file = file_get_contents('/home/mikrotik/deployment/templates/sensors/chap-secretsdel');
        $delete = str_replace($remove, '', $file);
        fwrite($main2, $delete);
        fclose($main2);
        $deletedone = fopen('/home/mikrotik/deployment/templates/sensors/chap-secretsdel2', 'r');
        // ftp_fput($connect, 'chap-secrets', $deletedone, FTP_BINARY);
        unlink('/home/mikrotik/deployment/templates/sensors/chap-secretsdel');
        unlink('/home/mikrotik/deployment/templates/sensors/chap-secretsdel2');
        $vpnip = new \Vpnip();
        $vpnip->unsetVpnip($scannerObj->id, $scannerObj->vpnip_id);
    }


    public function addSvrScannerdata()
    {
        $objData   = json_decode(\Input::get("newrecord"));
        $objDataArray = (array) $objData; //used for the validation piece
        $objReport = new \Sensor();
        $vpnip = new \Vpnip();
        $vpnip = $vpnip->getVpnip();
        /*$venue = new \Venue();
        $venueObj = $venue->find($objData->venue_id);
        $track_slug = $venueObj->track_slug;*/

        $rules = array(
            'track_name'  => 'required|alpha_num',
            'mac'    => 'required|macaddress_format|unique:sensors,mac',
            'track_min_power' => 'required|integer',
            'track_max_power' => 'required|integer',
        );

        $messages = array(
            'track_name.alpha_num' => 'Please enter name having alphabets and numbers only',
            'mac.macadress_format' => 'Please enter a correct mac-address',
            'mac.macadress_format' => 'This mac-address has been used',
            'track_min_power.integer' => 'Please enter a number for min power',
            'track_max_power.integer' => 'Please enter a number for max power',
        );
// ADDBACKIN
        // $validator = \Validator::make($objDataArray, $rules, $messages);
        // if ($validator->fails()) {
        //     $message = array('msg' => $validator->messages(), 'status' => '422');
        //     return $message;
        // }

        $objReport->name = $objData->track_name;
        //$objReport->code = 'server_track';
        $objReport->location = $objData->track_location;
        $objReport->queue = $objData->track_queue;
        $objReport->mac =  $objData->mac;
        $objReport->vpnip_id = $vpnip->id;
        $objReport->min_power = $objData->track_min_power;
        $objReport->max_power = $objData->track_max_power;
        $objReport->venue_id = $objData->venue_id;
        $objReport->venue_location = $objData->venue_location;

        $this->createConfigYml($objReport, $update = false, $oldmac = null);

        $objReport->save();

        $this->updateInsertSensorInTrack($objReport);


        $lastInsertedID = $objReport->id;
        $vpnip->setVpnip($objReport->id, $objReport->vpnip_id);


        $reportJson =  \Sensor::where('id', $lastInsertedID)->get();
        foreach ($reportJson as $value) {

            $rows = '<tr id="row' . $value->id . '"><td>
                        <input id="track_name' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Name" value="' . $value->name . '">
                          </td>
                          <td>
                            <input id="track_location' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Location" value="' . $value->location . '">
                          </td>
                          <td>
                            <input id="sensor_mac' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Mac Address" value="' . $value->mac . '">
                          </td>
                          <td>
                            <input id="track_queue' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Queue" value="' . $value->queue . '">
                          </td>
                          <td>
                            <input id="track_min_power' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Min Power" value="' . $value->min_power . '">
                          </td>
                          <td>
                            <input id="track_max_power' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Max Power" value="' . $value->max_power . '">
                          </td>
                          <td>
                            <input id="sensor_vpnip' . $value->id . '" class="form-control no-radius" readonly=readonly type="text" required autocomplete="off" placeholder="Max Power" value="' . $value->vpnip->ip_address . '">
                          </td>

                          <td width="17%">
                          <a onclick="updateServerRow(' . $value->id . ');" class="btn btn-primary no-radius btn-delete btn-sm">Update</a><a href="javascript:void(0);" onclick="removeServerRow(' . $value->id . ');" class="btn btn-primary no-radius btn-delete btn-sm" >Delete</a>
                          </td></tr> ';
        }
        $data = array('row' => $rows);


        $command = 'ssh  -v -i /var/www/.ssh/id_rsa root@vpn.hipzone.co.za -p 1759 "create_keys ' . $objReport->name . '" 2>&1';
        // $command = 'pwd';
        $output = shell_exec($command);

        // shell_exec($command);

        print_r(json_encode($data));
        // print_r($output);
    }

    public function updateSvrScannerdata()
    {

        $objData   = json_decode(\Input::get("newrecord"));
        $objDataArray = (array) $objData; //used for the validation piece
        $objReport = \Sensor::where('id', $objData->updateNum)->first();
        $oldmac = $objReport->mac;
        $oldscannername = $objReport->name;
        $rules_mac_diff = array(
            'track_name'  => 'required|alpha_num',
            'mac'    => 'required|macaddress_format|unique:sensors,mac',
            'track_min_power' => 'required|integer',
            'track_max_power' => 'required|integer',
        );

        $messages_mac_diff = array(
            'track_name.alpha_num' => 'Please enter name having alphabets and numbers only',
            'mac.macadress_format' => 'Please enter a correct mac-address',
            'mac.unique' => 'This mac-address has been used',
            'mac.required' => 'Please enter a valid mac-address',
            'track_min_power.integer' => 'Please enter a number for min power',
            'track_max_power.integer' => 'Please enter a number for max power',
        );

        $rules_mac_same = array(
            'track_name'  => 'required|alpha_num',
            'track_min_power' => 'required|integer',
            'track_max_power' => 'required|integer',
        );

        $messages_mac_same = array(
            'track_name.alpha_num' => 'Please enter name having alphabets and numbers only',
            'mac.macadress_format' => 'Please enter a correct mac-address',
            'track_min_power.integer' => 'Please enter a number for min power',
            'track_max_power.integer' => 'Please enter a number for max power',
        );

        if ($oldmac == $objData->mac) {
            $validator = \Validator::make($objDataArray, $rules_mac_same, $messages_mac_same);
        } else {
            $validator = \Validator::make($objDataArray, $rules_mac_diff, $messages_mac_diff);
        }
        if ($validator->fails()) {
            $message = array('msg' => $validator->messages(), 'status' => '422');
            return $message;
        }
        $objReport->name = $objData->track_name;
        //$objReport->code = 'server_track';
        $objReport->location = $objData->track_location;
        $objReport->queue = $objData->track_queue;
        $objReport->mac = $objData->mac;
        $objReport->min_power = $objData->track_min_power;
        $objReport->max_power = $objData->track_max_power;
        $objReport->venue_id = $objData->venue_id;
        $objReport->venue_location = $objData->venue_location;

        $this->createConfigYml($objReport, $update = true, $oldmac);
        // if ($oldscannername != $objData->track_name) {
        //     $this->updateChapSecretEntry($oldscannername, $objReport->name);
        // }
        $save = $objReport->save();


        if ($save) {
            $this->updateInsertSensorInTrack($objReport);
            $lastInsertedID = $objData->updateNum; //$objReport->id;

            $reportJson =  \Sensor::where('id', $lastInsertedID)->get();
            foreach ($reportJson as $value) {

                $rows = '<tr id="row' . $value->id . '"><td>
                            <input id="track_name' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Name" value="' . $value->name . '">
                          </td>
                          <td>
                            <input id="track_location' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Location" value="' . $value->location . '">
                          </td>
                          <td>
                            <input id="sensor_mac' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Mac Address" value="">
                          </td>
                          <td>
                            <input id="track_queue' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Queue" value="' . $value->queue . '">
                          </td>
                          <td>
                            <input id="track_min_power' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Min Power" value="' . $value->min_power . '">
                          </td>
                          <td>
                            <input id="track_max_power' . $value->id . '" class="form-control no-radius" type="text" required autocomplete="off" placeholder="Max Power" value="' . $value->max_power . '">
                          </td>
                          <td width="17%">
                          <a onclick="updateServerRow(' . $value->id . ');" class="btn btn-primary no-radius btn-delete btn-sm">Update</a><a href="javascript:void(0);" onclick="removeServerRow(' . $value->id . ');" class="btn btn-primary no-radius btn-delete btn-sm" >Delete</a>
                          </td></tr> ';
            }
        }
        $data = array('row' => $rows);
        print_r(json_encode($data));
    }

    public function deleteSvrScannerdata()
    {
        $record   = \Input::get("record");
        $objData = \Sensor::find($record);
        error_log("deleteSvrScannerdata id = " . $objData->id);
        $this->deleteScannerMeta($objData);
        $delete = $objData->delete();
        $vpnip = new \Vpnip();


        if ($delete) {

            $this->deleteSensorInTrack($objData);
            // Delete from track
            $msg = 'deleted';
        } else {
            $msg = 'not';
        }

        $data = array('msg' => $msg);
        print_r(json_encode($data));
    }

    public function showProspects()
    {
        $data = array();
        $data['currentMenuItem'] = "Prospects Register";

        return \View::make('hipjam.showprospects')->with('data', $data);
    }

    public function addProspect()
    {
        $data = array();
        $data['edit'] = false;
        $data['currentMenuItem'] = "Prospects Register";


        return \View::make('hipjam.addprospect')->with('data', $data);
    }

    public function editProspect($id)
    {
        $data = array();
        $data['edit'] = false;
        $data['currentMenuItem'] = "Prospects Register";


        return \View::make('hipjam.addprospect')->with('data', $data);
    }




    public function testApi()
    {

        $data = array();

        error_log("HipjamController : HipjamController : 10");

        // Process GET //////////////////////////////////
        $data_string = "abc";
        $data['getresponse'] = "Nothing yet GET";
        $ch = curl_init('http://dev.doteleven.co/hiphub/venue/kauai_seapoint');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        error_log("HipjamController : HipjamController : 15");

        $data['getresponse'] = curl_exec($ch); // json_encode(curl_exec($ch));
        error_log("HipjamController : HipjamController : 20 " . $data['getresponse']);



        // Process GET //////////////////////////////////
        $data['postresponse'] = "Nothing yet POST";
        //API Url
        $url = 'http://dev.doteleven.co/hiphub/venue/create';

        //Initiate cURL.
        $ch = curl_init($url);

        //The JSON data.
        $jsonData = array(
            "slug" => "gavin_test",
            "name" => "Gavin test venue",
            "address" => "15 Ray Mansions",
            "coordinates" => "-33.9564041,18.4621044",
            "franchise" => "gavin_franchise",
            "timezone" => "Africa/Johannesburg"
        );

        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //Execute the request
        $result = curl_exec($ch);


        return \View::make('hipjam.testApi')->with('data', $data);
        return $resopnse;
    }


    public function deleteVenue($id)
    {
        error_log("deleteVenue");
        $venue = \Venue::find($id);
        $brand_id = $venue->brand_id;
        $remotedb_id = \Brand::find($brand_id)->remotedb_id;

        if ($venue) {
            $venue->delete();
            $venue->deleteVenueInRadius($venue, $remotedb_id);
            $mikrotik = new \Mikrotik();
            $mikrotik->deleteVenue($venue);
        }

        return \Redirect::route('hipjam_showdashboard', ['json' => 1]);
    }


    public function viewVenue($json = null, $name = null)
    {
        /*$data = array();
        //$data['edit'] = false;
        $data['currentMenuItem'] = "Dashboard";

        return \View::make('hipjam.viewvenue')->with('data', $data);*/
        //return \View::make('hipjam.chart')->with('data', $data);

        /*$data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('hipjam.showdashboard')->with('data', $data);*/

        error_log("showVenues");

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['apisitename'] = $name;
        $data['apivenueid'] = $json;
        $venue = \DB::table('venues')->select("id", "sitename", "location", "track_slug", "track_type", "linked_billboard", "brand_id", "advertising_cost")->where('id', '=', $json)->first();
        $data['venue_type'] = $venue->track_type;
        $data['venue'] = $venue->sitename;
        $data['venue_id'] = $venue->id;
        $data['track_slugname'] = $venue->track_slug;
        //$data['location'] = $venue->location;

        $assetsdiry = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['fullpathimage'] = $assetsdiry->value . 'track/images/' . $venue->location . '.jpg';

        $date_from = \Input::get('date_from');
        $date_to = \Input::get('date_to');

        if ($date_from == null) {
            $date_from = date('Y-m-d',strtotime('last monday'));
        }

        if ($date_to == null) {
            $date_to = date('Y-m-d',strtotime('next sunday'));
        }

        if ($venue->linked_billboard != '' && $venue->linked_billboard != null) {
            $data['exposed_today'] = \DB::select("
            SELECT count(id) count FROM track_seen_mac_address tsma
            INNER JOIN (SELECT mac_address FROM track_seen_mac_address where venue_id = ".$venue->linked_billboard." and date_seen = CURDATE()) INQ ON tsma.mac_address = INQ.mac_address
            WHERE tsma.venue_id = ".$venue->id." and tsma.date_seen = CURDATE()
            ");

            $data['exposed_today'] = array_values($data['exposed_today']);

            $data['exposed_week'] = \DB::select("
            SELECT count(id) count FROM track_seen_mac_address tsma
            INNER JOIN (SELECT mac_address FROM track_seen_mac_address where venue_id = ".$venue->linked_billboard." and date_seen BETWEEN '$date_from' AND '$date_to') INQ ON tsma.mac_address = INQ.mac_address
            WHERE tsma.venue_id = ".$venue->id." and tsma.date_seen BETWEEN '$date_from' AND '$date_to'
            ");
            $data['exposed_week'] = array_values($data['exposed_week']);

        } else {
            $data['exposed_today'] = \DB::select("SELECT 0 as count");
            $data['exposed_week'] = \DB::select("SELECT 0 as count");
        }

        $data['reach_frequency'] = \DB::select("SELECT ROUND(COALESCE(AVG(seen_times), 0), 2) avg_seen_times FROM (SELECT count(id) seen_times, mac_address, venue_id from track_seen_mac_address where venue_id = ".$venue->id." AND mac_address<>'' AND date_seen BETWEEN '$date_from' AND '$date_to' GROUP BY mac_address) INQ");
        $data['total_conversions'] = \DB::select("SELECT
                                                    count(id) total_conversions
                                                FROM
                                                    track_seen_mac_address tsma
                                                    INNER JOIN ( SELECT DISTINCT
                                                            mac_address mac_addresses_seen_by_venues
                                                        FROM
                                                            track_seen_mac_address tsmaINNER
                                                            INNER JOIN (
                                                                SELECT
                                                                    id linked_venue_id
                                                                FROM
                                                                    venues iv
                                                                WHERE
                                                                    linked_billboard = ".$venue->id.") INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                            WHERE
                                                                tsmaINNER.date_seen BETWEEN '$date_from'
                                                                AND '$date_to') INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                                WHERE
                                                    tsma.venue_id = ".$venue->id."
                                                    AND tsma.date_seen BETWEEN '$date_from'
                                                    AND '$date_to'")[0]->total_conversions;

        $data['total_conversions_today'] = \DB::select("SELECT
                                                    count(id) total_conversions
                                                FROM
                                                    track_seen_mac_address tsma
                                                    INNER JOIN ( SELECT DISTINCT
                                                            mac_address mac_addresses_seen_by_venues
                                                        FROM
                                                            track_seen_mac_address tsmaINNER
                                                            INNER JOIN (
                                                                SELECT
                                                                    id linked_venue_id
                                                                FROM
                                                                    venues iv
                                                                WHERE
                                                                    linked_billboard = ".$venue->id.") INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                            WHERE
                                                                tsmaINNER.date_seen = CURDATE()) INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                                WHERE
                                                    tsma.venue_id = ".$venue->id."
                                                    AND tsma.date_seen = CURDATE()")[0]->total_conversions;



        $linked_venues = \Venue::where('linked_billboard', '=', $venue->id)->pluck('id');
       if (!$linked_venues) {
        $linked_venues = 0;
       }

        $data['strike_time'] = \DB::select("
            SELECT
                ROUND(AVG(time_taken_to_convert) / 60,2) minutes,
                ROUND(AVG(time_taken_to_convert) / 3600,2) hours,
                ROUND(AVG(time_taken_to_convert) / 86400,2) days
            FROM (
                SELECT
                    TIMESTAMPDIFF(SECOND, seen_by_billboard, seen_by_venue) time_taken_to_convert
                FROM (
                    SELECT
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id = ".$venue->id." AND date_seen = INQ.date_seen) seen_by_billboard,
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id IN (".$linked_venues.") AND date_seen = INQ.date_seen) seen_by_venue
                    FROM (
                        SELECT
                            count(*) seen_times,
                            mac_address,
                            date_seen
                        FROM
                            track_seen_mac_address tsma
                        WHERE
                            (venue_id = ".$venue->id." OR venue_id IN (".$linked_venues."))
                        AND (date_seen BETWEEN '$date_from' AND '$date_to')
                        GROUP BY mac_address,date_seen HAVING seen_times > 1
                    ) INQ
                ) INQ2 HAVING time_taken_to_convert > 0
            ) INQ3
            ");

            $data['strike_time_today'] = \DB::select("
            SELECT
                ROUND(AVG(time_taken_to_convert) / 60,2) minutes,
                ROUND(AVG(time_taken_to_convert) / 3600,2) hours
            FROM (
                SELECT
                    TIMESTAMPDIFF(SECOND, seen_by_billboard, seen_by_venue) time_taken_to_convert
                FROM (
                    SELECT
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id = ".$venue->id." AND date_seen = INQ.date_seen) seen_by_billboard,
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id IN (".$linked_venues.") AND date_seen = INQ.date_seen) seen_by_venue
                    FROM (
                        SELECT
                            count(*) seen_times,
                            mac_address,
                            date_seen
                        FROM
                            track_seen_mac_address tsma
                        WHERE
                            (venue_id = ".$venue->id." OR venue_id IN (".$linked_venues."))
                        AND (date_seen = CURDATE())
                        GROUP BY mac_address,date_seen HAVING seen_times > 1
                    ) INQ
                ) INQ2 HAVING time_taken_to_convert > 0
            ) INQ3
            ");

            $data['strike_distance'] = \DB::select("
            SELECT
                ROUND(IFNULL(AVG( 6371 * acos (cos ( radians(billboard_latitude))
                                * cos( radians( venue_latitude ) )
                                * cos( radians( venue_logitude ) - radians(billboard_longitude) )
                                + sin ( radians(billboard_latitude) )
                                * sin( radians( venue_latitude ) )
                                )
                            ), 0), 2) as distance
            FROM
                (
                    SELECT
                        (SELECT latitude FROM venues WHERE id = v.linked_billboard) as billboard_latitude,
                        (SELECT longitude FROM venues WHERE id = v.linked_billboard) as billboard_longitude,
                        latitude as venue_latitude,
                        longitude as venue_logitude
                    FROM
                        venues v
                    WHERE
                        linked_billboard = ".$venue->id."
                ) as inq
        ");

        $data['avg_basket_value'] = \Brand::find($venue->brand_id)->avg_basket_value;
        $data['potential_sales'] = $data['total_conversions'] * $data['avg_basket_value'];

        $data['potential_sales_today'] = $data['total_conversions_today'] * $data['avg_basket_value'];

        if ($data['total_conversions'] != 0) {
            $data['cpa'] = round($venue->advertising_cost / $data['total_conversions'], 2);
            $data['cpa_today'] = round(($venue->advertising_cost / 31) / $data['total_conversions_today'], 2);
        } else {
            $data['cpa'] = 'N/A';
            $data['cpa_today'] = 'N/A';
        }

        if ($venue->advertising_cost && $venue->advertising_cost != 0) {
            $data['roi'] = round(($data['potential_sales'] / $venue->advertising_cost) * 100, 2);
            $data['roi_today'] = round(($data['potential_sales_today'] / ($venue->advertising_cost / 31)) * 100, 2);
        } else {
            $data['roi'] = 'N/A';
            $data['roi_today'] = 'N/A';
        }







        // $venues = \Venue::all();
        /*$venue = new \Venue();
        $venues = $venue->getVenuesForUser('hipjam', 1);//print_r($venues); die();

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
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        $data['venuesJson'] = json_encode($venues);*/

        /*if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($data['venuesJson']);

        } else {*/
        error_log("showDashboard : returning NON json");
        //return \View::make('hipjam.showvenues')->with('data', $data);
        return \View::make('hipjam.viewvenue')->with('data', $data);
        //return \Redirect::route('hipjam_showdashboard', ['json' => 1]);

        /*}*/
    }

    public function viewVicinityVenue($json = null, $name = null) {
         /*$data = array();
        //$data['edit'] = false;
        $data['currentMenuItem'] = "Dashboard";

        return \View::make('hipjam.viewvenue')->with('data', $data);*/
        //return \View::make('hipjam.chart')->with('data', $data);

        /*$data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('hipjam.showdashboard')->with('data', $data);*/

        error_log("showVenues");

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['apisitename'] = $name;
        $data['apivenueid'] = $json;
        $venue = \DB::table('venues')->select("id", "sitename", "location", "track_slug", "track_type", "linked_billboard", "brand_id", "advertising_cost")->where('id', '=', $json)->first();
        $data['venue_type'] = $venue->track_type;
        $data['venue'] = $venue->sitename;
        $data['venue_id'] = $venue->id;
        $data['track_slugname'] = $venue->track_slug;
        //$data['location'] = $venue->location;

        $assetsdiry = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['fullpathimage'] = $assetsdiry->value . 'track/images/' . $venue->location . '.jpg';

        $date_from = \Input::get('date_from');
        $date_to = \Input::get('date_to');

        if ($date_from == null) {
            $date_from = date('Y-m-d',strtotime('last monday'));
        }

        if ($date_to == null) {
            $date_to = date('Y-m-d',strtotime('next sunday'));
        }

        if ($venue->linked_billboard != '' && $venue->linked_billboard != null) {
            $data['exposed_today'] = \DB::select("
            SELECT count(id) count FROM track_seen_mac_address tsma
            INNER JOIN (SELECT mac_address FROM track_seen_mac_address where venue_id = ".$venue->linked_billboard." and date_seen = CURDATE()) INQ ON tsma.mac_address = INQ.mac_address
            WHERE tsma.venue_id = ".$venue->id." and tsma.date_seen = CURDATE()
            ");

            $data['exposed_today'] = array_values($data['exposed_today']);

            $data['exposed_week'] = \DB::select("
            SELECT count(id) count FROM track_seen_mac_address tsma
            INNER JOIN (SELECT mac_address FROM track_seen_mac_address where venue_id = ".$venue->linked_billboard." and date_seen BETWEEN '$date_from' AND '$date_to') INQ ON tsma.mac_address = INQ.mac_address
            WHERE tsma.venue_id = ".$venue->id." and tsma.date_seen BETWEEN '$date_from' AND '$date_to'
            ");
            $data['exposed_week'] = array_values($data['exposed_week']);

        } else {
            $data['exposed_today'] = \DB::select("SELECT 0 as count");
            $data['exposed_week'] = \DB::select("SELECT 0 as count");
        }

        $data['reach_frequency'] = \DB::select("SELECT ROUND(COALESCE(AVG(seen_times), 0), 2) avg_seen_times FROM (SELECT count(id) seen_times, mac_address, venue_id from track_seen_mac_address where venue_id = ".$venue->id." AND mac_address<>'' AND date_seen BETWEEN '$date_from' AND '$date_to' GROUP BY mac_address) INQ");
        $data['total_conversions'] = \DB::select("SELECT
                                                    count(id) total_conversions
                                                FROM
                                                    track_seen_mac_address tsma
                                                    INNER JOIN ( SELECT DISTINCT
                                                            mac_address mac_addresses_seen_by_venues
                                                        FROM
                                                            track_seen_mac_address tsmaINNER
                                                            INNER JOIN (
                                                                SELECT
                                                                    id linked_venue_id
                                                                FROM
                                                                    venues iv
                                                                WHERE
                                                                    linked_billboard = ".$venue->id.") INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                            WHERE
                                                                tsmaINNER.date_seen BETWEEN '$date_from'
                                                                AND '$date_to') INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                                WHERE
                                                    tsma.venue_id = ".$venue->id."
                                                    AND tsma.date_seen BETWEEN '$date_from'
                                                    AND '$date_to'")[0]->total_conversions;

        $data['total_conversions_today'] = \DB::select("SELECT
                                                    count(id) total_conversions
                                                FROM
                                                    track_seen_mac_address tsma
                                                    INNER JOIN ( SELECT DISTINCT
                                                            mac_address mac_addresses_seen_by_venues
                                                        FROM
                                                            track_seen_mac_address tsmaINNER
                                                            INNER JOIN (
                                                                SELECT
                                                                    id linked_venue_id
                                                                FROM
                                                                    venues iv
                                                                WHERE
                                                                    linked_billboard = ".$venue->id.") INQ1 ON INQ1.linked_venue_id = tsmaINNER.venue_id
                                                            WHERE
                                                                tsmaINNER.date_seen = CURDATE()) INQ2 ON INQ2.mac_addresses_seen_by_venues = tsma.mac_address
                                                WHERE
                                                    tsma.venue_id = ".$venue->id."
                                                    AND tsma.date_seen = CURDATE()")[0]->total_conversions;



        $linked_venues = \Venue::where('linked_billboard', '=', $venue->id)->pluck('id');
       if (!$linked_venues) {
        $linked_venues = 0;
       }

        $data['strike_time'] = \DB::select("
            SELECT
                ROUND(AVG(time_taken_to_convert) / 60,2) minutes,
                ROUND(AVG(time_taken_to_convert) / 3600,2) hours,
                ROUND(AVG(time_taken_to_convert) / 86400,2) days
            FROM (
                SELECT
                    TIMESTAMPDIFF(SECOND, seen_by_billboard, seen_by_venue) time_taken_to_convert
                FROM (
                    SELECT
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id = ".$venue->id." AND date_seen = INQ.date_seen) seen_by_billboard,
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id IN (".$linked_venues.") AND date_seen = INQ.date_seen) seen_by_venue
                    FROM (
                        SELECT
                            count(*) seen_times,
                            mac_address,
                            date_seen
                        FROM
                            track_seen_mac_address tsma
                        WHERE
                            (venue_id = ".$venue->id." OR venue_id IN (".$linked_venues."))
                        AND (date_seen BETWEEN '$date_from' AND '$date_to')
                        GROUP BY mac_address,date_seen HAVING seen_times > 1
                    ) INQ
                ) INQ2 HAVING time_taken_to_convert > 0
            ) INQ3
            ");

            $data['strike_time_today'] = \DB::select("
            SELECT
                ROUND(AVG(time_taken_to_convert) / 60,2) minutes,
                ROUND(AVG(time_taken_to_convert) / 3600,2) hours
            FROM (
                SELECT
                    TIMESTAMPDIFF(SECOND, seen_by_billboard, seen_by_venue) time_taken_to_convert
                FROM (
                    SELECT
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id = ".$venue->id." AND date_seen = INQ.date_seen) seen_by_billboard,
                        (SELECT created_at FROM track_seen_mac_address WHERE mac_address = INQ.mac_address AND venue_id IN (".$linked_venues.") AND date_seen = INQ.date_seen) seen_by_venue
                    FROM (
                        SELECT
                            count(*) seen_times,
                            mac_address,
                            date_seen
                        FROM
                            track_seen_mac_address tsma
                        WHERE
                            (venue_id = ".$venue->id." OR venue_id IN (".$linked_venues."))
                        AND (date_seen = CURDATE())
                        GROUP BY mac_address,date_seen HAVING seen_times > 1
                    ) INQ
                ) INQ2 HAVING time_taken_to_convert > 0
            ) INQ3
            ");

            $data['strike_distance'] = \DB::select("
            SELECT
                ROUND(IFNULL(AVG( 6371 * acos (cos ( radians(billboard_latitude))
                                * cos( radians( venue_latitude ) )
                                * cos( radians( venue_logitude ) - radians(billboard_longitude) )
                                + sin ( radians(billboard_latitude) )
                                * sin( radians( venue_latitude ) )
                                )
                            ), 0), 2) as distance
            FROM
                (
                    SELECT
                        (SELECT latitude FROM venues WHERE id = v.linked_billboard) as billboard_latitude,
                        (SELECT longitude FROM venues WHERE id = v.linked_billboard) as billboard_longitude,
                        latitude as venue_latitude,
                        longitude as venue_logitude
                    FROM
                        venues v
                    WHERE
                        linked_billboard = ".$venue->id."
                ) as inq
        ");

        $data['avg_basket_value'] = \Brand::find($venue->brand_id)->avg_basket_value;
        $data['potential_sales'] = $data['total_conversions'] * $data['avg_basket_value'];

        $data['potential_sales_today'] = $data['total_conversions_today'] * $data['avg_basket_value'];

        if ($data['total_conversions'] != 0) {
            $data['cpa'] = round($venue->advertising_cost / $data['total_conversions'], 2);
            $data['cpa_today'] = round(($venue->advertising_cost / 31) / $data['total_conversions_today'], 2);
        } else {
            $data['cpa'] = 'N/A';
            $data['cpa_today'] = 'N/A';
        }

        if ($venue->advertising_cost && $venue->advertising_cost != 0) {
            $data['roi'] = round(($data['potential_sales'] / $venue->advertising_cost) * 100, 2);
            $data['roi_today'] = round(($data['potential_sales_today'] / ($venue->advertising_cost / 31)) * 100, 2);
        } else {
            $data['roi'] = 'N/A';
            $data['roi_today'] = 'N/A';
        }







        // $venues = \Venue::all();
        /*$venue = new \Venue();
        $venues = $venue->getVenuesForUser('hipjam', 1);//print_r($venues); die();

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
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        $data['venuesJson'] = json_encode($venues);*/

        /*if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($data['venuesJson']);

        } else {*/
        error_log("showDashboard : returning NON json");
        //return \View::make('hipjam.showvenues')->with('data', $data);
        return \View::make('hipjam.vicinityviewvenue')->with('data', $data);
        //return \Redirect::route('hipjam_showdashboard', ['json' => 1]);

        /*}*/
    }

    public function viewVenueJson($json = null, $name = null)
    {
        /*$data = array();
        //$data['edit'] = false;
        $data['currentMenuItem'] = "Dashboard";

        return \View::make('hipjam.viewvenue')->with('data', $data);*/
        //return \View::make('hipjam.chart')->with('data', $data);

        /*$data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('hipjam.showdashboard')->with('data', $data);*/

        error_log("showVenues");

        $data = array();
        $data['currentMenuItem'] = "Dashboard";
        $data['apisitename'] = $name;
        $data['apivenueid'] = $json;
        $venue = \DB::table('venues')->select("sitename", "location", "track_slug")->where('id', '=', $json)->first();
        $data['venue'] = $venue->sitename;
        $data['track_slugname'] = $venue->track_slug;
        //$data['location'] = $venue->location;

        $assetsdiry = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['fullpathimage'] = $assetsdiry->value . 'track/images/' . $venue->location . '.jpg';
        // $venues = \Venue::all();
        /*$venue = new \Venue();
        $venues = $venue->getVenuesForUser('hipjam', 1);//print_r($venues); die();

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
            // $venue["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $venue["sitename"]);
        }

        $data['venuesJson'] = json_encode($venues);*/

        /*if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($data['venuesJson']);

        } else {*/
        error_log("showDashboard : returning NON json");
        //return \View::make('hipjam.showvenues')->with('data', $data);
        return \View::make('hipjam.viewvenueajax')->with('data', $data);
        //return \Redirect::route('hipjam_showdashboard', ['json' => 1]);

        /*}*/
    }

    public function chartJsondata()
    {

        $period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $venue = Input::get('venue');
        $domain = Input::get('domain');
        $brand_id = \Venue::where('track_slug', '=', $venue)->first()->brand_id;
        $venue_id = \Venue::where('track_slug', '=', $venue)->first()->id;
        $min_session = \Brand::find($brand_id)->min_session_length;
        $max_session = \Brand::find($brand_id)->max_session_length;

        $json_url = "http://" . $domain . "/aggregate/" . $venue_id . "?period=" . $period . "&max_session=" . $max_session . "&min_session=" . $min_session;
        error_log("chartJsondata : json_url = $json_url");
        $json = file_get_contents($json_url);

        print_r($json);
    }
    public function heatmapJsondata()
    {
        $period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $domain = Input::get('domain');
        $hour = Input::get('hour');
        //$scanners = \Heatmap::getHeatmapscanners();//getting number of scanners and their co-ordinates from lib Heatmap. we can edit the values directly at any point with each background image of heatmap .
        $venue = Input::get('venue');
        $venue_id = Input::get('venue_id');
        $brand_id = \Venue::where('id', '=', $venue_id)->first()->brand_id;
        $min_session = \Brand::find($brand_id)->min_session_length;
        $max_session = \Brand::find($brand_id)->max_session_length;
        error_log("heatmapJsondata min_session = $min_session");
        error_log("heatmapJsondata max_session = $max_session");

        $scanners = \Sensor::where("venue_id", "like", $venue_id)->orderBy('id', 'DESC')->get();

        $mapData = array();
        $max = 0;


        // TEST DATA BEGIN

        // $domain = "tracks03.hipzone.co.za";
        // $venue = 1270;

        // $scanners = array();
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>28, "xcoord"=>28, "ycoord"=>100));
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>29, "xcoord"=>100, "ycoord"=>100));
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>30, "xcoord"=>50, "ycoord"=>300));
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>31, "xcoord"=>200, "ycoord"=>100));
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>32, "xcoord"=>150, "ycoord"=>150));
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>33, "xcoord"=>800, "ycoord"=>400));
        // array_push($scanners, array("name"=>"sparalgoa01", "id"=>34, "xcoord"=>375, "ycoord"=>375));

        // TEST DATA END

        foreach ($scanners as $key => $scanner) {
            error_log("heatmapJsondata scanner->id = " . $scanner["id"]);
            // error_log("heatmapJsondata scanner->name = " . $scanner["name"]);
            // Make call to the Track server using $venue, $scanner->name, $period, $domain


            if ($period != 'custom') {
                $json_url = "http://" . $domain . "/aggregate/" . $venue . "?period=" . $period . "&min_session=" . $min_session . "&max_session=" . $max_session . "&scanner=" . $scanner['name'];
            } else {
                $json_url = "http://" . $domain . "/venues/" . $venue . "?period=custom&start='" . Input::get('start') . "'&end='" . Input::get('end') . "'&min_session=" . $min_session . "&max_session=" . $max_session . "&scanner=" . $scanner['name'];
            }

            // $json_url = "http://tracks03.hipzone.co.za/aggregate/1270?period=this_month&scanner=30";
            error_log("heatmapJsondata json_url = $json_url");

            #TEST DATA
            #$testdata = $period."_test_data";
            #$json = $this->$testdata();//file_get_contents($json_url);
            #END TESTDATA

            $json = file_get_contents($json_url);
            error_log("heatmapJsondata json = $json");
            $jsonarray = json_decode($json, true);

            // $name = $jsonarray['scanners'][0]['location'];
            $count = $jsonarray['total']['total'];
            $x = $scanner["xcoord"];
            $y = $scanner["ycoord"];
            if ($count > $max) $max = $count;
            foreach ($jsonarray['total']['trends']['hours'] as $key => $value) {
                if ($value['hour'] == $hour) {
                    $scannerMapRecord = array("hr" => $value['hour'], "x" => $x, "y" => $y, "count" => $value['average_session']);
                    array_push($mapData, $scannerMapRecord);
                }
            }
        }

        // create array
        $returnArray = array("max" => $max, "data" => $mapData);
        $retrnJson = json_encode($returnArray);
        $retrnJson = str_replace('"', '', $retrnJson);
        error_log("heatmapJsondata : retrnJson = $retrnJson");

        // $retrn =   "{max: 100, data: [{x:300,y:100,count:1000},{x:290,y:400,count:20},{x:30,y:2000,count:80},{x:10,y:100,count:10},{x:200,y:70,count:60},{x:90,y:10,count:40},{x:50,y:100,count:70},{x:20,y:70,count:30},{x:60,y:50,count:15},{x:90,y:40,count:20}]}";

        return \Response::json($returnArray);
        // print_r($retrn);  // die();
    }
    public function today_test_data()
    {
        $result = [
            "id" => "Spar_Algoa",
            "name" => "Spar Algoa01",
            "franchise" => null,
            "address" => null,
            "coordinates" => null,
            "timezone" => "Africa/Johannesburg",
            "scanners" => [
                [
                    "id" => "sparalgoa01",
                    "updated_at" => "2018-03-08T23 =>58 =>53.000+02 =>00",
                    "location" => "Entrance",
                    "type" => null
                ]
            ],
            "period" => [
                "start" => "2018-03-08T00 =>00 =>00.000+02 =>00",
                "end" => "2018-03-09T00 =>00 =>00.000+02 =>00"
            ],
            "total" => [
                "total" => 94,
                "new" => 7,
                "average_session" => 13,
                "engaged_customers" => null,
                "window_conversion" => 13,
                "previous_period" => [
                    "total" => 121,
                    "new" => 12,
                    "average_session" => 12,
                    "engaged_customers" => null,
                    "window_conversion" => 12,
                    "change" => [
                        "total" => -27,
                        "new" => -5,
                        "average_session" => 1,
                        "engaged_customers" => 0,
                        "window_conversion" => 1
                    ]
                ],
                "trends" => [
                    "hours" => [
                        [
                            "hour" => 3,
                            "total" => 1,
                            "new" => 0,
                            "average_session" => 2
                        ],
                        [
                            "hour" => 4,
                            "total" => 2,
                            "new" => 0,
                            "average_session" => 100
                        ],
                        [
                            "hour" => 5,
                            "total" => 5,
                            "new" => 0,
                            "average_session" => 6
                        ],
                        [
                            "hour" => 6,
                            "total" => 8,
                            "new" => 0,
                            "average_session" => 1
                        ],
                        [
                            "hour" => 7,
                            "total" => 7,
                            "new" => 1,
                            "average_session" => 100
                        ],
                        [
                            "hour" => 8,
                            "total" => 8,
                            "new" => 1,
                            "average_session" => 12
                        ],
                        [
                            "hour" => 9,
                            "total" => 5,
                            "new" => 1,
                            "average_session" => 23
                        ],
                        [
                            "hour" => 10,
                            "total" => 6,
                            "new" => 0,
                            "average_session" => 7
                        ],
                        [
                            "hour" => 11,
                            "total" => 6,
                            "new" => 0,
                            "average_session" => 14
                        ],
                        [
                            "hour" => 12,
                            "total" => 10,
                            "new" => 1,
                            "average_session" => 15
                        ],
                        [
                            "hour" => 13,
                            "total" => 4,
                            "new" => 0,
                            "average_session" => 12
                        ],
                        [
                            "hour" => 14,
                            "total" => 9,
                            "new" => 0,
                            "average_session" => 11
                        ],
                        [
                            "hour" => 15,
                            "total" => 17,
                            "new" => 0,
                            "average_session" => 11
                        ],
                        [
                            "hour" => 16,
                            "total" => 10,
                            "new" => 1,
                            "average_session" => 13
                        ],
                        [
                            "hour" => 17,
                            "total" => 12,
                            "new" => 1,
                            "average_session" => 250
                        ],
                        [
                            "hour" => 18,
                            "total" => 8,
                            "new" => 0,
                            "average_session" => 8
                        ],
                        [
                            "hour" => 19,
                            "total" => 2,
                            "new" => 1,
                            "average_session" => 12
                        ]
                    ]
                ]
            ]
        ];
        return json_encode($result);
    }
    public function month_test_data()
    {
        $result = [
            "id" => "Spar_Algoa",
            "name" => "Spar Algoa01",
            "franchise" => null,
            "address" => null,
            "coordinates" => null,
            "timezone" => "Africa/Johannesburg",
            "scanners" => [

                "id" => "sparalgoa01",
                "updated_at" => "2018-03-08T11:36:12.000+02:00",
                "location" => "Entrance",
                "type" => null

            ],
            "period" => [
                "start" => "2018-02-01T00:00:00.000+02:00",
                "end" => "2018-03-01T00:00:00.000+02:00"
            ],
            "total" => [
                "total" => 1120,
                "new" => 305,
                "average_session" => 15,
                "engaged_customers" => null,
                "window_conversion" => 9,
                "previous_period" => [
                    "total" => 11,
                    "new" => 0,
                    "average_session" => 53,
                    "engaged_customers" => null,
                    "window_conversion" => 52,
                    "change" => [
                        "total" => 1109,
                        "new" => 305,
                        "average_session" => -38,
                        "engaged_customers" => 0,
                        "window_conversion" => -43
                    ]
                ],
                "trends" => [
                    "hours" => [
                        [
                            "hour" => 0,
                            "total" => 2,
                            "new" => 0,
                            "average_session" => 110
                        ],
                        [
                            "hour" => 1,
                            "total" => 2,
                            "new" => 0,
                            "average_session" => 35
                        ],
                        [
                            "hour" => 2,
                            "total" => 3,
                            "new" => 0,
                            "average_session" => 1
                        ],
                        [
                            "hour" => 3,
                            "total" => 7,
                            "new" => 1,
                            "average_session" => 59
                        ],
                        [
                            "hour" => 4,
                            "total" => 31,
                            "new" => 3,
                            "average_session" => 18
                        ],
                        [
                            "hour" => 5,
                            "total" => 46,
                            "new" => 5,
                            "average_session" => 21
                        ],
                        [
                            "hour" => 6,
                            "total" => 79,
                            "new" => 20,
                            "average_session" => 23
                        ],
                        [
                            "hour" => 7,
                            "total" => 86,
                            "new" => 15,
                            "average_session" => 18
                        ],
                        [
                            "hour" => 8,
                            "total" => 111,
                            "new" => 14,
                            "average_session" => 16
                        ],
                        [
                            "hour" => 9,
                            "total" => 118,
                            "new" => 18,
                            "average_session" => 14
                        ],
                        [
                            "hour" => 10,
                            "total" => 95,
                            "new" => 21,
                            "average_session" => 15
                        ],
                        [
                            "hour" => 11,
                            "total" => 121,
                            "new" => 25,
                            "average_session" => 10
                        ],
                        [
                            "hour" => 12,
                            "total" => 99,
                            "new" => 23,
                            "average_session" => 17
                        ],
                        [
                            "hour" => 13,
                            "total" => 122,
                            "new" => 22,
                            "average_session" => 15
                        ],
                        [
                            "hour" => 14,
                            "total" => 152,
                            "new" => 23,
                            "average_session" => 10
                        ],
                        [
                            "hour" => 15,
                            "total" => 193,
                            "new" => 37,
                            "average_session" => 9
                        ],
                        [
                            "hour" => 16,
                            "total" => 141,
                            "new" => 38,
                            "average_session" => 7
                        ],
                        [
                            "hour" => 17,
                            "total" => 108,
                            "new" => 20,
                            "average_session" => 250
                        ],
                        [
                            "hour" => 18,
                            "total" => 71,
                            "new" => 17,
                            "average_session" => 15
                        ],
                        [
                            "hour" => 19,
                            "total" => 29,
                            "new" => 3,
                            "average_session" => 310
                        ],
                        [
                            "hour" => 20,
                            "total" => 3,
                            "new" => 0,
                            "average_session" => 67
                        ],
                        [
                            "hour" => 21,
                            "total" => 2,
                            "new" => 0,
                            "average_session" => 510
                        ],
                        [
                            "hour" => 22,
                            "total" => 1,
                            "new" => 0,
                            "average_session" => 68
                        ],
                        [
                            "hour" => 23,
                            "total" => 2,
                            "new" => 0,
                            "average_session" => 55
                        ]
                    ],
                    "dates" => [
                        [
                            "date" => "2018-02-12",
                            "total" => 55,
                            "new" => 9,
                            "average_session" => 13
                        ],
                        [
                            "date" => "2018-02-13",
                            "total" => 95,
                            "new" => 30,
                            "average_session" => 13
                        ],
                        [
                            "date" => "2018-02-14",
                            "total" => 87,
                            "new" => 18,
                            "average_session" => 19
                        ],
                        [
                            "date" => "2018-02-15",
                            "total" => 97,
                            "new" => 19,
                            "average_session" => 18
                        ],
                        [
                            "date" => "2018-02-16",
                            "total" => 102,
                            "new" => 11,
                            "average_session" => 18
                        ],
                        [
                            "date" => "2018-02-17",
                            "total" => 101,
                            "new" => 21,
                            "average_session" => 11
                        ],
                        [
                            "date" => "2018-02-18",
                            "total" => 79,
                            "new" => 8,
                            "average_session" => 11
                        ],
                        [
                            "date" => "2018-02-19",
                            "total" => 70,
                            "new" => 13,
                            "average_session" => 21
                        ],
                        [
                            "date" => "2018-02-20",
                            "total" => 80,
                            "new" => 10,
                            "average_session" => 13
                        ],
                        [
                            "date" => "2018-02-21",
                            "total" => 90,
                            "new" => 16,
                            "average_session" => 19
                        ],
                        [
                            "date" => "2018-02-22",
                            "total" => 78,
                            "new" => 12,
                            "average_session" => 16
                        ],
                        [
                            "date" => "2018-02-23",
                            "total" => 133,
                            "new" => 30,
                            "average_session" => 9
                        ],
                        [
                            "date" => "2018-02-24",
                            "total" => 124,
                            "new" => 29,
                            "average_session" => 11
                        ],
                        [
                            "date" => "2018-02-25",
                            "total" => 88,
                            "new" => 26,
                            "average_session" => 12
                        ],
                        [
                            "date" => "2018-02-26",
                            "total" => 96,
                            "new" => 16,
                            "average_session" => 18
                        ],
                        [
                            "date" => "2018-02-27",
                            "total" => 126,
                            "new" => 19,
                            "average_session" => 20
                        ],
                        [
                            "date" => "2018-02-28",
                            "total" => 126,
                            "new" => 18,
                            "average_session" => 12
                        ]
                    ],
                    "weekdays" => [
                        [
                            "weekday" => "Friday",
                            "total" => 220,
                            "new" => 41,
                            "average_session" => 13
                        ],
                        [
                            "weekday" => "Monday",
                            "total" => 197,
                            "new" => 38,
                            "average_session" => 18
                        ],
                        [
                            "weekday" => "Saturday",
                            "total" => 216,
                            "new" => 50,
                            "average_session" => 11
                        ],
                        [
                            "weekday" => "Sunday",
                            "total" => 163,
                            "new" => 34,
                            "average_session" => 12
                        ],
                        [
                            "weekday" => "Thursday",
                            "total" => 162,
                            "new" => 31,
                            "average_session" => 17
                        ],
                        [
                            "weekday" => "Tuesday",
                            "total" => 271,
                            "new" => 59,
                            "average_session" => 16
                        ],
                        [
                            "weekday" => "Wednesday",
                            "total" => 268,
                            "new" => 52,
                            "average_session" => 16
                        ]
                    ]
                ]
            ]
        ];
        return json_encode($result);
    }
    //fetching data to create heatmap
    public function heatmapJsondata__()
    {


        $period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $venue = Input::get('venue');
        $venue_id = Input::get('venue_id');
        $domain = Input::get('domain');
        //$scanners = \Heatmap::getHeatmapscanners();//getting number of scanners and their co-ordinates from lib Heatmap. we can edit the values directly at any point with each background image of heatmap .
        $scanners = \Sensor::where("venue_id", "like", $venue_id)->orderBy('id', 'DESC')->get();

        $mapData = array();
        $max = 0;

        // TEST DATA BEGIN

        $domain = "tracks03.hipzone.co.za";
        $venue = 1270;

        $scanners = array();
        array_push($scanners, array("id" => 28, "xcoord" => 28, "ycoord" => 100));
        array_push($scanners, array("id" => 29, "xcoord" => 100, "ycoord" => 100));
        array_push($scanners, array("id" => 30, "xcoord" => 50, "ycoord" => 300));
        array_push($scanners, array("id" => 31, "xcoord" => 200, "ycoord" => 100));
        array_push($scanners, array("id" => 32, "xcoord" => 150, "ycoord" => 150));
        array_push($scanners, array("id" => 33, "xcoord" => 800, "ycoord" => 400));
        array_push($scanners, array("id" => 34, "xcoord" => 375, "ycoord" => 375));

        // TEST DATA END

        foreach ($scanners as $key => $scanner) {
            error_log("heatmapJsondata scanner->id = " . $scanner["id"]);
            // error_log("heatmapJsondata scanner->name = " . $scanner["name"]);
            // Make call to the Track server using $venue, $scanner->name, $period, $domain


            if ($period != 'custom') {
                $json_url = "http://" . $domain . "/aggregate/" . $venue . "?period=" . $period . "&min_session=5&max_session=60&scanner=" . $scanner['id'];
            } else {
                $json_url = "http://" . $domain . "/venues/" . $venue . "?period=custom&start='" . Input::get('start') . "'&end='" . Input::get('end') . "'&min_session=5&max_session=60&scanner=" . $scanner['id'];
            }

            // $json_url = "http://tracks03.hipzone.co.za/aggregate/1270?period=this_month&scanner=30";
            error_log("heatmapJsondata json_url = $json_url");
            $json = file_get_contents($json_url);
            error_log("heatmapJsondata json = $json");
            $jsonarray = json_decode($json, true);

            // $name = $jsonarray['scanners'][0]['location'];
            $count = $jsonarray['total']['total'];
            error_log("heatmapJsondata count = $count");

            if ($count > $max) $max = $count;

            // // create a $scannerMapRecord record:
            $x = $scanner["xcoord"];
            $y = $scanner["ycoord"];
            $scannerMapRecord = array("x" => $x, "y" => $y, "count" => $count);
            array_push($mapData, $scannerMapRecord);
        }

        // create array
        $returnArray = array("max" => $max, "data" => $mapData);
        $retrnJson = json_encode($returnArray);
        $retrnJson = str_replace('"', '', $retrnJson);
        error_log("heatmapJsondata : retrnJson = $retrnJson");

        // $retrn =   "{max: 100, data: [{x:300,y:100,count:1000},{x:290,y:400,count:20},{x:30,y:2000,count:80},{x:10,y:100,count:10},{x:200,y:70,count:60},{x:90,y:10,count:40},{x:50,y:100,count:70},{x:20,y:70,count:30},{x:60,y:50,count:15},{x:90,y:40,count:20}]}";

        return \Response::json($returnArray);
        // print_r($retrn);  // die();

    }

    public function heatmapJsondata_()
    {
        $period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $venue = Input::get('venue');
        $venue_id = Input::get('venue_id');
        $domain = Input::get('domain');
        //$scanners = \Heatmap::getHeatmapscanners();//getting number of scanners and their co-ordinates from lib Heatmap. we can edit the values directly at any point with each background image of heatmap .
        $scanners = \Sensor::where("venue_id", "like", $venue_id)->orderBy('id', 'DESC')->get();
        //print_r($scanners); die();

        $json1 = '{   "id": "clicks_145",
                     "name": "Clicks Canal Walk",
                     "franchise": "Clicks",
                     "address": null,
                     "coordinates": null,
                     "timezone": "Africa/Johannesburg",
                     "scanners": [{
                         "id": "clicks_6",
                         "updated_at": "2017-07-19T12:13:38.000+02:00",
                         "location": "Cosmetics",
                         "type": null
                     }],
                     "period": {
                         "start": "2017-07-12T12:29:47+00:00",
                         "end": "2017-07-19T12:29:47+00:00"
                     },
                     "total": {
                         "total": 15197,
                         "new": 8293,
                         "average_session": "03:36",
                         "previous_period": {
                             "total": 73238,
                             "new": 50736,
                             "average_session": "05:12",
                             "change": {
                                 "total": -58041,
                                 "new": -42443,
                                 "average_session": "59:09"
                             }
                         },
                         "trends": {
                             "hours": [{
                                 "hour": 0,
                                 "total": 173,
                                 "new": 90,
                                 "average_session": "07:34"
                             }, {
                                 "hour": 1,
                                 "total": 148,
                                 "new": 89,
                                 "average_session": "08:30"
                             }, {
                                 "hour": 2,
                                 "total": 150,
                                 "new": 77,
                                 "average_session": "11:09"
                             }, {
                                 "hour": 3,
                                 "total": 139,
                                 "new": 66,
                                 "average_session": "19:40"
                             }, {
                                 "hour": 4,
                                 "total": 153,
                                 "new": 80,
                                 "average_session": "05:58"
                             }, {
                                 "hour": 5,
                                 "total": 162,
                                 "new": 85,
                                 "average_session": "07:09"
                             }, {
                                 "hour": 6,
                                 "total": 179,
                                 "new": 87,
                                 "average_session": "10:22"
                             }, {
                                 "hour": 7,
                                 "total": 329,
                                 "new": 145,
                                 "average_session": "05:41"
                             }, {
                                 "hour": 8,
                                 "total": 351,
                                 "new": 106,
                                 "average_session": "05:05"
                             }, {
                                 "hour": 9,
                                 "total": 649,
                                 "new": 244,
                                 "average_session": "04:36"
                             }, {
                                 "hour": 10,
                                 "total": 1102,
                                 "new": 463,
                                 "average_session": "02:59"
                             }, {
                                 "hour": 11,
                                 "total": 1264,
                                 "new": 600,
                                 "average_session": "03:02"
                             }, {
                                 "hour": 12,
                                 "total": 1972,
                                 "new": 972,
                                 "average_session": "04:43"
                             }, {
                                 "hour": 13,
                                 "total": 1134,
                                 "new": 949,
                                 "average_session": "03:31"
                             }, {
                                 "hour": 14,
                                 "total": 1094,
                                 "new": 720,
                                 "average_session": "02:46"
                             }, {
                                 "hour": 15,
                                 "total": 978,
                                 "new": 430,
                                 "average_session": "04:50"
                             }, {
                                 "hour": 16,
                                 "total": 1462,
                                 "new": 655,
                                 "average_session": "02:40"
                             }, {
                                 "hour": 17,
                                 "total": 1370,
                                 "new": 665,
                                 "average_session": "03:45"
                             }, {
                                 "hour": 18,
                                 "total": 1005,
                                 "new": 463,
                                 "average_session": "03:07"
                             }, {
                                 "hour": 19,
                                 "total": 1098,
                                 "new": 518,
                                 "average_session": "03:07"
                             }, {
                                 "hour": 20,
                                 "total": 888,
                                 "new": 388,
                                 "average_session": "03:17"
                             }, {
                                 "hour": 21,
                                 "total": 423,
                                 "new": 142,
                                 "average_session": "08:44"
                             }, {
                                 "hour": 22,
                                 "total": 330,
                                 "new": 169,
                                 "average_session": "07:27"
                             }, {
                                 "hour": 23,
                                 "total": 198,
                                 "new": 90,
                                 "average_session": "11:37"
                             }],
                             "dates": [{
                                 "date": "2017-07-13",
                                 "total": 4899,
                                 "new": 2664,
                                 "average_session": "04:50"
                             }, {
                                 "date": "2017-07-14",
                                 "total": 5446,
                                 "new": 2695,
                                 "average_session": "04:12"
                             }, {
                                 "date": "2017-07-15",
                                 "total": 2519,
                                 "new": 1327,
                                 "average_session": "05:12"
                             }],
                             "weekdays": [{
                                 "weekday": "Wednesday",
                                 "total": 3210,
                                 "new": 1607,
                                 "average_session": "03:10"
                             }, {
                                 "weekday": "Thursday",
                                 "total": 4899,
                                 "new": 2664,
                                 "average_session": "04:50"
                             }, {
                                 "weekday": "Friday",
                                 "total": 5446,
                                 "new": 2695,
                                 "average_session": "04:12"
                             }, {
                                 "weekday": "Saturday",
                                 "total": 2519,
                                 "new": 1327,
                                 "average_session": "05:12"
                             }]
                         }
                     }
                }';

        $jsonarray1 = json_decode($json1, true);
        $scanners1 = \Sensor::select('name', 'xcoord', 'ycoord')->where("name", "like", $jsonarray1['scanners'][0]['id'])->orderBy('id', 'DESC')->first();
        $jsonarray1['xcoord'] = $scanners1->xcoord;
        $jsonarray1['ycoord'] = $scanners1->ycoord;
        $jsonarray1['name'] = $scanners1->name;
        $json1 = json_encode($jsonarray1, true);
        //print_r($json1); die();
        $count1 = $jsonarray1['total']['total'];


        $json2 = '{   "id": "clicks_145",
                     "name": "Clicks Cavendish",
                     "franchise": "Clicks",
                     "address": null,
                     "coordinates": null,
                     "timezone": "Africa/Johannesburg",
                     "scanners": [{
                         "id": "clicks_3",
                         "updated_at": "2017-07-19T12:13:38.000+02:00",
                         "location": "Cosmetics",
                         "type": null
                     }],
                     "period": {
                         "start": "2017-07-12T12:29:47+00:00",
                         "end": "2017-07-19T12:29:47+00:00"
                     },
                     "total": {
                         "total": 17197,
                         "new": 8593,
                         "average_session": "03:36",
                         "previous_period": {
                             "total": 73238,
                             "new": 50736,
                             "average_session": "05:12",
                             "change": {
                                 "total": -58041,
                                 "new": -42443,
                                 "average_session": "59:09"
                             }
                         },
                         "trends": {
                             "hours": [{
                                 "hour": 0,
                                 "total": 173,
                                 "new": 90,
                                 "average_session": "07:34"
                             }, {
                                 "hour": 1,
                                 "total": 148,
                                 "new": 89,
                                 "average_session": "08:30"
                             }, {
                                 "hour": 2,
                                 "total": 150,
                                 "new": 77,
                                 "average_session": "11:09"
                             }, {
                                 "hour": 3,
                                 "total": 139,
                                 "new": 66,
                                 "average_session": "19:40"
                             }, {
                                 "hour": 4,
                                 "total": 153,
                                 "new": 80,
                                 "average_session": "05:58"
                             }, {
                                 "hour": 5,
                                 "total": 162,
                                 "new": 85,
                                 "average_session": "07:09"
                             }, {
                                 "hour": 6,
                                 "total": 179,
                                 "new": 87,
                                 "average_session": "10:22"
                             }, {
                                 "hour": 7,
                                 "total": 329,
                                 "new": 145,
                                 "average_session": "05:41"
                             }, {
                                 "hour": 8,
                                 "total": 351,
                                 "new": 106,
                                 "average_session": "05:05"
                             }, {
                                 "hour": 9,
                                 "total": 649,
                                 "new": 244,
                                 "average_session": "04:36"
                             }, {
                                 "hour": 10,
                                 "total": 1102,
                                 "new": 463,
                                 "average_session": "02:59"
                             }, {
                                 "hour": 11,
                                 "total": 1264,
                                 "new": 600,
                                 "average_session": "03:02"
                             }, {
                                 "hour": 12,
                                 "total": 1972,
                                 "new": 972,
                                 "average_session": "04:43"
                             }, {
                                 "hour": 13,
                                 "total": 2134,
                                 "new": 949,
                                 "average_session": "03:31"
                             }, {
                                 "hour": 14,
                                 "total": 1894,
                                 "new": 720,
                                 "average_session": "02:46"
                             }, {
                                 "hour": 15,
                                 "total": 978,
                                 "new": 430,
                                 "average_session": "04:50"
                             }, {
                                 "hour": 16,
                                 "total": 1462,
                                 "new": 655,
                                 "average_session": "02:40"
                             }, {
                                 "hour": 17,
                                 "total": 1370,
                                 "new": 665,
                                 "average_session": "03:45"
                             }, {
                                 "hour": 18,
                                 "total": 1005,
                                 "new": 463,
                                 "average_session": "03:07"
                             }, {
                                 "hour": 19,
                                 "total": 1098,
                                 "new": 518,
                                 "average_session": "03:07"
                             }, {
                                 "hour": 20,
                                 "total": 888,
                                 "new": 388,
                                 "average_session": "03:17"
                             }, {
                                 "hour": 21,
                                 "total": 423,
                                 "new": 142,
                                 "average_session": "08:44"
                             }, {
                                 "hour": 22,
                                 "total": 330,
                                 "new": 169,
                                 "average_session": "07:27"
                             }, {
                                 "hour": 23,
                                 "total": 198,
                                 "new": 90,
                                 "average_session": "11:37"
                             }],
                             "dates": [{
                                 "date": "2017-07-13",
                                 "total": 4899,
                                 "new": 2664,
                                 "average_session": "04:50"
                             }, {
                                 "date": "2017-07-14",
                                 "total": 5446,
                                 "new": 2695,
                                 "average_session": "04:12"
                             }, {
                                 "date": "2017-07-15",
                                 "total": 2519,
                                 "new": 1327,
                                 "average_session": "05:12"
                             }],
                             "weekdays": [{
                                 "weekday": "Wednesday",
                                 "total": 3210,
                                 "new": 1607,
                                 "average_session": "03:10"
                             }, {
                                 "weekday": "Thursday",
                                 "total": 4899,
                                 "new": 2664,
                                 "average_session": "04:50"
                             }, {
                                 "weekday": "Friday",
                                 "total": 5446,
                                 "new": 2695,
                                 "average_session": "04:12"
                             }, {
                                 "weekday": "Saturday",
                                 "total": 2519,
                                 "new": 1327,
                                 "average_session": "05:12"
                             }]
                         }
                     }
                }';

        $jsonarray2 = json_decode($json2, true);
        $scanners2 = \Sensor::select('name', 'xcoord', 'ycoord')->where("name", "like", $jsonarray2['scanners'][0]['id'])->orderBy('id', 'DESC')->first();
        $jsonarray2['xcoord'] = $scanners2->xcoord;
        $jsonarray2['ycoord'] = $scanners2->ycoord;
        $jsonarray2['name'] = $scanners2->name;
        $json2 = json_encode($jsonarray2, true);

        $count2 = $jsonarray2['total']['total'];

        $apijsonarray = '[' . $json1 . ',' . $json2 . ']'; //combining all json data from each sensors.
        $apiarray = json_decode($apijsonarray, true);

        $resultarray = array();

        $heatmapdata  = array();
        for ($i = 1; $i <= 24; $i++) {

            $big = 0;
            foreach ($apiarray as $scanner) {
                $resultarray[$i]['x'] = $scanner['xcoord'];
                $resultarray[$i]['y'] = $scanner['ycoord'];
                $resultarray[$i]['scanner'] = $scanner['name'];

                $hours = $scanner['total']['trends']['hours'];

                foreach ($hours as $hour) {
                    if ($hour['hour'] == ($i - 1)) {
                        $resultarray[$i]['count'] = $hour['total'];
                    }
                }
                if ($big < $resultarray[$i]['count']) {
                    $big = $resultarray[$i]['count'];
                }
                $heatmapdata[$i]['data'][]  = array('x' => $scanner["xcoord"], 'y' => $scanner["ycoord"], 'count' => $resultarray[$i]['count']);
            }
            $heatmapdata[$i]['max'] = $big;
        }

        $attTemp = json_encode($heatmapdata[14]); //just showing a single hour(14 th) details
        $attTemp = str_replace('"', "", $attTemp);

        $retrn =  array('heatmap' => $attTemp, 'cordinates' => $scanners);
        print_r(json_encode($retrn));
        die();
    }

    //fetch sensors coordinates to preview floorplan image
    public function previewSensors()
    {
        $venue_id = Input::get('venue_id');

        $scanners = \Sensor::where("venue_id", "like", $venue_id)->orderBy('id', 'DESC')->get();
        print_r(json_encode($scanners));
    }

    //fetching data to create zonal tab view
    public function zonalJsondata()
    {
        $period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $venue = Input::get('venue');
        $domain = Input::get('domain');


        /*$apiserver = \DB::table('systemconfig')->select("*")->where('name', '=', "track_api_server")->first();*/
        if ($period != 'custom') {
            /*$sensers_url = $apiserver->value.$venue."?period=".$period."&min_session=5&max_session=60"; */
            $sensers_url = "http://" . $domain . "/aggregate/" . $venue . "?period=" . $period . "&min_session=5&max_session=60";
        } else {
            /*$sensers_url = $apiserver->value.$venue."?period=custom&start='".Input::get('start')."'&end='".Input::get('end')."'&min_session=5&max_session=60";*/
            $sensers_url = "http://" . $domain . "/venues/" . $venue . "?period=custom&start='" . Input::get('start') . "'&end='" . Input::get('end') . "'&min_session=5&max_session=60";
        }
        $sensers_json = file_get_contents($sensers_url);
        $sensers_array = json_decode($sensers_json, true);
        $scanners = $sensers_array['scanners'];

        $table = '';
        $rows = '';
        $beginTable = '
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Zone</th>
                      <th>Zone Name</th>
                      <th>Customers in Zone</th>
                      <th>Avg. Dwell Time</th>
                    </tr>
                  </thead>
                  <tbody> ';
        $i = 1;
        foreach ($scanners as $scanner) {
            if ($period != 'custom') {
                /*$json_url = $apiserver->value.$venue."?period=".$period."&min_session=5&max_session=60&scanner=".$scanner['id'];*/
                $json_url = "http://" . $domain . "/aggregate/" . $venue . "?period=" . $period . "&min_session=5&max_session=60&scanner=" . $scanner['id'];
            } else {
                /*$json_url = $apiserver->value.$venue."?period=custom&start='".Input::get('start')."'&end='".Input::get('end')."'&min_session=5&max_session=60&scanner=".$scanner['id'];*/
                $json_url = "http://" . $domain . "/venues/" . $venue . "?period=custom&start='" . Input::get('start') . "'&end='" . Input::get('end') . "'&min_session=5&max_session=60&scanner=" . $scanner['id'];
            }
            $json = file_get_contents($json_url);
            $jsonarray = json_decode($json, true); //print_r($jsonarray);
            $name = $jsonarray['scanners'][0]['location'];
            $count = $jsonarray['total']['total'];

            $numscanners = 2;
            if (sizeof($scanners) > 2) $numscanners =  sizeof($scanners) - 1;
            $dwelltime = round($jsonarray['total']['average_session'] / $numscanners, 1);

            $rows = $rows . '
                    <tr>
                      <td> ' . $i  . '</td>
                      <td> ' . $name  . '</td>
                      <td> ' . $count  . '</td>
                      <td> ' . $dwelltime . '</td>
                    </tr>
                    ';
            $i++;
        }

        $endTable = '
                  </tbody>
                </table>';

        $table = $beginTable . $rows . $endTable;


        print_r($table);
    }

    public function getWindowconversion()
    {

        $select_period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $venue = Input::get('venue');
        $domain = Input::get('domain');
        //echo($period); //die();
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        $period = 'custom';
        $getdata1 = "period=now";

        /*$apiserver = \DB::table('systemconfig')->select("*")->where('name', '=', "track_api_server")->first();*/
        /*$json_url1 = $apiserver->value.$venue."?min_session=5&max_session=60&".trim($getdata1);*/
        $json_url1 = "http://" . $domain . "/aggregate/" . $venue . "?min_session=5&max_session=60&" . trim($getdata1);
        /*$json_url1 = "http://cpt-mysql-slave.hipjam.net:9299/aggregate/".$venue."?min_session=5&max_session=60".trim($getdata1);*/
        $json1 = file_get_contents($json_url1);

        $getdata2 = "period=today";
        /*$json_url2 = $apiserver->value.$venue."?min_session=5&max_session=60&".trim($getdata2);*/
        $json_url2 = "http://" . $domain . "/aggregate/" . $venue . "?min_session=5&max_session=60&" . trim($getdata2);
        /*$json_url2 = "http://cpt-mysql-slave.hipjam.net:9299/aggregate/".$venue."?min_session=5&max_session=60".trim($getdata2);*/
        $json2 = file_get_contents($json_url2);

        $json3 = json_encode(json_decode("{}"));
        if ($select_period == 'daterange') {
            $getdata3 = "period=custom&start='" . Input::get('start') . "'&end='" . Input::get('end') . "'";

            /*$json_url3 = $apiserver->value.$venue."?min_session=5&max_session=60".trim($getdata3);*/
            $json_url3 = "http://" . $domain . "/venues/" . $venue . "?min_session=5&max_session=60" . trim($getdata3);
            /*$json_url3 = "http://mrp.doteleven.co/venues/mrp0381?".trim($getdata3);*/
            $json3 = file_get_contents($json_url3);
            $json3 = json_decode($json3);
        } else {
            if ($select_period == 'rep7day') {
                /*$json_url = $apiserver->value.$venue."?period=this_week&min_session=5&max_session=60";*/
                $json_url = "http://" . $domain . "/aggregate/" . $venue . "?period=this_week&min_session=5&max_session=60";
                /*$json_url = "http://mrp.doteleven.co/venues/mrp0381?period=week&scanner_type=".$scanner_type;*/
            } else if ($select_period == 'repthismonth') {
                /*$json_url = $apiserver->value.$venue."?period=this_month&min_session=5&max_session=60";*/
                $json_url = "http://" . $domain . "/aggregate/" . $venue . "?period=this_month&min_session=5&max_session=60";
                /*$json_url = "http://mrp.doteleven.co/venues/mrp0381?period=month&scanner_type=".$scanner_type;*/
            } else if ($select_period == 'replastmonth') {
                /*$json_url = $apiserver->value.$venue."?period=month&min_session=5&max_session=60";*/
                $json_url = "http://" . $domain . "/aggregate/" . $venue . "?period=month&min_session=5&max_session=60";
                /*$start = date('Y-m-d',strtotime('first day of last month'));
                $end = date('Y-m-d',strtotime('last day of last month'));
                $json_url = $apiserver->value.$venue."?period=custom&min_session=5&max_session=60&start='".$start."'&end='".$end."'";*/
                /*$json_url = "http://mrp.doteleven.co/venues/mrp0381?period=custom&scanner_type=".$scanner_type."&start='".$start."'&end='".$end."'";*/
            }

            $json = file_get_contents($json_url);
            $json3 = json_decode($json);
        }



        $json_array = array(json_decode($json1), json_decode($json2), $json3);
        $json = json_encode($json_array);

        print_r($json);
    }

    public function customchartJsondata()
    {

        $period = Input::get('period');
        $scanner_type = Input::get('scanner_type');
        $start = Input::get('start');
        $end = Input::get('end');
        $venue = Input::get('venue');
        $domain = Input::get('domain');

        $brand_id = \Venue::where('track_slug', '=', $venue)->first()->brand_id;
        $venue_id = \Venue::where('track_slug', '=', $venue)->first()->id;
        $min_session = \Brand::find($brand_id)->min_session_length;
        $max_session = \Brand::find($brand_id)->max_session_length;

        error_log("customchartJsondata : min_session = $min_session");
        error_log("customchartJsondata : max_session = $max_session");


        /*$apiserver = \DB::table('systemconfig')->select("*")->where('name', '=', "track_api_server")->first();
        $json_url = $apiserver->value.$venue."?period=".$period."&min_session=5&max_session=60&start=".$start."&end=".$end;*/
        $json_url = "http://" . $domain . "/aggregate/" . $venue . "/custom/" . $start . "/" . $end . "/?min_session=" . $min_session . "&max_session=" . $max_session;
        /*$json_url = "http://cpt-mysql-slave.hipjam.net:9299/aggregate/".$venue."?period=".$period."&min_session=5&max_session=60&start=".$start."&end=".$end;*/

        $json = file_get_contents($json_url);

        print_r($json);
    }
}
