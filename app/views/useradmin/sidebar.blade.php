

        <div class="col-sm-3 col-md-2 sidebar">
          <div class="logo">
            <a href="{{ url('dashboard'); }}">{{ HTML::image('/img/logo_hiphub.png', 'a ', array('class' => 'img-responsive')) }}</a>
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
            <li class="li-tna"><a href="{{ url('hiptna_showdashboard'); }}"><i class="fa fa-clock-o fa-3x"></i><strong>T&A</strong></a></li>
            @endif
          </ul>
          <ul class="nav nav-sidebar nav-user"> 
            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
            <li @if ( $data['currentMenuItem'] == "Dashboard" ) class="active" @endif > 
              <a href="{{ url('useradmin_showusers'); }}"><i class="fa fa-user"></i><strong>User Admin</strong></a>
            </li>
            @endif
            <li @if ( $data['currentMenuItem'] == "HipWifi" ) class="active" @endif >
              <a href="{{ url('support'); }}"><i class="fa fa-phone"></i><strong>Support</strong></a>
            </li>
            <li><a href="{{ url('logout'); }}"><i class="fa fa-unlock"></i><strong>Logout</strong></a></li>
          </ul>
        </div>
