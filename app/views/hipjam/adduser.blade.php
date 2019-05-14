@extends('layout')

@section('content')

  <body class="hipJAM">

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

      <h1 class="page-header">Add User</h1>
      <form role="form">
        <div class="form-group">
          <label for="exampleInputEmail1">Full Name</label>
          <input class="form-control" id="exampleInputEmail1" placeholder="Enter full name" type="text">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Username</label>
          <input class="form-control" id="exampleInputEmail1" placeholder="Enter username" type="text">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Email</label>
          <input class="form-control" id="exampleInputEmail1" placeholder="Enter email address" type="text">
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">Permissions</div>
          <div class="panel-body">
            <div class="form-group">
              <div class="checkbox">
                  <label>
                    <input value="" type="checkbox">
                    Store
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input value="" type="checkbox">
                    Zonal
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input value="" type="checkbox">
                    Group
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input value="" type="checkbox">
                    Marketing
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input value="" type="checkbox">
                    Operations
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input value="" type="checkbox">
                    Executive
                  </label>
                </div>
            </div>
          </div>
        </div>
        <a href="{{ url('hipjam_showusers'); }}" class="btn btn-primary">Submit</a> 
        <a href="{{ url('hipjam_showusers'); }}" class="btn btn-default">Cancel</a>
      </form>
              
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
  
    $('.btn-removeAccount').click(function(){
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to remove this brand?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, remove it!',
        closeOnConfirm: false,
        //closeOnCancel: false
      },
      function(){
        swal("Removed!", "Brand has been removed!", "success");
      });
    });
      


    </script>

  </body>
</html>
