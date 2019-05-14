@extends('layout')

@section('content')
<style type="text/css">
.modstattitle{
    /*background-color: #d3d3d3;#106f5d*/
    background-color: #58A5DA;
    height: 70px;
    padding: 10px;
}
.modstattitle h3{
    color: white;
}
#report_period {
    display: none;
}
</style>
<body class="hipTnA">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hiptna.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Overview Dashboard</h1>

            <?php if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
                      $pos = strpos($_SERVER['REQUEST_URI'],'public');
                      $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
                      $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
                  } else {
                      $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
                  }
             ?>
             <input type="hidden" id="url" name="" value="{{$url}}">

            <div class="container-fluid">
    

     

    <div class="row">


        
    </div> 

    <br><br>
    

    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/jquery-2.1.4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script> 

    <script src="{{ asset('js/fusioncharts.js') }}"></script>
    <script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
    <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>

    <script type="text/javascript">
      $(function() {
        availableInstances = "{{ Session::get('availableInstances') }}";
        currentInstance = "{{ Session::get('currentInstance') }}";
      });
    </script>

    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
    <script src="{{ asset('js/prefixfree.min.js') }}"></script>


    
            
        </div>
      </div>
    </div>
  
  </body>

@stop