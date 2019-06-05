@extends('layout')

@section('content')
<style type="text/css">
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
    <!-- <div id="loadingDiv" class="overlay">
        <img src="./img/loader.gif" style="width:80px;">
    </div> -->
    <a id="buildtable"></a>

    <div class="container-fluid">

        <div class="row">
            @include('hippnp.sidebar')

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">Pick n Pay Dashboard</h1>
                <input type="hidden" id="url" name="" value={{$data['url']}}>

                <div class="container-fluid">
                    <div class="row">
                        <div class="venuecolheading">Customer Overview</div>
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <h3>Customers In Store Today</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan">
                                        <span style="font-size: 30%;">{{$data['customer_in_store_today']}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <h3>Customers In Store This Month</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan"><span
                                            style="font-size: 30%;">{{$data['customer_in_store_this_month']}}</span>
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
                            <button type="button" class="btn btn-primary">View Printable
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
                        <div class="row">
                            <!-- <div id="report_period"><br><br>{{$data['report_period']}}</div> -->



                            <div class="col-sm-6">
                                <div class="chart-wrapper">
                                    <div class="chart-title venuecolheading">Total dwell time per section</div>
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
                                    <div class="chart-title venuecolheading">Average dwell time per section</div>
                                    <div class="chart-stage">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-stage">
                                                    <div id="staff_wrk_avg">Loading...</div>
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
        </div>
</body>

<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('js/fusioncharts.js') }}"></script>
<script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
<script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>

<script>
$(document).ready(function() {

    pathname = $('#url').val();

    var chartProperties = {
        "caption": "",
        "xAxisName": "Section",
        "yAxisName": "Total dwell time (minutes)",
        "paletteColors": "#0075c2,#f8b81d",
        "rotatevalues": "1",
        "theme": "zune"
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


    var chartProperties = {
        "caption": "",
        "xAxisName": "Section",
        "yAxisName": "Average dwell time (minutes)",
        "paletteColors": "#0075c2,#f8b81d",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'mscolumn2d',
        renderAt: 'staff_wrk_avg',
        width: '400',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "categories": [{
                "category": <?php echo $data['category_avg']; ?>
            }],
            "dataset": <?php echo $data['staff_graph_avg']; ?>

        }
    });
    apiChart.render();
});






function change_report_period() {
    var time = $("#brandreportperiod").val();
    if (time == 'daterange') {
        $('#custom').show();
    } else {
        $('#custom').hide();
        renderCharts(time, '', '')
    }
}

function custom_report_period() {
    var from = $('#venuefrom').val();
    var to = $('#venueto').val();

    renderCharts('daterange', from, to)
}

function renderCharts(time, start, end) {


    $.ajax({

        url: pathname + 'hippnp/periodchartJsondata',
        type: 'get',
        dataType: 'json',
        data: {
            'period': time
        },
        success: function(data) {

            // $("#report_period").html(data.report_period);
            // $("#report_name_date").val(data.report_name_date);
            debugger;
            var chartProperties = {
                "caption": "",
                "xAxisName": "Section",
                "yAxisName": "Total dwell time (minutes)",
                "paletteColors": "#0075c2,#f8b81d",
                "rotatevalues": "1",
                "theme": "zune"
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

            var chartProperties = {
                "caption": "",
                "xAxisName": "Section",
                "yAxisName": "Average dwell time (minutes)",
                "paletteColors": "#0075c2,#f8b81d",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn2d',
                renderAt: 'staff_wrk_avg',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [{
                        "category": <?php echo $data['category_avg']; ?>
                    }],
                    "dataset": <?php echo $data['staff_graph_avg']; ?>

                }
            });
            apiChart.render();

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            debugger;
            alert("Status: " + textStatus);
            alert("Error: " + errorThrown);
        }
    });
}
</script>


@stop