    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>
    
            <br>
            <div class="form-group">
              <div class="input-group">
                <a href="#" id="addsmsnotification" class="btn btn-primary" data-toggle="modal" data-target="#addSmsNotificationModal">
                  <i class="fa fa-plus"></i>Add SMS Notification
                </a>
              </div>
              <br>
              <label>Edit an existing SMS notification</label>
              <select id="smsnotificationlist"  class="form-control no-radius"></select>
            </div>

            <div class="col-md-10">
              <div class="panel panel-default">
                <div class="panel-heading"> Message </div>
                  <div class="panel-body">
                    <div id="smsdisplaymessage"></div>
                </div>
              </div>
            </div>
              
            <div class="input-group editsms">
              <a href="#" id="editsmsnotification" class="btn btn-primary">
                <i class="fa fa-plus"></i>Edit SMS Notification
              </a>
            </div>
                  

            <div class="modal fade" id="addSmsNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel">Add / Edit SMS Notification</h6>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  </div>
                  <div class="pushname">
                    <label>Name</label>
                    <input id="smsname" class="form-control no-radius" placeholder="Choose a name" type="text">
                  </div>


                  <div class="col-md-12">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        Message
                      </div>
                      <div class="panel-body">
                        <input id="smsmessage" class="form-control no-radius" placeholder="Message content to be sent to device" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="savesmsnotification" type="button" class="btn btn-primary">Save</button>
                    <!-- <button id="savesmsnotification" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> -->
                  </div>
                </div>
              </div>
            </div>
















            <!-- Add SMS Notification Modal -->
<!--             <div class="modal fade" id="addSmsNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel">Add / Edit SMS Notification</h6>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  </div>
                  <div class="smsname">
                    <label>Name</label>
                    <input id="smsname" class="form-control no-radius" placeholder="Choose a name" type="text">
                  </div>


                  <div class="panel panel-default">
                    <div class="panel-heading">
                      Message
                    </div>
                    <div class="panel-body">
                      <input id="smsmessage" class="form-control no-radius" placeholder="Text message (max 160 characters)" type="text">
                    </div>
                    <div class="notification-edit-save">
                      <button id="savesmsnotification" type="button" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->


