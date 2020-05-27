@extends('angle_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">


  

  <div class="row" id="filter_container">
    <div class="col-12">
      <div class="card card-default card-demo">
        <div class="card-header">
          <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <div class="card-title">Filter</div>
        </div>
    
          <div class="card-body">
            <div class="row">

              <div class="col-4">
                <small>Select Brand:</small> <br/>
                <select id="sub_brand_select" class="form-control changable-filter">
                  <option value="global">Global</option>
                    @foreach ($data['sub_brands'] as $brand)
                      <option value="{{$brand->id}}">{{isset($brand->friendly_name) ? $brand->friendly_name : $brand->name}}</option>
                    @endforeach
                </select>
              </div>          

            <div class="col-4">
              <small>Selected Type:</small> <br/>
              <select id="type_select" class="form-control changable-filter">
                <option value="global">Global</option>
                <option value="billboard">OOH Site</option>
                <option value="venue">Venue</option>
              </select>
            </div>

            <div class="col-4">
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
              <div id="custom_date_filter" style="display: none;">
                <div class="row" style="margin-top: 10px;">
                  <div class="col-5 text-center">
                    <input class="form-control" type="date" placeholder="yyyy/mm/dd" id="custom_filter_date_from"/>
                  </div>
                  <div class="col-5 text-center">
                    <input class="form-control" type="date" placeholder="yyyy/mm/dd" id="custom_filter_date_to" />
                  </div>
                  <div class="col-2 text-center">
                    <button class="btn btn-primary" id="apply_custom_date_filter">Apply</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-12">
      <div class="card card-default card-demo" id="cardChart9">
        <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
        <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
          <!-- <div class="card-title">Inbound visitor statistics</div> -->
        </div>
        <div class="card-wrapper">
          <div class="card-body">
            <div id="map" style="width:100%; height: 300px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row" id="selected_venue_view_container" style="display:none;">
    <div class="col-12">
      <div class="card card-default card-demo" id="cardChart9">
        <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <!-- <div class="card-title">Inbound visitor statistics</div> -->
        </div>
        
          <div class="card-body">
          <iframe style="width: 100%; height: 1880px; border: 0;" id="selected_venue_view"></iframe>
          </div>
        
      </div>
    </div>
  </div>

  
    
    <div class="row">
      <div class="col-xl-2 col-md-6">
        <!-- START card-->
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-primary-dark justify-content-center rounded-left"><em class="fas fa-map-signs fa-3x"></em></div>
          <div class="col-8 py-3 bg-primary rounded-right">
            <div class="h2 mt-0" id="live_nr_billboard_count"></div>
            <div class="text-uppercase">Live number of OOH Sites</div>
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-md-6">
        <!-- START card-->
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-purple-dark justify-content-center rounded-left"><em class="fas fa-building fa-3x"></em></div>
          <div class="col-8 py-3 bg-purple rounded-right">
            <div class="h2 mt-0" id="live_nr_retail_count"></div>
            <div class="text-uppercase">Live number of retail venues</div>
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-lg-6 col-md-12">
        <!-- START card-->
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-green-dark justify-content-center rounded-left"><em class="fas fa-history fa-3x"></em></div>
          <div class="col-8 py-3 bg-green rounded-right">
            <div class="h2 mt-0" id=live_uniques_today>Loading...</div>
            <div class="text-uppercase">Uniques today</div>
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-lg-6 col-md-12">
        <!-- START card-->
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-info justify-content-center rounded-left"><em class="fas fa-vector-square fa-3x"></em></div>
          <div class="col-8 py-3 bg-info-dark rounded-right">
            <div class="h2 mt-0">{{$data['avg_distance'][0]->distance}} km</div>
            <div class="text-uppercase">Avg Distance OOH Site / Retail</div>
          </div>
        </div>
      </div>
    <!-- </div>END cards box -->

    <!-- <div class="row"> -->
      <div class="col-xl-2 col-md-6">
        <!-- START card-->
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-danger justify-content-center rounded-left"><em class="fas fa-clock fa-3x"></em></div>
          <div class="col-8 py-3 bg-danger-dark rounded-right">
            <div class="h2 mt-0" id="live_individuals_exposed_current">Loading...</div>
            <div class="text-uppercase">Real-time Customers</div>
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-md-6">
        <!-- START card-->
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-warning justify-content-center rounded-left"><em class="fas fa-calendar fa-3x"></em></div>
          <div class="col-8 py-3 bg-warning-dark rounded-right">
            <div class="h2 mt-0" id="live_individuals_exposed_today">Loading...</div>
            <div class="text-uppercase">Real-time Customers Today</div>
          </div>
        </div>
      </div>

      </div>
      
    <!-- </div>END cards box -->


    <div class="row">
      <div class="col-6">
        <div class="card card-default card-demo" id="cardChart9">
          <div class="card-header">
          <a class="float-right" href="javascript:void(0);" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
            <div class="card-title">Best Performance</div>
          </div>
          <!-- <div class="card-wrapper"> -->
            <div class="card-body">
            <div class="card-wrapper no-overflow">
              <canvas id="best-performing-chart"></canvas>
            </div>
            </div>
          <!-- </div> -->
        </div>
      </div>

      <div class="col-6">
        <div class="card card-default card-demo" id="cardChart9">
          <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
            <div class="card-title">Worst Performance</div>
          </div>
          <!-- <div class="card-wrapper"> -->
            <div class="card-body">
            <div class="card-wrapper no-overflow">
            <canvas height="150" id="worst-performing-chart"></canvas>
            </div>
            </div>
          <!-- </div> -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo" id="cardChart9">
          <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
          
            <div class="card-title">Venue Selection</div>
          </div>
          <div class="card-body">
          <div class="card-wrapper">
            <div id="venueTable"> 
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- Page footer-->
<input type="hidden" id="focused_venue"/>
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

    liveJam.orderAndSortData = (raw_data) => {
      raw_data.sort((a, b) => parseFloat(a.y) - parseFloat(b.y));
      return {
        labels: $.map( raw_data, function( d, i ) {
                    return d.x
                }),
        data: $.map( raw_data, function( d, i ) {
                    return d.y
                })
      }
      // return $.map( raw_data, function( d, i ) {
      //               return {label: d.label, value: d.value}
      //           });
    }

    liveJam.graphSerializer = (raw_data) => {
                raw_data.sort((a, b) => parseFloat(a.value) - parseFloat(b.value));
                return $.map( raw_data, function( d, i ) {
                    return {label: d.label, value: d.value}
                });
            }

    
      liveJam.getVenueData = () => {
      let current_date = new Date();
      let formatted_node = moment().format('YYYY-MM-DD');
      let exposed_current = 0;
      let exposed_today = 0;
      let uniques_today = 0;
      let venues_with_no_data = 0;

      let date_array = getDateArray();
      let promise_array = []; 
      let all_venues = [];

      $.each(venue_array, (i, v) => {
        promise_array.push(v.doc(formatted_node).get()
          .then(doc => {
          
            // console.log('Venue loaded')
            if (doc.exists) {
              exposed_current += doc.data().customers_in_store_now;
              exposed_today += doc.data().customers_in_store_today;
              uniques_today += doc.data().new_customers_today;
              
              // best_performing.push({label: 'test', value: doc.data().customers_in_store_today})
            } else {
              venues_with_no_data += 1;
            }
            $('#live_individuals_exposed_current').html(exposed_current.toString());
            $('#live_individuals_exposed_today').html(exposed_today.toString());
            $('#live_uniques_today').html(uniques_today.toString());

            

        }))     
      });

      let best_worst_venue_promises = [];
      let best_worst_data = {};
      let running_total = 0;

      let master_promise = null;
      $.each(loaded_venues, (num, venue) => {
        $.each(date_array, (n, date) => {
          best_worst_venue_promises.push(
            db.collection(venue.id).doc(date).get()
              .then(doc => {
                
                if (doc.data() !== undefined) {
                  
                  // console.log(doc.data().customers_in_store_today)
                  debugger;
                  return {label: (venue.friendly_brandname !== null ? (`${venue.friendly_brandname} ${venue.sitename.split(' ')[1]}`) : venue.sitename), value: doc.data().customers_in_store_today};
                }
                else {
                  return {label: (venue.friendly_brandname !== null ? (`${venue.friendly_brandname} ${venue.sitename.split(' ')[1]}`) : venue.sitename), value: 0}; //running_total += 0;
                }
                  
              })
          )
        })


        master_promise = Promise.all(best_worst_venue_promises).then(function(res) {   
          return res;
          // all_venues =
           
          // all_venues.push({label: venue.sitename, value: eval(res.join("+"))});
          
        });
      });

      master_promise.then(function(b) { 
      all_venues = Enumerable.From(b)
            .GroupBy("$.label", null,
                    function (key, g) {
                        return {
                          x: key,
                          y: g.Sum("$.value")
                        }
            })
            .ToArray();
  
        
          let test_data = liveJam.graphSerializer(all_venues).slice(0,5);

      
        let top_venues = liveJam.orderAndSortData(all_venues);
        let canvas = document.getElementById('best-performing-chart');
        let ctx = canvas.getContext("2d");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: top_venues.labels.slice(0).slice(-5),
              datasets: [{
                  label: '# of Sessions',
                  data: top_venues.data.slice(0).slice(-5),
                  borderColor: "green",
                  backgroundColor: "rgba(55,188,155,1)"
                }]
              },
              options: global_chart_options
        });
        //WORST
        canvas = document.getElementById('worst-performing-chart');
        ctx = canvas.getContext("2d");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: top_venues.labels.slice(0).slice(0,5),
              datasets: [{
                  label: '# of Sessions',
                  data: top_venues.data.slice(0).slice(0,5),
                  borderColor: "red",
                  backgroundColor: "rgba(236,33,33,1)"
                }]
              },
              options: global_chart_options
        });

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
    var markers = [];
    let no_lat_long_count = 0;
    let venue_promises = [];

    var venue_count = 0;
    
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
          venue_count++;
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
                  let host = `http://${window.location.host}/`;
                  if (url_parts.length === 2) 
                    filters = `?${url_parts[1]}`
                  window.open(`${host}hipjam_viewvenue/${venue_id}/tracks03.hipzone.co.za${filters}`)
                  

                  // $('#selected_venue_view').attr('src', `http://hiphub.hipzone.co.za/hipjam_viewvenue/${venue_id}/tracks03.hipzone.co.za${filters}`);                  

                  // $('#selected_venue_view').slideDown('fast')
                  // $('#selected_venue_view_container').slideDown('fast');
                  $('#focused_venue').val(venue_id);

                  console.log(`marker clicked with id: ${venue_id}`);
                }
              })(marker, i));
              venue_count++;
              markers.push(marker)
              if (venue_count === venues.length) {
                debugger;
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

    function getDateArray() {
      let filters = get_presets();
      if (filters === null) {
          filters = {date_from: moment().startOf('week').add(1,'day'),
                      date_to: moment().endOf('week').add(1,'day')}
      }
      let start = moment(filters.date_from);
      let end = moment(filters.date_to);
      let date_array = [];

      // Multi Day i.e. this week / this month etc.
      while (start.format('YYYY-MM-DD') !== end.format('YYYY-MM-DD')) {
          date_array.push(start.format('YYYY-MM-DD'))
          start.add(1, 'days');
      }
      
      // Add last day
      date_array.push(start.format('YYYY-MM-DD'));
      return date_array;
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

    showVenuesTable(venuesJson);

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
          showVenuesTable(filteredVenuesjson);
        }
      });
    });

    function showVenuesTable(venuesjson) {
      let host = `http://${window.location.host}/`;
      let live_number_of_retail = 0;
      let live_number_of_billboard = 0;
      table = '';
      rows = '';
      beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Sitename</th>\n\
                      <th>Type</th>\n\
                      <th>Contact</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
      $.each(venuesjson, function(index, value) {
        if (value["apisitename"] != 'no_venue') {
          viewbutton = '<a href="' + host + 'hipjam_viewvenue/' + value["id"] + '/' + value["apisitename"] + '" class="btn btn-primary btn-sm view-venue-button" data-venue-id=' + value["id"] + '>view</a>\n';
        } else {
          viewbutton = '<a href="javascript:void(0);" onclick="alert_message()" class="btn btn-default btn-sm">view</a>\n';
        }

        if (value["track_type"] === 'billboard') {
          live_number_of_billboard += 1;
        } else {
          live_number_of_retail += 1;
        }
        let friendly_name = value['friendly_brandname'] + ' ' + value["sitename"].split(' ')[1];
        rows = rows + '\
                    <tr>\n\
                      <td style="width: 20%; text-align: left;">' + (value['friendly_brandname'] !== null ? friendly_name : value["sitename"]) + '</td>\n\
                      <td style="width:15%">' + (value["track_type"] === 'billboard' ? 'OOH Site' : 'Venue') + '</td>\n\
                      <td style="width: 15%"> ' + value["contact"] + '</td>\n\
                      <td style="width: 50%; text-align: left;"> ' + viewbutton + '</td>\n\
                    </tr>\n\
                    ';
      });

      endTable = ' \
                  </tbody>\n\
                </table>';

      table = beginTable + rows + endTable;

      $('#live_nr_retail_count').html(live_number_of_retail)
      $('#live_nr_billboard_count').html(live_number_of_billboard)

      
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
    if ($('#date_select').val() !== 'custom') {
      getFilters(false);  
    } else {
      showCustomDateRange();
    }
    

    $(document).on('change', '.changable-filter', function() {
      let date_filter = $('#date_select');
      if (date_filter.val() !== 'custom') {
        getFilters(true);
      } else {
        showCustomDateRange();
      }
      
    })

    function showCustomDateRange() {
      let container = $('#custom_date_filter');
      let date_from = $('#custom_filter_date_from');
      let date_to = $('#custom_filter_date_to');

    
      date_from.val(get_query_string_key('date_from'));
      date_to.val(get_query_string_key('date_to'))

      container.slideDown('fast');
    }

    $(document).on('click', '#apply_custom_date_filter', function () {
      getFilters(true);
    });

    function load_presets() {
      let presets = get_presets();
      let host = `http://${window.location.host}/`;
      if (presets !== null) {
        $('#sub_brand_select').val(presets.brand_id);
        $('#type_select').val(presets.type);
        $('#date_select').val(presets.span);
        $('#focused_venue').val(presets.venue_id);
        
        if (presets.venue_id !== '' && presets.venue_id !== null) {
          $('#selected_venue_view').attr('src', `${host}hipjam_viewvenue/${presets.venue_id}/tracks03.hipzone.co.za${getFilters(false)}`);                  
          $('#selected_venue_view').slideDown('fast');
          $('#selected_venue_view_container').slideDown('fast');
        }
      } else {
        $('#date_select').val('this_week');
      }
    }

    function getFilters(must_redirect) {
      let host = `http://${window.location.host}/`;
      let brand_id = $('#sub_brand_select').val();
      let type = $('#type_select').val();
      let span = $('#date_select').val();
      let date_from = generate_date_from();
      let date_to = generate_date_to();
      
      if (must_redirect)
        window.location.replace(`${host}hipjam_showdashboard${generate_query_string(brand_id, type, span, date_from, date_to)}`)
      else
        return generate_query_string(brand_id, type, span, date_from, date_to)
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
          return today.startOf('week').add(1,'day').format('YYYY-MM-DD');
          break;
        case 'last_week':
          return today.startOf('week').subtract(1, 'day').startOf('week').add(1,'day').format('YYYY-MM-DD');
          break;
        case 'this_month':
          return today.startOf('month').format('YYYY-MM-DD');
          break;
        case 'last_month':
          return today.startOf('month').subtract(1, 'day').startOf('month').format('YYYY-MM-DD');
          break;
        case 'custom':
          return $('#custom_filter_date_from').val();
          break;
        default:
          return today.startOf('week').add(1,'day').format('YYYY-MM-DD');
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
          return today.endOf('week').add(1,'day').format('YYYY-MM-DD');
          break;
        case 'last_week':
          return today.startOf('week').subtract(1, 'day').endOf('week').add(1,'day').format('YYYY-MM-DD');
          break;
        case 'this_month':
          return today.endOf('month').format('YYYY-MM-DD');
          break;
        case 'last_month':
          return today.startOf('month').subtract(1, 'day').endOf('month').format('YYYY-MM-DD');
          break;
        case 'custom':
          return $('#custom_filter_date_to').val();
          break;
        default:
          return today.endOf('week').add(1,'day').format('YYYY-MM-DD');
      }
    }

    function generate_query_string(brand_id, type, span, date_from, date_to) {
      let venue_ammend = '';
      if ($('#focused_venue').val() !== '')
        venue_ammend = `&venue_id=${$('#focused_venue').val()}`;
      return `?brand_id=${brand_id}&type=${type}&span=${span}&date_from=${date_from}&date_to=${date_to}${venue_ammend}`;
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
      return { brand_id: get_query_string_key('brand_id'), type: get_query_string_key('type'), span: get_query_string_key('span'),  date_from: get_query_string_key('date_from'), date_to: get_query_string_key('date_to'), venue_id: get_query_string_key('venue_id') }
    }

    window.addEventListener('DOMContentLoaded', (event) => {
      let presets = get_presets();
      let host = `http://${window.location.host}/`;
      if (presets !== null) {
        // Set the default filter for view venue buttons
        $('.view-venue-button').each(function(i, obj) {
            let venue_id = $(this).data('venue-id');
            console.log(venue_id)
            $(this).attr('href', `${host}hipjam_viewvenue/${venue_id}/tracks03.hipzone.co.za${generate_query_string(presets.brand_id, presets.type, presets.span, presets.date_from, presets.date_to)}`)
        });
      }
    });

  </script>

  <script>
    if (window.location.href.indexOf('brand_id') !== -1)
      $('#filter_container').slideDown('fast');
    $(document).on('click', '#filter_button', function () {
      let container = $('#filter_container');
      if (container.is(':visible'))
        container.stop().slideUp('fast');
      else
        container.stop().slideDown('fast');
    });


    // if ( $('[type="date"]').prop('type') != 'date' ) {
     $('[type="date"]').datepicker({ dateFormat: 'yy/mm/dd' });
    // }
  </script>

  <style>
    @media screen and (max-width: 2131px) {
      .align-items-center {
        min-height: 100px !important;
      }
    }

    @media screen and (max-width: 1514px) {
      .align-items-center {
        min-height: 117px !important;
      }
    }

    /* 1514 */
        /* min-height: 100px !important; */
    /* .align-items-center {
      min-height: 118px !important;
    } */
    .text-uppercase {
      font-size: 11px;
    }
    .h2 {
      font-size: 24px;
    }
    .no-overflow {
        max-height: none !important;
    }
  </style>

@stop