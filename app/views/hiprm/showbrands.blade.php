@extends('layout')

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Brand Management</h1>
			<form role="form">
              <div class="form-group">
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="start typing brand name to filter">
              </div>
            </form>
            <div class="table-responsive">
              <table id="brandManagementTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Brand Name</th>
                      <th style="width:200px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data['brands'] as $brand)
                      <tr>
                        <td>{{ $brand->name }}</td>
                        <td><a class="btn btn-default btn-sm" href="{{ url('hiprm_editbrand'); }}/{{ $brand->id }}">edit</a>
                        <a class="btn btn-default btn-delete btn-sm" href="#">delete</a></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
            <a href="{{ url('hiprm_addbrand'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Brand</a>

          
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

    $(function() {
      console.log("begin");
        showSelectedBrands();
    });
	
		$('.btn-delete').click(function(){
			swal({
				title: "Are you sure?",
				text: "Are you sure you want to delete this brand?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, delete it!',
				closeOnConfirm: false,
				//closeOnCancel: false
			},
			function(){
				swal("Deleted!", "Brand has been deleted!", "success");
			});
		});
    	


    </script>

  </body>

@stop