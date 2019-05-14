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
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    
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
        .pdf_doc_logo { 
            position: absolute;
            min-height: 107px;
            left: 78%; 
            bottom: 93%;
            float: right;
        }
        .pdf_doc_logo img {
            height: 102px;
            width: 125px;
        }
    </style>
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
                    <div>      
                        <?php if(isset($printButtonToken)) { ?>
                        <div id="download-button" class="col-md-4" style="width:20%; float: right;">
                            <button type="button" class="btn btn-primary" onclick="convertVenuePDF()">Print to Pdf</button>
                        </div>
                          <?php } ?>
                    </div>
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
                                {{ $fusionchartElementTwo }}
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


    <script type="text/javascript">
        function convertVenuePDF() {

            var myVenuePageone    =   $("#venue_section_one").html();
            var myVenuePagetwo    =   $("#venue_section_two").html();
            var myVenuePagethree  =   $("#venue_section_three").html();

            var totalWifiSessions = $("#totalwifisessions").html();
            var avgWifiSessions = $("#avgwifisessions").html();


            var wifiDataTotal = $("#totalwifidatatotal").html();
            var avgWifiDataTotal = $("#avgwifidatatotal").html();

            var totalNumberofPeople = $("#totalnumberofpeople").html();
            var avgNumberofPeople = $("#avgnumberofpeople").html();


            var totalTirstTimeUsers = $("#totalfirsttimeusers").html();
            var avgFirstTimeUsers = $("#avgfirsttimeusers").html();

            var venueAvgDataPerSession = $("#venueavgdatapersession").html();
            var brandAvgDataPerSession = $("#brandavgdatapersession").html();

            var venueAvgTimePerSession = $("#venueavgtimepersession").html();
            var brandAvgTimePerSession = $("#brandavgtimepersession").html();


            //console.log(myPage); alert(myPage);
            $("#myVenuePageone").val(myVenuePageone);
            $("#myVenuePagetwo").val(myVenuePagetwo);
            $("#myVenuePagethree").val(myVenuePagethree);

            $("#totalWifiSessions").val(totalWifiSessions);
            $("#avgWifiSessions").val(avgWifiSessions);
            
            $("#wifiDataTotal").val(wifiDataTotal);
            $("#avgWifiDataTotal").val(avgWifiDataTotal);
            
            $("#totalNumberofPeople").val(totalNumberofPeople);
            $("#avgNumberofPeople").val(avgNumberofPeople);
            
            $("#totalTirstTimeUsers").val(totalTirstTimeUsers);
            $("#avgFirstTimeUsers").val(avgFirstTimeUsers);           
            
            $("#venueAvgDataPerSession").val(venueAvgDataPerSession);
            $("#brandAvgDataPerSession").val(brandAvgDataPerSession);
            
            $("#venueAvgTimePerSession").val(venueAvgTimePerSession);
            $("#brandAvgTimePerSession").val(brandAvgTimePerSession);


            $("#printMyPageVenue").submit();
        }
    </script>    
            

<form name="printMyPageVenue" id="printMyPageVenue" action="{{ url('hipwifiVenuePdfDownload') }}" method="post">
<input type="hidden" name="myVenuePageone" id="myVenuePageone">
<input type="hidden" name="myVenuePagetwo" id="myVenuePagetwo">
<input type="hidden" name="myVenuePagethree" id="myVenuePagethree">

<input type="hidden" name="totalWifiSessions" id="totalWifiSessions">
<input type="hidden" name="avgWifiSessions" id="avgWifiSessions">

<input type="hidden" name="wifiDataTotal" id="wifiDataTotal">
<input type="hidden" name="avgWifiDataTotal" id="avgWifiDataTotal">

<input type="hidden" name="totalNumberofPeople" id="totalNumberofPeople">
<input type="hidden" name="avgNumberofPeople" id="avgNumberofPeople">

<input type="hidden" name="totalTirstTimeUsers" id="totalTirstTimeUsers">
<input type="hidden" name="avgFirstTimeUsers" id="avgFirstTimeUsers">

<input type="hidden" name="venueAvgDataPerSession" id="venueAvgDataPerSession">
<input type="hidden" name="brandAvgDataPerSession" id="brandAvgDataPerSession">

<input type="hidden" name="venueAvgTimePerSession" id="venueAvgTimePerSession">
<input type="hidden" name="brandAvgTimePerSession" id="brandAvgTimePerSession">

<input type="hidden" name="printtoken" value="true" id="printtoken">      
<input type="hidden" name="report_name_venue" id="report_name_venue" value="{{ $report_name_venue }}">

</form>
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