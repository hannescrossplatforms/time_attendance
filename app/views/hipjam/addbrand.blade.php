@extends('layout')


<?php 
error_log("hipjam - Edit is " . $data["edit"]);

$edit = $data["edit"] ;
if($data["is_activation"]) { $is_activation = 1; } else { $is_activation = 0; };
?>

@section('content')

  <body class="hipJAM">

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">@if ($is_activation) Activate @else Edit @endif Brand</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                  <?php error_log("here 20 : $error"); ?>
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif
            <form role="form" method="post" id="mainform" action="@if ($is_activation) {{ url('hipjam_editbrand'); }} @else {{ url('hipjam_editbrand'); }} @endif">
              {{ Form::hidden('id', $data['brand']->id) }}
              {{ Form::hidden('oldbrandcode', $data['brand']->code) }}
              {{ Form::hidden('is_activation', $is_activation) }}

              <div class="form-group">
                <label for="brand_name">Brand Name</label>
                <input type="text" class="form-control" id="" placeholder="" name="name"
                        value="@if(Input::old('name')){{Input::old('name')}}@else{{$data['brand']->name;}}@endif" disabled>
              </div>
              <div class="form-group">
                <label for="brand_code">Brand Code</label>
                <input type="text" class="form-control" id="" size="6" placeholder="" name="code" 
                       value="@if(Input::old('code')){{Input::old('code')}}@else{{$data['brand']->code;}}@endif" disabled>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading">Threshold Configuration</div>
                <div class="panel-body">

                  <div class="form-group">
                    <label for="session_time">Min Session Time</label>
                    <input type="text" class="form-control" id="" size="6" placeholder="Enter session time" name="min_session_length" 
                        @if (!$is_activation) 
                           value="@if(Input::old('min_session_length')){{Input::old('min_session_length')}}@else{{$data['brand']->min_session_length;}}@endif" 
                        @endif
                           >
                  </div>
                  <div class="form-group">
                    <label for="session_time">Max Session Time</label>
                    <input type="text" class="form-control" id="" size="6" placeholder="Enter session time" name="max_session_length" 
                        @if (!$is_activation) 
                           value="@if(Input::old('max_session_length')){{Input::old('max_session_length')}}@else{{$data['brand']->max_session_length;}}@endif" 
                        @endif
                           >
                    <span id="helpBlock" class="help-block">Valid sessions are between 'Min' and 'Max' minutes in length</span>
                  </div>

                  <BR>

                  <div class="form-group">
                    <label for="engaged_time">Min Engaged Customer Time</label>
                    <input type="text" class="form-control" id="" size="6" placeholder="Enter min engaged customer time" name="min_engaged_length" 
                        @if (!$is_activation) 
                           value="@if(Input::old('min_engaged_length')){{Input::old('min_engaged_length')}}@else{{$data['brand']->min_engaged_length;}}@endif" 
                        @endif
                           >
                  </div>

                  <div class="form-group">
                    <label for="engaged_time">Max Engaged Customer Time</label>
                    <input type="text" class="form-control" id="" size="6" placeholder="Enter max engaged customer time" name="max_engaged_length" 
                        @if (!$is_activation) 
                           value="@if(Input::old('max_engaged_length')){{Input::old('max_engaged_length')}}@else{{$data['brand']->max_engaged_length;}}@endif" 
                        @endif
                           >
                    <span id="helpBlock" class="help-block">Engaged customers are those who are detected between 'Min' and 'Max' minutes</span>
                  </div>


                </div>
              </div>
              <button class="btn btn-primary">Submit</button>
            <a href="{{ url('hipjam_showbrands'); }}" class="btn btn-default">Cancel</a>
            </form>
        </div>
        
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/prefixfree.min.js"></script> 
    @if (\User::isVicinity())
        <style>
          .sidebar {
            background-image: url(https://i.ibb.co/KbKv0Fc/vicinity-bg-copy.png);
            background-color: transparent;
            background-repeat: no-repeat;
            background-size: cover;
            width: 413px;
            background-position: center;
          }

          .nav.nav-sidebar.nav-products {
            height: 100vh;
            background: rgba(0, 0, 0, 0.3);
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
            background: rgba(0, 0, 0, 0.3);
            padding: 32px 0px;
          }

          .sidebarActive .subNav li.active a {
            background-color: rgba(255, 255, 255, 0.18);
            height: 60px;
          }

          .hipJAM .sidebarActive .subNav {
            background: transparent !important;
          }

          li.li-jam a:before {
            background: transparent !important;
          }

          li.li-jam a i,
          .nav-sidebar>li>a>i {
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

          .modstattitle {
            background-color: #9DD1ED;
          }
        </style>
        @endif
    
    <script>
  
    $('.btn-removeAccount').click(function(){
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to remove this brand?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, remove it!',
        closeOnConfirm: false,
        //closeOnCancel: false
      },
      function(){
        swal("Removed!", "Brand has been removed!", "success");
      });
    });
      


    </script>

  </body>
</html>
