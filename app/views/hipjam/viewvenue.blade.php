@extends('angle_layout')

@section('content')

<style type="text/css">
    .modstattitle {
        /*background-color: #d3d3d3;#106f5d*/
        background-color: #277d6d;
        height: 70px;
        padding: 10px;
    }

    .modstattitle h3 {
        color: white;
    }

    #heatmapArea {
        position: relative;
        float: left;
        width: 900px;
        height: 500px;
        /*background-image:url("{{$data['fullpathimage']}}");*/
        /*background-image:url("{{url('img/heatmap.jpg')}}");*/
        /*background-size: 885px 600px;*/
        background-repeat: no-repeat;
        border: 0px dashed black;
    }


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
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">

        <div id="loadingDiv" class="overlay">
            <img src="/img/loader.gif" style="width:80px;">
        </div>

        <a id="buildtable"></a>

        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card card-default card-demo">
                        <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
                        <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
                            <div class="card-title">{{$data['venue']}}</div>
                        </div>
                        <div class="card-body">
                        <div class="card-wrapper" style="max-height: none !important;">
                            <div class="row">



                                <div class="col-12">


                                    <?php if (strpos($_SERVER['REQUEST_URI'], 'public') !== false) {
                                        $pos = strpos($_SERVER['REQUEST_URI'], 'public');
                                        $portion = substr($_SERVER['REQUEST_URI'], 0, $pos + 7);
                                        $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
                                    } else {
                                        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/';
                                    }
                                    ?>
                                    <input type="hidden" id="url" name="" value="{{$url}}">
                                    <input type="hidden" id="apisitename" name="" value="{{$data['apisitename']}}">
                                    <input type="hidden" id="apivenuename" name="" value="{{$data['track_slugname']}}">
                                    <input type="hidden" id="apivenueid" name="" value="{{$data['apivenueid']}}">

                                    <div class="container-fluid">

                                        <!-- Nav tabs -->
                                        
                                        <!-- <br> -->
                                        <div class="row">
                                            <div class="col-12">
                                                <hr />
                                                <h4>Today</h4>
                                                <hr />
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-danger-dark justify-content-center rounded-left"><em class="fas fa-clock fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-danger rounded-right">
                                                        <div class="h2 mt-0" id="live_customer_now">Loading...</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Real-time Reach</div>
                                                        @else
                                                        <div class="text-uppercase">Real-time Customers</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-warning-dark justify-content-center rounded-left"><em class="fas fa-calendar fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-warning rounded-right">
                                                        <div class="h2 mt-0" id="live_customer_today">Loading...</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Individuals Exposed Today</div>
                                                        @else
                                                        <div class="text-uppercase">Customers In Store Today</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>






                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-success-dark justify-content-center rounded-left"><em class="fas fa-history fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-success rounded-right">
                                                        <div class="h2 mt-0" id="live_new_now">Loading...</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Uniques Current</div>
                                                        @else
                                                        <div class="text-uppercase">New Customers Now</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-purple-dark justify-content-center rounded-left"><em class="fas fa-calendar-plus fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-purple rounded-right">
                                                        <div class="h2 mt-0" id="live_new_today">Loading...</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Uniques Today</div>
                                                        @else
                                                        <div class="text-uppercase">New Customers Today</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-primary-dark justify-content-center rounded-left"><em class="fas fa-cash-register fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-primary rounded-right">
                                                        <div class="h2 mt-0" id="live_window_today">{{$data['exposed_today'][0]->count}}</div>
                                                        
                                                            @if ($data['venue_type'] == 'billboard')
                                                                <div class="text-uppercase">Exposed to OOH Site Today</div>
                                                            @else
                                                                <div class="text-uppercase">Conversions Today</div>
                                                            @endif
                                                        
                                                    </div>
                                                </div>
                                            </div>



                                        </div>

                                        <div class="row" id="report_date_filter">
                                            <div class="col-12">
                                                <div class="col-4">
                                                    <!-- <div class="col-4" style="width:43%; padding:6px 0px 0px 0px;"> -->
                                                    <label>Report Period</label>
                                                    <!-- </div> -->
                                                </div>
                                                <div class="col-4 text-center">
                                                    <select id="date_select" class="form-control changable-filter" style="width: 150px;">
                                                        <option value="yesterday">Yesterday</option>
                                                        <option value="today">Today</option>
                                                        <option value="this_week">This Week</option>
                                                        <option value="last_week">Last Week</option>
                                                        <option value="this_month">This Month</option>
                                                        <option value="last_month">Last Month</option>
                                                        <option value="custom">Custom</option>
                                                    </select>

                                                </div>





                                                <div id="custom_date_filter" style="display: none;">
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-5 text-center" style="max-width: 200px;">
                                                            <input class="form-control" type="date" placeholder="yyyy/mm/dd" id="custom_filter_date_from"/>
                                                        </div>
                                                        <div class="col-5 text-center" style="max-width: 200px;">
                                                            <input class="form-control" type="date" placeholder="yyyy/mm/dd" id="custom_filter_date_to" />
                                                        </div>
                                                        <div class="col-2 text-center" style="max-width: 100px;">
                                                            <button class="btn btn-primary" id="apply_custom_date_filter">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>








                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-danger justify-content-center rounded-left"><em class="fas fa-store fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-danger-dark rounded-right">
                                                        <div class="h2 mt-0" id="rep_customer">0</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Total Reach</div>
                                                        @else
                                                        <div class="text-uppercase">Total Customers in Store</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-warning justify-content-center rounded-left"><em class="fas fa-star fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-warning-dark rounded-right">
                                                        <div class="h2 mt-0" id="new_rep_customer">0</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Uniques</div>
                                                        @else
                                                        <div class="text-uppercase">New Customers In Store</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($data['venue_type'] == 'venue')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-success justify-content-center rounded-left"><em class="fas fa-sign-out-alt fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-success-dark rounded-right">
                                                        <div class="h2 mt-0" id="bounce_rate">0</div>
                                                        <div class="text-uppercase">Bounce Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-success justify-content-center rounded-left"><em class="fas fa-hourglass-end fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-success-dark rounded-right">
                                                        <div class="h2 mt-0" id="engaged_customers">0</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Engaged Customers</div>
                                                        @else
                                                        <div class="text-uppercase">High Dwell Customers</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            


                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-purple justify-content-center rounded-left"><em class="fas fa-hourglass-half fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-purple-dark rounded-right">
                                                        <div class="h2 mt-0" id="rep_ave">0</div>
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Average Dwell Time</div>
                                                        @else
                                                        <div class="text-uppercase">Average Time Spent in Store</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            




                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-primary justify-content-center rounded-left"><em class="fas fa-wallet fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-primary-dark rounded-right">
                                                        <div class="h2 mt-0" id="window_con">{{$data['exposed_week'][0]->count}}</div>

                                                        
                                                        @if ($data['venue_type'] == 'billboard')
                                                        <div class="text-uppercase">Exposed to OOH Site</div>
                                                        @else
                                                        <div class="text-uppercase">Total Conversions</div>
                                                        @endif
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                        
                                        
                                        
                                        
                                        </div>


                                        <div class="row">

                                        @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-danger-dark justify-content-center rounded-left"><em class="fas fa-cart-plus fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-danger rounded-right">
                                                        <div class="h2 mt-0" id="new_reach_rate">0</div>
                                                        <div class="text-uppercase">New Reach Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-danger-dark justify-content-center rounded-left"><em class="fas fa-retweet fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-danger rounded-right">
                                                        <div class="h2 mt-0" id="returning_customers">0</div>
                                                        <div class="text-uppercase">Returning Customers in Store</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-warning-dark justify-content-center rounded-left"><em class="fas fa-retweet fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-warning rounded-right">
                                                        <div class="h2 mt-0" id="return_reach_rate">0</div>
                                                        <div class="text-uppercase">Return Reach Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <div class="col-xl-2 col-md-6">
                                            <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-warning-dark justify-content-center rounded-left"><em class="fas fa-cart-plus fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-warning rounded-right">
                                                        <div class="h2 mt-0" id="conversion_rate">0</div>
                                                        <div class="text-uppercase">Conversion Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-success-dark justify-content-center rounded-left"><em class="fas fa-chalkboard-teacher fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-success rounded-right">
                                                        <div class="h2 mt-0" id="reach_frequency">{{$data['reach_frequency'][0]->avg_seen_times}}</div>
                                                        <div class="text-uppercase">Reach Frequency</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-purple-dark justify-content-center rounded-left"><em class="fas fa-chalkboard-teacher fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-purple rounded-right">
                                                        <div class="h2 mt-0" id="gross_rate_point">0</div>
                                                        <div class="text-uppercase">Gross Rate Point</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-primary-dark justify-content-center rounded-left"><em class="fas fa-layer-group fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-primary rounded-right">
                                                        <div class="h2 mt-0" id="conversion_floor_rate" data-total-conversions="{{$data['total_conversions']}}">0</div>
                                                        <div class="text-uppercase">Conversion Floor Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    <input type="hidden" id="hf_total_conversions" value="{{$data['total_conversions']}}"/>
                                            
                                        </div>
                                        <div class="row">


                                            @if ($data['venue_type'] == 'billboard')
                                                <div class="col-xl-2 col-md-6">
                                                    <div class="card flex-row align-items-center align-items-stretch border-0">
                                                        <div class="col-4 d-flex align-items-center bg-danger justify-content-center rounded-left"><em class="fas fa-broadcast-tower fa-3x"></em></div>
                                                        <div class="col-8 py-3 bg-danger-dark rounded-right">
                                                            <div class="h2 mt-0" id="strike_rate">0</div>
                                                            <div class="text-uppercase">Strike Rate</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($data['venue_type'] == 'billboard')
                                                <div class="col-xl-2 col-md-6">
                                                    <div class="card flex-row align-items-center align-items-stretch border-0">
                                                        <div class="col-4 d-flex align-items-center bg-warning justify-content-center rounded-left"><em class="fas fa-broadcast-tower fa-3x"></em></div>
                                                        <div class="col-8 py-3 bg-warning-dark rounded-right">
                                                            <div class="h2 mt-0" id="strike_time">
                                                            <small>{{$data['strike_time'][0]->days}} Days</small> <br />
                                                            <small>{{$data['strike_time'][0]->hours}} Hours</small> <br />
                                                                <small>{{$data['strike_time'][0]->minutes}} Minutes</small>
                                                            </div>
                                                            <div class="text-uppercase">Strike Time</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-success-dark justify-content-center rounded-left"><em class="fas fa-chalkboard-teacher fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-success rounded-right">
                                                        <div class="h2 mt-0" id="strike_distance">{{$data['strike_distance'][0]->distance}}KM</div>
                                                        <div class="text-uppercase">Strike Distance</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-purple-dark justify-content-center rounded-left"><em class="fas fa-chalkboard-teacher fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-purple rounded-right">
                                                        <div class="h2 mt-0" id="strike_distance">R {{$data['potential_sales']}}</div>
                                                        <div class="text-uppercase">Potential Sales</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if ($data['venue_type'] == 'billboard')
                                            <div class="col-xl-2 col-md-6">
                                                <div class="card flex-row align-items-center align-items-stretch border-0">
                                                    <div class="col-4 d-flex align-items-center bg-primary-dark justify-content-center rounded-left"><em class="fas fa-chalkboard-teacher fa-3x"></em></div>
                                                    <div class="col-8 py-3 bg-primary rounded-right">
                                                        <div class="h2 mt-0" id="strike_distance">R {{$data['cpa']}}</div>
                                                        <div class="text-uppercase">CPA (Cost per Acquisition)</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            


                                        </div>

                                        <div class="row">
                                            @if ($data['venue_type'] == 'billboard')
                                                <div class="col-xl-2 col-md-6">
                                                    <div class="card flex-row align-items-center align-items-stretch border-0">
                                                        <div class="col-4 d-flex align-items-center bg-danger justify-content-center rounded-left"><em class="fas fa-broadcast-tower fa-3x"></em></div>
                                                        <div class="col-8 py-3 bg-danger-dark rounded-right">
                                                            <div class="h2 mt-0" id="roi">{{$data['roi']}}%</div>
                                                            <div class="text-uppercase">ROI (%)</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>



                                    </div>
                                </div>

                            </div>
                        </div>
                        </div>
                        </div>






                        <div class="row">
                            <div class="col-12">
                                <div class="card card-default card-demo">
                                    <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
                                    <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
                                    @if ($data['venue_type'] == 'billboard')
                                        <div class="card-title">Overall OOH Site Traffic Trend</div>
                                    @else
                                        <div class="card-title">Overall Store Traffic Trend</div>
                                    @endif
                                    </div>

                                    <div class="card-body">
                                    <div class="card-wrapper">
                                        <div class="row">
                                            <div class="col-12" style="height: 15vw;">
                                                <canvas id="chart-05" height="50">Charts will render here</canvas>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="card card-default card-demo">
                                    <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
                                    <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
                                    @if ($data['venue_type'] == 'billboard')
                                        <div class="card-title">OOH Site Traffic by Hour<sup>*</sup></div>
                                    @else
                                        <div class="card-title">Store Traffic by Hour<sup>*</sup></div>
                                    @endif
                                    </div>
                                    <div class="card-body">
                                    <div class="card-wrapper">
                                        <div class="row">
                                            <div class="col-12" style="height: 22.5vw;">
                                                <canvas id="chart-container">Charts will render here</canvas>
                                                <small><sup>*</sup> Chart totals may differ from in store today customers because individual dwell time spans over mutiple hours.</small>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card card-default card-demo">
                                    <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
                                    <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
                                    @if ($data['venue_type'] == 'billboard')
                                        <div class="card-title" id="store_traffic_title">OOH Site Traffic this Week</div>
                                    @else
                                        <div class="card-title" id="store_traffic_title">Store Traffic this Week</div>
                                    @endif
                                    </div>
                                    <div class="card-body">
                                    <div class="card-wrapper">
                                        <div class="row">
                                        <div class="col-12" style="height: 22.5vw;">
                                                <canvas id="chart-07">Charts will render here</canvas>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card card-default card-demo">
                                    <div class="card-header"><a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card"><em class="fas fa-sync"></em></a>
                                    <a class="float-right" href="javascript:void(0);" data-tool="card-collapse" data-toggle="tooltip" title="" data-original-title="Collapse card"><em class="fa fa-minus"></em></a>
                                    @if ($data['venue_type'] == 'billboard')
                                        <div class="card-title">Overall OOH Site Traffic (New vs. Returning)</div>
                                    @else 
                                        <div class="card-title">Overall Store Traffic (New vs. Returning)</div>
                                    @endif
                                    </div>
                                    <div class="card-body">
                                    <div class="card-wrapper">
                                        <div class="row">
                                        <div class="col-12" style="height: 15vw;">
                                                <canvas height="50" id="chart-06">Charts will render here</canvas>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="venue_type" value="{{$data['venue_type']}}"/>



                        

                        

                        
                    <input id="hf_venue_type" type="hidden" value="{{$data['venue_type']}}" />
                

                    <script src="{{ asset('js/jquery_cookie.js') }}"></script>
                    <script src="{{ asset('js/fusioncharts.js') }}"></script>
                    <script src="{{ asset('js/fusioncharts.charts.js') }}"></script>
                    <script src="{{ asset('js/themes/fusioncharts.theme.zune.js') }}"></script>
                    <script src="{{ asset('js/hipjam/hipjam.js') }}"></script>
                    <script src="{{ asset('js/prefixfree.min.js') }}"></script>
                    <script type="text/javascript" src="{{ asset('js/heatmap.js') }}"></script>
                    <script type="text/javascript">
                        $("#venuefrom, #venueto, #zonalfrom, #zonalto, #heatmapfrom, #heatmapto").datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            orientation: "right bottom"
                        });
                        $("#venuefrom, #venueto").datepicker("setDate", new Date());
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.1.0/firebase-analytics.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-firestore.js"></script> -->

<script>

    var is_billboard = $('#hf_venue_type').val() === 'billboard';

    function get_query_string_key(key) {
        key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
        var match = location.search.match(new RegExp("[?&]" + key + "=([^&]+)(&|$)"));
        return match && decodeURIComponent(match[1].replace(/\+/g, " "));
    }
    
    function setGraphTitle() {
        let title_element = $('#store_traffic_title');
        let is_billboard = $('#venue_type').val() === 'billboard';
        let span_filter = get_query_string_key('span');
        switch(span_filter) {
        case 'last_week':
            if (is_billboard)
                title_element.html('OOH Site Traffic last week');
            else
                title_element.html('Store Traffic last week');
            break;
        case 'yesterday':
            if (is_billboard)
                title_element.html('OOH Site Traffic yesterday');
            else
                title_element.html('Store Traffic yesterday');
            break;
        case 'today':
            if (is_billboard)
                title_element.html('OOH Site Traffic today');
            else
                title_element.html('Store Traffic today');
            break;
        case 'this_month':
            if (is_billboard)
                title_element.html('OOH Site Traffic this month');
            else
                title_element.html('Store Traffic this month');
            break;
        case 'last_month':
            if (is_billboard)
                title_element.html('OOH Site Traffic last month');
            else
                title_element.html('Store Traffic last month');
            break;
        default:
            if (is_billboard)
                title_element.html('OOH Site Traffic this week')
            else
                title_element.html('Store Traffic this week')
        }
    }
    setGraphTitle();
    function remove_future_dates(parsed_date) {
        let current_date = moment().format('YYYY-MM-DD');
        return moment(parsed_date).isBefore(current_date) || current_date === parsed_date;
    }

    let liveJam = {};
    liveJam.initialize = (callback) => {
        $.getScript('https://www.gstatic.com/firebasejs/7.1.0/firebase-firestore.js', () => {
            let config = {
                apiKey: "AIzaSyDUxh-Quw0-D6V7Q2Pjcwgeco7R7x08hWw",
                authDomain: "tracks-e61f4.firebaseapp.com",
                databaseURL: "https://tracks-e61f4.firebaseio.com",
                projectId: "tracks-e61f4",
                storageBucket: "",
                messagingSenderId: "798983478031",
                appId: "1:798983478031:web:f81f6341211ab4dfd7bc7a",
                measurementId: "G-9B1XXZ1MXG"
            };
            firebase.initializeApp(config);
            firebase.analytics();

            this.db = firebase.firestore()
            this.all = db.collection
            this.venue = db.collection("{{$data['venue_id']}}");

            callback();
        });
    };

    liveJam.getVenueData = () => {
        let current_date = new Date();
        let formatted_node = `${current_date.getFullYear()}-${('0'+(current_date.getMonth()+1)).slice(-2)}-${('0'+current_date.getDate()).slice(-2)}`
        venue.doc(formatted_node).get()
            .then((doc) => {
                if (doc.exists) {
                    let venue_data = doc.data();
                    $('#live_customer_now').html(venue_data.customers_in_store_now);
                    $('#live_customer_today').html(venue_data.customers_in_store_today);
                    $('#live_new_now').html(venue_data.new_customers_now);
                    $('#live_new_today').html(venue_data.new_customers_today);
                    // $('#live_window_today').html('0');

                    if (is_billboard) {
                        $('#live_window_today').html(venue_data.customers_in_store_today);
                    }


                } else {
                    $('#live_customer_now').html('No data');
                    $('#live_customer_today').html('No data');
                    $('#live_new_now').html('No data');
                    $('#live_new_today').html('No data');
                    $('#live_window_today').html('No data');
                    console.log('x> No venue found in track FireStore')
                }
            })
            .catch(function(error) {
                console.log("Error getting document:", error);
            })
    }

    liveJam.getVenueDataWeek = () => {

    }
    liveJam.compileNodeArray = (span) => {
        let start = moment().startOf(span);
        let end = moment().endOf(span);

        if (span ==='week') {
            start = moment().startOf(span).add(1,'day');
            end = moment().endOf(span).add(1,'day');
        }

        let date_filter = get_query_string_key('date_from');
        if (date_filter !== '' && date_filter !== null && date_filter !== undefined) {
            start = moment(date_filter);
            end = moment(get_query_string_key('date_to'));
        }

        let date_array = [];

        // Multi Day i.e. this week / this month etc.
        while (start.format('YYYY-MM-DD') !== end.format('YYYY-MM-DD')) {
            date_array.push(start.format('YYYY-MM-DD'))
            start.add(1, 'days');
        }

        // Add last day
        date_array.push(start.format('YYYY-MM-DD'))
        return date_array;
    }

    liveJam.retrieveNode = (node) => {
        return venue.doc(node).get()
            .then((doc) => {
                if (doc.exists) {
                    return {
                        date: node,
                        data: doc.data()
                    }
                } else {
                    return {
                        date: node,
                        data: {
                            average_dwell: 0,
                            customers_in_store_now: 0,
                            customers_in_store_today: 0,
                            last_seen: 0,
                            new_customers_now: 0,
                            new_customers_today: 0,
                            type: ''
                        }
                    }
                }
            })
            .catch(function(error) {
                return {
                    date: node,
                    data: {
                        average_dwell: 0,
                        customers_in_store_now: 0,
                        customers_in_store_today: 0,
                        last_seen: 0,
                        new_customers_now: 0,
                        new_customers_today: 0,
                        type: ''
                    }
                }
            })
    }

    liveJam.compileData = (span, callback) => {
        let nodes = liveJam.compileNodeArray(span);
        let week_data_promises = [];
        let week_data = [];
        $.each(nodes, function(i, node) {
            console.log('GETTING DATA FOR: ' + node);
            week_data_promises.push(liveJam.retrieveNode(node));
        });
        Promise.all(week_data_promises).then(function() {
            callback(arguments[0]);
        })
    }

    liveJam.orderAndSortData = (raw_data) => {
      raw_data.sort((a, b) => parseFloat(a.y) - parseFloat(b.y));
      return {
        labels: $.map( raw_data, function( d, i ) {
                    return d.x
                }),
        data: $.map( raw_data, function( d, i ) {
                    return d.y
                })
      }
    }

    liveJam.sortArray = (array) => {
        array.sort(function(a, b) {
            var nameA = a.date.toLowerCase(),
                nameB = b.date.toLowerCase()
            if (nameA < nameB)
                return -1
            if (nameA > nameB)
                return 1
            return 0
        })
    }

    liveJam.sortArrayByKey = (array, key) => {
        return array.sort(function(a, b) {
            var x = a[key];
            var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
    }

    liveJam.graphSerializer = (raw_data) => {
        raw_data.sort((a, b) => a.date.localeCompare(b.date));
        return $.map(raw_data, function(d, i) {
            return {
                label: d.date,
                value: d.data.customers_in_store_today
            }
        });
    }

    liveJam.renderStoreTrafficTrendGraph = (data) => {
        let graph_data = liveJam.graphSerializer(data);
        let canvas = document.getElementById('chart-05');
        let ctx = canvas.getContext("2d");
        var myBarChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: graph_data.map((gd, i) => {return gd.label}),
              datasets: [{
                  label: '# of Sessions',
                  data: graph_data.map((gd, i) => {return gd.value}),
                  borderColor: "purple",
                  backgroundColor: "rgba(114,102,186,0.4)"
                }]
              },
              options: global_chart_options
        });
        
    }

    liveJam.renderStoreWeekTrafficGraph = (data) => {
        let graph_data = liveJam.graphSerializer(data);
        let canvas = document.getElementById('chart-07');
        let ctx = canvas.getContext("2d");
        var myBarChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: graph_data.map((gd, i) => {return gd.label}),
              datasets: [{
                  label: '# of Sessions',
                  data: graph_data.map((gd, i) => {return gd.value}),
                  borderColor: "orange",
                  backgroundColor: "rgba(247,118,0,0.4)"
                }]
              },
              options: global_chart_options
        });
    }

    liveJam.renderNewVsReturningGraph = (data) => {
        // let graph_data = liveJam.graphSerializer(data);
    

        let dateCat = $.map(data, function(d, i) {
            return {
                label: d.date
            }
        });


        let new_data_set = $.map(data, function(d, i) {
            return {
                value: d.data.new_customers_today
            }
        });

        let new_data_set_full = {
            seriesname: "New",
            data: new_data_set
        }

        let returning_data_set = $.map(data, function(d, i) {
            return {
                value: d.data.customers_in_store_today - d.data.new_customers_today
            }
        });

        let returning_data_set_full = {
            seriesname: "Returning",
            data: returning_data_set
        }




        
        let canvas = document.getElementById('chart-06');
        let ctx = canvas.getContext("2d");
        
        var myBarChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: data.map((d, i) => {return d.date}),
              datasets: [{
                  label: 'New',
                  data: new_data_set.map((nds, i) => {return nds.value}),
                  borderColor: "purple",
                  backgroundColor: "rgba(144,102,186,0)"
                },
                {
                  label: 'Returning',
                  data: returning_data_set.map((rds, i) => {return rds.value}),
                  borderColor: "green",
                  backgroundColor: "rgba(116,236,78,0)"
                }]
              },
              options: global_chart_options
        });







        // var chartProperties = {
        //     "caption": "",
        //     "xAxisName": "Date",
        //     "yAxisName": "Customers",
        //     "rotatevalues": "1",
        //     "theme": "zune"
        // };

        // apiChart = new FusionCharts({
        //     type: 'msline',
        //     renderAt: 'chart-06',
        //     width: '100%',
        //     height: '350',
        //     dataFormat: 'json',
        //     dataSource: {
        //         "chart": chartProperties,
        //         "categories": [{
        //             category: dateCat
        //         }],
        //         "dataset": [new_data_set_full, returning_data_set_full]
        //     }
        // });
        // apiChart.render();
    }

    liveJam.renderHourlyTrafficGraph = (data) => {
        let node = `${moment().format('YYYY-MM-DD')}/hourly`;

        let filters = get_presets();
        if (filters === null) {
            filters = {
                date_from: moment().startOf('week'),
                date_to: moment().endOf('week')
            }
        }
        let start = moment(filters.date_from);
        let end = moment(filters.date_to);
        let date_array = [];

        // Multi Day i.e. this week / this month etc.
        while (start.format('YYYY-MM-DD') !== end.format('YYYY-MM-DD')) {
            date_array.push(start.format('YYYY-MM-DD'));
            start.add(1, 'days');
        }

        // Add last day
        date_array.push(start.format('YYYY-MM-DD'))

        // console.log(`--- BEFORE ---`)
        // console.log(date_array);

        date_array = date_array.filter(remove_future_dates);

        // console.log(`--- AFTER ---`)
        // console.log(date_array);

        let data_promises = [];
        $.each(date_array, function(key, node) {
            data_promises.push(db.collection(`{{$data['venue_id']}}/${node}/hourly`).get());
        });

        let hourly_averages = {};
        Promise.all(data_promises).then(function(doc) {
            doc.forEach(function(parent) {
                parent.docs.forEach(function(hours) {
                    if (!hourly_averages.hasOwnProperty(hours.id)) {
                        hourly_averages[hours.id] = parseInt(hours.data().customers);
                    } else {
                        hourly_averages[hours.id] = hourly_averages[hours.id] + parseInt(hours.data().customers);
                    }
                });
            });
            hourly_averages = $.map(hourly_averages, function(obj, key) {
                // console.log(`${key} (${hourly_averages.length}): ${obj} / ${data_promises.length} = ${obj / data_promises.length}`)
                return {
                    label: key,
                    value: Math.ceil(obj / data_promises.length)
                };
            });



        
            let canvas = document.getElementById('chart-container');
            let ctx = canvas.getContext("2d");
            var myBarChart = new Chart(ctx, {
                type: 'line',
                data: {
                labels: hourly_averages.map((ha, i) => {return ha.label}),
                datasets: [{
                    label: '# of Sessions',
                    data: hourly_averages.map((ha, i) => {return ha.value}),
                    borderColor: "blue",
                    backgroundColor: "rgba(47,128,231,0.4)"
                    }]
                },
                options: global_chart_options
            });

            // var chartProperties = {
            //     "caption": "",
            //     "xAxisName": "Hour",
            //     "yAxisName": "Customers",
            //     "rotatevalues": "1",
            //     "theme": "zune"
            // };

            // apiChart = new FusionCharts({
            //     type: 'line',
            //     renderAt: 'chart-container',
            //     width: '100%',
            //     height: '350',
            //     dataFormat: 'json',
            //     dataSource: {
            //         "chart": chartProperties,
            //         "data": hourly_averages
            //     }
            // });
            // apiChart.render();
        });

    }

    liveJam.initialize(() => {
        liveJam.getVenueData();
        liveJam.compileData('week', function(week_data) {
            var time_since_opening = moment.duration(moment().diff(moment('2019-11-21 08:00:00'))).asMinutes();
            var customers_in_store = 0
            var new_customers_in_store = 0;
            var dwell = 0;
            var dwell_words = '';
            var exposed_to_billboard = 0;
            var high_dwell_customers = 0;
            var bounces = 0;
            var total_sessions = 0;
            var bounce_rate = 0;
            var new_reach_rate = 0;
            var return_reach_rate = 0;
            var total_conversions = parseInt($('#hf_total_conversions').val());
            var total_returning = 0;

            $.each(week_data, function(index, item) {
                dwell += item.data.average_dwell;
                customers_in_store += item.data.customers_in_store_today;
                new_customers_in_store += item.data.new_customers_today;
                high_dwell_customers += item.data.high_dwell_customers || 0;
                exposed_to_billboard = 0;
                bounces += item.data.bounces || 0;
                total_sessions += item.data.total_sessons || 0
            });
            dwell = (dwell / week_data.length) / 60
            bounce_rate = Math.round((bounces / total_sessions) * 100)
            new_reach_rate = Math.round((new_customers_in_store / customers_in_store) * 100);
            return_reach_rate = Math.round(((customers_in_store - new_customers_in_store) / customers_in_store) * 100);
            total_returning = (customers_in_store - new_customers_in_store);

            $('#rep_customer').html(customers_in_store);
            $('#gross_rate_point').html((parseFloat($('#reach_frequency').html()) * customers_in_store).toFixed(2));
            $('#conversion_floor_rate').html(total_conversions === 0 ? 0 : (customers_in_store / total_conversions).toFixed(2));
            $('#strike_rate').html(`${((total_conversions / customers_in_store) * 100).toFixed(2)}%`);
            $('#new_rep_customer').html(new_customers_in_store);
            $('#engaged_customers').html(high_dwell_customers);
            $('#bounce_rate').html(isNaN(bounce_rate) ? 'No Data' : `${bounce_rate}%`);
            $('#new_reach_rate').html(isNaN(new_reach_rate) ? 'No Data' : `${new_reach_rate}%`)
            $('#return_reach_rate').html(isNaN(return_reach_rate) ? 'No Data' : `${return_reach_rate}%`)
            $('#returning_customers').html(total_returning);
            $('#conversion_rate').html(`${ Math.round((total_conversions / customers_in_store) * 100)}%`);
            
            if (dwell === null || dwell === undefined || dwell === '' || dwell.toString() === 'NaN') {
                $('#rep_ave').html('0');
            } else {
                $('#rep_ave').html(Math.round(dwell));
            }

            if (is_billboard)
                $('#window_con').html(customers_in_store);
//             else
//                 // $('#window_con').html('0');
// // debugger;
                liveJam.renderStoreTrafficTrendGraph(week_data);
            liveJam.renderHourlyTrafficGraph(week_data);
            liveJam.renderNewVsReturningGraph(week_data);
            liveJam.renderStoreWeekTrafficGraph(week_data);
        });

    })

    function get_query_string_key(key) {
        key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
        var match = location.search.match(new RegExp("[?&]" + key + "=([^&]+)(&|$)"));
        return match && decodeURIComponent(match[1].replace(/\+/g, " "));
    }

    function get_presets() {
        let brand_id = get_query_string_key('brand_id');
        if (brand_id === '' || brand_id === null || brand_id === undefined)
            return null
        return {
            brand_id: get_query_string_key('brand_id'),
            type: get_query_string_key('type'),
            span: get_query_string_key('span'),
            date_from: get_query_string_key('date_from'),
            date_to: get_query_string_key('date_to')
        }
    }
</script>

<script>
    if (window.location !== window.parent.location) {
        $('.sidebar').remove();
        $('.main').attr('style', 'width: 100% !important; margin: 0 !important;');
        $('#report_date_filter').remove();
    }
</script>
<script>
    load_presets();
    getFilters(false);

    if ($('#date_select').val() !== 'custom') {
      getFilters(false);  
    } else {
      showCustomDateRange();
    }
    

    $(document).on('change', '.changable-filter', function() {
      let date_filter = $('#date_select');
      if (date_filter.val() !== 'custom') {
        getFilters(true);
      } else {
        showCustomDateRange();
      }
      
    })

    function showCustomDateRange() {
      let container = $('#custom_date_filter');
      let date_from = $('#custom_filter_date_from');
      let date_to = $('#custom_filter_date_to');

    
      date_from.val(get_query_string_key('date_from'));
      date_to.val(get_query_string_key('date_to'))

      container.slideDown('fast');
    }

    $(document).on('click', '#apply_custom_date_filter', function () {
      getFilters(true);
    });

    function load_presets() {
        let presets = get_presets();
        if (presets !== null) {
            $('#sub_brand_select').val(presets.brand_id);
            $('#type_select').val(presets.type);
            $('#date_select').val(presets.span);
        } else {
            $('#date_select').val('this_week');
        }
    }

    function getFilters(must_redirect) {
        let host = `http://${window.location.host}/`;
        let brand_id = $('#sub_brand_select').val();
        let type = $('#type_select').val();
        let span = $('#date_select').val();
        let date_from = generate_date_from();
        let date_to = generate_date_to();

        if (must_redirect)
            window.location.href = `${host}hipjam_viewvenue/{{$data['venue_id']}}/tracks03.hipzone.co.za${generate_query_string(brand_id, type, span, date_from, date_to)}`
    }

    function generate_date_from() {
        let selected_date_span = $('#date_select').val();
        let today = moment();
        switch (selected_date_span) {
            case 'yesterday':
                return today.subtract(1, 'day').format('YYYY-MM-DD');
                break;
            case 'today':
                return today.format('YYYY-MM-DD')
                break;
            case 'this_week':
                return today.startOf('week').add(1, 'day').format('YYYY-MM-DD'); // Week's data needs to start on Monday
                break;
            case 'last_week':
                return today.startOf('week').subtract(1, 'day').startOf('week').add(1, 'day').format('YYYY-MM-DD');
                break;
            case 'this_month':
                return today.startOf('month').format('YYYY-MM-DD');
                break;
            case 'last_month':
                return today.startOf('month').subtract(1, 'day').startOf('month').format('YYYY-MM-DD');
                break;
            case 'custom':
                return $('#custom_filter_date_from').val();
                break;
            default:
                return today.startOf('week').format('YYYY-MM-DD');
        }
    }

    function generate_date_to() {
        let selected_date_span = $('#date_select').val();
        let today = moment();
        switch (selected_date_span) {
            case 'yesterday':
                return today.subtract(1, 'day').format('YYYY-MM-DD');
                break;
            case 'today':
                return today.format('YYYY-MM-DD')
                break;
            case 'this_week':
                return today.endOf('week').add(1, 'day').format('YYYY-MM-DD'); // Week's data needs to end on Sunday
                break;
            case 'last_week':
                return today.startOf('week').subtract(1, 'day').endOf('week').add(1, 'day').format('YYYY-MM-DD');
                break;
            case 'this_month':
                return today.endOf('month').format('YYYY-MM-DD');
                break;
            case 'last_month':
                return today.startOf('month').subtract(1, 'day').endOf('month').format('YYYY-MM-DD');
                break;
            case 'custom':
                return $('#custom_filter_date_to').val();
                break;
            default:
                return today.endOf('week').format('YYYY-MM-DD');
        }
    }

    function generate_query_string(brand_id, type, span, date_from, date_to) {
        return `?brand_id=${brand_id}&type=${type}&span=${span}&date_from=${date_from}&date_to=${date_to}`;
    }

    function get_query_string_key(key) {
        key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
        var match = location.search.match(new RegExp("[?&]" + key + "=([^&]+)(&|$)"));
        return match && decodeURIComponent(match[1].replace(/\+/g, " "));
    }

    function get_presets() {
        let brand_id = get_query_string_key('brand_id');
        if (brand_id === '' || brand_id === null || brand_id === undefined)
            return null
        return {
            brand_id: get_query_string_key('brand_id'),
            type: get_query_string_key('type'),
            span: get_query_string_key('span'),
            date_from: get_query_string_key('date_from'),
            date_to: get_query_string_key('date_to')
        }
    }
</script>

<script>
    //  if ( $('[type="date"]').prop('type') != 'date' ) {
        $('[type="date"]').datepicker({ dateFormat: 'yy/mm/dd' });
    //  }
    if (self !== top) {
        $('.topnavbar-wrapper').style('display', 'none');
    }
</script>
<style>
    .card {
        min-height: 118px;
    }
    .no-overflow {
        max-height: none !important;
    }
</style>

@stop