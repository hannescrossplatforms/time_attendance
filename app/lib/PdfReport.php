<?php

/*
 * @ createdBy   : Shafeeque
 * @ Description : Auto create PdfReport  
 */
use hiptna\HiptnaController;

class PdfReport extends Eloquent {
    
    public function __construct()
    {

    }
    
    /*
     * 
     */
    public function generateReport( $period = "" , $currentInstance = "" ) {
        
            $outputfile               =   '';
            if( $period == 'lastday' ) {
                $venuefrom  =   date( 'Y-m-d' , strtotime( 'yesterday' ) );
                $venueto    =   date( 'Y-m-d' , strtotime( 'yesterday' ) );
                $outputfile =   'day_'.$venuefrom.'_'.$venueto;
            } else if( $period == 'lastweek' ) {
                $venuefrom  =   date("Y-m-d", strtotime("last week monday"));
                $venueto    =   date("Y-m-d", strtotime("last sunday"));
                $outputfile =   'week_'.$venuefrom.'_'.$venueto;
            } else if( $period == 'lastmonth' ) {
                $venuefrom      =   date('Y-m-d',strtotime('first day of last month'));
                $venueto        =   date('Y-m-d',strtotime('last day of last month'));
                $outputfile     =   'month_'.$venuefrom.'_'.$venueto;
            } else {
                return "Invalid Report period";
            }
            
            $absenceGraph           =   $this->generateAbsence(  $outputfile , $currentInstance );
            $latenessGraph          =   $this->generateLateness(  $outputfile , $currentInstance );
            $wsproximityGraph       =   $this->generateWSProximity(  $outputfile , $currentInstance );
            return $period;
        
    }
        
    /*
     * autogenerate AbsenceReport
     * @param $period varchar report period
     */

    public function generateAbsence( $file_name , $currentInstance ) {
        $data                   =   array();
        $outputPdfName          =   $currentInstance.'_absence_'.$file_name.".pdf";
        $templatepath           =   base_path()."/public/report/html_templates/";
        $outputpath             =   base_path()."/public/report/graph_images/";
        $pdfPath                =   base_path()."/public/report/pdf/";
        
        
        $webpageURLWithChart    =   array( $templatepath.'absence_graph01.html' , $templatepath.'absence_graph02.html' , $templatepath.'absence_graph03.html' );
        $outputImageFileName    =   array( $outputpath.'absence_graph01.png' , $outputpath.'absence_graph02.png' , $outputpath.'absence_graph03.png');
        $image_width            =   array( 800 , 300 , 300 ) ;
        $image_height           =   array( 800 , 220 , 220 ) ;
        $delay                  =   5000 ;
        $page_head              =   'Absence';
        $report_title           =   $this->getReportTitle( $file_name );
        
        if($this->generateGraphImage( $webpageURLWithChart , $outputImageFileName , $image_width , $image_height , $delay )) {
            $templatepath           =   base_path()."/public/report/html_templates/";
            $webpageURL             =   $templatepath.'absence_chart.html';
            $patterns               =   array();
            $patterns['page_head']  =   '%%PAGE_HEADER%%';
            $patterns['report_title']   = '%%REPORT_TITLE%%';
            $patterns[0]            =   '%%STAFF_ABSENCE_LIST%%';
            $patterns[1]            =   '%%ABSENCE_TODAY%%';
            $patterns[2]            =   '%%ABSENCE_YESTERDAY%%';
            $patterns[3]            =   '%%MOST_ABSENCE_LIST%%';
            $patterns[4]            =   '%%LEAST_ABSENCE_LIST%%';

            $replacements           =   array();
            $replacements['page_head']  =   $page_head;
            $replacements['report_title']   =   $report_title;
            $replacements[0]        =   $outputImageFileName[0];
            $replacements[1]        =   '23';
            $replacements[2]        =   '34';
            $replacements[3]        =   $outputImageFileName[1];
            $replacements[4]        =   $outputImageFileName[2];
            
            $outputFile             =       $templatepath.'absence_chart_'.  strtotime( date( 'h:m:s' ) ).'.html';
            
            $replaceTokens      =       $this->replaceTokenHtml( $webpageURL , $patterns , $replacements , $outputFile );
            if($replaceTokens) {
                $this->convertHtmlToPdf( $outputFile ,  $pdfPath.$outputPdfName , $delay ) ;
            }
        }
        return $outputPdfName;
    }

    /*
     * auto generate Lateness report 
     * @param $period varchar report period
     */
    public function generateLateness( $file_name , $currentInstance ) {
        $data                   =   array();
        $outputPdfName          =   $currentInstance.'_lateness_'.$file_name.".pdf";        
        $templatepath           =   base_path()."/public/report/html_templates/";
        $outputpath             =   base_path()."/public/report/graph_images/";
        $pdfPath                =   base_path()."/public/report/pdf/";
        
        $webpageURLWithChart    =   array( $templatepath.'lateness_graph01.html' , $templatepath.'lateness_graph02.html' , $templatepath.'lateness_graph03.html' , $templatepath.'lateness_graph04.html' , $templatepath.'lateness_graph05.html' );
        $outputImageFileName    =   array( $outputpath.'lateness_graph01.png' , $outputpath.'lateness_graph02.png' , $outputpath.'lateness_graph03.png' , $outputpath.'lateness_graph04.png' , $outputpath.'lateness_graph05.png');
        $image_width            =   array( 800 , 300 , 300 , 300 , 300 ) ;
        $image_height           =   array( 800 , 220 , 220 , 220 , 220 ) ;
        $delay                  =   5000 ;
        $page_head              =   'Lateness';
        $report_title           =   $this->getReportTitle( $file_name );
        
        if($this->generateGraphImage( $webpageURLWithChart , $outputImageFileName , $image_width , $image_height , $delay )) {
            $templatepath           =   base_path()."/public/report/html_templates/";
            $webpageURL             =   $templatepath.'lateness_chart.html';
            
            $patterns               =   array();
            $patterns['page_head']  =   '%%PAGE_HEADER%%';
            $patterns['report_title']   = '%%REPORT_TITLE%%';
            $patterns[0]            =   '%%STAFF_ABSENT_LIST%%';
            $patterns[1]            =   '%%ARRIVED_LATE_TODAY%%';
            $patterns[2]            =   '%%ARRIVED_LATE_YESTERDAY%%';
            $patterns[3]            =   '%%LEFT_EARLY_TODAY%%';
            $patterns[5]            =   '%%LEFT_EARLY_YESTERDAY%%';
            $patterns[6]            =   '%%MOST_LATENESS_LIST%%';
            $patterns[7]            =   '%%LEAST_LATENESS_LIST%%';
            $patterns[8]            =   '%%MOST_EARLY_LEAVING%%';
            $patterns[9]            =   '%%LEAST_EARLY_LEAVING%%';

            $replacements           =   array();
            $replacements['page_head']  =   $page_head;
            $replacements['report_title']   =   $report_title;
            $replacements[0]        =   $outputImageFileName[0];
            $replacements[1]        =   '23';
            $replacements[2]        =   '34';
            $replacements[3]        =   '23';
            $replacements[4]        =   '34';
            $replacements[5]        =   $outputImageFileName[1];
            $replacements[6]        =   $outputImageFileName[2];
            $replacements[7]        =   $outputImageFileName[3];
            $replacements[8]        =   $outputImageFileName[4];
            
            $outputFile         =       $templatepath.'lateness_chart_'.  strtotime( date( 'h:m:s' ) ).'.html';
            
            $replaceTokens      =       $this->replaceTokenHtml( $webpageURL , $patterns , $replacements , $outputFile );
            if($replaceTokens) {
                $this->convertHtmlToPdf( $outputFile ,  $pdfPath.$outputPdfName , $delay = 2000 ) ;
            }
        }
        return $outputPdfName;
    }

    /*
     * auto generate Wsproximity report 
     * @param $period varchar report period
     */
    public function generateWSProximity( $file_name , $currentInstance ) {
        $data                   =   array();
        $outputPdfName          =   $currentInstance.'_wsproximity_'.$file_name.".pdf";        
        $templatepath           =   base_path()."/public/report/html_templates/";
        $outputpath             =   base_path()."/public/report/graph_images/";
        $pdfPath                =   base_path()."/public/report/pdf/";
        
        $webpageURLWithChart    =   array( $templatepath.'wsproximity_graph01.html' , $templatepath.'wsproximity_graph02.html' , $templatepath.'wsproximity_graph03.html' );
        $outputImageFileName    =   array( $outputpath.'wsproximity_graph01.png' , $outputpath.'wsproximity_graph02.png' , $outputpath.'wsproximity_graph03.png');
        $image_width            =   array( 800 , 300 , 300 ) ;
        $image_height           =   array( 1200 , 220 , 220 ) ;
        $delay                  =   5000 ;
        $page_head              =   'WS Proximity';
        $report_title           =   $this->getReportTitle( $file_name );
        
        if($this->generateGraphImage( $webpageURLWithChart , $outputImageFileName , $image_width , $image_height , $delay )) {
            $templatepath           =   base_path()."/public/report/html_templates/";
            $webpageURL             =   $templatepath.'wsproximity_chart.html';
            $patterns               =   array();
            $patterns['page_head']      =   '%%PAGE_HEADER%%';
            $patterns['report_title']   =   '%%REPORT_TITLE%%';
            $patterns[0]            =   '%%STAFF_PROXIMITY_LIST%%';
            $patterns[1]            =   '%%WSTARGET_MET_YESTERDAY%%';
            $patterns[2]            =   '%%MOST_PROXIMITY_LIST%%';
            $patterns[3]            =   '%%LEAST_PROXIMITY_LIST%%';

            $replacements           =   array();
            $replacements['page_head']      =   $page_head;
            $replacements['report_title']   =   $report_title;
            $replacements[0]        =   $outputImageFileName[0];
            $replacements[1]        =   '23';
            $replacements[2]        =   $outputImageFileName[1];
            $replacements[3]        =   $outputImageFileName[2];
            
            $outputFile             =       $templatepath.'wsproximity_chart_'.  strtotime( date( 'h:m:s' ) ).'.html';
            
            $replaceTokens          =       $this->replaceTokenHtml( $webpageURL , $patterns , $replacements , $outputFile );
            if($replaceTokens) {
                $this->convertHtmlToPdf( $outputFile ,  $pdfPath.$outputPdfName , $delay = 5000 ) ;
            }
        }
        return $outputPdfName;
    }
    
    public function generateGraphImage( $webpageURLWithChart = '' , $outputImageFileName = '' , $image_width = '' , $image_height = '' , $delay = '1000') {
        if(is_array($webpageURLWithChart)) {
            foreach($webpageURLWithChart as $key => $webpage) {
                $command                =  'wkhtmltoimage --javascript-delay ' . $delay . ' --width ' . $image_width[$key]. ' --height '. $image_height[$key].' '.$webpage.' '.$outputImageFileName[$key];
                $shellout               =   shell_exec( $command );
            }
        } else {
            $command                =  'wkhtmltoimage --javascript-delay '.$delay.' '.$webpageURLWithChart.' '.$outputImageFileName;
            $shellout               =   shell_exec( $command );
        }
        return $outputImageFileName;
    }
    
    public function replaceTokenHtml( $webpageURL , $patterns , $replacements , $outputFile) {
        $webpage            =   file_get_contents($webpageURL);
        $output             =   str_replace($patterns, $replacements, $webpage);
        $html_file          =   fopen( $outputFile , "w" );
        fwrite( $html_file , $output );
        fclose( $html_file );
        return $outputFile;
    }

    public function convertHtmlToPdf( $webpageURL , $outPutFileName , $delay ) {
        if( is_array($webpageURL) ) {
            foreach( $webpageURL as $key => $webpage ) {
                $command                =  'wkhtmltopdf --javascript-delay ' . $delay. ' ' .$webpage.' '.$outPutFileName[$key];
                $shellout               =   shell_exec( $command );
            }
        } else {
                $command                =  'wkhtmltopdf --javascript-delay ' . $delay. ' ' .$webpageURL.' '.$outPutFileName;
                $shellout               =   shell_exec( $command );
        }
        return $outPutFileName;
    }
    
    public function getReportTitle( $title = '' ) {
        $report_title                   =   explode("_", $title );
        return 'Report for '.$report_title[1]." to ".$report_title[2];
    }
    

    
}

