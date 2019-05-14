        <div class="col-sm-3 col-md-3 sidebar sidebarActive">
          <div class="logo pull-left">
            <a href="{{ url('dashboard'); }}"><img src="{{ asset('img/logo_hiphub_small.png') }}" class="img-responsive" /></a>
            </div>
            <div class="productTitle pull-left">
              <h2><strong>TRACK</strong></h2>
            </div>
            <div class="clearfix"></div>
            <ul class="nav nav-sidebar nav-products">
              @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin")) 
              <li class="li-wifi"><a href="{{ url('hipwifi_showdashboard'); }}"><i class="fa fa-wifi"></i></a></li>
              @endif
              @if (\User::hasProduct("HipRM") || \User::hasAccess("superadmin")) 
              <li class="li-rm"> <a href="{{ url('hiprm_showdashboard'); }}"><i class="fa fa-credit-card"></i></a> </li>
              @endif
              @if (\User::hasProduct("HipJAM") || \User::hasAccess("superadmin")) 
              <li class="li-jam" class="active"><a href="{{ url('hipjam_showdashboard'); }}"><i class="fa fa-shopping-cart"></i></a></li>
              @endif
              @if (\User::hasProduct("HipENGAGE") || \User::hasAccess("superadmin")) 
              <li class="li-engage"><a href="{{ url('hipengage_showevents'); }}"><i class="fa fa-bullhorn"></i></a></li>
              @endif
              @if (\User::hasProduct("HipREPORTS") || \User::hasAccess("superadmin")) 
              <li class="li-reports"><a href="{{ url('hipreports_showdashboard'); }}"><i class="fa fa-bar-chart"></i></a></li>
              @endif
              @if (\User::hasProduct("HipTnA") || \User::hasAccess("superadmin")) 
              <li class="li-tna"><a href="{{ url('hiptna_showdashboard'); }}"><i class="fa fa-clock-o fa-3x"></i></a></li>
              @endif
              @if (\User::hasAccess("admin") || \User::hasAccess("superadmin")) 
              <li class="li-adm"><a href="{{ url('admin_showdashboard'); }}"><i class="fa fa-gear fa-3x"></i></a></li>
              @endif
              @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
 <!--                <li @if ( $data['currentMenuItem'] == "User Admin" ) class="active" @endif > 
                  <a href="{{ url('useradmin_showusers'); }}"><i class="fa fa-user"></i>
                </li> -->
              @endif
              <li><a href="{{ url('support'); }}"><i class="fa fa-phone"></i></a></li>
              <li><a href="{{ url('logout'); }}"><i class="fa fa-unlock"></i></a></li>
            </ul>

            <ul class="nav subNav">
                <li  @if ( $data['currentMenuItem'] == "Dashboard" ) class="active" @endif>
                    <a href="{{ url('hipjam_showdashboard'); }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    <ul class="subNav2">
                        <!-- <li><a href="#">Venue</a></li>
                        <li><a href="#">Zonal</a></li>
                        <li><a href="#">Group</a></li> -->
                        <!-- <li><a href="#">Marketing</a></li>
                        <li><a href="#">Operations</a></li> 
                        <li><a href="#">Executive</a></li> -->
                    </ul>
                </li>
                <!-- <li @if ( $data['currentMenuItem'] == "User Management" ) class="active" @endif><a href="{{ url('hipjam_showusers'); }}"><i class="fa fa-users"></i>User Management</a></li> -->

                <!-- <li @if ( $data['currentMenuItem'] == "Brand Management" ) class="active" @endif><a href="{{ url('hipjam_showbrands'); }}"><i class="fa fa-rocket"></i>Brand Management</a></li> -->
{{-- Sensor Monitoring branch content --}}
               {{--  <li @if ( $data['currentMenuItem'] == "Store Management" ) class="active" @endif><a href="{{ url('hipjam_showstores'); }}"><i class="fa fa-home"></i>Venue Management</a></li> --}}
                <li @if ( $data['currentMenuItem'] == "Brand Management" ) class="active" @endif><a href="{{ url('hipjam_showbrands'); }}"><i class="fa fa-rocket"></i>Brand Management</a></li>
               <li @if ( $data['currentMenuItem'] == "Venue Management" ) class="active" @endif><a href="{{ url('hipjam_showvenues'); }}"><i class="fa fa-home"></i>Venue Management</a></li>

                 <li @if ( $data['currentMenuItem'] == "Sensor Monitoring" ) class="active" @endif><a href="{{ url('hipjam_monitorsensors'); }}"><i class="fa fa-laptop"></i>Sensor Monitoring</a></li>
{{-- completed automated sensor files creation 2 content --}}
                

                <!-- <li @if ( $data['currentMenuItem'] == "Prospects Register" ) class="active" @endif><a href="{{ url('hipjam_showprospects'); }}"><i class="fa fa-user"></i>Prospects Register</a></li> -->
            </ul>
            <div class="clearfix"></div>
          
        </div>