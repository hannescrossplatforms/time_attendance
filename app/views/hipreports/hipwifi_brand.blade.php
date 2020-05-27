<div id="hipwifi_brand">

  <div class="row">
    <!-- FILTER -->
    <div class="col-lg-3 col-md-4 text-left">
      <div class="brandperiod">
        <label>Brand</label>
        <select id="brandlist" name="brand_id" class="form-control no-radius">
          <option id="brand_id" name="brand_id" value="">Select a brand</option>
          @foreach($data['brands'] as $brand)

          <option name="brand_id" value="{{ $brand->id }}" <?php if ($brand->id == $data['brand_id']) echo "selected"; ?> data-userdatabtn='{{ $brand->userdatabtn }}' data-logindatabtn='{{ $brand->logindatabtn }}'>
            {{ $brand->name }}
          </option>

          @endforeach
        </select>
      </div>
    </div>

    <div class="col-lg-3 col-md-4 text-left">
      <label>Report Period</label>
      <select id="brandreportperiod" name="reportperiod" class="form-control">
        <option value="rep7day" <?php if ($data['reportperiod'] == "rep7day") echo "selected"; ?>>7 days</option>
        <option value="repthismonth">This month</option>
        <option value="replastmonth" <?php if ($data['reportperiod'] == "replastmonth") echo "selected"; ?>>Last month</option>
        <option value="daterange" <?php if ($data['reportperiod'] == "daterange") echo "selected"; ?>>Custom range</option>
      </select>
    </div>

    <div class="col-6 text-right" id="printButton">
      <button style="margin-top: 25px;" type="button" class="btn btn-primary" onclick="printpreview()">View Printable Page</button>
      <button style="margin-top: 25px;" id="downloaduserprofiledata" type="submit" class="btn btn-info" title="Download user profile data">User Profile Data &darr;</button>
      <button style="margin-top: 25px;" id="downloadlistcustomerusage" type="submit" class="btn btn-info" title="List customer usage">Customer Login Data &darr;</button>
    </div>

    <div class="col-3 offset-3 text-right" style="margin-top:15px">
      <div id="branddaterange" class="branddaterange">
        <div class="form-group">
          {{ Form::text('date', null, 
          array('name' => 'from', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", '
            class' => 'form-control datepicker','placeholder' => 'From Date', 'id' => 'brandfrom')) }}
        </div>

        <div class="form-group">
          {{ Form::text('date', null, 
          array('name' => 'to', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", 
            'class' => 'form-control datepicker','placeholder' => 'To Date', 'id' => 'brandto')) }}
        </div>
        <div class="form-group">
          <button id="submitdaterange" type="submit" class="btn btn-primary">Submit Date Range</button>
        </div>
      </div>
      <div id="userdatabtn" class="userdatabtn form-group">
        <button id="downloaduserprofiledata" type="submit" class="btn btn-default" title="Download user profile data">User Profile Data &darr;</button>
      </div>

      <div id="logindatabtn" class="logindatabtn form-group">
        <button id="downloadlistcustomerusage" type="submit" class="btn btn-default" title="List customer usage">Customer Login Data &darr;</button>
      </div>

      <iframe id="my_iframe" style="display:none;"></iframe> <!-- This is for the file download -->
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
                <div id="brandage"></div>
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
                <div id="brandgender"></div>
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
                <div id="brandincome"></div>
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
              <h3 id="brandavguptime" class="modStatspan"></h3>
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
              <h3 id="brandavgjamvenuedwelltime" class="modStatspan"></h3>
              <p>Information provided by HipJAM sensors</p>
            </div>
          </div>
        </div>
      </div> -->

      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">New vs. Returning Customers2</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <div class="graphcell">
                <div id="brandnewvsreturning"></div>
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
                <div id="branddwelltimebysessionduration"></div>
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
                <div id="branddwelltimebyhour"></div>
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
              <p class="m-0 lead" id="brandavgwifisessions">0</p>
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
              <p class="m-0 lead" id="brandavgwifidatatotal">0.00</p>
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
              <p class="m-0 lead" id="brandavgnumberofpeople">0</p>
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
              <p class="m-0 lead" id="brandavgfirsttimeusers">0</p>
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
              <p class="m-0 lead" id="brandbrandavgdatapersession">0</p>
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
              <p class="m-0 lead" id="brandbrandavgtimepersession">0</p>
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

  <div class="row">

    <div class="col-6">
      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">Best Performance</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <div class="graphcell">
                <div id="chartcol1row1"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol1row2"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol1row3"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-6">
      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">Worst Performance</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <div class="graphcell">
                <div id="chartcol2row1"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol2row2"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol2row3"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-6">
      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">Biggest Improvement</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <div class="graphcell">
                <div id="chartcol3row1"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol3row2"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol3row3"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6">
      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">Biggest Falls</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <div class="graphcell">
                <div id="chartcol4row1"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol4row2"></div>
              </div>
              <div class="graphcell">
                <div id="chartcol4row3"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="col-12">
      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">Total Dwell Time (mins)</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <div id="dwelltimeChart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>


</div>










<div id="hipwifi_brand">

  <!--        print preview button end-->





  <!-- BEGIN DEMOGRAPHICS AND USAGE -->
  <div class="venuereports">
    <!-- section one start -->
    
    

  </div>

  <!-- section three start -->
  <div id="section_three">
    <!-- Wifi Dwell Time Modal -->
    

    <!-- END DEMOGRAPHICS AND USAGE -->

  </div>
  <!-- section three end -->


  <script type="text/javascript">
    function previewPDF() {
      var myPageone = $("#section_one").html();
      var myPagetwo = $("#section_two").html();
      var myPagethree = $("#section_three").html();
      var brand = $("#brandlist option:selected").text();
      var report_name = "Report for brand " + brand + " from " + from + " to " + to;

      //console.log(myPage); alert(myPage);
      $("#myPageone").val(myPageone);
      $("#myPagetwo").val(myPagetwo);
      $("#myPagethree").val(myPagethree);
      $("#report_name").val(report_name);
      $("#viewMyPage").submit()
    }
  </script>
</div>

<form name="viewMyPage" id="viewMyPage" target="_blank" action="{{ url('hipwifiBrandPdfDownload') }}" method="post">
  <input type="hidden" name="myPageone" id="myPageone">
  <input type="hidden" name="myPagetwo" id="myPagetwo">
  <input type="hidden" name="myPagethree" id="myPagethree">
  <input type="hidden" name="report_name" id="report_name">
</form>