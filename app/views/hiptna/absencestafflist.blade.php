<style type="text/css">
    .modstattitle{
        /*background-color: #d3d3d3;#106f5d*/
        background-color: #58a5da;
        height: 70px;
        padding: 10px;
    }
    .modstattitle h3{
        color: white;
    }
    </style>
<div class="clear">
<br><br><br>
      <div class="col-sm-12">
          <div class="chart-wrapper">
              <!-- <div class="chart-title venuecolheading">Absent Staff</div> -->
              <div class="chart-stage">
                  <div class="row abscentees_list">
                      <div class="col-sm-8">                    
                          <div class="chart-stage">
                              <div id="staff_absence_list">Loading...</div>
                          </div>
                      </div>
                      <div class="col-sm-4" style="padding:0px 0px 0px 0px;">
                        <div class="row">
                          <div class="col-md-2 modsta-row" style="">
                            <div class="venuerow">
                              <div class="modStat">
                                  <div class="modstattitle">
                                      <h3>ABSENTEES TODAY</h3>
                                  </div>
                                  <div id="absence_today" class="modStatspan">Loading...</div>
                              </div>
                            </div>
                          </div>
        
                          <div class="col-md-2 modsta-row" style="">
                            <div class="venuerow">
                              <div class="modStat">
                                <div class="modstattitle">
                                    <h3>ABSENTEES YESTERDAY</h3>
                                </div>
                                <div id="absence_yesterday" class="modStatspan">Loading...</div>
                              </div>
                            </div>
                          </div>
                          
                        </div>
                        <br>
                        <div class="row">
                          <div class="chart-stage">
                              <div id="most_absence_list">Loading...</div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="chart-stage">
                              <div id="least_absence_list">Loading...</div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
              <button class="csv-button" onclick="export_csv('absent')">Export to Csv</button>
          </div>
      </div>
</div>
<?php if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
                      $pos = strpos($_SERVER['REQUEST_URI'],'public');
                      $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
                      $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
                  } else {
                      $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
                  }
              ?>
              <input type="hidden" id="url" name="" value="{{$url}}">

              
              <input type="hidden" id="fullData" name="fullData" value="">

<script type="text/javascript">
    $( document ).ready(function() {

      pathname = $('#url').val(); //alert(pathname);
        //----------------staff absent-----------

        $.ajax({

                url: pathname+'hiptna/getabsentstafftoday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#absence_today").html(data);
                }
            });
        $.ajax({

                url: pathname+'hiptna/getabsentstaffyesterday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#absence_yesterday").html(data);
                }
            });
        $.ajax({

                url: pathname+'hiptna/getarrivedlatetoday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#late_today").html(data);
                }
            });
        $.ajax({

                url: pathname+'hiptna/getarrivedlateyesterday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#late_yesterday").html(data);
                }
            });
        $.ajax({

                url: pathname+'hiptna/getearlyleftyesterday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#early_left_yesterday").html(data);
                }
            });
        /*$.ajax({

                url: pathname+'hiptna/getproximityfailtoday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#proximity_failer_today").html(data);
                }
            });*/
        $.ajax({

                url: pathname+'hiptna/getproximityfailyesterday',
                type: 'get',
                dataType: 'json',
                success: function(data) {  //alert(data);
                    $("#proximity_failer_yesterday").html(data);
                }
            });
        
        /*$.ajax({

                url: pathname+'hiptna/getproximityfailyesterday',
                type: 'get',
                dataType: 'json',
                success: function(data) {

                  var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Absence",
                      "rotatevalues": "1",
                      "theme": "zune"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_absence_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                    "data": [
                        {
                            "label": "Bloggs, Joe Peter",
                            "value": "810000",
                            "link": "JavaScript:alert('Show Absence, Lateness, Proximity graphs for the staff member. They should have the same time period as the calling page. This should be in a light box similar to hipwifi->venuemonitoring')"
                        },
                        {
                            "label": "Mpofu, Samuel",
                            "value": "620000",
                            "link": "JavaScript:alert('Show Absence, Lateness, Proximity graphs for the staff member. They should have the same time period as the calling page. This should be in a light box similar to hipwifi->venuemonitoring')"
                        },
                        {
                            "label": "Smith, Joanne Mary",
                            "value": "350000",
                            "link": "JavaScript:alert('Show Absence, Lateness, Proximity graphs for the staff member. They should have the same time period as the calling page. This should be in a light box similar to hipwifi->venuemonitoring')"
                        },
                        {
                            "label": "Zuma, Jacob",
                            "value": "350000",
                            "link": "JavaScript:alert('Show Absence, Lateness, Proximity graphs for the staff member. They should have the same time period as the calling page. This should be in a light box similar to hipwifi->venuemonitoring')"
                        },
                     ]

                      }
                  });
                  apiChart.render();

                }
            });*/

        /*$.ajax({

            url: pathname+'hiptna/exceptionchart',
            type: 'get',
            dataType: 'json',
            data : '',
            success: function(data) { 
              $('#fullData').val(data);
              //absent
              var chartProperties = {
                  "caption": "",
                  "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                  "yAxisName": "Absence",
                  "rotatevalues": "1",
                  "theme": "zune",
                  "link": "JavaScript:membergraph("+data.external_user_id+")"
            
              };
              apiChart = new FusionCharts({
                  type: 'bar2d',
                  renderAt: 'staff_absence_list',
                  width: '800',
                  height: '1200',
                  dataFormat: 'json',
                  dataSource: {
                      "chart": chartProperties,
                      "data": data.staff_absent
                  }
              });
              apiChart.render();

              //lateness
              var chartProperties = {
                "caption": "",
                "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                "yAxisName": "Lateness",
                "rotatevalues": "1",
                "theme": "zune",
                "link": "JavaScript:membergraph("+data.external_user_id+")"
            };

            apiChart = new FusionCharts({
                type: 'bar2d',
                renderAt: 'staff_lateness_list',
                width: '800',
                height: '1200',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
              "data": data.staff_lateness
              
                }
            });
            apiChart.render();


            //proximity
            var chartProperties = {
                  "caption": "",
                  "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                  "yAxisName": "Proximity",
                  "rotatevalues": "1",
                  "theme": "zune",
                  "link": "JavaScript:membergraph("+data.external_user_id+")"
              };

              apiChart = new FusionCharts({
                  type: 'bar2d',
                  renderAt: 'staff_proximity_list',
                  width: '800',
                  height: '1200',
                  dataFormat: 'json',
                  dataSource: {
                      "chart": chartProperties,
                      "data": data.staff_proximity
                  }
              });
              apiChart.render();

            }
        });*/

    });

    function export_csv(etab){
      console.log(JSON.stringify(fullData));
      var time = $("#brandreportperiod").val();
      if(time == 'daterange'){
          start = $('#venuefrom').val();
          end = $('#venueto').val();
      }else{
          start = '';
          end = '';
      } 
      myFunction(pathname+'hiptna/csvdownload', 'post', {
        time : time,
        start : start,
        end : end,
        tab : etab
      });

    }

    function myFunction(action, method, input) {
      'use strict';
      var form;
      form = $('<form />', {
          action: action,
          method: method,
          style: 'display: none;'
      });
      if (typeof input !== 'undefined' && input !== null) {
          $.each(input, function (name, value) {
              $('<input />', {
                  type: 'hidden',
                  name: name,
                  value: value
              }).appendTo(form);
          });
      }
      form.appendTo('body').submit();
    }


</script>
