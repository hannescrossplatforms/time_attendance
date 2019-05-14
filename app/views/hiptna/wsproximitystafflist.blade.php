<div class="clear">
<br><br><br>

  <div class="col-sm-12">
      <div class="chart-wrapper">
          <!-- <div class="chart-title venuecolheading">Staff not meeting workstation proximity targets</div> -->
          <div class="chart-stage">
              <div class="row proximity_list" >
                  <div class="col-sm-8">                    
                      <div class="chart-stage">
                          <div id="staff_proximity_list">Loading...</div>
                      </div>
                  </div>
                  <div class="col-sm-4" style="padding:0px 0px 0px 0px;">
                        <div class="row">
                          <!-- <div class="col-md-2" style="width: 50%;">
                            <div class="venuerow">
                              <div class="modStat">
                                  <div class="modstattitle">
                                      <h3>PROXIMITY FAILURES TODAY</h3>
                                  </div>
                                  <div id="proximity_failer_today" class="modStatspan">loading...</div>
                              </div>
                            </div>
                          </div> -->
        
                          <div class="col-md-2" style="width: 50%;">
                            <div class="venuerow">
                              <div class="modStat">
                                <div class="modstattitle">
                                    <h3>WS TARGET NOT MET YESTERDAY</h3>
                                </div>
                                <div id="proximity_failer_yesterday" class="modStatspan">Loading...</div>
                              </div>
                            </div>
                          </div>
                          
                        </div>
                        <br>
                        <div class="row">
                          <div class="chart-stage">
                              <div id="most_proximity_list">Loading...</div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="chart-stage">
                              <div id="least_proximity_list">Loading...</div>
                          </div>
                        </div>
                      </div>
              </div>
          </div>
          <button class="csv-button" onclick="export_csv('proximity')">Export to Csv</button>
      </div>
  </div>
</div>

