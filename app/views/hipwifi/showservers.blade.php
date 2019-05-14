@extends('layout')

@section('content')

  <body class="hipWifi">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Server Management</h1>

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <label  class="sr-only">Hostname</label>
                <input type="text" class="form-control" id="src-hostname" placeholder="Hostname">
              </div>
              <div class="form-group">
                <label class="sr-only">Brand</label>
                <input type="text" class="form-control" id="src-brand" placeholder="Brand">
              </div>

              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button>
            </form>

			      <form role="form">
            </form>
            <div class="table-responsive">
                <table id="serverTable" class="table table-striped"> </table>
            </div>
            <a href="{{ url('hipwifi_addserver'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Server</a>
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

    // $(function() {
    //   console.log("begin");
    //     showSelectedServers();
    // });


    serversJason = {{ $data['serversJason'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

      $(document).delegate('#buildtable', 'click', function() {
        showServersTable(serversJason);
      });

      /////////////////////
      $(document).delegate('#reset', 'click', function() {
        showServersTable(serversJason);
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();

        hostname = $( "#src-hostname" ).val();
        brand = $( "#src-brand" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'hostname': hostname, 
              'brand': brand 
            },
            url: "{{ url('lib_filterservers'); }}",
            success: function(filteredServersjson) {
              showServersTable(filteredServersjson);
            }
         });
      });
      ////////////////////////////

      function showServersTable(serversjson) {
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Server Name</th>\n\
                      <th>Brand</th>\n\
                      <th>Country</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(serversjson, function(index, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["serverHostname"]  + '</td>\n\
                      <td> ' + value["brandName"]  + '</td>\n\
                      <td> ' + value["countryName"]  + '</td>\n\
                      <td><a href="{{ url('hipwifi_editserver'); }}/' + value["serverId"] + '" class="btn btn-default btn-sm">edit</a>\n\
                          <a class="btn btn-default btn-delete btn-sm" data-serverid = ' + value["serverId"] + ' href="#">delete</a>\n\
                      </td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#serverTable" ).html( table );
      }

      $(document).delegate('.btn-delete', 'click', function() {
      var serverId = this.getAttribute('data-serverid');
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this server?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
      },
        function(){
          swal("Deleted!", "Server has been deleted!", "success");
          $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('hipwifi_deleteserver/" + serverId + "'); }}",
            success: function(servers) {
              var serversjson = JSON.parse(servers); 
              showServersTable(serversjson);
            }
          });
        });
      });
	



    </script>

  </body>

@stop