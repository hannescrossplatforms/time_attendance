@extends('layout')

@section('content')

<body class="hipWifi">
<div class="container-fluid">
  <div class="row">
    @include('hipwifi.sidebar')

    <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
      <h1 class="page-header">Dashboard</h1>
      <div class="row">
        <div class="col-md-4">
          <h3 class="mod-title"><strong>Users</strong></h3>
          <div class="modStat">
            <h3>Registered</h3>
            <span id='wifi_total_users'></span>
            <h3 style="margin-top: 20px">Online</h3> 
            <span id='wifi_online_users'></span>
          </div>
        </div>
        <div class="col-md-8">
          <h3 class="mod-title"><strong>Venues</strong></h3>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Active</th>
                  <th class="success">Online</th>
                  <th class="danger">Offline</th>
                </tr>
              </thead>
              <tbody>
                @foreach($brands as $brand)
                  <tr>
                    <td>{{ $brand }}</td> 
                    <td>{{ $count[array_search($brand, $brands)] }} </td> 
                    <td class="success">{{ $onlinevenues[array_search($brand, $brands)] }}</td>
                    <td class="danger">{{ $offlinevenues[array_search($brand, $brands)] }}</td>
                  </tr>
                @endforeach
                </tbody>
            </table>
             </div>
          </div>
        </div>
      
      
          
          
      <br>
      <div class="row">
        <div class="col-md-12">
          <h3 class="mod-title"><strong>Statistics: </strong>This Month</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2 col-md-offset-1">
          <div class="dashboardstat">
            <div class="modStat">
              <div class="dashboardmodstattitle">
                <h6>Users</h6>
              </div>
            </div>
            <div class="dashboardtextalign">
              <div id="usersthismonth" class="dashboardmodStatspan"></div>
              <div id="userslastmonth"></div> 
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="dashboardstat">
            <div class="modStat">
              <div class="dashboardmodstattitle">
                <h6>New Users</h6>
              </div>
            </div>
            <div class="dashboardtextalign">
              <div id="newusersthismonth" class="dashboardmodStatspan"></div>
              <div id="newuserslastmonth"></div> 
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="dashboardstat">
            <div class="modStat">
              <div class="dashboardmodstattitle">
                <h6>Sessions</h6>
              </div>
            </div>
            <div class="dashboardtextalign">
              <div id="sessionsthismonth" class="dashboardmodStatspan"></div>
              <div id="sessionslastmonth"></div> 
            </div>
          </div>
        </div>


        <div class="col-md-2">
          <div class="dashboardstat">
            <div class="modStat">
              <div class="dashboardmodstattitle">
                <h6>Avg Dwell (min)</h6>
              </div>
            </div>
            <div class="dashboardtextalign">
              <div id="dwelltimethismonth" class="dashboardmodStatspan"></div>
              <div id="dwelltimelastmonth"></div> 
            </div>
          </div>
        </div>

     

   
        <div class="col-md-2">
          <div class="dashboardstat">
            <div class="modStat">
              <div class="dashboardmodstattitle">
                <h6>Data used (Gb)</h6>
              </div>
            </div>
            <div class="dashboardtextalign">
              <div id="datausedthismonth" class="dashboardmodStatspan"></div>
              <div id="datausedlastmonth"></div> 
            </div>
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

<script type="text/javascript">
  
$(document).ready(function(){

  get_dashboard_details1();
  get_dashboard_details2();
  
})

function get_dashboard_details1() {
  $.ajax({

    url:'{{ url('dashboard_details1') }}',
    type:'get',
    dataType:'json',

    success:function(data){
    $('#wifi_total_users').html(data['users']);
    $('#wifi_online_users').html(data['onlineusers']);
    
    $('#usersthismonth').html( data['usersthismonth'] );
    $('#userslastmonth').html('Last month: ' + data['userslastmonth'] );

    $('#newusersthismonth').html( data['newusersthismonth'] );
    $('#newuserslastmonth').html('Last month: ' + data['newuserslastmonth'] );
}

})
}





function get_dashboard_details2() {
  $.ajax({

    url:'{{ url('dashboard_details2') }}',
    type:'get',
    dataType:'json',

    success:function(data){
      $('#sessionsthismonth').html( data['sessionsthismonth'] );
      $('#sessionslastmonth').html('Last month: ' + data['sessionslastmonth'] );



      $('#dwelltimethismonth').html( data['avgdwelltimethismonth'] );
      $('#dwelltimelastmonth').html('Last month: ' + data['avgdwelltimelastmonth'] );


      $('#datausedthismonth').html( data['thismonthdata'] );
      $('#datausedlastmonth').html('Last month: ' + data['lastmonthdata'] );


    
    
  }

  });

}
   
</script>

</body>
@stop






