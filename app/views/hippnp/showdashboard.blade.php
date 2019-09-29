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
                <h1 class="page-header">Pick n Pay Category Management</h1>
                <input type="hidden" id="url" name="" value={{$data['url']}}>



                <div class="container-fluid">


                <div id="section_one"> <!-- Section one start -->

                    <div class="row">
                        <div class="venuecolheading">Staff Overview</div>
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitlepnp">
                                        <h3>Staff In Store Today</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan">
                                        <span>{{$data['customer_in_store_today']}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitlepnp">
                                        <h3>Staff In Store This Month</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan"><span>
                                    {{$data['customer_in_store_this_month']}}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>  <!-- Section one end -->

                    <div class="row">
                        <div class="col-md-4" style="width:30%;">
                            <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                <label>Report Period</label>
                            </div>
                            <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                <select id="brandreportperiod" onchange="change_report_period()" class="form-control"
                                    name="reportperiod">
                                    <option value="today">Today</option>
                                    <option value="rep7day">This Week</option>
                                    <option value="repthismonth">This month</option>
                                    <option value="replastmonth">Last month</option>
                                    <option value="daterange">Custom range</option>
                                </select>
                            </div>
                        </div>

                        <!--        printpreview button start-->
                        <div id="printButton" class="col-md-4" style="width:30%; float: right;">
                            <!-- <button type="button" class="btn btn-primary">View Printable Page</button> -->
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
                    <br>
                    <div class="row">
                        <div class="col-md-4" style="width:30%;">
                            <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                <label>Store</label>
                            </div>
                            <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                <select id="brandstore" onchange="change_report_period()" class="form-control"
                                    name="brandstore">
                                    <option value="">Select</option>
                                    @foreach($data['all_stores'] as $store)
                                    <option value="{{ $store->id }}">
                                    {{ $store->sitename }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-4" style="width:30%;">
                            <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                <label>Province</label>
                            </div>
                            <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                <select id="brandprovince" onchange="change_report_period()" class="form-control"
                                    name="brandprovince">
                                    <option value="">Select</option>
                                    @foreach($data['all_provinces'] as $province)
                                    <option value="{{ $province->id }}">
                                    {{ $province->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-4" style="width:30%;">
                            <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                                <label>Category</label>
                            </div>
                            <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
                                <select id="brandcategory" onchange="change_report_period()" class="form-control"
                                    name="brandcategory">
                                    <option value="">Select</option>
                                    @foreach($data['all_categories_for_filter'] as $category)
                                    <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="row">
                        <div id="printButton" class="col-md-4" style="width:30%; float: right;">
                                <button type="button" class="btn btn-primary" onclick="printpreview()" >View Printable Page</button>
                        </div>
                    </div>






                        <div id="fusion-chart">

                            <div id="section_two"> <!-- Section two start -->
                                <div class="row">
                                    <div class="col-sm-6 charty">
                                        <div class="chart-wrapper">
                                            <div class="chart-title venuecolheading">Total dwell time per category</div>
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

                                    <div class="col-sm-6 charty">
                                        <div class="chart-wrapper">
                                            <div class="chart-title venuecolheading">Average dwell time per category</div>
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

                            </div><!-- Section two end -->

                        </div>

                        <div class="row">

                            <div id="section_three"> <!-- Section three start -->

                                <div class="col-sm-6 charty">
                                    <div class="chart-wrapper">
                                        <div class="chart-title venuecolheading">Number of visits per category</div>
                                        <div class="chart-stage">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="chart-stage">
                                                        <div id="staff_visits_per_category">Loading...</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 charty">
                                    <div class="chart-wrapper">
                                        <div class="chart-title venuecolheading">Number of visits per store</div>
                                        <div class="chart-stage">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="chart-stage">
                                                        <div id="staff_visits_per_store">Loading...</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- Section three end -->


                        </div>

                        <div class="row">

                            <div id="section_four"> <!-- Section four start -->

                                <div class="col-sm-6">
                                    <div class="chart-wrapper">
                                        <div class="chart-title venuecolheading">Staff activity</div>
                                        <div class="chart-stage">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="chart-stage">
                                                        <div id="staff_activity">Loading...</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- Section four end -->

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

<script type="text/javascript">
    $("#venuefrom, #venueto").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "right bottom"
    });
    $("#venuefrom, #venueto").datepicker("setDate", new Date());
</script>


<script>


$(document).ready(function() {

    pathname = $('#url').val();

    // Total dwell time

    var chartProperties = {
        "caption": "",
        "xAxisName": "Category",
        "yAxisName": "Total dwell time (minutes)",
        "paletteColors": "#0075c2,#f8b81d,#3CB371",
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
                "category": <?php echo $data['category_list']; ?>
            }],
            "dataset": <?php echo $data['category_list_data']; ?>

        }
    });
    apiChart.render();

    // Average dwell time

    var chartProperties = {
        "caption": "",
        "xAxisName": "Category",
        "yAxisName": "Average dwell time (minutes)",
        "paletteColors": "#0075c2,#f8b81d,#3CB371",
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
                "category": <?php echo $data['category_list']; ?>
            }],
            "dataset": <?php echo $data['category_list_data_average']; ?>

        }
    });
    apiChart.render();

    // Number of visits per category

    var chartProperties = {
        "caption": "",
        "xAxisName": "Category",
        "yAxisName": "Number of visits per category",
        "paletteColors": "#0075c2,#f8b81d,#3CB371",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'mscolumn2d',
        renderAt: 'staff_visits_per_category',
        width: '400',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "categories": [{
                "category": <?php echo $data['category_list']; ?>
            }],
            "dataset": <?php echo $data['category_list_data_visits']; ?>

        }
    });
    apiChart.render();

    // Number of visits per store

    var chartProperties = {
        "caption": "",
        "xAxisName": "Store",
        "yAxisName": "Number of visits per store",
        "paletteColors": "#0075c2,#f8b81d,#3CB371",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'mscolumn2d',
        renderAt: 'staff_visits_per_store',
        width: '400',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "categories": [{
                "category": <?php echo $data['category_list']; ?>
            }],
            "dataset": <?php echo $data['category_list_data_visits_store']; ?>

        }
    });
    apiChart.render();

    //Staff graph

    var chartProperties = {
        "caption": "",
        "xAxisName": "Staff Activity",
        "yAxisName": "Total dwell time (minutes)",
        "paletteColors": "#0075c2,#f8b81d,#3CB371",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'mscolumn2d',
        renderAt: 'staff_activity',
        width: '400',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "categories": [{
                "category": <?php echo $data['staff_list']; ?>
            }],
            "dataset": <?php echo $data['staff_list_data']; ?>

        }
    });
    apiChart.render();

});

function change_report_period() {

    var time = $("#brandreportperiod").val();

    var category = $("#brandcategory").val();
    var store = $("#brandstore").val();
    var province = $("#brandprovince").val();

    if (time == 'daterange') {
        $('#custom').show();
    } else {
        $('#custom').hide();
        renderCharts(time, '', '', category, store, province);
    }
}

function custom_report_period() {
    var from = $('#venuefrom').val();
    var to = $('#venueto').val();

    var category = $("#brandcategory").val();
    var store = $("#brandstore").val();
    var province = $("#brandprovince").val();

    renderCharts('daterange', from, to, category, store, province);
}

var staffActivityData = null;

function renderCharts(time, start, end, category, store, province) {

    $.ajax({

        url: pathname + 'hippnp/periodchartJsondata',
        type: 'get',
        dataType: 'json',
        data: {
            'period': time,
            'start': start,
            'end': end,
            'category_id': category,
            'store_id': store,
            'province_id': province
        },
        success: function(data) {

            var chartProperties = {
                "caption": "",
                "xAxisName": "Category",
                "yAxisName": "Total dwell time (minutes)",
                "paletteColors": "#0075c2,#f8b81d,#3CB371",
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
                        "category": data['category_list']
                    }],
                    "dataset": data['category_list_data']
                }
            });

            apiChart.render();

            var chartProperties = {
                "caption": "",
                "xAxisName": "Category",
                "yAxisName": "Average dwell time (minutes)",
                "paletteColors": "#0075c2,#f8b81d,#3CB371",
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
                        "category": data['category_list']
                    }],
                    "dataset": data['category_list_data_average']

                }
            });
            apiChart.render();

            var chartProperties = {
                "caption": "",
                "xAxisName": "Category",
                "yAxisName": "Number of visits",
                "paletteColors": "#0075c2,#f8b81d,#3CB371",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn2d',
                renderAt: 'staff_visits_per_category',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [{
                        "category": data['category_list']
                    }],
                    "dataset": data['category_list_data_visits']

                }
            });
            apiChart.render();

            // Number of visits per store

            var chartProperties = {
                "caption": "",
                "xAxisName": "Store",
                "yAxisName": "Number of visits per store",
                "paletteColors": "#0075c2,#f8b81d,#3CB371",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn2d',
                renderAt: 'staff_visits_per_store',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [{
                        "category": data['category_list']
                    }],
                    "dataset": data['category_list_data_visits_store']

                }
            });
            apiChart.render();


            var chartProperties = {
                "caption": "",
                "xAxisName": "Staff Activity",
                "yAxisName": "Total dwell time (minutes)",
                "paletteColors": "#0075c2,#f8b81d,#3CB371",
                "rotatevalues": "1",
                "theme": "zune"
            };

            staffActivityData = data['staff_list_data'];

            apiChart = new FusionCharts({
                type: 'mscolumn2d',
                renderAt: 'staff_activity',
                width: '400',
                height: '350',
                dataFormat: 'json',
                entityDef: data['entity_dev'],
                dataSource: {
                    "chart": chartProperties,
                    "categories": [{
                        "category": data['staff_list']
                    }],
                    "dataset": data['staff_list_data']

                },
                events: {
                    "dataPlotClick": function (eventObj, dataObj) {
                        let object = staffActivityData[dataObj.datasetIndex];
                        let id = object.data[dataObj.dataIndex][0].id;
                        let staffMemeberID = parseInt(id);

                        window.location.replace("hippnp/periodchartJsondataStaff/" + staffMemeberID);

                    }
                }
            });
            apiChart.render();






        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });
}

function printpreview() {

    $('#loadingDiv').show();
    pathname = $('#url').val();

    var fusioncharts_container = {};
    $("span[class='fusioncharts-container']").each(function(index, elem) {
      var spanId = $(this).attr('id');
      spanId = spanId.split('-');
      fusioncharts_container[spanId[1]] = $(this).html();
    });

    var fusionchartspans = fusioncharts_container;
    var fusionImg = $.ajax({
      type: 'POST',
      dataType: 'json',
      async: false,
      url: pathname + 'hippnp_convertsvgtoimage',
      data: {
        fusionchartspans: fusionchartspans
      },
      success: function(result) {
        $('#loadingDiv').hide();
      }
    });

    var fusionImages = fusionImg.responseJSON.result_img;
    $("span[class='fusioncharts-container']").each(function(index, elem) {
      $(this).removeAttr('style');
      var spanId = $(this).attr('id');
      spanId = spanId.split('-');
      var image_path = 'fc_images/image_temp/' + fusionImages['img_' + spanId[1]];
      $(this).html('<img src="' + image_path + '">');
    });

    var i = 1;
    $('#fusion-chart .col-sm-6').each(function(index, elem) {
      if (i % 2 == 0) {
        $(this).addClass('col-6-right-al');
      } else {
        $(this).addClass('col-6-left-al');
      }
      i++;
    });

    previewPDF();
  }

  function previewPDF() {
        var myPageone    =   $("#section_one").html();
        var myPagetwo    =   $("#section_two").html();
        var myPagethree    =   $("#section_three").html();
        var myPagefour    =   $("#section_four").html();

    //console.log(myPage); alert(myPage);
        $("#myPageone").val(myPageone);
        $("#myPagetwo").val(myPagetwo);
        $("#myPagethree").val(myPagethree);
        $("#myPagefour").val(myPagefour);
        $("#viewMyPage").submit()
    }



</script>

<form name="viewMyPage" id="viewMyPage" target="_blank" action="{{ url('hippnpBrandPdfDownloadPreview') }}" method="post">
    <input type="hidden" name="myPageone" id="myPageone">
    <input type="hidden" name="myPagetwo" id="myPagetwo">
    <input type="hidden" name="myPagethree" id="myPagethree">
    <input type="hidden" name="myPagefour" id="myPagefour">
    <input type="hidden" name="reportName" id="reportName" value="HannesTest">
</form>

<!-- <div id="section_one" >
    <h1>Test</h1>
</div> -->

@stop