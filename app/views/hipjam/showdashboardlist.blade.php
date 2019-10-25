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
              <div id="map" style="width:100%; height: 500px;"></div>


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
                    <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="uniques_today">Loading...</p>
                    <small style="color: white; padding-left: 15px; text-transform: uppercase;">Uniques today</small>
                  </div>
                </div>

                <div style="width:calc(50% - 5px); display: inline-block; height:94px;">
                  <div style="background-color: #e72e2e; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                    <i class="fa fa-eye" style="color: white; font-size: 41px; margin-top: 25px;"></i>
                  </div>
                  <div style="background-color: #ec5d5d; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                    <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="individuals_exposed_current">Loading...</p>
                    <small style="color: white; padding-left: 15px; text-transform: uppercase;">Individuals exposed current</small>
                  </div>
                </div>

                <div style="width:calc(50% - 5px); display: inline-block; height:94px;">
                  <div style="background-color: #e72e2e; width: 33%; display: inline-block; float:left; height: 100%; border-radius: 5px 0 0 5px; text-align: center;">
                    <i class="fa fa-eye" style="color: white; font-size: 41px; margin-top: 25px;"></i>
                  </div>
                  <div style="background-color: #ec5d5d; width: 67%; display: inline-block; float:right; height: 100%; border-radius: 0 5px 5px 0;">
                    <p style="color: white;font-size: 30px; margin-top: 20px; padding-left: 15px; margin-bottom: 9px;" id="individuals_exposed_today">Loading...</p>
                    <small style="color: white; padding-left: 15px; text-transform: uppercase;">Individuals exposed today</small>
                  </div>
                </div>

              </div>

            <div class="row">

              <div class="graph-container" style="padding: 10px; width: 100%;">
                <div class="graphcol" style="width: 50%; margin: 0; float: left; border: 1px solid !important;margin-top: 20px;">
                  <h1>Best Performance</h1>
                  <div class="graphcell" style="padding: 1px;">
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

              <div class="graph-container" style="padding: 10px; width: 100%;">
                <div class="graphcol" style="width: 50%; margin: 0; float: left; border: 1px solid !important;">
                  <h1>Worst Performance</h1>
                  <div class="graphcell" style="padding: 1px;">
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
    <script>

    let venues = {{ $data['venuesJson'] }};

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(-30.341529, 25.322594),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: [
            {elementType: 'geometry', stylers: [{color: '#08304b'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#000000'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#FFFFFF'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#000000'}]
            },
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'poi',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#0e5064'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#165f71'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#165f71'}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#165f71'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#165f71'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#165f71'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#9dd5ff'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#FFFFFF'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#000000'}]
            }
          ]
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    let markers = [];
    let no_lat_long_count = 0;


    $.each(venues, function(i, venue) {
      if (venue.latitude === null || venue.latitude === '') {
        no_lat_long_count++;
      } else {
        let ico = '';

        if (venue.track_type === 'venue' || venue.track_type === '' || venue.track_type === null) {
          if (venue.status === 'Online') {
            ico = 'http://hiphub.hipzone.co.za/img/retail_marker.png'
          } else {
            ico = 'http://hiphub.hipzone.co.za/img/offline_retail_marker.gif'
          }
        } else {
          if (venue.status === 'Online') {
            ico = 'http://hiphub.hipzone.co.za/img/billboard_marker.png'
          } else {
            ico = 'http://hiphub.hipzone.co.za/img/offline_billboard_marker.gif'
          }
        }

        marker = new google.maps.Marker({
          position: new google.maps.LatLng(venue.latitude,  venue.longitude),
          map: map,
          icon: ico
        });
        markers.push(marker)
      }
    });

    if (no_lat_long_count !== 0) {
      $('#warn_no_locations_found').html(`<i class="fa fa-info-circle" style="margin-right: 10px"></i>${no_lat_long_count} venues do not have location data`)
      $('#warn_no_locations_found').slideDown('fast');
    }

    var markerCluster = new MarkerClusterer(map, markers,
          {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent(locations[i][0]);
        infowindow.open(map, marker);
      }
      })(marker, i));

    </script>

    <script>

    // $(function() {
    //   console.log("begin");
    //     showSelectedVenues();
    // });


      $(document).ready(function(){
        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('hipjam_load_customer_stats_for_dash'); }}",
            success: function(response) {


              $('#individuals_exposed_current').html(response.individualsExposedCurrent);
              $('#individuals_exposed_today').html(response.individuals_exposed_today);
              $('#uniques_today').html(response.uniques_today);

              $('#individuals_exposed_current').addClass( "font36" );
              $('#individuals_exposed_today').addClass( "font36" );
              $('#uniques_today').addClass( "font36" );

            },
            error: function(error){

            }
          });

          $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('hipjam_graph_data'); }}",
            success: function(brandjson) {
              console.log(brandjson);
              showBrandPerformanceGraphs(brandjson);
            },
            error: function(error) {
              debugger;
            }
          });


          function showBrandPerformanceGraphs(brandData) {

            var highest5Sessions = new FusionCharts({
              type: "column2d",
              renderAt: "chartcol1row1",
              width: "98%",
              height: "300",
              dataFormat: "json",
              dataSource: brandData["highest5Sessions"]
              });
              highest5Sessions.render("chartcol1row1");

              var lowest5session = new FusionCharts({
              type: "column2d",
              renderAt: "chartcol2row1",
              width: "98%",
              height: "300",
              dataFormat: "json",
              dataSource: brandData["lowest5Sessionsdata"]
              });
              lowest5session.render("chartcol2row1");

            }



      })


    venuesJson = {{ $data['venuesJson'] }};

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

        sitename = $( "#src-sitename" ).val();
        macaddress = $( "#src-macaddress" ).val();

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
                      <th>Location</th>\n\
                      <th>Contact</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {


            /*editbutton = '<a href="{{ url('hipwifi_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n';*/
            if(value["apisitename"] != 'no_venue'){
              viewbutton = '<a href="{{ url('hipjam_viewvenue'); }}/' + value["id"]+'/'+value["apisitename"] + '" class="btn btn-default btn-sm">view</a>\n';
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
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + value["location"]  + '</td>\n\
                      <td> ' + value["contact"]  + '</td>\n\
                      <td> ' + viewbutton + '</td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#venueTable" ).html( table );
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
        function(){
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

    <style>
      .dash-widget {
        border: 1px solid #D1D1D1D1; padding: 25px;    margin-right: 10px;    height: 142px;
      }
      @media only screen and (max-width: 1512px) {
        .dash-widget {
          height: 182px !important;
        }
        .text-tracked{height: 45px;}
      }

      .font36{
        font-size: 36px;
      }
      .graph-container{
        display:inline;
      }
    </style>

  </body>

@stop
