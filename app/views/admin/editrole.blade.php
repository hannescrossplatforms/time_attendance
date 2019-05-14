@extends('layout')

<?php /*$edit = $data["edit"]*/ ?>

@section('content')

  <body class="HipADMIN">

<!--              <form role="form" id="useradmin-form" method="post" 
                    action=" {{ url('useradmin_edit'); }} >
    <div class="container-fluid">-->
      <div class="row">

        @include('admin.sidebar')

        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"> -->
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header"> Edit Role</h1>
            
          <div class="row">
              <div class="col-md-12">

<!-- form was here -->

                  @foreach($data['roleDetails'] as $roleDetails)
                  <div class="form-group">
                    <input type="hidden" class="form-control" id="role_id" 
                        name="fullname" placeholder="" value="{{ $roleDetails->id }}"
                        required>
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="role_name" 
                        name="fullname" placeholder="" value="{{ $roleDetails->name }}"
                        required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <input type="text" class="form-control" id="role_description" 
                        name="email" placeholder="" 
                        value="{{ $roleDetails->description }}" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Product id</label>
                    
                    <select id="product_id" class="form-control">
                       <option value="" >Product Code</option>
                       @foreach($data['product'] as $product)
                            @if($product->id == $roleDetails->product_id)
                            <option value="{{ $product->id }}" selected="selected">{{ $product->name }}</option>
                            @else
                                 <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endif 
                     
                      @endforeach 
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputEmail1" 
                        name="password" placeholder="" 
                         required> -->
                  </div>
                @endforeach 
                <h2 class="sub-header">Access Permissions</h2>
                <select id="rolepermission" class="form-control">
                    <option>Select Permissions</option>
                  @foreach($data['permission'] as $permission)
                  <option id="role_permission_id_{{ $permission->id }}" value="{{ $permission->id }}">{{ $permission->name }}</option>
                  @endforeach 
                </select>
                <br>
                <button id="addrolepermission" class="btn btn-primary">Add</button>
                <br>
                <br>
                <div class="form-group">
                    <div id="add_permission_list">
                        @foreach($data['added_permission'] as $permission_list)
                        <span id="permission_list_span_{{ $permission_list->id }}">{{ $permission_list->name }} <a data-permisssiontext="{{ $permission_list->name }}" data-permisssionid="{{ $permission_list->id }}" data-target="#" data-toggle="modal" class="remove_permission" href="#"><i class="fa fa-trash-o fa-lg "></i></a><br></span>
                         @endforeach 
                        
                    </div>
                </div>
                  <!-- <input type="checkbox" name="product_ids[]" value="1" checked>Permissions1 <br>
                  <input type="checkbox" name="product_ids[]" value="1" checked>Permissions2 <br>
                  <input type="checkbox" name="product_ids[]" value="1" checked>Permissions3 <br>
                  <input type="checkbox" name="product_ids[]" value="1" checked>Permissions4 <br>
                  <input type="checkbox" name="product_ids[]" value="1" checked>Permissions5 -->

                                                    
                  <br> 
                  <button id="submitform" class="btn btn-primary">Submit</button>
                  <a href="{{ url('admin_showrolesandpermissions'); }}" class="btn btn-default">Cancel</a>
                    <!-- form ended here -->
            
            
                </div>
            </div>

        </div>
      </div>
    </div>
    
  <!-- Page Modals
    ================================================== -->
    
    
    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>
    
  <script>
      $(document).ready(function(){
            role_permission_id_arr = new Array();
            role_permission_text_arr = new Array();
            permission_id_arr = new Array();
            permission_text_arr = new Array();            

            var arrayAddedPermission = <?php echo json_encode($data['added_permission']); ?>;
            $.each(arrayAddedPermission, function (i, elem) {

                permission_id_arr.push(elem['id']);
            });
            console.log(permission_id_arr);

            
            
      });
      
      
      $(document).delegate('#addrolepermission', 'click', function() {   
          
        rolepermissionId = $('#rolepermission').val();
        rolepermissionText = $( "#rolepermission option:selected" ).text(); 
        
        permission_id_arr.push(rolepermissionId);
        permission_text_arr.push(rolepermissionText);
        
        role_permission_id_arr.push(rolepermissionId);
        role_permission_text_arr.push(rolepermissionText);
     
        rows = '';
        
        $.each(role_permission_text_arr,function(permissiontextindex,permisssiontextvalue) {
            $.each(role_permission_id_arr,function(permissionidindex,permisssionidvalue) {
         rows = rows + '<span id="permission_list_span_' + permisssionidvalue + '">' + permisssiontextvalue +' <a href="#" class="remove_permission" data-toggle="modal" data-target="#" data-permisssionid ="' + permisssionidvalue + '" data-permisssiontext ="' + permisssiontextvalue + '"><i class="fa fa-trash-o fa-lg " ></i></a><br></span>';
           }); 
        });
        
        role_permission_id_arr = [];
        role_permission_text_arr = [];
        $("#add_permission_list").append(rows);
        $('#role_permission_id_'+rolepermissionId).remove();
        //$('#rolepermission').selectmenu("refresh", true);

      });
      
      $(document).delegate('.remove_permission', 'click', function() {
      
        permissionid = $(this).data('permisssionid');
        permissiontext = $(this).data('permisssiontext');
        role_permission_option = '<option id="role_permission_id_' + permissionid + '" value="' + permissionid + '">' + permissiontext + '</option>';

        $('#permission_list_span_'+permissionid).remove();        
        $("#rolepermission").append(role_permission_option);
        
        var selectList = $('#rolepermission option');

        selectList.sort(function(a,b){
            a = a.value;
            b = b.value;

            return a-b;
        });

        $('#rolepermission').html(selectList);     
        $("#rolepermission").val($("#rolepermission option:first").val());


       
        var removeItem = permissionid;

        permission_id_arr = jQuery.grep(permission_id_arr, function(value) {
          return value != removeItem;
        });

        console.log(permission_id_arr);
      
      });
      
      $(document).delegate('#submitform', 'click', function() { 
          
          permission_id_arr;  
          role_id          = $('#role_id').val();
          role_name        = $('#role_name').val();
          role_description = $('#role_description').val();
          product_id       = $('#product_id').val();
          edit_role(); 
           
        });
        
        
        function edit_role()
        {
            //redirect_url = {{ url('admin_showrolesandpermissions'); }};
            redirect_url = '';
            $.ajax({
                type     :"GET",
                dataType :"Json",
                url      : "{{ url('admin_editRole'); }}",
                async: false,
                data     : {
                    
                            'permission_id_arr' : permission_id_arr,
                            'role_id'           : role_id,
                            'role_name'         : role_name,
                            'role_description'  : role_description,
                            'product_id'        : product_id      
                            
                            },
                success   : function(objResult){
                    redirect_url = 1;                 
                }
                
            });
            
            if(redirect_url == 1){               
                window.location="{{URL::to('admin_showrolesandpermissions')}}";
            } 
            
        }
      

  
  

    </script>
    
<!--                </form>-->
  </body>
@stop
