<!-- <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> -->
	<div>
            <!-- <h1 class="page-header">User Admin</h1> -->
            <a id="buildtable"></a> <!-- Needed to trigger click to build table -->
            <form  class="form-inline" role="form" style="margin-bottom: 15px;" >
              <div class="form-group">
                <label class="sr-only">Name</label>
                <input class="form-control" id="role_name" placeholder="Name" name="fullname">
              </div>
              <div class="form-group">
                <label class="sr-only">Description</label>
                <input class="form-control" id="role_description" placeholder="Description" name="description">
              </div>
              <div class="form-group">
                <select id="role_product_id" class="form-control product_code">
                    <option>Product Code</option>

                </select>

              </div>

                <a href="" class="btn btn-primary add_role" id="add_role" ><i class="fa fa-plus"></i> Add Role</a>
            </form>

            <div class="table-responsive">
              <table id="roleTable" class="table table-striped">
              	<thead>
                    <tr>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Product id</th>
                      <th>
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>
            </div>
        </div>

<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    <script src="/js/prefixfree.min.js"></script>

<script type="text/javascript">
    usersJason = [{'fullname':'anusha','email':'hghgh@fj.bhh'}];

    $(function() {
        var roleproduct;
        showRoleAvailableProducts();
        $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: "{{ url('admin_showAvailableRoles')}}",
                // data: "newrecord="+dataJson,
                //async: false,
                success:  function(objResult){
                  role = objResult
                  renderRoleRows()

                }
            });
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
         });

        $(document).delegate('#buildtable', 'click', function() {
          showUsersTable(usersJason);
    });

    function renderRoleRows()
    {
        rows = '';

        $.each(role,function(roleindex,rolevalue) {

        href = '{{ url("admin_roleedit/' + rolevalue['id'] + '")}}';
        rows = rows + '<tr>\
                <td>' + rolevalue["name"] + '</td>\
                <td>' + rolevalue["description"] + '</td> <td>';

                    $.each(roleproduct,function(roleproductindex,roleproductvalue) {
                        if(roleproductvalue['id'] == rolevalue['product_id']){
                            product_name = roleproductvalue["name"];

                        } else {
                            product_name = '';

                        }
                       rows = rows + product_name ;
                    });

        rows = rows + '</td><td><a href="'+ href +'" class="btn btn-default btn-sm">Edit</a>\
                    <a id="btn_delete_' + rolevalue["id"] + '" class="btn btn-default btn-delete btn-sm" data-roleid = "' + rolevalue["id"] + '" href="#">Delete</a>\
                </td>';
                });

        $("#roleTable tbody:last-child").append(rows);
    }

    function showRoleAvailableProducts()
    {

        $.ajax({
            type:"GET",
            dataType:"Json",
            contentType:'Application/Json',
            url:"{{ url('admin_getAvailableProducts/');}}",
            success:function(resultObj)
            {
                roleproduct= resultObj,
                options = '<option selected="selected">Product Code</option>';

            $.each(roleproduct, function(index, value) {
              options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
            });

            $( "#role_product_id" ).html( options );

            }
        });
    }

    function showUsersTable(usersjson)
    {
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Full Name</th>\n\
                      <th>Email Address</th>\n\
                      <th>Access Level</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(usersjson, function() {
          alert(usersjson);
            $.each(this, function(name, value) {
                rows = rows + '\
                        <tr>\n\
                          <td> ' + value["fullname"]  + '</td>\n\
                          <td> ' + value["email"]  + '</td>\n\
                          <td> ' + value["level_name"]  + '</td>\n\
                          <td><a href="{{ url('useradmin_edit'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                              <a class="btn btn-default btn-delete btn-sm" data-userid = ' + value["id"] + ' href="#">delete</a>\n\
                          </td>\n\
                        </tr>\n\
                        ';
            });
         });

         endTable = ' \
                </tbody>\n\
              </table>';

        table = beginTable + rows + endTable;
        $( "#userTable" ).html( table );
    }


    $( '#add_role' ).click( function( e ) {
        e.preventDefault();
        role_name        = $('#role_name').val();
        role_description = $('#role_description').val();
        role_product_id  = $('#role_product_id').val();
        addRole();

    });

    function addRole()
        {

            $.ajax({
                type: "GET",
                dataType: 'json',
                //contentType: "application/json",
                url: "{{ url('admin_addRole/');}}",
                data: {
                            'role_name'       : role_name,
                            'role_description': role_description,
                            'role_product_id' : role_product_id

                      },
                //async: false,
                success:  function(objResult){
                    $(objResult.row).prependTo("table#roleTable > tbody");
                    $('#role_name').val('');
                    $('#role_code').val('');
                    $('#role_description').val('');
                    showRoleAvailableProducts();

                }
            });

        }

    $(document).delegate('.btn-delete', 'click', function() {
        roleId = $(this).data('roleid');

         $.ajax({
                type: "POST",
                dataType: 'json',
                url: "{{ url('admin_deleteRole')}}",
                data: "id="+roleId,
                success:  function(objResult){
                  $('#btn_delete_'+objResult.id).closest('tr').remove();
                }
            });
    });



</script>