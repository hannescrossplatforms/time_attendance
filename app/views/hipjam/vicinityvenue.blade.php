@extends('layout')

<link href="{{ asset('css/modal_style.css')}}" rel="stylesheet" media="screen" />

<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id ?>

@section('content')

<body class="hipJAM">
  <div class="container-fluid">
    <div class="row">

      @include('hipjam.sidebar')
      <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

        <h1 class="page-header" data-venue="{{$data['venue']->sitename}}">Edit Track Venue - {{$data['venue']->sitename}}</h1>
        <div class="container-fluid">
          <p>General</p>
          <div class="row">
            {{ Form::hidden('id', $data['venue']->id) }}
            <div class="row">
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="track_slug">Track Venue ID</label>
                  @if (empty($data['venue']->track_slug))
                  <input id="track_slug" type="text" class="form-control" name="track_slug" placeholder="" value="{{$data['venue']->sitename}}" required>
                  @else
                  <input id="track_slug" type="text" class="form-control" name="track_slug" placeholder="" value="{{$data['venue']->track_slug}}" required>
                  @endif
                </div>
              </div>
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="billboard_retail">Billboard / Retail</label>
                  <select class="form-control" name="track_type" id="track_type" data-default-selected="{{$data['venue']->track_type}}">
                    <option value="venue">Venue</option>
                    <option value="billboard">Billboard</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="track_server_location">Vicinity Server ID</label>
                  <input id="track_server_location" type="text" class="form-control" name="track_server_location" placeholder="" value="{{$data['venue']->track_server_location}}" required>
                </div>
              </div>
              <div class="col-md-6 text-left">
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
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="track_ssid">Track WiFi SSID</label>
                  @if (empty($data['venue']->track_ssid))
                  <input id="track_ssid" type="text" class="form-control" name="track_ssid" placeholder="" value="RaspberryJAM" required>
                  @else
                  <input id="track_ssid" type="text" class="form-control" name="track_ssid" placeholder="" value="{{$data['venue']->track_ssid}}" required>
                  @endif
                </div>
              </div>
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="track_password">Track WiFi Password</label>
                  @if (empty($data['venue']->track_password))
                  <input id="track_password" type="text" class="form-control" name="track_password" placeholder="" value="3nd34vour" required>
                  @else
                  <input id="track_password" type="text" class="form-control" name="track_password" placeholder="" value="{{$data['venue']->track_password}}" required>
                  @endif
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="latitude">Venue Latitude (GPS co-ord)</label>
                  <input id="latitude" type="text" class="form-control" name="latitude" placeholder="e.g. -33.894083" value="{{$data['venue']->latitude}}" required>
                </div>
              </div>
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="longitude">Venue Longitude (GPS co-ord)</label>
                  <input id="longitude" type="text" class="form-control" name="longitude" placeholder="e.g. 18.510610" value="{{$data['venue']->longitude}}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 text-left">
                <div class="form-group">
                  <label for="track_password">Time Zone</label>
                  <select name="timezone" id="timezone_select" class="form-control" autocomplete="off">
                    {{ $data['timezoneselect'] }}
                  </select>
                </div>
              </div>
              <div class="col-md-12 text-left">
                <div class="form-group">
                  <label>Sensor Status : </label>
                  <label class="radio-inline">
                    <input type="radio" id="ap_active" name="ap_active" value="1" {{ $data['ap_active_checked']}}> Active
                  </label>
                  <label class="radio-inline">
                    <input type="radio" id="ap_inactive" name="ap_active" value="0" {{ $data['ap_inactive_checked']}}> Inactive
                  </label>
                </div>
                  </div>
            </div>
          
            <br>
            <button id="update_vicinity_venue" class="btn btn-primary" type="button"> Submit </button>
          </div>
          <hr />
          <p>Sensor</p>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="tnastafftable" id="server_scanners">
                  <tr>
                    <!-- <td>
                      <input id="track_name" class="form-control no-radius" type="text" autocomplete="off" placeholder="Name">
                    </td>
                    <td>
                      <input id="track_location" class="form-control no-radius" type="text" autocomplete="off" placeholder="Location">
                    </td> -->
                    <td class="sensor_mac">
                      <input id="sensor_mac" name="sensor_mac" class="form-control no-radius" placeholder="Mac Address" type="text">
                    </td>
                    <!-- <td>
                      <input id="track_queue" class="form-control no-radius" type="text" autocomplete="off" placeholder="Queue">
                    </td> -->
                    <td>
                      <input id="track_min_power" class="form-control no-radius" type="text" autocomplete="off" placeholder="Min Power" value="-80">
                    </td>
                    <td>
                      <input id="track_max_power" class="form-control no-radius" type="text" autocomplete="off" placeholder="Max Power" value="-10">
                    </td>
                    <td>
                      <a id="addscanner" class="btn btn-primary no-radius" href="javascript:void(0);"><i class="fa fa-plus"></i> Add Sensor</a>
                      <input type="hidden" id="hf_sensor_name" value="{{$data['sensor_name']}}"/>
                    </td>
                  </tr>
                  <tr></tr>

                  <?php if (!empty($data['sensors'])) {
                    foreach ($data['sensors'] as $sensor) { ?>
                      <tr id="row{{$sensor->id}}">
                        <!-- <td class="sensor_name">
                          <input id="track_name{{$sensor->id}}" name="track_name" class="form-control no-radius" placeholder="Name" value="{{$sensor->name}}" type="text">
                        </td>
                        <td class="track_location">
                          <input id="track_location{{$sensor->id}}" name="track_location" class="form-control no-radius" value="{{$sensor->location}}" placeholder="Location" type="text">
                        </td> -->
                        <td class="sensor_mac">
                          <input id="sensor_mac{{$sensor->id}}" name="sensor_mac" class="form-control no-radius" value="{{$sensor->mac}}" placeholder="Mac Address" type="text">
                        </td>
                        <!-- <td class="track_queue">
                          <input id="track_queue{{$sensor->id}}" name="track_queue" class="form-control no-radius" placeholder="Queue" value="{{$sensor->queue}}" type="text">
                        </td> -->
                        <td class="track_min_power">
                          <input id="track_min_power{{$sensor->id}}" name="track_min_power" class="form-control no-radius" value="{{$sensor->min_power}}" placeholder="Min Power" type="text">
                        </td>
                        <td class="track_max_power">
                          <input id="track_max_power{{$sensor->id}}" name="track_max_power" value="{{$sensor->max_power}}" class="form-control no-radius" placeholder="Max Power" type="text">
                        </td>
                        <td class="sensor_vpnip" style="display:none">
                          <input id="sensor_vpnip" name="vpnip" value="{{$sensor->vpnip->ip_address}}" class="form-control no-radius" placeholder="vpnip" type="text" readonly="readonly">
                        </td>
                        <td width="20%">
                          <a onclick="updateServerRow({{$sensor->id}});" class="btn btn-primary no-radius btn-delete btn-sm">Update</a>
                          <a href="javascript:void(0);" onclick="removeServerRow({{$sensor->id}});" class="btn btn-primary no-radius btn-delete btn-sm">Delete</a>
                        </td>
                      </tr>
                  <?php  }
                  } ?>

                </table>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>

  <style>
    .error {
      border: 1px solid red !important;
    }
  </style>

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

let default_selection = $('#track_type').data('default-selected');
      if (default_selection !== '')
        $('#track_type').val(default_selection);


    if (window.location.href.indexOf('activate') !== -1) {
      $('#update_vicinity_venue').html('Activate');
      $('.page-header').html(`Activate Track Venue - ${$('.page-header').data('venue')}`);
      $('#track_slug').attr('disabled', 'disabled');
    }



    showLinkVenueToBillboard();

    $(document).on('change', '#track_type', function() {
      showLinkVenueToBillboard();
    });

    $(document).on('click', '#update_vicinity_venue', function() {
      if (venueIsValid()) {
        let id = "{{$data['venue']->id}}";
        $.ajax({
          type: "POST",
          dataType: 'json',
          url: `http://hiphub.hipzone.co.za/vicinity/venue/${id}/update`,
          data: generateFormData(),
          success: function(objResult) {
            window.location.href = 'http://hiphub.hipzone.co.za/hipjam_showvenues';
          },
          error: function(jqXHR, status) {
debugger;
            alert("Invalid Track Server");
          }
        });
      }
    });

    function generateFormData() {
      var data = {};
      data['id'] = "{{$data['venue']->id}}";

      data['track_slug'] = $('#track_slug').val();
      data['track_type'] = $('#track_type').val();

      data['track_server_location'] = $('#track_server_location').val();

      data['track_ssid'] = $('#track_ssid').val();
      data['track_password'] = $('#track_password').val();

      data['latitude'] = $('#latitude').val();
      data['longitude'] = $('#longitude').val();

      data['timezone'] = $('#timezone_select').val();
      data['linked_billboard'] = $('#track_linked_billboard').val();

    
      data['ap_active'] = $('#ap_active').prop('checked');
    
      

      data['venue_location'] = "{{$data['venue']->location}}";

      return `data=${JSON.stringify(data)}`
    }

    function venueIsValid() {
      let is_valid = true;

      let track_slug_input = $('#track_slug'); // Track Venue ID
      // let track_type_input = $('#track_type'); // Billboard / Retail -- CANNOT BE EMPTY

      let track_server_location_input = $('#track_server_location'); // Vicinity Server ID

      let track_ssid_input = $('#track_ssid'); // Track WiFi SSID
      let track_password_input = $('#track_password'); // Track WiFi Password

      // let latitude_input = $('#latitude'); // Venue Latitude (GPS co-ord) -- NOT REQUIRED
      // let longitude_input = $('#longitude'); // Venue Longitude (GPS co-ord) -- NOT REQUIRED

      if (track_slug_input.val() === '') {
        track_slug_input.addClass('error');
        alert('Please enter a Track Venue ID');
        is_valid = false;
      } else {
        track_slug_input.removeClass('error');
      }

      if (track_server_location_input.val() === '') {
        track_server_location_input.addClass('error');
        alert('Please enter a Vicinity Server ID');
        is_valid = false;
      } else {
        track_server_location_input.removeClass('error');
      }

      if (track_ssid_input.val() === '') {
        track_ssid_input.addClass('error');
        alert('Please enter a Track WiFi SSID');
        is_valid = false;
      } else {
        track_ssid_input.removeClass('error');
      }

      if (track_password_input.val() === '') {
        track_password_input.addClass('error');
        alert('Please enter a Track WiFi Password');
        is_valid = false;
      } else {
        track_password_input.removeClass('error');
      }

      return is_valid;
    }

    function showLinkVenueToBillboard() {

      // if (default_selection === 'venue' || default_selection === '') {

      // }

      let selected_track_id = $('#track_type').val();
      let linked_billboard_container = $('#linked_billboard_container');
      if (selected_track_id === 'venue') {
        linked_billboard_container.slideDown('fast');
      } else {
        linked_billboard_container.slideUp('fast');
      }
    }

    $( "#addscanner" ).click(function(event) {
          event.preventDefault();
          var newrecord = {};
          newrecord['track_name'] = $('#hf_sensor_name').val();
          newrecord['track_location'] = "{{$data['venue']->track_type ? $data['venue']->track_type : 'venue'}}";

          newrecord['track_queue'] = 'tracks03';
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

                 var errors = objResult.msg
                 errorsHtml = '<div class="alert alert-danger"><ul>';
                 $.each( errors , function( key, value ) {
                 errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                 });
                 errorsHtml += '</ul></div>';
                 $( '.ajaxerrors' ).show().html( errorsHtml ); //appending to a <div id="form-errors">
                 }
                else {
                window.location.href = `http://hiphub.hipzone.co.za/vicinity/venue/${newrecord['venue_id']}`
                }
                // window.location.href = `http://hiphub.hipzone.co.za/vicinity/venue/${newrecord['venue_id']}`
             },
             error: function(xhr,m) {

             }
            });


          }

        });
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
            },
            error: function(xjr, err) {

            }
        });

  }
  </script>
</body>



@stop