@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <div class="content-wrapper">
    <div class="content-heading">
      <div>{{$data['brandname']}} Media Management<small data-localize="dashboard.WELCOME"></small></div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
            Authentication Pages Media
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 text-right">
                <a href="{{ url('hipwifi_addmedia', ['id' => $data['brandid']]); }}" class="btn btn-primary" style="margin-top:20px; float:right;"><i class="fa fa-plus"></i> Add WIFI Media</a>
              </div>
              <div class="col-12">
              <table id="mediaTable" class="table table-stripe"> 
                  <thead>
                   <tr>
                    <th>Brand Name</th>
                    <th>Targeting...</th>
                    <th></th>
                    <tr>
                  </thead>
                  <tbody>
                     @for($i = 0; $i<count($data['medianame']); $i++)
                        <tr>
                          <td>{{$data['medianame'][$i]}}</td>
                          <td>{{$data['mediatarget'][$i]}}</td>
                          <td><a href="{{url('hipwifi_editmedia/'.$data['mediaid'][$i])}}" class="btn btn-sm btn-warning">Edit Splash Page</a>
                          <a href="{{url('hipwifi_editcpmedia/'.$data['mediaid'][$i])}}" class="btn btn-sm btn-info">Edit Connect Page</a>
                          <a href="{{url('hipwifi_deletemedia/'.$data['mediaid'][$i])}}" class="btn btn-sm btn-danger btn-delete">Delete</a></td>
                        </tr>
                      @endfor
                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
            Advert Media
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 text-right">
              <a href="{{ url('hipwifi_addadvert/'.$data['brandid']); }}" class="btn btn-primary" style="margin-top:20px; float:right;"><i class="fa fa-plus"></i> Add Advert Media</a>
              </div>
              <div class="col-12">
              <table id="advertTable" class="table table-striped"> 
                  <thead>
                    <th>Campaign</th>
                    <th>Targeting...</th>
                    <th>Type</th>
                    <th></th>
                  </thead>
                  <tbody>
                       @for($i=0; $i < count($data['advertname']); $i++)
                      <tr>
                      <td>{{$data['advertname'][$i]}}</td>
                      <td>{{$data['adverttarget'][$i]}}</td>
                      <td>{{$data['adverttype'][$i]}}</td>
                      <td><a href="{{ route('hipwifi_editadvertmedia',  array($data['advertid'][$i], $data['brandid'])); }}" class="btn btn-sm btn-info">Edit</a>
                      <a href="{{ route('hipwifi_deleteadvertmedia',  array($data['advertid'][$i], $data['brandid'])); }}" class="btn btn-sm btn-danger deladvert">Delete</a></td>
                      </tr>
                      @endfor
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

    <script>
    $('.deladvert').on('click', function(){
        return confirm("Do you want to delete this advert?");

    });

      $('.delcp').on('click', function(){
        return confirm("Do you want to delete this connect page media?");

    });
    </script>
   

@stop