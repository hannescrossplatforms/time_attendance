@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="HipADMIN">

              <form role="form" id="useradmin-form" method="post" 
                    action=" @if ($edit) {{ url('useradmin_edit'); }} @else {{ url('useradmin_add'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        @include('admin.sidebar')

        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"> -->
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">@if ($edit) Edit @else Add @endif User</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif
          <div class="row">
              <div class="col-md-12">

<!-- form was here -->

                  {{ Form::hidden('id', $data['user']->id) }}
                	<div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" 
                        name="fullname" placeholder="" value="@if(Input::old('fullname')){{Input::old('fullname')}}@else{{ $data['user']->fullname }}@endif"
                        required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" 
                        name="email" placeholder="" 
                        value="@if(Input::old('email')){{Input::old('email')}}@else{{ $data['user']->email }}@endif" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Password</label>
                    <input type="password" class="form-control" id="exampleInputEmail1" 
                        name="password" placeholder="" 
                        @if(!$edit) required @endif>
                  </div>
                  <h2 class="sub-header">Access Level</h2>
                  @if (\User::hasAccess("superadmin")) 
              		<div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="superadmin" 
                        @if ( $data['user']->level_code == "superadmin" ) checked @endif >
                        {{ $data['level_names']['superadmin']; }}
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="admin"
                        @if ( $data['user']->level_code == "admin" ) checked @endif >
                        {{ $data['level_names']['admin']; }}
                    </label>
                  </div>
                  @endif
                  @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
                  <div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="reseller"
                        @if ( ($data['user']->level_code == "reseller") || !$edit ) checked @endif >
                        
                        {{ $data['level_names']['reseller']; }}
                    </label>
                  </div>
                  @endif
                  @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller")) 
                  <div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="brandadmin"
                        @if ( ($data['user']->level_code == "brandadmin") || !$edit ) checked @endif >
                        {{ $data['level_names']['brandadmin']; }}
                    </label>
                  </div>  
                  <div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="mediamanager"
                        @if ( ($data['user']->level_code == "mediamanager") || !$edit ) checked @endif >
                        {{ $data['level_names']['mediamanager']; }}
                    </label>
                  </div>  
                  <div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="defaultuser"
                        @if ( ($data['user']->level_code == "defaultuser") || !$edit ) checked @endif >
                        {{ $data['level_names']['defaultuser']; }}
                    </label>
                  </div>  
                  @endif

                  <h2 class="sub-header">Brands Managed</h2>
                  <div class="table-responsive">
                    <table id="brandManagementTable" class="table table-striped"></table>
                    <div class="form-group">
                        <div class="input-group">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>        
                        @endforeach
                          <a href="#" id="addbrand" class="btn btn-primary" data-toggle="modal" data-target="#addBrandModal">
                            <i class="fa fa-plus"></i>Add Brand
                          </a>
                        </div>
                    </div>
                  </div>
                  <!-- <div class="table-responsive"></div> -->


                  <h2 style="{{\User::isVicinity() ? 'display:none' : ''}}" class="sub-header">Product Access</h2>
                  <div style="{{\User::isVicinity() ? 'display:none' : ''}}"  class="checkbox">
                    <label>
                      <!-- <input type="checkbox" name="product_ids[]" value="1" checked> -->
                      {{ Form::checkbox('product_ids[]', 1, $data['products']['HipWIFI'], ['id' => 'product_ids1']) }}
                      WIFI 
                    </label>
                    <a href="#" id="xyz" data-toggle="modal" data-target="#hipWIFIModal"><i class="fa fa-gear" ></i></a>
                  </div>
                  <div style="{{\User::isVicinity() ? 'display:none' : ''}}"   class="checkbox">
                    <label>
                      <!-- $data['products']['HipRM']) -->
                      {{ Form::checkbox('product_ids[]', 2, $data['products']['HipRM'], ['id' => 'product_ids2']) }}
                      SURVEYS
                    </label>
                    <a href="#" data-toggle="modal" data-target="#hipRMModal"><i class="fa fa-gear" ></i></a>
                  </div>
                  <div style="{{\User::isVicinity() ? 'display:none' : ''}}"   class="checkbox">
                    <label>
                      {{ Form::checkbox('product_ids[]', 3, $data['products']['HipJAM'], ['id' => 'product_ids3']) }}
                      TRACK
                    </label>
                    <a href="#" data-toggle="modal" data-target="#hipJAMModal"><i class="fa fa-gear" ></i></a>
                  </div>
                  <div style="{{\User::isVicinity() ? 'display:none' : ''}}"   class="checkbox">
                    <label>
                      {{ Form::checkbox('product_ids[]', 4, $data['products']['HipENGAGE'], ['id' => 'product_ids4']) }}
                      ENGAGE
                    </label>
                    <a href="#" data-toggle="modal" data-target="#hipJAMModal"><i class="fa fa-gear" ></i></a>
                  </div>
                  <div style="{{\User::isVicinity() ? 'display:none' : ''}}"   class="checkbox">
                    <label>
                      {{ Form::checkbox('product_ids[]', 5, $data['products']['HipREPORTS'], ['id' => 'product_ids5']) }}
                      REPORTS
                    </label>
                    <a href="#" data-toggle="modal" data-target="#hipJAMModal"><i class="fa fa-gear" ></i></a>
                  </div> 
                  <div style="{{\User::isVicinity() ? 'display:none' : ''}}"   class="checkbox">
                    <label>
                      {{ Form::checkbox('product_ids[]', 6, $data['products']['HipTnA'], ['id' => 'product_ids6']) }}
                      T&A 
                    </label>
                    <a href="#" data-toggle="modal" data-target="#hipTnAModal" ><i class="fa fa-gear" ></i></a>
                    <!-- <a data-toggle="collapse" data-target="#notificationdisplaywrapper"><img class="expandcontract" src="/img/expand.png" ></a> -->
                  </div>                                   
                  <br> 
                  <button id="submitform" class="btn btn-primary">Submit</button>
                  <a href="{{ url('useradmin_showusers'); }}" class="btn btn-default">Cancel</a>
                    <!-- form ended here -->
            
            
                </div>
            </div>

        </div>
      </div>
    </div>
    
  <!-- Page Modals
    ================================================== -->
    
    <!-- Add Brand Modal -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">Add a brand</h6>
          </div>
          <div class="modal-body">

                  <div class="form-group">
                    <label>Brand Name </label>
                    <select id="brandlist" name="brand_id" class="form-control no-radius" ></select>
                  </div>

                  <div class="form-group">
                  <label>Country</label>
                  <select id="countrielist" class="form-control">
                      @foreach($data['countries'] as $countrie)
                        <option value="{{ $countrie->id }}">{{ $countrie->name }}</option>
                      @endforeach 
                  </select>                        
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button id="savebrands" type="button" class="btn btn-primary">Add</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- hipRM Modal -->
    <div class="modal fade" id="hipRMModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">HipWIFI Admins - View/Change Settings</h6>
          </div>
          <div class="modal-body">
                <!-- <form role="form"> -->
                    <div class="checkbox">
                      <label>
<!--                         <input type="checkbox" name="permission_ids[]" value="1">
 -->                        {{ Form::checkbox('permission_ids[]', 1, $data['permissions']['ques_rw']) }}
                          Add/Remove Questions
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
<!--                         <input type="checkbox" name="permission_ids[]" value="2">
 -->                        {{ Form::checkbox('permission_ids[]', 2, $data['permissions']['media_rw']) }}
                          Manage Media Backgrounds
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
<!--                         <input type="checkbox" name="permission_ids[]" value="3">
 -->                        {{ Form::checkbox('permission_ids[]', 3, $data['permissions']['uru_rw']) }}
                        Change User Redirect URL
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
                        <!-- <input type="checkbox" name="permission_ids[]" value="4"> -->
                            {{ Form::checkbox('permission_ids[]', 4, $data['permissions']['rep_rw']) }}
                        Access Reports Server
                      </label>
                    </div>
                <!-- </form> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- hipWIFI Modal -->
    <div class="modal fade" id="hipWIFIModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">HipRM Admins - View/Change Settings</h6>
          </div>
          <div class="modal-body">
                <p>content needed</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- hipJAM Modal -->
    <div class="modal fade" id="hipJAMModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">HipJAM Admins - View/Change Settings</h6>
          </div>
          <div class="modal-body">
                <p>content needed</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- hipLOYALTY Modal -->
    <div class="modal fade" id="hipLOYALTYModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">HipLOYALTY Admins - View/Change Settings</h6>
          </div>
          <div class="modal-body">
                <p>content needed</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

        <!-- hipTnA Modal -->
    <div class="modal fade" id="hipTnAModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h6 class="modal-title" id="myModalLabel">HipTnA - View/Change Roles</h6>
          </div>
          <div class="modal-body">
                <select id="tnapermissions" class="form-control"></select>
                <button id="addtnapermission" type="button" class="btn btn-primary">Add</button>
          </div>
          <div id="usertnapermissions"></div>
          <div>
            <table id="tnaRolesTable" class="table table-striped"></table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/prefixfree.min.js') }}"></script>

    @if (\User::isVicinity())
    <script>
      $('#product_ids3').prop('checked','checked');
    </script>
    @endif
    
  <script>
  
    var brandArray = <?php echo json_encode($data['brandArray']) ?>;
    //user_id = 112;

    $(function() {
        user_id = $("input[name=id]").val();
      
        showSelectedBrands();
        showAvailableTnARoles();
        showAssignedTnARoles();
    });


    $(document).delegate('#addbrand', 'click', function() {
        buildBrandList();
    });

    $(document).delegate('#countrielist', 'change', function() {
        buildBrandList();
    });

    $(document).delegate('#savebrands', 'click', function() {
        selectedValue=$( "#brandlist" ).val();
        text=$( "#brandlist option:selected" ).text();
        console.log('selectedValue : ' + selectedValue);
        var brandrecord = {name:text, countrie:$( "#countrielist option:selected" ).html()};
        brandArray[selectedValue] = brandrecord;
        // brandArray[selectedValue] = text;
        console.log(brandArray);
        
        showSelectedBrands();
    });

    $(document).delegate('.btn-delete', 'click', function() {
      brandId = $(this).data('brand-id');
      brandArray[brandId] = null;
      showSelectedBrands();
    });

    function showAssignedTnARoles() {        

      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        data: { 
              'user_id': user_id, 
              'product_id': 6 
        },
        url: "{{ url('useradmin_getrolesforuserandproduct/'); }}",
        success: function(roles) {
          console.log("Assigned Roles : " + roles);

          var selectedRoles = "";
          beginTable = ' \
              <thead> \
                  <tr> \
                    <th>Role Name</th> \
                    <th>Description</th> \
                    <th style="width:200px;"></th> \
                  </tr> \
                </thead> \
                <tbody> \
                ';
          endTable = '</tbody>';

          selectedRoles = beginTable;
          $.each(roles, function( roleId, value ) {
            if(value) {
              row = '\
                      <tr>\
                        <td> <input type="hidden" name="role_ids" id="role_id_' + value["id"] + '" value="' + value["id"] + '">'  + value["name"]  + ' </td>\
                        <td> ' + value["description"] + '</td>\
                        <td> <btn data-role-id="' + value["id"] + '" class="btn btn-default btn-delete btn-delete-permission btn-sm"> delete </btn></td>\
                      </tr>\
                    ';
              selectedRoles = selectedRoles + row;
          }
          selectedRoles = selectedRoles + endTable;

          });
          $( "#tnaRolesTable" ).html( selectedRoles );

        }
      });
    }

    function showAvailableTnARoles() {

      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        data: { 
                'user_id': user_id, 
                'product_id': 6 
          },
        url: "{{ url('useradmin_getrolesforproduct/'); }}",
        success: function(roles) {
          console.log("Roles : " + roles);

          openSelect = '<select id="tnapermissions" name="tna_permission_id" class="form-control">';
          options = '<option selected="selected">Please select</option>';
          $.each(roles, function(index, value) {
            options = options + '<option id="tna_permission_id_' + value["id"] + '" value="' + value["id"] + '">' + value["name"] + '</option>';
          });
          closeSelect = '</select>';

          selectHtml = openSelect + options + closeSelect;

          $( "#tnapermissions" ).html( selectHtml );

        }
      });
    }

    function buildBrandList() {
        var countrie_id = $( "#countrielist" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getbrands/" + countrie_id + "'); }}",
            success: function(brands) {
              var brandsjson = brands; 
              console.log("Brands : " + brands);

              openSelect = '<select id="brandlist" name="brand_id" class="form-control">';
              options = '<option selected="selected">Please select</option>';
              $.each(brandsjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#brandlist" ).html( selectHtml );

            }
          });
      }


    function showSelectedTnARoles(roles) {

    }

    function showSelectedBrands() {
        selectedBrands = "";
        beginTable = ' \
            <thead> \
                <tr> \
                  <th>Brand Name</th> \
                  <th>Country</th> \
                  <th style="width:200px;"></th> \
                </tr> \
              </thead> \
              <tbody> \
              ';
        endTable = '</tbody>';

        selectedBrands = beginTable;
        $.each(brandArray, function( brandId, value ) {
          if(value) {
            row = '\
                    <tr>\
                      <td> <input type="hidden" name="brand_ids[]" value="' + brandId + '">'  + value["name"]  + ' </td>\
                      <td> ' + value["countrie"] + '</td>\
                      <td> <btn data-brand-id="' + brandId + '" class="btn btn-default btn-delete btn-sm"> delete </btn></td>\
                    </tr>\
                  ';
            selectedBrands = selectedBrands + row;
        }
        selectedBrands = selectedBrands + endTable;

        // console.log(selectedBrands);
        });
        $( "#brandManagementTable" ).html( selectedBrands );
    }

    $("#useradmin-form").submit(function(){
    });

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
      
    $('.btn-sms').click(function(){
      swal({
        title: "Are you sure?",
        text: "An SMS will be Re-Sent to the user?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#428bca',
        confirmButtonText: 'Yes, send it!',
        closeOnConfirm: false,
        //closeOnCancel: false
      },
      function(){
        swal("SMS Sent!", "SMS has been sent to user!", "success");
      });
    });
    
     $(document).delegate('#addtnapermission', 'click', function() {
      role_id = $('#tnapermissions').val();
      //brandArray[brandId] = null;
      addtnapermission();
    });
    
    function addtnapermission() {
        
//        alert(role_id);
//        alert(user_id);
        $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          data: { 
                'user_id': user_id, 
                'role_id': role_id 
          },
          url: "{{ url('useradmin_addtnapermission/'); }}",
          
          success: function(objResult) {
            $('#tna_permission_id_'+objResult.id).closest('option').remove();
            $('#tnaRolesTable').append(objResult.row);

          }
      });
    }
    
    $(document).delegate('.btn-delete-permission', 'click', function() {
      role_id = $(this).data("role-id");      
      deleteTnAPermission();
    });
    
    function deleteTnAPermission() {
      
        $.ajax({
          type: "GET",
          dataType: 'json',
          contentType: "application/json",
          data: { 
                'user_id': user_id, 
                'role_id': role_id 
          },
          url: "{{ url('useradmin_deletetnapermission/'); }}",
          
          success: function(objResult) {
               $('#role_id_'+objResult.id).closest('tr').remove();
               $('#tnapermissions').append(objResult.row);

            
          }
      });
    }
    
    
      


    </script>
    
                </form>
  </body>
@stop
