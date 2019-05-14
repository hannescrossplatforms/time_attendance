<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon/favicon.ico">

    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>

    <title>User Admin | HipHUB</title>
    <link href="{{ asset('css/bootstrap_pdf.css')}}" rel="stylesheet" media="screen" />

<!--     Custom styles for this template - These are from MADE -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" media="screen" />
 <!--Additional ustom styles for this template - These kept separate from MADE in case MADE updates style.css-->
    <link href="{{asset('/css/styleadditional.css')}}" rel="stylesheet" media="screen" />
    
    <style type="text/css">
    

.col-6-right-al {
    float: right; top: -320px; margin-left: 400px; margin-bottom: 10px;
}
.col-6-left-al {
    top: -35px;margin-bottom: 10px;
}
.chart-wrapper {
    margin-top:-30px;
}
/*#fusion-chart .col-sm-6 {
    max-height: 325px !important;
}*/

#fusion-chart .row img {
    height: 275px;
}
 

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
        
<!--    <link href="{{asset('css/bootstrap_pdf.css') }}" rel="stylesheet" media="screen" />

     Custom styles for this template - These are from MADE 
    <link href="{{ asset('css/style.css')}}" rel="stylesheet" media="screen" />-->
 <!--Additional ustom styles for this template - These kept separate from MADE in case MADE updates style.css-->
<!--    <link href="{{ asset('/css/styleadditional.css')}}" rel="stylesheet" media="screen" />-->
    


    
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
    right: 6%;
}
.pdf_doc_logo img {
    height: 102px;
    width: 125px;
}
#report_period {
    font-size: 22px;
    color: #58A5DA;
    position: relative;
    top: -90px;
}
.page-head {
    color: #5ba5d8;
    font-size: 26px;
    font-family: sans-serif;
}

.modstattitle h3 {
     font-family: sans-serif;
}
</style>
    
    </head>
    
<body class="hipTnA">
    <a id="buildtable"></a>

    <div class="container-fluid" >
        <div class="row" style=" width: 86%; margin: 0px 7% 0 7%; ">

        

        <div class="col-sm-9  col-md-9  main" style="margin-top: -150px; margin-bottom: -60px;">
        <div class="pdf_doc_logo"><img src="{{asset('img/logo_hiphub_logo.jpg')}}"></div><br><br>
            <h1 class="page-head" >Time & Attendance Dashboard</h1>

    <div class="container-fluid" style="margin-top: -150px;">
    

    <div class="row" style="margin-top: -150px;">
        <div class="venuecolheading">Staff Overview</div>
        <div class="col-md-2"  style="width:120px;  float: left;">
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store Today</h3>
                    </div>
                    <div id="staff_today" class="modStatspan">{{$staff_today;}}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2" style="width:120px;  float: left;">
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store This Week</h3>
                    </div>
                    <div id="" class="modStatspan">{{$staff_week}}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2"  style="width:120px;  float: left;">
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store This Month</h3>
                    </div>
                    <div id="" class="modStatspan">{{$staff_month}}</div>
                </div>
            </div>
        </div>

        <div class="col-md-2"  style="width:120px;  float: left;">
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle" style="font-size: 15px; height: 85px; padding:5px 0 0 0; ">
                        <h3>Consecutive Days Without Absenteeism<br></h3>
                    </div>
                    <div id="" class="modStatspan">{{$cons_absent}}</div>
                </div>
            </div>
        </div> 

        <div class="col-md-2"  style="width:120px;  float: left;">
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle" style="font-size: 15px; height: 85px; padding:5px 0 0 0;">
                        <h3>Consecutive Days Without Lateness</h3><br>
                    </div>
                    <div id="" class="modStatspan">{{$cons_lateness}} </div>
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
            <button type="button" class="form-control" onclick="convertPDF()">Save as PDF</button>
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
    <br><br><br><br><br><br><br><br><br><br>
            <div id="fusion-chart" style="float: left;">
                <br><br><br><br><br>
            {{ $fusionchartElement }}
            </div> <!-- fusioncharts ends -->
    
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script> 
        </div>
      </div>
      </div></div>
   </body>
</html>