@extends('layout')

@section('content')

<body class="HipADMIN">
<div class="container-fluid">
  <a id="buildtable"></a>

  <div class="row">
    @include('admin.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Venue Management</h1>

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
              </div>
              <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">Mac Address</label>
                <input type="text" class="form-control" id="src-macaddress" placeholder="Mac Address">
              </div>

              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button>
              <div class="add-venue">
                  <a href="{{ url('admin_addvenue'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Venue</a>
              </div>
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
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Sitename</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {

            editbutton = '<a href="{{ url('admin_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n';

            deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';

            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + editbutton + deletebutton + '</td>\n\
                    </tr>\n\
                    ';

        	endTable = ' \
                  </tbody>\n\
                </table>';

	        table = beginTable + rows + endTable;
	        $( "#venueTable" ).html( table );
	    });
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
            url: "{{ url('admin_deletevenue/" + venueId + "'); }}",
            success: function(venues) {
              var venuesjson = JSON.parse(venues); 
              console.log(venuesjson);
              showVenuesTable(venuesjson);
            }
          });
        });
      });
	



    </script>







</body>
@stop
