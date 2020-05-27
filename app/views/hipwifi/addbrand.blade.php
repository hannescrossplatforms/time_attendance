@extends('angle_wifi_layout')
<?php 
error_log("Edit is " . $data["edit"]);

$edit = $data["edit"] ;
if($data["is_activation"]) { $is_activation = 1; } else { $is_activation = 0; };
?>
@section('content')

<section class="section-container">
  <div class="content-wrapper">
    <div class="content-heading">
      <div>@if ($is_activation) Activate @else Edit @endif Brand<small data-localize="dashboard.WELCOME"></small></div>
    </div>

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
              @if ($errors->has())
                  <div class="alert alert-danger">
                      @foreach ($errors->all() as $error)
                      <?php error_log("here 20 : $error"); ?>
                          {{ $error }}<br>        
                      @endforeach
                  </div>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-12">
              <form role="form" method="post" id="mainform" action="@if ($is_activation) {{ url('hipwifi_activatebrand'); }} @else {{ url('hipwifi_editbrand'); }} @endif">
              {{ Form::hidden('id', $data['brand']->id) }}
              {{ Form::hidden('oldbrandcode', $data['brand']->code) }}
              {{ Form::hidden('is_activation', $is_activation) }}

              @if (!$edit) 
              <div class="form-group">
                <label>ISP*</label>
                <select id="isplist" name="isp_id" class="form-control" disabled>
                  @foreach($data['allisps'] as $isp)
                    <option value="{{ $isp->id }}">
                      {{ $isp->name }} ({{ $isp->code }})
                    </option>
                  @endforeach 
                </select>
              </div>
              @endif
              <div class="form-group">
                <label for="brand_name">Brand Name</label>
                <input type="text" class="form-control" id="" placeholder="" 
                        name="name" 
                        value="@if(Input::old('name')){{Input::old('name')}}@else{{$data['brand']->name;}}@endif" disabled>
              </div>
              <div class="form-group">
                <label for="brand_code">Brand Code</label>
                <input type="text" class="form-control" id="" size="6" placeholder="" name="code" 
                       value="@if(Input::old('code')){{Input::old('code')}}@else{{$data['brand']->code;}}@endif" disabled>
              </div>
              <div class="form-group">
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
              
            <?php error_log("here 20"); ?>
              @if ($edit)
            <?php error_log("here 24"); ?>
              <div class="form-group">
                <label>Database</label>
                <input type="text" class="form-control" name="code" 
                       value="{{$data['database']->name}}" disabled>
              </div>
              @else
              <div class="form-group">
                <label>Database</label>
                <select id="databaselist" name="remotedb_id" class="form-control no-radius">
                  @foreach($data['databases'] as $database)
                    <option value="{{ $database->id }}">
                      {{ $database->name }}
                    </option>
                  @endforeach 
                </select>
              </div>
              @endif
            <?php error_log("here 26"); ?>

              <div class="form-group">
                <label for="exampleInputEmail1">Login Process</label>
                <select id="login_process" name="login_process" class="form-control no-radius">
                  @foreach($data['login_process'] as $login_process)
                    <option value="{{ $login_process['value'] }}" {{ $login_process['selected'] }}>
                      {{ $login_process['text'] }}
                    </option>
                  @endforeach 
                </select>
              </div>
              <div class="form-group">
                  <div class="input-group">
                    <a id="configButton" href="#" class="btn btn-default" data-toggle="modal" data-target="#configureZeroRegModal"><i class="fas fa-cog"></i> Configure Fields For Zero Registration</a>
                    </a>
                  </div>
              </div>
            <?php error_log("here 30"); ?>


              <div class="form-group">
            <?php error_log("here 20"); ?>
                <label for="exampleInputEmail1">Welcome Message</label>
                <input type="text" class="form-control" name="welcome" 
                      value="@if(Input::old('welcome')){{Input::old('welcome')}}@else{{$data['brand']->welcome;}}@endif" 
                        id="" placeholder="Enter Welcome Message">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Access Point SSID</label>
                <input type="text" class="form-control" name="ssid" 
                       value="@if(Input::old('ssid')){{Input::old('ssid')}}@else{{$data['brand']->ssid;}}@endif"
                        id="" >
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">User Redirection URL</label>
                <input type="text" class="form-control" name="uru" 
                       value="@if(Input::old('uru')){{Input::old('uru')}}@else{{$data['brand']->uru;}}@endif"
                        id="" placeholder="e.g. http://xxx.xyz.com">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Session Limit: &nbsp &nbsp</label>
                  <label class="radio-inline">
                    <input type="radio" name="limit_type" value="data" {{ $data['data_checked']}}> Data (Mb)
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="limit_type" value="time" {{ $data['time_checked']}}> Time (Minutes)
                  </label>
                <input type="text" class="form-control" name="limit" 
                       value="@if(Input::old('limit')){{Input::old('limit')}}@else{{$data['brand']->limit;}}@endif"
                        id="" >
              </div>

            <?php error_log("here 40"); ?>
             <!-- <div class="table-responsive"></div>  Is this needed? -->

              <div class="form-group">
                  <div class="input-group">
                  <label for="exampleInputEmail1">Server Host Names</label>
                </div>
                  <table id="serverManagementTable" class="table table-striped"></table>
              </div>

              <div class="form-group">
                <label>Terms and Conditions</label>
                <label class="radio-inline">
                  <input type="radio" id="standard_terms" name="terms_type" value="standard" {{ $data['standard_terms_checked']}}> Standard
                </label>
                <label class="radio-inline">
                  <input type="radio" id="custom_terms" name="terms_type" value="custom" {{ $data['custom_terms_checked']}}> Custom
                </label>
                <textarea id="terms" class="form-control" name="terms">
                  @if(Input::old('terms')){{Input::old('terms')}}@else{{$data['brand']->terms;}}@endif
                </textarea>
              </div>

          
            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="userdatabtn" @if ($edit) {{$data['brand']->userdatabtn;}} @endif> Show "Download User Data" button on reports 
                </label>
              </div>
            @endif

            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="logindatabtn" @if ($edit) {{$data['brand']->logindatabtn;}} @endif> Show "Customer Login Data" button on reports 
                </label>
              </div>
            @endif

            <button class="btn btn-primary">Submit</button>
            <a href="{{ url('hipwifi_showbrands'); }}" class="btn btn-default">Cancel</a>
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

    $( "#login_process" ).change(function(){
      console.log("xxlogin_process : " + $( "#login_process" ).val());
        if($( "#login_process" ).val() == "full") {
        console.log("login_process : FULL");
          $( "#configButton" ).hide();
        } else {
          console.log("login_process : NONE");
          $( "#configButton" ).show();
        }
          
    })
  
    $(function() {
      $( "#login_process" ).change();

      @if($edit)
        showServersForDatabase({{ $data['database']->id }});
      @else
        $('#databaselist').change();
      @endif
      
      showHideTerms()
      // $('textarea#terms').html($('textarea#terms').html().trim());
    });

    $('#standard_terms, #custom_terms').change(function() {
        showHideTerms()
        // alert($('input[name=terms_type]:radio:checked').val());
    });

    $(document).delegate('#databaselist', 'change', function() {
        remotedb_id=$( "#databaselist" ).val();

        showServersForDatabase(remotedb_id);
    });

    function showHideTerms() {
        if($('input[name=terms_type]:radio:checked').val() == "custom") {
            $('#terms').show();
        } else  {
            $('#terms').hide();
        }
    }

    function showServersForDatabase(remotedb_id) {

      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        data: { 
            'remotedb_id': remotedb_id 
        },
        url: "{{ url('lib_getserversfordatabase'); }}",
        success: function(servers) {
          serverManagementTable = ""; rows = "";
          beginTable = ' \
                <tbody> \
                ';
          endTable = '</tbody>';

          $.each(servers, function( i, server ) {
            rows = rows + '<tr><td>' + server["hostname"] + '</td></tr>';
          });

          serverManagementTable = beginTable + rows + endTable;

          $( "#serverManagementTable" ).html( serverManagementTable );

        }
      });
    }

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
@stop

@section('modals')
    <div class="modal fade" id="configureZeroRegModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog configureZeroRegModalClass modal-lg">
        <div class="modal-content" style="padding: 10px;">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Configure Fields For Zero Registration</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          @if (\User::hasAccess("superadmin")) 
          <div class="zonein_btn_text" ?>
            <div class="form-group">
              <label for="exampleInputEmail1" style="font-weight: 700; margin-top: 10px;">Zone In Button Text</label>
              <input form="mainform" type="text" class="form-control" name="zonein_btn_text" 
                     value="@if(Input::old('zonein_btn_text')){{Input::old('zonein_btn_text')}}@else{{$data['brand']->zonein_btn_text;}}@endif"
                      id="" >
            </div>
          </div>
          @else
              {{ Form::hidden('zonein_btn_text', 'Zone In', array("form" => "mainform")) }}
          @endif

          <div class="table-responsive">
              <table class="table table-striped" id="userTable">                
                  <thead>
                    <tr>
                      <th class="text-center">Field</th>
                      <th>Type</th>
                      <th>Field Config</th>
                      <th class="text-center">Registration</th>
                      <th class="text-center">Show</th>
                    </tr>
                  </thead>
                  <tbody>  
                    
                    <tr>
                      <td class="text-center"></td>
                      <td> Cellphone    
                        <input form="mainform" type="hidden" name="f1_type" value="cellphone" />           
                      </td>
                      <td><input form="mainform" type="text" class="form-control" name="f1_placeholder" 
                          value="{{$data['field_configuration']['f1_placeholder']}}" placeholder="Enter field text for cell phone">
                      </td>
                      <td class="text-center"><input form="mainform" type="radio" name="register_field" value="1" 
                        @if($data['field_configuration']['register_field'] == 1)checked @endif >
                      </td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f1_display" value="show" 
                        @if($data['field_configuration']['f1_display'] == "show")checked @endif >
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Email Address             
                        <input form="mainform" type="hidden" name="f2_type" value="email" />          
                      </td>
                      <td><input form="mainform" type="text" class="form-control" name="f2_placeholder" 
                      value="{{$data['field_configuration']['f2_placeholder']}}" placeholder="Enter field text for email address">
                      </td>
                      <td class="text-center"><input form="mainform" type="radio" name="register_field" value="2"
                        @if($data['field_configuration']['register_field'] == 2)checked @endif>
                      </td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f2_display" value="show" form="mainform"
                        @if($data['field_configuration']['f2_display'] == "show")checked @endif>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Voucher     
                        <input form="mainform" type="hidden" name="f3_type" value="voucher" />           
                      </td>
                      <td><input form="mainform" type="text" class="form-control" name="f3_placeholder" 
                        value="{{$data['field_configuration']['f3_placeholder']}}" placeholder="Enter field text for voucher">
                      </td>
                      <td class="text-center"><input form="mainform" type="radio" name="register_field" value="3" form="mainform"
                        @if($data['field_configuration']['register_field'] == 3)checked @endif
                        ></td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f3_display" 
                        value="show" @if($data['field_configuration']['f3_display'] == "show")checked @endif>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Age Gate     
                        <input form="mainform" type="hidden" name="f4_type" value="agegate" />           
                      </td>
                      <td><input form="mainform" type="text" class="form-control" name="f4_agegate" 
                        value="{{$data['field_configuration']['f4_agegate']}}" placeholder="Enter minimum age">
                      </td>
                      <td class="text-center"></td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f4_display" 
                        value="show" @if($data['field_configuration']['f4_display'] == "show")checked @endif>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Firstname</td>
                      <td class="left-cell">
                        <input form="mainform" type="checkbox" name="firstname_capture" value="1" 
                          @if($data['firstname_capture']) checked @endif > Capture 
                        
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <input form="mainform" type="checkbox" name="firstname_display" value="1" 
                          @if($data['firstname_display']) checked @endif > Display
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Social Media Heading Text</td>
                      <td><input form="mainform" type="text" class="form-control" name="sm_text" 
                        value="{{$data['brand']['sm_text']}}" placeholder="Enter text">
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Social Media Heading Color</td>
                      <td class="left-cell">
                        <input form="mainform" class="input-left" id="sm_color" name="sm_color" type="color" value='{{$data['brand']["sm_color"]}}'>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Social Media Button Size</td>
                      <td class="left-cell">
                        <input form="mainform" type="radio" name="sm_buttonsize" value="small" 
                          @if($data['sm_buttonsize'] == "small") checked @endif > Small 
                        
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <input form="mainform" type="radio" name="sm_buttonsize" value="large" 
                          @if($data['sm_buttonsize'] == "large") checked @endif > Large
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Facebook Login     
                        <input form="mainform" type="hidden" name="f5_type" value="facebook" />           
                      </td>
                      <td></td>
                      <td class="text-center"></td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f5_display" 
                        value="show" @if($data['field_configuration']['f5_display'] == "show")checked @endif>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                        <input form="mainform" type="hidden" name="f7_placeholder" value="f7_placeholder" />           
                      <td> Instagram Login     
                        <input form="mainform" type="hidden" name="f7_type" value="instagram" />           
                      </td>
                      <td></td>
                      <td class="text-center"></td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f7_display" 
                        value="show" @if($data['field_configuration']['f7_display'] == "show")checked @endif>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                        <input form="mainform" type="hidden" name="f8_placeholder" value="f8_placeholder" />           
                      <td> Twitter Login     
                        <input form="mainform" type="hidden" name="f8_type" value="twitter" />           
                      </td>
                      <td></td>
                      <td class="text-center"></td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f8_display" 
                        value="show" @if($data['field_configuration']['f8_display'] == "show")checked @endif>
                      </td>
                    </tr>

                    <tr>
                      <td class="text-center"></td>
                      <td> Custom Button     
                        <input form="mainform" type="hidden" name="f6_type" value="custombutton" />           
                      </td>
                      <td><input form="mainform" type="text" class="form-control" name="f6_placeholder" 
                        value="{{$data['field_configuration']['f6_placeholder']}}" placeholder="Enter button text"><br>
                        <input form="mainform" type="text" class="form-control" name="f6_url" 
                        value="{{$data['field_configuration']['f6_url']}}" placeholder="Enter url for button link">
                      </td>
                      <td class="text-center"></td>
                      <td class="text-center"><input form="mainform" type="checkbox" name="f6_display" 
                        value="show" @if($data['field_configuration']['f6_display'] == "show")checked @endif>
                      </td>
                    </tr>

                  </tbody>
                </table>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>

          </div>
        </div>
      </div>
    </div>
@stop
