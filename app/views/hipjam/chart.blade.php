@extends('layout')

@section('content')

  <body class="hipJAM">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')


        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Venue Management</h1>

            <!-- <div class="table-responsive">
                <table id="venueTable" class="table table-striped"> </table>
            </div> -->

            <?php if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
                      $pos = strpos($_SERVER['REQUEST_URI'],'public');
                      $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                      //echo 'Car exists.';
                  } else {
                      $url = 'http://' . $_SERVER['SERVER_NAME'];
                      //echo 'No cars.';
                  }
                  echo $url;
             ?>


            <div class="container-fluid">
    <div class="row">
      <div>CUSTOMER VISITS</div>
        <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>CUSTOMERS IN STORE NOW</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h1 id="customer_now" >4</h1>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>CUSTOMERS IN STORE TODAY</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h1 id="customer_today" >253</h1>

                  </div>
              </div>
          </div>
        </div>
      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>TIME SPENT IN STORE (MIN)</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h4 ><span>15.2&nbsp; &nbsp; &nbsp;&nbsp; AVE</h4>
                    <h4 >22.5  MEDIAN</h4>
                    <h4 >47.3  &nbsp; &nbsp;&nbsp;   MAX</h4>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>NEW CUSTOMERS NOW</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h1 id="new_now">0</h1>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>NEW CUSTOMERS TODAY</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h1 id="new_today" >16</h1>

                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>NEW CUSTOMERS THIS WEEK</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h1 id="new_week">102</h1>
                  </div>
              </div>
          </div>
        </div>

      <!-- <div class="col-md-6" style="width: 14%;">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-comments fa-5x"></i>
              </div>
              <div class="col-xs-9 text-right">
                <div class="huge">26</div>
                <div>New Comments!</div>
              </div>
            </div>
          </div>
          <a href="#">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right">
                <i class="fa fa-arrow-circle-right"></i>
              </span>
              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div> -->

    </div>
    <div class="row">

      <div class="col-sm-12">
        <div class="chart-wrapper">
          <div class="chart-title">
            OVERALL STORE TRAFFIC TREND
          </div>
          <div class="chart-stage">
            <div id="chart-05">Charts will render here</div>
          </div>
          <div class="chart-notes">
          </div>
        </div>
      </div>
    </div>
    <div class="row">

        <div class="col-sm-6">
          <div class="chart-wrapper">
              <div class="chart-title">STORE TRAFFIC BY HOUR</div>
            <div class="chart-stage">
                <div class="tab-content">
                  <div id="chart-container">Loading...</div>
                </div>
            </div>
        </div>
        </div>


        <div class="col-sm-6">
          <div class="chart-wrapper">
              <div class="chart-title">STORE TRAFFIC THIS WEEK</div>
              <div class="chart-stage">
                <div class="row">
                    <div class="col-sm-12">

                      <div class="chart-stage">
                          <div id="date_week">Charts will render here</div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="row">
    <div>COMPARITIVE ANALTYICS </div>

      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>OVERALL TRAFFIC ENTRY VS PREVIOUS PERIOD</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h3 >2015</h3>
                    <h4>1845</h4>
                    <h5 >20%</h5>
                  </div>
              </div>
          </div>
        </div>
      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>NEW CUSTOMERS VS PREVIOUS PERIOD</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h3 >102</h3>
                    <h4>85</h4>
                    <h5 >15.5%</h5>
                  </div>
              </div>
          </div>
        </div>
      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>RETURNING CUSTOMERS VS PREVIOUS PERIOD</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h3 >256</h3>
                    <h4>262</h4>
                    <h5 >3 %</h5>
                  </div>
              </div>
          </div>
        </div>
      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>AVERAGE TIME SPENT IN STORE VS PREVIOUS PERIOD</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h3 >15.2</h3>
                    <h4>30.4</h4>
                    <h5 >50 %</h5>
                  </div>
              </div>
          </div>
        </div>
      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>WINDOW CONVERSION %</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h3 >14%</h3>
                    <h4>8%</h4>
                    <h5 >50%</h5>
                  </div>
              </div>
          </div>
        </div>
      <div class="col-md-6" style="width: 16%;">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 text-center fa-comments" style="height:60px;">
                      <div>VISITS TO TILL POINT %</div>
                    </div>
                </div>
              </div>
              <div class="panel-footer" style="height:120px; padding:0px 0px 0px 0px;">

                  <div class="container text-center" style="width:167px; padding:0px 0px 0px 0px;">
                    <h3 >54%</h3>
                    <h4>67%</h4>
                    <h5 >25%</h5>
                  </div>
              </div>
          </div>
        </div>
    </div>
    <div class="row">

      <div class="col-sm-12">
        <div class="chart-wrapper">
          <div class="chart-title">
            OVERALL STORE TRAFFIC (NEW vs. RETURNING) – SHADE WEEKENDS/ SHOW PREDICTIVE AVE
          </div>
          <div class="chart-stage">
            <div id="chart-06"></div>
          </div>
          <div class="chart-notes">
          </div>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-sm-6">
        <div class="chart-wrapper">
          <div class="chart-title">
            AVE STORE VISITS/HOUR (NEW vs. RETURNING)
          </div>
          <div class="chart-stage">
            <div id="chart-07"></div>
          </div>
          <div class="chart-notes">
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="chart-wrapper">
          <div class="chart-title">
            AVE STORE VISITS/DAY (NEW vs. RETURNING)
          </div>
          <div class="chart-stage">
            <div id="chart-08"></div>
          </div>
          <div class="chart-notes">
          </div>
        </div>
      </div>

    </div>
    <div class="row">

      <div class="col-sm-6">
        <div class="chart-wrapper">
          <div class="chart-title">
            VISIT DURATION (NEW vs. RETURNING)
          </div>
          <div class="chart-stage">
            <div id="chart-05"></div>
          </div>
          <div class="chart-notes">
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="row">
           <div class="col-md-6" style="width: 33%;">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-xs-3">
                      <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <div class="huge">26</div>
                      <div>New Comments!</div>
                    </div>
                  </div>
                </div>
                <a href="#">
                  <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right">
                      <i class="fa fa-arrow-circle-right"></i>
                    </span>
                    <div class="clearfix"></div>
                  </div>
                </a>
              </div>
            </div>
            <div class="col-md-6" style="width: 33%;">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-xs-3">
                      <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <div class="huge">26</div>
                      <div>New Comments!</div>
                    </div>
                  </div>
                </div>
                <a href="#">
                  <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right">
                      <i class="fa fa-arrow-circle-right"></i>
                    </span>
                    <div class="clearfix"></div>
                  </div>
                </a>
              </div>
            </div>
            <div class="col-md-6" style="width: 33%;">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-xs-3">
                      <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <div class="huge">26</div>
                      <div>New Comments!</div>
                    </div>
                  </div>
                </div>
                <a href="#">
                  <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right">
                      <i class="fa fa-arrow-circle-right"></i>
                    </span>
                    <div class="clearfix"></div>
                  </div>
                </a>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-6" style="width: 100%;">
            <img src="" >
          </div>
        </div>
      </div>
      <!-- <div class="col-sm-6">
        <div class="chart-wrapper">
          <div class="chart-title">
            <div class="btn-group-xs">Active Users
              <button type="button" id="7days" class="btn btn-default">7 Days</button>
              <button type="button" id="14days" class="btn btn-default">14 Days</button>
              <button type="button" id="28days" class="btn btn-default">28 Days</button>
            </div>
          </div>
          <div class="chart-stage">
            <div id="map"></div>
          </div>
          <div class="chart-notes">
          </div>
        </div>
      </div> -->
    </div>

  <!-- Mapbox.js Assets -->
  <!-- <script type="text/javascript" src='https://api.tiles.mapbox.com/mapbox.js/v2.0.0/mapbox.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox.js/v2.0.0/mapbox.css' rel='stylesheet' />
  <script type="text/javascript" src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-heat/v0.1.0/leaflet-heat.js'></script> -->

  <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
  <!-- <script type="text/javascript" src="js/jquery.knob.min.js"></script>

  <script type="text/javascript" src="js/keen.min.js"></script>
  <script type="text/javascript" src="js/meta.js"></script>

  <script type="text/javascript" src="js/connected-devices.js"></script> -->


  <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
  <script src="{{ asset('js/fusioncharts.js') }}"></script>
  <script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
  <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>






        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script> -->

    <script src="{{ asset('js/prefixfree.min.js') }}"></script>



  </body>

@stop