@extends('layout')

@section('content')
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
</style>
  <body class="hipTnA">
    <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savelookupmedia'); }}"></form>
    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>

    <div class="container-fluid">
      <div class="row">

        @include('hippnp.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          <h1 class="page-header">Staff Lookup</h1>

            <div class="row">
                <div class="col-md-4" style="width:30%;">
                    <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                        <label>Report Period</label>
                    </div>
                    <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                        <select id="brandreportperiod" onchange="change_report_period()" class="form-control"
                            name="reportperiod">
                            <option value="today">Today</option>
                            <option value="rep7day">This Week</option>
                            <option value="repthismonth">This month</option>
                            <option value="replastmonth">Last month</option>
                            <option value="daterange">Custom range</option>
                        </select>
                    </div>
                </div>

                <!--        printpreview button start-->
                <div id="printButton" class="col-md-4" style="width:30%; float: right;">
                    <!-- <button type="button" class="btn btn-primary">View Printable Page</button> -->
                </div>
                <!--        print preview button end-->

                <div class="col-md-8" id="custom" style="display:none; width:70%;">
                    <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                        <input type="text" class="form-control datepicker" name="venuefrom" id="venuefrom"
                            placeholder="FromDate">
                    </div>
                    <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                        <input type="text" class="form-control datepicker" name="venueto" id="venueto"
                            placeholder="ToDate">
                    </div>
                    <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                        <button type="submit" class="form-control" onclick="custom_report_period()">Submit Date
                            Range</button>
                    </div>
                </div>
            </div>

            <div id="fusion-chart">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="chart-wrapper">
                            <div class="chart-title venuecolheading">Staff beacon activity</div>
                            <div class="chart-stage">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="chart-stage">
                                            <div id="staff_beacon_activity">Loading...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
      </div>
    </div>



    <!--     Code for staff member popup graphs -->
    <div id="memberGraphModalhtml"> </div>
    <div id="memberModalLinkDiv"> </div>
    <script src="{{ asset('js/hiptna/membergraphs.js') }}"></script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>



    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>

    <script src="{{ asset('js/prefixfree.min.js') }}"></script>


    <script src="{{ asset('js/hiptna/push.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/js.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fusioncharts/fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fusioncharts/themes/fusioncharts.theme.zune.js') }}"></script>
    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>

    <script>


        $(document).ready(function() {

            var chartProperties = {
                "caption": "",
                "xAxisName": "Category",
                "yAxisName": "Total dwell time (minutes)",
                "rotatevalues": "1",
                "theme": "fusion"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn2d',
                renderAt: 'staff_beacon_activity',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [{
                        "category": <?php echo $data['time_list']; ?>
                    }],
                    "dataset": "";

                }
            });
            apiChart.render();
            debugger;
        });



      $(function() {


      $(document).delegate('#reset', 'click', function() {
        showStaffTable(staffJason);
      });

      $(document).delegate('.staff-view', 'click', function() {
        external_user_id = this.getAttribute('data-external-user-id');
        membergraph(external_user_id);
        // showmembergraphs(3254, 'Bloggs, Joe Peter')

      });

      $("#brandreportperiod").change(function(event) {
        membergraph(external_user_id);
      });

      $(document).delegate('.pagesendbutton', 'click', function(event) {
        external_user_id = this.getAttribute('data-external-user-id');
      });

      $( ".sendMessage" ).on( "click", function(event) {
        event.preventDefault();

        content = $("#content").val();

        $('#messageModal').modal('toggle');

        event.preventDefault();
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();

        surname = $( "#src-surname" ).val();
        firstnames = $( "#src-firstnames" ).val();

        showStaffTable(filteredStaffjson);
      });

      function showStaffTable(staffjson) {

      }

    </script>
    <script type="text/javascript">
      function previewPDF() {
          var myPageone    =   $("#modal-body-view").html();
          var staffName =  $( "#memberHeader" ).text();
          var report_date = $("#report_date_details").text();
          var report_name = staffName+"-"+report_date;
        //console.log(myPage); alert(myPage);
        //alert(report_name);

          $("#myPageone").val(myPageone);
          $("#report_name").val(report_name);
          $("#viewMyPage").submit();
      }



</script>

    <form name="viewMyPage" id="viewMyPage" target="_blank" action="{{ url('hiptnaStaffLookupDownload') }}" method="post">
      <input type="hidden" name="myPageone" id="myPageone">
      <input type="hidden" name="report_name" id="report_name">

    </form>

  </body>
@stop
