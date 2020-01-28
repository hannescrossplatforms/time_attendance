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
                    <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
                    <button id="filter" type="submit" class="btn btn-primary">Filter</button>
                    <button id="reset" type="submit" class="btn btn-default">Reset</button>
                </form>
            </div>

            <div class="monitoring-icons">
              <a href="" id="listviewicon" title="List view"><i class="fa fa-align-justify fa-3x"></i></a>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <!-- <a href="" id="gridviewicon" title="Grid view"><i class="fa fa-th fa-3x"></i></a> -->
            </div>

			<div>
                <div class="table-responsive clear" id="listview">
                    <table class="display" id="table-list-view">
                        <thead>
                            <th>Venue Names</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </thead>
                        <tbody>
                            @foreach($data['venues'] as $venue)
                            <tr>
                                <td id="venue{{$venue->id}}" idval="{{$venue->id}}" class="sensorlist">{{$venue->sitename}} 
                                    <ol id="sensors{{$venue->id}}"></ol>
                                </td>
                                <td id="status{{$venue->id}}" class="" idval="{{$venue->id}}">
                                    
                                </td>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive clear" id="gridview">
                        @foreach($data['venues'] as $venue)
                            <div class="venuegrid-{{$venue->id}}">
                                    <span id="venuegrid{{$venue->id}}" idval="{{$venue->id}}">{{$venue->sitename}}</span>
                                    <ol id="sensors{{$venue->id}}"></ol>
                            </div>
                        @endforeach
                        <br class="clearBoth" />
                </div>
		    </div>	
		</div>
		<div>
            
                <div id="viewVenueModalsTable"></div>
        </div>
	</div>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" type="text/css"> -->
    <!-- <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <script>

    	$('document').ready(function(){
            $('#table-list-view').DataTable();
    		$("[id^=sensors]").hide();
		
    	});

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