<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon/favicon.ico">

    <title>User Admin | HipHUB</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.css') }}" rel="stylesheet" media="screen" />
    <link href="{{asset('css/bootstrap-social.css')}}" rel="stylesheet" media="screen" />

    <link href="{{ asset('css/datepicker.css')}}" rel="stylesheet" media="screen" />
    
    <!-- Fontawesome CSS -->
    <!-- <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
     -->
    <!-- Sweet Alert CSS --> 
    <script type="text/javascript" src="{{ asset('js/sweet-alert.min.js') }}"></script>
    
    <link href="{{ asset('css/sweet-alert.css')}}" rel="stylesheet" media="screen" />
    
    <!-- Google Webfont CSS -->
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template - These are from MADE -->
    <link href="{{ asset('css/style.css')}}" rel="stylesheet" media="screen" />

    <!-- Additional ustom styles for this template - These kept separate from MADE in case MADE updates style.css-->
    <link href="{{ asset('/css/styleadditional.css')}}" rel="stylesheet" media="screen" />

    <link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css?v=2.1.5')}}" type="text/css" media="screen" />

    <link href="{{ asset('/css/jquery.timepicker.css')}}" type="text/css" rel="stylesheet">






    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->   

    <style type="text/css">
      .svgimg img { width: 200px; height: 300px; }
      .hipReports {
        color: #555555; font-family: 'Ubuntu',sans-serif;
      }
      
      .pdf_doc_logo { 
          position: absolute;
          min-height: 107px;          
          right: 5%;
          bottom: 93.4%;
      }
      .pdf_doc_logo img {
          height: 102px;
          width: 125px;
      }
      .venuecolheading{
        color: #555555; font-family: 'Ubuntu',sans-serif;
        font-size: 16px;
        font-weight: 900;
      }
      .modstattitle h3{
        color: #555555; font-family: 'Ubuntu',sans-serif;
        font-size: 16px;
        font-weight: 900;
        
      }  

      .modstattitle {
        background-color: #FFCC29; 
        height: 45px; 
        padding: 10px;
      }    
      .page-header{
        color: #555555; font-family: 'Ubuntu',sans-serif;
        font-weight: 600;
      }

   
    </style>

    <script type="text/javascript">       

         $(function(){
            $("img").attr(style:"width:100px !important");

         });
        
        
    </script>


  </head>
  <body class="hipReports"> 

   <div class="container-fluid">
      <div class="row">     

        <div class="col-sm-9 main" style="width: 80%; margin: 10px 10% 0 12%;">

            <h1 class="page-header">HipWIFI Reports</h1>
            <div class="pdf_doc_logo" >
                <img src="{{asset('img/logo_hiphub_logo.jpg')}}">
            </div>
          <div class="reports-subheader">
          </div>

          <div role="tabpanel"> 
            
            <!-- Tab panes -->
            <div class="tab-content">

                <!-- Venue Level - Shoe the list of Venues -->
                <div role="tabpanel" class="tab-pane" id="showvenues" style="display:block">                     
                    <div id="download-preview">
                        <!-- BEGIN DEMOGRAPHICS AND USAGE -->
                        <div class="venuereports">
                            <!-- section preview one start -->
                            <div id="venue_section_one">
                                {{ $fusionchartElementOne }}
                            </div>
                            <!-- section preview one end -->
                            <!-- section preview two start -->
                            <div id="venue_section_two">
                                <div class="venuecol3" style=" float:left; width: 42%;margin-right: 20px;">
                                    <div class="venuecolheading">Hipzone Wifi & Data Admin Usage Statistics</div>
                                    <div class="venuerow" style=" float: inherit; margin: 10px 0;  background-color: #ffffff;">
                                      <div class="venuerowleft" style=" float: left; width: 50%; height: 100%;  display: inline-block; height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" style="background-color: #FFCC29; height: 45px; padding: 10px;"><h3>Total Wifi Sessions1</h3> </div>
                                          <div id="totalwifisessions" class="modStatspan">{{$totalWifiSessions}}</div>
                                          <div id="avgwifisessions">{{$avgWifiSessions}}</div>
                                        </div>
                                      </div>
                                      <div class="venuerowright" style=" float: right;  width: 50%; height: 100%; display: inline-block;  height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle"><h3>Wifi Data (Total Consumption Gb)</h3> </div>
                                          <div id="totalwifidatatotal" class="modStatspan">{{$wifiDataTotal}}</div>
                                          <div id="avgwifidatatotal">{{$avgWifiDataTotal}}</div>
                                        </div>                      
                                      </div>
                                    </div>
                                    <div class="venuerow" style=" float: inherit;  margin: 190px 0;  background-color: #ffffff;">
                                      <div class="venuerowleft" style="float: left; width: 50%; height: 100%;  display: inline-block; height: auto; border: 1px solid #999999;">                        
                                        <div class="modStat">
                                          <div class="modstattitle" ><h3>Number of Unique Customers</h3> </div>
                                          <div id="totalnumberofpeople" class="modStatspan">{{$totalNumberofPeople}}</div>
                                          <div id="avgnumberofpeople">{{$avgNumberofPeople}}</div>
                                        </div>  
                                      </div>
                                      <div class="venuerowright" style="  float: right; width: 50%; height: 100%; display: inline-block; height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" ><h3>First Time Wifi Users</h3></div>
                                          <div id="totalfirsttimeusers" class="modStatspan">{{$totalTirstTimeUsers}}</div>
                                          <div id="avgfirsttimeusers">{{$avgFirstTimeUsers}}</div>
                                        </div>                      
                                      </div>
                                    </div>
                                    <div class="venuerow" style=" float: inherit; margin: 380px 0;  background-color: #ffffff;">
                                      <div class="venuerowleft" style="float: left; width: 50%; height: 100%; display: inline-block; height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" > <h3>Wifi Data (Avg Per Session Mb)</h3> </div>
                                          <div id="venueavgdatapersession" class="modStatspan">{{$venueAvgDataPerSession}}</div>
                                          <div id="brandavgdatapersession">{{$brandAvgDataPerSession}}</div>
                                        </div>
                                      </div>
                                      <div class="venuerowright" style=" float: right; width: 50%; height: 100%; display: inline-block; height: auto; border: 1px solid #999999;">
                                        <a class="modal-link" href="#" id="customerDwellTimeLink" data-toggle="modal" data-target="#customerDwellTimeModal">
                                          <div class="modStat">
                                            <div class="modstattitle" > <h3>Wifi Dwell Time</h3> </div>
                                            <div id="venueavgtimepersession" class="modStatspan">{{$venueAvgTimePerSession}}</div>
                                            <div id="brandavgtimepersession">{{$brandAvgTimePerSession}}</div>                    
                                          </div>
                                        </a>
                                      </div>
                                    </div>
                                    <div class="venuerow" style="  margin: 570px 0; background-color: #ffffff;">
                                      <div class="modStat">
                                          <div class="modstattitle" ><h3>Data Admin Usage</h3></div>
                                          <div>No Data Available. <br> You are not using HipZone as your ISP.</div>
                                          <!-- <div class="venuerow"><img src="/img/tmpcharts/3-4.PNG" width="100%"></div> -->
                                      </div>
                                    </div>                        
                                </div>  
                            </div>
                            <!-- section preview two start -->
                        </div>
                        <!-- section preview three start -->
                        <div id="venue_setcion_three">
                            {{ $fusionchartElementThree }}
                        </div>
                        <!-- section preview three start -->
                    </div> 
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/js.cookie.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
    <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.zune.js"></script>
    <script src="js/bootstrap-datepicker.js"></script> 
    <script src="js/hipreports/hipwifi/hipwifi_venue.js"></script>
    

</body>

</html>