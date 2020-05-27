@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Media Management<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              All Media Management Brands
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
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
		</div>
	  </div>
	</div>
  </div>
</section>


@stop