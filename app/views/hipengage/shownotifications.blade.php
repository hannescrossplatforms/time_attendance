  @extends('layout')

  @section('content')

    <body class="hipENGAGE">

      <div class="container-fluid">
        <div class="row">

          @include('hipengage.sidebar')

          <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">Content Manager</h1>
            <form role="form">
              <div class="form-group">
                <input class="form-control" id="exampleInputEmail1" placeholder="start typing notification name to filter" type="text">
              </div>
            </form>
            <br>

            <a id="buildtable"></a>
            <div class="row">
                <div class="col-md-12">

                <form class="form-inline">
                  <div class="form-group">
                    <span class="selectnotification">Add a new content </span>
                    
                    <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savepushmedia'); }}"></form>

                    <select id="notificationtypelist" class="form-control no-radius" >
                        <option>Select type</option>
                        <option value="sms">SMS</option>
                        <option value="email">Email</option>
                        <option value="push">Push</option>
                        <option value="mgr">Mgr</option>
                        <option value="api">Outbound API</option>
                    </select>
                    <a id="addnotification" class="btn btn-primary"><i class="fa fa-plus"></i> </a>

                  </div>
                </form>
                  <div class="table-responsive table-campaignManagement">
                    <div class="table-responsive">
                      <table id="pushnotificationTable" class="table table-striped"> </table>
                    </div>
                  </div>
                <div class="table-responsive table-campaignManagement">
                  <div class="table-responsive">
                    <table id="smsnotificationTable" class="table table-striped"> </table>
                  </div>
                </div>
                <div class="table-responsive table-campaignManagement">
                  <div class="table-responsive">
                    <table id="emailnotificationTable" class="table table-striped"> </table>
                  </div>
                </div>
                <div class="table-responsive table-campaignManagement">
                  <div class="table-responsive">
                    <table id="apinotificationTable" class="table table-striped"> </table>
                  </div>
                </div>
                <div class="table-responsive table-campaignManagement">
                  <div class="table-responsive">
                    <table id="mgrnotificationTable" class="table table-striped"> </table>
                  </div>
                </div>
              </div>
            </div>        
          </div>
        </div>
      </div>

          <!-- Add Push Notification Modal -->
      <div class="modal fade" id="addNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="myModalLabel">Add / Edit Push Notification</h6>
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
          </div>
        </div>
      </div>


      <!-- Placed at the end of the document so the pages load faster -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/prefixfree.min.js"></script> 

      <script>



        $( "#addnotification" ).click(function(event) {

          nt = $('#notificationtypelist').val();
          url = "{{ url('hipengage_selectaddnotification'); }}";
          window.location.href = url + '/' + nt;
        });

        pushnotificationsJason = {{ $data['notifications']['pusnotificationsJson'] }};
        smsnotificationsJason = {{ $data['notifications']['smsnotificationsJson'] }};
        emailnotificationsJason = {{ $data['notifications']['emailnotificationsJson'] }};
        apinotificationsJason = {{ $data['notifications']['apinotificationsJson'] }};
        mgrnotificationsJason = {{ $data['notifications']['mgrnotificationsJson'] }};

        $(function() {
          $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        });

        $(document).delegate('#buildtable', 'click', function() {
          showPushNotificationsTable(pushnotificationsJason);
          showSmsNotificationsTable(smsnotificationsJason);
          showEmailNotificationsTable(emailnotificationsJason);
          showApiNotificationsTable(apinotificationsJason);
          showMgrNotificationsTable(mgrnotificationsJason);
        });

        function showPushNotificationsTable(pushnotificationsJason) {
          table = '';
          rows = '';
          beginTable = '\
                  <table class="table table-striped">\n\
                    <thead>\n\
                      <tr>\n\
                        <th width="40%">Push Notifications</th>\n\
                        <th width="40%">Brand</th>\n\
                        <th></th>\n\
                      </tr>\n\
                    </thead>\n\
                    <tbody>  \n';
          $.each(pushnotificationsJason, function(index, value) {
            id="editpushnotification"
              rows = rows + '\
                      <tr>\n\
                        <td> ' + value["name"]  + '</td>\n\
                        <td> ' + value["brandname"]  + '</td>\n\
                        <td><a href="{{ url('hipengage_editpush'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                            <a class="btn btn-default btn-delete btn-sm" btn-sm" data-notificationtype="push" data-notificationid = ' + value["id"] + ' href="#">delete</a>\n\
                        </td>\n\
                      </tr>\n\
                      ';
          });

          endTable = '\
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#pushnotificationTable" ).html( table );
        }


        function showSmsNotificationsTable(smsnotificationsJason) {
          table = '';
          rows = '';
          beginTable = '\
                  <table class="table table-striped">\n\
                    <thead>\n\
                      <tr>\n\
                        <th width="40%">Sms</th>\n\
                        <th width="40%">Brand</th>\n\
                        <th></th>\n\
                      </tr>\n\
                    </thead>\n\
                    <tbody>  \n';
          $.each(smsnotificationsJason, function(index, value) {
              rows = rows + '\
                      <tr>\n\
                        <td> ' + value["name"]  + '</td>\n\
                        <td> ' + value["brandname"]  + '</td>\n\
                        <td><a href="{{ url('hipengage_editsms'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                            <a class="btn btn-default btn-delete btn-sm" btn-sm" data-notificationtype="sms" data-notificationid = ' + value["id"] + ' href="#">delete</a>\n\
                        </td>\n\
                      </tr>\n\
                      ';
          });

          endTable = '\
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#smsnotificationTable" ).html( table );
        }

        function showEmailNotificationsTable(emailnotificationsJason) {
          table = '';
          rows = '';
          beginTable = '\
                  <table class="table table-striped">\n\
                    <thead>\n\
                      <tr>\n\
                        <th width="40%">Email</th>\n\
                        <th width="40%">Brand</th>\n\
                        <th></th>\n\
                      </tr>\n\
                    </thead>\n\
                    <tbody>  \n';
          $.each(emailnotificationsJason, function(index, value) {
              rows = rows + '\
                      <tr>\n\
                        <td> ' + value["name"]  + '</td>\n\
                        <td> ' + value["brandname"]  + '</td>\n\
                        <td><a href="{{ url('hipengage_editemail'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                            <a class="btn btn-default btn-delete btn-sm" btn-sm" data-notificationtype="email" data-notificationid = ' + value["id"] + ' href="#">delete</a>\n\
                        </td>\n\
                      </tr>\n\
                      ';
          });

          endTable = '\
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#emailnotificationTable" ).html( table );
        }

        function showApiNotificationsTable(apinotificationsJason) {
          table = '';
          rows = '';
          beginTable = '\
                  <table class="table table-striped">\n\
                    <thead>\n\
                      <tr>\n\
                        <th width="40%">Api</th>\n\
                        <th width="40%">Brand</th>\n\
                        <th></th>\n\
                      </tr>\n\
                    </thead>\n\
                    <tbody>  \n';
          $.each(apinotificationsJason, function(index, value) {
              rows = rows + '\
                      <tr>\n\
                        <td> ' + value["name"]  + '</td>\n\
                        <td> ' + value["brandname"]  + '</td>\n\
                        <td><a href="{{ url('hipengage_editapi'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                            <a class="btn btn-default btn-delete btn-sm" data-notificationtype="api" data-notificationid = ' + value["id"] + ' href="#">delete</a>\n\
                        </td>\n\
                      </tr>\n\
                      ';
          });

          endTable = ' \
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#apinotificationTable" ).html( table );
        }

        function showMgrNotificationsTable(apinotificationsJason) {
          table = '';
          rows = '';
          beginTable = '\
                  <table class="table table-striped">\n\
                    <thead>\n\
                      <tr>\n\
                        <th width="40%">Mgr</th>\n\
                        <th width="40%">Brand</th>\n\
                        <th></th>\n\
                      </tr>\n\
                    </thead>\n\
                    <tbody>  \n';
          $.each(mgrnotificationsJason, function(index, value) {
              rows = rows + '\
                      <tr>\n\
                        <td> ' + value["name"]  + '</td>\n\
                        <td> ' + value["brandname"]  + '</td>\n\
                        <td><a href="{{ url('hipengage_editmgr'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                            <a class="btn btn-default btn-delete btn-sm" data-notificationtype="mgr" data-notificationid = ' + value["id"] + ' href="#">delete</a>\n\
                        </td>\n\
                      </tr>\n\
                      ';
          });

          endTable = ' \
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#mgrnotificationTable" ).html( table );
        }


          $(document).delegate('.btn-delete', 'click', function() {
          var notificationid = this.getAttribute('data-notificationid');
          var notificationtype = this.getAttribute('data-notificationtype');
          swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this content?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            //closeOnCancel: false
          },
            function(){
              swal("Deleted!", "Notification has been deleted!", "success");
              if(notificationtype == "push") {
                url = "{{ url('hipengage_deletepush/" + notificationid + "'); }}"
              } else if(notificationtype == "sms") {
                url = "{{ url('hipengage_deletesms/" + notificationid + "'); }}"
              } else if(notificationtype == "email") {
                url = "{{ url('hipengage_deleteemail/" + notificationid + "'); }}"
              } else if(notificationtype == "api") {
                url = "{{ url('hipengage_deleteapi/" + notificationid + "'); }}"
              } else if(notificationtype == "mgr") {
                url = "{{ url('hipengage_deletemgr/" + notificationid + "'); }}"
              }
              console.log('Deleting notificationid = ' + notificationid);

              $.ajax({
                type: "GET",
                dataType: 'json',
                contentType: "application/json",
                url: url,
                success: function(notifications) {
                  location.reload();
                }
              });
            });
        });
    
      </script>

    </body>
  @stop
