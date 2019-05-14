@extends('layout')

@section('content')

  <body class="hipJAM">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Dashboard</h1>

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

    // $(function() {
    //   console.log("begin");
    //     showSelectedVenues();
    // });


    venuesJson = {{ $data['venuesJson'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

      $(document).delegate('#buildtable', 'click', function() {
        showVenuesTable(venuesJson);
      });

      $(document).delegate('#reset', 'click', function() {
        showVenuesTable(venuesJson);
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
                      <th>Location</th>\n\
                      <th>Contact</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {


            /*editbutton = '<a href="{{ url('hipwifi_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n';*/
            if(value["apisitename"] != 'no_venue'){             
              viewbutton = '<a href="{{ url('hipjam_viewvenue'); }}/' + value["id"]+'/'+value["apisitename"] + '" class="btn btn-default btn-sm">view</a>\n';
            } else {
              viewbutton = '<a href="javascript:void(0);" onclick="alert_message()" class="btn btn-default btn-sm">view</a>\n';
            }

            /*viewbutton = '<a href="{{ url('hipjam_viewvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">view</a>\n';*/


            /*deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';*/

            /*if(value["device_type"] == 'Mikrotik') {
              redeploybutton = '<a href="{{ url('hipwifi_redeploymikrotikvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">redeploy</a>\n';
            } else {
              redeploybutton = '\n';
            }*/

            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + value["location"]  + '</td>\n\
                      <td> ' + value["contact"]  + '</td>\n\
                      <td> ' + viewbutton + '</td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#venueTable" ).html( table );
      }

      function alert_message(venuesjson) {
        alert('Track Venue Id has not been provided. Please configure in Track->Venue Management');
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
            url: "{{ url('hipwifi_deletevenue/" + venueId + "'); }}",
            success: function(venues) {
              var venuesjson = JSON.parse(venues);
              showVenuesTable(venuesjson);
            }
          });
        });
      });

    </script>

  </body>

@stop
