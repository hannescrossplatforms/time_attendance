<?php

namespace hiptna;
use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Session;
// use app\lib\Hipauth;
// use App\models\Reportrecipients
// use BaseController;

class HiptnaController extends \BaseController {


    public function showDashboard($instance = null)
    {

        // @if (\User::hasProduct("HipTnA") || \User::hasAccess("superadmin"))
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";

        if(\Hipauth::hasAllPermissions(array("TNA_CE", "TNA_IM"))) {
            Session::put('availableInstances', "BOTH");
        } else if (\Hipauth::hasAnyPermissions(array("TNA_IM"))) {
            Session::put('availableInstances', "IM");
            $instance = "IM";
        } else if (\Hipauth::hasAnyPermissions(array("TNA_CE"))) {
            Session::put('availableInstances', "CE");
            $instance = "CE";
        } else if (\Hipauth::hasAnyPermissions(array("NR0001","NR0002"))) {
            Session::put('availableInstances', "NR_BOTH");
        } else if (\Hipauth::hasAnyPermissions(array("NR0001"))) {
            Session::put('availableInstances', "NR01");
            $instance = "NR01";
        } else if (\Hipauth::hasAnyPermissions(array("NR0002"))) {
            Session::put('availableInstances', "NR02");
            $instance = "NR02";
        } else if (\Hipauth::hasAnyPermissions(array("PNP_ACCESS"))) {
            Session::put('availableInstances', "PNP_ACCESS");
            $instance = "PNP_ACCESS";
        } else if (\Hipauth::hasAnyPermissions(array("BIDVEST_ACCESS"))) {
            Session::put('availableInstances', "BIDVEST_ACCESS");
            $instance = "BIDVEST_ACCESS";
        }
        // Session::put('availableInstances', "NR01");
        // $instance = "NR01";
        // ANUSHA - CURRENTLY THE ABOVE WILL SET availableInstances to 'BOTH'
        // TO TEST A SCENARIO WHERE THE USER ONLY HAS ON INSTANCE THEN COMMENT OUT THE NEXT 2 LINES
        // Session::put('availableInstances', "IM");
        // $instance = "IM";


        if($instance) {

            Session::put('currentInstance', $instance);
            if($instance == "NR01" || $instance == "NR02" ) {
                return Redirect::action('hiptna\HiptnaController@showNrInstanceDashboard');
            } else if ($instance == "PNP_ACCESS") {
                return Redirect::action('hippnp\HippnpController@showDashboard');
            } else if ($instance == "BIDVEST_ACCESS") {
                return Redirect::action('hipbidvest\HipbidvestController@showDashboard');
            } else {
                return Redirect::action('hiptna\HiptnaController@showInstanceDashboard');
            }

        } else {

            Session::put('currentInstance', 'NONE');
            return \View::make('hiptna.showmaindashboard')->with('data', $data);

        }
    }

    public function selectDashboard()
    {

        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";

        return \View::make('hiptna.showmaindashboard')->with('data', $data);
    }


    public function getTnaInstancePrefix() {
        $currentInstance = Session::get('currentInstance');
        $settings = array() ;

        if( $currentInstance == "IM" ) {
            $instance               =   "im_";
        } else if( $currentInstance == "CE" ) {
            $instance               =   "ce_";
        } else if( $currentInstance == "NR01" ) {
            $instance               =   "nr01_";
        } else if( $currentInstance == "NR02" ) {
            $instance               =   "nr02_";
        } else {
            $instance               =   "";
        }

        return $instance;
    }

    public function getTnaInstanceSettings()
    {

        $instance = $this->getTnaInstancePrefix();

        $settings["lateness_threshold"]         =       $instance.'lateness_threshold';
        $settings["proximity_target"]           =       $instance.'proximity_target';
        $settings["tnaproximitydistance_a"]     =       $instance.'tnaproximitydistance_a';
        $settings["tnaproximitytime_a"]         =       $instance.'tnaproximitytime_a';
        $settings["tnaproximitydistance_b"]     =       $instance.'tnaproximitydistance_b';
        $settings["tnaproximitytime_b"]         =       $instance.'tnaproximitytime_b';
        $settings["tnaproximitydistance_c"]     =       $instance.'tnaproximitydistance_c';
        $settings["tnaproximitytime_c"]         =       $instance.'tnaproximitytime_c';
        $settings["late_sms_trigger"]           =       $instance.'late_sms_trigger';
        $settings["absence_sms_trigger"]        =       $instance.'absence_sms_trigger';

        $settings["start_time"]                 =       $instance.'start_time';

        return $settings;
    }

    public function getStaffTable() {

        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'IM'){
            $staff_table = 'im_staff';
        }else if($currentInstance == 'CE'){
            $staff_table = 'ce_staff';
        }else{
            $staff_table = 'staff';
        }

        return $staff_table;
    }

    public function showInstanceDashboard()
    {
        // Store data in the session...
        // Session::set('currentInstance', 'IM');
        $currentInstance = Session::get('currentInstance');
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $data['staff_week'] = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('attendance', '=', "present")->where('instance', '=', $currentInstance )->get()->count();
        $data['staff_month'] = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('attendance', '=', "present")->where('instance', '=', $currentInstance )->get()->count();
//        print_r($data);die("here");
        $cons_absent = \Timeandattendance::select('date')->where('attendance', '!=', "present")->where('instance', '=', $currentInstance )->orderBy('date', 'desc')->first();
        $start = strtotime($cons_absent->date);
        $end = strtotime(date('Y-m-d'));
        $data['cons_absent'] = ceil(abs($end - $start) / 86400)-1;

        $cons_lateness = \Timeandattendance::select('date')->where('punctuality', '>', $lateness_threshold)->where('instance', '=', $currentInstance)->orderBy('date', 'desc')->first();
        $start = strtotime($cons_lateness->date);
        $end = strtotime(date('Y-m-d'));
        $data['cons_lateness'] = ceil(abs($end - $start) / 86400)-1;

        $data['category'] = \Timeandattendance::select('date as label')->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get()->toJson();

        // Get Absenteeism Data
        $present_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` = "present" THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_present = array("seriesname" => "Staff At Work", "data" => $present_data);
        $absent_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` <> "present" THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_absent = array("seriesname" => "Staff Not At Work", "data" => $absent_data);
        $staff_graph = array($staff_present, $staff_absent);
        $data['staff_graph'] = json_encode($staff_graph);

        // Get Lateness Data
        $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= '.$lateness_threshold.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
        $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > '.$lateness_threshold.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
        $lateness_graph = array($staff_ontime, $staff_late);
        $data['lateness_graph'] = json_encode($lateness_graph);

        // Get Lateness Data
        $proximal_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` >= '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_proximal = array("seriesname" => "Staff At Work", "data" => $proximal_data);
        $away_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` < '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_away = array("seriesname" => "Staff Not At Work", "data" => $away_data);
        $staff_wsproximity = array($staff_proximal, $staff_away);
        $data['wsproximity_graph'] = json_encode($staff_wsproximity);
        $data['report_period']      =   "Report for ".date('Y-m-d',strtotime('last monday'))." to ". date('Y-m-d');
        $data['report_name_date']   =   "day_".date('Y-m-d',strtotime('last monday'))."_".date('Y-m-d');




        //echo '<pre>';
//        print_r($data); die();

        return \View::make('hiptna.showdashboard')->with('data', $data);
    }

    public function showNrInstanceDashboard()
    {
        // die (Session::get('currentInstance'));
        $currentInstance = Session::get('currentInstance');
        $data = array() ;
        $data['currentMenuItem'] = "Dashboard";

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;

        $data['staff_month'] = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('instance', '=', $currentInstance )->groupBy('external_user_id')->get()->count();
//        print_r($data);die("here");

        $cons_lateness = \Timeandattendance::select('date')->where('punctuality', '>', $lateness_threshold)->where('instance', '=', $currentInstance)->orderBy('date', 'desc')->first();
        $start = strtotime($cons_lateness->date);
        $end = strtotime(date('Y-m-d'));
        $data['cons_lateness'] = ceil(abs($end - $start) / 86400)-1;

        $data['category'] = \Timeandattendance::select('date as label')->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get()->toJson();


        // Get Lateness Data
        $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= '.$lateness_threshold.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();

//        print_r($ontime_data); die();
        $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
        $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > '.$lateness_threshold.' THEN 0 END ) AS value'))->where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('instance', '=', $currentInstance )->groupBy('date')->get();
        $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
        $lateness_graph = array($staff_ontime, $staff_late);
        $data['lateness_graph'] = json_encode($lateness_graph);


        $data['report_period']      =   "Report for ".date('Y-m-d',strtotime('last monday'))." to ". date('Y-m-d');
        $data['report_name_date']   =   "day_".date('Y-m-d',strtotime('last monday'))."_".date('Y-m-d');




        //echo '<pre>';
//        print_r($data); die();

        return \View::make('hiptna.showdashboard')->with('data', $data);
    }


    public function pdftest() {
        return $this->generatePdf();
    }

    public function generateJson( $period = '' , $currentInstance ) {

        $venuefrom              =   '';
        $venueto                =   '';
        $filename               =   'json_result.json';
        $outputfile             =   '';

        if( $period == 'lastday' ) {
            $period     =   'daterange';
            $venuefrom  =   date( 'Y-m-d' , strtotime( 'yesterday' ) );
            $venueto    =   date( 'Y-m-d' , strtotime( 'yesterday' ) );
        } else if( $period == 'lastweek' ) {
            $period     =   'daterange';
            $venuefrom  =   date("Y-m-d", strtotime("last week monday"));
            $venueto    =   date("Y-m-d", strtotime("last sunday"));
        } else if( $period == 'lastmonth' ) {
            $period         =   'replastmonth';
            $venuefrom      =   date('Y-m-d',strtotime('first day of last month'));
            $venueto        =   date('Y-m-d',strtotime('last day of last month'));
        }

        $json_data      =   $this->periodExceptionchartJsondata( $period , $venuefrom , $venueto , $currentInstance );
        $json_file      =   fopen(base_path().'/public/report/json_file/'.$filename , 'w');
        fwrite( $json_file , $json_data );
        fclose($json_file);
        return $filename;
    }

    /*
     * pdf report generation
     * Generating pdf report for lateness,absence,wxproximity based on current date.
     * @param
     * @return .
     */
    public function generatePdf() {
        error_log("generatePdfgeneratePdfgeneratePdfgeneratePdfgeneratePdf");

        $day        =   date('D'); //Day Today 'Mon';//
        $date       =   date('d'); //date today '01';//

        if( $day == 'Mon' && $date == '01') {
            $report_period      =       array( 'lastmonth' , 'lastweek','lastday');
        } else if( $day == 'Mon' ) {
            $report_period      =       array( 'lastweek' , 'lastday' );
        } else if( $date == '01') {
            $report_period      =       array( 'lastmonth' , 'lastday' );
        } else {
            $report_period      =       array( 'lastday' );
        }

        require app_path().'/lib/PdfReport.php';
        $pdfReport      =   new \PdfReport();
        $count          =   0;
        foreach ( $report_period as $key => $period) {
            Session::set('currentInstance', 'IM');
            $currentInstance  =         "IM";
            $json_file        =       $this->generateJson( $period , $currentInstance );
            $pdfReport->generateReport( $period , $currentInstance );

            Session::set('currentInstance', 'CE');
            $currentInstance  =         "CE";
            $json_file        =       $this->generateJson( $period , $currentInstance );
            $pdfReport->generateReport( $period , $currentInstance );

            $count++;
        }
        return "Total number of files created ".$count*3*2;

    }

        //print preview for dashboard download
    //print preview for dashboard download
    public function showDashboarddownload() {
        $input_data                 =       Input::all();

        $data                       =       array();
        $data['currentMenuItem']    =       "Dashboard";
        $data['fusionchartElement'] =       $input_data['myPage'];
        $data['report_name_date']   =       $input_data['report_name_date'];

        $currentInstance = Session::get('currentInstance');
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $data['staff_week']         =       \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('attendance', '=', "present")->where('instance', '=', $currentInstance )->get()->count();
        $data['staff_month']        =       \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('attendance', '=', "present")->where('instance', '=', $currentInstance )->get()->count();

        $cons_absent = \Timeandattendance::select('date')->where('attendance', '!=', "present")->orderBy('date', 'desc')->where('instance', '=', $currentInstance )->first();
        $start = strtotime($cons_absent->date);
        $end = strtotime(date('Y-m-d'));
        $data['cons_absent'] = ceil(abs($end - $start) / 86400)-1;

        $cons_lateness = \Timeandattendance::select('date')->where('punctuality', '>', $lateness_threshold)->where('instance', '=', $currentInstance )->orderBy('date', 'desc')->first();
        $start = strtotime($cons_lateness->date);
        $end = strtotime(date('Y-m-d'));
        $data['cons_lateness'] = ceil(abs($end - $start) / 86400)-1;

        $data['staff_today']    =   $this->getstafftoday();

        if(isset($input_data['printtoken'])) {
//////////download hiptna.showdashboard_graphview blade page as pdf ////////////////
            $dompdf = \PDF::loadView('hiptna.showdashboard_download', $data);
//            $pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'portrait');
//            $pdf->get_option('enable_css_float');
            $filename               =       $input_data['report_name_date'].".pdf";
            return $dompdf->download($filename);
//            $pdf = $dompdf->output();
//            $file_location = base_path()."/public/fc_images/pdfreport/".$filename;
//            file_put_contents($file_location,$pdf);
        } else {
////////////view printablepage/////////////////
            $data['printButtonToken']   =   TRUE;
            return \View::make('hiptna.showdashboard_graphview', $data);
        }
    }

    //download view page as pdf dompdf
    public function showDrilldownDownload() {
        $input_data                 =       Input::all();
        $data                       =       array();
        $data['currentMenuItem'] = "Dashboard";
        $data['fusionchartElement'] =  $input_data['myPage'];
        $data['graph_name']         =   $input_data['graph_name'];
        $currentInstance            =   Session::get('currentInstance');
        if($currentInstance != "")
            $currentInstance        =   $currentInstance."_";
        else
            $currentInstance        =   "";

        if(isset($input_data['printtoken'])) {
            $dompdf = \PDF::loadView('hiptna.drilldowngraph_download', $data);
            $filename               =       $currentInstance.$input_data['graph_name']."_".$input_data['report_name'].".pdf";
            return $dompdf->download($filename);
        } else {
            $data['report_name']        =   $input_data['report_name_date'];
            $data['page_header']        =   $input_data['page-header'];
            $data['printButtonToken']   =   TRUE;
            return \View::make('hiptna.drilldowngraph_preview', $data);
        }
    }

    //download exception report
    public function downloadExceptionReport() {
        $input_data                 =   Input::all();
        $data                       =   array();
        $data['currentMenuItem']    =   "Dashboard";
        $data['fusionchartElement'] =   $input_data['myPage'];
        $data['graph_name']         =   $input_data['graph_name'];

        $currentInstance            =   Session::get('currentInstance');
        if($currentInstance != "")
            $currentInstance        =   $currentInstance."_";
        else
            $currentInstance        =   "";
        if(isset($input_data['printtoken'])) {
            $dompdf = \PDF::loadView('hiptna.drilldowngraph_download', $data);
            $filename               =       $currentInstance.$input_data['graph_name']."_".$input_data['report_name'].".pdf";

            return $dompdf->download($filename);
        } else {
            $data['report_name']        =   $input_data['report_name_date'];
            $data['page_header']        =   $input_data['page-header'];
            $data['printButtonToken']   =   TRUE;
            return \View::make('hiptna.drilldowngraph_preview', $data);
        }
    }


///////////////////////////////////////////////////////////////

// Name: hiptnaStaffLookupDownload

// Purpose:  To convert hiptna Staff Lookup view page to a pdf format and enable // download option
// using Dom pdf

// It receive html content and pdf name from the form submission, pass it to     // the download preview page and to the download page. Enable pdf download using // Dom pdf.

// Created at 4-11-2016 by Prajeesh

// Last updated at 4-11-2016 by Prajeesh

//////////////////////////////////////////////////////////////


    public function hiptnaStaffLookupDownload() {
        $input_data                 =       Input::all();
        $data                       =       array();
        $data['currentMenuItem'] = "Dashboard";
        $data['fusionchartElement'] =  $input_data['myPageone'];
        $data['report_name']         =   $input_data['report_name'];


        if(isset($input_data['printtoken'])) {
            //return \View::make('hiptna.mc_stafflookup_download', $data);
            $dompdf = \PDF::loadView('hiptna.mc_stafflookup_download', $data);
//            $pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'portrait');
//            $pdf->get_option('enable_css_float');
            $filename = "staff_lookup_report_".str_replace(' ','_',$input_data['report_name']).".pdf";
//            $filename           =       "graphview".strtotime(date('h:i:s')).".pdf";
            return $dompdf->download($filename);
//            $pdf = $dompdf->output();
//            $file_location = base_path()."/public/fc_images/pdfreport/".$filename;
//            file_put_contents($file_location,$pdf);
        } else {

            $data['printButtonToken']   =   TRUE;
            return \View::make('hiptna.mc_stafflookup_download_preview', $data);
        }
    }




    //convertSVGtoPNG
    public function convertSvgToImage() {
        $data                       =       array();
        $input_data                 =       Input::all();

        $fusionchart_spans                  =   $input_data['fusionchartspans'];
        $chart_svg                          =   "";
        $images                         =   array();
        $svgs                           =   array();
        $i                              =   0;
        //converting svg code to image
        foreach($fusionchart_spans as $key => $charts) {
            $i++;
            $chart_svg                 .=       $charts;
            $path                       =       base_path()."/public/fc_images/svg_temp/";
            $fileName                   =       $key.strtotime(date('H:i:s'));
            $svg_file                   =       fopen($path.$fileName.".svg","w");
            fwrite($svg_file, $charts);
            fclose($svg_file);
            $svgs["img_".$key]               =   $fileName;
        }


/////////////for dev1 pdfinvestigation /////////////
            $svgpath                    =   base_path().'/public/fc_images/svg_temp/';
            $imgpath                    =   base_path().'/public/fc_images/image_temp/';
        foreach($svgs as $key => $val) {
            //shell excution for svg to image
            $cmd                        =   'inkscape -f '.$svgpath.$val.'.svg -e '.$imgpath.$val.'.png';
            shell_exec($cmd);
            $images[$key]               =   $val.".png";
            unlink($svgpath.$val.'.svg');
        }

        $response                       =   array('status' => "success" , 'result_img' => $images);
        print_r(json_encode($response));exit;

    }

    public function showManagementConsole()
    {
        $data = array();
        $data['currentMenuItem'] = "Management Console";

        return \View::make('hiptna.managementconsole')->with('data', $data);
    }

    public function showSettings()
    {
        $data = array();
        $data['currentMenuItem'] = "Settings";
        $currentInstance    =       Session::get('currentInstance');

        $settings           =       $this->getTnaInstanceSettings();

        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $data["lateness_threshold"] = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $data['current_instance']   =   $currentInstance;

        if( $currentInstance == "NR01" || $currentInstance == "NR02" ) {
            $data["start_time"]     = \Tnasetting::where('name', 'like', $settings["start_time"])->first()->value;
        } else {
            $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;
            $data["proximity_target"] = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;
            $data["tnaproximitydistance_a"] = \Tnasetting::where('name', 'like', $settings["tnaproximitydistance_a"])->first()->value;
            $data["tnaproximitytime_a"] = \Tnasetting::where('name', 'like', $settings["tnaproximitytime_a"])->first()->value;
            $data["tnaproximitydistance_b"] = \Tnasetting::where('name', 'like', $settings["tnaproximitydistance_b"])->first()->value;
            $data["tnaproximitytime_b"] = \Tnasetting::where('name', 'like', $settings["tnaproximitytime_b"])->first()->value;
            $data["tnaproximitydistance_c"] = \Tnasetting::where('name', 'like', $settings["tnaproximitydistance_c"])->first()->value;
            $data["tnaproximitytime_c"] = \Tnasetting::where('name', 'like', $settings["tnaproximitytime_c"])->first()->value;
            $data["late_sms_trigger"] = \Tnasetting::where('name', 'like', $settings["late_sms_trigger"])->first()->value;
            $data["absence_sms_trigger"] = \Tnasetting::where('name', 'like', $settings["absence_sms_trigger"])->first()->value;
        }
        return \View::make('hiptna.mc_settings')->with('data', $data);
    }

    public function updateThresholds() {

        error_log("In editSettings");
        $data = array();
        $data['settings'] = array();
        $settings = $this->getTnaInstanceSettings();
        $currentInstance    =       Session::get('currentInstance');
        if( $currentInstance == "NR01" || $currentInstance == "NR02" ) {
            $lateness_threshold     = Input::get('lateness_threshold');
            $start_time             = Input::get('start_time');
            \Tnasetting::where( 'name', 'like', $settings['lateness_threshold'] )->update(['value' => $lateness_threshold]);
            \Tnasetting::where( 'name', 'like', $settings['start_time'] )->update(['value' => $start_time]);
        } else {
            $lateness_threshold = Input::get('lateness_threshold');
            $proximity_target = Input::get('proximity_target');
            $tnaproximitydistance_a = Input::get('tnaproximitydistance_a');
            $tnaproximitytime_a = Input::get('tnaproximitytime_a');
            $tnaproximitydistance_b = Input::get('tnaproximitydistance_b');
            $tnaproximitytime_b = Input::get('tnaproximitytime_b');
            $tnaproximitydistance_c = Input::get('tnaproximitydistance_c');
            $tnaproximitytime_c = Input::get('tnaproximitytime_c');
            $late_sms_trigger = Input::get('late_sms_trigger');
            $absence_sms_trigger = Input::get('absence_sms_trigger');

            \Tnasetting::where( 'name', 'like', $settings['lateness_threshold'] )->update(['value' => $lateness_threshold]);
            \Tnasetting::where( 'name', 'like', $settings['proximity_target'] )->update(['value' => $proximity_target]);
            \Tnasetting::where( 'name', 'like', $settings['tnaproximitydistance_a'] )->update(['value' => $tnaproximitydistance_a]);
            \Tnasetting::where( 'name', 'like', $settings['tnaproximitytime_a'] )->update(['value' => $tnaproximitytime_a]);
            \Tnasetting::where( 'name', 'like', $settings['tnaproximitydistance_b'] )->update(['value' => $tnaproximitydistance_b]);
            \Tnasetting::where( 'name', 'like', $settings['tnaproximitytime_b'] )->update(['value' => $tnaproximitytime_b]);
            \Tnasetting::where( 'name', 'like', $settings['tnaproximitydistance_c'] )->update(['value' => $tnaproximitydistance_c]);
            \Tnasetting::where( 'name', 'like', $settings['tnaproximitytime_c'] )->update(['value' => $tnaproximitytime_c]);
            \Tnasetting::where( 'name', 'like', $settings['late_sms_trigger'] )->update(['value' => $late_sms_trigger]);
            \Tnasetting::where( 'name', 'like', $settings['absence_sms_trigger'] )->update(['value' => $absence_sms_trigger]);
        }
        error_log("updateThresholds : lateness_threshold = $lateness_threshold");

        return \Response::json($data['settings']);


    }

    public function addReportuser()
    {

        $objData            =       json_decode(\Input::get("newrecord"));
        $currentInstance    =       Session::get('currentInstance');
        $objReport = new \Reportrecipients();

        // Shafeeque - how does this get set if it is before the variable definition? Did you test this?
        // Have you added the IM / CE fields in the database
        if($currentInstance == 'IM')  {
            $objReport->IM = 1;
            $objReport->CE = 0;
        } if($currentInstance == 'CE') {
            $objReport->CE = 1;
            $objReport->IM = 0;
        }


        $objReport->name = $objData->add_name;
        $objReport->email = $objData->add_email;
        $objReport->absence = $objData->add_absence;
        $objReport->lateness = $objData->add_time_management;
        $objReport->ws_proximity = $objData->add_ws_proximity;
        $objReport->daily = $objData->add_daily;
        $objReport->weekly = $objData->add_weekly;
        $objReport->monthly = $objData->add_monthly;
        $objReport->save();

        $lastInsertedID =$objReport->id;

        $reportJson =  \Reportrecipients::where('id',$lastInsertedID)->get();

        foreach ($reportJson as $value) {
            if($value->absence == 1){
                $absence = "checked='checked'";
            } else {
                $absence = "";
            }

            if($value->lateness == 1){
                $lateness = "checked='checked'";
            } else {
                $lateness = "";
            }

            if($value->ws_proximity == 1){
                $ws_proximity = "checked='checked'";
            } else {
                $ws_proximity = "";
            }

            if($value->daily == 1){
                $daily = "checked='checked'";
            } else {
                $daily = "";
            }

            if($value->weekly == 1){
                $weekly = "checked='checked'";
            } else {
                $weekly = "";
            }

            if($value->monthly == 1){
                $monthly = "checked='checked'";
            } else {
                $monthly = "";
            }



            $rows = '<tr>\
                    <td class="tnastafftd_name"> <input id="add_name_'.$value->id.'" class="form-control no-radius" placeholder="Name"  type="text" value="'.$value->name.'"> </td>\
                    <td class="tnastafftd_email"> <input id="add_email_'.$value->id.'" class="form-control no-radius" placeholder="Email" type="text" value="'.$value->email.'"> </td>\
                    <td class="tnastafftd_absence"> <input id="add_absence_'.$value->id.'" type="checkbox" value="" '.$absence.'> <br>Absence  </td>\
                    <td class="tnastafftd_time_management"> <input id="add_time_management_'.$value->id.'" type="checkbox" value=""   '.$lateness.' > <br>Lateness </td>\
                    <td style="width:15%;" class="tnastafftd_ws_proximity"> <input id="add_ws_proximity_'.$value->id.'" type="checkbox" value=""  '.$ws_proximity.'><br> WS Proximity </td>\
                    <td class="tnastafftd_daily"> <input id="add_daily_'.$value->id.'" value=""  type="checkbox" '.$daily.'> <br>Daily  </td>\
                    <td class="tnastafftd_weekly"> <input id="add_weekly_'.$value->id.'" value="" type="checkbox" '.$weekly.'> <br>Weekly </td>\
                    <td class="tnastafftd_monthy"> <input id="add_monthly_'.$value->id.'" value="" type="checkbox" '.$monthly.'> <br> Monthly </td>\
                    <td class="tnastafftd_update_update" style="width:17%;"> <a id="updateReportUser_'.$value->id.'" class="btn btn-default btn-delete btn-sm" onclick="updateReportUser('.$value->id.');" >Update</a> <a id="deleteReportuser_'.$value->id.'" class="btn btn-default btn-delete btn-sm" onclick="deleteReportUser('.$value->id.');" >Delete </a></td>\
                    <td></td>\
                  </tr>';
            }
        $data = array('row'=>$rows);
        print_r(json_encode($data));


    }


    public function showReportRecipients()
    {
         //$reportJson =  DB::select('SELECT * FROM reportrecipients ORDER BY created_at DESC');
                       /* print_r($reportJson);
                        exit();*/

        $reportJson =  \Reportrecipients::orderBy('created_at','DESC')
                                        ->get();

        return \Response::json($reportJson);



    }

    public function updateReportUser()
    {
        //echo "hi";

        $objData = json_decode(\Input::get("newrecord"));

        $objUpdateReport = \Reportrecipients::find($objData->update_id);
        $objUpdateReport->name = $objData->update_name;
        $objUpdateReport->email = $objData->update_email;
        $objUpdateReport->absence = $objData->update_absence;
        $objUpdateReport->lateness = $objData->update_time_management;
        $objUpdateReport->ws_proximity = $objData->update_ws_proximity;
        $objUpdateReport->daily = $objData->update_daily;
        $objUpdateReport->weekly = $objData->update_weekly;
        $objUpdateReport->monthly = $objData->update_monthly;
        $result = $objUpdateReport->save();

        //$queries = DB::getQueryLog();
        if($result) {

            $data = array('message' => "success");

        } else {

            $data = array('message' => "Faild");

        }

        print_r(json_encode($data));

    }

    public function deleteReportUser()
    {

        $objData = json_decode(\Input::get("newrecord"));

        $objDeleteReport = \Reportrecipients::find($objData->id);
        $objDeleteReport->delete();

        $data = array( 'id' => $objData->id );
        print_r(json_encode($data));


    }


    public function showStafflookup()
    {

        $data = array();
        $data['currentMenuItem'] = "Staff Lookup";

        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['previewurl'] = $assetsserver->value . 'hiptna/images/';

        return \View::make('hiptna.mc_stafflookup')->with('data', $data);
    }


    public function gettnastaff() {

        $surname = \Input::get('surname');
        $firstnames = \Input::get('firstnames');

        $surname =  "%" . $surname . "%";
        $firstnames =  "%" . $firstnames . "%";

        error_log("engage_filterstaff : $surname : $firstnames");

        $currentInstance        =   Session::get('currentInstance');

        error_log("gettnastaff : currentInstance = $currentInstance");

        if($currentInstance == 'IM'){
            $staff =  new \Imstaff();
        }else if($currentInstance == 'CE'){
            $staff =  new \Cestaff();
        }else{
            $staff =  new \Staff();
        }

        $staffdata =  $staff->where('surname', 'like', $surname)
                        ->where('surname', '<>', "")
                        ->where('firstnames', 'like', $firstnames)
                        ->orderBy('surname')
                        ->get();

        return \Response::json($staffdata);
    }

    public function getstaffthismonth () {

        $currentInstance = Session::get('currentInstance');

        if( $currentInstance == "NR01" || $currentInstance == "NR02" ) {
            $staff_month = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('external_user_id', '!=', "Vacant")->where('instance', '=', $currentInstance)->get()->count();
        } else {
            $staff_month = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('attendance', '=', "present")->where('external_user_id', '!=', "Vacant")->where('instance', '=', $currentInstance)->get()->count();
        }
        $maxDays=date('t');
        $currentDayOfMonth=date('j');

        /*$staff_count = \Staff::where('external_user_id', '!=', "Vacant")->get()->count(DB::raw('DISTINCT external_user_id'));
        $total_days = $currentDayOfMonth * $staff_count;*/
        $total_days = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('external_user_id', '!=', "Vacant")->where('instance', '=', $currentInstance)->get()->count();

        $sheduled_dayoff = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('first day of this month')))->where('day_off', '=', 1)->where('external_user_id', '!=', "Vacant")->where('instance', '=', $currentInstance)->get()->count();

        $total_needed = $total_days - $sheduled_dayoff;

        if($total_needed) {
            $percentage = ($staff_month / $total_needed) * 100 ;
        } else {
            $percentage = "N/A";
        }

        return round($percentage );
    }


    public function getstafftoday () {

        error_log("getstafftoday 10");

        // $staff_present = \Beaconmessage::select(array(DB::raw('count(distinct beaconmessages.external_user_id) as unique_count')))
        //         ->where('beaconmessages.created_at', ">", date('Y-m-d',strtotime('today midnight')))
        //         ->where('beaconmessages.event_code', "=","HIPJAM0002")
        //         ->get()->first();
        //         // ->where('beaconmessages.external_user_id','!=', '')

        //         return $staff_present["unique_count"];

        // $staff_present = \Staff::join('beaconmessages' , 'staff.external_user_id','=', 'beaconmessages.external_user_id')
        //         ->select(array(DB::raw('count(distinct beaconmessages.external_user_id) as unique_count')))
        //         ->where('beaconmessages.created_at', ">", strtotime("today midnight"))
        //         ->where('staff.external_user_id','=', 'beaconmessages.external_user_id')
        //         ->where('staff.external_user_id','!=', 'vacant')
        //         ->where('beaconmessages.external_user_id','!=', '')
        //         ->where('beaconmessages.event_code', "=","HIPJAM0002")
        //         ->get()->first();

        //         return $staff_present["unique_count"];


        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $currentInstance = Session::get('currentInstance');

        if( $currentInstance == "NR01" || $currentInstance == "NR02" ) {

            $staff_present = \Beaconmessage::select(array(DB::raw('count(distinct beaconmessages.external_user_id) as unique_count')))
            ->where('beaconmessages.created_at', ">", date('Y-m-d',strtotime('today midnight')))
            ->where('beaconmessages.app_id','=', 'HZDEMO')
            ->where('beaconmessages.external_user_id','!=', '')
            ->where('beaconmessages.event_code', "=","HIPJAM0002")
            ->get()->first();
        } else {
            $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;
            $staff_table = $this->getStaffTable();

            $staff_present = \Beaconmessage::join($staff_table , $staff_table.'.external_user_id','=', 'beaconmessages.external_user_id')
            ->select(array(DB::raw('count(distinct beaconmessages.external_user_id) as unique_count')))
            ->where('beaconmessages.created_at', ">", date('Y-m-d',strtotime('today midnight')))
            ->where($staff_table.'.external_user_id','!=', 'vacant')
            ->where('beaconmessages.external_user_id','!=', '')
            ->where('beaconmessages.event_code', "=","HIPJAM0002")
            ->get()->first();
        }
        /*$staff_present = \Beaconmessage::join('staff' , 'staff.external_user_id','=', 'beaconmessages.external_user_id')
        ->select(array(DB::raw('count(distinct beaconmessages.external_user_id) as unique_count')))
        ->where('beaconmessages.created_at', ">", date('Y-m-d',strtotime('today midnight')))
        ->where('staff.external_user_id','!=', 'vacant')
        ->where('beaconmessages.external_user_id','!=', '')
        ->where('beaconmessages.event_code', "=","HIPJAM0002")
        ->get()->first();*/

        return $staff_present["unique_count"];
    }

    public function getstaffexpected () {

        $rosterObj          =   $this->getRosterTableObject();
        $instance           =   $this->getTnaInstancePrefix();

        $staff_expect       =   $rosterObj->select( $instance.'roster.day_start' )
            ->where( $instance.'roster.date' , "=" , date( "Y-m-d" ))
            ->where( $instance.'roster.external_user_id' , '!=', 'vacant' )
            ->groupBy( $instance.'roster.external_user_id' )
            ->get();

        $count = 0;
        if(count($staff_expect) > 0){
            foreach ($staff_expect as $value) {
                if (preg_match('/^\d{2}:\d{2}$/', $value['day_start'])) {
                    $count++;
                }/* else { print_r($value['day_start']); }*/
            }
        }
        return $count;
    }

    /*
     * Get Roster table object depends on current instance.
     */
    public function getRosterTableObject() {
        $currentInstance = Session::get('currentInstance');

        if($currentInstance == 'IM') {
            $rosterTableObject  =    new \Imroster();
        } else if($currentInstance == 'CE') {
            $rosterTableObject  =    new \Ceroster();
        } else {
            $rosterTableObject  =    new \Roster();
        }
        return $rosterTableObject;
    }



    public function getabsentstafftoday () {

        $staff_absent = $this->getStaffAbsentByDay(date('Y-m-d',strtotime('today midnight')));
        return sizeof($staff_absent);

        // $staff_present = \Staff::select(array(DB::raw('count(distinct staff.external_user_id) as unique_count')))
        //         ->where('staff.external_user_id','!=', 'vacant')
        //         ->whereNotIn('staff.external_user_id', (\Beaconmessage::select("external_user_id")->where('beaconmessages.created_at', ">", strtotime("today midnight"))->distinct()->get()->toArray()) )
        //         ->get()->first();

        //         return $staff_present["unique_count"];
    }

    public function getabsentstaffyesterday() {

        $absent_data = \Timeandattendance::select(array(DB::raw('COUNT( CASE WHEN  `attendance` <> "present" THEN 0 END ) AS value')))
                ->where('date', "=", date('Y-m-d',strtotime('yesterday')))
                ->where('instance', '=', Session::get('currentInstance'))
                ->get()->first();

                return $absent_data["value"];
    }

    public function getarrivedlatetoday () {

        /*$lateness_threshold = \Tnasetting::where('name', 'like', 'lateness_threshold')->first()->value;

        $staff_present = \Beaconmessage::leftJoin('staff' , 'staff.external_user_id','=', 'beaconmessages.external_user_id')
                ->select(array(DB::raw('count(distinct staff.external_user_id) as unique_count')))
                ->where('beaconmessages.created_at', ">", date('Y-m-d').' 08:05:00')
                ->where('staff.external_user_id','!=', 'vacant')
                ->where('beaconmessages.event_code', "=","HIPJAM0002")
                ->get()->first();*/

            // return $staff_present["unique_count"];
        $staff_lateness = $this->getStaffLateByDay(date('Y-m-d',strtotime('today midnight')));
        return sizeof($staff_lateness);

    }

    public function getarrivedlateyesterday() {
        $currentInstance = Session::get('currentInstance');
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"] )->first()->value;
        $absent_data = \Timeandattendance::select(array(DB::raw('COUNT( CASE WHEN  `punctuality` > '.$lateness_threshold.' THEN 0 END ) AS value')))
                ->where('date', "=", date('Y-m-d',strtotime('yesterday')))
                ->where('instance', '=', Session::get('currentInstance'))
                ->get()->first();

                return $absent_data["value"];
    }

    public function getproximityfailtoday () {

        /*$proximity_data = \Beaconmessage::leftJoin('staff' , 'staff.external_user_id','=', 'beaconmessages.external_user_id')
                ->select(array(DB::raw('count(distinct staff.external_user_id) as unique_count')))
                ->where('beaconmessages.created_at', ">", date('Y-m-d').' 08:05:00')
                ->where('staff.external_user_id','!=', 'vacant')
                ->where('beaconmessages.event_code', "=","HIPJAM0002")
                ->get()->first();

                return $proximity_data["unique_count"];*/
                return 0;
    }

    public function getproximityfailyesterday() {

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $proximity_data = \Timeandattendance::select(array(DB::raw('COUNT( CASE WHEN  `proximity` < '.$proximity_target.' THEN 0 END ) AS value')))
                ->where('date', "=", date('Y-m-d',strtotime('yesterday')))
                ->where('instance', '=', Session::get('currentInstance'))
                ->get()->first();

                return $proximity_data["value"];
    }

    public function getearlyleftyesterday() {

        $earlyleft_data = \Timeandattendance::select(array(DB::raw('COUNT( CASE WHEN shift_end < "17:00" THEN 0 END ) AS value')))
                ->where('date', "=", date('Y-m-d',strtotime('yesterday')))
                ->where('instance', '=', Session::get('currentInstance'))
                ->get()->first();

                return $earlyleft_data["value"];
    }

    public function showInstantmessaging()
    {

        $data = array();
        $data['currentMenuItem'] = "Instant Messaging";
        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'IM'){
            $data['hubs']            = \Imstaff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();
            $data['channels']        = \Imstaff::select(DB::raw('distinct channel'))->get()->toArray();
        }else if($currentInstance == 'CE'){
            $data['hubs']            = \Cestaff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();
            $data['channels']        = \Cestaff::select(DB::raw('distinct channel'))->get()->toArray();
        }else{
            $data['hubs']            = \Staff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();
            $data['channels']        = \Staff::select(DB::raw('distinct channel'))->get()->toArray();
        }
        /*$data['hubs']            = \Staff::select(DB::raw('distinct hubname'))->get()->toArray(); //print_r($data['hubs']); die();
        $data['channels']        = \Staff::select(DB::raw('distinct channel'))->get()->toArray();*/

        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $data['previewurl'] = $assetsserver->value . 'hiptna/images/';

        return \View::make('hiptna.mc_instantmessaging')->with('data', $data);
    }

    public function sendPushNotification($content, $push_hwid, $destFullName) {

        $notifications = array(array(
          "send_date" => "now", // YYYY-MM-DD HH:mm  OR 'now'
          "ignore_user_timezone" => true, // or false
          "content" => $content,
          "data" => array("url"=> $destFullName),
          "devices" => array($push_hwid)
          ));

        error_log("sendPushNotification notifications = " . print_r($notifications, true));

        $request = array(
          "application" => "FDDCE-9D44D",
          "auth" => "lHmQSQCaKouW8KBv8ayU2mkuCs2H4t4CeOGqGSdoE7zhpAXiPzBt1SEORBO62e0kB/jyj9vQkVOxPGzZpICV",
          "notifications" => $notifications,
          );
        $jsonData = json_encode(array("request" => $request));
        $jsonData = stripslashes($jsonData);

        error_log("sendPushNotification jsonData = $jsonData");
        error_log("sendPushNotification jsonData = $jsonData");

        $ch = curl_init('https://cp.pushwoosh.com/json/1.3/createMessage');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);
        error_log("sendPushNotification response : " . $response);

        return $response;
    }

    public function sendPushNotifications()
    {
        $data = array();

        $push_hwid = "";
        $external_user_id = Input::get('external_user_id');
        $content = Input::get('content');
        error_log("sendPushNotification external_user_id : " . $external_user_id);
        // $remote_page = $event->pushnotification->image_url; // This needs to be something like $event->pushNotification->image_url
        // $data = array("url" => $remote_page);

        $mb_extn = \Input::get("mb_ext");
        $assetsdiry = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $fullpathimage = $assetsdiry->value . 'hiptna/images/';
        $sourceFileName = $fullpathimage . 'preview' . '.' . $mb_extn;

        $devices = \Device::where('external_user_id', '=', $external_user_id)->get();

        $returnvals = array();
        $pushnotificationObj = new \Instantpush();

        //$brand_id = \Input::get('brand_id');
        //$pushnotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $pushnotificationObj->sender_user_id = \Auth::user()->id;
        $pushnotificationObj->message = \Input::get('content');
        $pushnotificationObj->image_url = $sourceFileName;
        $pushnotificationObj->isgroupmessage = 0;
        $pushnotificationObj->single_recipient_external_user_id = $external_user_id;
        $pushnotificationObj->single_recipient_hwid = 0;//\Input::get('preload');
        //$pushnotificationObj->group_channels = \Input::get('content');
        //$pushnotificationObj->group_store_types = \Input::get('image_url');

        if($pushnotificationObj->save()) {

            $mb_ext = \Input::get("mb_ext");
            $id = $pushnotificationObj->id;

            error_log("addPushSave : xxx mb_ext = $mb_ext");
            error_log("addPushSave : id = $id");

            $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
            $fullpath = $assetsdir->value . 'hiptna/images/';

            // Get Mobile File ///////////////////////////////////////////
            $sourceFullName = $fullpath . 'preview' . '.' . $mb_ext;
            $destFullName = $fullpath . $id . '.' . $mb_ext;

            error_log("addMediaSave: MB : sourceFullName : $sourceFullName :::: destFullName : $destFullName");
            \File::move($sourceFullName, $destFullName);

            $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
            $assetsPath = $assetsserver->value . 'hiptna/images/';
            $assetsFullPath = $assetsPath . $id . '.' . $mb_ext;

            $pushnotificationObj->image_url = $assetsFullPath;
            $pushnotificationObj->save();

            $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();

            $returnvals["status"] = 1;
            $returnvals["id"] = $pushnotificationObj->id;

        } else {
            $returnvals["status"] = 0;
        }

        foreach($devices as $device) {

            $push_hwid = $device->push_hwid;
            $this->sendPushNotification($content, $push_hwid,$assetsFullPath);
        }

        return $returnvals;
    }

    public function sendGroupNotifications(){

        error_log("sendGroupNotifications : 10 : ");
        $data = array();
        //print_r('kkkk'); die();
        $push_hwid = "";
        $content = Input::get('content');
        $hubs = Input::get('hub_names');
        $channels = Input::get('channels');
        error_log("sendGroupNotifications : hubs : " . print_r($hubs, true));
        error_log("sendGroupNotifications : channels : " . print_r($channels, true));

        $image_id = rand(10000000, 50000000);
        $mb_ext = \Input::get("mb_ext");
        $send_at    =   Input::get('send_at');

        $assetsdir = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsdir")->first();
        $previewFile = $assetsdir->value . 'hiptna/images/preview.' . $mb_ext;
        $destFile = $assetsdir->value . 'hiptna/images/' . 'im_' . $image_id . '.' . $mb_ext;
        \File::copy($previewFile, $destFile);

//////////////save value to instantpushes starts//////////////////
        $pushnotificationObj = new \Instantpush();

        //$brand_id = \Input::get('brand_id');
        //$pushnotificationObj->engagebrand_code = \Brand::find($brand_id)->code;
        $pushnotificationObj->sender_user_id = \Auth::user()->id;
        $pushnotificationObj->message = $content;
        $pushnotificationObj->image_url = $destFile;
        $pushnotificationObj->isgroupmessage = 1;
        $pushnotificationObj->send_at = $send_at;

        $pushnotificationObj->save();
//////////////save value to instantpushes ends//////////////////


        $assetsserver = \DB::table('systemconfig')->select("*")->where('name', '=', "assetsserver")->first();
        $assetsUrl = $assetsserver->value . 'hiptna/images/';
        $assetsUrlFullPath = $assetsUrl . 'im_' . $image_id . '.' . $mb_ext;

        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'IM'){
            $all_staff = \Imstaff::select(DB::raw('distinct external_user_id'))->whereIn('hubname', $hubs)->orWhereIn('channel', $channels)->get()->toArray();
        }else if($currentInstance == 'CE'){
            $all_staff = \Cestaff::select(DB::raw('distinct external_user_id'))->whereIn('hubname', $hubs)->orWhereIn('channel', $channels)->get()->toArray();
        }else{
            $all_staff = \Staff::select(DB::raw('distinct external_user_id'))->whereIn('hubname', $hubs)->orWhereIn('channel', $channels)->get()->toArray();
        }
        /*$all_staff = \Staff::select(DB::raw('distinct external_user_id'))->whereIn('hubname', $hubs)->orWhereIn('channel', $channels)->get()->toArray();*/ //print_r($all_staff); die();
        error_log("sendGroupNotifications : all_staff : " . print_r($all_staff, true));
        foreach ($all_staff as $staffs) {
            error_log("sendGroupNotifications : staff : " . print_r($staffs, true));
            $external_user_id = $staffs['external_user_id'];
            $devices = \Device::where('external_user_id', '=', $external_user_id)->get();
            foreach($devices as $device) {

                $push_hwid = $device->push_hwid;
                $this->sendPushNotification($content, $push_hwid, $assetsUrlFullPath);
            }
        }
        return 1;

    }

    public function showBeaconbatterymonitor()
    {

        $data = array();
        $data['currentMenuItem'] = "Beacon Battery Monitor";


        return \View::make('hiptna.mc_beaconbatterymonitor')->with('data', $data);
    }

    public function showExceptionreports()
    {

        $data = array();
        $data['currentMenuItem'] = "Exception Reports";


        return \View::make('hiptna.exceptionreports')->with('data', $data);
    }

    public function periodchartJsondata()
    {
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

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;

        $data['category'] = \Timeandattendance::select('date as label')->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get()->toArray();

        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'NR01' || $currentInstance == 'NR02' ){
            error_log("periodchartJsondata : processing nr");

            $data['currentInstance'] = $currentInstance;
            // Get Lateness Data
            $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
            $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
            $lateness_graph = array($staff_ontime, $staff_late);
            $data['lateness_graph'] = $lateness_graph;

        } else {
            error_log("periodchartJsondata : processing not_nr");
            $data['currentInstance'] = 'NOT_NR';

            $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

            // Get Absenteeism Data
            $present_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` = "present" THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_present = array("seriesname" => "Staff At Work", "data" => $present_data);
            $absent_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `attendance` <> "present" THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_absent = array("seriesname" => "Staff Not At Work", "data" => $absent_data);
            $staff_graph = array($staff_present, $staff_absent);
            $data['staff_graph'] = $staff_graph;


            // Get Lateness Data
            $ontime_data = \Timeandattendance::select(DB::raw('COUNT(*) as count'), DB::raw('COUNT( CASE WHEN  `punctuality` <= ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_ontime = array("seriesname" => "Staff on Time", "data" => $ontime_data);
            $late_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `punctuality` > ' . $lateness_threshold . ' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_late = array("seriesname" => "Late Staff", "data" => $late_data);
            $lateness_graph = array($staff_ontime, $staff_late);
            $data['lateness_graph'] = $lateness_graph;

            // Get Proximity Data
            $proximal_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` >= '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_proximal = array("seriesname" => "Staff At Work", "data" => $proximal_data);
            $away_data = \Timeandattendance::select(DB::raw('COUNT( CASE WHEN  `proximity` < '.$proximity_target.' THEN 0 END ) AS value'))->where('date', ">=", $start)->where('date', "<=", $end)->where('instance', '=', Session::get('currentInstance'))->groupBy('date')->get();
            $staff_away = array("seriesname" => "Staff Not At Work", "data" => $away_data);
            $staff_wsproximity = array($staff_proximal, $staff_away);
            $data['wsproximity_graph'] = $staff_wsproximity;
        }


        $data['report_period']      =   "Report for ".$start." to ". $end;
        $data['report_name_date']   =   $rep_name_period."_".$start."_".$end;


        $json = json_encode($data);

        print_r($json);

    }

    public function isStaffMemberAbsent($staffmember, $staff_present) {

        foreach($staff_present as $presentmember) {
            if($presentmember["external_user_id"] == $staffmember["external_user_id"]) { return false; }
        }

        return true;

    }

    public function getDummyExceptionData() {
        $data = array();

        // Testing
        for($i=1; $i<=350; $i++) {
            $record=array();
            $record["label"] = "xxx";
            $record["value"] = "5";
            $record["external_user_id"] = "123";
            array_push($data, $record);
        }

        return $data;
    }


    public function getStaffAbsentByDay($date) {


        // return $this->getDummyExceptionData();

        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'IM'){
            $staff_table = 'im_staff';
            $all_staff = \Imstaff::select( DB::raw('CONCAT(im_staff.surname," ",im_staff.firstnames) As label'), DB::raw('im_staff.id As value') , 'im_staff.external_user_id', 'im_staff.surname', 'im_staff.firstnames' ) ->where('im_staff.external_user_id','!=', 'vacant')->get()->toArray();
        }else if($currentInstance == 'CE'){
            $staff_table = 'ce_staff';
            $all_staff = \Cestaff::select( DB::raw('CONCAT(ce_staff.surname," ",ce_staff.firstnames) As label'), DB::raw('ce_staff.id As value') , 'ce_staff.external_user_id', 'ce_staff.surname', 'ce_staff.firstnames' ) ->where('ce_staff.external_user_id','!=', 'vacant')->get()->toArray();
        }else{
            $staff_table = 'staff';
            $all_staff = \Staff::select( DB::raw('CONCAT(staff.surname," ",staff.firstnames) As label'), DB::raw('staff.id As value') , 'staff.external_user_id', 'staff.surname', 'staff.firstnames' ) ->where('staff.external_user_id','!=', 'vacant')->get()->toArray();
        }
        /*$all_staff = \Staff::select( DB::raw('CONCAT(staff.surname," ",staff.firstnames) As label'), DB::raw('staff.id As value') , 'staff.external_user_id', 'staff.surname', 'staff.firstnames' ) ->where('staff.external_user_id','!=', 'vacant')->get()->toArray();*/

        $staff_present = \Beaconmessage::leftJoin($staff_table , $staff_table.'.external_user_id','=', 'beaconmessages.external_user_id')
                ->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label'), DB::raw($staff_table.'.id As value') , $staff_table.'.external_user_id',$staff_table.'.surname',$staff_table.'.firstnames' )
                ->where('beaconmessages.created_at', ">", $date)
                ->where($staff_table.'.external_user_id','!=', 'vacant')
                ->where('beaconmessages.event_code', "=","HIPJAM0002")
                ->groupBy($staff_table.'.external_user_id')
                ->get()->toArray();
                // ->whereNull('beaconmessages.external_user_id')

        $staff_absent = array();

        foreach($all_staff as $staffmember) {
            if($this->isStaffMemberAbsent($staffmember, $staff_present)){
                $staffmember["value"] = 1;
                $staffmember["csvcount"] = 1;
                array_push($staff_absent, $staffmember);
            }
        }


        return $staff_absent;
    }

    public function getStaffLateByDay($date) {
        // return $this->getDummyExceptionData();

        $tna = new \Tna();
        $dt_date = new \DateTime($date);

        $currentInstance            =   Session::get('currentInstance');
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        // $all_staff = \Staff::select( DB::raw('CONCAT(staff.surname," ",staff.firstnames) As label'), DB::raw('staff.id As value') , '*' ) ->where('staff.external_user_id','!=', 'vacant')->get()->toArray();

        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'IM'){
            $all_staff = \Imstaff::where('im_staff.external_user_id','!=', 'vacant')->get()->toArray();
        }else if($currentInstance == 'CE'){
            $all_staff = \Cestaff::where('ce_staff.external_user_id','!=', 'vacant')->get()->toArray();
        }else{
            $all_staff = \Staff::where('staff.external_user_id','!=', 'vacant')->get()->toArray();
        }
        /*$all_staff = \Staff::where('staff.external_user_id','!=', 'vacant')->get()->toArray();*/
        $late_staff = array();

        foreach ($all_staff as $staffmember) {
            // error_log("getStaffLateByDay : surname = " . $staffmember["surname"]);
            $chartrecord = array();
            $lateness = $tna->getPunctuality($staffmember, $dt_date);
            if($lateness > $lateness_threshold) {
                $chartrecord["label"] = $staffmember["surname"] . ', ' . $staffmember["firstnames"] ;
                $chartrecord["value"] = $lateness;
                $chartrecord["external_user_id"] = $staffmember["external_user_id"];
                $chartrecord["surname"] = $staffmember["surname"];
                $chartrecord["firstnames"] = $staffmember["firstnames"] ;
                $chartrecord["csvcount"] = 1 ;
                array_push($late_staff, $chartrecord);
            }
        }
        return($late_staff);
    }


    public function getStaffNotProximalByDay($date) {
        // return $this->getDummyExceptionData();
    }

    public function exceptionchart()
    {
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $data = array();

        //if($tab == 'absent'){
            // $staff_absent = \Timeandattendance::join('staff' , 'staff.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT(staff.surname," ",staff.firstnames) As label') , DB::raw('COUNT( CASE WHEN timeandattendance.attendance != "present" THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", date('Y-m-d',strtotime('last monday')))->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.attendance', '!=', "present")->groupBy('timeandattendance.external_user_id')->get()->toArray();

            $staff_absent = $this->getStaffAbsentByDay(date('Y-m-d',strtotime('today midnight')));
            $staff_absent1 = $this->set_graphdata($staff_absent);
            usort($staff_absent1, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data['staff_absent'] = $staff_absent1;


            // $staff_lateness = \Timeandattendance::join('staff' , 'staff.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT(staff.surname," ",staff.firstnames) As label') , DB::raw(' CASE WHEN timeandattendance.punctuality >= 5 THEN Sum( timeandattendance.punctuality ) END  AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", date('Y-m-d',strtotime('last monday')))->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.punctuality', '>=', '5')->groupBy('timeandattendance.external_user_id')->get()->toArray();
            $staff_lateness = $this->getStaffLateByDay(date('Y-m-d',strtotime('today midnight')));
            $staff_lateness1 = $this->set_graphdata($staff_lateness);
            usort($staff_lateness1, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data['staff_lateness'] = $staff_lateness1;

            $currentInstance = Session::get('currentInstance');
            $staff_table = $this->getStaffTable();

            $staff_proximity = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')
            ->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('timeandattendance.proximity AS value'), 'timeandattendance.external_user_id' )
            ->where('timeandattendance.date', ">=", date('Y-m-d',strtotime('yesterday midnight')))
            ->where('timeandattendance.external_user_id', '!=', "Vacant")
            ->where('timeandattendance.proximity', '>', 0)
            ->where('timeandattendance.proximity', '<=', $proximity_target)
            ->where('timeandattendance.instance', '=', $currentInstance)
            ->groupBy('timeandattendance.external_user_id')->get()->toArray();

            $staff_proximity1 = $this->set_graphdata($staff_proximity);
            usort($staff_proximity1, function($a, $b) {
                return $a['value'] - $b['value'];
            });
            $data['staff_proximity'] = $staff_proximity1;


            $staff_early_leaving = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('COUNT( CASE WHEN timeandattendance.shift_end < "17:00" THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", date('Y-m-d',strtotime('yesterday midnight')))->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.attendance', '=', "present")->where('timeandattendance.instance', '=', $currentInstance )->groupBy('timeandattendance.external_user_id')->get()->toArray();
            $staff_early_leaving1 = $this->set_graphdata($staff_early_leaving);
            usort($staff_early_leaving1, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data['staff_early_leaving'] = $staff_early_leaving1;

            $data['report_period']      =   "Report for ".date('Y-m-d')." to ". date('Y-m-d');
            $data['report_name_date']   =   "day_".date('Y-m-d')."_".date('Y-m-d');

        $json = json_encode($data);

        print_r($json);

    }

    public function set_graphdata($data)
    {

        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] =$value;
            $result[$key]['link'] = "JavaScript:membergraph(".$value['external_user_id'].")";
        }
        return $result;
        /*$res_aray = array();
        foreach ($data as $key => $value) {
            if(array_key_exists($value->value , $result)) {
                $result[$value->value] .= ',' . $value->label;
            } else {
                $result[$value->value]  =   $value->label;
            }
        }
        $i = 0;
        foreach ($result as $key => $value) {
            $res_aray[$i]['label'] = $value;
            $res_aray[$i]['value'] = $key;
            $i++;
        }

        return($res_aray);*/

    }

    /*
     *
     */
    public function periodExceptionchartJsondata( $period = '' , $venuefrom = '' , $venueto = '' , $currentInstance = '' )
    {
        if( $currentInstance == '') {
            $currentInstance    =       Session::get('currentInstance');
        } else {
            $currentInstance    =       $currentInstance;
        }

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $data               =       array() ;
        $start              =       '';
        $end                =       '';
        $rep_name_period    =       '';
        $return_value       =       FALSE;

        if(!isset($period) || empty($period)) {
            $period = Input::get('period');
        } else {
            $return_value       =   TRUE;
        }

        if( isset( $venuefrom ) && $venuefrom != "") {
            $start      =   $venuefrom;
        } else {
            $start      =   $venuefrom = Input::get('start');
        }

        if( isset( $venueto ) && $venueto != "") {
            $end      =   $venueto;
        } else {
            $end        =   $venueto  = Input::get('end');
        }

        if($period == 'rep7day'){
            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');
            $rep_name_period    =   "week";
        } else if($period == 'repthismonth'){
            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d');
            $rep_name_period    =   "month";
        } else if($period == 'replastmonth'){
            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));
            $rep_name_period    =   "month";
        } else if($period == 'daterange'){
            $start      =   $venuefrom;
            $end        =   $venueto;
            $rep_name_period    =   "date";
        } else if($period == 'today'){
            $this->exceptionchart(); die();
        }


        $staff_table = $this->getStaffTable();

        $staff_absent = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('COUNT( CASE WHEN timeandattendance.attendance != "present" THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.attendance', '!=', "present")->where('timeandattendance.instance', '=', $currentInstance)->groupBy('timeandattendance.external_user_id')->get()->toArray();

        $staff_absent1 = $this->set_graphdata($staff_absent);
        usort($staff_absent1, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        $data['staff_absent'] = $staff_absent1;

        error_log("periodExceptionchartJsondata start : " . $start);
        error_log("periodExceptionchartJsondata lateness_threshold : " . $lateness_threshold);

        error_log("periodExceptionchartJsondata start = $start");
        error_log("periodExceptionchartJsondata end = $end");
        error_log("periodExceptionchartJsondata lateness_threshold = $lateness_threshold");

        $staff_lateness = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')
        ->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('COUNT( CASE WHEN (CAST(timeandattendance.punctuality AS SIGNED )) > ' . $lateness_threshold . ' THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )
        ->where('timeandattendance.date', ">=", $start)
        ->where('timeandattendance.date', "<=", $end)
        ->where($staff_table.'.external_user_id', '!=', "Vacant")
        ->where(DB::raw('(CAST(timeandattendance.punctuality AS SIGNED))'), '>', $lateness_threshold)
        ->where('timeandattendance.instance', '=', $currentInstance )
        ->groupBy($staff_table.'.external_user_id')->get()->toArray();
        error_log("staff_lateness " . print_r($staff_lateness, true));

        $staff_lateness1 = $this->set_graphdata($staff_lateness);
        usort($staff_lateness1, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        $data['staff_lateness'] = $staff_lateness1;

        $staff_proximity = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')
        ->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('COUNT( CASE WHEN timeandattendance.proximity < '.$proximity_target.' THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )
        ->where('timeandattendance.date', ">=", $start)
        ->where('timeandattendance.date', "<=", $end)
        ->where('timeandattendance.external_user_id', '!=', "Vacant")
        ->where('timeandattendance.proximity', '<', $proximity_target)
        ->where('timeandattendance.instance', '=', $currentInstance )
        ->groupBy('timeandattendance.external_user_id')->get()->toArray();
        $staff_proximity1 = $this->set_graphdata($staff_proximity);
        usort($staff_proximity1, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        $data['staff_proximity'] = $staff_proximity1;

        $staff_early_leaving = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('COUNT( CASE WHEN timeandattendance.shift_end < "17:00" THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.attendance', '=', "present")->where('timeandattendance.instance', '=', $currentInstance)->groupBy('timeandattendance.external_user_id')->get()->toArray();
        $staff_early_leaving1 = $this->set_graphdata($staff_early_leaving);
        usort($staff_early_leaving1, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        $data['staff_early_leaving'] = $staff_early_leaving1;
        $data['report_period']      =   "Report for " . $start . " to " . $end;
        $data['report_name_date']   =   $rep_name_period."_".$start."_".$end;
        $json = json_encode($data);

        if($return_value == TRUE) {
            return $json;
        } else {
            print_r($json);
        }
    }

    public function memberGraph()
    {
        $data = array() ;
        $period = Input::get('period');
        $staff_id = Input::get('staff_id');
        $start = '';
        $end = '';

        if($period == 'rep7day'){
            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');
            $reportPeriod = 'This Week';
        }else if($period == 'repthismonth'){
            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d');
            $reportPeriod = 'This Month';
        }else if($period == 'replastmonth'){
            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));
            $reportPeriod = 'Last Month';
        }else if($period == 'daterange'){
            $start = Input::get('start');
            $end = Input::get('end');
            $reportPeriod = 'Date Range';
        }

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $currentInstance = Session::get('currentInstance');
        $staff_table = $this->getStaffTable();

        $staff_absent = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As name') , DB::raw(' CASE WHEN timeandattendance.attendance != "present" THEN 1 ELSE 0 END AS value'), 'timeandattendance.external_user_id', 'timeandattendance.attendance','timeandattendance.date AS label' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '=', $staff_id)->where('timeandattendance.instance', '=', $currentInstance )->orderBy('timeandattendance.date', 'asc')->get()->toArray();
        $staff_absent1 = $staff_absent;//$this->set_graphdata($staff_absent);

        $data['staff_absent'] = $staff_absent1;


        $staff_lateness = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As name') , 'timeandattendance.punctuality AS value' /*DB::raw(' CASE WHEN timeandattendance.punctuality > 5 THEN 0 ELSE timeandattendance.punctuality END AS value')*/, 'timeandattendance.external_user_id','timeandattendance.date AS label' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '=', $staff_id)->where('timeandattendance.instance', '=', $currentInstance )->orderBy('timeandattendance.date', 'asc')->get()->toArray();
        $staff_lateness1 = $staff_lateness;//$this->set_graphdata($staff_lateness);

        $data['staff_lateness'] = $staff_lateness1;


        $staff_proximity = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As name') , 'timeandattendance.proximity AS value'/*DB::raw(' CASE WHEN timeandattendance.proximity < '.$proximity_target.' THEN 0 ELSE timeandattendance.proximity END AS value')*/, 'timeandattendance.external_user_id','timeandattendance.date AS label' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '=', $staff_id)->where('timeandattendance.instance', '=', $currentInstance )->orderBy('timeandattendance.date', 'asc')->get()->toArray();
        $staff_proximity1 = $staff_proximity;//$this->set_graphdata($staff_proximity);

        $data['staff_proximity'] = $staff_proximity1;

        $data['report_date'] = $reportPeriod." ".$start."_".$end;

        $json = json_encode($data);

        print_r($json);
    }


    public function showGraphdrilldown()
    {

        $data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('hiptna.graphdrilldown')->with('data', $data);
    }

    public function fileupload()
    {

        if (Input::file('cfile')) {
            $file = Input::file('cfile');
            $month = Input::get('cmonth');
            $year = Input::get('cyear');
            $email = Input::get('cemail');
            // setting up rules
            $rules = array('cfile' => array('required', 'in:csv'));

            $validator = Validator::make(
                [
                    'file'      => $file,
                    'extension' => strtolower($file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv',
                ]
            );
            if ($validator->fails()) {
                //echo "Not Valid";
                return Redirect::to('hiptna_showSettings')->withInput()->withErrors($validator);
            }else{
                //echo "Valid";
                //\Staff::truncate();
                $currentInstance = Session::get('currentInstance');
                if($currentInstance == 'IM'){
                    \Imstaff::truncate();
                }else if($currentInstance == 'CE'){
                    \Cestaff::truncate();
                }else{
                    \Staff::truncate();
                }

                $extension = Input::file('cfile')->getClientOriginalExtension();

                $all_rows = array();
                $header = null;
                $fp = fopen($file,"r");
                $i = 0;

                $delimiter = $this->getFileDelimiter($file, 5); //Check 5 lines to determine the delimiter
                $all_rows = $this->getFileData($fp, $delimiter, $file);// fetch all data from csv as an array


                //echo '<pre>'; print_r($all_rows); die();

                $save = $this->saveStaffRecord( $all_rows, $month, $year ); //save all records to staff table

                fclose($fp);
                if($save){
                    $msg = "<b> successfully uploaded</b>";
                }else{
                    $msg = "<font color='red'><b> upload failed</b></font>";
                }
                //email notification of roster file upload
                    $message = "";
                    $to = $email;
                    $subject = "Roster Uploading Notification";
                    $send = \Mail::send('hiptna.rosteremail', array('msg' => $msg, 'email' => $to, 'month' => $month, 'year'=>$year ), function($message) use($to, $subject)
                    {
                        $message->to($to)->subject($subject);
                    });

                    if($send){ $msg = "done"; }
                    else{ $msg = 'not sent'; }
                    error_log("email notification of roster file upload");
                print_r('<span id="infomessgae" style="color:green;text-align:center;font-size:36px;">Your upload is finished. <br><br>You may now close this window</span>'); die();
                //return Redirect::to('hiptna_showSettings')->with('msg',$msg);
            }

        }
    }

    function getFileDelimiter($file, $checkLines = 2){
        $file = new \SplFileObject($file);
        $delimiters = array(
          ',',
          '\t',
          ';',
          '|',
          ':'
        );
        $results = array();
        $i = 0;
         while($file->valid() && $i <= $checkLines){
            $line = $file->fgets();
            foreach ($delimiters as $delimiter){
                $regExp = '/['.$delimiter.']/';
                $fields = preg_split($regExp, $line);
                if(count($fields) > 1){
                    if(!empty($results[$delimiter])){
                        $results[$delimiter]++;
                    } else {
                        $results[$delimiter] = 1;
                    }
                }
            }
           $i++;
        }
        $results = array_keys($results, max($results));
        return $results[0];
    }

    public function getFileData($fp, $delimiter, $file){

        $header = null;
        $i = 0;
        /*if( $delimiter == ','){
            while ($line = stream_get_line($fp, 4096, "\r")) {
                $row = str_getcsv($line, $delimiter);
                if($i != 0 ){
                    if ($header === null) {
                        $header = array_map('trim',$row);
                        $header = $this->modifyHeader($header);
                        //print_r($header); die();
                        continue;
                    }
                    if( sizeof($header) == sizeof($row)) {
                        $all_rows[] = array_combine($header, $row);
                    }
                }
                $i++;
            } array_shift($all_rows);

        } else if( $delimiter == ';') {
            $csvData = file_get_contents($file);
            $lines = explode(PHP_EOL, $csvData);
            $all_rows = array();//print_r($lines); die();
            foreach ($lines as $line) {
                $all_row[] = str_getcsv($line,';');
            }
            array_shift($all_row);
            array_pop($all_row);
            foreach ($all_row as $row) {
                if($i == 0 ){
                    $header = array_map('trim',$row);
                    $header = $this->modifyHeader($header);
                    //echo '<pre>'; print_r($header); die();
                }
                else if($i != 0 ){
                    //$all_rows[] = array_combine($header, array_map('trim',$row));
                    $all_rows[] = array_combine($header, $row);
                    error_log('error',count($row));  //echo '<pre>'; print_r($all_rows); die();
                }
                $i++;
            }
            array_shift($all_rows);
        }*/
            $csvData = file_get_contents($file);
            $lines = explode(PHP_EOL, $csvData);
            $all_rows = array();//print_r($lines); die();
            foreach ($lines as $line) {
                $all_row[] = str_getcsv($line,$delimiter);
            }
            array_shift($all_row);
            array_pop($all_row);
            foreach ($all_row as $row) {
                if($i == 0 ){
                    $header = array_map('trim',$row);
                    $header = $this->modifyHeader($header);
                    //echo '<pre>'; print_r($header); die();
                }
                else if($i != 0 ){
                    //$all_rows[] = array_combine($header, array_map('trim',$row));
                    $all_rows[] = array_combine($header, $row);
                    error_log('error',count($row));  //echo '<pre>'; print_r($all_rows); die();
                }
                $i++;
            }
            array_shift($all_rows);
        return $all_rows;
    }

    public function modifyHeader($charset){

        $words_so_far = array();
        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $i = 1;
        // for each word, check if we've encountered it so far
        //    - if not, add it to our list
        //    - if yes, replace it
        foreach($charset as $k => $word){
            if(in_array($word, $words_so_far)){
                $charset[$k] = $word.$i;
                if($word == 'Hour'){
                    $i++;
                }
            }
            else {
                $words_so_far[] = $word;
            }

        }
        return $charset;
    }


    public function cleanField( $field ) {

        return str_replace("\n", "", $field);
    }

    public function saveStaffRecord( $all_rows, $month, $year )
    {
        $save = '';
        foreach ($all_rows as $key => $value) {
            if($value['Full EE Name'] != ''){

                $exp_name_ar = explode(" ",$value['Full EE Name']);
                $last = end($exp_name_ar);
                $first = trim(chop($value['Full EE Name'],$last));
                $day_array= array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Lunch Times','Monday1','Tuesday1','Wednesday1','Thursday1','Friday1','Saturday1','Sunday1','Lunch Times1','Monday2','Tuesday2','Wednesday2','Thursday2','Friday2','Saturday2','Sunday2','Lunch Times2','Monday3','Tuesday3','Wednesday3','Thursday3','Friday3','Saturday3','Sunday3','Lunch Times3','Monday4','Tuesday4','Wednesday4','Thursday4','Friday4','Saturday4','Sunday4','Lunch Times4');
                //$day_array= array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Lunch Times');
                $split_array = array();
                foreach ($day_array as $days) {
                    $split_data = explode("-", $value[$days]);
                    $count = count($split_data);

                    if($count >1 ){
                        $split_array[$days."_start"] = $split_data[0];
                        $split_array[$days."_end"] = $split_data[1];
                    }else{
                        $split_array[$days."_start"] = $value[$days];
                        $split_array[$days."_end"] = $value[$days];
                    }
                }

                foreach ($split_array as $key => $val) {

                    $t = str_replace(" ","", $val);
                    if (preg_match('/^\d:\d{2}:\d{2}$/', $t)) $t = "0" . $t;
                    if (preg_match('/^\d:\d{2}$/', $t)) $t = "0" . $t;
                    $split_array[$key] = $t;
                } $t = "";

                ////echo '<pre>';
                //print_r($split_array); die();
                $this->saveRosterrecord($value['EE Number'], $month, $year, $split_array);

                //insert csv data into mysql table
                $currentInstance = Session::get('currentInstance');
                if($currentInstance == 'IM'){
                    $staffObject        =   new \Imstaff();
                } else if($currentInstance == 'CE'){
                    $staffObject        =   new \Cestaff();
                } else {
                    $staffObject        =   new \Staff();
                }
                        $staffObject->firstnames = $first;
                        $staffObject->surname = $last;
                        $staffObject->external_user_id = $this->cleanField($value['EE Number']);
                        $staffObject->gender = $value['Gender'];
                        $staffObject->dob = date('Y-m-d', strtotime($value['Birth date']));
                        $staffObject->storetype = $value['Grading'];
                        $staffObject->province = $value['Province'];
                        $staffObject->hubname = $value['Hub Name'];
                        $staffObject->channel = $value['Channel'];
                        $staffObject->smollan_cellphone = $value['Smollan Cellphone Number'];
                        $staffObject->other_cellphone = $value['Other Phone Number'];
                        $staffObject->email = $value['Email'];
                        $staffObject->monday_start = $split_array['Monday_start'];
                        $staffObject->monday_end = $split_array['Monday_end'];
                        $staffObject->tuesday_start = $split_array['Tuesday_start'];
                        $staffObject->tuesday_end = $split_array['Tuesday_end'];
                        $staffObject->wednesday_start = $split_array['Wednesday_start'];
                        $staffObject->wednesday_end = $split_array['Wednesday_end'];
                        $staffObject->thursday_start = $split_array['Thursday_start'];
                        $staffObject->thursday_end = $split_array['Thursday_end'];
                        $staffObject->friday_start = $split_array['Friday_start'];
                        $staffObject->friday_end = $split_array['Friday_end'];
                        $staffObject->saturday_start = $split_array['Saturday_start'];
                        $staffObject->saturday_end = $split_array['Saturday_end'];
                        $staffObject->sunday_start = $split_array['Sunday_start'];
                        $staffObject->sunday_end = $split_array['Sunday_end'];
                        $staffObject->lunch_start = $split_array['Lunch Times_start'];
                        $staffObject->lunch_end = $split_array['Lunch Times_end'];
                        $staffObject->lunch_length = $this->get_time_difference(trim($split_array['Lunch Times_end']), trim($split_array['Lunch Times_start']));
                        $staffObject->total_hours = $value['Hour'];
                        $save = $staffObject->save();


                }
                /*$save = \Staff::create(['firstnames'=>$first,
                        'surname'=>$last,
                        'external_user_id'=>$this->cleanField($value['EE Number']),
                        'gender'=>$value['Gender'],
                        'dob'=>date('Y-m-d', strtotime($value['Birth date'])),
                        'storetype'=>$value['Grading'],
                        'province'=>$value['Province'],
                        'hubname'=>$value['Hub Name'],
                        'channel'=>$value['Channel'],
                        'smollan_cellphone'=>$value['Smollan Cellphone Number'],
                        'other_cellphone'=>$value['Other Phone Number'],
                        'email'=>$value['Email'],
                        'monday_start'=>$split_array['Monday_start'],
                        'monday_end'=>$split_array['Monday_end'],
                        'tuesday_start'=>$split_array['Tuesday_start'],
                        'tuesday_end'=>$split_array['Tuesday_end'],
                        'wednesday_start'=>$split_array['Wednesday_start'],
                        'wednesday_end'=>$split_array['Wednesday_end'],
                        'thursday_start'=>$split_array['Thursday_start'],
                        'thursday_end'=>$split_array['Thursday_end'],
                        'friday_start'=>$split_array['Friday_start'],
                        'friday_end'=>$split_array['Friday_end'],
                        'saturday_start'=>$split_array['Saturday_start'],
                        'saturday_end'=>$split_array['Saturday_end'],
                        'sunday_start'=>$split_array['Sunday_start'],
                        'sunday_end'=>$split_array['Sunday_end'],
                        'lunch_start'=>$split_array['Lunch Times_start'],
                        'lunch_end'=>$split_array['Lunch Times_end'],
                        'lunch_length'=>$this->get_time_difference(trim($split_array['Lunch Times_end']), trim($split_array['Lunch Times_start'])),
                        'total_hours'=>$value['Hour']

                    ]);*/
            }

        return $save;
    }

    public function saveRosterrecord($EENumber, $month, $year, $split_array){

        /*$day_array= array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Lunch Times');*/
        /*$day_array= array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Lunch Times','Monday1','Tuesday1','Wednesday1','Thursday1','Friday1','Saturday1','Sunday1','Lunch Times1','Monday2','Tuesday2','Wednesday2','Thursday2','Friday2','Saturday2','Sunday2','Lunch Times2','Monday3','Tuesday3','Wednesday3','Thursday3','Friday3','Saturday3','Sunday3','Lunch Times3','Monday4','Tuesday4','Wednesday4','Thursday4','Friday4','Saturday4','Sunday4','Lunch Times4');*/
        $day_array= array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Monday1','Tuesday1','Wednesday1','Thursday1','Friday1','Saturday1','Sunday1','Monday2','Tuesday2','Wednesday2','Thursday2','Friday2','Saturday2','Sunday2','Monday3','Tuesday3','Wednesday3','Thursday3','Friday3','Saturday3','Sunday3','Monday4','Tuesday4','Wednesday4','Thursday4','Friday4','Saturday4','Sunday4');
        //$year = date("Y");
        $day_name = date('l', strtotime("$month 1 $year"));
        $date = date('Y-m-d', strtotime("$month 1 $year"));

        $last_date  = date('Y-m-t', strtotime("$month 1 $year"));
        $namecheck = null;
        $i = '';
        $currentInstance = Session::get('currentInstance');
        foreach ($day_array as $days) {
            $dayofweek = strftime("%A",strtotime($date));
            if($days == 'Lunch Times'){ $i = '';
            }else if($days == 'Lunch Times1'){ $i = 1;
            }else if($days == 'Lunch Times2'){ $i = 2;
            }else if($days == 'Lunch Times3'){ $i = 3;
            }else if($days == 'Lunch Times4'){ $i = 4; }

            if($dayofweek == $days || $namecheck != null){
                $namecheck = 1;
                if( $date <= $last_date ){
                    // $currentInstance = Session::get('currentInstance');
                    if($currentInstance == 'IM'){
                        $rosterObj              =   new \Imroster();
                        $checkdata = \Imroster::where('external_user_id', '=', $this->cleanField($EENumber) )->where('date', '=', $date )->first();
                    }else if($currentInstance == 'CE'){
                        $rosterObj              =   new \Ceroster();
                        $checkdata = \Ceroster::where('external_user_id', '=', $this->cleanField($EENumber) )->where('date', '=', $date )->first();
                    }else{
                        $rosterObj              =   new \Roster();

                        $checkdata = \Roster::where('external_user_id', '=', $this->cleanField($EENumber) )->where('date', '=', $date )->first();
                    }
                    /*$checkdata = \Roster::where('external_user_id', '=', $this->cleanField($EENumber) )->where('date', '=', $date )->first(); */
                    if(!empty($checkdata)) {
                        $checkdata->day_start=$split_array[$days.'_start'];
                        $checkdata->day_end=$split_array[$days.'_end'];
                        $checkdata->lunch_start=$split_array['Lunch Times'.$i.'_start'];
                        $checkdata->lunch_end=$split_array['Lunch Times'.$i.'_end'];
                        $checkdata->lunch_length=$this->get_time_difference(trim($split_array['Lunch Times'.$i.'_end']), trim($split_array['Lunch Times'.$i.'_start']));
                        $checkdata->save();
                        /*$checkdata->update([
                                'day_start'=>$split_array[$days.'_start'],
                                'day_end'=>$split_array[$days.'_end'],
                                'lunch_start'=>$split_array['Lunch Times'.$i.'_start'],
                                'lunch_end'=>$split_array['Lunch Times'.$i.'_end'],
                                'lunch_length'=>$this->get_time_difference(trim($split_array['Lunch Times'.$i.'_end']), trim($split_array['Lunch Times'.$i.'_start']))
                            ]);*/
                    } else {
                        $rosterObj->external_user_id=$this->cleanField($EENumber);
                        $rosterObj->date=$date;
                        $rosterObj->day_start=$split_array[$days.'_start'];
                        $rosterObj->day_end=$split_array[$days.'_end'];
                        $rosterObj->lunch_start=$split_array['Lunch Times'.$i.'_start'];
                        $rosterObj->lunch_end=$split_array['Lunch Times'.$i.'_end'];
                        $rosterObj->lunch_length=$this->get_time_difference(trim($split_array['Lunch Times'.$i.'_end']), trim($split_array['Lunch Times'.$i.'_start']));
                        $save = $rosterObj->save();

                    }
                    $date = date('Y-m-d',strtotime("+1 day", strtotime($date)));
                }
            }

        }

    }
    // public function fileupload()
    // {

    //     if (Input::file('cfile')) {
    //         $file = Input::file('cfile');
    //         // setting up rules
    //         $rules = array('cfile' => array('required', 'in:csv'));

    //         $validator = Validator::make(
    //             [
    //                 'file'      => $file,
    //                 'extension' => strtolower($file->getClientOriginalExtension()),
    //             ],
    //             [
    //                 'file'          => 'required',
    //                 'extension'      => 'required|in:csv',
    //             ]
    //         );
    //         if ($validator->fails()) {
    //             //echo "Not Valid";
    //             return Redirect::to('hiptna_showSettings')->withInput()->withErrors($validator);
    //         }else{
    //             //echo "Valid";
    //             \Staff::truncate();
    //             $extension = Input::file('cfile')->getClientOriginalExtension();

    //             $all_rows = array();
    //             $all_row = array();
    //             $header = null;
    //             $fp = fopen($file,"r");
    //             $i = 0;

    //             $csvData = file_get_contents($file);
    //             $lines = explode(PHP_EOL, $csvData);
    //             $all_rows = array();//print_r($lines); die();
    //             foreach ($lines as $line) {
    //                 $all_row[] = str_getcsv($line,';');
    //             }
    //             array_shift($all_row);
    //             array_pop($all_row);
    //             foreach ($all_row as $row) {
    //                 if($i == 0 ){
    //                     $header = array_map('trim',$row);
    //                 }
    //                 else if($i != 0 ){
    //                     $all_rows[] = array_combine($header, array_map('trim',$row));
    //                     error_log('error',count($row));
    //                 }
    //                 $i++;
    //             }
    //             array_shift($all_rows); //echo "<pre>"; print_r($all_rows); die();
    //             /*while ($line = stream_get_line($fp, 4096, "\r")) {
    //                 $row = str_getcsv($line);
    //                 if($i != 0 ){
    //                     if ($header === null) {
    //                         $header = array_map('trim',$row);
    //                         continue;
    //                     }
    //                     if( sizeof($header) == sizeof($row)) {
    //                         $all_rows[] = array_combine($header, $row);
    //                     }
    //                 }
    //                 $i++;
    //             } array_shift($all_rows);*/

    //             $save = $this->saveStaffRecord( $all_rows );

    //             fclose($fp);
    //             if($save){
    //                 $msg = "<b>File successfully uploaded</b>";
    //             }else{
    //                 $msg = "<font color='red'><b>File upload failed</b></font>";
    //             }
    //             return Redirect::to('hiptna_showSettings')->with('msg',$msg);
    //         }

    //     }
    // }

    // public function saveStaffRecord( $all_rows )
    // {
    //     $save = '';
    //     foreach ($all_rows as $key => $value) {

    //         $exp_name_ar = explode(" ",$value['Full EE Name']);
    //         $last = end($exp_name_ar);
    //         $first = trim(chop($value['Full EE Name'],$last));
    //         $day_array= array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','Lunch Times');
    //         $split_array = array();
    //         foreach ($day_array as $days) {
    //             $split_data = explode("-", $value[$days]);
    //             $count = count($split_data);

    //             if($count >1 ){
    //                 $split_array[$days."_start"] = $split_data[0];
    //                 $split_array[$days."_end"] = $split_data[1];
    //             }else{
    //                 $split_array[$days."_start"] = $value[$days];
    //                 $split_array[$days."_end"] = $value[$days];
    //             }
    //         }

    //         foreach ($split_array as $key => $val) {

    //             $t = str_replace(" ","", $val);
    //             if (preg_match('/^\d:\d{2}:\d{2}$/', $t)) $t = "0" . $t;
    //             if (preg_match('/^\d:\d{2}$/', $t)) $t = "0" . $t;
    //             $split_array[$key] = $t;
    //         }

    //         //insert csv data into mysql table
    //         $save = \Staff::create(['firstnames'=>$first,
    //                 'surname'=>$last,
    //                 'external_user_id'=>$value['EE Number'],
    //                 'gender'=>$value['Gender'],
    //                 'dob'=>date('Y-m-d', strtotime($value['Birth date'])),
    //                 'storetype'=>$value['Grading'],
    //                 'province'=>$value['Province'],
    //                 'hubname'=>$value['Hub Name'],
    //                 'channel'=>$value['Channel'],
    //                 'smollan_cellphone'=>$value['Smollan Cellphone Number'],
    //                 'other_cellphone'=>$value['Other Phone Number'],
    //                 'email'=>$value['Email'],
    //                 'monday_start'=>$split_array['Monday_start'],
    //                 'monday_end'=>$split_array['Monday_end'],
    //                 'tuesday_start'=>$split_array['Tuesday_start'],
    //                 'tuesday_end'=>$split_array['Tuesday_end'],
    //                 'wednesday_start'=>$split_array['Wednesday_start'],
    //                 'wednesday_end'=>$split_array['Wednesday_end'],
    //                 'thursday_start'=>$split_array['Thursday_start'],
    //                 'thursday_end'=>$split_array['Thursday_end'],
    //                 'friday_start'=>$split_array['Friday_start'],
    //                 'friday_end'=>$split_array['Friday_end'],
    //                 'saturday_start'=>$split_array['Saturday_start'],
    //                 'saturday_end'=>$split_array['Saturday_end'],
    //                 'sunday_start'=>$split_array['Sunday_start'],
    //                 'sunday_end'=>$split_array['Sunday_end'],
    //                 'lunch_start'=>$split_array['Lunch Times_start'],
    //                 'lunch_end'=>$split_array['Lunch Times_end'],
    //                 'lunch_length'=>$this->get_time_difference(trim($split_array['Lunch Times_end']), trim($split_array['Lunch Times_start'])),
    //                 'total_hours'=>$value['Hour']

    //             ]);
    //     }
    //     return $save;
    // }

    // function to find time diffrence
    private function get_time_difference($time2, $time1)
    {
        $time1 = strtotime("1/1/1980 $time1");
        $time2 = strtotime("1/1/1980 $time2");

        if ($time2 < $time1)
        {
            $time2 = $time2 + 86400;
        }

        return date("H:i",($time2 - $time1));
    }

    public function csvdownload(){

        //$file = '/home/anusha/public_html/hiphub/public/docs/data.csv';
        $staff = Input::get();
        $period = Input::get('time');
        $start = date('Y-m-d');
        $end = date('Y-m-d');
        $tab = Input::get('tab');
        if($period == 'rep7day'){
            $start = date('Y-m-d',strtotime('last monday'));
            $end = date('Y-m-d');
        }else if($period == 'repthismonth'){
            $start = date('Y-m-d',strtotime('first day of this month'));
            $end = date('Y-m-d');
        }else if($period == 'replastmonth'){
            $start = date('Y-m-d',strtotime('first day of last month'));
            $end = date('Y-m-d',strtotime('last day of last month'));
        }else if($period == 'daterange'){
            $start = Input::get('start');
            $end = Input::get('end');
        }else if($period == 'today'){
            return $this->csvdownloadToday($tab);
        }

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $measure_label = "";

        $staff_table = $this->getStaffTable();

        //print_r($staff); die();
        if($tab == 'absent'){
            $measure_label = "Days Absent";
            $filename = 'absenteeism_'.$start.'_'.$end;
            $staff_absent = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( $staff_table.'.surname',$staff_table.'.firstnames' , DB::raw('COUNT( CASE WHEN timeandattendance.attendance != "present" THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.attendance', '!=', "present")->where('timeandattendance.instance', '=', $currentInstance )->groupBy('timeandattendance.external_user_id')->get()->toArray();
            //$staff_absent1 = $this->set_graphdata($staff_absent);
            usort($staff_absent, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data = $staff_absent;
        }
        if($tab == 'lateness'){
            $measure_label = "Days Late";
            $filename = 'lateness_'.$start.'_'.$end;
            $staff_lateness = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( $staff_table.'.surname',$staff_table.'.firstnames', DB::raw('COUNT( CASE WHEN CAST(timeandattendance.punctuality AS SIGNED) > '.$lateness_threshold.' THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '!=', "Vacant")->where(DB::raw('(CAST(timeandattendance.punctuality AS SIGNED))'), '>', $lateness_threshold)->where('timeandattendance.instance', '=', $currentInstance )->groupBy('timeandattendance.external_user_id')->get()->toArray();
            //$staff_lateness1 = $this->set_graphdata($staff_lateness);
            usort($staff_lateness, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data = $staff_lateness;
        }
        if( $tab == 'proximity'){
            $measure_label = "Days Not Meeting WS Target";
            $filename = 'proximity_'.$start.'_'.$end;
            $staff_proximity = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')->select( $staff_table.'.surname',$staff_table.'.firstnames' , DB::raw('COUNT( CASE WHEN timeandattendance.proximity <= '.$proximity_target.' THEN 0 END ) AS value'), 'timeandattendance.external_user_id' )->where('timeandattendance.date', ">=", $start)->where('timeandattendance.date', "<=", $end)->where('timeandattendance.external_user_id', '!=', "Vacant")->where('timeandattendance.proximity', '<=', $proximity_target)->where('timeandattendance.instance', '=', $currentInstance )->groupBy('timeandattendance.external_user_id')->get()->toArray();
            //$staff_proximity1 = $this->set_graphdata($staff_proximity);
            usort($staff_proximity, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data = $staff_proximity;
        }


        $file = base_path('public/docs/'.$filename.'.csv');
        $output = fopen($file, 'w');

        fputcsv($output, array('Surname', 'Firstnames', $measure_label));


        foreach ($data as $row) { //print_r($row); die();
            // iterate over each tweet and add it to the csv
            $rowdata =  array($row['surname'], $row['firstnames'], $row['value']); // append each row
            fputcsv($output, $rowdata);
        }



        $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="data.csv"',
        );
        return Response::download($file, $filename.'.csv', $headers);
        //return Response::make(rtrim($output, "\n"), 200, $headers);

        die();
    }

    public function csvdownloadToday($tab)
    {
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $data = array();
        $measure_label = "";

        if( $tab == 'absent'){
            $measure_label = "Days Absent";
            $filename = 'absenteeism_'.date('Y-m-d');
            $staff_absent = $this->getStaffAbsentByDay(date('Y-m-d',strtotime('today midnight')));
            usort($staff_absent, function($a, $b) {
                return $b['value'] - $a['value'];
            }); //print_r($staff_absent); die();
            $data = $staff_absent;
        } else if( $tab == 'lateness'){
            $measure_label = "Days Late";
            $filename = 'lateness_'.date('Y-m-d');
            $staff_lateness = $this->getStaffLateByDay(date('Y-m-d',strtotime('today midnight')));
            usort($staff_lateness, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            $data = $staff_lateness;
        } else if( $tab == 'proximity'){
            $measure_label = "Days Not Meeting WS Target";
            $filename = 'proximity_'.date('Y-m-d');

            $currentInstance = Session::get('currentInstance');
            $staff_table = $this->getStaffTable();

            $staff_proximity = \Timeandattendance::join($staff_table , $staff_table.'.external_user_id','=', 'timeandattendance.external_user_id')
            ->select( DB::raw('CONCAT('.$staff_table.'.surname," ",'.$staff_table.'.firstnames) As label') , DB::raw('timeandattendance.proximity AS value'), $staff_table.'.surname', $staff_table.'.firstnames', 'timeandattendance.external_user_id', DB::raw('COUNT( CASE WHEN timeandattendance.proximity <= '.$proximity_target.' THEN 0 END ) AS csvcount') )
            ->where('timeandattendance.date', ">=", date('Y-m-d',strtotime('yesterday midnight')))
            ->where('timeandattendance.external_user_id', '!=', "Vacant")
            ->where('timeandattendance.proximity', '>', 0)
            ->where('timeandattendance.proximity', '<=', $proximity_target)
            ->where('timeandattendance.instance', '=', $currentInstance )
            ->groupBy('timeandattendance.external_user_id')->get()->toArray();

            usort($staff_proximity, function($a, $b) {
                return $a['value'] - $b['value'];
            });
            $data = $staff_proximity;
        }

        $file = base_path('public/docs/'.$filename.'.csv');
        $output = fopen($file, 'w');

        fputcsv($output, array('Surname', 'Firstnames', $measure_label));


        foreach ($data as $row) { //print_r($row); die();
            // iterate over each tweet and add it to the csv
            $rowdata =  array($row['surname'], $row['firstnames'], $row['csvcount']); // append each row
            fputcsv($output, $rowdata);
        }



        $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="data.csv"',
        );
        return Response::download($file, $filename.'.csv', $headers);
        //return Response::make(rtrim($output, "\n"), 200, $headers);

        die();

    }


    public function memberGraphToday()
    {
        $data = array() ;
        $period = Input::get('period');
        $staff_id = Input::get('staff_id');
        $date = date('Y-m-d',strtotime('today midnight'));
        $dt_date = new \DateTime($date);

        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;

        $start = '';
        $end = '';

        $data = array();
        $data['staff_absent'] = array();
        $data['staff_lateness'] = array();
        $data['staff_proximity'] = array();

        if($currentInstance == 'IM'){
            $staffmember = \Imstaff::where('external_user_id', '=', $staff_id)->first();
        }else if($currentInstance == 'CE'){
            $staffmember = \Cestaff::where('external_user_id', '=', $staff_id)->first();
        }else{
            $staffmember = \Staff::where('external_user_id', '=', $staff_id)->first();
        }
        /*$staffmember = \Staff::where('external_user_id', '=', $staff_id)->first();*/
        $tna = new \Tna();
        $lateness = $tna->getPunctuality($staffmember, $dt_date);

        $data['staff_absent'][0]["name"] = $staffmember->surname . ', ' . $staffmember->firstnames;
        if($lateness) {
            $data['staff_absent'][0]["value"] = "<span style='color:green'>Present</span>";
            if($lateness > $lateness_threshold) {

                $data['staff_lateness'][0]["value"] = "<span style='color:red'>" . $lateness . "</span>";
            } else {
                $data['staff_lateness'][0]["value"] = "<span style='color:green'>" . $lateness . "</span>";
            }
        } else {
            $data['staff_absent'][0]["value"] = "<span style='color:red'>Absent</span>";
            $data['staff_lateness'][0]["value"] = "---";
        }

        // $data['staff_week'] = \Timeandattendance::where('date', ">=", date('Y-m-d',strtotime('last monday')))->where('attendance', '=', "present")->get()->count();
        $tnarecord = \Timeandattendance::where('external_user_id', '=', $staff_id)->where('date', "=", date('Y-m-d',strtotime('yesterday')))->where('instance', '=', $currentInstance )->first();
        if($tnarecord) {
            if($tnarecord->proximity > $proximity_target) {
                $data['staff_proximity'][0]["value"] = "<span style='color:green'>" . $tnarecord->proximity . "</span>";
            } else {
                $data['staff_proximity'][0]["value"] = "<span style='color:red'>" . $tnarecord->proximity . "</span>";
            }
        } else {
            $data['staff_proximity'][0]["value"] = "---";
        }
        $data['report_date'] = "Today ".date("Y-m-d");

        $json = json_encode($data);

        print_r($json);
    }

    public function cleanupStaff(){

        $days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday','lunch');

        $currentInstance = Session::get('currentInstance');
        if($currentInstance == 'IM'){
            $data = \Imstaff::get();
        }else if($currentInstance == 'CE'){
            $data = \Cestaff::get();
        }else{
            $data = \Staff::get();
        }
        /*$data = \Staff::get();*/
        foreach ($data as $value) {

            foreach ($days as $day ) {
                $daystart = $day.'_start';
                $t = str_replace(" ","", $value->$daystart);
                if (preg_match('/^\d:\d{2}:\d{2}$/', $t)) $t = "0" . $t;
                if (preg_match('/^\d:\d{2}$/', $t)) $t = "0" . $t;
                $start[$daystart] = $t;

                $dayend = $day.'_end';
                $t1 = str_replace(" ","", $value->$dayend);
                if (preg_match('/^\d:\d{2}:\d{2}$/', $t1)) $t1 = "0" . $t1;
                if (preg_match('/^\d:\d{2}$/', $t1)) $t1 = "0" . $t1;
                $start[$dayend] = $t1;
            }
            //print_r($start);

            if($currentInstance == 'IM'){
                \Imstaff::where('id', '=', $value->id )->update($start);
            }else if($currentInstance == 'CE'){
                \Cestaff::where('id', '=', $value->id )->update($start);
            }else{
                \Staff::where('id', '=', $value->id )->update($start);
            }
            /*\Staff::where('id', '=', $value->id )->update($start);*/

        }
        echo "sucess";

    }

    public function latenessthreshold(){
        $currentInstance            =   Session::get('currentInstance');
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        echo $lateness_threshold;
    }

    public function proximitythreshold(){
        $settings = $this->getTnaInstanceSettings();
        $lateness_threshold = \Tnasetting::where('name', 'like', $settings["lateness_threshold"])->first()->value;
        $proximity_target = \Tnasetting::where('name', 'like', $settings["proximity_target"])->first()->value;
        echo $proximity_target;
    }

}