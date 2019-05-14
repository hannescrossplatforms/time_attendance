@extends('layout')

@section('content')

  <body class="hipENGAGE">
  <a id="initiatetable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

          <h1 class="page-header">Venue Monitoring</h1>
          <div class="reports-subheader">
          </div>

          <div role="tabpanel"> 
            
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a id="dashtab" href="#beaconpanel" aria-controls="beaconpanel" role="tab" data-toggle="tab">Beacons</a></li>
              <li role="presentation"><a id="brandtab" href="#sensorpanel" aria-controls="sensorpanel" role="tab" data-toggle="tab">Sensors</a></li>
            </ul>
            <br>
            
            <!-- Tab panes -->
            <div class="tab-content">

              <div role="tabpanel" class="tab-pane active" id="beaconpanel"> 
                  <div class="form-group monitoring-form">
                    <form class="form-inline" role="form" style="margin-bottom: 15px;">
                        <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                        <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
                        <button type="submit" id="filter-sitename" class="btn btn-primary">Filter</button>&nbsp &nbsp &nbsp &nbsp &nbsp
                        <input type="text" class="form-control" id="src-beaconid" placeholder="Beacon Id">
                        <button type="submit" id="filter-beaconid" class="btn btn-primary">Filter</button>&nbsp &nbsp &nbsp &nbsp &nbsp
                        <input type="text" class="form-control" id="src-position" placeholder="Position Name">
                        <button type="submit" id="filter-position" class="btn btn-primary">Filter</button>&nbsp &nbsp &nbsp &nbsp &nbsp
                        <button id="reset" type="submit" class="btn btn-default">Reset</button>
                    </form>
                  </div>

                <div id="beaconsTable"></div>
              </div>

              <div role="tabpanel" class="tab-pane" id="sensorpanel">
                No Data
                <div id="sensorsTable"></div>
              </div>

            </div>
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
    
    <script>

      $(function() {

        // $("#branddaterange").hide();

        // tabstatus = Cookies.get('tabstatus');
        // if(tabstatus) { 
        //   $( '#' + tabstatus ).click(); 
        // } else {
        //   $('#dashtab').click(); 
        // };
        
        // $('#brandreportperiod').change(); 

      });

     </script>




     <script>

      $(function() {
        $('#initiatetable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        reverse = false;
      });

      $(document).delegate('#initiatetable', 'click', function() {
        initiateBeaconsTable();
      });

      $(document).delegate('#reset', 'click', function() {
          event.preventDefault();
          showBeaconsTable(beacons);
      });

      $( "#filter-sitename" ).click(function(event) {
          event.preventDefault();
          filterBeacons("sitename", $("#src-sitename").val());
      });

       $( "#filter-beaconid" ).click(function(event) {
          event.preventDefault();
          filterBeacons("beacon_id", $("#src-beaconid").val());
      });

       $( "#filter-position" ).click(function(event) {
          event.preventDefault();
          filterBeacons("position", $("#src-position").val());
      });

      $( "#src-sitename" ).change(function(event) {
      });

      $( "#src-beaconid" ).change(function(event) {
      });

      $( "#src-position" ).change(function(event) {
      });

      $( document ).on( 'click', '#sitenameSort', function (event) {
          filteredBeacons.sort(sort_by('sitename', function(a){return a}));
          showBeaconsTable(filteredBeacons);
      });

      $( document ).on( 'click', '#positionSort', function (event) {
          filteredBeacons.sort(sort_by('position', function(a){return a}));
          showBeaconsTable(filteredBeacons);
      });

      $( document ).on( 'click', '#beacon_idSort', function (event) {
          filteredBeacons.sort(sort_by('beacon_id', function(a){return a}));
          showBeaconsTable(filteredBeacons);
      });

      $( document ).on( 'click', '#last_checkinSort', function (event) {
          filteredBeacons.sort(sort_by('last_checkin', function(a){return a}));
          showBeaconsTable(filteredBeacons);
      });

      $( document ).on( 'click', '#battery_levelSort', function (event) {
          filteredBeacons.sort(sort_by('battery_level', parseInt));
          showBeaconsTable(filteredBeacons);
      });

      sort_by = function(field, primer){

         if(reverse) { reverse = false } else { reverse = true };

         var key = function (x) {return primer ? primer(x[field]) : x[field]};

         return function (a,b) {
          var A = key(a), B = key(b);
          return ( (A < B) ? -1 : ((A > B) ? 1 : 0) ) * [-1,1][+!!reverse];                  
         }
      }

      function initiateBeaconsTable () {

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_filterbeacons'); }}",
            success: function(beaconsjson) {
              filteredBeacons = beacons = jQuery.parseJSON(beaconsjson); // Global setting for the beacons list

              // beacons.sort(compare);

              // beacons.sort(sort_by('sitename', false, function(a){return a.toUpperCase()}));   
              beacons.sort(sort_by('battery_level', parseInt));

              showBeaconsTable(beacons);

            }
         });        
      }


      function filterBeacons (searchfield, searchstring) {

          console.log("filterBeacons : searchfield " + searchfield);
          console.log("filterBeacons : searchString " + searchstring);

          filteredBeacons = [];

          $.each(beacons, function(index, value) {
            console.log("filterBeacons : beacon " + value["beacon_id"]);

            regexp = new RegExp(searchstring,"gi");
            if(value[searchfield].match(regexp)) { filteredBeacons.push(value); }
          });
          
          showBeaconsTable(filteredBeacons);
      }


      function getBatteryDiv(battery_level){

        // Calculate widtch based on a total width of 200px
        leftwidth = battery_level * 2;
        rightwidth = 200 - leftwidth;

        // red (0-20) or orange (21 - 50) or green (50 - 100)

        if(battery_level <=20) {
          leftdiv_color = "red";
        } else if (battery_level <=50) {
          leftdiv_color = "orange";
        } else {
          leftdiv_color = "green";
        }

        battery_div = '\
              <div class="batterycell" style="width:' + leftwidth + 'px;background-color:' + leftdiv_color + '">' + battery_level + '%</div><div class="batterycell" style="width:' + rightwidth + 'px;background-color:white">&nbsp</div>\n\
          \n';

        return battery_div;
      }

      function showBeaconsTable(beacons) {


        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th><a id="sitenameSort">Site Name</a></th>\n\
                      <th><a id="beacon_idSort">Beacon Id</a></th>\n\
                      <th><a id="positionSort">Position</a></th>\n\
                      <th><a id="last_checkinSort">Last Checkin</a></th>\n\
                      <th><a id="battery_levelSort">Battery Level</a></th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(beacons, function(index, value) {

            if(value["battery_level"] != "999") {
              batteryDiv = getBatteryDiv(value["battery_level"]);
            } else {
              batteryDiv = "---";
            }
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + value["beacon_id"]  + '</td>\n\
                      <td> ' + value["position"]  + '</td>\n\
                      <td> ' + value["last_checkin"]  + '</td>\n\
                      <td><a title="' + value["battery_level"] + '%"> ' + batteryDiv + '</a> </td>\n\
                    </tr>\n\
                    ';
        });
                      // <td> ' + value["battery_level"]  + '</td>\n\

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#beaconsTable" ).html( table );
      }



    </script>








  </body>
@stop
