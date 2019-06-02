<?php

namespace hippnp;
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

	public static function showDashboard(){

        // 2019-06-02

        $data = array() ;
        $data['customerInStoreToday'] = \Picknpay::customerInStoreToday();
        $data['customerInStoreThisMonth'] = \Picknpay::customerInStoreThisMonth();
        $data['category'] = \Picknpay::chartCategoriesAsJson('rep7day');
        $data['staff_graph'] = \Picknpay::getChartDwellTimeData('rep7day');

        $data['report_period'] = 'asdf';



        return \View::make('hippnp.showdashboard')->with('data', $data);

    }

    public function periodchartJsondata(){

        $data = array() ;
        $period = Input::get('period');
        $start = '';
        $end = '';

        if($period == 'rep7day'){
            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');
            $rep_name_period    =   "week";
        }else if($period == 'repthismonth'){
            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d');
            $rep_name_period    =   "month";
        }else if($period == 'replastmonth'){
            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));
            $rep_name_period    =   "month";
        }else if($period == 'daterange'){
            $start = Input::get('start');
            $end = Input::get('end');
            $rep_name_period    =   "date";
        }else if($period == 'custom'){
            $start = Input::get('start');
            $end = Input::get('end');
            $rep_name_period    =   "date";
        }

        // $settings = $this->getTnaInstanceSettings();
        // $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;

        // $data['category'] = \Timeandattendance::select('date as label')->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get()->toArray();

        // $currentInstance = Session::get('currentInstance');
        // if($currentInstance == 'NR01' || $currentInstance == 'NR02' ){
        //     error_log("periodchartJsondata : processing nr");

        //     $data['currentInstance'] = $currentInstance;
        //     // Get Lateness Data
        //     $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
        //     $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
        //     $lateness_graph = array($staff_ontime, $staff_late);
        //     $data['lateness_graph'] = $lateness_graph;

        // } else {
        //     error_log("periodchartJsondata : processing not_nr");
        //     $data['currentInstance'] = 'NOT_NR';

        //     $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        //     // Get Absenteeism Data
        //     $present_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` = "present" THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_present = array("seriesname" => "Staff At Work", "data" => $present_data);
        //     $absent_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` <> "present" THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_absent = array("seriesname" => "Staff Not At Work", "data" => $absent_data);
        //     $staff_graph = array($staff_present, $staff_absent);
        //     $data['staff_graph'] = $staff_graph;


        //     // Get Lateness Data
        //     $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
        //     $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
        //     $lateness_graph = array($staff_ontime, $staff_late);
        //     $data['lateness_graph'] = $lateness_graph;

        //     // Get Proximity Data
        //     $proximal_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` >= '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_proximal = array("seriesname" => "Staff At Work", "data" => $proximal_data);
        //     $away_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` < '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
        //     $staff_away = array("seriesname" => "Staff Not At Work", "data" => $away_data);
        //     $staff_wsproximity = array($staff_proximal, $staff_away);
        //     $data['wsproximity_graph'] = $staff_wsproximity;
        // }


        $data['report_period']      =   "Report for ".$start." to ". $end;
        $data['report_name_date']   =   $rep_name_period."_".$start."_".$end;


        $json = json_encode($data);

        print_r($json);

    }

}