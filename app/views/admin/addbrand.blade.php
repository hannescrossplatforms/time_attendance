@extends('layout')

<?php 
error_log("Edit is " . $data["edit"]);

$edit = $data["edit"] ;
?>

@section('content')

  <body class="HipADMIN">

    <div class="container-fluid">
      <div class="row">

        @include('admin.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">@if ($edit) Edit @else Add @endif Brand</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif
            <form role="form" method="post" id="mainform"
                  action=" @if ($edit) {{ url('admin_editbrand'); }} @else {{ url('admin_addbrand'); }} @endif ">
              @if ($edit) {{ Form::hidden('id', $data['brand']->id) }} @endif
              @if ($edit) {{ Form::hidden('oldbrandcode', $data['brand']->code) }} @endif
              @if (!$edit) 
              <div class="form-group" style="{{\User::isVicinity() ? 'display:none' : ''}}">
                <label>ISP*</label>
                <select id="isplist" name="isp_id" class="form-control">
                  @foreach($data['allisps'] as $isp)
                    <option value="{{ $isp->id }}">
                      {{ $isp->name }} ({{ $isp->code }})
                    </option>
                  @endforeach 
                </select>
              </div>
              @endif
              <div class="form-group" id="brand_name_container">


                <label for="exampleInputEmail1">Brand Name</label>
                <input type="text" class="form-control" id="brand_name_input" placeholder="" 
                        name="name" 
                        value="@if(Input::old('name')){{Input::old('name')}}@else{{$data['brand']->name;}}@endif"
                        maxlength="8">
              </div>
              <p id="brand_already_exists" style="color: red; font-size: 12px; display:none;">A brand with this name already exists.</p>
              <div class="form-group" style="{{\User::isVicinity() ? 'display:none' : ''}}">
                <label for="exampleInputEmail1">Brand Code</label>
                <input type="text" class="form-control" id="brand_code_input" size="6" placeholder="" name="code" 
                       value="@if(Input::old('code')){{Input::old('code')}}@else{{$data['brand']->code;}}@endif">
              </div>
              <div class="form-group" style="{{\User::isVicinity() ? 'display:none' : ''}}">
                <label>Country</label>
                <select id="countrielist" name="countrie_id" class="form-control no-radius">
                  @foreach($data['allcountries'] as $countrie)
                    <option value="{{ $countrie->id }}"
                      @if ($edit) @if ($data['brand']->countrie_id == $countrie->id)  selected  @endif @endif>
                      {{ $countrie->name }}
                    </option>
                  @endforeach 
                </select>
              </div>
              
              <div class="form-group" style="{{\User::isVicinity() ? 'display:none' : ''}}">
                <label>Products Enabled</label>
                <div class="checkbox">
                <label>
                  <input type="hidden" name="admin" value="1">
                  <div class="appcheckboxes">
                    <input type="checkbox" name="hipwifi" @if ($edit) {{$data['brand']->hipwifi;}} @endif> WIFI
                  </div>
                  <div class="appcheckboxes">
                    <input type="checkbox" name="hiprm" @if ($edit) {{$data['brand']->hiprm;}} @endif> SURVEY
                  </div>
                  <div class="appcheckboxes">
                    <input type="checkbox" @if (\User::isVicinity()) {{'checked'}} @endif  name="hipjam" @if ($edit) {{$data['brand']->hipjam;}} @endif> TRACK
                  </div>
                  <div class="appcheckboxes">
                    <input type="checkbox" name="hipengage" @if ($edit) {{$data['brand']->hipengage;}} @endif> ENGAGE
                  </div>
                </label>
              </div>
            </div>
            @if (\User::isVicinity())
              <input type="hidden" name="parent_brand" value="165">
            @endif
            <button class="btn btn-primary" id="admin_add_brand_button" type="button">Submit</button>
            <a href="{{ url('admin_showbrands'); }}" class="btn btn-default">Cancel</a>
            </form>          
        </div>
      </div>
    </div>

  

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>

    @if(\User::isVicinity())
    <script>
      $(document).on('input', '#brand_name_input', function() {
        let sub = $('#brand_name_input').val().substring(0, 6)
        $('#brand_code_input').val(sub)
      });
    </script>
    @endif

    <script>
    $(document).on('click', '#admin_add_brand_button', function() {
      $.post('/admin_check_if_brand_exists', {name: $('#brand_name_input').val()}, function(b) {
        let brand = JSON.parse(b);
        if (brand.exists) {
          $('#brand_name_container').addClass('has-error');
          $('#brand_already_exists').slideDown('fast');
        } else {
          $('#brand_name_container').removeClass('has-error');
          $('#brand_already_exists').slideUp('fast');
          $('#mainform').submit();
        }
      })
    });
    </script>
    
    <script>


    $('.btn-delete').click(function(){
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this brand?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
        //closeOnCancel: false
      },
      function(){
        swal("Deleted!", "Brand has been deleted!", "success");
      });
    });
	    	
    </script>

  </body>
@stop
