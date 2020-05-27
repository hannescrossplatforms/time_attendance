@extends('angle_layout')


<?php
error_log("hipjam - Edit is " . $data["edit"]);

$edit = $data["edit"];
if ($data["is_activation"]) {
  $is_activation = 1;
} else {
  $is_activation = 0;
};
?>

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>@if ($is_activation) Activate @else Edit @endif Brand</div>
      @if ($errors->has())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <?php error_log("here 20 : $error"); ?>
        {{ $error }}<br>
        @endforeach
      </div>
      @endif
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>

            <div class="card-title">
              Brand Information

            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <form role="form" method="post" id="mainform" action="@if ($is_activation) {{ url('hipjam_editbrand'); }} @else {{ url('hipjam_editbrand'); }} @endif">
                  {{ Form::hidden('id', $data['brand']->id) }}
                  {{ Form::hidden('oldbrandcode', $data['brand']->code) }}
                  {{ Form::hidden('is_activation', $is_activation) }}

                  <div class="form-group">
                    <label for="brand_name">Brand Name</label>
                    <input type="text" class="form-control" id="" placeholder="" name="name" value="@if(Input::old('name')){{Input::old('name')}}@else{{$data['brand']->name;}}@endif" disabled>
                  </div>
                  <div class="form-group">
                    <label for="brand_code">Brand Code</label>
                    <input type="text" class="form-control" id="" size="6" placeholder="" name="code" value="@if(Input::old('code')){{Input::old('code')}}@else{{$data['brand']->code;}}@endif" disabled>
                  </div>
                  <div class="form-group">
                    <label for="brand_code">Friendly Name</label>
                    <input type="text" class="form-control" id="" placeholder="" name="friendly_name" value="@if(Input::old('friendly_name')){{Input::old('friendly_name')}}@else{{$data['brand']->friendly_name;}}@endif">
                  </div>

                  <div class="card card-default card-demo">
                    <div class="card-header">
                      <div class="card-title">Threshold Configuration</div>
                    </div>
                    <div class="card-body">

                      <div class="form-group">
                        <label for="session_time">Min Session Time</label>
                        <input type="text" class="form-control" id="" size="6" placeholder="Enter session time" name="min_session_length" @if (!$is_activation) value="@if(Input::old('min_session_length')){{Input::old('min_session_length')}}@else{{$data['brand']->min_session_length;}}@endif" @endif>
                      </div>
                      <div class="form-group">
                        <label for="session_time">Max Session Time</label>
                        <input type="text" class="form-control" id="" size="6" placeholder="Enter session time" name="max_session_length" @if (!$is_activation) value="@if(Input::old('max_session_length')){{Input::old('max_session_length')}}@else{{$data['brand']->max_session_length;}}@endif" @endif>
                        <span id="helpBlock" class="help-block">Valid sessions are between 'Min' and 'Max' minutes in length</span>
                      </div>

                      <BR>

                      <div class="form-group">
                        <label for="engaged_time">Min Engaged Customer Time</label>
                        <input type="text" class="form-control" id="" size="6" placeholder="Enter min engaged customer time" name="min_engaged_length" @if (!$is_activation) value="@if(Input::old('min_engaged_length')){{Input::old('min_engaged_length')}}@else{{$data['brand']->min_engaged_length;}}@endif" @endif>
                      </div>

                      <div class="form-group">
                        <label for="engaged_time">Max Engaged Customer Time</label>
                        <input type="text" class="form-control" id="" size="6" placeholder="Enter max engaged customer time" name="max_engaged_length" @if (!$is_activation) value="@if(Input::old('max_engaged_length')){{Input::old('max_engaged_length')}}@else{{$data['brand']->max_engaged_length;}}@endif" @endif>
                        <span id="helpBlock" class="help-block">Engaged customers are those who are detected between 'Min' and 'Max' minutes</span>
                      </div>


                    </div>
                  </div>
                  <div class="card card-default card-demo">
                    <div class="card-header">
                      <div class="card-title">Sales Configuration</div>
                    </div>
                    <div class="card-body">

                      <div class="form-group">
                        <label for="avg_basket_value">Average Basket Value</label>
                        <input type="text" class="form-control" id="avg_basket_value" placeholder="0.00" name="avg_basket_value" @if (!$is_activation) value="@if(Input::old('avg_basket_value')){{Input::old('avg_basket_value')}}@else{{$data['brand']->avg_basket_value;}}@endif" @endif>
                      </div>

                    </div>
                  </div>
                  
                  <button class="btn btn-primary">Submit</button>
                  <a href="{{ url('hipjam_showbrands'); }}" class="btn btn-default">Cancel</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script>
  $('.btn-removeAccount').click(function() {
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
      function() {
        swal("Removed!", "Brand has been removed!", "success");
      });
  });
</script>
@stop