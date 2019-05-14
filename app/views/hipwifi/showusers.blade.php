@extends('layout')

@section('content')

  <body class="hipWifi">

    <div class="container-fluid">
      <div class="row">
        
        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            <h1 class="page-header">Brand Administrators</h1>
            <a id="buildtable"></a> <!-- Needed to trigger click to build table -->
            <form action=" {{ url('hipwifi_showusers'); }}"  class="form-inline" role="form" style="margin-bottom: 15px;" >
              <div class="form-group">
                <label class="sr-only">Full Name</label>
                <input class="form-control" id="fullname" placeholder="Full Name" name="fullname">
              </div>
              <div class="form-group">
                <label class="sr-only">Email Address</label>
                <input class="form-control" id="email" placeholder="Email Address" name="email">
              </div>
              <button type="submit" class="btn btn-primary" name="filter" value="on">Filter</button>
              <button type="submit" class="btn btn-default" name="filter" value="off">Reset</button>
            </form>

            <div class="table-responsive">
              <table id="userTable" class="table table-striped"> </table>
            </div>
            <a href="{{ url('hipwifi_adduser'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/prefixfree.min.js"></script> 
    <script>

      usersJason = {{ $data['usersJason'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

      $(document).delegate('#buildtable', 'click', function() {
        showUsersTable(usersJason);
      });

      function showUsersTable(usersjson) {
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Full Name</th>\n\
                      <th>Email Address</th>\n\
                      <th>Access Level</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(usersjson, function() {
          $.each(this, function(name, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["fullname"]  + '</td>\n\
                      <td> ' + value["email"]  + '</td>\n\
                      <td> ' + value["level_name"]  + '</td>\n\
                      <td><a href="{{ url('hipwifi_edituser'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                          <a class="btn btn-default btn-delete btn-sm" data-userid = ' + value["id"] + ' href="#">delete</a>\n\
                      </td>\n\
                    </tr>\n\
                    ';
          });
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#userTable" ).html( table );
      }

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
              url: "{{ url('hipwifi_deleteuser/" + userid + "'); }}",
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