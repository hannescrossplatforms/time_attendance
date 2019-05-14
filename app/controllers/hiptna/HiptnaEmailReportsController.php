<?php

namespace hiptna;
use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
// use App\models\Reportrecipients
// use BaseController;

class HiptnaEmailReportsController extends \BaseController {



    public function sendemails() {

        error_log("sdfjsdflksjdflksjfdslkdfj");

        $hiptna = new HiptnaController();
        $hiptna->generatePdf();

        $today = date("Y-m-d");
        $yesterday = date('Y-m-d',strtotime('yesterday'));
        $dayofweek = date('l');
        $month_day = date('Y-m-d',strtotime('first day of this month'));
        $instances = array("IM", "CE");
        $previous_week = date('Y-m-d',strtotime('Monday last week'));
        
        $headers = "From: noreply@hipzone.com" ;
        if( $dayofweek == "Monday" ){
            echo "Processing week";
            foreach($instances as $instance) {

                $weekly_recipients = \Reportrecipients::where('weekly','=',1)->where($instance, '=', 1)->get();
                if( count($weekly_recipients) > 0 ){
                    $absence_file = $instance . '_absence_week_'.$previous_week.'_'. $yesterday .'.pdf';
                    $lateness_file = $instance . '_lateness_week_'.$previous_week.'_'. $yesterday .'.pdf';
                    $wsproximity_file = $instance . '_wsproximity_week_'.$previous_week.'_'. $yesterday .'.pdf';
                    foreach ($weekly_recipients as $recipient) {
                        $this->setmail($instance, $recipient,$previous_week,$absence_file,$lateness_file,$wsproximity_file,$headers,'Weekly' );
                    }
                }
            }
        }

        if( $today == $month_day ){
            echo "Processing month";
            foreach($instances as $instance) {

                $monthly_recipients = \Reportrecipients::where('monthly','=',1)->where($instance, '=', 1)->get();
                if( count($monthly_recipients) > 0 ){
                    $previous_month = date('Y-m-d',strtotime('first day of last month'));
                    $previous_month_last = date('Y-m-d',strtotime('last day of last month'));
                    $absence_file = $instance . '_absence_month_'.$previous_month.'_'.$previous_month_last.'.pdf';
                    $lateness_file = $instance . '_lateness_month_'.$previous_month.'_'.$previous_month_last.'.pdf';
                    $wsproximity_file = $instance . '_wsproximity_month_'.$previous_month.'_'.$previous_month_last.'.pdf';
                    foreach ($monthly_recipients as $recipient) {
                       $this->setmail($instance, $recipient,$previous_month,$absence_file,$lateness_file,$wsproximity_file,$headers,'Monthly' ); 
                    }
                }
            }
        }

        //for daily update
        foreach($instances as $instance) {
            $daily_recipients = \Reportrecipients::where('daily','=',1)->get(); //print_r($day_list); die();
            if( count($daily_recipients) > 0 ){
                $absence_file = $instance . '_absence_day_'.$yesterday.'_'.$yesterday.'.pdf';
                $lateness_file = $instance . '_lateness_day_'.$yesterday.'_'.$yesterday.'.pdf';
                $wsproximity_file = $instance . '_wsproximity_day_'.$yesterday.'_'.$yesterday.'.pdf';
                foreach ($daily_recipients as $recipient) { //echo "kkk"; die();
                    $this->setmail($instance, $recipient,$yesterday,$absence_file,$lateness_file,$wsproximity_file,$headers,'Daily' );
                }  
            }  
        }
    }

    //setting contents for send mail to all ,who selects each of absence,lateness,ws_proximity
    public function setmail($instance, $recipient,$previous,$absence_file,$lateness_file,$wsproximity_file,$headers,$period ){

        if($period == "Monthly") $txtperiod = "month";
        if($period == "Weekly") $txtperiod = "week";
        if($period == "Daily") $txtperiod = "day";

        if( $recipient['absence'] = 1 ){

            $subject = "Absence $period $instance Report - $previous";
            $file = $absence_file;
            echo "sending " . $file . "<br>";
            $this->mailsend($recipient,$subject,$headers,$file,'Absence',$txtperiod, $previous);

        }
        if( $recipient['lateness'] = 1 ){

            $subject = "Lateness $period $instance Report - $previous";
            $file = $lateness_file;
            echo "sending " . $file . "<br>";
            $this->mailsend($recipient,$subject,$headers,$file,'Lateness',$txtperiod, $previous);
        }
        if( $recipient['ws_proximity'] = 1 ){

            $subject = "Workstation Proximity $period $instance Report - $previous";
            $file = $wsproximity_file;
            echo "sending " . $file . "<br>";
            $this->mailsend($recipient,$subject,$headers,$file,'Worksation Proximity',$txtperiod, $previous);
        }
    }

    //send mail with pdf
    public function mailsend($recipient,$subject,$headers,$filename,$report_type,$period, $previous){

        $file = '/var/www/hiphub/public/report/pdf/'.$filename;
        $to = $recipient["email"];
        $name = $recipient["name"];

        error_log("mailsend : sending file : $file");
        error_log("mailsend : sending to : $to");
        $message = "";
        
        if( file_exists($file)){

            $send = \Mail::send('hiptna.reportemail', array('name' => $name, 'report_type' => $report_type, 'period' => $period, 'date' => $previous), function($message) use($to, $subject, $file)
            {
                $message->to($to)->subject($subject);
                $message->attach($file); 
            });

            if($send) echo $file . " done";
            else echo $file . ' not sent <BR>';
        }else{
            echo "file: $filename not exist <br>";
        }
    }

}