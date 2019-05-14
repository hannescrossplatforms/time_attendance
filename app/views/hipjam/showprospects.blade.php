@extends('layout')

@section('content')

  <body class="hipJAM">

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">Prospects Register</h1>
          <form role="form">
              <div class="form-group">
                <input class="form-control" id="exampleInputEmail1" placeholder="start typing prospect name to filter" type="email">
              </div>
            </form>
            <div class="table-responsive">
              <table id="brandManagementTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Reseller Name</th>
                      <th>Prospect Name</th>
                      <th>Registration Date</th>
                      <th class="text-center">Contact</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Bo Bissict</td>
                      <td>Nandos</td>
                      <td>21/01/2015</td>
                      <td class="text-center">
                              <input checked="" type="checkbox">
                      </td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipjam_addprospect'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Paul Greeff</td>
                      <td>Steers</td>
                      <td>22/01/2015</td>
                      <td class="text-center"><input type="checkbox"></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipjam_addprospect'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>James Gilmour</td>
                      <td>KFC</td>
                      <td>23/01/2015</td>
                      <td class="text-center"><input type="checkbox"></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipjam_addprospect'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <a href="{{ url('hipjam_addprospect'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Prospect</a>
        

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
