@extends('layout')

@section('content')

<body class="hipReports">
<div class="container-fluid">
  <div class="row">
    @include('hipreports.sidebar')

    <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
      <h1 class="page-header">Dashboard</h1>
      <div class="row">
        <div class="col-md-4">
          <!-- <h3 class="mod-title">Total Minutes Browzing</h3> -->
        </div>
      </div>
      <br>
      <div class="row">
      </div>
    </div>

  </div>
</div>

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/prefixfree.min.js"></script>




</body>
@stop
