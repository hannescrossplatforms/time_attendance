@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="alert alert-danger" id="warn_no_lat_long" style="display: none;"></div>
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

    <div class="row">

      <div class="col-xl-2 col-md-6">
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-primary-dark justify-content-center rounded-left"><em class="fas fa-map-signs fa-3x"></em></div>
          <div class="col-8 py-3 bg-primary rounded-right">
            <div class="h2 mt-0" id="usersthismonth"></div>
            <small id="userslastmonth"></small>
            <div class="text-uppercase">Users</div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-6">
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-purple-dark justify-content-center rounded-left"><em class="fas fa-building fa-3x"></em></div>
          <div class="col-8 py-3 bg-purple rounded-right">
            <div class="h2 mt-0" id="newusersthismonth"></div>
            <small id="newuserslastmonth"></small>
            <div class="text-uppercase">New Users</div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-lg-6 col-md-12">
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-green-dark justify-content-center rounded-left"><em class="fas fa-history fa-3x"></em></div>
          <div class="col-8 py-3 bg-green rounded-right">
            <div class="h2 mt-0" id="sessionsthismonth"></div>
            <small id="sessionslastmonth"></small>
            <div class="text-uppercase">Sessions</div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-lg-6 col-md-12">
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-info justify-content-center rounded-left"><em class="fas fa-vector-square fa-3x"></em></div>
          <div class="col-8 py-3 bg-info-dark rounded-right">
            <div class="h2 mt-0" id="dwelltimethismonth"></div>
            <small id="dwelltimelastmonth"></small>
            <div class="text-uppercase">Avg Dwell (min)</div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-6">
        <div class="card flex-row align-items-center align-items-stretch border-0">
          <div class="col-4 d-flex align-items-center bg-danger justify-content-center rounded-left"><em class="fas fa-clock fa-3x"></em></div>
          <div class="col-8 py-3 bg-danger-dark rounded-right">
            <div class="h2 mt-0" id="datausedthismonth"></div>
            <small id="datausedlastmonth"></small>
            <div class="text-uppercase">Data Used (Gb)</div>
          </div>
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo" id="cardChart9">
          <div class="card-header">
          <a class="float-right" href="javascript:void(0);" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
          <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
            <div class="card-title">Venues</div>
          </div>
          <div class="card-body">
            <div class="card-wrapper no-overflow">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Active</th>
                  <th class="success">Online</th>
                  <th class="danger">Offline</th>
                </tr>
              </thead>
              <tbody>
                @foreach($brands as $brand)
                  <tr>
                    <td>{{ $brand }}</td>
                    <td>{{ $count[array_search($brand, $brands)] }} </td>
                    <td> <span class="badge badge-success">{{ $onlinevenues[array_search($brand, $brands)] }}</span></td>
                    <td><span class="badge badge-danger">{{ $offlinevenues[array_search($brand, $brands)] }}</span></td>
                  </tr>
                @endforeach
                </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>


<script type="text/javascript">

$(document).ready(function(){

  get_dashboard_details1();
  get_dashboard_details2();

})

function get_dashboard_details1() {
  $.ajax({

    url:'{{ url('dashboard_details1') }}',
    type:'get',
    dataType:'json',

    success:function(data){
    $('#wifi_total_users').html(data['users']);
    $('#wifi_online_users').html(data['onlineusers']);

    $('#usersthismonth').html( data['usersthismonth'] );
    $('#userslastmonth').html('Last month: ' + data['userslastmonth'] );

    $('#newusersthismonth').html( data['newusersthismonth'] );
    $('#newuserslastmonth').html('Last month: ' + data['newuserslastmonth'] );
}

})
}





function get_dashboard_details2() {
  $.ajax({

    url:'{{ url('dashboard_details2') }}',
    type:'get',
    dataType:'json',

    success:function(data){
      $('#sessionsthismonth').html( data['sessionsthismonth'] );
      $('#sessionslastmonth').html('Last month: ' + data['sessionslastmonth'] );



      $('#dwelltimethismonth').html( data['avgdwelltimethismonth'] );
      $('#dwelltimelastmonth').html('Last month: ' + data['avgdwelltimelastmonth'] );


      $('#datausedthismonth').html( data['thismonthdata'] );
      $('#datausedlastmonth').html('Last month: ' + data['lastmonthdata'] );




  }

  });

}

</script>


<script>
  var active_infowindow = null;
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

    var map_brands = {{$data['mapVenues']}};
    var no_lat_long_count = 0;
    var markers = [];

    $.each(map_brands, function(i, venues) {

      if (venues.length !== 0) {
        $.each(venues, function(i, venue) {
          if ((venue.latitude === null || venue.latitude === '')) {
            console.log('No LatLong')
            no_lat_long_count++;
          } else {
            console.log('Has LatLong')
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(parseFloat(venue.latitude), parseFloat(venue.longitude)),
              map: map,
              icon: (($.now() / 1000) - 300 < venue.last_seen) ? 'http://hiphub.hipzone.co.za/img/retail_marker.png' : 'http://hiphub.hipzone.co.za/img/offline_retail_marker.gif',
              venue_id: venue.id
            });
             // INFOWINDOW POPUP
            google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
              return function() {

                  let info_window = new google.maps.InfoWindow({
                    content: `<div><strong>Venue Name:</strong> ${venue.sitename} </div>`
                  });
                  info_window.open(map, marker);
                  active_infowindow = info_window;

              }

            })(marker, i));
            marker.addListener('mouseout', function() {
              active_infowindow.close();
            });
            markers.push(marker);
          }
        });
      }


    });

    var markerCluster = new MarkerClusterer(map, markers, {
                  imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });

    if (no_lat_long_count > 0) {
      $('#warn_no_lat_long').html(`<i class="fas fa-exclamation" style="margin-right: 10px;"></i>${no_lat_long_count} venues do not have location data.`)
      $('#warn_no_lat_long').slideDown('fast');
    }


</script>

@stop






