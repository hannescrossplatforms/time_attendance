<div class="row">
  <div class="col-4 text-left">
    <h3 id="sitename" class="sitename"></h3>
    <span id="reportperiod" class="reportperiod">Report Period</span>
    <select id="venuereportperiod" name="reportperiod" class="form-control">
      <option value="rep7day" <?php if ($data['reportperiod'] == "rep7day") echo "selected"; ?>>7 days</option>
      <option value="repthismonth" <?php if ($data['reportperiod'] == "repthismonth") echo "selected"; ?>>This month</option>
      <option value="replastmonth" <?php if ($data['reportperiod'] == "replastmonth") echo "selected"; ?>>Last month</option>
      <option value="daterange" <?php if ($data['reportperiod'] == "daterange") echo "selected"; ?>>Custom range</option>
    </select>
  </div>
  <div class="col-8 text-right">
    <a style="margin-right:25px;" href="#showvenues" aria-controls="showvenues" data-toggle="tab">
      << Back to list</a> <button type="button" class="btn btn-primary" onclick="printVenuePreview()">View Printable Page</button>
  </div>
  <div class="col-4 text-right">
    <div id="venuedaterange" class="venuedaterange" style="margin-top: 15px;">
      <div class="form-group">
        {{ Form::text('date', null, 
                            array('name' => 'from', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", '
                              class' => 'form-control datepicker schedulecell','placeholder' => 'From Date', 'id' => 'venuefrom')) }}
      </div>

      <div class="form-group">
        {{ Form::text('date', null, 
                            array('name' => 'to', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", 
                              'class' => 'form-control datepicker','placeholder' => 'To Date', 'id' => 'venueto')) }}
      </div>
      <!-- <a id="submitdaterange" href="" >Submit Date Range</a> -->
      <button id="submitvenuedaterange" type="submit" class="btn btn-primary">Submit Date Range</button>
    </div>
  </div>
</div>





<hr />





<div class="row">

  <div class="col-4">

    <h3>Customer Demographics</h3>

    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">Age</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="graphcell">
              <div id="age"></div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">Gender</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="graphcell">
              <div id="gender"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">Income</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="graphcell">
              <div id="income"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="col-4">
    <h3>Venue Performance</h3>

    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">% Uptime</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-center">
            <h3 id="venueuptime" class="modStatspan"></h3>
            <p id="venuebrandavguptime"></p>
          </div>
        </div>
      </div>
    </div>


    <!-- <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">Avg Venue Dwell Time (mins)</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-center">
            <h3 id="avgjamvenuedwelltime" class="modStatspan"></h3>
            <p>Information provided by HipJAM sensors</p>
          </div>
        </div>
      </div>
    </div> -->

    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">New vs. Returning Customers</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-center">
            <div class="graphcell">
              <div id="newvsreturning"></div>
              <div id="newvsreturningtext">
                <table width="100%">
                  <tr style="font-size:11px">
                    <td></td>
                    <td>New</td>
                    <td>Returning</td>
                  </tr>
                  <tr>
                    <td width="50%">This venue</td>
                    <td id="newthisstore"></td>
                    <td id="returningthisstore"></td>
                  </tr>
                  <tr>
                    <td>Brand Avg</td>
                    <td id="newbrandavg"></td>
                    <td id="returningbrandavg"></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">% Customers by Dwell Time Period</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-center">
            <div class="graphcell">
              <div id="dwelltimebysessionduration"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-default card-demo">
      <div class="card-header">
        <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <div class="card-title">% Customers by Hour</div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-center">
            <div class="graphcell">
              <div id="dwelltimebyhour"></div>
            </div>
          </div>
        </div>
      </div>
    </div>



  </div>


  <div class="col-4">
    <h3>Hipzone Wifi & Data Admin Usage Statistics</h3>
    <div class="list-group mb-3">

      <div class="list-group-item">
        <div class="d-flex align-items-center py-3">
          <div class="w-50 px-3">
            <p class="m-0 lead" id="totalwifisessions">0</p> (<small id="avgwifisessions"></small>)
            <p class="m-0 text-sm">Total Wifi Sessions</p>
          </div>
          <div class="w-50 px-3 text-center">
            <i class="fas fa-wifi fa-3x"></i>
          </div>
        </div>
      </div>

      <div class="list-group-item">
        <div class="d-flex align-items-center py-3">
          <div class="w-50 px-3">
            <p class="m-0 lead" id="totalwifidatatotal">0.00</p> (<small id="avgwifidatatotal"></small>)
            <p class="m-0 text-sm">Wifi Data (Total Consumption Gb)</p>
          </div>
          <div class="w-50 px-3 text-center">
            <i class="fas fa-sim-card fa-3x"></i>
          </div>
        </div>
      </div>

      <div class="list-group-item">
        <div class="d-flex align-items-center py-3">
          <div class="w-50 px-3">
            <p class="m-0 lead" id="totalnumberofpeople">0</p> (<small id="avgnumberofpeople"></small>)
            <p class="m-0 text-sm">Number of Unique Customers</p>
          </div>
          <div class="w-50 px-3 text-center">
            <i class="fas fa-users fa-3x"></i>
          </div>
        </div>
      </div>

      <div class="list-group-item">
        <div class="d-flex align-items-center py-3">
          <div class="w-50 px-3">
            <p class="m-0 lead" id="totalfirsttimeusers">0</p> (<small id="avgfirsttimeusers"></small>)
            <p class="m-0 text-sm">First Time Wifi Users</p>
          </div>
          <div class="w-50 px-3 text-center">
            <i class="fas fa-medal fa-3x"></i>
          </div>
        </div>
      </div>

      <div class="list-group-item">
        <div class="d-flex align-items-center py-3">
          <div class="w-50 px-3">
            <p class="m-0 lead" id="venueavgdatapersession">0</p>(<small id="brandavgdatapersession"></small>)
            <p class="m-0 text-sm">Wifi Data (Avg Per Session Mb)</p>
          </div>
          <div class="w-50 px-3 text-center">
            <i class="fas fa-cloud-download-alt fa-3x"></i>
          </div>
        </div>
      </div>

      <div class="list-group-item">
        <div class="d-flex align-items-center py-3">
          <div class="w-50 px-3">
            <p class="m-0 lead" id="venueavgtimepersession">0</p>(<small id="brandavgtimepersession"></small>)
            <p class="m-0 text-sm">Wifi Dwell Time</p>
          </div>
          <div class="w-50 px-3 text-center">
            <i class="fas fa-clock fa-3x"></i>
          </div>
        </div>
      </div>



    </div>
  </div>

</div>







<!-- -----------------------------------------------------------------------  -->
<!-- -----------------------------------------------------------------------  -->
<!-- -----------------------------------------------------------------------  -->
<!-- -----------------------------------------------------------------------  -->
<!-- -----------------------------------------------------------------------  -->




<div id="hipwifi_venue_report">
  <div class="venuereports">
    <!-- venue section one start -->
    
    <!-- venue section one end -->
    <!-- venue section two start -->
    
    <!-- venue section two end -->
  </div>


  <!-- venue section three start -->
  <div id="venue_setcion_three">
    <!-- Wifi Dwell Time Modal -->
    
  </div>
  <!-- venue section three end -->


</div>

<script type="text/javascript">
  function previewVenuePDF() {
    var myVenuePageone = $("#venue_section_one").html();
    var myVenuePagetwo = $("#venue_section_two").html();
    var myVenuePagethree = $("#venue_section_three").html();
    var venue = $("#sitename").text();
    var report_name_venue = "Report for venue " + venue + " from " + from + " to " + to;

    //console.log(myPage); alert(myPage);
    $("#myVenuePageone").val(myVenuePageone);
    $("#myVenuePagetwo").val(myVenuePagetwo);
    $("#myVenuePagethree").val(myVenuePagethree);
    $("#report_name_venue").val(report_name_venue);
    $("#viewMyPageVenue").submit()
  }
</script>
<form name="viewMyPageVenue" id="viewMyPageVenue" target="_blank" action="{{ url('hipwifiVenuePdfDownload') }}" method="post">
  <input type="hidden" name="myVenuePageone" id="myVenuePageone">
  <input type="hidden" name="myVenuePagetwo" id="myVenuePagetwo">
  <input type="hidden" name="myVenuePagethree" id="myVenuePagethree">
  <input type="hidden" name="report_name_venue" id="report_name_venue">
</form>