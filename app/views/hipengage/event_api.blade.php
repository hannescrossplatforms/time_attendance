    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>
    
    <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_saveapimedia'); }}"></form>
                  <br>
                  <div class="form-group">

                    <div class="input-group">
                      <a href="#" id="addapinotification" class="btn btn-primary" data-toggle="modal" data-target="#addApiNotificationModal">
                        <i class="fa fa-plus"></i>Add API Notification
                      </a>
                    </div>
                    <br>
                    <label>Edit an existing api notification</label>
                    <select id="apinotificationlist"  class="form-control no-radius"></select>
                  </div>

                  <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        Api Notification Settings
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <label>Auth Token </label> 
                          <div id="apidisplayauth"> </div>
                        </div>
                        <div class="form-group">
                          <label>Endpoint </label> 
                          <div id="apidisplayurl"> </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="input-group editapi">
                    <a href="#" id="editapinotification" class="btn btn-primary">
                      <i class="fa fa-plus"></i>Edit Api Notification
                    </a>
                  </div>
                  



                <!-- Add Api Notification Modal -->
            <div class="modal fade" id="addApiNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">

                  <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel">Add / Edit Api Notification</h6>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  </div>

                  <div class="apiname">
                    <label>Name</label>
                    <input id="apiname" class="form-control no-radius" placeholder="Choose a name" type="text">
                  </div>

                  <div class="col-md-12">
                    <div class="panel panel-default">

                      <div class="panel-heading">
                        Api Notification Settings
                      </div>

                      <div class="panel-body">

                        <div class="form-group">
                          <label>Auth Token</label><br>
                          <input id="apiauth" class="form-control no-radius" placeholder="" type="text">
                        </div>
                        
                        <div class="form-group">
                          <label>Endpoint</label><br>
                          <input id="apiurl" class="form-control no-radius" placeholder="" type="text">
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button id="saveapinotification" type="button" class="btn btn-primary">Save</button>
                  </div>

                </div>
              </div>
            </div>
