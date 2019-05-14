@extends('layout')

@section('content')

  <body class="hipJAM">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Brand Management</h1>

            <!-- <form role="form"> -->
            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
            <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <label>Select a brand for activation</label>
                <select id="brandlist" name="brand_id" class="form-control no-radius" required></select>
              </div>
                  <a id="activatebrand" class="btn btn-primary"><i class="fa fa-plus"></i> Activate Brand</a>
            </form>
            @endif

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

    $(function() {
      // alert("begin");
        // showSelectedBrands();
    });

    $('#activatebrand').click( function(){
      editpage = "hipjam_activatebrand/" + $( "#brandlist" ).val();
      window.location.href = editpage;
    });


    buildBrandList();

    function buildBrandList() {
      $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          url: "{{ url('hipjam_getinactivebrands'); }}",
          success: function(brands) {
            var brandsjson = JSON.parse(brands); 
            console.log("Brands : " + brands);

            openSelect = '<select id="brandlist" name="brand_id" class="form-control">';
            options = '';
            $.each(brandsjson, function(index, value) {
                options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
            });
            closeSelect = '</select>';

            selectHtml = openSelect + options + closeSelect;

            $( "#brandlist" ).html( selectHtml );

          }
        });
    }

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
                      <td><a href="{{ url('hipjam_editbrand'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                      </td>\n\
                    </tr>\n\
                    ';
                          // <a class="btn btn-default btn-delete btn-sm" data-brandid = ' + value["id"] + ' href="#">delete</a>\n\
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
              url: "{{ url('hipjam_deletebrand/" + brandid + "'); }}",
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