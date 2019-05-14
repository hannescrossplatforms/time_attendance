                <div class="venuefilterform">
                  <form id="venuefilterform" class="form-inline" role="form" style="margin-bottom: 15px;" action=" {{ url('hipreports_hipwifi'); }}  ">
                    <div id="venueheader" class="venueheader">
                      <div class="form-group">
                        <div class="venueperiod">
                          <span id="sitename" class="sitename"></span>
                          <span id="reportperiod" class="reportperiod">Report Period</span>
                          <select id="venuereportperiod" name="reportperiod" class="form-control">
                              <option value="rep7day" <?php if($data['reportperiod'] == "rep7day") echo "selected"; ?>>7 days</option>
                              <option value="repthismonth" <?php if($data['reportperiod'] == "repthismonth") echo "selected"; ?>>This month</option>
                              <option value="replastmonth" <?php if($data['reportperiod'] == "replastmonth") echo "selected"; ?>>Last month</option>
                            <option value="daterange" <?php if($data['reportperiod'] == "daterange") echo "selected"; ?>>Custom range</option>
                          </select>
                        </div>
                        <div  id="venuedaterange" class="venuedaterange">
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
                          <button id="submitvenuedaterange" type="submit" class="btn btn-default">Submit Date Range</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <!--        printpreview button start-->
                  <div id="printButtonVenue" class="col-md-4" style="width:25%;float:right;">
                      <button type="button" class="btn btn-primary" onclick="printVenuePreview()">View Printable Page</button>
                </div>
                <!--        print preview button end-->

                <div class="backtovenues" style="float:right ;width:12%">
                  <a href="#showvenues" aria-controls="showvenues" data-toggle="tab"><< Back to list</a>
                </div>

                <div id="hipwifi_venue_report">
                  <div class="venuereports">
                    <!-- venue section one start -->
                    <div id="venue_section_one">
                      <div class="venuecol1 svgimg">
                        <div class="venuecolheading">Customer Demographics</div>
                        <div class="venuerow">
                          <div class="modStat">
                              <div class="modstattitle"><h3>Age</h3></div>
                              <div class="graphcell">
                                <div id="age"></div>
                              </div>
                          </div>
                        </div>
                        <div class="venuerow">
                          <div class="modStat">
                              <div class="modstattitle"><h3>Gender</h3></div>
                              <div class="graphcell">
                                <div id="gender"></div>
                              </div>
                          </div>
                        </div>
                        <div class="venuerow">
                          <div class="modStat">
                              <div class="modstattitle"><h3>Income</h3></div>
                              <div class="graphcell">
                                <div id="income"></div>
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="venuecol2 svgimg">
                        <div class="venuecolheading">Venue Performance Data</div>
                          <div class="venuerow">
                            <div class="modStat">
                              <div class="modstattitle"><h3>% Uptime</h3> </div>
                              <div id="venueuptime" class="modStatspan"></div>
                              <div id="venuebrandavguptime"></div>
                            </div>
                          </div>
                          <div class="venuerow">
                            <div class="modStat">
                              <div class="modstattitle"><h3>Avg Venue Dwell Time (mins)</h3></div>
                              <div id="avgjamvenuedwelltime" class="modStatspan"></div>
                              <div>Information provided by HipJAM sensors</div>
                          </div>
                        </div>
                        <div class="venuerow">
                          <div class="modStat">
                              <div class="modstattitle"><h3>New vs. Returning Customers</h3></div>
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
                              <!-- <div class="venuerow"><img src="/img/tmpcharts/1_3.PNG" width="100%"></div> -->
                          </div>
                        </div>
                      </div>                      
                    </div>
                    <!-- venue section one end -->
                    <!-- venue section two start -->
                    <div id="venue_section_two">
                      <div class="venuecol3">
                        <div class="venuecolheading">Hipzone Wifi & Data Admin Usage Statistics</div>
                        <div class="venuerow">
                          <div class="venuerowleft">
                            <div class="modStat">
                              <div class="modstattitle"><h3>Total Wifi Sessions</h3> </div>
                              <div id="totalwifisessions" class="modStatspan"></div>
                              <div id="avgwifisessions"></div>
                            </div>
                          </div>
                          <div class="venuerowright">
                            <div class="modStat">
                              <div class="modstattitle"><h3>Wifi Data (Total Consumption Gb)</h3> </div>
                              <div id="totalwifidatatotal" class="modStatspan"></div>
                              <div id="avgwifidatatotal"></div>
                            </div>                      
                          </div>
                        </div>
                        <div class="venuerow">
                          <div class="venuerowleft">                        
                            <div class="modStat">
                              <div class="modstattitle"><h3>Number of Unique Customers</h3> </div>
                              <div id="totalnumberofpeople" class="modStatspan"></div>
                              <div id="avgnumberofpeople"></div>
                            </div>  
                          </div>
                          <div class="venuerowright">
                            <div class="modStat">
                              <div class="modstattitle"><h3>First Time Wifi Users</h3></div>
                              <div id="totalfirsttimeusers" class="modStatspan"></div>
                              <div id="avgfirsttimeusers"></div>
                            </div>                      
                          </div>
                        </div>
                        <div class="venuerow">
                          <div class="venuerowleft">
                            <div class="modStat">
                              <div class="modstattitle"> <h3>Wifi Data (Avg Per Session Mb)</h3> </div>
                              <div id="venueavgdatapersession" class="modStatspan"></div>
                              <div id="brandavgdatapersession"></div>
                            </div>
                          </div>
                          <div class="venuerowright">
                            <a class="modal-link" href="#" id="customerDwellTimeLink" data-toggle="modal" data-target="#customerDwellTimeModal">
                              <div class="modStat">
                                <div class="modstattitle"> <h3>Wifi Dwell Time</h3> </div>
                                <div id="venueavgtimepersession" class="modStatspan"></div>
                                <div id="brandavgtimepersession"></div>                    
                              </div>
                            </a>
                          </div>
                        </div>
                        <div class="venuerow">
                          <div class="modStat">
                              <div class="modstattitle"><h3>Data Admin Usage</h3></div>
                              <div>No Data Available. <br> You are not using HipZone as your ISP.</div>
                              <!-- <div class="venuerow"><img src="/img/tmpcharts/3-4.PNG" width="100%"></div> -->
                          </div>
                        </div>                        
                      </div>                      
                    </div> 
                    <!-- venue section two end -->                   
                  </div>


                  <!-- venue section three start -->
                  <div id="venue_setcion_three">
                    <!-- Wifi Dwell Time Modal -->
                    <div class="modal fade" id="customerDwellTimeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog dwelltime-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h6 class="modal-title" id="myModalLabel">Customer Dwell Time Percentages</h6>
                          </div>
                          <div class="modal-body">
                            <div class="venuerow">
                              <div >                        
                                <div class="modStat">
                                    <div class="modstattitle"><h3>% Customers by Dwell Time Period</h3></div>
                                    <div class="graphcell">
                                      <div id="dwelltimebysessionduration"></div>
                                    </div>
                                </div>  
                              </div>
                              <div >
                                <div class="modStat">
                                    <div class="modstattitle"><h3>% Customers by Hour</h3></div>
                                    <div class="graphcell">
                                      <div id="dwelltimebyhour"></div>
                                    </div>
                                </div>                     
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer"> </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <!-- venue section three end -->

                  
                </div>

                <script type="text/javascript">
                  function previewVenuePDF() {
                      var myVenuePageone    =   $("#venue_section_one").html();
                      var myVenuePagetwo    =   $("#venue_section_two").html();
                      var myVenuePagethree  =   $("#venue_section_three").html();
                      var venue =  $( "#sitename" ).text();
                      var report_name_venue = "Report for venue "+venue+" from "+from+" to "+to;               
                      
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
