@extends('angle_layout')

@section('content')


<a id="buildtable"></a>










    <!-- Modal -->


    <section class="section-container">
        <!-- Page content-->
        <div class="content-wrapper">
            <div class="content-heading">
                <div>Sensor Monitoring<small data-localize="dashboard.SENSORMONITORING"></small></div><!-- START Language list-->
            </div>
            <div class="row">
                <div class="col-12 main">
                    <div class="card card-default card-demo">
                        <div class="card-header">
                            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
                                <em class="fas fa-sync"></em>
                            </a>
                            <div class="card-title">
                                All Sensors
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <h3 class="page-header">Sensor Monitoring</h3> -->
                            <div class="form-group monitoring-form">
                                <form class="form-inline" role="form" style="margin-bottom: 15px;">
                                    <label class="sr-only" for="exampleInputEmail2">Site Name</label>
                                </form>
                            </div>

                            <div class="monitoring-icons" style="float: right; margin-bottom: 25px;">
                                <a href="" id="listviewicon" title="List view"><i class="fa fa-align-justify fa-3x" style="font-size: 20px !important;"></i></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="" id="gridviewicon" title="Grid view"><i class="fa fa-th fa-3x" style="font-size: 20px !important;"></i></a>
                            </div>
                            </br>
                            <div>
                                <div class="table-responsive clear" id="listview">
                                    <table class="table table-striped dataTable" id="table-list-view">
                                        <thead>
                                            <th>Venue Names</th>
                                            <th class="text-center">Actions</th>
                                            <th>Last Reported In</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                            @foreach($data['venues'] as $venue)
                                            <tr>
                                                <td id="venue{{$venue->id}}" idval="{{$venue->id}}" class="sensorlist" style="cursor: pointer">
                                                @if (is_null($venue->friendly_brandname)) 
                                                    {{$venue->sitename}}
                                                @else 
                                                    {{$venue->friendly_brandname.' '.explode(" ", $venue->sitename)[1]}}
                                                @endif
                                                    <i class="fas fa-chevron-circle-down"></i>
                                                    <ol id="sensors{{$venue->id}}"></ol>
                                                </td>
                                                <!-- <td id="status{{$venue->id}}" class="" idval="{{$venue->id}}"> -->

                                                <!-- </td> -->
                                                <td class="text-center">

                                                @if ($venue->sonoff_device_uuid != null)
                                                    <button class="btn sonoff-button" device_auth_token="{{$venue->sonoff_device_auth_token}}" venue_id="{{$venue->id}}" sonoff_status="{{$venue->sonoff_device_action_status}}">{{$venue->sonoff_device_action_status}}<br><small>Click to turn off</small></button>
                                                @endif
                                                
                                                </td>
                                                <td id="venue_last_reported_{{$venue->id}}"></td>
                                                <td class="text-center" id="venue_status_{{$venue->id}}"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                </br>
                                <div id="gridview" style="display: none;">

                                    <div class="row">
                                        <input id="grid-filter" style="width: 150px;margin-bottom: 10px;" placeholder="Site Name" class="form-control"></input>
                                        <br>
                                    </div>
                                    <div class="row" style="margin: 0">
                                        <?php $pos = 0 ?>
                                        @foreach($data['venues'] as $venue)
                                        <div id="grid_venue_status_{{$venue->id}}" class="grid-tile" sitename={{preg_replace('/\s+/', '_', $venue->sitename)}}>
                                            <a style="color: white;" href="#" class="gridlinks" data-toggle="modal" data-target="#modalPopup" index={{$pos}}>
                                                {{$venue->sitename}}
                                            </a>
                                        </div>
                                        <?php $pos++ ?>
                                        @endforeach
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div>

                            <div id="viewVenueModalsTable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .grid-tile {
            width: 150px;
            display: inline-block;
            padding: 8px;
            border: 2px solid black;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            color: black !important;
            margin-left: -5px;
            margin-bottom: -7px;
        }

        .online {
            background-color: green !important;
            color: white;
        }

        .offline {
            background-color: red !important;
            color: white;
        }

        .some-online {
            background-color: yellow !important;
            color: white;
        }

        .dataTables_filter input {
            margin: 0 !important;
        }
    </style>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/prefixfree.min.js"></script>

    <script>
        // $('#table-list-view_filter input').addClass('form-control');
        // $('#table-list-view_filter input').attr("placeholder", "Site Name");


        $('#grid-filter').on('input', function() {
            let textValue = this.value;
            textValue = textValue.replace(/ /g, "_");

            if (textValue == "") {
                $('.grid-tile').removeClass("hidden");
                return;
            }

            $('.grid-tile').addClass("hidden");

            $('.grid-tile').each(function(i, obj) {

                let sitename = $(this).attr('sitename');

                if (sitename.toLowerCase().indexOf(textValue.toLowerCase()) >= 0) {
                    $(this).removeClass("hidden");
                }


            });

        });


        function updateViewDataOnTimer() {
            getSensorDataForAllVenues();

            // getModalSensorInfo
            // getSensorDataForAllVenues

            setTimeout(function() {
                updateViewDataOnTimer();
            }, 15000);
        }
        $('document').ready(function() {
            initializeDatatable();
            $("[id^=sensors]").hide();
            getSensorDataForAllVenues();
            updateViewDataOnTimer();
        });






        $('.gridlinks').on('click', function(e) {
            e.preventDefault();
            let venue = venues[parseInt($(this).attr("index"))];
            $("#modal-sitename").html(`${venue.sitename}`);
            getInfoForGridSensor(venue.id);
        });


        let venues = null;

        function getSensorDataForAllVenues() {
            venues = <?php echo json_encode($data['venues']) ?>;

            venues.forEach(function(item, index) {
                getSensorInfo(item.id);
                getSensorSonoff(item.id);
            });

        }

        function initializeDatatable() {
            $('#table-list-view').DataTable({
                "oLanguage": {
                    "sSearch": ""
                },
                "pageLength": 100
            });
            $('#table-list-view_filter input').addClass('form-control');
            $('#table-list-view_filter input').attr("placeholder", "Site Name");
        }

        $("[id^=sensortable").click(function() {
            id = $(this).attr("id");
            alert(id);
        });

        $("[id^=venue]").click(function() {
            id = $(this).attr("idval");
            //$(this).children().toggle();
            getSensors(id);


        });

        $("[id^=venuegrid]").click(function() {
            id = $(this).attr("idval");
            //$(this).children().toggle();
            getModalSensorInfo(id);


        });
        $(document).on('click', '#closex', function() {
            $('#viewVenueModalsTable').html("");
            $('#viewVenueModalsTable').hide();

        });
        $(document).on('click', '#listviewicon', function(event) {
            event.preventDefault();

            $('#listview').show();
            $('#gridview').hide();

        });

        $(document).on('click', '#gridviewicon', function(event) {
            event.preventDefault();

            $('#listview').hide();
            $('#gridview').show();

        });


        $(document).delegate('#filter', 'click', function(event) {
            event.preventDefault();
            var search = $('#src-sitename').val();
            window.location.replace("http://hiphub.local/index.php/hipjam_monitorsensors?search=" + search);

        });

        function getInfoForGridSensor(venueId) {

            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = venueId;

            $.ajax({
                type: "POST",
                data: "sentData=" + sentData,
                dataType: 'json',
                url: url,
                async: true,

                success: function(data) {

                    let statuses = [];
                    let lastReportedInTimes = [];
                    let displayStatusForRow = "";
                    let displayLastReportedForRow = "";

                    data.forEach(function(item, index) {
                        statuses.push(item.status);
                        lastReportedInTimes.push(item.lastreportedin);
                    });

                    if (statuses.every((val, i, arr) => val === arr[0])) {
                        //All statuses is the same
                        if (statuses[0] == "Offline") {
                            //All statuses = offline
                            displayStatusForRow = "offline";
                        } else {
                            //All statuses = online
                            displayStatusForRow = "online";
                        }
                    } else {
                        //They differ, show yellow status
                        displayStatusForRow = "some_online";
                    }

                    displayLastReportedForRow = lastReportedInTimes[0];
                    lastReportedInTimes.forEach(function(item, index) {
                        if (labelToNumberForComparrison(displayLastReportedForRow) < labelToNumberForComparrison(item)) {
                            displayLastReportedForRow = item
                        }

                    });


                    $("#modal-status").html(`${displayStatusForRow}`);
                    $("#modal-check-in").html(`${displayLastReportedForRow}`);

                },
                error: function() {

                }

            });

        }

        function getSensorInfo(venueId) {
            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = venueId;

            $.ajax({
                type: "POST",
                data: "sentData=" + sentData,
                dataType: 'json',
                url: url,
                async: true,

                success: function(data) {

                    let statuses = [];
                    let lastReportedInTimes = [];
                    let displayStatusForRow = "";
                    let displayLastReportedForRow = "";
                    

                    data.forEach(function(item, index) {
                        statuses.push(item.status);
                        lastReportedInTimes.push(item.lastreportedin);
                    });

                    if (statuses.every((val, i, arr) => val === arr[0])) {
                        //All statuses is the same
                        if (statuses[0] == "Offline") {
                            //All statuses = offline
                            displayStatusForRow = "offline";
                        } else {
                            //All statuses = online
                            displayStatusForRow = "online";
                        }
                    } else {
                        //They differ, show yellow status
                        displayStatusForRow = "some_online";
                    }

                    displayLastReportedForRow = lastReportedInTimes[0];
                    lastReportedInTimes.forEach(function(item, index) {

                        if (labelToNumberForComparrison(displayLastReportedForRow) < labelToNumberForComparrison(item)) {
                            displayLastReportedForRow = item
                        }

                    });

                    setStatusForVenue(venueId, displayStatusForRow);
                    setLastReportedForVenue(venueId, displayLastReportedForRow);
                    //Hannes hier webhook

                },
                error: function() {

                }

            });
        }

        function getSensorSonoff(venueId) {
            var url = '{{ URL::route('hipjam_getvenuesonoff')}}';
            sentData = venueId;

            $.ajax({
                type: "POST",
                data: "sentData=" + sentData,
                dataType: 'json',
                url: url,
                async: true,

                success: function(data) {
                    debugger;
                    updateButtonStatus(venueId, data);
                    updateSonoffButtons();
                },
                error: function() {

                }

            });
        }

        function updateButtonStatus(venueId, data){
            $('.sonoff-button').each(function(){    
                if(parseInt($(this).attr('venue_id')) == parseInt(venueId)){

                    if(data.sonoff_query_time != null){
                        let device_date = new Date(data.sonoff_query_time);
                        let date = new Date();
                        let seconds_diff = (date - device_date) / 1000;
                        
                        if (seconds_diff > 300){
                            $(this).attr('sonoff_status', 'offline');
                        }
                        else {
                            $(this).attr('sonoff_status', data.sonoff_device_action_status);
                        }
                    }
                    else {
                        $(this).attr('sonoff_status', data.sonoff_device_action_status);
                    }
                    
                }
            });
        }

        $(document).on('click', '.sonoff-button', function(){
            var button = $(this);
            var sonoff_status = $(this).attr('sonoff_status');
            if(sonoff_status == 'on'){
                shutDownVenue(button);
            }
            else if(sonoff_status == 'off'){
                startUpVenue(button);
            }
      });

      function shutDownVenue(button){
        var venueId = button.attr('venue_id');
        var deviceAuthToken = button.attr('device_auth_token');

        var url = 'http://hiphub.hipzone.co.za/hipjam/update_sonoff_status?auth_token=001c2fcd-99a5-4bac-8689-3f73d4d46849&device_auth_token=' + deviceAuthToken + '&status=off';
        $.ajax({
              type: "GET",
              dataType: 'json',
              contentType: "application/json",
              url: url,
              success: function(venues) {
                button.removeClass("btn-success");
                button.addClass("btn-warning");
                button.attr('sonoff_status', 'shutting_down');
                button.html('Shutting Down');
              }
            });
      }

      function startUpVenue(button){
        debugger;
        var venueId = button.attr('venue_id');
        var deviceAuthToken = button.attr('device_auth_token');
        var url = 'http://hiphub.hipzone.co.za/hipjam/update_sonoff_status?auth_token=001c2fcd-99a5-4bac-8689-3f73d4d46849&device_auth_token=' + deviceAuthToken + '&status=on';
        $.ajax({
              type: "GET",
              dataType: 'json',
              contentType: "application/json",
              url: url,
              success: function(venues) {
                button.removeClass("btn-danger");
                button.addClass("btn-warning");
                button.attr('sonoff_status', 'starting_up');
                button.html('Starting Up');
              }
            });
      }

        function updateSonoffButtons(){
        $('.sonoff-button').each(function(){

          var sonoff_status = $(this).attr('sonoff_status');
          console.log(sonoff_status);
          
          if(sonoff_status == 'on'){
            //clickable, on, click to turn off
            $(this).removeClass("btn-danger");
            $(this).removeClass("btn-warning");
            $(this).addClass("btn-success");
            $(this).html('ON<br><small>Click to turn off</small>');
          }
          else if (sonoff_status == 'shutting_down'){
            //not clickable, shutting down
            $(this).removeClass("btn-danger");
            $(this).removeClass("btn-success");
            $(this).addClass("btn-warning");
            $(this).html('Shutting Down');
          }
          else if (sonoff_status == 'starting_up'){
            //not clickable, starting up
            $(this).removeClass("btn-danger");
            $(this).removeClass("btn-success");
            $(this).addClass("btn-warning");
            $(this).html('Starting Up');
          }
          else if (sonoff_status == 'off') {
            // clickable, Off, click to turn on
            $(this).removeClass("btn-success");
            $(this).removeClass("btn-warning");
            $(this).addClass("btn-danger");
            $(this).html('Off<br><small>Click to turn on</small>');
          }
          else if (sonoff_status == 'offline') {
            // clickable, Off, click to turn on
            $(this).removeClass("btn-success");
            $(this).removeClass("btn-warning");
            $(this).addClass("btn-danger");
            $(this).html('Offline');
          }
          else {
            $(this).hide();
          }

        });
      };

        function labelToNumberForComparrison(stringToGetNumbersFrom) {

            if (stringToGetNumbersFrom != null) {
                return parseInt(stringToGetNumbersFrom.match(/\d+/));
            } else {
                return 99999999;
            }

        }

        function setStatusForVenue(venueId, status) {

            if (status == "some_online") {
                $(`#venue_status_${venueId}`).html("Varying");
                $(`#grid_venue_status_${venueId}`).addClass("badge badge-warning");
                $(`#venue_status_${venueId}`).addClass("some-online");

            } else if (status == "offline") {
                $(`#venue_status_${venueId}`).html("Offline");
                $(`#venue_status_${venueId}`).addClass("badge badge-danger");
                $(`#grid_venue_status_${venueId}`).addClass("offline");

            } else {
                $(`#venue_status_${venueId}`).html("Online");
                $(`#venue_status_${venueId}`).addClass("badge badge-success");
                $(`#grid_venue_status_${venueId}`).addClass("online");
            }

        }

        function setLastReportedForVenue(venueId, displayLastReportedForRow) {
            $(`#venue_last_reported_${venueId}`).html(displayLastReportedForRow);
        }

        function getModalSensorInfo(id) {

            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = JSON.stringify(id);

            $.ajax({
                type: "POST",
                data: "sentData=" + sentData,
                dataType: 'json',
                url: url,
                async: true,

                success: function(data) {

                    if (data.length == 0) {
                        container = $("There are no Sensors for this venue.");
                        container.append("<span class='closex' id ='closex' >X</span>");
                    } else {
                        container = $("<div class='modal_holder'>");
                        container.append("<table class=" + "table table-striped" + "id=sensortable" + id + ">");
                        container.append("<tr><th>Sensor Name</th><th>Sensor Location</th><th>Status</th><th>Last Reported In</th></tr>")
                        for (i = 0; i < data.length; i++) {
                            if (data[i]["status"] == "Offline") {
                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["location"] === 'billboard' ? 'OOH Site' : 'Venue' + "</td><td class=" + data[i]["status"] + "Bg" + ">" + data[i]["status"] + "</td><td>" + data[i]["lastreportedin"] + "</td></tr>");
                            } else if (data[i]["status"] == "Online") {
                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["location"] === 'billboard' ? 'OOH Site' : 'Venue' + "</td><td  class=" + data[i]["status"] + "Bg" + ">" + data[i]["status"] + "</td><td>" + data[i]["lastreportedin"] + "</td></tr>");
                            } else {
                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["status"] + "</td><td></td></tr>");
                            }

                            //alert(data[i]["name"]);
                        }
                        container.append("</table>");
                        container.append("<span class='closex' id ='closex' >X</span>");
                        container.append("</div>");

                    }
                    $('#viewVenueModalsTable').show();
                    $('#viewVenueModalsTable').html(container);
                },
                error: function() {

                }

            });


        }

        function getSensors(id) {
            $("#sensors" + id).toggle();

            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = JSON.stringify(id);

            $.ajax({
                type: "POST",
                data: "sentData=" + sentData,
                dataType: 'json',
                url: url,
                async: true,

                success: function(data) {
                    if (data.length == 0) {
                        return;
                    } else {
                        let html = '';
                        // container = $("<table class=" + "table table-striped" + "id=sensortable" + id + ">");
                        // container.append("<tr><th>Sensor Name</th><th>Sensor Location</th><th>Status</th><th>Last Reported In</th></tr>")
                        for (i = 0; i < data.length; i++) {
                            html += `
                        <strong>Sensor Location: </strong> <span>${data[i]["location"] === 'billboard' ? 'OOH Site' : 'Venue'}</span> <br />
                        <strong>Status: </strong> <span> <label style="margin-bottom: -2px" class="${data[i]["status"] === 'Offline' ? "badge badge-danger" : "badge badge-success"}">${data[i]["status"]}</label></span> <br />
                        <strong>Last Reported In: </strong> <span>${data[i]["lastreportedin"]}</span> <br />
                    `
                        }
                        // container.append("</table>");
                        $("#sensors" + id).html(html);
                    }
                }

            });

        }

        function getSensorsOnlineStatus(id) {

            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = JSON.stringify(id);
            $.ajax({
                type: "POST",
                data: "sentData=" + sentData,
                dataType: 'json',
                url: url,
                async: true,

                success: function(data) {

                    if (data.length == 0) {
                        return;
                    } else {

                        for (i = 0; i < data.length; i++) {
                            if (data[i]["status"] == "Offline") {
                                return false;
                            }

                        }
                    }
                    return true;
                }


            });

        }


        $('#listviewicon').click();
    </script>

    <script>
        

        
    </script>

    @stop

    @section('modals')
    <div id="modalPopup" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-sitename">' + sitename + '</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Status Comment : <b>
                        <div id="modal-status" style="display: inline"></div>
                    </b>
                    <br>
                    Last Check in : <div id="modal-check-in" style="display: inline"></div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @stop