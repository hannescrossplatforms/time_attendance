@extends('layout')

@section('content')

<body class="HipADMIN">
<div class="container-fluid">

  <div class="row">
    @include('admin.sidebar')

    <!-- <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
      <h1 class="page-header">Roles and Permissions</h1>
      <div class="row"> </div>
      <br>
    </div> -->

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

          <h1 class="page-header">Roles and Permissions</h1>
          <div class="reports-subheader">
          </div>

          <div role="tabpanel"> 
            
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a id="dashtab" href="#permissionstab" aria-controls="permissions" role="tab" data-toggle="tab">Permissions</a></li>
              <li role="presentation"><a id="brandtab" href="#rolestab" aria-controls="roles" role="tab" data-toggle="tab">Roles</a></li>
              <!-- <li role="presentation"><a id="venuetab" href="#showvenues" aria-controls="showvenues" role="tab" data-toggle="tab">Venue Level</a></li>
              <li role="presentation"><a id="statstab" href="#statistics" aria-controls="statistics" role="tab" data-toggle="tab">Statistics</a></li> -->
            </ul>
            <br>
            
            <!-- Tab panes -->
            <div class="tab-content">

              <!-- permissionstab -->
              <div role="tabpanel" class="tab-pane active" id="permissionstab"> 
                @include('admin.permissionstab')
              </div>

              <!-- rolestab -->
              <div role="tabpanel" class="tab-pane" id="rolestab">
                @include('admin.rolestab')
              </div>


            </div>
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

</script>
</body>
@stop
