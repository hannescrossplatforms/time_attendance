@extends('layout')

@section('content')

<body class="hipJAM">
  <a id="buildtable"></a>

  <div class="container-fluid">
    <div class="row">

      @include('hipjam.sidebar')

      <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
        <h1 class="page-header">Dashboard</h1>


        <div class="alert alert-danger" id="warn_no_locations_found" style="display: none;">X venues do not have location data</div>
        <!-- FILTERS -->
        <div class="row">
          <!-- BRAND -->
          <div class="col-md-4 text-center">
            <small>Select Brand:</small> <br/>
            <select id="sub_brand_select" class="form-control changable-filter">
              <option value="global">Global</option>
                @foreach ($data['sub_brands'] as $brand)
                  <option value="{{$brand->id}}">{{$brand->name}}</option>
                @endforeach
            </select>
          </div>

          <!-- TYPE -->
          <div class="col-md-4 text-center">
            <small>Selected Type:</small> <br/>
            <select id="type_select" class="form-control changable-filter">
              <option value="global">Global</option>
              <option value="billboard">Billboard</option>
              <option value="venue">Venue</option>
            </select>
          </div>

        <!-- DATE -->
        <div class="col-md-4 text-center">
            <small>Selected Date Span:</small> <br/>
            <select id="date_select" class="form-control changable-filter">
              <option value="yesterday">Yesterday</option>
              <option value="today">Today</option>
              <option value="this_week">This Week</option>
              <option value="last_week">Last Week</option>
              <option value="this_month">This Month</option>
              <option value="last_month">Last Month</option>
              <option value="custom">Custom</option>
            </select>
          </div>
        </div>
        <br />


        <div id="map" style="width:100%; height: 500px;"></div>

        <div id="clear-button-div" style="display: none; float: right; margin-top: 10px;">
          <a class="btn btn-default btn-sm">Clear</a>
        </div>
        <div id="ajax-venue-stats-page"></div>
        <iframe style="width: 100%; height: 1880px; border: 0;" id="selected_venue_view"></iframe>

        <div id="stats-and-graph-container">

          <div style="width: 100%; margin-top: 15px;">
            <div style="width:33%; display: inline-block; height:94px;">
              <div style="background-color: #2e80e7; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                <i class="fa fa-map-signs" style="color: white; font-size: 41px; margin-top: 25px;"></i>
              </div>
              <div style="background-color: #5d9dec; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;">{{$data["live_number_of_billboards"]}}</p>
                <small style="color: white; padding-left: 15px; text-transform: uppercase;">Live number of billboards</small>
              </div>
            </div>

            <div style="width:33%; display: inline-block; height:94px;">
              <div style="background-color: #2a9579; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                <i class="fa fa-building" style="color: white; font-size: 41px; margin-top: 25px;"></i>
              </div>
              <div style="background-color: #37bc9b; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;">{{$data["live_number_of_retail_venues"]}}</p>
                <small style="color: white; padding-left: 15px; text-transform: uppercase;">Live number of retail venues</small>
              </div>
            </div>

            <div style="width:33%; display: inline-block; height:94px;">
              <div style="background-color: #574aa3; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                <i class="fa fa-history" style="color: white; font-size: 41px; margin-top: 25px;"></i>
              </div>
              <div style="background-color: #7d6dde; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="live_uniques_today">Loading...</p>
                <small style="color: white; padding-left: 15px; text-transform: uppercase;">Uniques today</small>
              </div>
            </div>

            <div style="width:33%; display: inline-block; height:94px;">
              <div style="background-color: #e72e2e; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                <i class="fa fa-eye" style="color: white; font-size: 41px; margin-top: 25px;"></i>
              </div>
              <div style="background-color: #ec5d5d; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="live_individuals_exposed_current">Loading...</p>
                <small style="color: white; padding-left: 15px; text-transform: uppercase;">Individuals exposed current</small>
              </div>
            </div>

            <div style="width:33%; display: inline-block; height:94px;">
              <div style="background-color: #e72e2e; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                <i class="fa fa-eye" style="color: white; font-size: 41px; margin-top: 25px;"></i>
              </div>
              <div style="background-color: #ec5d5d; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="live_individuals_exposed_today">Loading...</p>
                <small style="color: white; padding-left: 15px; text-transform: uppercase;">Individuals exposed today</small>
              </div>
            </div>

            <div style="width:33%; display: inline-block; height:94px;">
              <div style="background-color: #e72e2e; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                <i class="fa fa-eye" style="color: white; font-size: 41px; margin-top: 25px;"></i>
              </div>
              <div style="background-color: #ec5d5d; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="live_individuals_exposed_today">{{$data['avg_distance'][0]->distance}}km</p>
                <small style="color: white; padding-left: 15px; text-transform: uppercase;">Avg Distance Billboard / Retail</small>
              </div>
            </div>

          </div>

          <div class="row">

            <div class="graph-container" style="padding: 10px; width: 100%;">
              <div class="graphcol" style="width: 50%; margin: 0; float: left; border: 1px solid !important;margin-top: 20px;">
                <h1>Best Performance</h1>
                <div class="graphcell" style="padding: 1px;">
                  <div id="best-performing-chart"></div>
                </div>
                <div class="graphcell">
                  <div id="chartcol1row2"></div>
                </div>
                <div class="graphcell">
                  <div id="chartcol1row3"></div>
                </div>
              </div>
            </div>

            <div class="graph-container" style="padding: 10px; width: 100%;">
              <div class="graphcol" style="width: 50%; margin: 0; float: left; border: 1px solid !important;">
                <h1>Worst Performance</h1>
                <div class="graphcell" style="padding: 1px;">
                  <div id="worst-performing-chart"></div>
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




        <!--
            Exposed visits today global view (Exposed to billboard)
Exposed visits month (Exposed to billboard)
Unexposed visits today (without being exposed to billboard)
Unexposed visits month (without being exposed to billboard)
Time spent in store (dwell) -->



        <div class="table-responsive">
          <table id="venueTable" class="table table-striped"> </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script src="{{ asset('js/fusioncharts.js') }}"></script>
  <script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
  <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>

  <script src="js/prefixfree.min.js"></script>

  <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
  </script>
  <script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDS0aGw5pQFy_dg8198J42w0EeuZtI2Wuk" type="text/javascript"></script>
  <script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-analytics.js"></script>
  <style>
    #selected_venue_view {
      display: none;
    }
  </style>
  <script>
    let venue_array = [];
    let loaded_venues = {{$data['venuesJson']}};
    let liveJam = {};
    liveJam.initialize = (callback) => {
      $.getScript('https://www.gstatic.com/firebasejs/7.1.0/firebase-firestore.js', () => {
        let config = {
          apiKey: "AIzaSyDUxh-Quw0-D6V7Q2Pjcwgeco7R7x08hWw",
          authDomain: "tracks-e61f4.firebaseapp.com",
          databaseURL: "https://tracks-e61f4.firebaseio.com",
          projectId: "tracks-e61f4",
          storageBucket: "",
          messagingSenderId: "798983478031",
          appId: "1:798983478031:web:f81f6341211ab4dfd7bc7a",
          measurementId: "G-9B1XXZ1MXG"
        };
        firebase.initializeApp(config);
        firebase.analytics();

        this.db = firebase.firestore()
        this.all = db.collection

        $.each(loaded_venues, function(i, v) {
          venue_array.push(db.collection(v.id));
        });



        callback();
      });
    };

    liveJam.graphSerializer = (raw_data) => {
                raw_data.sort((a, b) => parseFloat(a.value) - parseFloat(b.value));
                return $.map( raw_data, function( d, i ) {
                    return {label: d.label, value: d.value}
                });
            }

    
    liveJam.getVenueData = () => {
      let current_date = new Date();
      let formatted_node = `${current_date.getFullYear()}-${('0'+(current_date.getMonth()+1)).slice(-2)}-${('0'+current_date.getDate()).slice(-2)}`
      let exposed_current = 0;
      let exposed_today = 0;
      let uniques_today = 0;
      let venues_with_no_data = 0;

      let promise_array = []; 

      $.each(venue_array, (i, v) => {
        
        promise_array.push(v.doc(formatted_node).get()
          .then(doc => {
            console.log('Venue loaded')
            if (doc.exists) {
              exposed_current += doc.data().customers_in_store_now;
              exposed_today += doc.data().customers_in_store_today;
              uniques_today += doc.data().new_customers_today;
              // debugger;
              // best_performing.push({label: 'test', value: doc.data().customers_in_store_today})
            } else {
              venues_with_no_data += 1;
            }
            $('#live_individuals_exposed_current').html(exposed_current.toString());
            $('#live_individuals_exposed_today').html(exposed_today.toString());
            $('#live_uniques_today').html(uniques_today.toString());
            if (doc.exists) {
            return {label: 'test', value: doc.data().customers_in_store_today}
            }
          })
          )
      });

      Promise.all(promise_array).then(function(res) {
        let all_venues = [];
        $.each(res, function(i, obj) {
          if (obj !== undefined) {
            all_venues.push({label: loaded_venues[i].sitename, value: obj.value})
          } else {
            all_venues.push({label: loaded_venues[i].sitename, value: 0})
          }
        });

          let test_data = liveJam.graphSerializer(all_venues).slice(0,5);

        

        //WORST
        var dataSource = {
                chart: {
                  caption: "Best performing Venues",
                  xaxisname: "Store",
                  yaxisname: "Sessions",
                  theme: "zune"
                },
                data: liveJam.graphSerializer(all_venues).slice(1).slice(-5)
              };

              var myChart = new FusionCharts({
                type: "column2d",
                renderAt: "best-performing-chart",
                width: "100%",
                height: "350",
                dataFormat: "json",
                dataSource
              }).render();

              dataSource = {
                chart: {
                  caption: "Worst performing Venues",
                  xaxisname: "Store",
                  yaxisname: "Sessions",
                  theme: "zune"
                },
                data: test_data
              };

              myChart = new FusionCharts({
                type: "column2d",
                renderAt: "worst-performing-chart",
                width: "100%",
                height: "350",
                dataFormat: "json",
                dataSource
              }).render();
      })
      

    }

    function getIcon(venue, callback) {
      let current_date = new Date();
      let current_date_node = `${current_date.getFullYear()}-${('0'+(current_date.getMonth()+1)).slice(-2)}-${('0'+current_date.getDate()).slice(-2)}`
      db.collection(venue.id).doc(current_date_node).get()
        .then((doc) => {
          if (doc.exists) {
            let venue_data = doc.data();
            if (($.now() / 1000) - 300 < venue_data.last_seen) {
              if (venue_data.type === null || venue_data.type === 'venue' || venue_data.type === '') {
                callback('http://hiphub.hipzone.co.za/img/retail_marker.png');
              } else {
                callback('http://hiphub.hipzone.co.za/img/billboard_marker.png');
              }
            } else {
              if (venue_data.type === null || venue_data.type === 'venue') {
                callback('http://hiphub.hipzone.co.za/img/offline_retail_marker.gif');
              } else {
                callback('http://hiphub.hipzone.co.za/img/offline_billboard_marker.gif');
              }
            }
          } else {
            callback('http://hiphub.hipzone.co.za/img/offline_retail_marker.gif');
          }
        });
    }

    let venues = {{$data['venuesJson']}};

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(-30.341529, 25.322594),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: [{
          elementType: 'geometry',
          stylers: [{
            color: '#08304b'
          }]
        },
        {
          elementType: 'labels.text.stroke',
          stylers: [{
            color: '#000000'
          }]
        },
        {
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'administrative.locality',
          elementType: 'labels.text.stroke',
          stylers: [{
            color: '#000000'
          }]
        },
        {
          featureType: 'administrative.locality',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'poi',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'poi.park',
          elementType: 'geometry',
          stylers: [{
            color: '#0e5064'
          }]
        },
        {
          featureType: 'poi.park',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'road',
          elementType: 'geometry',
          stylers: [{
            color: '#165f71'
          }]
        },
        {
          featureType: 'road',
          elementType: 'geometry.stroke',
          stylers: [{
            color: '#165f71'
          }]
        },
        {
          featureType: 'road',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'road.highway',
          elementType: 'geometry',
          stylers: [{
            color: '#165f71'
          }]
        },
        {
          featureType: 'road.highway',
          elementType: 'geometry.stroke',
          stylers: [{
            color: '#165f71'
          }]
        },
        {
          featureType: 'road.highway',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'transit',
          elementType: 'geometry',
          stylers: [{
            color: '#165f71'
          }]
        },
        {
          featureType: 'transit.station',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'water',
          elementType: 'geometry',
          stylers: [{
            color: '#9dd5ff'
          }]
        },
        {
          featureType: 'water',
          elementType: 'labels.text.fill',
          stylers: [{
            color: '#FFFFFF'
          }]
        },
        {
          featureType: 'water',
          elementType: 'labels.text.stroke',
          stylers: [{
            color: '#000000'
          }]
        }
      ]
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    let markers = [];
    let no_lat_long_count = 0;
    let venue_promises = [];

    
    // liveJam.compileData = (span, callback) => {
    //     let nodes = liveJam.compileNodeArray(span);
    //     let week_data_promises = [];
    //     let week_data = [];
    //     $.each(nodes, function(i, node) {
    //         console.log('GETTING DATA FOR: ' + node);
    //         week_data_promises.push(liveJam.retrieveNode(node));
    //     });
    //     Promise.all(week_data_promises).then(function() {
    //         callback(arguments[0]);
    //     })
    // }

    liveJam.initialize(() => {
      liveJam.getVenueData();
      $.each(venues, function(i, venue) {

        if ((venue.latitude === null || venue.latitude === '') || venue.ap_active === '0') {
          no_lat_long_count++;
        } else {
          getIcon(venue, function(ico) {
              marker = new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(venue.latitude), parseFloat(venue.longitude)),
                map: map,
                icon: ico,
                venue_id: venue.id
              });

              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {

                  let venue_id = marker.venue_id;
                  let url_parts = window.location.href.split('?');
                  let filters = '';
                  if (url_parts.length === 2) 
                    filters = `?${url_parts[1]}`

                  $('#selected_venue_view').attr('src', `http://hiphub.hipzone.co.za/hipjam_viewvenue/${venue_id}/tracks03.hipzone.co.za${filters}`);
                  $('#selected_venue_view').slideDown('fast')

                  console.log(`marker clicked with id: ${venue_id}`);
                }
              })(marker, i));

              markers.push(marker)

              if (i + 1 === venues.length) {
                var markerCluster = new MarkerClusterer(map, markers, {
                  imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });
              }
            });
          };

      });

      if (no_lat_long_count !== 0) {
        $('#warn_no_locations_found').html(`<i class="fa fa-info-circle" style="margin-right: 10px"></i>${no_lat_long_count} venues do not have location data`)
        $('#warn_no_locations_found').slideDown('fast');
      }

      



      $("#clear-button-div").click(function() {
        $("#clear-button-div").css("display", 'none');
        $("#stats-and-graph-container").css("display", "initial");
        $("#ajax-venue-stats-page").html(null);
      });

      // liveJam.compileData('week', function (week_data) {
        // setTimeout(
        // function() 
        // {
        //   let best_performing_data = best_performing;
                
        // }, 5000);
        
      // })
      
            
      


    });
  </script>

  <script>
    // $(function() {
    //   console.log("begin");
    //     showSelectedVenues();
    // });


    // $(document).ready(function(){
    //   $.ajax({
    //       type: "GET",
    //       dataType: 'json',
    //       contentType: "application/json",
    //       url: "{{ url('hipjam_load_customer_stats_for_dash'); }}",
    //       success: function(response) {


    //         $('#individuals_exposed_current').html(response.individualsExposedCurrent);
    //         $('#individuals_exposed_today').html(response.individuals_exposed_today);
    //         $('#uniques_today').html(response.uniques_today);

    //         $('#individuals_exposed_current').addClass( "font36" );
    //         $('#individuals_exposed_today').addClass( "font36" );
    //         $('#uniques_today').addClass( "font36" );

    //       },
    //       error: function(error){

    //       }
    //     });

    //     $.ajax({
    //       type: "GET",
    //       dataType: 'json',
    //       contentType: "application/json",
    //       url: "{{ url('hipjam_graph_data'); }}",
    //       success: function(brandjson) {
    //         console.log(brandjson);
    //         showBrandPerformanceGraphs(brandjson);
    //       },
    //       error: function(error) {
    //         debugger;
    //       }
    //     });


    //     function showBrandPerformanceGraphs(brandData) {

    //       var highest5Sessions = new FusionCharts({
    //         type: "column2d",
    //         renderAt: "chartcol1row1",
    //         width: "98%",
    //         height: "300",
    //         dataFormat: "json",
    //         dataSource: brandData["highest5Sessions"]
    //         });
    //         highest5Sessions.render("chartcol1row1");

    //         var lowest5session = new FusionCharts({
    //         type: "column2d",
    //         renderAt: "chartcol2row1",
    //         width: "98%",
    //         height: "300",
    //         dataFormat: "json",
    //         dataSource: brandData["lowest5Sessionsdata"]
    //         });
    //         lowest5session.render("chartcol2row1");

    //       }



    // })


    venuesJson = {{$data['venuesJson']}};

    $(function() {
      $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
    });

    $(document).delegate('#buildtable', 'click', function() {
      showVenuesTable(venuesJson);
    });

    $(document).delegate('#reset', 'click', function() {
      showVenuesTable(venuesJson);
    });

    $(document).delegate('#filter', 'click', function(event) {

      event.preventDefault();

      sitename = $("#src-sitename").val();
      macaddress = $("#src-macaddress").val();

      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        data: {
          'sitename': sitename,
          'macaddress': macaddress
        },
        url: "{{ url('lib_filterdvenues'); }}",
        success: function(filteredVenuesjson) {
          // alert("success");
          showVenuesTable(filteredVenuesjson);
        }
      });
    });

    function showVenuesTable(venuesjson) {
      // alert("showVenuesTable");
      table = '';
      rows = '';
      beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Sitename</th>\n\
                      <th>Contact</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
      $.each(venuesjson, function(index, value) {


        /*editbutton = '<a href="{{ url('hipwifi_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n';*/
        if (value["apisitename"] != 'no_venue') {
        
          viewbutton = '<a href="http://hiphub.hipzone.co.za/hipjam_viewvenue/' + value["id"] + '/' + value["apisitename"] + '" class="btn btn-default btn-sm">view</a>\n';
        } else {
          viewbutton = '<a href="javascript:void(0);" onclick="alert_message()" class="btn btn-default btn-sm">view</a>\n';
        }

        /*viewbutton = '<a href="{{ url('hipjam_viewvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">view</a>\n';*/


        /*deletebutton = '<a class="btn btn-default btn-delete btn-sm" data-venueid = ' + value["id"] + ' href="#">delete</a>\n';*/

        /*if(value["device_type"] == 'Mikrotik') {
          redeploybutton = '<a href="{{ url('hipwifi_redeploymikrotikvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">redeploy</a>\n';
        } else {
          redeploybutton = '\n';
        }*/

        rows = rows + '\
                    <tr>\n\
                      <td style="width: 20%; text-align: left;">' + value["sitename"] + '</td>\n\
                      <td style="width: 20%"> ' + value["contact"] + '</td>\n\
                      <td style="width: 60%; text-align: left;"> ' + viewbutton + '</td>\n\
                    </tr>\n\
                    ';
      });

      endTable = ' \
                  </tbody>\n\
                </table>';

      table = beginTable + rows + endTable;
      $("#venueTable").html(table);
    }

    function alert_message(venuesjson) {
      alert('Track Venue Id has not been provided. Please configure in Track->Venue Management');
    }

    $(document).delegate('.btn-delete', 'click', function() {
      var venueId = this.getAttribute('data-venueid');
      swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this venue?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, delete it!',
          closeOnConfirm: false,
        },
        function() {
          swal("Deleted!", "Venue has been deleted!", "success");
          $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('hipwifi_deletevenue/" + venueId + "'); }}",
            success: function(venues) {
              var venuesjson = JSON.parse(venues);
              showVenuesTable(venuesjson);
            }
          });
        });
    });
  </script>
<!-- FILTER SCRIPT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script>
    load_presets();
    getFilters(false);

    $(document).on('change', '.changable-filter', function() {
      getFilters(true);
    })

    function load_presets() {
      let presets = get_presets();
      if (presets !== null) {
        $('#sub_brand_select').val(presets.brand_id);
        $('#type_select').val(presets.type);
        $('#date_select').val(presets.span);
      } else {
        $('#date_select').val('this_week');
      }
    }

    function getFilters(must_redirect) {
      let brand_id = $('#sub_brand_select').val();
      let type = $('#type_select').val();
      let span = $('#date_select').val();
      let date_from = generate_date_from();
      let date_to = generate_date_to();
      
      if (must_redirect)
        window.location.href = `http://hiphub.hipzone.co.za/hipjam_showdashboard${generate_query_string(brand_id, type, span, date_from, date_to)}`
    }

    function generate_date_from() {
      let selected_date_span = $('#date_select').val();
      let today = moment();
      switch(selected_date_span) {
        case 'yesterday':
          return today.subtract(1, 'day').format('YYYY-MM-DD');
          break;
        case 'today':
          return today
          break;
        case 'this_week':
          return today.startOf('week').format('YYYY-MM-DD');
          break;
        case 'last_week':
          return today.startOf('week').subtract(1, 'day').startOf('week').format('YYYY-MM-DD');
          break;
        case 'this_month':
          return today.startOf('month').format('YYYY-MM-DD');
          break;
        case 'last_month':
          return today.startOf('month').subtract(1, 'day').startOf('month').format('YYYY-MM-DD');
          break;
        default:
          return today.startOf('week').format('YYYY-MM-DD');
      }
    }

    function generate_date_to() {
      let selected_date_span = $('#date_select').val();
      let today = moment();
      switch(selected_date_span) {
        case 'yesterday':
          return today.subtract(1, 'day').format('YYYY-MM-DD');
          break;
        case 'today':
          return today
          break;
        case 'this_week':
          return today.endOf('week').format('YYYY-MM-DD');
          break;
        case 'last_week':
          return today.startOf('week').subtract(1, 'day').endOf('week').format('YYYY-MM-DD');
          break;
        case 'this_month':
          return today.endOf('month').format('YYYY-MM-DD');
          break;
        case 'last_month':
          return today.startOf('month').subtract(1, 'day').endOf('month').format('YYYY-MM-DD');
          break;
        default:
          return today.endOf('week').format('YYYY-MM-DD');
      }
    }

    function generate_query_string(brand_id, type, span, date_from, date_to) {
      return `?brand_id=${brand_id}&type=${type}&span=${span}&date_from=${date_from}&date_to=${date_to}`;
    }

    function get_query_string_key(key) {
      key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
      var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
      return match && decodeURIComponent(match[1].replace(/\+/g, " "));
    }

    function get_presets() {
      let brand_id = get_query_string_key('brand_id');
      if (brand_id === '' || brand_id === null || brand_id === undefined)
        return null
      return { brand_id: get_query_string_key('brand_id'), type: get_query_string_key('type'), span: get_query_string_key('span'),  date_from: get_query_string_key('date_from'), date_to: get_query_string_key('date_to') }
    }

  </script>

  <style>
    .dash-widget {
      border: 1px solid #D1D1D1D1;
      padding: 25px;
      margin-right: 10px;
      height: 142px;
    }

    @media only screen and (max-width: 1512px) {
      .dash-widget {
        height: 182px !important;
      }

      .text-tracked {
        height: 45px;
      }
    }

    .font36 {
      font-size: 36px;
    }

    .graph-container {
      display: inline;
    }
  </style>
  

</body>

@stop