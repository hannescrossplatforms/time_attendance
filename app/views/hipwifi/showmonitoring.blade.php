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

  <!-- Bootstrap core JavaScript
      ================================================== --> 
  <!-- Placed at the end of the document so the pages load faster --> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
  <script src="js/bootstrap.min.js"></script> 
  <script src="js/prefixfree.min.js"></script>

  <script>

    function refreshPage() {
      $.ajax({
        url: 'http://hiphub.hipzone.co.za/hipwifi_populatemonitoring',
            type: 'get',
            dataType: 'html',
            success: function(result) {
                $("#page-replace-div").html(result);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });
    }

    $('document').ready(function(){
      
      refreshPage();
      
      (function loop() {
        setTimeout(function () {
          this.refreshPage();
          loop()
        }, 15000);
      }());


    });

    

  </script>

</body>
@stop
