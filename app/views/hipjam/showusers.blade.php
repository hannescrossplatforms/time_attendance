@extends('layout')

@section('content')

  <body class="hipJAM">

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">User Management</h1>
          <form role="form">
                      <div class="form-group">
                        <input class="form-control" id="exampleInputEmail1" placeholder="start typing user name" type="email">
                      </div>
                    </form>
                    <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Full Name</th>
                          <th>Email</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Laurie Phillips</td>
                          <td>lauriep@gmail.com</td>
                          <td><a class="btn btn-default btn-sm" href="{{ url('hipjam_adduser'); }}">Edit</a> <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                        </tr>
                        <tr>
                          <td>Veronica Nelson</td>
                          <td>veronican@yahoo.co.uk</td>
                          <td><a class="btn btn-default btn-sm" href="hipjam_adduser">Edit</a> <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                        </tr>
                        <tr>
                          <td>Luke Dixon</td>
                          <td>lukeyd@hotmail.co.za</td>
                          <td><a class="btn btn-default btn-sm" href="hipjam_adduser">Edit</a> <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                        </tr>
                      </tbody>
                    </table>
          </div>
          <a href="hipjam_adduser" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
        

        </div>

      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/prefixfree.min.js"></script> 

  </body>
@stop
