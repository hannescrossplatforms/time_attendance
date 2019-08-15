

        <div class="col-sm-3 col-md-2 sidebar" @if ( \User::isVicinity() ) style="background" @endif>
          <div class="logo">
            @if (\User::isVicinity())
              <a href="{{ url('dashboard'); }}">{{ HTML::image('/img/vicinity_logo_full.png', 'a ', array('class' => 'img-responsive', 'style' => 'margin: 0 auto;')) }}</a>
            @else
              <a href="{{ url('dashboard'); }}">{{ HTML::image('/img/logo_hiphub.png', 'a ', array('class' => 'img-responsive')) }}</a>
            @endif
          </div>
          <ul class="nav nav-sidebar nav-products">
            @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin")) 
            <li class="li-wifi"><a href="{{ url('hipwifi_showdashboard'); }}"><i class="fa fa-wifi"></i><strong>WIFI</strong></a></li>
            @endif
            @if (\User::hasProduct("HipRM") || \User::hasAccess("superadmin")) 
            <li class="li-rm"><a href="{{ url('hiprm_showdashboard'); }}"><i class="fa fa-credit-card"></i><strong>SURVEYS</strong></a></li>
            @endif
            @if (\User::hasProduct("HipJAM") || \User::hasAccess("superadmin")) 
            <li class="li-jam"><a href="{{ url('hipjam_showdashboard'); }}"><i class="fa fa-shopping-cart"></i><strong>TRACK</strong></a></li>
            @endif
            @if (\User::hasProduct("HipENGAGE") || \User::hasAccess("superadmin")) 
            <li class="li-engage"><a href="{{ url('hipengage_showevents'); }}"><i class="fa fa-bullhorn"></i><strong>ENGAGE</strong></a></li>
            @endif
            @if (\User::hasProduct("HipREPORTS") || \User::hasAccess("superadmin")) 
            <li class="li-reports"><a href="{{ url('hipreports_showdashboard'); }}"><i class="fa fa-bar-chart"></i><strong>REPORTS</strong></a></li>
            @endif
            @if (\User::hasProduct("HipTnA") || \User::hasAccess("superadmin")) 
            <li class="li-tna"><a href="{{ url('hiptna_showdashboard'); }}"><i class="fa fa-clock-o fa-2x"></i><strong>T&A</strong></a></li>
            @endif
            @if (\User::hasAccess("admin") || \User::hasAccess("superadmin")) 
              <li class="li-adm"><a href="{{ url('admin_showdashboard'); }}"><i class="fa fa-gear fa-2x"></i><strong>Admin</strong></a></li>
            @endif
          </ul>
          <ul class="nav nav-sidebar nav-user"> 
            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
         <!--    <li @if ( $data['currentMenuItem'] == "Dashboard" ) class="active" @endif > 
              <a href="{{ url('useradmin_showusers'); }}"><i class="fa fa-user"></i><strong>User Admin</strong></a>
            </li> -->
            @endif
            <li @if ( $data['currentMenuItem'] == "HipWifi" ) class="active" @endif >
              <a href="{{ url('support'); }}"><i class="fa fa-phone"></i><strong>Support</strong></a>
            </li>
            <li><a href="{{ url('logout'); }}"><i class="fa fa-unlock"></i><strong>Logout</strong></a></li>
          </ul>
        </div>

        @if (\User::isVicinity())
        <style>
          .sidebar {
            background-image: url(https://i.ibb.co/KbKv0Fc/vicinity-bg-copy.png);
            background-color: transparent;
            background-repeat: no-repeat;
            background-size: cover;
            width: 273px;
            background-position: center;
          }
          .nav.nav-sidebar.nav-products {
            background: rgba(0,0,0,0.3);
          }
          .nav.subNav {
            padding: 0;
          }
          .productTitle.pull-left {
            background-color: transparent;
          }
          .productTitle.pull-left h2 {
            background-color: transparent;
          }
          .logo.pull-left {
            background: rgba(0,0,0,0.3);
            padding: 32px 0px;
          }
          .sidebarActive .subNav li.active a {
            background-color: rgba(255,255,255,0.18);
            height: 60px;
          }
          .hipJAM .sidebarActive .subNav {
            background: transparent !important;
          }
          li.li-jam a:before {
            background: transparent !important;
          }
          li.li-jam a i, .nav-sidebar > li > a > i {
            background: transparent !important;
          }
          .sidebarActive .subNav li {
            border: none !important;
          }
          /* .vacinity-layer {
            background-color: rgba(0, 0, 0, 0.2);
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
          } */
          .nav-products li a:before {
            background-color: transparent !important;
          }
          .hipJAM .page-header {
            color: #9dd1ed !important;
          }
          .sidebarActive .logo img {
            margin-top: -12px;
          }
          li.li-jam a {
            background-color: #3d728b !important;
          }
          @media (max-width: 1555px) {
            .main {
              padding-left: 120px;
            }
          }
          li.li-jam a {
            color: #5ba5d8;
          }
          .modstattitle {
            background-color: #9DD1ED;
          }
        </style>
        @endif