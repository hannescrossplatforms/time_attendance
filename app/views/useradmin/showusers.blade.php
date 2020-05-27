@extends('angle_admin_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>User Admin</div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              All Users

            </div>
          </div>
          <div class="card-body">
          <a id="buildtable"></a>
            <div class="row">
              <div class="col-12">
                <form action=" {{ url('useradmin_showusers'); }}"  class="form-inline" role="form" style="margin-bottom: 15px;" >
                <div class="form-group">
                  <label class="sr-only">Full Name</label>
                  <input class="form-control" id="fullname" placeholder="Full Name" name="fullname">
                </div>
                <div class="form-group">
                  <label class="sr-only">Email Address</label>
                  <input class="form-control" id="email" placeholder="Email Address" name="email">
                </div>
                <button type="submit" class="btn btn-info" name="filter" value="on">Filter</button>
                <button type="submit" class="btn btn-warning" name="filter" value="off">Reset</button>
              
                <a href="{{ url('useradmin_add'); }}" class="btn btn-primary" style="float: right"><i class="fa fa-plus"></i> Add User</a>
            
              </form>
              <br />
              <div class="col-12">
                <table id="userTable" class="table table-striped">
                <thead>
                    <tr>
                      <th>Full Name</th>
                      <th>Email Address</th>
                      <th>Access Level</th>
                      <th>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="user_table_body"></tbody>
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

      let usersJason = {{$data['usersJason']}};

      // $(function() {
      //   $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      // });
      // showUsersTable(usersJason);
      // $(document).delegate('#buildtable', 'click', function() {
      //   showUsersTable(usersJason);
      // });

      function showUsersTable(usersjson) {
        let table_html = '';
        let tbody = $('#user_table_body');
        debugger;
        $.each(usersjson, function() {
          debugger;
          $.each(this, function(name, value) {
            table_html += `
            <tr>
              <td>${value["fullname"]}</td>
              <td>${value["email"]}</td>
              <td>${value["level_name"]}</td>
              <td>
                <a href="http://hiphub.hipzone.co.za/useradmin_edit/${value['id']}" class="btn btn-primary btn-sm">edit</a>
                <a class="btn btn-danger btn-delete btn-sm" data-userid='${value["id"]}' href="#">delete</a>
              </td>
            </tr>
            `
          });
        });

        tbody.html(table_html);
      }
      showUsersTable(usersJason);

      $(document).delegate('.btn-delete', 'click', function() {
        var userid = this.getAttribute('data-userid');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this user?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, delete it!',
          closeOnConfirm: false,
          //closeOnCancel: false
        },
          function(){
            swal("Deleted!", "User has been deleted!", "success");
            $.ajax({
              type: "GET",
              dataType: 'json',
              contentType: "application/json",
              url: "{{ url('useradmin_delete/" + userid + "'); }}",
              success: function(users) {
                var usersjson = JSON.parse(users); 
                showUsersTable(usersjson);
              }
            });
          });
      });
        
      $('.btn-sms').click(function(){
        swal({
          title: "Are you sure?",
          text: "An SMS will be Re-Sent to the user?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: '#428bca',
          confirmButtonText: 'Yes, send it!',
          closeOnConfirm: false,
          //closeOnCancel: false
        },
        function(){
          swal("SMS Sent!", "SMS has been sent to user!", "success");
        });
      });
      
    </script>
    
  </body>

@stop