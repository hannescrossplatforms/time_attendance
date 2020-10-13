@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

      <h1 class="page-header">Media Management</h1>
            

        <div class="form-group">
          <label>Brand Name</label>
           
            <input class="form-control" value="Kauai" disabled="" type="text">
                  </div>

        <div id="login_process_name"><label>Login Process : </label> Full Registration</div>
        
        <br>

        <!-- <div id="loginprocess" style="display:none"></div> -->
        <div id="dt_ext_div" name="dt_ext" style="display:none"><input id="dt_ext" name="dt_ext" value="jpg" type="hidden"></div>
        <div id="mb_ext_div" name="mb_ext" style="display:none"><input id="mb_ext" name="mb_ext" value="jpg" type="hidden"></div>

        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">Desktop Login Page</div>
              <div class="panel-body">
                <div class="form-group">
<!--                   <label>Login Process <a href="#" data-toggle="modal" data-target="#loginProcessModal">( i )</a></label>
                  <br>
                  <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-default">Full Registration</button>
                    <button type="button" class="btn btn-default">Zero Registration</button>
                  </div>
                  <br>
                  <br> -->
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Desktop Background</label>
                        <div id="imagedisplaydt" style="display: block;"><img src="http://localhost/assets/hipwifi/images/preview-dt.jpg?0.004566776727714328" style="margin-bottom: 10px;" class="img-responsive"></div>

                        <div id="previewdt">
                          <a id="dtpreview" class="btn btn-default btn-sm  btn-block" target="blank">
                            Preview Background
                          </a>
                        </div>
                        <div id="dtboxlink"></div>
                        <div style="height: 0px; width: 0px; overflow: hidden;"><input id="dtimage" name="dtimage" form="dtimageform" type="file"></div>
                          <a id="dt-file" href="#" class="btn btn-default btn-sm  btn-block " data-toggle="modal" data-target="#desktopBgModal"> 
                              Upload new image
                          </a>
                        

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">Mobile Login Page</div>
              <div class="panel-body">
                <div class="form-group">
<!--                   <label>Login Process <a href="#" data-toggle="modal" data-target="#loginProcessModal">( i )</a></label>
                  <br>
                  <div class="btn-group" role="group" aria-label="..."> 
                    <a class="btn btn-default" href="#loginFull" aria-controls="loginFull" role="tab" data-toggle="tab">Full Registration</a> 
                    <a class="btn btn-default" href="#loginNone" aria-controls="loginNone" role="tab" data-toggle="tab">Zero Registration</a> 
                  </div>
                </div> -->
                <!-- Tab panes -->
                  <!-- <div role="tabpanel" class="tab-pane active" id="loginLanding"></div> -->
                  <!-- <div role="tabpanel" class="tab-pane active" id="loginFull"> -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Mobile Background</label>
                        <div id="imagedisplaymb" style="display: block;"><img src="http://localhost/assets/hipwifi/images/preview-mb.jpg?0.3618724466449592" style="margin-bottom: 10px;" class="img-responsive"></div>

                        <div id="previewmb">
                          <a id="mbpreview" class="btn btn-default btn-sm  btn-block" target="blank">
                            Preview Background
                          </a>
                        </div>     
                        <div id="mbboxlink"></div>
                    <div style="height: 0px; width: 0px; overflow: hidden;"><input id="mbimage" name="mbimage" form="mbimageform" type="file"></div>
                          <a id="mb-file" href="#" class="btn btn-default btn-sm  btn-block " data-toggle="modal" data-target="#mobileBgModal"> 
                              Upload new image
                          </a>
                        
                      </div>
                    </div>

                    <div class="clear"></div>
                    <br>
                    <a style="display: none;" id="configButton" href="#" class="btn btn-default" data-toggle="modal" data-target="#configMobileModal"><i class="fa fa-gears"></i> Configuration</a>



                  <!-- </div> -->
<!--                   <div role="tabpanel" class="tab-pane" id="loginNone">
                  <a href="#" class="btn btn-default" data-toggle="modal" data-target="#configMobileModal">
                    <i class="fa fa-gears"></i> Configuration</a>
                  </div> -->
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading"> Targeting </div>
              <div class="panel-body">

                 

                <input name="id" value="92" type="hidden">
                <input name="countrie_id" value="1" type="hidden">
                <input name="province_id" value="0" type="hidden">
                <input name="citie_id" value="" type="hidden">
                <input name="brand_id" value="1" type="hidden">
                <input name="location" value="HIPXKAUAIXXXXXXXXXXXXXXXXZA" type="hidden">
                <input id="locationcode" name="location" class="form-control" value="HIPXKAUAIXXXXXXXXXXXXXXXXZA" disabled="" type="text">

                
                  <div id="locationCodeHidden"></div>
<!--                 <div  class="form-group">
                  <label for="exampleInputEmail1">Location Code (To be removed - for testing purposes only.)</label>
                  <div id="locationCodeDisplayed">
                    <input  id="locationcode" name="location" class="form-control" type="text" 
                            value="HIPXKAUAIXXXXXXXXXXXXXXXXZA" disabled >
                  </div>
                </div> -->

              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-primary"> Update </button>
        <a href="http://newhiphub.localhost/hipwifi_showmedias" class="btn btn-default">Cancel</a>
      
      <br>
      <br>
    </div>
  
        </div>

      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/prefixfree.min.js"></script> 
    
    <script>
	
		$('.btn-delete').click(function(){
			swal({
				title: "Are you sure?",
				text: "Are you sure you want to delete this media?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, delete it!',
				closeOnConfirm: false,
				//closeOnCancel: false
			},
			function(){
				swal("Deleted!", "Media has been deleted!", "success");
			});
		});
    	


    </script>

  </body>
@stop
