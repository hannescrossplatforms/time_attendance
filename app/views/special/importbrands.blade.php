@extends('layout')

@section('content')

<body class="hipWifi">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
      <h1 class="page-header">Import Brands From Database</h1>
		<form action="special_importbrandsexecute" role="form" id="synchdb-form" method="post">

          <div class="form-group">
            <label>Select database to import from</label>
            <select id="databaselist" name="remotedb_id" class="form-control no-radius"">
              @foreach($data['databases'] as $database)
                <option value="{{ $database->id }}">
                  {{ $database->name }}
                </option>
              @endforeach 
            </select>
          </div>

        
	      <button class="btn btn-primary">Submit</button>
  		</form>

    </div>
  </div>
</div>