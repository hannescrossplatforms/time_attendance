    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>
    
    <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savepushmedia'); }}"></form>
                  <br>
                  <div class="form-group">

                    <div class="input-group">
                      <a href="#" id="addpushnotification" class="btn btn-primary" data-toggle="modal" data-target="#addNotificationModal">
                        <i class="fa fa-plus"></i>Add Push Notification
                      </a>
                    </div>
                    <br>
                    <label>Edit an existing push notification</label>
                    <select id="pushnotificationlist"  class="form-control no-radius"></select>
                  </div>

                  <div class="col-md-4">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        image
                      </div>
                      <div id="pushimagedisplay" style="display:none"></div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        Push Notification Settings
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <label>Notification Type: </label> 
                          <div id="pushdisplaynotificationtype"> </div>
                        </div>
                        <div class="form-group">
                          <label>Preload Notification: </label> 
                          <div id="pushdisplaypreload"> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading"> Message </div>
                        <div class="panel-body">
                          <div id="pushdisplaymessage"></div>
                      </div>
                    </div>
                  </div>
                  <div class="input-group editpush">
                    <!-- <a href="#" id="editpushnotification" class="btn btn-primary" data-toggle="modal" data-target="#addNotificationModal"> -->
                    <a href="#" id="editpushnotification" class="btn btn-primary">
                      <i class="fa fa-plus"></i>Edit Push Notification
                    </a>
                  </div>
                  



                <!-- Add Push Notification Modal -->
            <div class="modal fade" id="addNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel">Add / Edit Push Notification</h6>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  </div>
                  <div class="pushname">
                    <label>Name</label>
                    <input id="pushname" class="form-control no-radius" placeholder="Choose a name" type="text">
                  </div>

                  <div class="col-md-4">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                      Image
                    </div>
                    <div class="panel-body">
<!--                       <input type="hidden" id="pushimageurl" value="assets.hipzone.com/test.jpg">
                      <img style="margin-bottom: 10px;" src="img/wallpaper_mobile_full.jpg" class="img-responsive">
                      <a href="#" class="btn btn-default btn-sm  btn-block" data-toggle="modal" data-target="#previewMobileModal">Preview Header</a> 
                      <a href="#" class="btn btn-default btn-sm  btn-block" data-toggle="modal" data-target="#mobileBgModal">Upload new image</a>
 -->


                      <div class="col-md-4 pushpic">
                        <div class="form-group">
                          <div id="pushimageedit" style="display:none"></div>
                          <input id="mbimage" type="file" name="mbimage" form="mbimageform">
                            <a  id="mb-file" href="#" class="btn btn-default btn-sm " data-toggle="modal" data-target="#mobileBgModal"  >
                              Upload new image
                            </a>
                          </input>
                        </div>
                      </div>





                    </div>
                  </div>
                  </div>
                  <div class="col-md-8">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                      Push Notification Settings
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <label>Notification Type</label>
                          <br>
                          <label class="checkbox-inline">
                      <input id="pushtypesound" type="checkbox">
                      Sound </label>
                      <label class="checkbox-inline">
                      <input id="pushtypevibrate" type="checkbox">
                      Vibrate </label>
                      </div>
                      <div class="form-group">
                        <label>Preload Notification</label>
                          <select id="pushpreload" class="form-control no-radius">
                            <option value="1">true</option>
                            <option value="0">false</option>
                          </select>
                      </div>
                    </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        Message
                      </div>
                      <div class="panel-body">
                        <input id="pushmessage" class="form-control no-radius" placeholder="Message content to be sent to device" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="savepushnotification" type="button" class="btn btn-primary">Save</button>
                    <!-- <button id="savepushnotification" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> -->
                  </div>
                </div>
              </div>
            </div>
