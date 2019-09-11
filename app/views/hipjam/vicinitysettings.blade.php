@extends('layout')

@section('content')

<body class="hipJAM">

    <div class="container-fluid">
        <div class="row">

            @include('hipjam.sidebar')

            <!-- <div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 main">  -->
            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">User Admin</h1>
                <a id="buildtable"></a> <!-- Needed to trigger click to build table -->
                <form action=" {{ url('useradmin_showusers'); }}" class="form-inline" role="form" style="margin-bottom: 15px;">
                    <!-- <div class="form-group">
                        <label class="sr-only">Full Name</label>
                        <input class="form-control" id="fullname" placeholder="Full Name" name="fullname">
                    </div>
                    <div class="form-group">
                        <label class="sr-only">Email Address</label>
                        <input class="form-control" id="email" placeholder="Email Address" name="email">
                    </div>
                    <button type="submit" class="btn btn-primary" name="filter" value="on">Filter</button>
                    <button type="submit" class="btn btn-default" name="filter" value="off">Reset</button> -->
                    <a href="{{ url('useradmin_add'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
                </form>

                <div class="table-responsive">
                    <table id="userTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Access Level</th>
                                <th>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['users'] as $user) { ?>
                                @if ($user->id != 1 && $user->id != 45)
                                <tr>
                                    <td>{{$user->fullname}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->level_name}}</td>
                                    <td>
                                        <a href="/vicinity/users//edit" class="btn btn-default btn-sm">edit</a>
                                        <a class="btn btn-default btn-delete btn-sm" href="#">delete</a>
                                    </td>
                                </tr>
                                @endif
                            <?php  }
                            ?>
                        </tbody>
                    </table>
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
    <!-- <script>
        usersJason = {
            {
                $data[' usersJason'] } }; $(function() { $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load }); $(document).delegate('#buildtable', 'click' , function() { showUsersTable(usersJason); }); function showUsersTable(usersjson) { table='' ; rows='' ; beginTable='\
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
                  <tbody>  \n' ; $.each(usersjson, function() { $.each(this, function(name, value) { rows=rows + '\
                    <tr>\n\
                      <td> ' + value["fullname"] + '</td>\n\
                      <td> ' + value["email"] + '</td>\n\
                      <td> ' + value["level_name"] + '</td>\n\
                      <td><a href="{{ url('
                    useradmin_edit '); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                          <a class="btn btn-default btn-delete btn-sm" data-userid = ' + value["id"] + ' href="#">delete</a>\n\
                      </td>\n\
                    </tr>\n\
                    ' ; }); }); endTable=' \
                  </tbody>\n\
                </table>' ; table=beginTable + rows + endTable; $("#userTable").html(table); } $(document).delegate('.btn-delete', 'click' , function() { var userid=this.getAttribute('data-userid'); swal({ title: "Are you sure?" , text: "Are you sure you want to delete this user?" , type: "warning" , showCancelButton: true, confirmButtonColor: '#DD6B55' , confirmButtonText: 'Yes, delete it!' , closeOnConfirm: false, //closeOnCancel: false }, function() { swal("Deleted!", "User has been deleted!" , "success" ); $.ajax({ type: "GET" , dataType: 'json' , contentType: "application/json" , url: "{{ url('useradmin_delete/" + userid + "'); }}" , success: function(users) { var usersjson=JSON.parse(users); showUsersTable(usersjson); } }); }); }); $('.btn-sms').click(function() { swal({ title: "Are you sure?" , text: "An SMS will be Re-Sent to the user?" , type: "warning" , showCancelButton: true, confirmButtonColor: '#428bca' , confirmButtonText: 'Yes, send it!' , closeOnConfirm: false, //closeOnCancel: false }, function() { swal("SMS Sent!", "SMS has been sent to user!" , "success" ); }); }); </script> -->

</body>

@stop