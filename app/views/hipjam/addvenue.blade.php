@extends('layout')

<link href="{{ asset('css/modal_style.css')}}" rel="stylesheet" media="screen" />
<style type="text/css">
  /*.topmargin {
    margin-top: 30px;
  }*/
</style>
<?php $edit = $data["edit"] ; 
    if (!$edit) {$editval = 0; } else{$editval = 1; }
    if($data["is_activation"]) { $is_activation = 1; } else { $is_activation = 0; };
?>
<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id ?>

@section('content')

  <body class="hipJAM">
    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">@if ($is_activation) Activate @else Edit @endif  Track Venue - {{$data['venue']->sitename}}</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>
                  @endforeach
              </div>
            @endif

    <div class="container-fluid">
            
            <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
              @if ($data['user'] != 'superadmin') 
                <li role="presentation" id="servertab" class="active"><a id="absencetab" href="#venue" aria-controls="absence" role="tab" data-toggle="tab">Sensors</a></li>
                <li role="presentation" id="heatmaptab"><a id="wsproximitytab" href="#heatmap" aria-controls="wsproximity" role="tab" data-toggle="tab">
                Heatmap
                </a></li>
              @else 
                <li role="presentation" id="generaltab" class="active"><a id="generaltab" href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
                <li role="presentation" id="servertab"><a id="absencetab" href="#venue" aria-controls="absence" role="tab" data-toggle="tab">Sensors</a></li>
                <li role="presentation" id="heatmaptab"><a id="wsproximitytab" href="#heatmap" aria-controls="wsproximity" role="tab" data-toggle="tab">
                Heatmap
                </a></li>
              @endif
              </ul>
              <br>
      <!-- Tab panes -->
      <div class="tab-content">
      <div role="tabpanel" class="tab-pane <?php if($data['user'] == 'superadmin'){echo ' active'; } ?>" id="general">
        <div class="row">
          <!-- <form role="form" id="servertrack-form" method="post"
        action="{{ url('hipjam_editvenueserver'); }}"> -->
                    {{ Form::hidden('id', $data['venue']->id) }}
                      <div class="form-group">
                        <label for="track_slug">Track Venue Id</label>
                        <input  id="track_slug" type="text" class="form-control" name="track_slug" placeholder="" value="{{$data['venue']->track_slug}}"
                                required>
                      </div>

                      <div class="form-group">
                        <label for="track_server_location">Track API Server Name</label>
                        <input  id="track_server_location" type="text" class="form-control" name="track_server_location" placeholder=""
                                value="{{$data['venue']->track_server_location}}"
                                required>
                      </div>

                      <div class="form-group">
                        <label for="track_ssid">Track SSID</label>
                        <input  id="track_ssid" type="text" class="form-control" name="track_ssid" placeholder=""
                                value="{{$data['venue']->track_ssid}}"
                                required>
                      </div>

                      <div class="form-group">
                        <label for="track_password">Track Password</label>
                        <input  id="track_password" type="text" class="form-control" name="track_password" placeholder=""
                                value="{{$data['venue']->track_password}}"
                                required>
                      </div>
                      <div class="form-group">
                  <label for="billboard_retail">Billboard / Retail</label>
                  <select class="form-control" name="track_type" id="track_type" data-default-selected="{{$data['venue']->track_type}}">
                    <option value="venue">Venue</option>
                    <option value="billboard">Billboard</option>
                  </select>
                </div>
                        <div class="form-group" id="linked_billboard_container" style="display: none;">
                          <label for="track_linked_billboard">Linked Billboard</label>
                          <select class="form-control" name="linked_billboard" id="track_linked_billboard" value="{{$data['venue']->linked_billboard}}">
                          <option>Unlinked</option>
                          <?php foreach ($data['billboards'] as $billboard) { ?>
                                        <option value="{{$billboard->id}}">{{$billboard->sitename}}</option>
                                    <?php  }
                                    ?>
                          </select>
                          <!-- <input id="linked_billboard" type="text" class="form-control" name="track_ssid" placeholder="" value="{{$data['venue']->track_ssid}}" required> -->
                        </div>
                    

                      <div class="form-group">
                        <label for="track_password">Time Zone</label>
                        <select name="timezone" id="timezone_select" class="form-control" autocomplete="off">
                        {{ $data['timezoneselect'] }}
                        </select>
                      </div>


                  <br>
              <button id="submitform_gen" class="btn btn-primary"> @if ($is_activation) Activate @else Submit @endif </button>
              @if ($edit)
              <a href="{{ url('hipjam_showvenues'); }}" class="btn btn-primary pull-right">Done</a>
              @endif
          <!-- </form> -->
        </div>            
      </div>
      <div role="tabpanel" class="tab-pane <?php if($data['user'] != 'superadmin'){echo ' active'; } ?>" id="venue">
      <div class="ajaxerrors">
        <ul>
            <li>Errors</li>
        </ul>
      </div>
        <div class="row">
                      <div class="form-group">
                        <label>Sensors</label>
                        <br>
                      <div class="table-responsive">  
                        <table class="tnastafftable" id="server_scanners">
                        <tr>
                          <td>
                            <input id="track_name" class="form-control no-radius" type="text" autocomplete="off" placeholder="Name" >
                          </td>
                          <td>
                            <input id="track_location" class="form-control no-radius" type="text" autocomplete="off" placeholder="Location">
                          </td>
                          <td class="sensor_mac"> 
                            <input id="sensor_mac" name="sensor_mac" class="form-control no-radius"  placeholder="Mac Address" type="text"> 
                          </td>
                          <td>
                            <input id="track_queue" class="form-control no-radius" type="text" autocomplete="off" placeholder="Queue" >
                          </td>
                          <td>
                            <input id="track_min_power" class="form-control no-radius" type="text" autocomplete="off" placeholder="Min Power" value="-80">
                          </td>
                          <td>
                            <input id="track_max_power" class="form-control no-radius" type="text" autocomplete="off" placeholder="Max Power" value="-10">
                          </td>
                          <td>
                          <a id="addscanner" class="btn btn-primary no-radius" href="javascript:void(0);" ><i class="fa fa-plus"></i> Add Sensor</a> 
                          </td>
                          </tr>
                          <tr></tr>

                          <?php if(!empty($data['sensors'])){ foreach ($data['sensors'] as $sensor)  { ?>
                                      <tr id="row{{$sensor->id}}"><td class="sensor_name"> 
                                          <input id="track_name{{$sensor->id}}" name="track_name" class="form-control no-radius" placeholder="Name" value="{{$sensor->name}}"  type="text"> 
                                        </td>
                                        <td class="track_location"> 
                                          <input id="track_location{{$sensor->id}}" name="track_location" class="form-control no-radius" value="{{$sensor->location}}" placeholder="Location" type="text"> 
                                        </td>
                                        <td class="sensor_mac"> 
                                          <input id="sensor_mac{{$sensor->id}}" name="sensor_mac" class="form-control no-radius" value="{{$sensor->mac}}" placeholder="Mac Address" type="text"> 
                                        </td> 
                                        <td class="track_queue"> 
                                          <input id="track_queue{{$sensor->id}}" name="track_queue" class="form-control no-radius" placeholder="Queue" value="{{$sensor->queue}}" type="text"> 
                                        </td>
                                        <td class="track_min_power"> 
                                          <input id="track_min_power{{$sensor->id}}" name="track_min_power" class="form-control no-radius" value="{{$sensor->min_power}}" placeholder="Min Power" type="text" > 
                                        </td>
                                        <td class="track_max_power"> 
                                          <input id="track_max_power{{$sensor->id}}" name="track_max_power" value="{{$sensor->max_power}}" class="form-control no-radius" placeholder="Max Power" type="text" > 
                                        </td>
                                        @if ($sensor->vpnip) 
                                        <td class="sensor_vpnip"> 
                                          <input id="sensor_vpnip" name="vpnip" value="{{$sensor->vpnip->ip_address}}" class="form-control no-radius" placeholder="vpnip" type="text" readonly="readonly"> 
                                        </td>
                                        @endif
                                        <td width="17%">
                                          <a onclick="updateServerRow({{$sensor->id}});" class="btn btn-primary no-radius btn-delete btn-sm">Update</a>
                                          <a href="javascript:void(0);" onclick="removeServerRow({{$sensor->id}});" class="btn btn-primary no-radius btn-delete btn-sm" >Delete</a>
                                        </td>
                                      </tr>  
                              <?php  }
                              } ?>

                          </table>
                      </div>    
                      </div>

            <br>
            @if ($edit)
            <a href="{{ url('hipjam_showvenues'); }}" class="btn btn-primary pull-right">Done</a>
            @endif
            
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="heatmap">
      <div class="clear">
      <div class="row">

      <form role="form" id="fpimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savefpmedia'); }}"></form>
      <form role="form" id="useradmin-form" method="post"
        action=" @if ($is_activation) {{ url('hipjam_activatevenue'); }} @else {{ url('hipjam_editvenue'); }} @endif ">


            <h1 class="page-header">Add Venue</h1>
          <form>
              <div class="form-group">
                  <label>Choose Brand</label>
                    <select class="form-control">
                      <option>Brand Name</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                </div>
                <div class="form-group">
                  <label>Venue Name/number</label>
                    <input class="form-control" placeholder="" type="text">
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">Venue Location</div>
                  <div class="panel-body">
                      <div class="form-group">
                            <label>Country</label>
                            <select class="form-control">
                              <option>South Africa</option>
                              <option>2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Province/State</label>
                            <select class="form-control">
                              <option>Western Cape</option>
                           <option>2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <select class="form-control">
                              <option>Cape Town</option>
                              <option>2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Area/Town</label>
                            <select class="form-control">
                              <option>Gardens</option>
                              <option>2</option>
                            </select>
                        </div>
                        <div class="form-group">
                  <label>Timezone</label>
                    <select class="form-control">
                              <option>(GMT +2) South Africa</option>
                              <option>2</option>
                    </select>
                </div>
                <div class="form-group">
                  <label>Location Code</label>
                    <input class="form-control" placeholder="eg MOTPNPLIQCANALWLKWWCPTXWCZA" type="text">
                </div>
                  </div>
                </div>
                
                <div class="panel panel-default">
                  <div class="panel-heading">Opening Hours</div>
                  <div class="panel-body">
                    <div class="form-group">
                  <label>From</label>
                    <input class="form-control" placeholder="" type="text">
                  </div>
                    <div class="form-group">
                  <label>To</label>
                    <input class="form-control" placeholder="" type="text">
                  </div>
                  </div>



<input type="hidden" name="x_cordi" id="x_cordi" value="0">
<input type="hidden" name="y_cordi" id="x_cordi" value="0">
                </div>
            </div>
        </div>
      </form>

      </div>
      </div>
      </div>

    </div>

    </div>
  </div>

<div id="fpfunction"></div>

  <!-- Trigger the Modal -->
<img id="myImg" src="{{$data['previewurl']}}/{{$data['venue']->location}}.jpg" style="display: none; " >

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>

  <a id="test">
    <!-- Modal Content (The Image) -->
    <canvas class="modal-content" id="img01" style="align-content: center; cursor:crosshair;)"></canvas>
    <!-- <img class="modal-content" id="img01"> -->

  </a>
  <!-- <a style="align:center;align-content: center;"> 

  </a> -->


  <!-- Modal Caption (Image Text) -->
  <div id="caption">
      <button id="donebtn" class="btn btn-primary" >done</button>
  </div>
</div>

<div id="previewModal" class="modal">

  <!-- The Close Button -->
  <span class="close" onclick="document.getElementById('previewModal').style.display='none'">&times;</span>

  <a id="test">
    <!-- Modal Content (The Image) -->
    <canvas class="modal-content" id="imgpreview" style="align-content: center; cursor:crosshair;)"></canvas>
    <!-- <img class="modal-content" id="img01"> -->

  </a>

</div>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{asset('js/jquery.form.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/prefixfree.min.js')}}"></script>
    <script src="{{asset('js/colpick.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('js/jquery.fancybox.pack.js?v=2.1.5')}}"></script>
    <script src="{{asset('js/jquery.timepicker.min.js')}}"></script>

    <script>

      // Get the modal
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
function setxyImage(id){
    var imge = document.getElementById('setxy');
    var imgsrc = document.getElementById('myImg');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    //imge.onclick = function(){
    modal.style.display = "block";
    //modalImg.src = imgsrc.src;
    //var img = new Image();
    var c = document.getElementById("img01");
    var ctx = c.getContext("2d");
    var img = document.getElementById("myImg");
    c.width=img.width;
    c.height=img.height;
    ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);
    //captionText.innerHTML = imgsrc.alt;
    if(id != 0){
      var x = $("#x_cordinate"+id).val();
      var y = $("#y_cordinate"+id).val();
      drawCoordinates(x,y);
    }

    $('#setxy').attr("rownum",id);
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}


$(function() {
/*$("#img01").click(function(e) {
  var offset = $(this).offset();
  var relativeX = (e.pageX - offset.left);
  var relativeY = (e.pageY - offset.top);
  alert(relativeX+':'+relativeY);
  drawCoordinates(relativeX,relativeY)
  $(".position").val("afaf");
});*/

$("#img01").click(function(event){
     removeCoordinates();

     var rect = img01.getBoundingClientRect();
     //console.log(rect);
     var x = event.clientX - rect.left;
     var y = event.clientY - rect.top;

     rownum = $('#setxy').attr('rownum');
        $('#x_cordi').val(x); 
        $('#y_cordi').val(y);
      if(rownum != 0) {
       $('#x_cordinate'+rownum).val(x); 
       $('#y_cordinate'+rownum).val(y);
      } else {
        $('#x_cordinate').val(x); 
        $('#y_cordinate').val(y);
      }
     drawCoordinates(x,y);
     //setTimeout(function(){ modal.style.display = "none"; }, 1000);
     
});

$("#donebtn").click(function(event){
    modal.style.display = "none"; 
});


});


function drawCoordinates(x,y){
    var pointSize = 3; // Change according to the size of the point.
    var ctx = document.getElementById("img01").getContext("2d");

    /*var c = document.getElementById("img01");
    ctx.clearRect(0,0,c.width,c.height);*/
    ctx.fillStyle = "#ff2626"; // Red color

    ctx.beginPath(); //Start path
    ctx.arc(x, y, pointSize, 0, Math.PI * 2, true); // Draw a point using the arc function of the canvas with a point structure.
    ctx.fill(); // Close the path and fill.
}
function removeCoordinates(){
    var ctx = document.getElementById("img01").getContext("2d");

    var c = document.getElementById("img01");
    ctx.clearRect(0,0,c.width,c.height);
    setxyImage($('#id').val());
}

function drawCoordinatespreview(x,y){
    var pointSize = 3; // Change according to the size of the point.
    var ctx = document.getElementById("imgpreview").getContext("2d");

    ctx.fillStyle = "#ff2626"; // Red color

    ctx.beginPath(); //Start path
    ctx.arc(x, y, pointSize, 0, Math.PI * 2, true); // Draw a point using the arc function of the canvas with a point structure.
    ctx.fill(); // Close the path and fill.
}



      $(document).ready(function() {
        //$("a#dtpreview").fancybox();
        $('.ajaxerrors').hide();

        // if("{{$data['venue']->track_slug}}" == "" || "{{$data['venue']->track_server_location}}" == ""){
        @if ($is_activation)  
          $("#servertab").addClass("disabled").find("a").attr("href", "");
          $("#heatmaptab").addClass("disabled").find("a").attr("href", "");
        @endif
        // }
      });

      $('#dtpreview').click(function(){

          var url = '{{ URL::route('hipjam_previewsensors')}}';
          $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: "venue_id={{$data['venue']->id}}",
                success:  function(objResult){
                  console.log(objResult); 
                    
                    $('#previewModal').show();
                    var c = document.getElementById("imgpreview");
                    var ctx = c.getContext("2d");
                    var img = document.getElementById("myImg");
                    c.width=img.width;
                    c.height=img.height;
                    ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

                    $.each(objResult, function(arrayID,group) {
                        drawCoordinatespreview(group.xcoord,group.ycoord);
                    });

                }
            }); 
      });

      $('#fp-file').click(function(){
          fpimage.click();
      }).show();

      $(function() {
        $('#countrielist').change(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $('#brandlist').change();
        @if ($edit) $('#fpfunction').click(); @endif

        buildServerList();
      });

      $('body').delegate('#fpfunction','click', function(){
        showFloorImage('jpg');
        // $( "#mb_ext" ).html( "<input type='hidden' name='mb_ext' value='" + mb_ext + "'/>" );
      });

      $(document).delegate('#countrielist', 'change', function() {
        buildProvinceList();
      });

      $(document).delegate('#provincelist', 'change', function() {
        buildCityList();
      });

      $(document).delegate('#citielist', 'change', function() {
        buildLocationCode();
      });

      $(document).delegate('#brandlist', 'change', function() {
        buildLocationCode();
        buildServerList();
      });

      $(document).delegate('#sitename', 'focusout', function() {
        buildLocationCode();
      });

      $(document).delegate('#macaddress', 'focusout', function() {
        buildLocationCode();
      });

      $('#submitform').click(function() {

        returnval = true;
        message = "";
        
      });

      $( "#submitform_gen" ).click(function(event) {


          event.preventDefault();
          var newrecord = {};
          newrecord['track_slug'] = $('#track_slug').val(); 
          newrecord['track_server_location'] = $('#track_server_location').val();
          newrecord['track_ssid'] = $('#track_ssid').val();
          newrecord['track_password'] = $('#track_password').val();
          newrecord['timezone'] = $('#timezone_select').val();
          newrecord['id'] = "{{$data['venue']->id}}";
          newrecord['venue_location'] = "{{$data['venue']->location}}";

          var url = '{{ URL::route('hipjam_editvenueserver')}}';
          //console.log(newrecord);

          var dataJson = JSON.stringify(newrecord);

          
          if(!newrecord['track_slug']) {
            alert("Enter Venue Id");
            return false; 

          } else if(!newrecord['track_server_location']) {
            alert("Enter Server Location");
            return false; 

          } else if(!newrecord['track_ssid']) {
            alert("Enter Track SSID");
            return false; 

          } else if(!newrecord['track_password']) {
            alert("Enter Track Password");
            return false; 

          } else {

            console.log("here 1");

            $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: url,
                data: "newrecord="+dataJson,
                //async: false,
                success:  function(objResult){
                  console.log(objResult); 

                  showvenuespage = '{{ URL::route('hipjam_showvenues')}}';
                  window.location.href = showvenuespage;
                  
                  // $("#servertab").removeClass("disabled").find("a").attr("href", "#venue");
                  // $("#heatmaptab").removeClass("disabled").find("a").attr("href", "#heatmap");
                }, 
                error:function (jqXHR, status) {
                  alert("Invalid Track Server");
                }
            }); 


          }

        });

      $('body').delegate('#fpimage','change', function(){
        var val = $(this).val();

        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
            case 'jpg': 
                //alert("an image");
                break;
            default:
                $(this).val('');
                // error message here
                $('#fpmsg').html('The file must be a file of type: jpg.');
                //alert("not an image");
                return false;
                break;
        }

        var options = { 
              success:    function(objResult) { 
                            $('#fpmsg').html('');
                            //console.log(objResult);
                            if(objResult.file == 'success'){
                              $('#fpmsg').html('');
                              showFloorImage('jpg'); 
                            } else {
                              $('#fpmsg').html(objResult.file);
                            }
                          }  ,
              dataType: 'json'            
              //dataType: 'text' 
              }; 
        $('#fpimageform').ajaxForm(options).submit();    
      });

      function showFloorImage(extension){
        //src = "src='{{url('assets/track/images/'.$data['venue']->location.'.jpg')}}'";
        /*src = "{{url('assets/track/images/'.$data['venue']->location.'.jpg')}}";*/
        d = new Date();
        src = "{{$data['previewurl']}}/{{$data['venue']->location}}.jpg?"+d.getTime();
        /*src = "src='{{$data['previewurl']}}{{$data['venue']->location}}.jpg";*/
        imgtag = "<img src='" + src + "' style='margin-bottom: 10px;' class='img-responsive'/>";

        $("#imagedisplayfp").html(imgtag);
        $("#imagedisplayfp").css('display','block');

        $( "#fp_ext_div" ).html( "<input type='hidden' id='fp_ext' name='fp_ext' value='" + extension + "'/>" );

        //$("#dtpreview").attr("href", src);
        $("#myImg").attr("src", src);
      }



      var rowCount = 0;
      

//------------update x-y coordinate start
      function updateRow(updateNum) {
        //if (confirm('Are You Sure ?')) {  
          //event.preventDefault();
          var newrecord = {};
          newrecord['updateNum'] = updateNum;

          newrecord['x_cordinate'] = $('#x_cordinate'+updateNum).val(); 
          newrecord['y_cordinate'] = $('#y_cordinate'+updateNum).val(); 
          newrecord['venue_location'] = "{{$data['venue']->location}}";

          var url = '{{ URL::route('hipjam_updateSensordata')}}';
          //console.log(newrecord);
          var dataJson = JSON.stringify(newrecord);

          if(!newrecord['x_cordinate']) {
            //alert("Enter Email");
            //return false;
            newrecord['x_cordinate'] =  0;

          } 
          if(!newrecord['y_cordinate']) {
            //alert("Enter Email");
            //return false;
            newrecord['y_cordinate'] =  0;

          } else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: url,
                data: "newrecord="+dataJson,
                //async: false,
                success:  function(objResult){
                  console.log(objResult.row); 
   
                  $("td#add_name"+updateNum).parent().replaceWith(objResult.row); 
                }
            }); 


          }
        //}
      };
//------------update x-y coordinate end

//------------sensor add start

      $( "#addscanner" ).click(function(event) {
          //
          event.preventDefault();
          var newrecord = {};
          newrecord['track_name'] = $('#track_name').val(); 
          newrecord['track_location'] = $('#track_location').val();
          newrecord['track_queue'] = $('#track_queue').val(); 
          newrecord['mac'] = $('#sensor_mac').val();
          newrecord['track_min_power'] = $('#track_min_power').val(); 
          newrecord['track_max_power'] = $('#track_max_power').val(); 
          newrecord['venue_id'] = "{{$data['venue']->id}}";
          newrecord['vpnip'] = $('#sensor_vpnip').val();
          newrecord['venue_location'] = "{{$data['venue']->location}}";

          var url = '{{ URL::route('hipjam_addSvrScannerdata')}}';
          //console.log(newrecord);

          var dataJson = JSON.stringify(newrecord);

          
          if(!newrecord['track_name']) {
            alert("Enter Name");
            return false; 

          } else if(!newrecord['track_location']) {
            alert("Enter Location");
            return false; 

          } else if(!newrecord['track_queue']) {
            alert("Enter Queue");
            return false; 

          } else if(!newrecord['track_min_power']) {
            alert("Enter Min power");
            return false; 

          } else if(!newrecord['track_max_power']) {
            alert("Enter Max power");
            return false; 

            } else if(newrecord['vpnip'] == 1){
              alert("Select vpn ip");
              return false;
            

          } else {
           
            $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: url,
                data: "newrecord="+dataJson,
               
                //async: false,
                
              success:  function(objResult){
                if (objResult.status == 422){
                  debugger;
                 var errors = objResult.msg 
                 errorsHtml = '<div class="alert alert-danger"><ul>';
                 $.each( errors , function( key, value ) {
                 errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                 });
                 errorsHtml += '</ul></div>';
                 $( '.ajaxerrors' ).show().html( errorsHtml ); //appending to a <div id="form-errors">
                 }
                else {
                 console.log(objResult.row);
                 $("#server_scanners tr:first").after(objResult.row);             
                 $('#track_name').val('');
                 $('#track_location').val('');
                 $('#track_queue').val('');
                 $('#sensor_mac').val('');
                 $('#track_min_power').val('-80');
                 $('#track_max_power').val('-10');
                 $( '.ajaxerrors' ).hide().html('');
                }
             
             },
             error: function(xhr,m) {
               debugger;
             }     
            }); 


          }

        });
//------------sensor add end
//------------sensor remove start

      function removeServerRow(removeNum) {
        
            var url = '{{ URL::route('hipjam_deleteSvrScannerdata')}}';
            $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: url,
                data: "record="+removeNum,
                //async: false,
                success:  function(objResult){
                  console.log(objResult); 
                  if(objResult.msg == 'deleted'){
                    jQuery('#row'+removeNum).remove();
                  }
                }
            });
        
      }
//------------sensor remove end
//------------sensor update start

     function updateServerRow(updateNum) {
        
          var newrecord = {};
          newrecord['updateNum'] = updateNum;
          newrecord['track_name'] = $('#track_name'+updateNum).val(); 
          newrecord['track_location'] = $('#track_location'+updateNum).val();
          newrecord['mac'] = $('#sensor_mac'+updateNum).val();
          newrecord['track_queue'] = $('#track_queue'+updateNum).val(); 
          newrecord['track_min_power'] = $('#track_min_power'+updateNum).val(); 
          newrecord['track_max_power'] = $('#track_max_power'+updateNum).val(); 
          newrecord['venue_id'] = "{{$data['venue']->id}}";
          newrecord['venue_location'] = "{{$data['venue']->location}}";
          newrecord['track_type'] = $('#track_type').val();

          var url = '{{ URL::route('hipjam_updateSvrScannerdata')}}';
          //console.log(newrecord);
          var dataJson = JSON.stringify(newrecord);

          
          if(!newrecord['track_name']) {
            alert("Enter Name");
            return false; 

          } else if(!newrecord['track_location']) {
            alert("Enter Location");
            return false; 

          } else if(!newrecord['track_queue']) {
            alert("Enter Queue");
            return false;

          } else if(!newrecord['track_min_power']) {
            alert("Enter Min power");
            return false; 

          } else if(!newrecord['track_max_power']) {
            alert("Enter Max power");
            return false; 

          } else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: url,
                data: "newrecord="+dataJson,
                async: false,
                success:  function(objResult){
                if (objResult.status == 422){
                 var errors = objResult.msg 
                 errorsHtml = '<div class="alert alert-danger"><ul>';
                 $.each( errors , function( key, value ) {
                 errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                 });
                 errorsHtml += '</ul></div>';
                 $( '.ajaxerrors' ).show().html( errorsHtml ); //appending to a <div id="form-errors">
                 }
                else {
                  console.log(objResult.row);
                  $( '.ajaxerrors' ).hide().html('')
   
                  $("td#track_name"+updateNum).parent().replaceWith(objResult.row); 
                }
              }
            }); 


          }
            alert("Updated");
      };

//------------sensor update end     

      //$(document).delegate('#previewfp', 'click', function() {

        // alert(brand_id);

        //$("a#single_image").fancybox();
        //$("a#dtpreview").fancybox();

        /*$(".fancybox").fancybox({
            'width': 400,
            'height': 700,
            'transitionIn': 'elastic', // this option is for v1.3.4
            'transitionOut': 'elastic', // this option is for v1.3.4
            // if using v2.x AND set class fancybox.iframe, you may not need this
            'type': 'iframe',
            // if you want your iframe always will be 600x250 regardless the viewport size
            'fitToView' : false  // use autoScale for v1.3.4
        });

        $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          data: { 
              'brand_id': brand_id, 
          },
          url: "{{ url('lib_getserverurl'); }}",
          success: function(data) {

          }
        });*/

      //});



      function isSitenameDuplicate(sitename, brand_id) {

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: {
                'sitename': sitename,
                'brand_id': brand_id
            },
            url: "{{ url('lib_issitenameduplicate'); }}",
            success: function(message) {
                // alert("aaaaaaaaaaaaaaaaaaa ");
              if(message == "exists") {
                // alert("true ");
                return true;
              } else {
                // alert("false ");
                return false;
              }
            }
          });

      }
      function isDuplicate(id, column, table, label) {

        value=$( id ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: {
                'table': table,
                'column': column,
                'value': value
            },
            url: "{{ url('lib_isduplicate'); }}",
            success: function(message) {
              if(message == "exists") {
                // alert("true ");
                return 1;
              } else {
                // alert("false ");
                return 0;
              }
            }
          });

      }

      function buildLocationCode() {
        console.log("buildLocationCode");

        // isp_id=$( "#isplist" ).val();
        brand_id=$( "#brandlist" ).val();
        sitename=$( "#sitename" ).val();
        countrie_id=$( "#countrielist" ).val();
        province_id=$( "#provincelist" ).val();
        citie_id=$( "#citielist" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: {
                'brand_id': brand_id,
                'sitename': sitename,
                'countrie_id': countrie_id,
                'province_id': province_id,
                'citie_id': citie_id
            },
            url: "{{ url('lib_buildlocationcode'); }}",
            success: function(locationCode) {
              htmlstring = '<input id="locationcode" type="text" class="form-control"  \
                placeholder="' + locationCode + '" disabled>';
              $( "#locationCodeDisplayed" ).html( htmlstring );

              htmlstring = '<input type="hidden" name="location" value = "' + locationCode + '">';
              $( "#locationCodeHidden" ).html( htmlstring );
            }
          });
      }

      function buildProvinceList() {
        var countrie_id = $( "#countrielist" ).val();
        console.log("buildProvinceList " + countrie_id);

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getprovinces/" + countrie_id + "'); }}",
            success: function(provinces) {
              var provincesjson = JSON.parse(provinces);
              console.log("Provinces : " + provinces);

              openSelect = '<select id="provincelist" name="countrie_id" class="form-control">';
              options = '<option selected="selected">Please select</option>';
              $.each(provincesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#provincelist" ).html( selectHtml );

            }
          });
      }

      function buildCityList() {
        var province_id = $( "#provincelist" ).val();
        console.log("buildCityList " + province_id);

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getcities/" + province_id + "'); }}",
            success: function(cities) {
              var citiesjson = JSON.parse(cities);
              console.log("cities : " + cities);

              options = '<option id="citielist" selected="selected">Please select</option>';
              $.each(citiesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#citielist" ).html( selectHtml );

            }
          });
      }

      function buildServerList() {
        brand_id = $('#brand_id').val() || $( "#brandlist" ).val();
        console.log("brand_id " + brand_id);

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getservers/" + brand_id + "'); }}",
            success: function(servers) {
              var serversjson = JSON.parse(servers);

              openSelect = '<select id="serverlist" name="server_id" class="form-control">';
              options = '';
              selected = '';
              $.each(serversjson, function(index, value) {
                  sid = $('#server_id').val();
                  if( +sid == +value["id"] ) {
                    selected="selected";
                  }
                  options = options + '<option value="' + value["id"] + '" ' + selected + ' >' + value["hostname"] + '</option>';
                  selected = '';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;
              console.log("selectHtml : " + selectHtml);

              $( "#serverlist" ).html( selectHtml );

            }
          });
      }

      $(function() {
        measurenames = {};
        $('#mon_from, #tue_from, #wed_from, #thu_from, #fri_from, #sat_from, #sun_from').timepicker(
          { 'timeFormat': 'H:i' ,
            'noneOption': [
                {
                    'label': 'Closed',
                    'value': 'Closed'
                }
            ]
          }
        );

        $('#mon_from').val('{{$data['venue']->mon_from}}');
        $('#mon_to').val('{{$data['venue']->mon_to}}');

        $('#tue_from').val('{{$data['venue']->tue_from}}');
        $('#tue_to').val('{{$data['venue']->tue_to}}');

        $('#wed_from').val('{{$data['venue']->wed_from}}');
        $('#wed_to').val('{{$data['venue']->wed_to}}');

        $('#thu_from').val('{{$data['venue']->thu_from}}');
        $('#thu_to').val('{{$data['venue']->thu_to}}');

        $('#fri_from').val('{{$data['venue']->fri_from}}');
        $('#fri_to').val('{{$data['venue']->fri_to}}');

        $('#sat_from').val('{{$data['venue']->sat_from}}');
        $('#sat_to').val('{{$data['venue']->sat_to}}');

        $('#sun_from').val('{{$data['venue']->sun_from}}');
        $('#sun_to').val('{{$data['venue']->sun_to}}');

        if($('#mon_from').val() == "Closed") { $('#mon_to').hide(); };
        if($('#tue_from').val() == "Closed") { $('#tue_to').hide(); };
        if($('#wed_from').val() == "Closed") { $('#wed_to').hide(); };
        if($('#thu_from').val() == "Closed") { $('#thu_to').hide(); };
        if($('#fri_from').val() == "Closed") { $('#fri_to').hide(); };
        if($('#sat_from').val() == "Closed") { $('#sat_to').hide(); };
        if($('#sun_from').val() == "Closed") { $('#sun_to').hide(); };


        $('#mon_to, #tue_to, #wed_to, #thu_to, #fri_to, #sat_to, #sun_to').timepicker({ 'timeFormat': 'H:i' });

      });

      $('#mon_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#mon_to').hide();
        } else {
          $('#mon_to').timepicker('option', 'minTime', $(this).val());
          $('#mon_to').timepicker('option', 'maxTime', '12am');
          $('#mon_to').show();
        }
      });

      $('#tue_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#tue_to').hide();
        } else {
          $('#tue_to').timepicker('option', 'minTime', $(this).val());
          $('#tue_to').timepicker('option', 'maxTime', '12am');
          $('#tue_to').show();
        }
      });


      $('#wed_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#wed_to').hide();
        } else {
          $('#wed_to').timepicker('option', 'minTime', $(this).val());
          $('#wed_to').timepicker('option', 'maxTime', '12am');
          $('#wed_to').show();
        }
      });


      $('#thu_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#thu_to').hide();
        } else {
          $('#thu_to').timepicker('option', 'minTime', $(this).val());
          $('#thu_to').timepicker('option', 'maxTime', '12am');
          $('#thu_to').show();
        }
      });


      $('#fri_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#fri_to').hide();
        } else {
          $('#fri_to').timepicker('option', 'minTime', $(this).val());
          $('#fri_to').timepicker('option', 'maxTime', '12am');
          $('#fri_to').show();
        }
      });


      $('#sat_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#sat_to').hide();
        } else {
          $('#sat_to').timepicker('option', 'minTime', $(this).val());
          $('#sat_to').timepicker('option', 'maxTime', '12am');
          $('#sat_to').show();
        }
      });


      $('#sun_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#sun_to').hide();
        } else {
          $('#sun_to').timepicker('option', 'minTime', $(this).val());
          $('#sun_to').timepicker('option', 'maxTime', '12am');
          $('#sun_to').show();
        }
      });

    </script>

    <script>
      let default_selection = $('#track_type').data('default-selected');
      if (default_selection !== '')
        $('#track_type').val(default_selection);
showLinkVenueToBillboard();
function showLinkVenueToBillboard() {
  let selected_track_id = $('#track_type').val();
        let linked_billboard_container = $('#linked_billboard_container');
        if (selected_track_id === 'venue') {
          linked_billboard_container.slideDown('fast');
        } else {
          linked_billboard_container.slideUp('fast');
        }
}

        


        $(document).on('change', '#track_type', function() {
          showLinkVenueToBillboard();
        });


    </script>

  </body>

  

@stop
