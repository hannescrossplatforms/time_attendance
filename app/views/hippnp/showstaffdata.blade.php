@extends('layout')

@section('content')
<style type="text/css">
.modstattitle{
    background-color: #58A5DA;
    height: 70px;
    padding: 10px;
}
.modstattitle h3{
    color: white;
}
</style>
  <body class="hipTnA">
    <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savelookupmedia'); }}"></form>
    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>

    <div class="container-fluid">
      <div class="row">

        @include('hippnp.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          <h1 class="page-header">Staff Lookup</h1>
          <input type="hidden" id="url" name="" value={{$data['url']}}>

            <div class="row">
                <div class="col-md-6" style="width:30%;">
                    <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
                        <label>Report Period</label>
                    </div>
                    <div class="col-md-4" style="padding:0px 0px 0px 6px;">
                        <input type="text" class="form-control datepicker" name="selectedDate" id="selectedDate"
                            placeholder="Selected date">
                    </div>
                </div>
            </div>

            <div id="fusion-chart">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="chart-wrapper">
                            <div class="chart-title venuecolheading">Staff beacon activity</div>
                            <div class="chart-stage">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="chart-stage">
                                            <div id="staff_beacon_activity">Loading...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Staff member</label>
                <select id="staff_select" name="store_id" onchange="get_staff_chart()" class="form-control" required>
                <option value="0">Select</option>
                @foreach($data['staff'] as $staffMember)
                    <option value="{{ $staffMember->id }}">
                    {{ $staffMember->name }} {{ $staffMember->surname }}
                    </option>
                @endforeach
                </select>
            </div>

            <div id="fusion-chart">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="chart-wrapper">
                            <div class="chart-title venuecolheading">Staff beacon activity</div>
                            <div class="chart-stage">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="chart-stage">
                                            <div id="single_staff_beacon_activity">Loading...</div>
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


    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">Category Activity</h6>
          </div>
            <div class="modal-body">
            <div id="fusion-chart">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="chart-wrapper">
                            <div class="chart-title venuecolheading">Staff beacon activity</div>
                            <div class="chart-stage">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="chart-stage">
                                            <div id="staff_category_activity">Loading...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="modal-footer">
              <a href="" class="btn btn-default" data-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!--     Code for staff member popup graphs -->
    <div id="memberGraphModalhtml"> </div>
    <div id="memberModalLinkDiv"> </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>

    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>



    <script src="{{ asset('js/prefixfree.min.js') }}"></script>



    <script type="text/javascript" src="{{ asset('js/js.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fusioncharts/fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fusioncharts/themes/fusioncharts.theme.zune.js') }}"></script>


    <script>

        var timeListData = null;
        var singleTimeListData = null;

        $("#selectedDate").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            orientation: "right bottom"
        });

        $("#selectedDate").datepicker("setDate", new Date());

        $('#selectedDate').change(function () {

            let date = $('#selectedDate').val();
            pathname = $('#url').val();


            $.ajax({
                url: pathname + 'hippnp/periodchartJsondataStaffAjax',
                type: 'get',
                dataType: 'json',
                data: {
                    'date': date
                },
                success: function(data) {

                    $("#staff_select").val('0');

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Time of day",
                        "yAxisName": "Total dwell time (minutes)",
                        "paletteColors": "#0075c2,#f8b81d,#3CB371",
                        "rotatevalues": "1",
                        "theme": "zune"
                    };

                    timeListData = data['time_list_data'];

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'staff_beacon_activity',
                        width: '100%',
                        height: 350,
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [{
                                "category": data['time_list']
                            }],
                            "dataset": data['time_list_data']

                        },
                        events: {
                            "dataPlotClick": function (eventObj, dataObj) {
                                let object = timeListData[dataObj.datasetIndex];
                                let id = object.data[dataObj.dataIndex][0].id;
                                let staffMemeberID = parseInt(id);

                                let date = $('#selectedDate').val();
                                alert(staffMemeberID);
                                get_staff_category_details(staffMemeberID, date);

                            }
                        }
                    });
                    apiChart.render();

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Time of day",
                        "yAxisName": "Total dwell time (minutes)",
                        "paletteColors": "#0075c2,#f8b81d,#3CB371",
                        "rotatevalues": "1",
                        "theme": "zune"
                    };

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'single_staff_beacon_activity',
                        width: '100%',
                        height: 350,
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [{
                                "category": []
                            }],
                            "dataset": []

                        }
                    });
                    apiChart.render();

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }
            });

        });

        function get_staff_category_details(staffId, date){

            $.ajax({
                url: pathname + 'hippnp/staffCategoryActivity',
                type: 'POST',
                dataType: 'json',
                data: {
                    'date': date,
                    'staff_id': staffId
                },
                success: function(data) {

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Time of day",
                        "yAxisName": "Total dwell time (minutes)",
                        "paletteColors": "#0075c2,#f8b81d,#3CB371",
                        "rotatevalues": "1",
                        "theme": "zune"
                    };

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'staff_category_activity',
                        width: '100%',
                        height: 350,
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [{
                                "category": data['time_list']
                            }],
                            "dataset": data['time_list_data']

                        }
                    });
                    apiChart.render();

                    $('#messageModal').modal('toggle');

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }
            });
        }

        function get_staff_chart(){

            let pathname = $('#url').val();
            let date = $('#selectedDate').val();
            let staffId = $('#staff_select').val();
            let text = $('#staff_select').text();


            if (text != "Select"){

                $.ajax({
                    url: pathname + 'hippnp/periodchartJsondataSingleStaffAjax',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'date': date,
                        'staff_id': staffId
                    },
                    success: function(data) {

                        singleTimeListData = data['time_list_data'];
                        var chartProperties = {
                            "caption": "",
                            "xAxisName": "Time of day",
                            "yAxisName": "Total dwell time (minutes)",
                            "paletteColors": "#0075c2,#f8b81d,#3CB371",
                            "rotatevalues": "1",
                            "theme": "zune"
                        };

                        apiChart = new FusionCharts({
                            type: 'msline',
                            renderAt: 'single_staff_beacon_activity',
                            width: '100%',
                            height: 350,
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "categories": [{
                                    "category": data['time_list']
                                }],
                                "dataset": data['time_list_data']

                            },
                            events: {
                                "dataPlotClick": function (eventObj, dataObj) {
                                    let object = singleTimeListData[dataObj.datasetIndex];
                                    let id = object.data[dataObj.dataIndex][0].id;
                                    let staffMemeberID = parseInt(id);

                                    let date = $('#selectedDate').val();
                                    alert(staffMemeberID);
                                    get_staff_category_details(staffMemeberID, date);

                                }
                            }
                        });
                        apiChart.render();

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {

                    }
                });

            }
            else {

                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Time of day",
                    "yAxisName": "Total dwell time (minutes)",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'msline',
                    renderAt: 'single_staff_beacon_activity',
                    width: '100%',
                    height: 350,
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": []
                        }],
                        "dataset": []

                    }
                });
                apiChart.render();
            }

        }


        $(document).ready(function() {



            timeListData = <?php echo $data['time_list_data']; ?>

            var chartProperties = {
                "caption": "",
                "xAxisName": "Time of day",
                "yAxisName": "Total dwell time (minutes)",
                "paletteColors": "#0075c2,#f8b81d,#3CB371",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'msline',
                renderAt: 'staff_beacon_activity',
                width: '100%',
                height: 350,
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [{
                        "category": <?php echo $data['time_list']; ?>
                    }],
                    "dataset": <?php echo $data['time_list_data']; ?>

                }
            });
            apiChart.render();

            var chartProperties = {
                    "caption": "",
                    "xAxisName": "Time of day",
                    "yAxisName": "Total dwell time (minutes)",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'msline',
                    renderAt: 'single_staff_beacon_activity',
                    width: '100%',
                    height: 350,
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": []
                        }],
                        "dataset": []

                    }
                });
                apiChart.render();

        });

    </script>

  </body>
@stop
