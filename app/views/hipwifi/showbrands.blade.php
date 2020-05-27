@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Brand Management<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              All Brands

            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                <form class="form-inline" role="form" style="margin-bottom: 15px;">
                  <div class="form-group">
                    <label style="margin-right: 10px;">Select a brand for activation</label>
                    <select style="margin-right: 10px; width: 120px;" id="brandlist" name="brand_id" class="form-control no-radius" required></select>
                  </div>
                  <a id="activatebrand" class="btn btn-primary" style="color: white;"><i class="fas fa-plus"></i> Activate Brand</a>
                </form>
                @endif
              </div>
              <div class="col-12">
                <table id="brandTable" class="table table-striped">
                <thead>
                    <tr>
                      <th>Brand Name</th>
                      <th>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="brand_table_body"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    
    <script>

    // $(function() {
    //   console.log("begin");
    //     showSelectedBrands();
    // });

    $('#activatebrand').click( function(){
      editpage = "hipwifi_activatebrand/" + $( "#brandlist" ).val();
      window.location.href = editpage;
    });


    buildBrandList();

    function buildBrandList() {
      $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          url: "{{ url('hipwifi_getinactivebrands'); }}",
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
      showBrandsTable(brandsJason);
      

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
                      <td><a href="{{ url('hipwifi_editbrand'); }}/' + value["id"] + '" class="btn btn-primary btn-sm">edit</a>\n\
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
              url: "{{ url('hipwifi_deletebrand/" + brandid + "'); }}",
              success: function(brands) {
                var brandsjson = JSON.parse(brands); 
                showBrandsTable(brandsjson);
              }
            });
          });
      });
	



    </script>

  

@stop