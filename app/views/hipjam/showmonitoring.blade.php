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
                                <td>test</td>
                                <td>test</td>
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
                                        {{$venue->sitename}}
                                    </div>
                                @else
                                <div class="grid-tile" style="background-color: red;" sitename={{preg_replace('/\s+/', '_', $venue->sitename)}}>
                                        {{$venue->sitename}}
                                    </div>
                                @endif
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
            debugger;
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

        function getSensorDataForAllVenues() {
            let venues = <?php echo json_encode($data['venues']) ?>;
            debugger;

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
            table-list-view_filter
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