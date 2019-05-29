@extends('layout')

@section('content')
<style type="text/css">
.overlay {
    background: rgba(129, 119, 119, 0.5) none no-repeat scroll 0% 0%;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0px;
    left: 1px;
    z-index: 1019;
    padding-left: 53%;
    padding-top: 20%;
}
</style>

<body class="hipTnA">
    <!-- <div id="loadingDiv" class="overlay">
        <img src="./img/loader.gif" style="width:80px;">
    </div> -->
    <a id="buildtable"></a>

    <div class="container-fluid">

        <div class="row">
            @include('hippnp.sidebar')

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">Pick n Pay Dashboard</h1>
                <div class="container-fluid">
                    <div class="row">
                        <div class="venuecolheading">Customer Overview</div>
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <h3>Customers In Store Today</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan"><span
                                            style="font-size: 30%;">{{$data['customerInStoreToday']}}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="venuerow">
                                <div class="modStat">
                                    <div class="modstattitle">
                                        <h3>Customers In Store This Month</h3>
                                    </div>
                                    <div id="staff_today" class="modStatspan"><span
                                            style="font-size: 30%;">{{$data['customerInStoreThisMonth']}}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

@stop