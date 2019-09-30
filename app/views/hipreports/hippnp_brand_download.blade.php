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
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    <!-- Latest compiled JavaScript -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->

    <!-- <link href="{{asset('css/bootstrap.css') }}" rel="stylesheet" media="screen" />
    <link href="{{asset('css/bootstrap-social.css')}}" rel="stylesheet" media="screen" /> -->

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
          top:-8;
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

      .fusioncharts-container img {
        width:300px;
      }

      #section_two {
        /* margin-top: -100px!important; */
        height: 300px!important;
      }

      #section_three {
        margin-top: -100px!important;
        height: 300px!important;
      }


      #section_four {
        margin-top: -100px!important;
        height: 300px!important;
      }

      .charty {
        width: 500px !important;
        height: 300px !important;
        display: inline-block !important;
      }


    </style>

    <script type="text/javascript">

        //  $(function(){
        //     $("img").attr(style:"width:200px");

        //  });


    </script>

  </head>

<body class="hipReports">

   <div class="container-fluid">
      <div class="row">

        <div class="col-sm-9 main" style="width: 80%; margin: 0px 10% 0 12%;">

          <h1 class="page-header">Pick n Pay Report</h1>
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
                            <div id="section_two">
                                {{ $fusionchartElementTwo }}
                            </div>

                            <div id="section_three">
                                {{ $fusionchartElementThree }}
                            </div>

                            <div id="section_four">
                                {{ $fusionchartElementFour }}
                            </div>

                        </div>

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
            var myPagethree    =   $("#section_three").html();
            var myPagefour    =   $("#section_four").html();

            //console.log(myPage); alert(myPage);
            $("#myPageone").val(myPageone);
            $("#myPagetwo").val(myPagetwo);
            $("#myPagethree").val(myPagethree);
            $("#myPagefour").val(myPagefour);
            $("#printMyPage").submit();
        }
    </script>



    <form name="printMyPage" id="printMyPage" action="{{ url('hippnpBrandPdfDownload') }}" method="post">
        <input type="hidden" name="myPageone" id="myPageone">
        <input type="hidden" name="myPagetwo" id="myPagetwo">
        <input type="hidden" name="myPagethree" id="myPagethree">
        <input type="hidden" name="printtoken" value="true" id="printtoken">
        <input type="hidden" name="reportName" id="reportName" value="{{ $reportName }}">
    </form>



  </body>

</html>