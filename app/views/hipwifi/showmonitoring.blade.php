@extends('angle_wifi_layout')

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Venue Monitoring<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              All Venues

            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
              <div id="page-replace-div"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="viewVenueModals"><span id="closeViewModal">x</span></div>  

  <script>
    let scrollPosition = 0;
    let selectedToShow = 0;
    let searchText = null;
    let selectedModalId = null;
    function refreshPage() {
      
      $.ajax({
        url: 'http://hiphub.hipzone.co.za/hipwifi_populatemonitoring',
            type: 'get',
            dataType: 'html',
            success: function(result) {

              let shouldShow = true;
              if(!$(selectedModalId).hasClass('in')){
                shouldShow = false;
              }

                if(selectedModalId != null) {
                    $(selectedModalId).modal('hide');
                }
                $("#page-replace-div").html(result);
                window.scrollTo(0, scrollPosition);
                if(selectedModalId != null) {
                  if(shouldShow){
                    $(selectedModalId).modal('show');
                  }
                  
                }         
                
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });
    }

    $('document').ready(function() {
      
      refreshPage();
      
      (function loop() {
        setTimeout(function () {
          this.refreshPage();
          loop()
        }, 15000);
      }());

    });

    $(window).scroll(function(){
        var scrollPos = $(document).scrollTop();
        scrollPosition = scrollPos;
    });    

  </script>
@stop
