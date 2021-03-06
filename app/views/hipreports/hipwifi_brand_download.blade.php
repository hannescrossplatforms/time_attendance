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
    <!-- <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"> -->
    
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
        height: 50px; 
        padding: 10px;
      }        
      .graph-container h1{
        color: #555555; font-family: 'Ubuntu',sans-serif;
        font-size: 16px;
        font-weight: 900;
      }
      .page-header{
        color: #555555; font-family: 'Ubuntu',sans-serif;
        font-weight: 600;
      }

      .graphcol{
        width: 20%; 
        margin: 20px; 
        float: left; 
        border: 2px 
        solid !important;
      }

   
    </style>

    <script type="text/javascript">       

         $(function(){
            $("img").attr(style:"width:200px");

         });
        
        
    </script>

  </head> 

<body class="hipReports"> 

   <div class="container-fluid">
      <div class="row">     

        <div class="col-sm-9 main" style="width: 80%; margin: 0px 10% 0 12%;">

          <h1 class="page-header">HipWIFI Reports</h1>
            <div class="pdf_doc_logo" >
                <img src="{{asset('img/logo_hiphub_logo.jpg')}}">
            </div>
          

          <div role="tabpanel">        
          
            
            
            <!-- Tab panes -->
            <div class="tab-content">

                <!-- Dashboard -->
                <div role="tabpanel" class="tab-pane active" id="dashboard"> 
                    <div>      
                        <?php if(isset($printButtonToken)) { ?>
                        <div id="download-button" class="col-md-4" style="width:20%; float: right;">
                            <button type="button" class="btn btn-primary" onclick="convertPDF()">Print to Pdf</button>
                        </div>
                          <?php } ?>
                    </div>

                    <div id="download-preview">
                        <!-- BEGIN DEMOGRAPHICS AND USAGE -->
                        <div class="venuereports">
                            <!-- section  one start -->
                            <div id="section_one">
                                {{ $fusionchartElementOne }}
                            </div>
                            <!-- section  one end -->
                            <!-- section  two start -->
                            <div id="section_two">
                                <div class="venuecol3" style=" float:left; width: 42%;margin-right: 20px;">
                                    <div class="venuecolheading">Hipzone Wifi &amp; Data Admin Usage Statistics</div>
                                    <div class="venuerow" style=" float: inherit; margin: 10px 0;  background-color: #ffffff;">
                                      <div class="venuerowleft" style=" float: left; width: 50%; height: 100%;  display: inline-block; height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" ><h3>Total Wifi Sessions</h3> </div>
                                          <div id="brandavgwifisessions" class="modStatspan">{{ $totalWifiSessions }}</div>
                                        </div>
                                      </div>
                                      <div class="venuerowright" style=" float: right;  width: 50%; height: 100%; display: inline-block;  height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" ><h3>Wifi Data (Total Consumption Gb)</h3> </div>
                                          <div id="brandavgwifidatatotal" class="modStatspan">{{ $wifiDataTotal }}</div>
                                        </div>                      
                                      </div>
                                    </div>
                                    <div class="venuerow" style=" float: inherit;  margin: 180px 0;  background-color: #ffffff;">
                                      <div class="venuerowleft" style="float: left; width: 50%; height: 100%;  display: inline-block; height: auto; border: 1px solid #999999;">                        
                                        <div class="modStat">
                                          <div class="modstattitle" ><h3>Number of Unique Customers</h3> </div>
                                          <div id="brandavgnumberofpeople" class="modStatspan">{{ $avgNumberofPeople }}</div>
                                        </div>  
                                      </div>
                                      <div class="venuerowright" style="  float: right; width: 50%; height: 100%; display: inline-block; height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" ><h3>First Time Wifi Users</h3></div>
                                          <div id="brandavgfirsttimeusers" class="modStatspan">{{ $avgFirstTimeUsers }}</div>
                                        </div>                      
                                      </div>
                                    </div>
                                    <div class="venuerow" style=" float: inherit; margin: 360px 0;  background-color: #ffffff;">
                                      <div class="venuerowleft" style="float: left; width: 50%; height: 100%; display: inline-block; height: auto; border: 1px solid #999999;">
                                        <div class="modStat">
                                          <div class="modstattitle" > <h3>Wifi Data (Avg Per Session Mb)</h3> </div>
                                          <div id="brandbrandavgdatapersession" class="modStatspan">{{ $avgDataPerSession }}</div>
                                        </div>
                                      </div>
                                      <div class="venuerowright" style=" float: right; width: 50%; height: 100%; display: inline-block; height: auto; border: 1px solid #999999;">
                                        <a class="modal-link" href="#" id="brandCustomerDwellTimeLink" data-toggle="modal" data-target="#brandCustomerDwellTimeModal">
                                          <div class="modStat">
                                            <div class="modstattitle" > <h3>Wifi Dwell Time</h3> </div>
                                            <div id="brandbrandavgtimepersession" class="modStatspan">{{ $avgTimePerSession }}</div>
                                          </div>
                                        </a>
                                      </div>
                                    </div><br><br>
                                    <div class="venuerow" style="  margin: 500px 0; background-color: #ffffff;">
                                      <div class="modStat" style="">
                                          <div class="modstattitle" ><h3>Data Admin Usage</h3></div>
                                          <div>No Data Available. <br> You are not using HipZone as your ISP.</div>
                                          <!-- <div class="venuerow"><img src="/img/tmpcharts/3-4.PNG" width=""></div> -->
                                      </div>
                                    </div>
                                    
                                  </div>
                            </div>
                            <!-- section  two start -->
                        </div>
                        <!-- section  three start -->
                        <div id="section_three">
                            {{ $fusionchartElementThree }}
                        </div>
                        <!-- section  three start -->
                    </div> 
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>


    <script type="text/javascript">
        function convertPDF() {
            var myPageone    =   $("#section_one").html();
            var myPagetwo    =   $("#section_two").html();
            var myPagethree  =   $("#section_three").html();
            //console.log(myPage); alert(myPage);
            $("#myPageone").val(myPageone);
            $("#myPagetwo").val(myPagetwo);
            $("#myPagethree").val(myPagethree);
            $("#printMyPage").submit();
        }
    </script>
    
            

    <form name="printMyPage" id="printMyPage" action="{{ url('hipwifiBrandPdfDownload') }}" method="post">
        <input type="hidden" name="myPageone" id="myPageone">
        <input type="hidden" name="myPagetwo" id="myPagetwo">
        <input type="hidden" name="myPagethree" id="myPagethree">
        <input type="hidden" name="printtoken" value="true" id="printtoken">      
        <input type="hidden" name="report_name" id="report_name" value="{{ $report_name }}">
    </form>  

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/js.cookie.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
    <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.zune.js"></script>
    <script src="js/bootstrap-datepicker.js"></script> 
    <script src="js/hipreports/hipwifi/hipwifi_brand.js"></script>
    
  </body>

</html>