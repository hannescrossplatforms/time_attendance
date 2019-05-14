@extends('layout')

@section('content')

  <body class="hipENGAGE">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Venue Management</h1>

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
              </div>

              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button>
              <div class="add-venue">
                  <a href="{{ url('hipengage_addvenue'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Venue</a>
              </div>
            </form>

            <div class="table-responsive">
                <table id="venueTable" class="table table-striped"> </table>
            </div>
            <!-- <a href="{{ url('hipwifi_addvenue'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Venue</a> -->
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
            redeploy = '';
            if(value["device_type"] == 'Mikrotik') {
              redeploy = '<td><a href="{{ url('hipwifi_redeploymikrotikvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">redeploy</a>\n\'';
            }
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + value["location"]  + '</td>\n\
                      <td> ' + value["contact"]  + '</td>\n\
                      <td><a href="{{ url('hipengage_editvenuepositions'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit positions</a>\n\
                      </td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
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