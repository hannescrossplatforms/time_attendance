@extends('layout')

@section('content')

  <body class="hipENGAGE">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">View Engage Brands</h1>
            <div class="table-responsive">
                <table id="brandTable" class="table table-striped"> </table>
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


    brandsJason = {{ $data['brandsJason'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

      $(document).delegate('#buildtable', 'click', function() {
        showBrandsTable(brandsJason);
      });

      function showBrandsTable(brandsjson) {
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Brand Name</th>\n\
                      <th>Code</th>\n\
                      <th>Auth Token</th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(brandsjson, function(index, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["name"]  + '</td>\n\
                      <td> ' + value["code"]  + '</td>\n\
                      <td> ' + value["auth_token"]  + '</td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#brandTable" ).html( table );
      }

        $(document).delegate('.btn-delete', 'click', function() {
        var brandid = this.getAttribute('data-brandid');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this brand?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, delete it!',
          closeOnConfirm: false,
          //closeOnCancel: false
        },
          function(){
            swal("Deleted!", "Brand has been deleted!", "success");
            $.ajax({
              type: "GET",
              dataType: 'json',
              contentType: "application/json",
              url: "{{ url('hipwifi_deletebrand/" + brandid + "'); }}",
              success: function(brands) {
                var brandsjson = JSON.parse(brands); 
                showBrandsTable(brandsjson);
              }
            });
          });
      });
	



    </script>

  </body>

@stop