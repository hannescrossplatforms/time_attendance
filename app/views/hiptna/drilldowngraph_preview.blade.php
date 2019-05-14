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
    <link href="{{ asset('/css/style_pdf_preview.css')}}" rel="stylesheet" media="screen" /> <!-- for pdf preview -->
    <style type="text/css">
    .csv-button {
        display: none;
    }
    .pdf_doc_logo { 
        position: absolute;
        min-height: 107px;
        min-weight: 107px;
        right: 6%;
        top: -10px;
    }
    .pdf_doc_logo img {
        height: 102px;
        width: 125px;
    }
    .page-header {
        margin-top: 1px;
    }
    #report_period {
        display:block;
        font-size: 25px;
        color: #5ba5d8;
    }
    </style>
    
    
    

    
    
    
    <script type="text/javascript">
        function convertPDF() {
            var myPage              =       $("#drill-graph").html();
        //    console.log(myPage); alert(myPage);
            $("#myPage").val(myPage);
            $("#printMyPage").submit()
        }
    </script>
    
    </script>
        
    </head>
    <body>
        <?php if(isset($printButtonToken)) { ?>
        <div id="printButton" class="col-md-4" style="width:30%; top: 140px; float: right;">
            <button type="button" class="btn btn-primary" onclick="convertPDF()">Print to Pdf</button>
        </div>
        <?php } ?>
        <div id="drill-graph" style="width:80%; margin: 0 auto;">
        <br><br>
        <div class="pdf_doc_logo"><img src="{{asset('img/logo_hiphub_logo.jpg')}}"></div>
        <h1 class="page-header">{{$page_header}}</h1>
            
        {{ $fusionchartElement }}
        
        </div>
        <form name="printMyPage" id="printMyPage" action="{{ url('downloadDrilldown') }}" method="post">
            <input type="hidden" name="myPage" id="myPage" value="" >
            <input type="hidden" name="printtoken" value="true" id="printtoken">
            <input type="hidden" name="graph_name" id="graph_name" value="{{ $graph_name }}">
            <input type="hidden" name="report_name" id="report_name" value="{{ $report_name}}">
        </form>
    </body>
<script type="text/javascript">
    $( document ).ready(function() {   
      tab = $('#graph_name').val(); 
      if(tab == 'absence'){ 
        $('.lateness_list').hide();
        $('.proximity_list').hide();
        $('#lateness').hide();
        $('#wsproximity').hide(); 
        
        $('.page-header').html('Absent Staff');
        $('.tab-pane').removeClass('active');
        $('#staff_lateness_list').hide();
        $('#staff_proximity_list').hide();
        
        $('#staff_absence_list').show();
        $('#absence').addClass('active');
      }else if(tab == 'lateness'){
        $('.proximity_list').hide();
        $('.abscentees_list').hide();
        $('#absence').hide();
        $('#wsproximity').hide(); 
          
        $('.page-header').html('Late Staff');
        $('.tab-pane').removeClass('active');
        $('#staff_absence_list').hide();
        $('#staff_proximity_list').hide();
        $('#staff_lateness_list').show();
        $('#lateness').addClass('active');
      }else if(tab == 'wsproximity'){
        $('.abscentees_list').hide();
        $('.lateness_list').hide();
        $('#absence').hide();
        $('#lateness').hide();
          
        $('.page-header').html('Staff Not Meeting Proximity Target');
        $('.tab-pane').removeClass('active');
        $('#staff_absence_list').hide();
        $('#staff_lateness_list').hide();
        $('#staff_proximity_list').show();
        $('#wsproximity').addClass('active');
      }

    });

</script>
</html>


