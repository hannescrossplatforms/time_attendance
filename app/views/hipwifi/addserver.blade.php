@extends('angle_wifi_layout')
<?php $edit = $data["edit"] ?>
@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Add Server<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              Server Details
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                @if ($errors->has())
                <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                  {{ $error }}<br>
                  @endforeach
                </div>
                @endif
              </div>
            </div>
            <form role="form" id="useradmin-form" method="post" action=" @if ($edit) {{ url('hipwifi_editserver'); }} @else {{ url('hipwifi_addserver'); }} @endif ">
              <div class="row">
                <div class="col-12">
                  {{ Form::hidden('id', $data['server']->id) }}
                  <div class="form-group">
                    <label for="exampleInputEmail1">Server Hostname</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="hostname" placeholder="" value="@if(Input::old('hostname')){{Input::old('hostname')}}@else{{$data['server']->hostname;}}@endif">
                  </div>
                  @if ($edit)
                  <div class="form-group">
                    <label>Database</label>
                    <input type="text" class="form-control" name="code" value="{{$data['database']->name}}" disabled>
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


                  <div class="form-group">
                    <label for="exampleInputEmail1">Notes</label>
                    <input type="text" class="form-control" rows="2" id="exampleInputEmail1" name="notes" placeholder="" value="{{ $data['server']->notes }}">
                  </div>

                  <br>
                  <button class="btn btn-primary">Submit</button>
                  <a href="{{ url('hipwifi_showservers'); }}" class="btn btn-default">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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


@stop