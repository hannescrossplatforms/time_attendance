@extends('layout')

@section('content')

<body class="hipWifi">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">
      @include('hipwifi.sidebar')
      	<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
      		<h1 class="page-header">Media Management</h1>
      		<table class="table table-striped">
      			<thead>
      				<th>Brand</th>
      				<th>Country</th>
      			</thead>

      			<tbody>
            @foreach($data['brands'] as $brand)
            <?php error_log("showadminbrand brand = " . $brand->name) ?>
      				<tr>
      				      <td><a href="{{route('hipwifi_showsinglebrandmedia', ['id' => $brand->id ])}}">{{$brand->name}}</a></td>
      				      <td>{{ $brand->countrie->name }}</td>
				</tr>

      			@endforeach
      			</tbody>

      			

      		</table>
      	</div>

      </div>
     </div>
  </body>





@endsection