@extends('layout')

@section('content')
<style type="text/css">
    .overlay {
      background: rgba(129, 119, 119, 0.5) none no-repeat scroll 0% 0%;
      width: 100%;
      height: 100%;
      position: fixed;
      top: 0px;
      left: 1px;
      z-index: 1019;
      padding-left: 53%;
      padding-top: 20%;
      }

</style>

  <body class="hipTnA">
      
    <div id="loadingDiv" class="overlay" >    
      <img src="./img/loader.gif" style="width:80px;">                
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="{{ asset('/css/style_pdf_preview.css')}}" rel="stylesheet" media="screen" /> <!-- for pdf preview -->
    <style type="text/css">
    #report_period {
        display:none;
    }
    </style>
    <div class="container-fluid">
      <div class="row">

        @include('hiptna.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Exception Reports</h1>
          <div class="row">
            <div role="tabpanel">               

              <div class="row">
                <div class="col-md-4" style="width:30%;">
                  <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                      <label>Report Period</label>
                  </div>
                  <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;"><!-- This should look the same as in Dashboard -->
                    <select id="brandreportperiod" name="reportperiod" onchange="exception_change_report_period()">
                        <option value="today">Today</option>
                        <option value="rep7day">This Week</option>
                        <option value="repthismonth">This month</option>
                        <option value="replastmonth">Last month</option>
                        <option value="daterange">Custom range</option>
                    </select>
                  </div>
                </div>
                <div id="printButton" class="col-md-4" style="width:30%; float: right;">
                    <button type="button" class="btn btn-primary" onclick="printpreview()">View Printable Page</button>
                </div>
                <div class="col-md-8" id="custom" style="display:none; width:70%;">
                  <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                      <input type="text" class="form-control datepicker" name="venuefrom" id="venuefrom" placeholder="FromDate">
                  </div>
                  <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                      <input type="text" class="form-control datepicker" name="venueto" id="venueto" placeholder="ToDate">
                  </div>
                  <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                      <button type="submit" class="form-control" onclick="exception_custom_report_period()">Submit Date Range</button> 
                  </div>
                </div>
              </div>
              <br> 
              
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"  class="active"><a id="absencetab" href="#absence" aria-controls="absence" role="tab" data-toggle="tab">Absence</a></li>
                <li role="presentation"><a id="latenesstab" href="#lateness" aria-controls="lateness" role="tab" data-toggle="tab">Lateness</a></li>
                <li role="presentation"><a id="wsproximitytab" href="#wsproximity" aria-controls="wsproximity" role="tab" data-toggle="tab">WS Proximity</a></li>
              </ul>
              <br>



              <!-- Tab panes -->
              <div class="tab-content">
                <div id="report_period"></div>
                <div role="tabpanel" class="tab-pane active" id="absence"> 
                  @include('hiptna.absencestafflist') 
                </div>

                <div role="tabpanel" class="tab-pane" id="lateness">
                  @include('hiptna.latenessstafflist') 
                </div>

                <div role="tabpanel" class="tab-pane" id="wsproximity">
                  @include('hiptna.wsproximitystafflist') 
                </div>

              </div>

              

            </div>
          </div>
        </div>
      </div>
    </div>

    
     <!--     Code for staff member popup graphs -->
    <div id="memberGraphModalhtml"> </div>

    <div id="member_popup" class="modal fade " id="modal_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none; overflow:auto;">
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

    <script type="text/javascript">
      $(function() {
        availableInstances = "{{ Session::get('availableInstances') }}";
        currentInstance = "{{ Session::get('currentInstance') }}";
      });
    </script>
    
    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
    <script type="text/javascript">
    $("#venuefrom, #venueto").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "right bottom"
      });
      $("#venuefrom, #venueto").datepicker("setDate", new Date());

    </script>
    
    <script type="text/javascript">
    $( document ).ready(function() {
      $('#loadingDiv').hide();
      $('#absencetab').click(function() {
          $('#graph_name').val('absence');
      });
      $('#latenesstab').click(function() {
          $('#graph_name').val('lateness');
      });
      $('#wsproximitytab').click(function() {
          $('#graph_name').val('wsproximity');
      });      
      
      var tab = window.location.hash;

      if(tab == '#absence'){ 
        $('#graph_name').val('absence');
        $('.tab-pane').removeClass('active');
        $('#absence').addClass('active');
      }else if(tab == '#lateness'){
        $('#graph_name').val('lateness');
        $('.tab-pane').removeClass('active');
        $('#lateness').addClass('active');
      }else if(tab == '#wsproximity'){
        $('#graph_name').val('wsproximity');
        $('.tab-pane').removeClass('active');
        $('#wsproximity').addClass('active');
      }

    });

</script>

<script type="text/javascript">



    $( document ).ready(function() {

      pathname = $('#url').val(); //
        //----------------staff absent-----------
        

        $.ajax({

            url: pathname+'hiptna/exceptionchart',
            type: 'get',
            dataType: 'json',
            data : ''/*{ 'period':time,'start':start,'end':end }*/,
            success: function(data) { 
              $('#fullData').val(data);
              fullData = data;

              $("#report_period").html(data.report_period);
              $("#report_name_date").val(data.report_name_date);
              
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
                  width: '500',
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
                width: '500',
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
                  width: '500',
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
              var yaxisabsent = (most_absent.length == 0 || most_absent[0].value < 2 ) ? 5 : most_absent[0].value;
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
                      "yAxisName": "Days absent",
                      "yAxisMaxValue": yaxisabsent,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisabsent - 2,
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
                      "yAxisName": "Days absent",
                      "yAxisMaxValue": yaxisabsent,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisabsent - 2,
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
              var yaxislateness = (most_late.length == 0 || most_late[0].value < 2 ) ? 5 : most_late[0].value;
              //get least 5 lateness details
              var max_length_l = data.staff_lateness.length; 
              var min_length_l = max_length - 5;
              var least_late = [];
              $.each(data.staff_lateness.slice(min_length_l,max_length_l).reverse(), function(i, list) {
                  least_late.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Number of days late",
                      "yAxisMaxValue": yaxislateness,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxislateness - 2,
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
                      "caption": "Bottom 5 ",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Number of days late",
                      "yAxisMaxValue": yaxislateness,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxislateness - 2,
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
              var yaxisproximity = (most_proximity.length == 0 || most_proximity[0].value < 2 ) ? 5 : most_proximity[0].value;
              //get least 5 proximity details
              var max_length_p = data.staff_proximity.length; 
              var min_length_p = max_length - 5;
              var least_proximity = [];
              $.each(data.staff_proximity.slice(min_length_p,max_length_p).reverse(), function(i, list) {
                  least_proximity.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days not meeting target",
                      "yAxisMaxValue": yaxisproximity,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisproximity - 2,
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
                      "yAxisName": "Days not meeting target",
                      "yAxisMaxValue": yaxisproximity,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisproximity - 2,
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
              var yaxisearly_leaving = (most_early_leaving.length == 0 || most_early_leaving[0].value < 2 ) ? 5 : most_early_leaving[0].value;
              //get least 5 early leaving details
              var max_length_e = data.staff_early_leaving.length; 
              var min_length_e = max_length_e - 5;
              var least_early_leaving = [];
              $.each(data.staff_early_leaving.slice(min_length_e,max_length_e).reverse(), function(i, list) {
                least_early_leaving.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                    "caption": "Top 5 - Early Leavers",
                    "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                    "yAxisName": "Days Arriving Early",
                    "yAxisMaxValue": yaxisearly_leaving,
                    "yAxisMinValue": 0,
                    "numDivLines" : yaxisearly_leaving - 2,
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
                    "caption": "Bottom 5 - Early Leavers",
                    "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                    "yAxisName": "Days Arriving Early",
                    "yAxisMaxValue": yaxisearly_leaving,
                    "yAxisMinValue": 0,
                    "numDivLines" : yaxisearly_leaving - 2,
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

    });

    function previewPDF() {
        var myPage              =       $(".tab-content").html();
    //    console.log(myPage); alert(myPage);
        $("#myPage").val(myPage);
        $("#viewMyPage").submit()
    }
    
</script>
    
    <form name="viewMyPage" id="viewMyPage" target="_blank" action="{{ url('downloadExceptionReport') }}" method="post">
            <input type="hidden" name="myPage" id="myPage">
            <input type="hidden" name="graph_name" id="graph_name" value="absence">
            <input type="hidden" name="page-header" id="page-header" value="">
            <input type="hidden" name="report_name_date" id="report_name_date" value="">
    </form>

  </body>
@stop
