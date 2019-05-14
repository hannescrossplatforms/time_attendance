@extends('layout')

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Dashboard</h1>
          <div class="row">
              <div class="col-md-4">
                  <h3 class="mod-title">At a Glance</h3>
                  <div class="modStat">
                      <h3>Insights this month</h3>
                      <span>1854</span>
                    </div>
                  <div class="modStat">
                      <h3>Active Questions</h3>
                      <span>56</span>
                        <p>522 Loaded</p>
                    </div>
                </div>
                <div class="col-md-8">
                  <h3 class="mod-title">Venues</h3>
                    <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th class="text-center">Demographic</th>
                          <th class="text-center">Active</th>
                          <th class="text-center">Insights</th>
                          <th>&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Kauai</td>
                          <td class="text-center">8</td>
                          <td class="text-center">4</td>
                          <td class="text-center">4</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                        <tr>
                          <td>Cove</td>
                          <td class="text-center">8</td>
                          <td class="text-center">2</td>
                          <td class="text-center">2</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                        <tr>
                          <td>Protea Hotels</td>
                          <td class="text-center">8</td>
                          <td class="text-center">8</td>
                          <td class="text-center">8</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                        <tr>
                          <td>Leisure Hotels</td>
                          <td class="text-center">0</td>
                          <td class="text-center">0</td>
                          <td class="text-center">0</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                        <tr>
                          <td>Independant</td>
                          <td class="text-center">8</td>
                          <td class="text-center">0</td>
                          <td class="text-center">0</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                        <tr>
                          <td>Pernod</td>
                          <td class="text-center">2</td>
                          <td class="text-center">2</td>
                          <td class="text-center">2</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                        <tr>
                          <td>Heineken</td>
                          <td class="text-center">4</td>
                          <td class="text-center">2</td>
                          <td class="text-center">2</td>
                          <td><a class="btn btn-default btn-sm" href="#" data-toggle="modal" data-target="#statsModal">View Stats</a></td>
                        </tr>
                      </tbody>
                    </table>
          </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    
         <!-- Page Modals
    ================================================== -->
    
    <!-- Stats Modal -->
    <div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">Kauai | Question Statistics</h6>
          </div>
          <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th class="text-center">Questions</th>
                          <th class="text-center">Sample Size</th>
                          <th class="text-center">Completed</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="text-center">8</td>
                          <td class="text-center">4</td>
                          <td class="text-center">4</td>
                        </tr>
                        <tr>
                          <td class="text-center">8</td>
                          <td class="text-center">2</td>
                          <td class="text-center">2</td>
                        </tr>
                        <tr>
                          <td class="text-center">8</td>
                          <td class="text-center">8</td>
                          <td class="text-center">8</td>
                        </tr>
                        <tr>
                          <td class="text-center">0</td>
                          <td class="text-center">0</td>
                          <td class="text-center">0</td>
                        </tr>
                      </tbody>
                    </table>
          </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
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
