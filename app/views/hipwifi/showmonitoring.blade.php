@extends('layout')

@section('content')

<body class="hipWifi">
  <div id="buildtable"></div>
  <div class="container-fluid">
    <div class="row">
      @include('hipwifi.sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Venue Monitoring</h1>
            <div id="page-replace-div"></div>
        </div>
    </div>
  </div>

  <div id="viewVenueModals"><span id="closeViewModal">x</span></div>

  

  <script>

    $('document').ready(function(){
      $.ajax({
        url: 'http://hiphub.hipzone.co.za/hipwifi_populatemonitoring',
            type: 'get',
            dataType: 'html',
            success: function(result) {
              debugger;
                $("#page-replace-div").html(result);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              debugger;
            }
        });
    });

      

  </script>

</body>
@stop
