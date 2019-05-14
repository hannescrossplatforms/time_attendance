@extends('layout')

@section('content')

<body class="hipWifi">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
      <h1 class="page-header">Synchronize Venues With Database</h1>
		<form role="form" id="synchdb-form" method="post">

          <div class="form-group">
            <label>Database</label>
            <select id="databaselist" name="remotedb_id" class="form-control no-radius"">
              @foreach($data['databases'] as $database)
                <option value="{{ $database->id }}">
                  {{ $database->name }}
                </option>
              @endforeach 
            </select>
          </div>

	      <div class="form-group">
	        <label>Database synch direction</label>
	        <br>
			<input type="radio" id="synchto" name="synch" value="from" checked/>From Radius to Hiphub (will overwrite venues table in Hiphub)
	        <br>
	        <input type="radio" id="synchto" name="synch" value="to" />From Hiphub to Radius (will overwrite naslookup table in Radius)
	      </div>

	      <div id="messagesTable"></div>

	      <button class="btn btn-primary">Submit</button>
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
<script type="text/javascript">

	$( "#synchdb-form" ).submit(function( event ) {
  		synchvenues();
  		event.preventDefault();
	});

    function synchvenues() {
		remotedb_id = $("#databaselist").val();
		synch = $("#synchto:checked").val();

		$.ajax({
			type: "GET",
			dataType: 'json',
			contentType: "application/json",
			data: { 
			    'remotedb_id': remotedb_id,
			    'synch': synch 
			},
			url: "{{ url('special_synchvenuesexecute'); }}",
			success: function(messages) {

				messagesTable = ""; rows = "";
	          	beginTable = ' \
	                <tbody> \
	                ';
	          	endTable = '</tbody>';

				$.each(messages, function( i, message ) {
	            	rows = rows + '<tr><td>' + message + '</td></tr>';
	          	});
				
				messagesTable = beginTable + rows + endTable;

	          	$( "#messagesTable" ).html( messagesTable );
			}
		});
    }

</script>
</body>
@stop
