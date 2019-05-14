@extends('layout')

@section('content')

  <body class="hipENGAGE">

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">Campaign Management</h1>
          <div class="row">
              <div class="col-md-12">
                  <h3 class="mod-title">At a Glance</h3>
                    <div class="row">
                      <div class="col-md-4">
                          <div class="modStat">
                                <h3>Active Campaigns</h3>
                                <span>21</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="modStat">
                                <h3>Event Triggers</h3>
                                <span>8021</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="modStat">
                              <ul>
                                  <li><em>72</em> Campaigns Loaded</li>
                                    <li><em>18</em> Campaigns Ending this month</li>
                                    <li><em>15</em> Campaigns Starting next month</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                  <h3 class="mod-title">Campaigns</h3>
                    <div class="table-responsive table-campaignManagement">
              <table id="brandManagementTable" class="table table-striped table-condensed">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Type</th>
                      <th>Targeting</th>
                      <th>Start</th>
                      <th>End</th>
                      <th>Active</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Autumn Starter</td>
                      <td>Ext | Int</td>
                      <td>South Africa/Western Province</td>
                      <td>01/03/2015</td>
                      <td>31/03/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Autumn Starter</td>
                      <td>Ext | Int</td>
                      <td>South Africa/Gauteng/Pretoria</td>
                      <td>01/03/2015</td>
                      <td>31/03/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Autumn Starter</td>
                      <td>Ext | Int</td>
                      <td>South Africa/Gauteng/Johannesburg</td>
                      <td>01/03/2015</td>
                      <td>31/03/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Window Display A</td>
                      <td>Int</td>
                      <td>South Africa/Gauteng/Durban/Umhla214</td>
                      <td>01/03/2015</td>
                      <td>7/03/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Autumn starter</td>
                      <td>Int</td>
                      <td>South Africa/Gauteng/Durban</td>
                      <td>01/03/2015</td>
                      <td>31/04/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Homeware Special A</td>
                      <td>Int</td>
                      <td>South Africa</td>
                      <td>01/03/2015</td>
                      <td>31/04/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Homeware Special A</td>
                      <td>Int</td>
                      <td>South Africa</td>
                      <td>01/03/2015</td>
                      <td>31/04/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Window Display Autumn</td>
                      <td>Int</td>
                      <td>South Africa/Gauteng/Durban/Umhla214</td>
                      <td>01/04/2015</td>
                      <td>31/04/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Tefal 10%</td>
                      <td>Ext | Int</td>
                      <td>South Africa</td>
                      <td>01/03/2015</td>
                      <td>15/04/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Gardening (Equip.) </td>
                      <td>Ext | Int</td>
                      <td>South Africa</td>
                      <td>15/04/2015</td>
                      <td>30/04/2015</td>
                      <td class="text-center"><i class="fa fa-check-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                    <tr>
                      <td>Homeware Special B</td>
                      <td>Int</td>
                      <td>South Africa</td>
                      <td>15/04/2015</td>
                      <td>30/04/2015</td>
                      <td class="text-center"><i class="fa fa-times-circle"></i></td>
                      <td><a class="btn btn-default btn-sm" href="{{ url('hipengage_addcampaign'); }}">edit</a>
                      <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <a href="{{ url('hipengage_addcampaign'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Campaign</a>
                </div>
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
