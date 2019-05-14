@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Add Admin</h1>
      <form role="form">
              <div class="form-group">
                <label for="exampleInputEmail1">Full Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
              </div>
              <div class="panel panel-default">
                  <div class="panel-heading">Brands</div>
                  <div class="panel-body">
  
                  <div class="form-inline">
                      <div class="form-group">
                            <select class="form-control">
                              <option>Select Brand</option>
                              <option>Kauai</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                      <div class="form-group">
                        <select class="form-control">
                          <option>Select Country</option>
                          <option>South Africa</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select>
                      </div>
                      <button class="btn btn-default">Add Brand</button>
                    </div>
                    </div>
                    
              
                </div>
        <div class="panel panel-default">
                  <div class="panel-heading">Permissions</div>
                  <div class="panel-body">
                    <div class="checkbox">
                  <label>
                    <input type="checkbox" value="">
                    Add/remove Questions
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="">
                    Manage media backgrounds
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="">
                    Change user directed URL
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="">
                    Access Reports Server
                  </label>
                </div>
                  </div>
                </div>
              <br>
              
            <a href="hipRM_userManagement.html" class="btn btn-primary btn-lg">Submit</a>
            <a href="hipRM_userManagement.html" class="btn btn-default  btn-lg">Cancel</a>
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
