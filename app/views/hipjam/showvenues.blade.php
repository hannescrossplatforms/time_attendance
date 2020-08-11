@extends('angle_layout')

@section('content')





<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Venue Management</div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              All Venues

            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                <form class="form-inline" role="form" style="margin-bottom: 15px;">
                  <div class="form-group">
                    <label style="margin-right: 10px;">Select a venue forr activation</label>
                    <select style="margin-right: 10px;" id="venuelist" name="venue_id" class="form-control no-radius" required></select>
                  </div>
                  <a id="activatevenue" class="btn btn-primary" style="color: white;"><i class="fas fa-plus"></i> Activate Venue</a>
                </form>
                @endif
              </div>
              <div class="col-12">
                <table id="venueTable" class="table table-striped">
                <thead>
                    <tr>
                      <th>Brand Name</th>
                      <th>Venue Type</th>
                      <th>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="venue_table_body"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

    @if (\User::isVicinity())
  <script>
    $('#activatevenue').click(function() {
      window.location.href = `vicinity/venue/${$("#venuelist").val()}/activate`;
    });
  </script>
  @else
  <script>
    $('#activatevenue').click(function() {
      activatepage = "hipjam_activatevenue/" + $("#venuelist").val();
      window.location.href = activatepage;
    });
  </script>
  @endif

    <script>

    venuesJson = {{ $data['venuesJson'] }};
    debugger;
    buildVenueList();

    function buildVenueList() {
            console.log("venues : xxxxxxxxxxxxxxxxxx " );
      $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          url: "{{ url('hipjam_getinactivevenues'); }}",
          success: function(venues) {
            var venuesjson = JSON.parse(venues);
            console.log("venues : " + venues);

            openSelect = '<select id="venuelist" name="venue_id" class="form-control">';
            options = '';
            $.each(venuesjson, function(index, value) {
                options = options + '<option value="' + value["id"] + '">' + value["sitename"] + '</option>';
            });
            closeSelect = '</select>';

            selectHtml = openSelect + options + closeSelect;

            $( "#venuelist" ).html( selectHtml );

          },
          error: function(xhr, m){

          }
      });
    }

      showVenuesTable(venuesJson);
      updateSonoffButtons();

      $(document).delegate('#buildtable', 'click', function() {
        showVenuesTable(venuesJson);
        updateSonoffButtons();
      });

      $(document).delegate('#reset', 'click', function() {
        showVenuesTable(venuesJson);
        updateSonoffButtons();
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();

        sitename = $( "#src-sitename" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: {
              'sitename': sitename
            },
            url: "{{ url('lib_filterdvenues'); }}",
            success: function(filteredVenuesjson) {
              // alert("success");
              showVenuesTable(filteredVenuesjson);
              updateSonoffButtons();
            }
         });
      });

      function showVenuesTable(venuesjson) {
        // alert("showVenuesTable");
        let table = '';
        let rows = '';
        let editbutton = '';
        let beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Venue Name</th>\n\
                      <th>Type</th>\n\
                      <th>Status</th>\n\
                      <th>Actions</th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {
          
          if(("{{$data['user']}}" != "superadmin") && (value["track_slug"] == "" || value["track_server_location"] == "" ) ) {
            editbutton = '<a href="javascript:void(0)" onclick="alert(\'Track Venue Id and Track Server need to be set by a super admin before you can continue.\');" class="btn btn-primary btn-sm">edit</a>\n';
          } else {
            editbutton = '<a href="{{ url('hipjam_editvenue'); }}/' + value["id"] + '" class="btn btn-primary btn-sm">edit</a>\n';
          }
          console.log("{{$data['is_vicinity']}}")
          if ("{{$data['is_vicinity']}}") {
            editbutton = `<a href="/vicinity/venue/${value["id"]}" class="btn btn-primary btn-sm">edit</a>\n`
          }
          var sonoff_button = '';
          if (value["sonoff_device_uuid"] != null){
            sonoff_button = '<button class="btn sonoff-button" venue_id="' +  value["id"] + '" sonoff_status="' + value["sonoff_device_action_status"] + '">' + value["sonoff_device_action_status"] + '<br><small>Click to turn off</small></button>';  
          }






            /*deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';*/
            let status_row = value['ap_active'] === '0' ? '<span class="badge badge-danger">INACTIVE</span>' : '<span class="badge badge-success">ACTIVE</span>'

            // console.log(`[${value['id']}] ${value["sitename"]} => VALUE: ${value['jam_activated']}; is_active: ${value['jam_activated'] === '1'}; is_false: ${value['jam_activated'] === '0'}`);
            

            rows = rows + '\
                    <tr>\n\
                      <td style="width: 20%;"> ' + value["sitename"]  + '</td>\n\
                      <td style="width: 15%;"> ' + (value["track_type"] === 'billboard' ? 'OOH Site' : 'Venue')  + '</td>\n\
                      <td>' + status_row + '</td>\n\
                      <td style="width: 65%"> ' + editbutton  + '<button class="btn btn-danger btn-disable" data-venueid="' + value["id"] + '">disable</button> ' + sonoff_button + '</td>\n\
                    </tr>\n\
                    ';
        });

        

        let endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#venueTable" ).html( table );
      }

      

      $(document).delegate('.btn-delete', 'click', function() {
      var venueId = this.getAttribute('data-venueid');
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this venue?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
      },
        function(){
          swal("Deleted!", "Venue has been deleted!", "success");
          $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('hipjam_deletevenue/" + venueId + "'); }}",
            success: function(venues) {
              var venuesjson = JSON.parse(venues);
              showVenuesTable(venuesjson);
              updateSonoffButtons();
            }
          });
        });
      });

      function updateSonoffButtons(){
        $('.sonoff-button').each(function(){
          
          var sonoff_status = $(this).attr('sonoff_status');
          console.log(sonoff_status);

          if(sonoff_status == 'on'){
            //clickable, on, click to turn off
            $(this).addClass("btn-success");
            $(this).value("ON");
          }
          else if (sonoff_status == 'shutting_down'){
            //not clickable, shutting down
            $(this).addClass("btn-warning");
            $(this).value("Shutting Down");
          }
          else if (sonoff_status == 'starting_up'){
            //not clickable, starting up
            $(this).addClass("btn-warning");
            $(this).value("Starting Up");
          }
          else if (sonoff_status == 'off') {
            // clickable, Off, click to turn on
            $(this).addClass("btn-danger");
            $(this).value("Off");
          }
          else {
            $(this).hide();
          }

        });
      };

      $(document).on('click', '.btn-disable', function () {
      var venueId = $(this).data('venueid');
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to disable this venue?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, disable it!',
        closeOnConfirm: false,
      }).then((willDelete) => {
          if (willDelete) {
            swal("Disabled!", "Venue has been disabled!", "success");
            $.ajax({
              type: "GET",
              dataType: 'json',
              contentType: "application/json",
              url: "{{ url('hipwifi_disablevenue/" + venueId + "'); }}",
              success: function(venues) {
                var venuesjson = JSON.parse(venues);
                showVenuesTable(venuesjson);
                buildVenueList();
                updateSonoffButtons();
              }
            });
          }
      });
    });




    </script>


@stop
