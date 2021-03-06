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
              <a href="" id="gridviewicon" title="Grid view"><i class="fa fa-th fa-3x"></i></a>
            </div>

			<div>
                <div class="table-responsive clear" id="listview">
                    <table class="table table-striped">
                        <thead>
                            <th>Venue Names</th>
                            <th>Status</th>
                            
                        </thead>
                        <tbody>
                            @foreach($data['venues'] as $venue)
                            <tr>
                                <td id="venue{{$venue->id}}" idval="{{$venue->id}}" class="sensorlist">{{$venue->sitename}} 
                                    <ol id="sensors{{$venue->id}}"></ol>
                                </td>
                                <td id="status{{$venue->id}}" class="venuelist-{{$data['status'][$venue->id]}}" idval="{{$venue->id}}">
                                    {{$data['status'][$venue->id]}}
                                </td>
                                                                        
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive clear" id="gridview">
                        @foreach($data['venues'] as $venue)
                            <div class="venuegrid-{{$data['status'][$venue->id]}}">
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
    <script>
    	$('document').ready(function(){
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
    	

    
    	
    </script>
</body>

@stop