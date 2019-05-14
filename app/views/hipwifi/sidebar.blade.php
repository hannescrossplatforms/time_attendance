    <div class="col-sm-3 col-md-3 sidebar sidebarActive">
      <div class="logo pull-left"> <a href="{{ url('dashboard'); }}"><img src="/img/logo_hiphub_small.png" class="img-responsive" /></a> </div>
      <div class="productTitle pull-left">
        <h2><strong>WIFI</strong></h2>
      </div>
      <div class="clearfix"></div>

      <ul class="nav nav-sidebar nav-products">
        @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin")) 
        <li class="li-wifi" class="active"><a href="{{ url('hipwifi_showdashboard'); }}"><i class="fa fa-wifi"></i></a></li>
        @endif
        @if (\User::hasProduct("HipRM") || \User::hasAccess("superadmin")) 
        <li class="li-rm"> <a href="{{ url('hiprm_showdashboard'); }}"><i class="fa fa-credit-card"></i></a> </li>
        @endif
        @if (\User::hasProduct("HipJAM") || \User::hasAccess("superadmin")) 
        <li class="li-jam"><a href="{{ url('hipjam_showdashboard'); }}"><i class="fa fa-shopping-cart"></i></a></li>
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
              <!-- <li class="li-adm"><a href="{{ url('admin_showdashboard'); }}"><i class="fa fa-gear fa-3x"></i></a></li> -->
        @endif
        @if (\User::hasAccess("admin") || \User::hasAccess("superadmin")) 
              <li class="li-adm"><a href="{{ url('admin_showdashboard'); }}"><i class="fa fa-gear fa-3x"></i></a></li>
        @endif
        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
        <!--   <li @if ( $data['currentMenuItem'] == "User Admin" ) class="active" @endif > 
            <a href="{{ url('useradmin_showusers'); }}"><i class="fa fa-user"></i>
          </li> -->
        @endif
        <li><a href="{{ url('support'); }}"><i class="fa fa-phone"></i></a></li>
        <li><a href="{{ url('logout'); }}"><i class="fa fa-unlock"></i></a></li>
      </ul>

      <ul class="nav subNav">

        <li @if ( $data['currentMenuItem'] == "Dashboard" ) class="active" @endif><a href="{{ url('hipwifi_showdashboard'); }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
       
        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller") || \User::hasAccess("brandadmin"))
        <li @if ( $data['currentMenuItem'] == "Brand Management" ) class="active" @endif><a href="{{ url('hipwifi_showbrands'); }}"><i class="fa fa-rocket"></i>Brand Management</a></li>
        @endif

<!--         @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
        <li @if ( $data['currentMenuItem'] == "Brand Administrators" ) class="active" @endif><a href="{{ url('hipwifi_showusers'); }}"><i class="fa fa-users"></i>Brand Administrators</a></li>
        @endif -->

        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller") || \User::hasAccess("brandadmin") || \User::hasAccess("mediamanager"))

        <li @if ( $data['currentMenuItem'] == "Media Management" ) class="active" @endif><a href="{{ url('hipwifi_showbrandmedia'); }}"><i class="fa fa-image"></i>Media Management</a></li>

       {{--  <li @if ( $data['currentMenuItem'] == "Media Management" ) class="active" @endif><a href="{{ url('hipwifi_showmedias'); }}"><i class="fa fa-image"></i>Media Management</a></li> --}}
        @endif
        
        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller") || \User::hasAccess("brandadmin"))
        <li @if ( $data['currentMenuItem'] == "Venue Management" ) class="active" @endif><a href="{{ url('hipwifi_showvenues'); }}"><i class="fa fa-sitemap"></i>Venue Management</a></li>
        @endif

        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller") || \User::hasAccess("brandadmin"))
        <li @if ( $data['currentMenuItem'] == "User Management" ) class="active" @endif><a href="{{ url('hipwifi_showwifiusers'); }}"><i class="fa fa-users"></i>User Management</a></li>
        @endif

        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin"))
        <li @if ( $data['currentMenuItem'] == "Server Management" ) class="active" @endif><a href="{{ url('hipwifi_showservers'); }}"><i class="fa fa-server"></i>Server Management</a></li>
        @endif

        <li @if ( $data['currentMenuItem'] == "Venue Monitoring" ) class="active" @endif><a href="{{ url('hipwifi_showmonitoring'); }}"><i class="fa fa-laptop"></i>Venue Monitoring</a></li>

      </ul>
      
      <div class="clearfix"></div>
    </div>
    <script> currentProduct = "hipWIFI"; </script>
    
