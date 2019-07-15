      <div class="panel panel-default">
          <div class="panel-heading">Add Permissions</div>

            <div class="panel-body">

              <div class="form-group">
                <div class="table-responsive">

                      <table class="tnastafftable">
                        <thead>
                          <tr>
                            <th class="tnastafftd_name">  </th>
                            <th class="tnastafftd_email">  </th>
                            <th class="tnastafftableheader"></th>
                            <th class="tnastafftableheader"></th>
                            <th class="tnastafftableheader"></th>
                            <th class="tnastafftableheader"></th>
                          </tr>
                        </thead>

                        <tbody>
                          <tr>
                            <td class="tnastafftd_name"> <input id="permission_name" class="form-control no-radius" placeholder="Name"  type="text"> </td>
                            <td class="tnastafftd_name"> <input id="permission_code" class="form-control no-radius" placeholder="Code"  type="text"> </td>
                            <td class="tnastafftd_name"> <input id="permission_description" class="form-control no-radius" placeholder="Description"  type="text"> </td>
                            <td class="tnastafftd_name"> <select id="permission_product_id" class="form-control no-radius product_code">
                              <option>Product Code</option>

                            </select> </td>
                            <td class="tnastafftd_add_update" style="width:16%;"> <a id="addpermission"  class="btn btn-default btn-delete btn-sm addpermission">Add</a> </td>
                            <td class="tnastafftd5">  </td>
                          </tr>
                        </tbody>
                      </table>

                      <div id="permissiontableview"></div>

                </div>
              </div>
            </div>
          </div>

<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    <script src="/js/prefixfree.min.js"></script>

    <script type="text/javascript">
        //permissions = new Array();
        $(document).ready(function(){

            var product;
            showAvailableProducts();
            setTimeout(function() {
              $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: "{{ url('admin_showAvailablePermission')}}",
                // data: "newrecord="+dataJson,
                //async: false,
                success:  function(objResult){
                  permissions = objResult
                  renderPermissionsRows()

                }

              }, 3000);

            });

        })


    function showAvailableProducts()
    {

        $.ajax({
            type:"GET",
            dataType:"Json",
            contentType:'Application/Json',
            url:"{{ url('admin_getAvailableProducts/');}}",
            success:function(resultObj)
            {
                product= resultObj,
                options = '<option selected="selected">Product Code</option>';

            $.each(product, function(index, value) {
              options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
            });

            $( "#permission_product_id" ).html( options );

            }
        });
    }

        function renderPermissionsRows()
        {
            rows = '<table   id="permissionTable"  class="tnastafftable"><tbody>';
            $.each( permissions, function( index, value ) {

                rows = rows + '<tr>\
                          <td class="tnastafftd_name" style="width:19%;"> <input id="permission_name_'+ value["id"] +'" class="form-control no-radius" placeholder="Name"  type="text" value="' + value["name"] + '"> </td>\
                          <td class="tnastafftd_name" style="width:23%;"> <input id="permission_code_'+ value["id"] +'" class="form-control no-radius" placeholder="Code" type="text" value="' + value["code"] + '"> </td>\
                          <td class="tnastafftd_name" style="width:19%;"> <input id="permission_description_'+ value["id"] +'" class="form-control no-radius" placeholder="Description" type="text" value="' + value["description"] + '"> </td>\
                          <td class="tnastafftd_name" style="width:19.5%;"> <select id="permission_product_id_'+ value["id"] +'" class="form-control no-radius product_code"><option selected="selected">Product Code</option>';

            $.each(product, function(key, row) {
                if(value['product_id'] == row['id']) {

                    option_selected =  'selected="selected"';

                } else {

                    option_selected     =   '';
                }
              rows = rows+'<option value="' + row["id"] + '"' + option_selected + '>'+row["name"] + '</option>';
            });

rows = rows + '</select></td>\<td class="tnastafftd_add_update" style="width:17%;"> <a id="updatePermissionDetails_'+ value["id"] +'" class="btn btn-default btn-delete btn-sm" onclick="updatePermissionDetails('+ value["id"] +');">Update</a> <a id="deletePermissionDetails_'+ value["id"] +'" class="btn btn-default btn-delete btn-sm" onclick="deletePermissionDetails('+ value["id"] +');">Delete</a></td>\
                          <td></td>\
                        </tr>\
                    ';
            });

            rows += '</tbody>\
                  </table>';

            $("#permissiontableview").html(rows);
        }

        $(document).delegate('.addpermission', 'click', function() {

           permission_name        = $('#permission_name').val();
           permission_code        = $('#permission_code').val();
           permission_description = $('#permission_description').val();
           permission_product_id  = $('#permission_product_id').val();
           addpermission();
        });

        function addpermission()
        {

            $.ajax({
                type: "GET",
                dataType: 'json',
                //contentType: "application/json",
                url: "{{ url('admin_addPermission/');}}",
                data: {
                            'permission_name'       : permission_name,
                            'permission_code'       : permission_code,
                            'permission_description': permission_description,
                            'permission_product_id' : permission_product_id

                      },
                //async: false,
                success:  function(objResult){
                    $(objResult.row).prependTo("table#permissionTable > tbody");
                    $('#permission_name').val('');
                    $('#permission_code').val('');
                    $('#permission_description').val('');
                    showAvailableProducts();

                }
            });

        }

        function deletePermissionDetails(id)
        {

            permission_id = id;
            //newrecord['id'] = id ;

            $.ajax({
                type: "POST",
                dataType: 'json',

                url: "{{ url('admin_deletePermission')}}",
                data: "id="+permission_id,
                success:  function(objResult){
                  $('#permission_name_'+objResult.id).closest('tr').remove();
                }
            });
        }

        function updatePermissionDetails(id)
        {

            permission_id = id;
            permission_name        = $('#permission_name_'+permission_id).val();
            permission_code        = $('#permission_code_'+permission_id).val();
            permission_description = $('#permission_description_'+permission_id).val();
            permission_product_id  = $('#permission_product_id_'+permission_id).val();

            $.ajax({
                type: "POST",
                dataType: 'json',

                url: "{{ url('admin_updatePermission')}}",
                data: {
                        'permission_id'         : permission_id,
                        'permission_name'       : permission_name,
                        'permission_code'       : permission_code,
                        'permission_description': permission_description,
                        'permission_product_id' : permission_product_id
                      },
                success:  function(objResult){

                }
            });
        }


</script>