@extends('layout')

@section('content')

  <body class="hipJAM">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Venue Management</h1>

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
<!--               <div class="form-group">
                <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
              </div>
              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button> -->
              @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
              <div class="form-group">
                <label>Select a venue for activation</label>
                <select id="venuelist" name="venue_id" class="form-control no-radius" required></select>
              </div>
                <a id="activatevenue" class="btn btn-primary"><i class="fa fa-plus"></i> Activate Venue</a>
              @endif
              <div class="table-responsive">
                  <table id="venueTable" class="table table-striped"> </table>
              </div>

              <div class="add-venue">
                  <!-- <a href="{{ url('hipjam_addvenue'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Venue</a> -->
              </div>
            </form>

        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/prefixfree.min.js"></script>

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

    buildVenueList();

    function buildVenueList() {
            console.log("venues : xxxxxxxxxxxxxxxxxx " );
      $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          url: "{{ url('hipjam_getinactivevenues'); }}",
          success: function(venues) {
            debugger;
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
debugger;
          }
      });
    }

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
                      <th>Venue Name</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {

          if(("{{$data['user']}}" != "superadmin") && (value["track_slug"] == "" || value["track_server_location"] == "" ) ) {  
            editbutton = '<a href="javascript:void(0)" onclick="alert(\'Track Venue Id and Track Server need to be set by a super admin before you can continue.\');" class="btn btn-default btn-sm">edit</a>\n';
          } else {
            editbutton = '<a href="{{ url('hipjam_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n';
          }
          console.log("{{$data['is_vicinity']}}")
          if ("{{$data['is_vicinity']}}") {
            editbutton = `<a href="/vicinity/venue/${value["id"]}" class="btn btn-default btn-sm">edit</a>\n`
          }


            /*deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';*/

            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + editbutton  + '</td>\n\
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
            url: "{{ url('hipjam_deletevenue/" + venueId + "'); }}",
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
