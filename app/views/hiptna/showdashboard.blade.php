@extends('layout')

@section('content')
<style type="text/css">
.modstattitle {
    /*background-color: #d3d3d3;#106f5d*/
    background-color: #58A5DA;
    height: 70px;
    padding: 10px;
}

.modstattitle h3 {
    color: white;
}

#report_period {
    display: none;
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

<body class="hipTnA">
    <div id="loadingDiv" class="overlay">
        <img src="./img/loader.gif" style="width:80px;">
    </div>
    <a id="buildtable"></a>

    <div class="container-fluid">
        <div class="row">

            @include('hiptna.sidebar')

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">Time & Attendance Dashboard</h1>

                <?php if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
                      $pos = strpos($_SERVER['REQUEST_URI'],'public');
                      $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
                      $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
                  } else {
                      $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
                  }
             ?>
                <input type="hidden" id="url" name="" value="{{$url}}">
                <?php
    /*Session::put('nonRoster','nr'); */
    $instance = Session::get('currentInstance');
    if(!$instance) $instance = "";
    if($instance == 'NR01' || $instance == 'NR02' ){  ?>
                @include('hiptna.nr_showdashboard')
                <?php }else{ ?>


                <div class="container-fluid">

                    <div class="row">
                        <div class="venuecolheading">Staff Overview</div>

                        <!--         <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store Now</h3>
                    </div>
                    <div id="staff_now" class="modStatspan"><span style="font-size: 30%;">Data Not Available</span></div>
                </div>
            </div>
        </div>
         -->
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <h3>Staff In Store Today</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan"><span
                                            style="font-size: 30%;">Loading...</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <!-- <h3>Staff In Store This Week</h3> -->
                                        <h3>Staff Expected in Store Today</h3>
                                    </div>
                                    <!-- <div id="" class="modStatspan">{{$data['staff_week']}}</div> -->
                                    <div id="staff_expected" class="modStatspan"><span
                                            style="font-size: 30%;">Loading...</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <h3>Staff In Store This Month</h3>
                                    </div>
                                    <div id="staff_thismonth" class="modStatspan"><span
                                            style="font-size: 30%;">Loading...</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle" style="padding:4px 0px 0px 0px">
                                        <h3>Consecutive Days Without Absenteeism</h3>
                                    </div>
                                    <div id="" class="modStatspan">{{$data['cons_absent']}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle" style="padding:4px 0px 0px 0px">
                                        <h3>Consecutive Days Without Lateness</h3>
                                    </div>
                                    <div id="" class="modStatspan">{{$data['cons_lateness']}}</div>
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
                                <select id="brandreportperiod" onchange="change_report_period()" class="form-control"
                                    name="reportperiod">
                                    <!-- <option value="">Select</option> -->
                                    <option value="rep7day">This Week</option>
                                    <option value="repthismonth">This month</option>
                                    <option value="replastmonth">Last month</option>
                                    <option value="daterange">Custom range</option>
                                </select>
                            </div>
                        </div>

                        <!--        printpreview button start-->
                        <div id="printButton" class="col-md-4" style="width:30%; float: right;">
                            <button type="button" class="btn btn-primary" onclick="printpreview()">View Printable
                                Page</button>
                        </div>
                        <!--        print preview button end-->

                        <div class="col-md-8" id="custom" style="display:none; width:70%;">
                            <div class="col-md-2" style="width:25%; padding:0px 0px 0px 0px;">
                                <input type="text" class="form-control datepicker" name="venuefrom" id="venuefrom"
                                    placeholder="FromDate">
                            </div>
                            <div class="col-md-2" style="width:25%; padding:0px 0px 0px 6px;">
                                <input type="text" class="form-control datepicker" name="venueto" id="venueto"
                                    placeholder="ToDate">
                            </div>
                            <div class="col-md-2" style="width:40%; padding:0px 0px 0px 6px;">
                                <button type="submit" class="form-control" onclick="custom_report_period()">Submit Date
                                    Range</button>
                            </div>
                        </div>


                    </div>

                    <br><br>
                    <div id="fusion-chart">
                        <!-- fusioncharts starts -->
                        <div class="row">
                            <div id="report_period"><br><br>{{$data['report_period']}}</div>
                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">Absence </div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_wrk">Loading...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">Absence Trend</div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_wrk_trend">Loading...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br><br>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">Lateness </div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_ontime">Loading...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">Lateness Trend</div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_ontime_trend">Loading...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br><br>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">WS Proximity</div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_proximity">Loading...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">WS Proximity Trend</div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_proximity_trend">Loading...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br>
                    </div> <!-- fusioncharts ends -->

                    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
                    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

                    <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
                    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

                    <script src="{{ asset('js/fusioncharts.js') }}"></script>
                    <script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
                    <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>

                    <script type="text/javascript">
                    $(function() {
                        availableInstances = "{{ Session::get('availableInstances') }}";
                        currentInstance = "{{ Session::get('currentInstance') }}";
                    });
                    </script>

                    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
                    <script src="{{ asset('js/prefixfree.min.js') }}"></script>
                    <script type="text/javascript">
                    $("#venuefrom, #venueto").datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        orientation: "right bottom"
                    });
                    $("#venuefrom, #venueto").datepicker("setDate", new Date());
                    </script>

                    <script type="text/javascript">
                    $(document).ready(function() {
                        $('#loadingDiv').hide();
                        // Get the "Staff in store today"
                        // We should make all of the elements on this page load in parallel via ajax calls like this
                        $.ajax({

                            url: pathname + 'hiptna/getstafftoday',
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                $("#staff_today").html(data);
                            }
                        });

                        $.ajax({

                            url: pathname + 'hiptna/getstaffthismonth',
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                data = $("#staff_thismonth").html(data + '%');
                            }
                        });

                        $.ajax({

                            url: pathname + 'hiptna/getstaffexpected',
                            type: 'get',
                            dataType: 'json',
                            success: function(data) {
                                data = $("#staff_expected").html(data);
                            }
                        });

                        var period = getParameterByName('period');
                        //var start = getParameterByName('start');
                        var end = getParameterByName('end');
                        if (end == '') {
                            var start = '';
                        } else {
                            var start = getParameterByName('start');
                        }


                        if (period != '' && period != null) {
                            $("select#brandreportperiod").val(period);
                            $("#venuefrom").val(start);
                            $("#venueto").val(end);
                            renderCharts(period, start, end);
                        }
                        var time = $("#brandreportperiod").val();
                        var startbase = $("#venuefrom").val();
                        var endbase = $("#venueto").val();


                        //----------------staff at work-----------
                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Date",
                            "yAxisName": "Staff",
                            "paletteColors": "#0075c2,#f8b81d",
                            "rotatevalues": "1",
                            "theme": "zune",
                            "clickURL": "{{ url('/hiptna_showgraphdrilldown?period=" + time + "&start=" +
                                startbase + "&end=" + endbase + "#absence') }}"

                        };

                        apiChart = new FusionCharts({
                            type: 'msline',
                            renderAt: 'staff_wrk_trend',
                            width: '400',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": <?php echo $data['category']; ?>
                                }],
                                "dataset": <?php echo $data['staff_graph']; ?>

                            }
                        });
                        apiChart.render();

                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Date",
                            "yAxisName": "Staff",
                            "paletteColors": "#0075c2,#f8b81d",
                            "rotatevalues": "1",
                            "theme": "zune",
                            "clickURL": "{{ url('/hiptna_showgraphdrilldown?period=" + time + "&start=" +
                                startbase + "&end=" + endbase + "#absence') }}"
                        };

                        apiChart = new FusionCharts({
                            type: 'mscolumn2d',
                            renderAt: 'staff_wrk',
                            width: '400',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": <?php echo $data['category']; ?>
                                }],
                                "dataset": <?php echo $data['staff_graph']; ?>

                            }
                        });
                        apiChart.render();

                        //-------------- Staff On Time -------------
                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Date",
                            "yAxisName": "Staff",
                            "paletteColors": "#0075c2,#f8b81d",
                            "rotatevalues": "1",
                            "theme": "zune",
                            "clickURL": "{{ url('/hiptna_showgraphdrilldown?period=" + time + "&start=" +
                                startbase + "&end=" + endbase + "#lateness') }}"
                        };

                        apiChart = new FusionCharts({
                            type: 'msline',
                            renderAt: 'staff_ontime_trend',
                            width: '400',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": <?php echo $data['category']; ?>
                                }],
                                "dataset": <?php echo $data['lateness_graph']; ?>

                            }
                        });
                        apiChart.render();

                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Date",
                            "yAxisName": "Staff",
                            "paletteColors": "#0075c2,#f8b81d",
                            "rotatevalues": "1",
                            "theme": "zune",
                            "clickURL": "{{ url('/hiptna_showgraphdrilldown?period=" + time + "&start=" +
                                startbase + "&end=" + endbase + "#lateness') }}"
                        };

                        apiChart = new FusionCharts({
                            type: 'mscolumn2d',
                            renderAt: 'staff_ontime',
                            width: '400',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": <?php echo $data['category']; ?>
                                }],
                                "dataset": <?php echo $data['lateness_graph']; ?>

                            }
                        });
                        apiChart.render();


                        //-------------- Staff Meeting WS Proximity Target -------------
                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Date",
                            "yAxisName": "Staff",
                            "paletteColors": "#0075c2,#f8b81d",
                            "rotatevalues": "1",
                            "theme": "zune",
                            "clickURL": "{{ url('/hiptna_showgraphdrilldown?period=" + time + "&start=" +
                                startbase + "&end=" + endbase + "#wsproximity') }}"
                        };

                        apiChart = new FusionCharts({
                            type: 'msline',
                            renderAt: 'staff_proximity_trend',
                            width: '400',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": <?php echo $data['category']; ?>
                                }],
                                "dataset": <?php echo $data['wsproximity_graph']; ?>

                            }
                        });
                        apiChart.render();

                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Date",
                            "yAxisName": "Staff",
                            "paletteColors": "#0075c2,#f8b81d",
                            "rotatevalues": "1",
                            "theme": "zune",
                            "clickURL": "{{ url('/hiptna_showgraphdrilldown?period=" + time + "&start=" +
                                startbase + "&end=" + endbase + "#wsproximity') }}"
                        };

                        apiChart = new FusionCharts({
                            type: 'mscolumn2d',
                            renderAt: 'staff_proximity',
                            width: '400',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": <?php echo $data['category']; ?>
                                }],
                                "dataset": <?php echo $data['wsproximity_graph']; ?>

                            }
                        });
                        apiChart.render();


                    });

                    function previewPDF() {
                        var myPage = $("#fusion-chart").html();
                        //    console.log(myPage); alert(myPage);
                        $("#myPage").val(myPage);
                        $("#viewMyPage").submit()
                    }
                    </script>


                </div>
                <?php } ?>
            </div>
        </div>


        <form name="viewMyPage" id="viewMyPage" target="_blank" action="{{ url('myPageDownload') }}" method="post">
            <input type="hidden" name="myPage" id="myPage">
            <input type="hidden" name="report_name_date" id="report_name_date" value="{{$data['report_name_date']}}">
        </form>
</body>

@stop