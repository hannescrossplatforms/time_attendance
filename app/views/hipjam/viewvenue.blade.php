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

                    <!-- <a href="{{ URL::previous() }}">
                        << Back</a> -->
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
                                                                    @if ($data['venue_type'] == 'billboard')
                                                                        <h3>Individuals Exposed Current</h3>
                                                                    @else
                                                                        <h3>Customers In Store Now</h3>
                                                                    @endif
                                                                </div>
                                                                <div id="live_customer_now" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                @if ($data['venue_type'] == 'billboard')
                                                                        <h3>Individuals Exposed Today</h3>
                                                                    @else
                                                                        <h3>Customers In Store Today</h3>
                                                                    @endif
                                                                    
                                                                </div>
                                                                <div id="live_customer_today" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    @if ($data['venue_type'] == 'billboard')
                                                                        <h3>Uniques Current</h3>
                                                                    @else
                                                                        <h3>New Customers Now</h3>
                                                                    @endif
                                                                </div>
                                                                <div id="live_new_now" class="modStatspan">loading...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    @if ($data['venue_type'] == 'billboard')
                                                                        <h3>Uniques Today</h3>
                                                                    @else
                                                                        <h3>New Customers Today</h3>
                                                                    @endif
                                                                    
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
                                                                        @if ($data['venue_type'] == 'billboard')
                                                                            <h3>Exposed to Billboard Today</h3>
                                                                        @else
                                                                            <h3>Conversions Today</h3>
                                                                        @endif
                                                                    @else
                                                                        <h3>Window Conversion Today</h3>
                                                                    @endif
                                                                </div>
                                                                <div id="live_window_today" class="modStatspan">{{$data['exposed_today'][0]->count}}
                                                                    <!-- <span style="font-size: 35%;">Data Not Available</span> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row" id="report_date_filter">
                                                    <div class="col-md-4" style="width:30%;">
                                                        <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                                            <label>Report Period</label>
                                                        </div>
                                                        <div class="col-md-4 text-center">
                                                            <select id="date_select" class="form-control changable-filter" style="width: 150px;">
                                                            <option value="yesterday">Yesterday</option>
                                                            <option value="today">Today</option>
                                                            <option value="this_week">This Week</option>
                                                            <option value="last_week">Last Week</option>
                                                            <option value="this_month">This Month</option>
                                                            <option value="last_month">Last Month</option>
                                                            <option value="custom">Custom</option>
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
                                                                    @if ($data['venue_type'] == 'billboard')
                                                                        <h3>Individuals Exposed</h3>
                                                                    @else
                                                                        <h3>Customers In Store</h3>
                                                                    @endif
                                                                </div>
                                                                <div id="rep_customer" class="modStatspan">0</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    @if ($data['venue_type'] == 'billboard')
                                                                        <h3>Uniques</h3>
                                                                    @else
                                                                        <h3>New Customers In Store</h3>
                                                                    @endif
                                                                </div>
                                                                <div id="new_rep_customer" class="modStatspan">0</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    

                                                    @if ($data['venue_type'] == 'billboard')
                                                        <div class="col-md-3" style="width: 20%;"></div>
                                                    @else
                                                    <div class="col-md-3" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                        @if ($data['venue_type'] == 'billboard')
                                                                            <h3>Engaged Customers</h3>
                                                                        @else
                                                                            <h3>High Dwell Customers</h3>
                                                                        @endif
                                                                    
                                                                </div>
                                                                <div id="engaged_customers" class="modStatspan">0</div>
                                                                <!-- <div id="engaged_customer" class="modStatspan"><span style="font-size: 35%;">Data Not Available</span></div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if ($data['venue_type'] == 'billboard')
                                                        <div class="col-md-3" style="width: 20%;"></div>
                                                    @else
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
                                                    @endif


                                                    

                                                    <div class="col-md-3" style="width: 20%;">
                                                        <div class="venuerow">
                                                            <div class="modStat">
                                                                <div class="modstattitle">
                                                                    @if (\User::isVicinity())
                                                                        @if ($data['venue_type'] == 'billboard')
                                                                            <h3>Exposed to Billboard</h3>
                                                                        @else
                                                                            <h3>Total Conversions</h3>
                                                                        @endif
                                                                    @else
                                                                    <h3>Window Conversion </h3>
                                                                    @endif
                                                                </div>
                                                                <div id="window_con" class="modStatspan">{{$data['exposed_week'][0]->count}}
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
                                                            <div class="chart-title venuecolheading">Store Traffic by Hour<sup>*</sup></div>
                                                            <div class="chart-stage">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="chart-stage">
                                                                            <div id="chart-container">Loading...</div>
                                                                        </div>
                                                                        <small><sup>*</sup> Chart totals may differ from in store today customers because individual dwell time spans over mutiple hours.</small>
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
                                                                            <div id="chart-07">Charts will render here</div>
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
                            <input id="hf_venue_type" type="hidden" value="{{$data['venue_type']}}" />
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
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
            var is_billboard = $('#hf_venue_type').val() === 'billboard';

            function get_query_string_key(key) {
                key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
                var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
                return match && decodeURIComponent(match[1].replace(/\+/g, " "));
            }

            function remove_future_dates(parsed_date) {
                let current_date = moment().format('YYYY-MM-DD');
                return moment(parsed_date).isBefore(current_date) || current_date === parsed_date;
            }

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

                    this.db = firebase.firestore()
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
                            // $('#live_window_today').html('0');

                            if (is_billboard) {
                                $('#live_window_today').html(venue_data.customers_in_store_today);
                            }

                            
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

            liveJam.getVenueDataWeek = () => {

            }
            liveJam.compileNodeArray = (span) => {
                let start = moment().startOf(span);
                let end = moment().endOf(span);

                let date_filter = get_query_string_key('date_from');
                if (date_filter !== '' && date_filter !== null && date_filter !== undefined) {
                    start = moment(date_filter);
                    end = moment(get_query_string_key('date_to'));
                }

                let date_array = [];

                // Multi Day i.e. this week / this month etc.
                while (start.format('YYYY-MM-DD') !== end.format('YYYY-MM-DD')) {
                    date_array.push(start.format('YYYY-MM-DD'))
                    start.add(1, 'days');
                }
                
                // Add last day
                date_array.push(start.format('YYYY-MM-DD'))
                return date_array;
            }

            liveJam.retrieveNode = (node) => {
                 return venue.doc(node).get()
                    .then((doc) => {
                        if (doc.exists) {
                            return {
                                date: node,
                                data: doc.data()
                            }
                        } else {
                            return {
                                date: node,
                                data: {
                                    average_dwell: 0,
                                    customers_in_store_now: 0,
                                    customers_in_store_today: 0,
                                    last_seen: 0,
                                    new_customers_now: 0,
                                    new_customers_today: 0,
                                    type: ''
                                }
                            }
                        }
                    })
                    .catch(function(error) {
                        return {
                            date: node,
                            data: {
                                average_dwell: 0,
                                customers_in_store_now: 0,
                                customers_in_store_today: 0,
                                last_seen: 0,
                                new_customers_now: 0,
                                new_customers_today: 0,
                                type: ''
                            }
                        }
                    })
            }

            liveJam.compileData = (span, callback) => {
                let nodes = liveJam.compileNodeArray(span);
                let week_data_promises = [];
                let week_data = [];
                $.each(nodes, function(i, node) {
                    console.log('GETTING DATA FOR: ' + node);
                    week_data_promises.push(liveJam.retrieveNode(node));
                });
                Promise.all(week_data_promises).then(function() {
                    callback(arguments[0]);
                })
            }

            liveJam.sortArray = (array) => {
                array.sort(function(a, b){
                    var nameA=a.date.toLowerCase(), nameB=b.date.toLowerCase()
                    if (nameA < nameB)
                        return -1 
                    if (nameA > nameB)
                        return 1
                    return 0
                })
            }

            liveJam.sortArrayByKey = (array, key) => {
                return array.sort(function (a, b) {
                    var x = a[key]; var y = b[key];
                    return ((x < y) ? -1 : ((x > y) ? 1 : 0));
                });
            }

            liveJam.graphSerializer = (raw_data) => {
                raw_data.sort((a, b) => a.date.localeCompare(b.date));
                return $.map( raw_data, function( d, i ) {
                    return {label: d.date, value: d.data.customers_in_store_today}
                });
            }

            liveJam.renderStoreTrafficTrendGraph = (data) => {
                var chartProperties = {
                "caption": "OVERALL STORE TRAFFIC TREND",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'line',
                renderAt: 'chart-05',
                width: '100%',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": liveJam.graphSerializer(data)

                }
            });
            apiChart.render();
            }

            liveJam.renderStoreWeekTrafficGraph = (data) => {
                var chartProperties = {
                "caption": "STORE TRAFFIC THIS WEEK",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'line',
                renderAt: 'chart-07',
                width: '100%',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": liveJam.graphSerializer(data)

                }
            });
                apiChart.render();
            }

            liveJam.renderNewVsReturningGraph = (data) => {
                let dateCat = $.map( data, function( d, i ) {
                    return {label: d.date}
                });
            

                let new_data_set = $.map( data, function( d, i ) {
                    return {value: d.data.new_customers_today}
                });

                let new_data_set_full = {seriesname: "New", data: new_data_set}

                let returning_data_set = $.map( data, function( d, i ) {
                    return {value: d.data.customers_in_store_today - d.data.new_customers_today}
                });

                let returning_data_set_full = {seriesname: "Returning", data: returning_data_set}
                

                var chartProperties = {
                "caption": "OVERALL STORE TRAFFIC TREND",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'msline',
                    renderAt: 'chart-06',
                    width: '100%',
                    height: '350',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{category: dateCat}],
                        "dataset": [new_data_set_full, returning_data_set_full]
                    }
                });
                apiChart.render();
            }

            liveJam.renderHourlyTrafficGraph = (data) => {
                let node = `${moment().format('YYYY-MM-DD')}/hourly`;

                let filters = get_presets();
                if (filters === null) {
                    filters = {date_from: moment().startOf('week'),
                                date_to: moment().endOf('week')}
                }
                let start = moment(filters.date_from);
                let end = moment(filters.date_to);
                let date_array = [];

                // Multi Day i.e. this week / this month etc.
                while (start.format('YYYY-MM-DD') !== end.format('YYYY-MM-DD')) {
                    date_array.push(start.format('YYYY-MM-DD'));
                    start.add(1, 'days');
                }
                
                // Add last day
                date_array.push(start.format('YYYY-MM-DD'))

                // console.log(`--- BEFORE ---`)
                // console.log(date_array);

                date_array = date_array.filter(remove_future_dates);

                // console.log(`--- AFTER ---`)
                // console.log(date_array);

                let data_promises = [];
                $.each( date_array, function( key, node ) {
                    data_promises.push(db.collection(`{{$data['venue_id']}}/${node}/hourly`).get());
                });

                let hourly_averages = {};
                Promise.all(data_promises).then(function(doc) {
                    doc.forEach(function(parent) {
                        parent.docs.forEach(function(hours) {
                            if (!hourly_averages.hasOwnProperty(hours.id)) {
                                hourly_averages[hours.id] = parseInt(hours.data().customers);
                            } else {
                                hourly_averages[hours.id] = hourly_averages[hours.id] + parseInt(hours.data().customers);
                            }
                        }); 
                    });
                    hourly_averages = $.map( hourly_averages, function( obj, key ) {
                        // console.log(`${key} (${hourly_averages.length}): ${obj} / ${data_promises.length} = ${obj / data_promises.length}`)
                        return {label: key, value: Math.ceil(obj / data_promises.length)};
                    });

                    

                    var chartProperties = {
                            "caption": "Store Traffic by Hour",
                            "xAxisName": "Hour",
                            "yAxisName": "Customers",
                            "rotatevalues": "1",
                            "theme": "zune"
                        };

                        apiChart = new FusionCharts({
                            type: 'line',
                            renderAt: 'chart-container',
                            width: '100%',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "data": hourly_averages
                            }
                        });
                        apiChart.render();
                });
               
            }

            liveJam.initialize(() => {
                liveJam.getVenueData();
                liveJam.compileData('week', function (week_data) { 
                    var time_since_opening = moment.duration(moment().diff(moment('2019-11-21 08:00:00'))).asMinutes();
                    var customers_in_store = 0
                    var new_customers_in_store = 0;
                    var dwell = 0; 
                    var dwell_words = '';
                    var exposed_to_billboard = 0;
                    var high_dwell_customers = 0;
                    
                    $.each(week_data, function(index, item) {
                        dwell += item.data.average_dwell;
                        customers_in_store += item.data.customers_in_store_today;
                        new_customers_in_store += item.data.new_customers_today;
                        high_dwell_customers += item.data.high_dwell_customers || 0;
                        exposed_to_billboard = 0;
                    });
                    dwell = (dwell / week_data.length) / 60
                    $('#rep_customer').html(customers_in_store);
                    $('#new_rep_customer').html(new_customers_in_store);
                    $('#engaged_customers').html(high_dwell_customers);
                    if (dwell === null || dwell === undefined || dwell === '' || dwell.toString() === 'NaN') {
                        $('#rep_ave').html('0');
                    } else {
                        $('#rep_ave').html(Math.round(dwell));
                    }
                    
                    if (is_billboard)
                        $('#window_con').html(customers_in_store);
                    else
                        // $('#window_con').html('0');

                    liveJam.renderStoreTrafficTrendGraph(week_data);
                    liveJam.renderHourlyTrafficGraph(week_data);
                    liveJam.renderNewVsReturningGraph(week_data);
                    liveJam.renderStoreWeekTrafficGraph(week_data);
                });

            })

            function get_query_string_key(key) {
                key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
                var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
                return match && decodeURIComponent(match[1].replace(/\+/g, " "));
            }

            function get_presets() {
                let brand_id = get_query_string_key('brand_id');
                if (brand_id === '' || brand_id === null || brand_id === undefined)
                    return null
                return { brand_id: get_query_string_key('brand_id'), type: get_query_string_key('type'), span: get_query_string_key('span'),  date_from: get_query_string_key('date_from'), date_to: get_query_string_key('date_to') }
            }
        </script>

        <script>
            if (window.location !== window.parent.location) {
                $('.sidebar').remove();
                $('.main').attr('style', 'width: 100% !important; margin: 0 !important;');
                $('#report_date_filter').remove();
            }
        </script>
<script>
load_presets();
    getFilters(false);

    $(document).on('change', '.changable-filter', function() {
      getFilters(true);
    })

    function load_presets() {
      let presets = get_presets();
      if (presets !== null) {
        $('#sub_brand_select').val(presets.brand_id);
        $('#type_select').val(presets.type);
        $('#date_select').val(presets.span);
      } else {
        $('#date_select').val('this_week');
      }
    }

    function getFilters(must_redirect) {
      let brand_id = $('#sub_brand_select').val();
      let type = $('#type_select').val();
      let span = $('#date_select').val();
      let date_from = generate_date_from();
      let date_to = generate_date_to();
      
      if (must_redirect)
        window.location.href = `http://hiphub.hipzone.co.za/hipjam_viewvenue/{{$data['venue_id']}}/tracks03.hipzone.co.za${generate_query_string(brand_id, type, span, date_from, date_to)}`
    }

    function generate_date_from() {
      let selected_date_span = $('#date_select').val();
      let today = moment();
      switch(selected_date_span) {
        case 'yesterday':
          return today.subtract(1, 'day').format('YYYY-MM-DD');
          break;
        case 'today':
          return today.format('YYYY-MM-DD')
          break;
        case 'this_week':
          return today.startOf('week').format('YYYY-MM-DD');
          break;
        case 'last_week':
          return today.startOf('week').subtract(1, 'day').startOf('week').format('YYYY-MM-DD');
          break;
        case 'this_month':
          return today.startOf('month').format('YYYY-MM-DD');
          break;
        case 'last_month':
          return today.startOf('month').subtract(1, 'day').startOf('month').format('YYYY-MM-DD');
          break;
        default:
          return today.startOf('week').format('YYYY-MM-DD');
      }
    }

    function generate_date_to() {
      let selected_date_span = $('#date_select').val();
      let today = moment();
      switch(selected_date_span) {
        case 'yesterday':
          return today.subtract(1, 'day').format('YYYY-MM-DD');
          break;
        case 'today':
          return today.format('YYYY-MM-DD')
          break;
        case 'this_week':
          return today.endOf('week').format('YYYY-MM-DD');
          break;
        case 'last_week':
          return today.startOf('week').subtract(1, 'day').endOf('week').format('YYYY-MM-DD');
          break;
        case 'this_month':
          return today.endOf('month').format('YYYY-MM-DD');
          break;
        case 'last_month':
          return today.startOf('month').subtract(1, 'day').endOf('month').format('YYYY-MM-DD');
          break;
        default:
          return today.endOf('week').format('YYYY-MM-DD');
      }
    }

    function generate_query_string(brand_id, type, span, date_from, date_to) {
      return `?brand_id=${brand_id}&type=${type}&span=${span}&date_from=${date_from}&date_to=${date_to}`;
    }

    function get_query_string_key(key) {
      key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
      var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
      return match && decodeURIComponent(match[1].replace(/\+/g, " "));
    }

    function get_presets() {
      let brand_id = get_query_string_key('brand_id');
      if (brand_id === '' || brand_id === null || brand_id === undefined)
        return null
      return { brand_id: get_query_string_key('brand_id'), type: get_query_string_key('type'), span: get_query_string_key('span'),  date_from: get_query_string_key('date_from'), date_to: get_query_string_key('date_to') }
    }
</script>

</body>

@stop