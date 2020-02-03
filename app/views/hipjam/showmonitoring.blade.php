@extends('layout')

@section('content')

<body class="hipJAM">
    <a id="buildtable"></a>

@include('hipjam.sidebar')

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
			<h3 class="page-header">Sensor Monitoring</h3>
            <div class="form-group monitoring-form">
                <form class="form-inline" role="form" style="margin-bottom: 15px;">
                    <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                </form>
            </div>
            
            <div class="monitoring-icons">
              <a href="" id="listviewicon" title="List view"><i class="fa fa-align-justify fa-3x"></i></a>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <a href="" id="gridviewicon" title="Grid view"><i class="fa fa-th fa-3x"></i></a>
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
                                <td id="venue{{$venue->id}}" idval="{{$venue->id}}" class="sensorlist">{{$venue->sitename}} 
                                    <ol id="sensors{{$venue->id}}"></ol>
                                <!-- </td> -->
                                <!-- <td id="status{{$venue->id}}" class="" idval="{{$venue->id}}"> -->
                                    
                                <!-- </td> -->
                                <td class="text-center">
                                    @if ($venue->id == 1476)
                                        @if ($venue->status == 'Online')
                                            <button class="btn btn-success turn-off-sensor" data-venue-id="{{$venue->id}}">ON <br/> <small>Click to turn off</small></button>
                                        @else
                                            <button class="btn btn-danger turn-on-sensor" data-venue-id="{{$venue->id}}">OFF <br/> <small>Click to turn on</small></button>
                                        @endif
                                    @else
                                        <label class="label label-warning">N/A</label>
                                    @endif
                                </td>
                                <td id="venue_last_reported_{{$venue->id}}"></td>
                                <td id="venue_status_{{$venue->id}}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                </br>
                <div id="gridview">

                    <div class="row">
                    <input id="grid-filter" style="width: 150px;" placeholder="Site Name" class="form-control"></input>
                    <br>
                    </div>
                    <div class="row">
                        @foreach($data['venues'] as $venue)
                                @if ($venue->status == "Online")
                                <div class="grid-tile" style="background-color: green;" sitename={{preg_replace('/\s+/', '_', $venue->sitename)}}>


                                <!-- '<a href="#" class="gridlinks" data-toggle="modal" data-target="#modal_' + modal_id + '" title="' + index + '">'
                                  + venue_name + 
                                '</a>\n\ -->
                                        <a href="#" class="gridlinks" data-toggle="modal" data-target="#exampleModal">
                                            {{$venue->sitename}}
                                        </a>
                                    </div>
                                @else
                                    <div class="grid-tile" style="background-color: red;" sitename={{preg_replace('/\s+/', '_', $venue->sitename)}}>
                                        <a href="#" class="gridlinks" data-toggle="modal" data-target="#exampleModal">
                                            {{$venue->sitename}}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                    </div>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

                </div>
                
		    </div>	
		</div>
		<div>
            
                <div id="viewVenueModalsTable"></div>
        </div>
	</div>
</div>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div> -->
<!-- <div>
    <div class="modal fade" id="modal_test" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h6 class="modal-title" id="myModalLabel">' + sitename + '</h6>
                </div>
                <div class="modal-body">
                    Status Comment : <b>Test1
                    </b>
                    <br>
                    <br> 
                    Today MB (Up/Down) : Test2 
                    <br> 
                    Gateway IP : Test3
                    <br> 
                    Last Check in : Test4 
                    <br> 
                </div>
            </div>
        </div>
    </div>
</div> -->
<style>
    .grid-tile{
        width: 150px;
        display: inline-block;
        padding:8px;
        border: 2px solid black;
        text-overflow:ellipsis;
        white-space: nowrap;
        overflow: hidden;
        color: black;
        margin-left: -5px;
        margin-bottom: -7px;
    }
    .online{
        background-color:green!important;
        color:white;
    }
    .offline{
        background-color:red!important;
        color:white;
    }
    .some-online{
        background-color:yellow!important;
        color:white;
    }
</style>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <script>

    $('#grid-filter').on('input', function() {
        let textValue = this.value;
        textValue=textValue.replace(/ /g,"_");

        if (textValue == "") {
            $('.grid-tile').removeClass("hidden");
            return;
        }

        $('.grid-tile').addClass("hidden");

        $('.grid-tile').each(function(i, obj) {

            let sitename = $(this).attr('sitename');
            
            if (sitename.toLowerCase().indexOf(textValue.toLowerCase()) >= 0){
                $(this).removeClass("hidden");
            }


        });
        
    });

    	$('document').ready(function(){
            initializeDatatable();
            $("[id^=sensors]").hide();
            getSensorDataForAllVenues();
        });

        // $('#gridlinks').on('click', function(e){
        //     e.preventDefault();
        //     // createVenueModal();
        // });

        // function createVenueModal(id, sitename, venuedata) {


            
        //     //Hannes hier
        //     modalhtml = 
        //     '<div class="modal fade" id="modal_' + id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\n\
        //         <div class="modal-dialog">\n\
        //         <div class="modal-content">\n\
        //             <div class="modal-header">\n\
        //             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\n\
        //             <h6 class="modal-title" id="myModalLabel">' + sitename + '</h6>\n\
        //             </div>\n\
        //             <div class="modal-body">\n\
        //             Status Comment : <b>' + venuedata["statuscomment"]  + '</b> <br> <br> \n\
        //             Today MB (Up/Down) : ' + venuedata["bytes"]  + ' <br> \n\
        //             Gateway IP : ' + venuedata["gateway"]  + ' <br> \n\
        //             Last Check in : ' + venuedata["lastcheckin"]  + ' <br> \n\
        //         </div>\n\
        //         </div>\n\
        //     </div>\n\
        //     </div>';

        //     return modalhtml;

        //     $( '#viewVenueModals' ).html(modalshtml);
        //     }
            
        function getSensorDataForAllVenues() {
            let venues = <?php echo json_encode($data['venues']) ?>;
            
            venues.forEach(function(item, index){
                getSensorInfo(item.id);
            });

        }
        
        function initializeDatatable(){
            $('#table-list-view').DataTable({
                "oLanguage": {
                    "sSearch": ""
                },
                "pageLength": 100
            });
            $('#table-list-view_filter input').addClass('form-control');
            $('#table-list-view_filter input').attr("placeholder", "Site Name");
        }

        $("[id^=sensortable").click( function(){
            id = $(this).attr("id");
            alert (id);
        });

        $("[id^=venue]").click( function(){
             id = $(this).attr("idval");
             //$(this).children().toggle();
             getSensors(id);
             
            
        });

        $("[id^=venuegrid]").click( function(){
            id = $(this).attr("idval");
            //$(this).children().toggle();
            getModalSensorInfo(id);
            
           
       });
       $(document).on('click','#closex',  function(){
            $( '#viewVenueModalsTable' ).html("");
            $( '#viewVenueModalsTable' ).hide();
        
        });
        $( document ).on( 'click', '#listviewicon', function (event) {
          event.preventDefault();

          $('#listview').show();
          $('#gridview').hide();

        });

        $( document ).on( 'click', '#gridviewicon', function (event) {
          event.preventDefault();

          $('#listview').hide();
          $('#gridview').show();

        });


        $(document).delegate('#filter', 'click', function(event) {
          event.preventDefault();
          var search = $('#src-sitename').val();
          window.location.replace("http://hiphub.local/index.php/hipjam_monitorsensors?search=" + search);

        });

        function getSensorInfo(venueId){
            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = venueId;
        
            $.ajax({
                type: "POST",
                data: "sentData="+sentData,
                dataType: 'json',
                url: url,
                async:false,

                success: function(data){

                    let statuses = [];
                    let lastReportedInTimes = [];
                    let displayStatusForRow = "";
                    let displayLastReportedForRow = "";

                    data.forEach(function(item, index){
                        statuses.push(item.status);
                        lastReportedInTimes.push(item.lastreportedin);
                    });

                    if (statuses.every( (val, i, arr) => val === arr[0])) {
                        //All statuses is the same
                        if(statuses[0] == "Offline") {
                            //All statuses = offline
                            displayStatusForRow = "offline";
                        }
                        else {
                            //All statuses = online
                            displayStatusForRow = "online";
                        }
                    }
                    else {
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
                
                },
                error: function(){
                    
                }
                
            });
        }

        function labelToNumberForComparrison(stringToGetNumbersFrom){
            return parseInt(stringToGetNumbersFrom.match(/\d+/));
        }
        
        function setStatusForVenue(venueId, status) {
        
            if (status == "some_online"){
                $(`#venue_status_${venueId}`).html("Varying");
                $(`#venue_status_${venueId}`).addClass("some-online");
                
            }
            else if (status == "offline"){
                $(`#venue_status_${venueId}`).html("Offline");
                $(`#venue_status_${venueId}`).addClass("offline");
                
            }
            else {
                $(`#venue_status_${venueId}`).html("Online");
                $(`#venue_status_${venueId}`).addClass("online");
            }

        }

        function setLastReportedForVenue(venueId, displayLastReportedForRow) {
            $(`#venue_last_reported_${venueId}`).html(displayLastReportedForRow);
        }

        function getModalSensorInfo(id){
            
            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = JSON.stringify(id);
        
            $.ajax({
                type: "POST",
                data: "sentData="+sentData,
                dataType: 'json',
                url: url,
                async:false,

                success: function(data){
                    if(data.length == 0)  {
                        container = $("There are no Sensors for this venue.");
                        container.append("<span class='closex' id ='closex' >X</span>");
                    }
                    else{
                        container = $("<div class='modal_holder'>");
                        container .append("<table class="+"table table-striped" + "id=sensortable" + id + ">");
                        container.append("<tr><th>Sensor Name</th><th>Sensor Location</th><th>Status</th><th>Last Reported In</th></tr>") 
                            for (i=0;i<data.length;i++){
                            if(data[i]["status"] == "Offline"){
                                    container.append("<tr><td>"+ data[i]["name"] + "</td><td>" + data[i]["location"] + "</td><td class="+ data[i]["status"] + "Bg" +">"+ data[i]["status"] +"</td><td>"+ data[i]["lastreportedin"] + "</td></tr>");
                            }
                            else if (data[i]["status"] == "Online"){
                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["location"] + "</td><td  class=" + data[i]["status"]+"Bg" +">" + data[i]["status"] +"</td><td>"+ data[i]["lastreportedin"] + "</td></tr>");  
                            }
                            else {
                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["status"] +"</td><td></td></tr>");
                            }
                            
                            //alert(data[i]["name"]);
                            }
                        container.append("</table>");
                        container.append("<span class='closex' id ='closex' >X</span>");
                        container.append("</div>");
                        
                    }
                    $( '#viewVenueModalsTable' ).show();
                    $( '#viewVenueModalsTable' ).html(container);
                },
                error: function(){
                    
                }
                
            });
    
            
        }
        function getSensors(id){
            $("#sensors" + id).toggle();
           
            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = JSON.stringify(id);
        
            $.ajax({
                type: "POST",
                data: "sentData="+sentData,
                dataType: 'json',
                url: url,
                async:false,

                success: function(data){
                                            
                                            if(data.length == 0)  {
                                                return;
                                            }
                                            else{
                                                        container = $("<table class="+"table table-striped" + "id=sensortable" + id + ">");
                                                        container.append("<tr><th>Sensor Name</th><th>Sensor Location</th><th>Status</th><th>Last Reported In</th></tr>") 
                                                         for (i=0;i<data.length;i++){
                                                            if(data[i]["status"] == "Offline"){
                                                                 container.append("<tr><td>"+ data[i]["name"] + "</td><td>" + data[i]["location"] + "</td><td class="+ data[i]["status"] + "Bg" +">"+ data[i]["status"] +"</td><td>"+ data[i]["lastreportedin"] + "</td></tr>");
                                                            }
                                                            else if (data[i]["status"] == "Online"){
                                                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["location"] + "</td><td  class=" + data[i]["status"]+"Bg" +">" + data[i]["status"] +"</td><td>"+ data[i]["lastreportedin"] + "</td></tr>");  
                                                            }
                                                            else {
                                                                container.append("<tr><td>" + data[i]["name"] + "</td><td>" + data[i]["status"] +"</td><td></td></tr>");
                                                            }
                                                            
                                                            //alert(data[i]["name"]);
                                                            }
                                                        container.append("</table>");
                                                        $("#sensors" + id).html(container);
                                                }
                }

            });

        }

        function getSensorsOnlineStatus(id){
           
            var url = '{{ URL::route('hipjam_getvenuesensors')}}';
            sentData = JSON.stringify(id);
            $.ajax({
                type: "POST",
                data: "sentData="+sentData,
                dataType: 'json',
                url: url,
                async:false,

                success: function(data){
                                            
                    if(data.length == 0)  {
                        return;
                    }
                    else{

                        for (i=0;i<data.length;i++){
                            if(data[i]["status"] == "Offline"){
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
        $(document).on('click', '.turn-off-sensor', function() {
            let venue_id = $(this).data('venue-id');
            $.get(`http://hiphub.hipzone.co.za/hipjam_monitorsensors/${venue_id}/turn_off`, function(resp) {
                $.post(`https://maker.ifttt.com/trigger/turn_off_greenside/with/key/bAelf-3oTw4zsZBRxZzvrIL9ILmVSuIkdy_s9Gx4co8`, function(res) {
                    
                });
                window.location.href = 'http://hiphub.hipzone.co.za/hipjam_monitorsensors';
            });
        })

        $(document).on('click', '.turn-on-sensor', function() {
            let venue_id = $(this).data('venue-id');
            $.get(`http://hiphub.hipzone.co.za/hipjam_monitorsensors/${venue_id}/turn_on`, function(resp) {
                $.post(`https://maker.ifttt.com/trigger/turn_on_greenside/with/key/bAelf-3oTw4zsZBRxZzvrIL9ILmVSuIkdy_s9Gx4co8`, function(res) {
                    
                })
                window.location.href = 'http://hiphub.hipzone.co.za/hipjam_monitorsensors';
            });
        })
    </script>
</body>

@stop