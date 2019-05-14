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

  <body class="hipReports">
    <div id="loadingDiv" class="overlay" >    
        <img src="./img/loader.gif" style="width:80px;">                
    </div>
      

    <div class="container-fluid">
      <div class="row">

        @include('hipreports.sidebar')

        <?php if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
                      $pos = strpos($_SERVER['REQUEST_URI'],'public');
                      $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
                      $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
                  } else {
                      $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
                  }
        ?>
        <input type="hidden" id="url" name="" value="{{$url}}">

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

          <h1 class="page-header">HipWIFI Reports</h1>
          <div class="reports-subheader">
          </div>

          <div role="tabpanel"> 
            
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a id="dashtab" href="#dashboard" aria-controls="dashboard" role="tab" data-toggle="tab">Dashboard</a></li>
              <li role="presentation"><a id="brandtab" href="#brandLevel" aria-controls="brandLevel" role="tab" data-toggle="tab">Brand Level</a></li>
              <li role="presentation"><a id="venuetab" href="#showvenues" aria-controls="showvenues" role="tab" data-toggle="tab">Venue Level</a></li>
              <!-- <li role="presentation"><a id="statstab" href="#statistics" aria-controls="statistics" role="tab" data-toggle="tab">Statistics</a></li> -->
            </ul>
            <br>
            
            <!-- Tab panes -->
            <div class="tab-content">

              <!-- Dashboard -->
              <div role="tabpanel" class="tab-pane active" id="dashboard"> 
                @include('hipreports.hipwifi_dashboard')
              </div>

              <!-- Brand Level -->
              <div role="tabpanel" class="tab-pane" id="brandLevel">
                @include('hipreports.hipwifi_brand')
              </div>

              <!-- Venue Level - Shoe the list of Venues -->
              <div role="tabpanel" class="tab-pane" id="showvenues">
                @include('hipreports.hipwifi_venues')
              </div>

              <!-- Venue Level - Shoe a specific Venue -->
              <div role="tabpanel" class="tab-pane" id="showvenue">
                @include('hipreports.hipwifi_venue')
              </div>

              <!-- Statistics -->
              <div role="tabpanel" class="tab-pane" id="statistics">
                @include('hipreports.hipwifi_statistics')
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>



  <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog ">
        <div class="modal-content">
          <div class="modal-body">
            <h6 class="modal-title" id="myModalLabel">Generating data. This might take several minutes.</h6>
          </div>
        </div> 
      </div>  
    </div>

                
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/js.cookie.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
    <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.zune.js"></script>
    <script src="js/bootstrap-datepicker.js"></script> 
    
    <script>
       

      $(function() {

        $("#branddaterange").hide();
        $('#loadingDiv').hide();

        tabstatus = Cookies.get('tabstatus');
        if(tabstatus) { 
          $( '#' + tabstatus ).click(); 
        } else {
          $('#dashtab').click(); 
        };
        
        $('#brandreportperiod').change(); 

      });



      $("#from, #venuefrom, #brandfrom, #to, #venueto, #brandto").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "right bottom"
      });
      $("#from, #venuefrom, #brandfrom, #to, #venueto, #brandto").datepicker("setDate", new Date());


      $( "#dashtab" ).click(function(){
        Cookies.set('tabstatus', "dashtab" , { expires: 365 } );
      });

      $( "#brandtab" ).click(function(){
        Cookies.set('tabstatus', "brandtab" , { expires: 365 } );
        
      });

      $( "#venuetab" ).click(function(){
        Cookies.set('tabstatus', "venuetab" , { expires: 365 } );
      });

      $( "#statstab" ).click(function(){
        Cookies.set('tabstatus', "statstab" , { expires: 365 } );
      });
      
      $( "#brandlist, #reportperiod" ).change(function(){
        // $( "#brandfilterform" )[0].submit();
      });

    </script>

    <script>
      ////////////////////// Set up the global variables for the tab scripts ///////////////////////

      window.dashboarddata = {{ $data['dashboarddata'] }};

      window.dashboardurl = "{{ url('lib_dashboardjson'); }}"

      window.venuesJason = {{ $data['venuesJason'] }};
      window.filterdvenuesurl = "{{ url('lib_filterdvenues'); }}"

      window.venuedataurl = "{{ url('hipreports_hipwifi_venuedatajson'); }}"
      window.venuedatasingleurl = "{{ url('hipreports_hipwifi_venuedatajsonsingle'); }}"
      window.branddataurl = "{{ url('hipreports_hipwifi_branddatajson'); }}"
      window.branddatasingleurl = "{{ url('hipreports_hipwifi_branddatajsonsingle'); }}"
      window.downloaduserprofiledataurl = "{{ url('hipreports_hipwifi_downloaduserprofiledata'); }}"
      window.downloadlistcustomerusageurl = "{{ url('hipreports_hipwifi_downloadlistcustomerusage'); }}"

      window.statsdata = {{ $data['statsdata'] }};
      window.statsurl = "{{ url('hipreports_hipwifi_statistcsjson/1'); }}"

      popupchartWidth = "100%";
      popupchartHeight = "300px";


    </script>

  
<script>
</script>

    <!-- /////////////////////// DASHBOARD /////////////////////// -->
    <script src="js/hipreports/hipwifi/hipwifi_dashboard.js"></script> 

    <!-- /////////////////////// BRANDS /////////////////////// -->
    <script src="js/hipreports/hipwifi/hipwifi_brand.js"></script> 

    <!-- /////////////////////// VENUES /////////////////////// -->
    <script src="js/hipreports/hipwifi/hipwifi_venues.js"></script> 
    <script src="js/hipreports/hipwifi/hipwifi_venue.js"></script> 

     <!-- ///////////////////// STATISTICS //////////////////// -->
    <script>
      // $('.datepicker').datepicker({
      //   format: 'yyyy-mm-dd',
      //   autoclose: true,
      //   orientation: "bottom"
      // });
    </script>
     <script type="text/javascript">



     </script>
     <script src="js/hipreports/hipwifi/hipwifi_statistics.js"></script> 

  </body>
@stop
