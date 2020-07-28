@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Venue Management<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
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
                <form class="form-inline" role="form" style="margin-bottom: 15px;">
                  <div class="form-group">
                    <label>Select a venue for activation</label>
                    <select id="venuelist" name="venue_id" class="form-control no-radius" required></select>
                  </div>
                  @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                    <a id="activatevenue" class="btn btn-primary" style="color: white"><i class="fa fa-plus"></i> Activate Venue</a>
                  @endif
                </form>
              </div>
              <div class="col-12">
                <table id="venueTable" class="table table-striped"> </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


    
    <script>

   $('#activatevenue').click( function(){
      activatepage = "hipwifi_activatevenue/" + $( "#venuelist" ).val();
      window.location.href = activatepage;
    });



    buildVenueList();

    function buildVenueList() {
      $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          url: "{{ url('hipwifi_getinactivevenues'); }}",
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

          }
        });
    }


    venuesJason = {{ $data['venuesJason'] }};

    showVenuesTable(venuesJason);

      $(document).delegate('#buildtable', 'click', function() {
        showVenuesTable(venuesJason);
      });

      $(document).delegate('#reset', 'click', function() {
        showVenuesTable(venuesJason);
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();

        sitename = $( "#src-sitename" ).val();
        macaddress = $( "#src-macaddress" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'sitename': sitename, 
              'macaddress': macaddress 
            },
            url: "{{ url('lib_filterdvenues'); }}",
            success: function(filteredVenuesjson) {
              // alert("success");
              showVenuesTable(filteredVenuesjson);
            }
         });
      });

      function showVenuesTable(venuesjson) {
        // alert("showVenuesTable");
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Sitename</th>\n\
                      <th>Macaddress</th>\n\
                      <th>Location</th>\n\
                      <th>Server</th>\n\
                      <th>Contact</th>\n\
                      <th>Status</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {

            editbutton = '<a href="{{ url('hipwifi_editvenue'); }}/' + value["id"] + '" class="btn btn-info btn-sm">edit</a>\n';

            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin"))

              if (value["device_type"] == 'Mikrotik') {
              scriptbutton = '<a href="{{ url('hipwifi_deployrsc'); }}/' + value["id"] + '" class="btn btn-purple btn-sm">script</a>\n';

               }
                else {
                scriptbutton = '\n';
              }
            
            @else 
              scriptbutton = '\n';
            @endif

            // deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';
            disablebutton = '<a class="btn btn-danger btn-disable btn-sm" data-venueid = ' + value["id"] + ' href="#">disable</a>\n';

            if(value["device_type"] == 'Mikrotik') {
              redeploybutton = '<a href="{{ url('hipwifi_redeploymikrotikvenue'); }}/' + value["id"] + '" class="btn btn-warning btn-sm">redeploy</a>\n';
            } else {
              redeploybutton = '\n';
            }

            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + value["macaddress"]  + '</td>\n\
                      <td> ' + value["location"]  + '</td>\n\
                      <td> ' + value["hostname"]  + '</td>\n\
                      <td> ' + value["contact"]  + '</td>\n\
                      <td> ' + value["status"]  + '</td>\n\
                      <td> ' + editbutton + disablebutton + scriptbutton + redeploybutton + '</td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#venueTable" ).html( table );
      }

      // $(document).delegate('.btn-delete', 'click', function() {
      // var venueId = this.getAttribute('data-venueid');
      // swal({
      //   title: "Are you sure?",
      //   text: "Are you sure you want to delete this venue?",
      //   type: "warning",
      //   showCancelButton: true,
      //   confirmButtonColor: '#DD6B55',
      //   confirmButtonText: 'Yes, delete it!',
      //   closeOnConfirm: false,
      // },
      //   function(){
      //     swal("Deleted!", "Venue has been deleted!", "success");
      //     $.ajax({
      //       type: "GET",
      //       dataType: 'json',
      //       contentType: "application/json",
      //       url: "{{ url('hipwifi_deletevenue/" + venueId + "'); }}",
      //       success: function(venues) {
      //         var venuesjson = JSON.parse(venues); 
      //         showVenuesTable(venuesjson);
      //       }
      //     });
      //   });
      // });
	
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
              }
            });
          }
      });  
    });


    </script>



@stop