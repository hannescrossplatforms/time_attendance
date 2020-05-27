@extends('angle_layout')

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
                    <select style="margin-right: 10px;" id="brandlist" name="brand_id" class="form-control no-radius" required></select>
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

    $('#activatebrand').click(function() {
      if ($("#brandlist").val() !== null) {
        editpage = "hipjam_activatebrand/" + $("#brandlist").val();
        window.location.href = editpage;
      } else {
        swal("No brand selected", "Please select a brand to activate it.", "error");
      }
      
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

          $("#brandlist").html(selectHtml);

        }
      });
    }


    brandsJason = {{$data['brandsJason']}}

    showBrandsTable(brandsJason);

    function showBrandsTable(brandsjson) {
      let table_contents = '';
      let table = $('#brand_table_body');
      $.each(brandsjson, function(i, b) {
        table_contents += `
          <tr>
            <td>
              ${b["name"]}
            </td>
            <td>
              <a href="/hipjam_editbrand/${b["id"]}" class="btn btn-primary">edit</a>
            </td>
          </tr>
        `;
      });

      table.html(table_contents);
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
        function() {
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


@stop