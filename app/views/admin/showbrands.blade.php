@extends('angle_admin_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Brand Management</div><!-- START Language list-->
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
            <form role="form">
                @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                  <a href="{{ url('admin_addbrand'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Brand</a>
                @endif
            </form>
            <div class="table-responsive">
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
</section>




    
    <script>

    // $(function() {
    //   console.log("begin");
    //     showSelectedBrands();
    // });


    brandsJason = {{ $data['brandsJason'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });
      showBrandsTable(brandsJason);

      function showBrandsTable(brandsjson) {

        let table_html = '';
        let tbody = $('#brand_table_body');
        $.each(brandsjson, function(index, value) {
          table_html += `
            <tr>
              <td>${value["name"]}</td>
              <td>
                <a href="http://hiphub.hipzone.co.za/admin_editbrand/${value['id']}" class="btn btn-primary btn-sm">edit</a>
                <a class="btn btn-danger btn-delete btn-sm" data-brandid=${value["id"]} href="#">delete</a>
              </td>
            </tr>
          `;
        });
        tbody.html(table_html);
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
        }).then((willDelete) => {
        
            if (willDelete) {
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
            }
          });
      });
	



    </script>



@stop