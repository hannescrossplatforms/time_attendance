@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Add Brand</h1>
			
            <form role="form" method="post" 
                  action=" @if ($edit) {{ url('hiprm_editbrand'); }} @else {{ url('hiprm_addbrand'); }} @endif ">
              @if ($edit) {{ Form::hidden('id', $data['brand']->id) }} @endif
            	<div class="form-group">
                <label for="exampleInputEmail1">Brand Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" 
                        name="name" value="@if ($edit) {{ $data['brand']->name; }} @endif">
              </div>
              <div class="form-group">
                    	<label>Country</label>
                    	<select class="form-control">
                          <option>South Africa</option>
                        </select>
                    </div>
              <div class="form-group">
                <label for="exampleInputFile">Desktop Background</label>
                <input type="file" id="exampleInputFile">
                <p class="help-block">Example block-level help text here.</p>
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Mobile Background</label>
                <input type="file" id="exampleInputFile">
                <p class="help-block">Example block-level help text here.</p>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Welcome Message</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Welcome Message">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">User Redirection URL</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="User Redirection URL">
              </div>
              <button class="btn btn-primary">Submit</button>
              <a href="hipRM_brandManagement.html" class="btn btn-default">Cancel</a>
            </form>
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
