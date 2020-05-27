@extends('angle_admin_layout')

@section('content')
<section class="section-container">
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Dashboard</div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">



    @if (\User::hasAccess("superadmin") || \User::hasAccess("admin"))
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/user_admin_banner_2.jpeg');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/useradmin_showusers">Visit</a></div>
              <p><span class="text-lg" style="font-size: 2rem">USER<br/> ADMIN</span></p>
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif

    @if (\User::hasAccess("superadmin"))
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/rap_banner.jpeg');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/admin_showrolesandpermissions">Visit</a></div>
              <p><span class="text-lg" style="font-size: 2rem">ROLES &<br/> PERMISSIONS</span></p>
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif

    @if (\User::hasAccess("superadmin") || (\User::isVicinity() && \User::hasAccess("admin")))
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/brand_banner.jpeg');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/admin_showbrands">Visit</a></div>
              <p><span class="text-lg" style="font-size: 2rem">BRAND<br/> MANAGEMENT</span></p>
            </div>
          </div>
        </div><!-- END card-->
      </div>
    </div>
    @endif


    @if (\User::hasAccess("superadmin") || (\User::isVicinity() && \User::hasAccess("admin")))
    <div class="col-xl-4">
      <!-- START card-->
      <div class="card">
        <div class="row row-flush">
          <div class="col-5 d-flex align-items-center justify-content-center" style="background: url('http://hiphub.hipzone.co.za/img/venue_banner.jpeg');background-size: cover;"></div>
          <div class="col-7">
            <div class="p-3">
              <div class="float-right"><a class="btn btn-primary btn-sm" href="http://hiphub.hipzone.co.za/admin_showvenues">Visit</a></div>
              <p><span class="text-lg" style="font-size: 2rem">VENUE<br/> MANAGEMENT</span></p>
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

