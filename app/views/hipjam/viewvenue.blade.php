@extends('layout')

@section('content')

<style type="text/css">
    .modstattitle {
        /*background-color: #d3d3d3;#106f5d*/
        background-color: #277d6d;
        height: 70px;
        padding: 10px;
    }

    .modstattitle h3 {
        color: white;
    }

    #heatmapArea {
        position: relative;
        float: left;
        width: 900px;
        height: 500px;
        /*background-image:url("{{$data['fullpathimage']}}");*/
        /*background-image:url("{{url('img/heatmap.jpg')}}");*/
        /*background-size: 885px 600px;*/
        background-repeat: no-repeat;
        border: 0px dashed black;
    }


    .overlay {
        background: rgba(129, 119, 119, 0.5) none no-repeat scroll 0% 0%;
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0px;
        left: 1px;
        z-index: 1019;
        padding-left: 53%;
        padding-top: 20%;
    }
</style>

<body class="hipJAM">

    <div id="loadingDiv" class="overlay">
        <img src="/img/loader.gif" style="width:80px;">
    </div>

    <a id="buildtable"></a>

    <div class="container-fluid">
        <div class="row">

            @include('hipjam.sidebar')

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">{{$data['venue']}} </h1>

                <?php if (strpos($_SERVER['REQUEST_URI'], 'public') !== false) {
                    $pos = strpos($_SERVER['REQUEST_URI'], 'public');
                    $portion = substr($_SERVER['REQUEST_URI'], 0, $pos + 7);
                    $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
                } else {
                    $url = 'http://' . $_SERVER['SERVER_NAME'] . '/';
                }
                ?>
                <input type="hidden" id="url" name="" value="{{$url}}">
                <input type="hidden" id="apisitename" name="" value="{{$data['apisitename']}}">
                <input type="hidden" id="apivenuename" name="" value="{{$data['track_slugname']}}">
                <input type="hidden" id="apivenueid" name="" value="{{$data['apivenueid']}}">

                <div class="container-fluid">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a id="absencetab" href="#venue" aria-controls="absence" role="tab" data-toggle="tab">Store</a></li>
                        @if (!\User::isVicinity())
                        <li role="presentation" style="@if (\User::isVicinity()) {{'display: none'}} @endif "><a id="zonaltab" href="#zonal" aria-controls="lateness" role="tab" data-toggle="tab">Zonal</a></li>
                        <li role="presentation" style="@if (\User::isVicinity()) {{'display: none'}} @endif "><a id="wsproximitytab" href="#heatmap" aria-controls="wsproximity" role="tab" data-toggle="tab">
                                Heatmap
                            </a></li>
                        @endif
                    </ul>
                    <br>

                    <a href="{{ URL::previous() }}">
                        << Back</a> <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="report_period"></div>
                                <div role="tabpanel" class="tab-pane active" id="venue">

                                    <!-- <div class="container-fluid"> -->
                                    <div class="clear">
                                        <br><br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="venuecolheading">Today</div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>Customers In Store Now</h3>
                                                                </div>
                                                                <div id="live_customer_now" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>Customers In Store Today</h3>
                                                                </div>
                                                                <div id="live_customer_today" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>New Customers Now</h3>
                                                                </div>
                                                                <div id="live_new_now" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>New Customers Today</h3>
                                                                </div>
                                                                <div id="live_new_today" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    @if (\User::isVicinity())
                                                                    <h3>Exposed to Billboard Today</h3>
                                                                    @else
                                                                    <h3>Window Conversion Today</h3>
                                                                    @endif

                                                                </div>
                                                                <div id="live_window_today" class="modStatspan">loading...
                                                                    <!-- <span style="font-size: 35%;">Data Not Available</span> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4" style="width:30%;">
                                                        <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                                            <label>Report Period</label>
                                                        </div>
                                                        <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                                            <select id="brandreportperiod" class="form-control" name="reportperiod">
                                                                <!-- <option value="">Select</option> -->
                                                                <!-- <option value="rep7day">This week</option>
                                        <option value="repthismonth">This month</option>
                                        <option value="replastmonth">Last month</option>
                                        <option value="daterange">Custom range</option> -->
                                                                <option value="this_week">This week</option>
                                                                <option value="this_month">This month</option>
                                                                <option value="last_month">Last month</option>
                                                                <option value="custom">Custom range</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-8" id="custom" style="display:none; width:70%;">
                                                        <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                                                            <input type="text" class="form-control datepicker" name="venuefrom" id="venuefrom" placeholder="FromDate">
                                                        </div>
                                                        <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                                                            <input type="text" class="form-control datepicker" name="venueto" id="venueto" placeholder="ToDate">
                                                        </div>
                                                        <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                                                            <button type="submit" class="form-control" onclick="custom_report_period()">Submit Date Range</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>Customers In Store</h3>
                                                                </div>
                                                                <div id="rep_customer" class="modStatspan">0</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>New Customers In Store </h3>
                                                                </div>
                                                                <div id="new_rep_customer" class="modStatspan">0</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>Engaged Customers </h3>
                                                                </div>
                                                                <div id="engaged_customers" class="modStatspan">0</div>
                                                                <!-- <div id="engaged_customer" class="modStatspan"><span style="font-size: 35%;">Data Not Available</span></div> -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    <h3>Time Spent In Store (Avg)</h3>
                                                                </div>
                                                                <div class="modStatspan" id="rep_ave">0</div>
                                                                <!-- <div class="modStatspan" style="height:35px;font-size:200%;"><span id="rep_max" >0</span> <span style="font-size: 50%;">Max</span></div> -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    @if (\User::isVicinity())
                                                                    <h3>Exposed to Billboard</h3>
                                                                    @else
                                                                    <h3>Window Conversion </h3>
                                                                    @endif
                                                                </div>
                                                                <div id="window_con" class="modStatspan">loading...
                                                                    <!-- <span style="font-size: 35%;">Data Not Available</span> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="chart-wrapper">
                                                            <div class="chart-title venuecolheading">Overall Store Traffic Trend</div>
                                                            <div class="chart-stage">
                                                                <div id="chart-05">Charts will render here</div>
                                                            </div>
                                                            <div class="chart-notes"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-6">
                                                        <div class="chart-wrapper">
                                                            <div class="chart-title venuecolheading">Store Traffic/Hour <span id="perHperiod">Today</span></div>
                                                            <div class="chart-stage">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="chart-stage">
                                                                            <div id="chart-container">Loading...</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="chart-wrapper">
                                                            <div class="chart-title venuecolheading">Store Traffic <span id="venueTrfc">This Week</span></div>
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

                                                <br><br><br>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="chart-wrapper">
                                                            <div class="chart-title venuecolheading">Overall Store Traffic (New vs. Returning)</div>
                                                            <div class="chart-stage">
                                                                <div id="chart-06"></div>
                                                            </div>
                                                            <div class="chart-notes"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- </div> -->

                                </div>

                                <div role="tabpanel" class="tab-pane" id="zonal">
                                    <div class="row">
                                        <div class="col-md-4" style="width:30%;">
                                            <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                                <label>Report Period</label>
                                            </div>
                                            <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                                <select id="zonalreportperiod" onchange="change_zonal_report_period()" class="form-control" name="zonalreportperiod">
                                                    <!-- <option value="">Select</option> -->
                                                    <option selected="selected" value="today">Today</option>
                                                    <option value="this_week">This week</option>
                                                    <option value="this_month">This month</option>
                                                    <option value="month">Last month</option>
                                                    <option value="daterange">Custom range</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-8" id="zonecustom" style="display:none; width:70%;">
                                            <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                                                <input type="text" class="form-control datepicker" name="zonalfrom" id="zonalfrom" placeholder="FromDate">
                                            </div>
                                            <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                                                <input type="text" class="form-control datepicker" name="zonalto" id="zonalto" placeholder="ToDate">
                                            </div>
                                            <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                                                <button type="submit" class="form-control" onclick="custom_zonal_report_period()">Submit Date Range</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="zoneTable" class="table table-striped"> </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="heatmap">
                                    <div class="clear">
                                        <div class="row">
                                            <div class="col-md-4" style="width:30%;">
                                                <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                                    <label>Report Period</label>
                                                </div>
                                                <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                                    <!-- <select id="heatmapreportperiod" class="form-control" name="heatmapreportperiod" > -->
                                                    <select id="heatmapreportperiod" class="form-control" name="heatmapreportperiod">
                                                        <!-- <option value="">Select</option> -->
                                                        <option selected="selected" value="today">Today</option>
                                                        <option value="this_week">7 days</option>
                                                        <option value="this_month">This month</option>
                                                        <option value="month">Last month</option>
                                                        <option value="daterange">Custom range</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-md-8" id="heatmapcustom" style="display:none; width:70%;">
                                                <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                                                    <input type="text" class="form-control datepicker" name="heatmapfrom" id="heatmapfrom" placeholder="FromDate">
                                                </div>
                                                <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                                                    <input type="text" class="form-control datepicker" name="heatmapto" id="heatmapto" placeholder="ToDate">
                                                </div>
                                                <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                                                    <button type="submit" class="form-control" onclick="custom_heatmap_report_period()">Submit Date Range</button>
                                                </div>
                                            </div>
                                        </div>

                                        <br><br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="slidehour">
                                                        <input type="text" id="heatmaphour" value="1:00">
                                                    </div>
                                                    <br>
                                                    <div class="slidecontainer">
                                                        <input type="range" min="0" max="23" value="1" class="slider" id="heatmaphourslider" name="heatmaphourslider">
                                                        <output for="heatmaphourslider" onforminput="value = heatmaphourslider.valueAsNumber;"></output>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div id="heatmapArea">
                                                        <canvas class="modal-content" id="imgcanvas" style="align-content: center; cursor:crosshair;)"></canvas>
                                                        <!-- <img src="{{$data['fullpathimage']}}" style="display: none; " id="myImg" > -->
                                                        <img src="{{url('img/heatmap.jpg')}}" width="885" height="600" id="myImg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
                            <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

                            <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
                            <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

                            <script src="{{ asset('js/jquery_cookie.js') }}"></script>
                            <script src="{{ asset('js/fusioncharts.js') }}"></script>
                            <script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
                            <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>
                            <script src="{{ asset('js/hipjam/hipjam.js') }}"></script>
                            <script src="{{ asset('js/prefixfree.min.js') }}"></script>
                            <script type="text/javascript" src="{{ asset('js/heatmap.js') }}"></script>
                            <script type="text/javascript">
                                $("#venuefrom, #venueto, #zonalfrom, #zonalto, #heatmapfrom, #heatmapto").datepicker({
                                    format: 'yyyy-mm-dd',
                                    autoclose: true,
                                    orientation: "right bottom"
                                });
                                $("#venuefrom, #venueto").datepicker("setDate", new Date());
                            </script>
                </div>
            </div>
        </div>

        <script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-analytics.js"></script>
        <!-- <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-firestore.js"></script> -->

        <script>
            let liveJam = {};
            liveJam.initialize = (callback) => {
                $.getScript('https://www.gstatic.com/firebasejs/7.1.0/firebase-firestore.js', () => {
                    let config = {
                        apiKey: "AIzaSyDUxh-Quw0-D6V7Q2Pjcwgeco7R7x08hWw",
                        authDomain: "tracks-e61f4.firebaseapp.com",
                        databaseURL: "https://tracks-e61f4.firebaseio.com",
                        projectId: "tracks-e61f4",
                        storageBucket: "",
                        messagingSenderId: "798983478031",
                        appId: "1:798983478031:web:f81f6341211ab4dfd7bc7a",
                        measurementId: "G-9B1XXZ1MXG"
                    };
                    firebase.initializeApp(config);
                    firebase.analytics();

                    var db = firebase.firestore()
                    this.all = db.collection
                    this.venue = db.collection("{{$data['venue_id']}}");

                    callback();
                });
            };

            liveJam.getVenueData = () => {
                let current_date = new Date();
                let formatted_node = `${current_date.getFullYear()}-${('0'+(current_date.getMonth()+1)).slice(-2)}-${('0'+current_date.getDate()).slice(-2)}`
            
                venue.doc(formatted_node).get()
                .then((doc) => {
                    if (doc.exists) {
                        let venue_data = doc.data();
                        $('#live_customer_now').html(venue_data.customers_in_store_now);
                        $('#live_customer_today').html(venue_data.customers_in_store_today);
                        $('#live_new_now').html(venue_data.new_customers_now);
                        $('#live_new_today').html(venue_data.new_customers_today);
                        $('#live_window_today').html('0');
                    
                        // console.log(`DATA FROM FIREBASE: ${doc.data()}`)
                    } else {
                        $('#live_customer_now').html('No data');
                        $('#live_customer_today').html('No data');
                        $('#live_new_now').html('No data');
                        $('#live_new_today').html('No data');
                        $('#live_window_today').html('No data');
                        console.log('x> No venue found in track FireStore')
                    }
                })
                .catch(function(error) {
                    console.log("Error getting document:", error);
                })
            }

            liveJam.initialize(() => {
                liveJam.getVenueData();
            })
        </script>

</body>

@stop