<div class="container-fluid">

    <div class="row">
        <div class="venuecolheading">Staff Overview</div>
        

        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store Today</h3>
                    </div>
                    <div id="staff_today" class="modStatspan"><span style="font-size: 30%;">Loading...</span></div>
                </div>
            </div>
        </div>
                
        <div class="col-md-2" >
            <div class="venuerow">
                <div class="modStat">
                    <div class="modstattitle">
                        <h3>Staff In Store This Month</h3>
                    </div>
                    <div id="staff_thismonth" class="modStatspan"><span style="font-size: 30%;">Loading...</span></div>
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
                <select id="brandreportperiod" onchange="change_report_period()" class="form-control" name="reportperiod" >
                    <!-- <option value="">Select</option> -->
                    <option value="rep7day">This Week</option>
                    <option value="repthismonth">This month</option>
                    <option value="replastmonth">Last month</option>
                    <option value="daterange">Custom range</option>
                </select>
            </div>
        </div>
        
<!--        printpreview button start-->
        <!-- <div id="printButton" class="col-md-4" style="width:30%; float: right;">
            <button type="button" class="btn btn-primary" onclick="printpreview()">View Printable Page</button>
        </div> -->
<!--        print preview button end-->

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

    <br><br>
    <div id="fusion-chart"> <!-- fusioncharts starts -->
    
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



        $( document ).ready(function() {
            $('#loadingDiv').hide();
            // Get the "Staff in store today"
            // We should make all of the elements on this page load in parallel via ajax calls like this
            $.ajax({

                url: pathname+'hiptna/getstafftoday',
                type: 'get',
                dataType: 'json',
                success: function(data) { 
                    $("#staff_today").html(data);
                }
            });

            $.ajax({

                url: pathname+'hiptna/getstaffthismonth',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                data=$("#staff_thismonth").html(data+'%');
                }
            });


            var period = getParameterByName('period');
            //var start = getParameterByName('start');
            var end = getParameterByName('end');
            if(end == ''){
                var start = '';
            }else{
                var start = getParameterByName('start');
            } 

            
            if(period != '' && period != null){
                $("select#brandreportperiod").val(period);
                $("#venuefrom").val(start);
                $("#venueto").val(end);
                renderCharts(period,start,end);
            }
            var time = $("#brandreportperiod").val();
            var startbase = $("#venuefrom").val();
            var endbase = $("#venueto").val();



            //-------------- Staff On Time -------------
            var chartProperties = {
                "caption": "",
                "xAxisName": "Date",
                "yAxisName": "Staff",
                "paletteColors" : "#0075c2,#f8b81d",
                "rotatevalues": "1",
                "theme": "zune",
                "clickURL": "{{ url('/hiptna_showgraphdrilldown?period="+time+"&start="+startbase+"&end="+endbase+"#lateness') }}"
            };

            apiChart = new FusionCharts({
                type: 'msline',
                renderAt: 'staff_ontime_trend',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [
                        {
                            "category": <?php echo $data['category']; ?>
                        }
                    ],
                    "dataset": <?php echo $data['lateness_graph']; ?>

                }
            });
            apiChart.render();

            var chartProperties = {
                "caption": "",
                "xAxisName": "Date",
                "yAxisName": "Staff",
                "paletteColors" : "#0075c2,#f8b81d",
                "rotatevalues": "1",
                "theme": "zune",
                "clickURL": "{{ url('/hiptna_showgraphdrilldown?period="+time+"&start="+startbase+"&end="+endbase+"#lateness') }}"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn2d',
                renderAt: 'staff_ontime',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": [
                        {
                            "category": <?php echo $data['category']; ?>
                        }
                    ],
                    "dataset": <?php echo $data['lateness_graph']; ?>

                }
            });
            apiChart.render();


        });
        
    function previewPDF() {
        var myPage              =       $("#fusion-chart").html();
    //    console.log(myPage); alert(myPage);
        $("#myPage").val(myPage);
        $("#viewMyPage").submit()
    }
    </script>
            
            
        </div>