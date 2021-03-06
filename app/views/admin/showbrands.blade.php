@extends('layout')

@section('content')

  <body class="HipADMIN">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('admin.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Brand Management</h1>
			      <form role="form">
<!--               <div class="form-group">
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="start typing brand name to filter">
              </div> -->
                @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                  <a href="{{ url('admin_addbrand'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Brand</a>
                @endif
            </form>
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

    // $(function() {
    //   console.log("begin");
    //     showSelectedBrands();
    // });


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
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(brandsjson, function(index, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["name"]  + '</td>\n\
                      <td><a href="{{ url('admin_editbrand'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                          <a class="btn btn-default btn-delete btn-sm" data-brandid = ' + value["id"] + ' href="#">delete</a>\n\
                      </td>\n\
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
              url: "{{ url('admin_deletebrand/" + brandid + "'); }}",
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