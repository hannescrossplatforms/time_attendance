@extends('layout')

@section('content')

  <body class="hipWifi">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Venue Management</h1>

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
<!--               <div class="form-group">
                <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
              </div>
              <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">Mac Address</label>
                <input type="text" class="form-control" id="src-macaddress" placeholder="Mac Address">
              </div>

              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button> -->

              <div class="form-group">
                <label>Select a venue for activation</label>
                <select id="venuelist" name="venue_id" class="form-control no-radius" required></select>
              </div>
              @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                <a id="activatevenue" class="btn btn-primary"><i class="fa fa-plus"></i> Activate Venue</a>
              @endif
            </form>

            <div class="table-responsive">
                <table id="venueTable" class="table table-striped"> </table>
            </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/prefixfree.min.js"></script> 
    
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

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

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

            editbutton = '<a href="{{ url('hipwifi_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n';

            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin"))

              if (value["device_type"] == 'Mikrotik') {
              scriptbutton = '<a href="{{ url('hipwifi_deployrsc'); }}/' + value["id"] + '" class="btn btn-default btn-sm">script</a>\n';

               }
                else {
                scriptbutton = '\n';
              }
            
            @else 
              scriptbutton = '\n';
            @endif

            // deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';
            disablebutton = '<a class="btn btn-default btn-disable btn-sm" data-venueid = ' + value["id"] + ' href="#">disable</a>\n';

            if(value["device_type"] == 'Mikrotik') {
              redeploybutton = '<a href="{{ url('hipwifi_redeploymikrotikvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">redeploy</a>\n';
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
	
      $(document).delegate('.btn-disable', 'click', function() {
      var venueId = this.getAttribute('data-venueid');
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to disable this venue?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, disable it!',
        closeOnConfirm: false,
      },
        function(){
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
        });
      });


    </script>

  </body>

@stop