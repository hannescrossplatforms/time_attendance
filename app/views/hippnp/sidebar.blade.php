
        <div class="col-sm-3 col-md-3 sidebar sidebarActive">
          <div class="logo pull-left">
            <a href="{{ url('dashboard'); }}"><img src="img/logo_hiphub_small.png" class="img-responsive" /></a>
            </div>
            <div class="productTitle pull-left">
              <h2><strong>T&A</strong></h2>
            </div>
            <div class="clearfix"></div>
            <ul class="nav nav-sidebar nav-products">
              @if (\User::hasAccess("superadmin"))
              <li class="li-wifi"><a href="{{ url('hipwifi_showdashboard'); }}"><i class="fa fa-wifi"></i></a></li>
              @endif
              @if (\User::hasAccess("superadmin"))
              <li class="li-rm" class="active"> <a href="{{ url('hiprm_showdashboard'); }}"><i class="fa fa-credit-card"></i></a> </li>
              @endif
              @if (\User::hasAccess("superadmin"))
              <li class="li-jam"><a href="{{ url('hipjam_showdashboard'); }}"><i class="fa fa-shopping-cart"></i></a></li>
              @endif
              @if (\User::hasAccess("superadmin"))
              <li class="li-engage"><a href="{{ url('hipengage_showevents'); }}"><i class="fa fa-bullhorn"></i></a></li>
              @endif
              @if (\User::hasAccess("superadmin"))
              <li class="li-reports"><a href="{{ url('hipreports_showdashboard'); }}"><i class="fa fa-bar-chart"></i></a></li>
              @endif
              @if (\User::hasAccess("superadmin"))
              <li class="li-tna"><a href="{{ url('hiptna_showdashboard'); }}"><i class="fa fa-clock-o fa-3x"></i></a></li>
              @endif
               @if (\User::hasAccess("admin") || \User::hasAccess("superadmin"))
              <li class="li-adm"><a href="{{ url('admin_showdashboard'); }}"><i class="fa fa-gear fa-3x"></i></a></li>
              @endif
              @if (\User::hasAccess("admin"))
               <!--  <li @if ( $data['currentMenuItem'] == "User Admin" ) class="active" @endif >
                  <a href="{{ url('useradmin_showusers'); }}"><i class="fa fa-user"></i>
                </li> -->
              @endif
              <li><a href="{{ url('support'); }}"><i class="fa fa-phone"></i></a></li>
              <li><a href="{{ url('logout'); }}"><i class="fa fa-unlock"></i></a></li>
            </ul>

          <ul class="nav subNav">
              <li @if ( $data['currentMenuItem'] == "Dashboard" ) class="active" @endif><a href="{{ url('hippnp_showdashboard'); }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>



              <div id="instance_menus">
                  <ul class="subNav2">

                    <li><a href="{{ url('hippnp_showdashboard'); }}">Pick n Pay </a></li>

                  </ul>
              </div>
              <div id="picknpay_settings">
                <li><a href="{{ url('hippnp/picknpay_manage_store_categories'); }}"><i class="fa fa-gears"></i>Category Management</a></li>
                <li><a href="{{ url('hippnp/picknpay_beacon_management'); }}"><i class="fa fa-gears"></i>Beacon Management</a></li>
              </div>

              @if (\User::hasAccess("superadmin"))
                <div id="instance_menus">
                    <ul class="subNav2">
                      <li><a href="{{ url('hipbidvest_showdashboard'); }}">Bidvest </a></li>
                    </ul>
                </div>
                <div id="bidvest_settings">
                  <li><a href="{{ url('hipbidvest/bidvest_manage_store_categories'); }}"><i class="fa fa-gears"></i>Category Management</a></li>
                  <li><a href="{{ url('hipbidvest/bidvest_beacon_management'); }}"><i class="fa fa-gears"></i>Beacon Management</a></li>
                </div>
              @endif



              <div id="exception_manage_menus" style="display: none">
                @if( Session::get('currentInstance') != "NR01" && Session::get('currentInstance') != "NR02" )
                    <li @if ( $data['currentMenuItem'] == "Exception Reports" ) class="active" @endif ><a href="{{ url('hiptna_exceptionreports'); }}"><i class="fa fa-bell-o"></i>Exception Reports</a></li>
                @endif
                <li @if ( $data['currentMenuItem'] == "Management Console" ) class="active" @endif ><a href="{{ url('hiptna_showSettings'); }}"><i class="fa fa-desktop"></i>Management Console</a></li>
                  <ul class="subNav2">

                    <li @if ( $data['currentMenuItem'] == "Settings" ) class="active" @endif ><a href="{{ url('hiptna_showSettings'); }}">Settings</a></li>
                    @if( Session::get('currentInstance') != "NR01" && Session::get('currentInstance') != "NR02" )
                        <li @if ( $data['currentMenuItem'] == "Staff Lookup" ) class="active" @endif ><a href="{{ url('hiptna_showStafflookup'); }}">Staff Lookup</a></li>
                        <li @if ( $data['currentMenuItem'] == "Instant Messaging" ) class="active" @endif ><a href="{{ url('hiptna_showInstantmessaging'); }}">Instant Messaging</a></li>
                        <li @if ( $data['currentMenuItem'] == "Beacon Battery Monitor" ) class="active" @endif ><a href="{{ url('hiptna_showBeaconbatterymonitor'); }}">Beacon Battery Monitor</a></li>
                    @endif
                  </ul>
              </div>

          </ul>
          <div class="clearfix"></div>

        </div>


