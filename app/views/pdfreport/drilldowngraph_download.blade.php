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
    
    <link href="{{ asset('/css/style_pdf.css')}}" rel="stylesheet" media="screen" /> <!-- for pdf preview -->
    <style>
    .col-sm-12 .chart-wrapper {
        margin-top: -75px;
    }
    
    .col-sm-8 .fusioncharts-container img {
        width: auto; height: auto;
    }
    .report-period {
        font-size: 18px;
    }
    </style>
    
        
    </head>
    <body>
        
        
        <div id="drill-graph">
        <div class="pdf_doc_logo"><img src="{{asset('img/logo_hiphub_logo.jpg')}}"></div>
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <div class="page-header">
                {{ $page_header }}<br><span style="font-size: 22px; top: -70px;">{{ $report_period }}</span>
            </div> 

            
        </div>
        <div style="margin-top: -100px;">
            {{ $fusionchartElement }}
        </div>
        </div>
        <input type="hidden" name="graph_name" id="graph_name" value="{{ $graph_name}}">
    
    <script type="text/javascript">
    $( document ).ready(function() {   
      tab = $('#graph_name').val(); 
      if(tab == 'absence'){ 
        $('.lateness_list').hide();
        $('.proximity_list').hide();
        $('#lateness').hide();
        $('#wsproximity').hide(); 
        
//        $('.page-header').html('Absent Staff');
        $('.tab-pane').removeClass('active');
        $('#staff_lateness_list').hide();
        $('#staff_proximity_list').hide();
        
        $('#staff_absence_list').show();
        $('#absence').addClass('active');
      }else if(tab == 'lateness'){ 
        $('.proximity_list').hide();
        $('.abscentees_list').hide();

//        $('#wsproximity').html('');
//        $('#absence').html('');

        $('#absence').hide();
        $('#wsproximity').hide(); 

          
//        $('.page-header').html('Late Staff');
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
          
//        $('.page-header').html('Staff Not Meeting Proximity Target');
        $('.tab-pane').removeClass('active');
        $('#staff_absence_list').hide();
        $('#staff_lateness_list').hide();
        $('#staff_proximity_list').show();
        $('#wsproximity').addClass('active');
      }

    });

</script>
    </body>
</html>


