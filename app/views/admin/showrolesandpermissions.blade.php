@extends('angle_admin_layout')
@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Roles and Permissions</div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">


      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="permissions-tab" data-toggle="tab" href="#permissionstab" role="tab" aria-controls="home" aria-selected="true">Permissions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="roles-tab" data-toggle="tab" href="#rolestab" role="tab" aria-controls="profile" aria-selected="false">Roles</a>
        </li>
      </ul>

      

      <!-- permissionstab -->
      <div class="tab-content" id="myTabContent">
              <div role="tabpanel" class="tab-pane fade active show" id="permissionstab"> 
                @include('admin.permissionstab')
              </div>

              <!-- rolestab -->
              <div role="tabpanel" class="tab-pane fade" id="rolestab">
                @include('admin.rolestab')
              </div>
              </div>


        
      </div>
    </div>
  </div>
</section>


@stop
