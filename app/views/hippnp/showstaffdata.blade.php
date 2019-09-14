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

          <form class="form-inline" role="form" style="margin-bottom: 15px;">

            <div class="form-group">
              <label class="sr-only" for="exampleInputEmail2">Surname</label>
              <input type="text" class="form-control" id="src-surname" placeholder="Surname">
            </div>
            <div class="form-group">
              <label  class="sr-only" for="exampleInputEmail2">First Names</label>
              <input type="text" class="form-control" id="src-firstnames" placeholder="First Names">
            </div>

            <button id="filter" type="submit" class="btn btn-primary">Filter</button>
            <button id="reset" type="submit" class="btn btn-default">Reset</button>

          </form>

          <div class="table-responsive">
              <table id="staffTable" class="table table-striped"> </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">Send Message</h6>
          </div>
          <div class="modal-body">
            <textarea id="content" class="form-control no-radius" placeholder="Message content " rows="4" cols="100"></textarea>
            <br>
            <div class="">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Image
                  </div>
                  <div class="panel-body">

                    <div class="col-md-4 pushpic">
                      <div class="form-group">
                        <div id="pushimageedit" style="display:none"></div>
                        <input id="mbimage" type="file" name="mbimage" form="mbimageform">
                          <a  id="mb-file" href="#" class="btn btn-default btn-sm " data-toggle="modal" data-target="#mobileBgModal"  >
                            Upload new image
                          </a>
                      </input>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button id="sendMessage" class="sendMessage" type="button" class="btn btn-primary">Send</button>
              <a href="" class="btn btn-default" data-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>


     <!--     Code for staff member popup graphs -->
    <div id="member_popup" class="modal fade " id="modal_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none; overflow:auto;">
      <div class="modal-dialog">
        <div class="modal-content" style="width:130%;">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
              <span class="sr-only">Close</span>
            </button>
            <h6 class="modal-title" id="myModalLabel"><div id="memberHeader"></div></h6>
            <!--        printpreview button start-->
                  <div id="printButton" class="col-md-4" style="width:20%; margin-right: 55px; margin-top: -30px; float: right;">
                      <button type="button" class="btn btn-primary" onclick="printpreview()">View Printable Page</button>
                  </div>
                  <!--        print preview button end-->
          </div>
          <div class="modal-body" id="modal-body-view">

            <!-- <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;"><!-- This should look the same as in Dashboard -->
            <div>
              <select id="brandreportperiod" name="reportperiod">
                  <option value="today">Today</option>
                  <option value="rep7day" selected>This Week</option>
                  <option value="repthismonth" >This month</option>
                  <option value="replastmonth">Last month</option>
                  <option value="daterange">Custom range</option>
              </select>
              <div id="report_date_details" style="display:none">


              </div>
            </div>

            <div class="clear"></div>

            <div id="member_absence"></div>
            <div id="member_lateness"></div>
            <div id="member_proximity"></div>

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


      $(function() {


      // $(document).delegate('#buildtable', 'click', function() {
      //   showStaffTable(staffJason);
      // });

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
