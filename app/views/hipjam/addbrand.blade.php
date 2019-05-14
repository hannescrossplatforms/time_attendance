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
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/prefixfree.min.js"></script> 
    
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
