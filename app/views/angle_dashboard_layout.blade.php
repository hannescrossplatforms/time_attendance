<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon/favicon.ico">
  <title>User Admin | HipHub</title>


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
  <link rel="stylesheet" href="{{asset('theme/vendor/whirl/dist/whirl.css') }}">
  <link rel="stylesheet" href="{{asset('theme/vendor/weather-icons/css/weather-icons.css') }}">
  <link rel="stylesheet" href="{{asset('theme/css/bootstrap.css') }}" id="bscss">
  <link rel="stylesheet" href="{{asset('theme/css/app.css') }}" id="maincss">
  <link rel="stylesheet" href="{{asset('theme/css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="wrapper">
      <!-- top navbar-->
      <header class="topnavbar-wrapper">
         <!-- START Top Navbar-->
         <nav class="navbar topnavbar">
            <!-- START navbar header-->
            <div class="navbar-header"><a class="navbar-brand" href="http://hiphub.hipzone.co.za/dashboard">
            @if (\User::isVicinity())
                  <div class="brand-logo"><img class="img-fluid" src="http://hiphub.hipzone.co.za/img/vicinity_logo_only.png" alt="App Logo" style="height: 30px;float: left;"> <h4 style="color: white; margin-top: 5px;">DASHBOARD</h4></div>
                  <div class="brand-logo-collapsed"><img class="img-fluid" src="http://hiphub.hipzone.co.za/img/vicinity_logo_only.png" alt="App Logo"></div>
               @else
                  <div class="brand-logo"><img class="img-fluid" src="http://hiphub.hipzone.co.za/img/logo_hiphub_small.svg" alt="App Logo" style="height: 30px;float: left;"> <h4 style="color: white; margin-top: 5px;">DASHBOARD</h4></div>
                  <div class="brand-logo-collapsed"><img class="img-fluid" src="http://hiphub.hipzone.co.za/img/logo_hiphub_small.svg" alt="App Logo"></div>
               @endif   
               </a></div><!-- END navbar header-->
            <!-- START Left navbar-->
            <ul class="navbar-nav mr-auto flex-row">
               <li class="nav-item">
                  <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops--><a class="nav-link d-none d-md-block d-lg-block d-xl-block" href="#" data-trigger-resize="" data-toggle-state="aside-collapsed"><em class="fas fa-bars"></em></a><!-- Button to show/hide the sidebar on mobile. Visible on mobile only.--><a class="nav-link sidebar-toggle d-md-none" href="#" data-toggle-state="aside-toggled" data-no-persist="true"><em class="fas fa-bars"></em></a></li><!-- START User avatar toggle-->
               <!-- START lock screen-->
               <li class="nav-item d-none d-md-block"><a class="nav-link" href="http://hiphub.hipzone.co.za/logout" title="Logout"><em class="icon-lock"></em></a></li><!-- END lock screen-->
            </ul><!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="navbar-nav flex-row">
               <!-- Search icon-->
              
               <li class="nav-item d-none d-md-block"><a class="nav-link" href="#" data-toggle-fullscreen=""><em class="fas fa-expand"></em></a></li><!-- START Alert menu-->
               <!-- START Offsidebar button-->
            </ul><!-- END Right Navbar-->
            <!-- START Search form-->
            
         </nav><!-- END Top Navbar-->
      </header><!-- sidebar-->

      <aside class="aside-container">
         <div class="aside-inner">
            <nav class="sidebar" data-sidebar-anyclick-close="">
               <ul class="sidebar-nav">
                  <li class="nav-heading"><span data-localize="sidebar.heading.HEADER">Main Navigation</span></li>

                  @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin")) 
                  <li>
                    <a href="http://hiphub.hipzone.co.za/hipwifi_showdashboard" title="WiFi">
                        <em class="fas fa-wifi"></em><span data-localize="sidebar.nav.WIFI">CONNECT</span>
                     </a>
                  </li>
                  @endif
                
                  @if (\User::hasProduct("HipJAM") || \User::hasAccess("superadmin")) 
                  <li>
                    <a href="/hipjam_showdashboard" title="Track">
                        <em class="fas fa-shopping-cart"></em><span data-localize="sidebar.nav.TRACK">TRACK</span>
                     </a>
                  </li>
                  @endif

                  @if (\User::hasProduct("HipENGAGE") || \User::hasAccess("superadmin")) 
                  <li>
                    <a href="http://hiphub.hipzone.co.za/hipengage_showevents" title="Engage">
                        <em class="fas fa-bullhorn"></em><span data-localize="sidebar.nav.ENGAGE">ENGAGE</span>
                     </a>
                  </li>
                  @endif
                  
                  @if (\User::hasProduct("HipTnA") || \User::hasAccess("superadmin")) 
                  <li>
                    <a href="http://hiphub.hipzone.co.za/hiptna_showdashboard" title="T&A">
                        <em class="fas fa-clock"></em><span data-localize="sidebar.nav.TA">STAFF TRACKER</span>
                     </a>
                  </li>
                  @endif

                  @if (\User::hasAccess("admin") || \User::hasAccess("superadmin")) 
                    <li>
                      <a href="http://hiphub.hipzone.co.za/admin_showdashboard" title="Administration">
                          <em class="fas fa-cog"></em><span data-localize="sidebar.nav.ADMINISTRATION">Admin</span>
                      </a>
                    </li>
                @endif

               </ul><!-- END sidebar nav-->
            </nav>
         </div><!-- END Sidebar (left)-->
      </aside><!-- offsidebar-->

   <script src="{{asset('theme/vendor/modernizr/modernizr.custom.js')}}"></script><!-- STORAGE API-->
   <script src="{{asset('theme/vendor/js-storage/js.storage.js')}}"></script><!-- SCREENFULL-->
   <script src="{{asset('theme/vendor/screenfull/dist/screenfull.js')}}"></script><!-- i18next-->
   <script src="{{asset('theme/vendor/i18next/i18next.js')}}"></script>
   <script src="{{asset('theme/vendor/i18next-xhr-backend/i18nextXHRBackend.js')}}"></script>
   <script src="{{asset('theme/vendor/jquery/dist/jquery.js')}}"></script>
   <script src="{{asset('theme/vendor/popper.js/dist/umd/popper.js')}}"></script>
   <script src="{{asset('theme/vendor/bootstrap/dist/js/bootstrap.js')}}"></script><!-- =============== PAGE VENDOR SCRIPTS ===============-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  
   <script src="{{asset('theme/vendor/jquery-slimscroll/jquery.slimscroll.js')}}"></script><!-- SPARKLINE-->
   <script src="{{asset('theme/vendor/jquery-sparkline/jquery.sparkline.js')}}"></script><!-- FLOT CHART-->
   <script src="{{asset('theme/vendor/flot/jquery.flot.js')}}"></script>
   <script src="{{asset('theme/vendor/jquery.flot.tooltip/js/jquery.flot.tooltip.js')}}"></script>
   <script src="{{asset('theme/vendor/flot/jquery.flot.resize.js')}}"></script>
   <script src="{{asset('theme/vendor/flot/jquery.flot.pie.js')}}"></script>
   <script src="{{asset('theme/vendor/flot/jquery.flot.time.js')}}"></script>
   <script src="{{asset('theme/vendor/flot/jquery.flot.categories.js')}}"></script>
   <script src="{{asset('theme/vendor/jquery.flot.spline/jquery.flot.spline.js')}}"></script><!-- EASY PIE CHART-->
   <script src="{{asset('theme/vendor/easy-pie-chart/dist/jquery.easypiechart.js')}}"></script><!-- MOMENT JS-->
   <script src="{{asset('theme/vendor/moment/min/moment-with-locales.js')}}"></script><!-- =============== APP SCRIPTS ===============-->
   <script src="{{asset('theme/js/app.js')}}"></script>
   <script src="{{ asset('js/fusioncharts.js') }}" defer></script>
  <script src="{{ asset('js/fusioncharts.charts.js') }}" defer></script>
  <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}" defer></script>
   <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDS0aGw5pQFy_dg8198J42w0EeuZtI2Wuk" type="text/javascript"></script>
  <script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-analytics.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/linq.js/2.2.0.2/linq.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
      @yield('content')
      <footer class="footer-container"><span>&copy; 2020 - Hipzone</span></footer>
   </div>
</body>

</html>