@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

      <h1 class="page-header">Edit Display Group</h1>
      <form role="form">
      <div class="form-group">
        <label for="exampleInputEmail1">Display Group Name</label>
        <input class="form-control" type="text">
      </div>
      <div class="form-group">
        <div class="checkbox  ">
          <label>
            <input value="" type="checkbox">
            Active </label>
        </div>
        <div class="checkbox">
          <label>
            <input value="" type="checkbox">
            Desktop </label>
        </div>
        <div class="checkbox">
          <label>
            <input value="" type="checkbox">
            Mobile </label>
        </div>
      </div>
      <div class="form-group">
        <label>Sample Size</label>
        <input class="form-control" type="text">
      </div>
      <div class="panel panel-default">
          <div class="panel-heading">Targeting</div>
          <div class="panel-body">
          <div class="form-group">
        <div class="input-group">
          <label>Brand</label>
          <input class="form-control" placeholder="start typing to search brand" type="text">
          <span class="input-group-btn">
          <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
          </span> </div>
        <!-- /input-group --> 
      </div>
            <div class="form-group">
        <div class="input-group">
          <label>Country</label>
          <input class="form-control" placeholder="start typing to search country" type="text">
          <span class="input-group-btn">
          <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
          </span> </div>
        <!-- /input-group --> 
      </div>
      <div class="form-group">
        <div class="input-group">
          <label for="exampleInputEmail1">Province/State</label>
          <input class="form-control" placeholder="start typing to search province" type="text">
          <span class="input-group-btn">
          <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
          </span> </div>
        <!-- /input-group --> 
      </div>
      <div class="form-group">
        <div class="input-group">
          <label for="exampleInputEmail1">City</label>
          <input class="form-control" placeholder="start typing to search province" type="text">
          <span class="input-group-btn">
          <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
          </span> </div>
        <!-- /input-group --> 
      </div>
      <div class="form-group">
        <div class="input-group">
          <label for="exampleInputEmail1">Venues</label>
          <input class="form-control" placeholder="start typing to search province" type="text">
          <span class="input-group-btn">
          <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
          </span> </div>
        <!-- /input-group --> 
      </div>
          </div>
      </div>
      
      <div class="panel panel-default">
          <div class="panel-heading">Insight Setup</div>
          <div class="panel-body">
            <div class="form-group">
        <div class="radio">
          <label>
            <input name="optionsRadios" id="optionsRadios1" value="option1" checked="" type="radio">
            Question Pair </label>
        </div>
      </div>
      <div class="form-group">
        <div class="radio">
          <label>
            <input name="optionsRadios" id="optionsRadios1" value="option1" checked="" type="radio">
            Single Question </label>
        </div>
      </div>
      <div class="form-group">
        <div class="radio">
          <label>
            <input name="optionsRadios" id="optionsRadios1" value="option1" checked="" type="radio">
            No Questions </label>
        </div>
      </div>
      <div class="form-group">
        <div class="checkbox">
          <label>
            <input value="" type="checkbox">
            Advert </label>
        </div>
      </div>
      
          </div>
        </div>
        
        <div class="panel panel-default">
  <div class="panel-heading">Content</div>
  <div class="panel-body">
    <div role="tabpanel">
      
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Question 1</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Question 2</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Advert</a></li>
      </ul>
      
      <!-- Tab panes -->
      <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="home">
      <br>
      
        <div class="form-group">
          <label>Question Type</label>
          <div class="clearfix"></div>
          <div class="btn-group" role="group"> 
            <a href="#slider" class="btn btn-default" data-toggle="tab">Slider</a> 
            <a href="#radio" class="btn btn-default" data-toggle="tab">Radio</a> 
            <a href="#checkbox" class="btn btn-default" data-toggle="tab">Checkbox</a> 
            <a href="#image" class="btn btn-default" data-toggle="tab">Image</a> 
          </div>
        </div>
        <!-- Single button -->
        
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="slider">
            <div class="form-group">
          <label>Question Text <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label>Extremeties</label>
          <br>
          <div class="btn-group" role="group" aria-label="...">
  <a class="btn btn-default" href="#exText" aria-controls="profile" role="tab" data-toggle="tab">Text</a>
  <a class="btn btn-default" href="#exImage" aria-controls="profile" role="tab" data-toggle="tab">Image</a>
</div>

        </div>
        <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="exLanding"></div>
    <div role="tabpanel" class="tab-pane" id="exText">
      <div class="form-group">
          <label>Left Text</label>
            <input class="form-control" placeholder="" type="text">
        </div>
        <div class="form-group">
          <label>Right Text</label>
            <input class="form-control" placeholder="" type="text">
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="exImage">
      <div class="form-group">
          <div class="radio">
            <label>
              <input name="optionsRadios2" id="optionsRadios3" value="option3" checked="" type="radio">
              <i class="fa fa-smile-o"></i> <i class="fa fa-frown-o"></i></label>
          </div>
          <div class="radio">
            <label>
              <input name="optionsRadios2" id="optionsRadios4" value="option4" type="radio">
              <i class="fa fa-thumbs-up"></i> <i class="fa fa-thumbs-down"></i> </label>
          </div>
        </div>
    </div>
  </div>
  <br>
        
        <div class="form-group">
          <label>Stops</label>
          <div class="radio">
            <label>
              <input name="optionsRadios2" id="optionsRadios3" value="option3" checked="" type="radio">
              Text </label>
          </div>
          <div class="radio">
            <label>
              <input name="optionsRadios2" id="optionsRadios4" value="option4" type="radio">
              Image </label>
          </div>
        </div>
        <div class="form-group">
          <label>Intervals</label>
          <div class="radio">
            <label>
              <input name="optionsRadios3" id="optionsRadios5" value="option5" checked="" type="radio">
              Continuous (1 - 100) </label>
          </div>
          <div class="radio">
            <label>
              <input name="optionsRadios3" id="optionsRadios6" value="option6" type="radio">
              3 Stops (0, 50, 100) </label>
          </div>
          <div class="radio">
            <label>
              <input name="optionsRadios3" id="optionsRadios7" value="option7" type="radio">
              5 Stops (0, 25, 50, 75, 100) </label>
          </div>
        </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="radio"> 
            <div class="form-group">
          <label>Question Text <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 1 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 2 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <a href="#" class="btn btn-default"><i class="fa fa-icon fa-plus"></i> Add Answer</a>
          </div>
          <div role="tabpanel" class="tab-pane" id="checkbox">

            <div class="form-group">
          <label>Question Text <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 1 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 2 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <a href="#" class="btn btn-default"><i class="fa fa-icon fa-plus"></i> Add Answer</a>
          </div>
          <div role="tabpanel" class="tab-pane" id="image">
            <div class="form-group">
          <label>Caption <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="2"></textarea>
        </div>
        <img src="img/wallpaper_mobile_full.jpg" style="margin-bottom: 10px;" width="200px;">
        <div class="clearfix"></div>
        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#imageModal">Upload new image</a>
          </div>
        </div>
        
      
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
    <br>
      <form role="form">
        <div class="form-group">
          <label>Question Type</label>
          <div class="clearfix"></div>
          <div class="btn-group" role="group"> 
            <a href="#slider2" class="btn btn-default" data-toggle="tab">Slider</a> 
            <a href="#radio2" class="btn btn-default" data-toggle="tab">Radio</a> 
            <a href="#checkbox2" class="btn btn-default" data-toggle="tab">Checkbox</a> 
          </div>
        </div>
        <!-- Single button -->
        
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="slider2">
            <div class="form-group">
          <label>Question Text <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label>Extremeties</label>
          <div class="radio">
            <label>
              <input name="optionsRadios1" id="optionsRadios1" value="option1" checked="" type="radio">
              Text </label>
          </div>
          <div class="radio">
            <label>
              <input name="optionsRadios1" id="optionsRadios2" value="option2" type="radio">
              Image </label>
          </div>
        </div>
        <div class="form-group">
          <label>Stops</label>
          <div class="radio">
            <label>
              <input name="optionsRadios2" id="optionsRadios3" value="option3" checked="" type="radio">
              Text </label>
          </div>
          <div class="radio">
            <label>
              <input name="optionsRadios2" id="optionsRadios4" value="option4" type="radio">
              Image </label>
          </div>
        </div>
        <div class="form-group">
          <label>Left Text</label>
            <input class="form-control" placeholder="" type="text">
        </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="radio2"> 

            <div class="form-group">
          <label>Question Text <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 1 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 2 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <a href="#" class="btn btn-default"><i class="fa fa-icon fa-plus"></i> Add Answer</a>
          </div>
          <div role="tabpanel" class="tab-pane" id="checkbox2">

            <div class="form-group">
          <label>Question Text <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 1 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <div class="form-group">
          <label>Answer 2 <span class="text-info">(55 chars left)</span></label>
          <textarea class="form-control" rows="1"></textarea>
        </div>
        <a href="#" class="btn btn-default"><i class="fa fa-icon fa-plus"></i> Add Answer</a>
          </div>
          <div role="tabpanel" class="tab-pane" id="image">
            <div class="form-group">
          <label>Caption <span class="text-info">(160 chars left)</span></label>
          <textarea class="form-control" rows="2"></textarea>
        </div>
        <img src="img/wallpaper_mobile_full.jpg" style="margin-bottom: 10px;" width="200px;">
        <div class="clearfix"></div>
        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#imageModal">Upload new image</a>
          </div>
        </div>
        
      </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="messages">
    <br>
      <div class="row">
                <div class="col-md-5">
                  <div class="panel panel-default">
                  <div class="panel-heading">
                    Desktop Background
                  </div>
                  <div class="panel-body">
                    <a href="#" class="btn btn-default btn-sm  btn-block" data-toggle="modal" data-target="#desktopBgModal">Upload new image</a>
                  </div>
                </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                  <div class="panel-heading">
                    Mobile Background
                  </div>
                  <div class="panel-body">
                    <a href="#" class="btn btn-default btn-sm  btn-block" data-toggle="modal" data-target="#mobileBgModal">Upload new image</a>
                  </div>
                </div>
                </div>
              </div>
    </div>
  </div>
</div>
  </div>
</div></form>
      
      
      
<br>

<a href="#" class="btn btn-success" data-toggle="modal" data-target="#previewModal">Preview Desktop</a> <a href="#" class="btn btn-success" data-toggle="modal" data-target="#previewModal2">Preview Mobile</a> <br> <br><a href="hipRM_questionManagement.html" class="btn btn-primary">Update</a> <a href="hipRM_questionManagement.html" class="btn btn-default">Cancel</a>


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
