@extends('angle_dashboard_layout')

@section('content')
<section class="section-container">
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Products</div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">


    @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin")) 
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/connect_banner.jpg');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/hipwifi_showdashboard">Open</a></div>
              <p><span class="text-lg" style="font-size: 2rem">CONNECT</span></p>
              <p><strong>Premium Hospitality WiFi</strong></p>
              <!-- <p>Donec posuere neque in elit luctus tempor consequat enim egestas. Nulla dictum egestas leo at lobortis.</p> -->
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif

    @if (\User::hasProduct("HipJAM") || \User::hasAccess("superadmin")) 
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/track_banner.png');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="/hipjam_showdashboard">Open</a></div>
              <p><span class="text-lg" style="font-size: 2rem">TRACK</span></p>
              <p><strong>Retail/Consumer Intelligence</strong></p>
              <!-- <p>Donec posuere neque in elit luctus tempor consequat enim egestas. Nulla dictum egestas leo at lobortis.</p> -->
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif

    @if (\User::hasProduct("HipENGAGE") || \User::hasAccess("superadmin")) 
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/engage_banner.png');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/hipengage_showevents">Open</a></div>
              <p><span class="text-lg" style="font-size: 2rem">ENGAGE</span></p>
              <p><strong>Campaign / Event Management</strong></p>
              <!-- <p>Donec posuere neque in elit luctus tempor consequat enim egestas. Nulla dictum egestas leo at lobortis.</p> -->
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif

    @if (\User::hasProduct("HipTnA") || \User::hasAccess("superadmin")) 
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/ta_banner.jpg');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/hiptna_showdashboard">Open</a></div>
              <p><span class="text-lg" style="font-size: 2rem">STAFF TRACKER</span></p>
              <p><strong>App and Beacon based Time & Attendance</strong></p>
              <!-- <p>Donec posuere neque in elit luctus tempor consequat enim egestas. Nulla dictum egestas leo at lobortis.</p> -->
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif
    </div>
  </div>
</section>


@stop