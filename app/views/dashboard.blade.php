@extends('layout')

@section('content')

<body class="dashboard">
<div class="container-fluid">
  <div class="row">
    @include('sidebar')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Dashboard</h1>
      <section id="dashLinks" class="row">
        @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin")) 
        <div class="col-md-2"> 
          <a href="{{ url('hipwifi_showdashboard'); }}" class="icon-button dashwifi">
              <i class="fa fa-wifi"></i>
                <h4><span></span>WIFI</h4>
            </a>
        </div>
        @endif
        @if (\User::hasProduct("HipRM") || \User::hasAccess("superadmin")) 
        <div class="col-md-2">
          <a href="{{ url('hiprm_showdashboard'); }}" class="icon-button dashrm">
              <i class="fa fa-credit-card"></i>
                <h4><span></span>SURVEYS</h4>
            </a>
        </div>
        @endif
        @if (\User::hasProduct("HipJAM") || \User::hasAccess("superadmin")) 
        <div class="col-md-2">
          <a href="{{ url('hipjam_showdashboard'); }}" class="icon-button dashjam">
              <i class="fa fa-shopping-cart"></i>
                <h4><span></span>TRACK</strong></h4>
            </a>
        </div>
        @endif
        @if (\User::hasProduct("HipENGAGE") || \User::hasAccess("superadmin")) 
        <div class="col-md-2">
          <a href="{{ url('hipengage_showevents'); }}" class="icon-button dashengage">
              <i class="fa fa-bullhorn"></i>
                <h4><span></span>ENGAGE</strong></h4>
          </a>
        </div>
        @endif
        @if (\User::hasProduct("HipREPORTS") || \User::hasAccess("superadmin")) 
        <div class="col-md-2">
          <a href="{{ url('hipreports_showdashboard'); }}" class="icon-button dashreports">
              <i class="fa fa-bar-chart"></i>
                <h4><span></span>REPORTS</strong></h4>
          </a>
        </div>
        @endif
      </section>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/prefixfree.min.js"></script>
</body>

@stop