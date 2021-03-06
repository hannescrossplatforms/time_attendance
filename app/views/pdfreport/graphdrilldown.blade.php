@extends('layout')

@section('content')

  <body class="hipTnA">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="{{ asset('/css/style_pdf_preview.css')}}" rel="stylesheet" media="screen" /> <!-- for pdf preview -->
    <style type="text/css">
/*    #report_period {
        display:none;
    }*/
    .csv-button {
        display: none;
    }
    .modstattitle {
        background: #58A5DA;
        height: 70px;
        padding: 10px;
    }
    .modstattitle h3{
        color: white;
    }
    </style>
    <div class="container-fluid">
      <div class="row">

       <?php 
            if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
                $pos = strpos($_SERVER['REQUEST_URI'],'public');
                $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
                $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
            } else {
                $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
            }
              ?>
          <input type="hidden" id="url" name="" value="{{$url}}">
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
        
        <!-- <div><a href="{{ url('/hiptna_showdashboard') }}"><< Back </a></div> --> <!-- takes you back to the dashboard -->
          <h1 class="page-header"></h1>
          <div class="row"> 

              <div class="row">
                  
                <div class="col-md-8" id="custom" style="display:none; width:70%;">
                  <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                      <input type="text" class="form-control" name="venuefrom" id="venuefrom" value="{{ $venuefrom }}" placeholder="FromDate">
                  </div>
                  <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                      <input type="text" class="form-control" name="venueto" id="venueto" value="{{ $venueto }}" placeholder="ToDate">
                  </div>
                  <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                      <button type="submit" class="form-control" onclick="exception_custom_report_period()">Submit Date Range</button> 
                  </div>
                </div>
              </div>
              <br> 
          <!-- Include the appropriate staff list here -->
            <!-- Tab panes -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane" id="absence"> 
                  @include('hiptna.absencestafflist') 
                </div>
                <div role="tabpanel" class="tab-pane active" id="lateness">
                  @include('hiptna.latenessstafflist') 
                </div>
                <div role="tabpanel" class="tab-pane" id="wsproximity" >
                  @include('hiptna.wsproximitystafflist') 
                </div>

              </div>


          </div>
        </div>
      </div>
    </div>

    <!--     Code for staff member popup graphs -->
    <div id="memberGraphModalhtml"> </div>

    <div id="member_popup" class="modal fade " id="modal_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none; overflow:auto; width:auto;">
      <div class="modal-dialog">
        <div class="modal-content" style="width:130%;">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
              <span class="sr-only">Close</span>
            </button>
            <h6 class="modal-title" id="myModalLabel"><div id="memberHeader"></div></h6>
          </div>
          <div class="modal-body">
            <div id="member_absence"></div>
            <br/>
            <br/>
            <div id="member_lateness"></div>
            <br/>
            <div id="member_proximity"></div>
            <br/>
          </div>
        </div>
      </div>
    </div>
    <form name="viewMyPage" id="viewMyPage" target="" action="{{ url('autoDownloadPdf') }}" method="post">
            <input type="hidden" name="myPage" id="myPage">
            <input type="hidden" name="graph_name" id="graph_name" value="{{ $graph_name }}">
            <input type="hidden" name="page-header" id="page-header" value="">
            <input type="hidden" name="report_name_date" id="report_name_date" value="">
            <input type="hidden" name="report_period" id="report_period" value="">
            <input type="hidden" name="svg_images" id="svg_images" value="">
            <input type="hidden" name="filename" id="filename" value="{{ $filename }}">
    </form>
    
    <input type="hidden" name="period" id="period" value="{{$period}}">
    <div id="memberModalLinkDiv"> </div>
    <script src="{{ asset('js/hiptna/membergraphs.js') }}"></script>

    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/js.cookie.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
    <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.zune.js"></script>
    <script src="js/bootstrap-datepicker.js"></script> 
    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
    
    <script type="text/javascript">
    $( document ).ready(function() {
      



      
      var tab = $('#graph_name').val(); 
      if(tab == 'absence'){
        // $('#graph_name').val("absence");
        $('.page-header').html('Absent Staff');
        $('.tab-pane').removeClass('active');
        $('#staff_lateness_list').html('').hide();
        $('#staff_proximity_list').html('').hide();
        $('#staff_absence_list').show();
        
        $('#lateness .venuerow').html('').css('border','none');
        $('#wsproximity .venuerow').html('').css('border','none');
        $('#most_proximity_list').html('');
        $('#least_proximity_list').html('');
        
        $('#absence').addClass('active');
      }else if(tab == 'lateness'){
        // $('#graph_name').val("lateness");
        $('.page-header').html('Late Staff');
        $('.tab-pane').removeClass('active');
        
        $('#staff_absence_list').html('').hide();
        $('#staff_proximity_list').html('').hide();
        $('#staff_lateness_list').show();
        
        $('#absence .venuerow').html('').css('border','none');
        $('#wsproximity .venuerow').html('').css('border','none');
        $('#most_absence_list').html('');
        $('#least_absence_list').html('');
        $('#most_proximity_list').html('');
        $('#least_proximity_list').html('');
        

        $('#lateness').addClass('active');
      }else if(tab == 'wsproximity'){
        // $('#graph_name').val("wsproximity");
        $('.page-header').html('Staff Not Meeting Proximity Target');
        $('.tab-pane').removeClass('active');

        $('#staff_absence_list').html('').hide();
        $('#staff_lateness_list').html('').hide();
        $('#staff_proximity_list').show();

        $('#absence .venuerow').html('').css('border','none');
        $('#lateness .venuerow').html('').css('border','none');
        
        $('#most_absence_list').html('');
        $('#least_absence_list').html('');


        $('#wsproximity').addClass('active');
      }
      
      $('#page-header').val($('.page-header').html());

    });

</script>

<script type="text/javascript">
    $( document ).ready(function() {

      pathname = $('#url').val(); 
        //----------------staff absent-----------
      var time = $("#period").val(); 
      if(time == 'daterange'){
          end = '<?php echo $venueto;?>';
          start = '<?php echo $venuefrom;?>';
      }else{
          start = '';
          end = '';
      }  

      var drillDownRender   =   $.ajax({
          
            url: pathname+'hiptna/periodExceptionchartJsondata',
            type: 'get',
            dataType: 'json',
            async   : false,
            data : { 'period':time,'start':start,'end':end },
            success: function(data) { 
                $("#report_period").val(data.report_period);
                $("#report_name_date").val(data.report_name_date);
                
              $('#fullData').val(data);
              fullData = data;
              //absent
              var chartProperties = {
                  "caption": "",
                  "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                  "yAxisName": "Absence",
                  "rotatevalues": "1",
                  "theme": "zune",
                  "link": "JavaScript:membergraph("+data.external_user_id+")"
            
              };
              apiChart = new FusionCharts({
                  type: 'bar2d',
                  renderAt: 'staff_absence_list',
                  width: '800',
                  height: getChartHeight(data.staff_absent.length),
                  dataFormat: 'json',
                  dataSource: {
                      "chart": chartProperties,
                      "data": data.staff_absent
                  }
              });
              apiChart.render();

              //lateness
              var chartProperties = {
                "caption": "",
                "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                "yAxisName": "Lateness",
                "rotatevalues": "1",
                "theme": "zune",
                "link": "JavaScript:membergraph("+data.external_user_id+")"
            };

            apiChart = new FusionCharts({
                type: 'bar2d',
                renderAt: 'staff_lateness_list',
                width: '800',
                height: getChartHeight(data.staff_lateness.length),
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
              "data": data.staff_lateness
              
                }
            });
            apiChart.render();


            //proximity
            var chartProperties = {
                  "caption": "",
                  "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                  "yAxisName": "Proximity",
                  "rotatevalues": "1",
                  "theme": "zune",
                  "link": "JavaScript:membergraph("+data.external_user_id+")"
              };

              apiChart = new FusionCharts({
                  type: 'bar2d',
                  renderAt: 'staff_proximity_list',
                  width: '800',
                  height: getChartHeight(data.staff_proximity.length),
                  dataFormat: 'json',
                  dataSource: {
                      "chart": chartProperties,
                      "data": data.staff_proximity
                  }
              });
              apiChart.render();

              //get most 5 absent details
              var most_absent = [];
              $.each(data.staff_absent.slice(0,5), function(i, list) {
                  most_absent.push(list);
              });
              //get least 5 absent details
              var max_length = data.staff_absent.length; 
              var min_length = max_length - 5;
              var least_absent = [];
              $.each(data.staff_absent.slice(min_length,max_length).reverse(), function(i, list) {
                  least_absent.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Absence",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_absence_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": most_absent

                      }
                  });
                  apiChart.render();

                  var chartProperties = {
                      "caption": "Bottom 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Absence",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'least_absence_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": least_absent

                      }
                  });
                  apiChart.render();

                  //------------------------

              //get most 5 lateness details
              var most_late = [];
              $.each(data.staff_lateness.slice(0,5), function(i, list) {
                  most_late.push(list);
              });
              //get least 5 lateness details
              var max_length_l = data.staff_lateness.length; 
              var min_length_l = max_length_l - 5;
              var least_late = [];
              $.each(data.staff_lateness.slice(min_length_l,max_length_l).reverse(), function(i, list) {
                  least_late.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Lateness",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_lateness_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": most_late

                      }
                  });
                  apiChart.render();

                  var chartProperties = {
                      "caption": "Bottom 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Lateness",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'least_lateness_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": least_late

                      }
                  });
                  apiChart.render();

                      //------------------------
                  
              //get most 5 proximity details
              var most_proximity = [];
              $.each(data.staff_proximity.slice(0,5), function(i, list) {
                  most_proximity.push(list);
              });
              //get least 5 proximity details
              var max_length_p = data.staff_proximity.length; 
              var min_length_p = max_length_p - 5;
              var least_proximity = [];
              $.each(data.staff_proximity.slice(min_length_p,max_length_p).reverse(), function(i, list) {
                  least_proximity.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Proximity",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_proximity_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": most_proximity

                      }
                  });
                  apiChart.render();

                  var chartProperties = {
                      "caption": "Bottom 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Proximity",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'least_proximity_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": least_proximity

                      }
                  });
                  apiChart.render();

              //get most 5 early leaving details
                    var most_early_leaving = [];
                    $.each(data.staff_early_leaving.slice(0,5), function(i, list) {
                        most_early_leaving.push(list);
                    });
                    //get least 5 proximity details
                    var max_length_e = data.staff_early_leaving.length; 
                    var min_length_e = max_length_e - 5;
                    var least_early_leaving = [];
                    $.each(data.staff_early_leaving.slice(min_length_e,max_length_e).reverse(), function(i, list) {
                      least_early_leaving.push(list);
                    }); //console.log(least_absent); alert("jjj");
                    var chartProperties = {
                          "caption": "Top 5",
                          "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                          "yAxisName": "Proximity",
                          "rotatevalues": "1",
                          "theme": "zune",
                          "link": "JavaScript:membergraph("+data.external_user_id+")"
                      };

                      apiChart = new FusionCharts({
                          type: 'bar2d',
                          renderAt: 'most_early_leaving',
                          width: '300',
                          height: '220',
                          dataFormat: 'json',
                          dataSource: {
                              "chart": chartProperties,
                              "data": most_early_leaving

                          }
                      });
                      apiChart.render();

                      var chartProperties = {
                          "caption": "Bottom 5",
                          "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                          "yAxisName": "Proximity",
                          "rotatevalues": "1",
                          "theme": "zune",
                          "link": "JavaScript:membergraph("+data.external_user_id+")"
                      };

                      apiChart = new FusionCharts({
                          type: 'bar2d',
                          renderAt: 'least_early_leaving',
                          width: '300',
                          height: '220',
                          dataFormat: 'json',
                          dataSource: {
                              "chart": chartProperties,
                              "data": least_early_leaving

                          }
                      });
                      apiChart.render();


            }
        });
        
         setTimeout(function(){
          printpreview();
        }, 8000);
    });

    function previewPDF(fusionImages) { 
        var myPage              =       $(".tab-content").html();
        if(fusionImages) {
            var svgImage    =   '';

            $.each(fusionImages, function(i, image) { 
                svgImage        =  svgImage+","+image; 
            });
            $("#svg_images").val(svgImage);
        }
        
        $("#myPage").val(myPage);
        $("#viewMyPage").submit();
    }

</script>

    
  </body>
@stop
