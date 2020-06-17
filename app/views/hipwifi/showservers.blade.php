@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Server Management<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              All Servers

            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
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
                  <button id="reset" type="submit" class="btn btn-warning">Reset</button>
                </form>
              </div>
              <div class="col-6 text-right">
                <a href="{{ url('hipwifi_addserver'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Server</a>
              </div>
              <div class="col-12">
                <table id="serverTable" class="table table-striped"> </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> 
    
    <script>

    // $(function() {
    //   console.log("begin");
    //     showSelectedServers();
    // });


    serversJason = {{ $data['serversJason'] }};

    showServersTable(serversJason);

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
                      <td><a href="{{ url('hipwifi_editserver'); }}/' + value["serverId"] + '" class="btn btn-info btn-sm">edit</a>\n\
                          <a class="btn btn-default btn-danger btn-sm btn-delete" data-serverid = ' + value["serverId"] + ' href="#">delete</a>\n\
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

      addDeleteFunctionality();
      function addDeleteFunctionality(){

        $(document).delegate('.btn-delete', 'click', function() {
          debugger;
          deleteServer();
        });

        
      }

      // $(document).delegate('.btn-delete', 'click', function() {
      // var serverId = this.getAttribute('data-serverid');
      // swal({
      //   title: "Are you sure?",
      //   text: "Are you sure you want to delete this server?",
      //   type: "warning",
      //   showCancelButton: true,
      //   confirmButtonColor: '#DD6B55',
      //   confirmButtonText: 'Yes, delete it!',
      //   closeOnConfirm: false,
      // },
      //   function(){
      //     swal("Deleted!", "Server has been deleted!", "success");
      //     $.ajax({
      //       type: "GET",
      //       dataType: 'json',
      //       contentType: "application/json",
      //       url: "{{ url('hipwifi_deleteserver/" + serverId + "'); }}",
      //       success: function(servers) {
      //         var serversjson = JSON.parse(servers); 
      //         showServersTable(serversjson);
      //       }
      //     });
      //   });
      // });
  
      
      // title: "Are you sure ??",
      //       text: 'asdf', 
      //       icon: "warning",
      //       buttons: true,
      //       dangerMode: true,
      function deleteServer(){
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this server?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, delete it!',
          closeOnConfirm: false
        })
        .then((willDelete) => {
          if (willDelete) {
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
            
          } else {
            swal("The server has not been deleted!");
          }
        });
      }


    </script>



@stop