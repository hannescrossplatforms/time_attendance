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
            var myPageone              =       $("#fusion-chart").html();
        //    console.log(myPage); alert(myPage);
            $("#myPageone").val(myPageone);
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
    right: 10%;
    margin-top: -16px;
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
    font-size: 22px;
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
                <h1 class="page-header" >Staff Lookup Report </h1>
          
            <div class="container-fluid">
    

    
                
    <div class="row">
        <h2 style="color: #5ba5d8;font-size: 20px; font-weight: 600;font-family: 'Ubuntu',sans-serif;   " >{{$report_name}} </h2>

<!--        print  button start-->
        <?php if(isset($printButtonToken)) { ?>
        <div id="printButton" class="col-md-4" style="width:20%; float: right;">
            <button type="button" class="btn btn-primary" onclick="convertPDF()">Print to Pdf</button>
        </div>
        <?php } ?>
<!--        print  button end-->
        
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

        <form name="printMyPage" id="printMyPage" action="{{ url('hiptnaStaffLookupDownload') }}" method="post">
            <input type="hidden" name="myPageone" id="myPageone" value="" >
            <input type="hidden" name="printtoken" value="true" id="printtoken">
            <input type="hidden" name="report_name" id="report_name" value="{{$report_name}}">    
        </form>

        <script type="text/javascript">
            $(function(){

                $("#brandreportperiod").hide();

            });


        </script>
  </body>

</html>