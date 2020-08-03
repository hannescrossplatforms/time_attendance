@extends('angle_admin_layout')

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
              <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
                <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
              </div>
              <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">Mac Address</label>
                <input type="text" class="form-control" id="src-macaddress" placeholder="Mac Address">
              </div>

              <button id="filter" type="submit" class="btn btn-info">Filter</button>
              <button id="reset" type="submit" class="btn btn-warning">Reset</button>
              <div class="add-venue">
                  <a href="{{ url('admin_addvenue'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Venue</a>
              </div>
            </form>

            <div class="table-responsive">
              <table id="venueTable" class="table table-striped">
                <thead>
                    <tr>
                      <th>Sitename</th>
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
  </div>
</section>




    <script>

    venuesJason = {{ $data['venuesJason'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });
      showVenuesTable(venuesJason);
    

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
        let table_html = '';
        let tbody = $('#venue_table_body');
        debugger;
        debugger;
        $.each(venuesjson, function(index, value) {
          table_html += `
            <tr>
              <td>${value["sitename"]}</td>
              <td>
                <a href="http://hiphub.hipzone.co.za/admin_editvenue/${value['id']}" class="btn btn-primary btn-sm">edit</a>
                <a class="btn btn-danger btn-delete btn-sm" data-venueid='${value['id']}' href="#">delete</a>
              </td>
            </tr>          
        
          `;
        });
        tbody.html(table_html);
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
      })
      .then((willDelete) => {
        // function(){
          if (willDelete) {
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
        }
        });
      });
	



    </script>







</body>
@stop
