
<div class="col-sm-3 col-md-3 sidebar sidebarActive">
    <div class="logo pull-left">
    <a href="{{ url('dashboard'); }}"><img src="img/logo_hiphub_small.png" class="img-responsive" /></a>
    </div>
    <div class="productTitle pull-left">
        <h2><strong>T&A</strong></h2>
    </div>
    <div class="clearfix"></div>
    <ul class="nav nav-sidebar nav-products">
        @if (\User::hasProduct("HipWIFI") || \User::hasAccess("superadmin"))
        <li class="li-wifi"><a href="{{ url('hipwifi_showdashboard'); }}"><i class="fa fa-wifi"></i></a></li>
        @endif
        @if (\User::hasProduct("HipRM") || \User::hasAccess("superadmin"))
        <li class="li-rm" class="active"> <a href="{{ url('hiprm_showdashboard'); }}"><i class="fa fa-credit-card"></i></a> </li>
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
        <li class="li-adm"><a href="{{ url('admin_showdashboard'); }}"><i class="fa fa-gear fa-3x"></i></a></li>
        @endif
        @if (\User::hasAccess("superadmin") || \User::hasAccess("admin"))

        @endif
        <li><a href="{{ url('support'); }}"><i class="fa fa-phone"></i></a></li>
        <li><a href="{{ url('logout'); }}"><i class="fa fa-unlock"></i></a></li>
    </ul>

    <ul class="nav subNav">

        <div id="instance_menus" style="display: none">
            <ul class="subNav2">

            <li><a href="{{ url('hippnp_showdashboard'); }}">Pick n Pay </a></li>
            <li><a href="{{ url('hipbidvest_showdashboard'); }}/IM">Bidvest @if ( Session::get('currentInstance') == "IM" ) * @endif</a></li>
            <li><a href="{{ url('hiptna_showdashboard'); }}/IM">IM Instance @if ( Session::get('currentInstance') == "IM" ) * @endif</a></li>
            <li><a href="{{ url('hiptna_showdashboard'); }}/CE">CE Instance @if ( Session::get('currentInstance') == "CE" ) * @endif</a></li>
            </ul>
        </div>
        <div id="exception_manage_menus" style="display: none">

            <ul class="subNav2">


            </ul>
        </div>

    </ul>
    <div class="clearfix"></div>

</div>


