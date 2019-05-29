<?php

use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Session;

class HippnpController extends \BaseController {

	public function showDashboard()
    {

        // $data = array() ;
        // $data['currentMenuItem'] = "Dashboard";

        // if(\Hipauth::hasAllPermissions(array("TNA_CE", "TNA_IM"))) {
        //     Session::put('availableInstances', "BOTH");
        // } else if (\Hipauth::hasAnyPermissions(array("TNA_IM"))) {
        //     Session::put('availableInstances', "IM");
        //     $instance = "IM";
        // } else if (\Hipauth::hasAnyPermissions(array("TNA_CE"))) {
        //     Session::put('availableInstances', "CE");
        //     $instance = "CE";
        // } else if (\Hipauth::hasAnyPermissions(array("NR0001","NR0002"))) {
        //     Session::put('availableInstances', "NR_BOTH");
        // } else if (\Hipauth::hasAnyPermissions(array("NR0001"))) {
        //     Session::put('availableInstances', "NR01");
        //     $instance = "NR01";
        // } else if (\Hipauth::hasAnyPermissions(array("NR0002"))) {
        //     Session::put('availableInstances', "NR02");
        //     $instance = "NR02";
        // }

        // if($instance) {
                return Redirect::action('showInstanceDashboard');
		// }

	}

	public function showInstanceDashboard()
    {

        // $currentInstance = Session::get('currentInstance');
        // $data = array() ;
        // $data['currentMenuItem'] = "Dashboard";

        // $settings = $this->getTnaInstanceSettings();
        // $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        // $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        // $data['staff_week'] = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('attendance', '=', "present")->where('instance', '=', $currentInstance )->get()->count();
        // $data['staff_month'] = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('attendance', '=', "present")->where('instance', '=', $currentInstance )->get()->count();

        // $cons_absent = \Timeandattendance::select('date')->where('attendance', '!=', "present")->where('instance', '=', $currentInstance )->orderBy('date', 'desc')->first();
        // $start = strtotime($cons_absent->date);
        // $end = strtotime(date('Y-m-d'));
        // $data['cons_absent'] = ceil(abs($end - $start) / 86400)-1;

        // $cons_lateness = \Timeandattendance::select('date')->where('punctuality', '>', $lateness_threshold)->where('instance', '=', $currentInstance)->orderBy('date', 'desc')->first();
        // $start = strtotime($cons_lateness->date);
        // $end = strtotime(date('Y-m-d'));
        // $data['cons_lateness'] = ceil(abs($end - $start) / 86400)-1;

        // $data['category'] = \Timeandattendance::select('date as label')->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get()->toJson();

        // // Get Absenteeism Data
        // $present_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` = "present" THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        // $staff_present = array("seriesname" => "Staff At Work", "data" => $present_data);
        // $absent_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` <> "present" THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        // $staff_absent = array("seriesname" => "Staff Not At Work", "data" => $absent_data);
        // $staff_graph = array($staff_present, $staff_absent);
        // $data['staff_graph'] = json_encode($staff_graph);

        // // Get Lateness Data
        // $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= '.$lateness_threshold.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        // $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
        // $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > '.$lateness_threshold.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        // $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
        // $lateness_graph = array($staff_ontime, $staff_late);
        // $data['lateness_graph'] = json_encode($lateness_graph);

        // // Get Lateness Data
        // $proximal_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` >= '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        // $staff_proximal = array("seriesname" => "Staff At Work", "data" => $proximal_data);
        // $away_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` < '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        // $staff_away = array("seriesname" => "Staff Not At Work", "data" => $away_data);
        // $staff_wsproximity = array($staff_proximal, $staff_away);
        // $data['wsproximity_graph'] = json_encode($staff_wsproximity);
        // $data['report_period']      =   "Report for ".date('Y-m-d',strtotime('last monday'))." to ". date('Y-m-d');
        // $data['report_name_date']   =   "day_".date('Y-m-d',strtotime('last monday'))."_".date('Y-m-d');

        // return \View::make('hiptna.showdashboard')->with('data', $data);
    }

}
