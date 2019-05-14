<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon/favicon.ico">

    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>

    <title>User Admin | HipHUB</title>
    <style type="text/css">
        
    @font-face {
        font-family: 'Glyphicons Halflings';

/*        src: url('../fonts/glyphicons-halflings-regular.eot');
        src: url('../fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('../fonts/glyphicons-halflings-regular.woff') format('woff'), url('../fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('../fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular') format('svg');*/
    }
    </style>
    <!-- Bootstrap core CSS -->
    <script type="text/javascript">
        function convertPDF() {
            var myPage              =       $("#fusion-chart").html();
        //    console.log(myPage); alert(myPage);
            $("#myPage").val(myPage);
            $("#printMyPage").submit()
        }
    </script>
        
    <link href="{{asset('css/bootstrap_pdf.css') }}" rel="stylesheet" media="screen" />

<!--     Custom styles for this template - These are from MADE -->
    <link href="{{ asset('css/style.css')}}" rel="stylesheet" media="screen" />
 <!--Additional ustom styles for this template - These kept separate from MADE in case MADE updates style.css-->
    <link href="{{ asset('/css/styleadditional.css')}}" rel="stylesheet" media="screen" />
    


    
<style type="text/css">
.modstattitle{
    /*background-color: #d3d3d3;#106f5d*/
    background-color: #58A5DA;
    height: 70px;
    padding: 10px;
}
.modstattitle h3{
    color: white;
}
.pdf_doc_logo { 
    position: absolute;
    min-height: 107px;
    min-weight: 107px;
    right: 10%;
}
.pdf_doc_logo img {
    height: 102px;
    width: 125px;
}
#report_period {
    font-size: 22px;
    color: #58A5DA;
}
.page-header {
    color: #5ba5d8;
    font-size: 26px;
}
.testclass {
		float:left; background-color: #58A5DA; width:30%; height: 200px;
	}
</style>
    
    </head>
    
    <body class="hipTnA">
    <style type="text/css">
	.testclass {
		float:left; background-color: #58A5DA; width:30%; height: 200px;
	}
</style>
<!--    <div style="width:100%;height:700px; background:#FFCCC;">
        <div class="testclass">One</div>
            <div class="testclass">Two</div>
            <div class="testclass">Three</div>
            <div class="testclass">Four</div>
            <div class="testclass">Five</div>
    </div>-->
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        

        <div class="col-sm-9 main" style="width: 80%; margin: 0px 10% 0 10%;">
            <div class="pdf_doc_logo"><img src="{{asset('img/logo_hiphub_logo.jpg')}}"></div><br><br>
                    <h1 class="page-header" >Time & Attendance Dashboard</h1>

            <?php 
//            if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
//                      $pos = strpos($_SERVER['REQUEST_URI'],'public');
//                      $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
//                      $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
//                  } else {
//                      $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
//                  }
             ?>

            <div class="container-fluid">
    

    <div class="row">
        <div class="venuecolheading">Staff Overview</div>
        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store Today</h3>
                    </div>
                    <div id="staff_today" class="modStatspan">{{$staff_today;}}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store This Week</h3>
                    </div>
                    <div id="" class="modStatspan">{{$staff_week}}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store This Month</h3>
                    </div>
                    <div id="" class="modStatspan">{{$staff_month}}</div>
                </div>
            </div>
        </div>

        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle" style="padding:4px 0px 0px 0px">
                        <h3>Consecutive Days Without Absenteeism</h3>
                    </div>
                    <div id="" class="modStatspan">{{$cons_absent}}</div>
                </div>
            </div>
        </div> 

        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle" style="padding:4px 0px 0px 0px">
                        <h3>Consecutive Days Without Lateness</h3>
                    </div>
                    <div id="" class="modStatspan">{{$cons_lateness}}</div>
                </div>
            </div>
        </div>       
     
    </div>  
                
    <div class="row">
<!--        <div class="col-md-4" style="width:30%;">
            <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                <label>Report Period</label>
            </div>
            <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                <select id="brandreportperiod" onchange="change_report_period()" class="form-control" name="reportperiod" >
                     <option value="">Select</option> 
                    <option value="rep7day">This Week</option>
                    <option value="repthismonth">This month</option>
                    <option value="replastmonth">Last month</option>
                    <option value="daterange">Custom range</option>
                </select>
            </div>
        </div>-->
        
<!--        printpreview button start-->
        <?php if(isset($printButtonToken)) { ?>
        <div id="printButton" class="col-md-4" style="width:30%; float: right;">
            <button type="button" class="btn btn-primary" onclick="convertPDF()">Print to Pdf</button>
        </div>
        <?php } ?>
<!--        print preview button end-->

        <div class="col-md-8" id="custom" style="display:none; width:70%;">
            <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                <input type="text" class="form-control datepicker" name="venuefrom" id="venuefrom" placeholder="FromDate">
            </div>
            <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                <input type="text" class="form-control datepicker" name="venueto" id="venueto" placeholder="ToDate">
            </div>
            <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                <button type="submit" class="form-control" onclick="custom_report_period()">Submit Date Range</button> 
            </div>
        </div>
        
        
    </div> 

    <br><br>
    <div id="fusion-chart">
        
                    {{ $fusionchartElement }}
                
            </div> <!-- fusioncharts ends -->
    
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script> 

    

    
            
            
        </div>
      </div>
    </div>

  
<!--     <div id="print" class="col-md-4" style="width:30%; float: right;">
        <button type="button" class="form-control" onclick="print()">Print</button>
    </div>-->

        <form name="printMyPage" id="printMyPage" action="{{ url('myPageDownload') }}" method="post">
            <input type="hidden" name="myPage" id="myPage" value="" >
            <input type="hidden" name="printtoken" value="true" id="printtoken">
            <input type="hidden" name="report_name_date" id="report_name_date" value="{{$report_name_date}}">    
        </form>
  </body>

</html>