@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipWifi">

              <form role="form" id="useradmin-form" method="post" 
                    action=" @if ($edit) {{ url('hipwifi_editserver'); }} @else {{ url('hipwifi_addserver'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            <h1 class="page-header">Add Server</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif
          <div class="row">
              <div class="col-md-12">

<!-- form was here -->

                  {{ Form::hidden('id', $data['server']->id) }}
                  <div class="form-group">
                    <label for="exampleInputEmail1">Server Hostname</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" 
                        name="hostname" placeholder="" 
                        value="@if(Input::old('hostname')){{Input::old('hostname')}}@else{{$data['server']->hostname;}}@endif">
                  </div>
                  @if ($edit)
                  <div class="form-group">
                    <label>Database</label>
                    <input type="text" class="form-control" name="code" 
                           value="{{$data['database']->name}}" disabled>
                  </div>
                  @else
                  <div class="form-group">
                    <label>Database</label>
                    <select id="databaselist" name="remotedb_id" class="form-control no-radius">
                      @foreach($data['databases'] as $database)
                        <option value="{{ $database->id }}">
                          {{ $database->name }}
                        </option>
                      @endforeach 
                    </select>
                  </div>
                  @endif

                  <label for="exampleInputEmail1">Brands</label>
                  <div class="table-responsive">
                    <table id="brandManagementTable" class="table table-striped"></table>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Notes</label>
                    <input type="text" class="form-control" rows="2" id="exampleInputEmail1" 
                        name="notes" placeholder="" value="{{ $data['server']->notes }}">
                  </div>

                  <br> 
                  <button class="btn btn-primary">Submit</button>
                  <a href="{{ url('hipwifi_showservers'); }}" class="btn btn-default">Cancel</a>
                    <!-- form ended here -->
                </div>
            </div>

        </div>
      </div>
    </div>
    
  <!-- Page Modals
    ================================================== -->
    
    
     
    </form>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>
    
   <script>
  
    $(function() {
      @if($edit)
        showBrandsForDatabase({{ $data['database']->id }});
      @else
        $('#databaselist').change();
      @endif
    });

    $(document).delegate('#databaselist', 'change', function() {
        remotedb_id=$( "#databaselist" ).val();
        showBrandsForDatabase(remotedb_id);
    });

    function showBrandsForDatabase(remotedb_id) {

      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        data: { 
            'remotedb_id': remotedb_id 
        },
        url: "{{ url('lib_getbrandsfordatabase'); }}",
        success: function(brands) {
          brandManagementTable = ""; rows = "";
          beginTable = ' \
                <tbody> \
                ';
          endTable = '</tbody>';

          $.each(brands, function( i, brand ) {
            rows = rows + '<tr><td>' + brand["name"] + '</td></tr>';
          });

          brandManagementTable = beginTable + rows + endTable;

          $( "#brandManagementTable" ).html( brandManagementTable );

        }
      });
    }

    $("#useradmin-form").submit(function(){
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
      
    $('.btn-sms').click(function(){
      swal({
        title: "Are you sure?",
        text: "An SMS will be Re-Sent to the user?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#428bca',
        confirmButtonText: 'Yes, send it!',
        closeOnConfirm: false,
        //closeOnCancel: false
      },
      function(){
        swal("SMS Sent!", "SMS has been sent to user!", "success");
      });
    });
      


    </script>

  </body>
@stop
