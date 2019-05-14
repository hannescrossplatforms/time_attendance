@extends('layout')

@section('content')


  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')
        
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Admin Management</h1>
            <a id="buildtable"></a> 
			<form role="form">
                      <div class="form-group">
                        <input type="email" class="form-control input-lg" id="exampleInputEmail1" placeholder="start typing full name, username or email address to filter">
                      </div>
                    </form>
            <div class="table-responsive">
                    <table id="userTable" class="table table-striped"> </table>
          </div>
          <a href="{{ url('hiprm_adduser'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Admin</a>
	
          
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
	
		$('.btn-delete').click(function(){
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
			});
		});
    	

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
                      <td> ' + value["level_code"]  + '</td>\n\
                      <td><a href="{{ url('hiprm_edituser'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
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
              url: "{{ url('useradmin_delete/" + userid + "'); }}",
              success: function(brands) {
                var brandsjson = JSON.parse(brands); 
                showUsersTable(brandsjson);
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
</html>
