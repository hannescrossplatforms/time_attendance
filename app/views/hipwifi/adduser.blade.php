@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipWifi">

              <form role="form" id="useradmin-form" method="post" 
                    action=" @if ($edit) {{ url('hipwifi_edituser'); }} @else {{ url('hipwifi_adduser'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

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
                        name="fullname" placeholder="" value="@if(Input::old('fullname')){{Input::old('fullname')}}@else{{ $data['user']->fullname }}@endif">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" 
                        name="email" placeholder="" value="@if(Input::old('email')){{Input::old('email')}}@else{{ $data['user']->email }}@endif" >
                  </div>
                    <div class="form-group">
                    <label for="exampleInputEmail1">Password</label>
                    <input type="password" class="form-control" id="exampleInputEmail1" 
                        name="password" placeholder="" 
                        @if(!$edit) required @endif>
                  </div>
                  <h2 class="sub-header">Access Level</h2>
                  @if (\User::hasAccess("superadmin")) 
<!--                   <div class="radio">
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
                  </div> -->
                  @endif
                  @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
    <!--               <div class="radio">
                    <label>
                      <input type="radio" name="level_code" id="level_code" value="reseller"
                        @if ( $data['user']->level_code == "reseller" ) checked @endif >
                        {{ $data['level_names']['reseller']; }}
                    </label>
                  </div> -->
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
                        @if ( ($data['user']->level_code == "mediamanager") || !$edit ) @endif >
                        {{ $data['level_names']['mediamanager']; }}
                    </label>
                  </div>  
                  @endif

               <h2 class="sub-header">Brands Managed</h2>
                  <div class="table-responsive">
                    <table id="brandManagementTable" class="table table-striped"></table>
                    <div class="form-group">
                        <div class="input-group">
                          <a href="#" id="addbrand" class="btn btn-primary" data-toggle="modal" data-target="#addBrandModal">
                            <i class="fa fa-plus"></i>Add Brand
                          </a>
                        </div>
                    </div>
                  </div>

                  <br> 
                  <button class="btn btn-primary">@if ($edit) Update @else Submit @endif</a>
                  <button href="userAdmin.html" class="btn btn-default">Cancel</a>
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

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>
    
   <script>
  
    var brandArray = <?php echo json_encode($data['brandArray']) ?>;

    $(function() {
      // console.log("begin");
        showSelectedBrands();
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
      


    </script>
    
                </form>
  </body>
@stop
