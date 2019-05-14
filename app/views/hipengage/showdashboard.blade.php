@extends('layout')

@section('content')

  <body class="hipENGAGE">

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">Dashboard</h1>
          <div class="row">
              <div class="col-md-12">
                  <h3 class="mod-title">At a Glance</h3>
                    <div class="row">
                      <div class="col-md-4">
                          <div class="modStat">
                                <h3>Active Campaigns</h3>
                                <span>72</span>
                                <p>21 Loaded</p>
                            </div>
                            <div class="modStat">
                                <h3>Total App Users</h3>
                                <span>1939</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="modStat">
                                        <h3>Push Notifications</h3>
                                        <span>56</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="modStat">
                                <h3>Email Engagements</h3>
                                <span>0</span>
                            </div>
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="modStat">
                                <h3>Event Triggers</h3>
                                <span>8021</span>
                            </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="modStat">
                                <h3>SMS</h3>
                                <span>5146</span>
                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
