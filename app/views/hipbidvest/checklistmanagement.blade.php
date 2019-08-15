@extends('layout')

@section('content')
<style type="text/css">
.overlay {
    background: rgba(129, 119, 119, 0.5) none no-repeat scroll 0% 0%;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0px;
    left: 1px;
    z-index: 1019;
    padding-left: 53%;
    padding-top: 20%;
}

</style>

<body class="hipTnA">

    <div class="container-fluid">
        <div class="row">

            @include('hipbidvest.sidebar')

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">Bidvest Checklist Management</h1>
                <!-- To look at errors look at the addvenue.blade file -->
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Select Venue</label>
                            <select id="venue_select" name="venue_id" onchange="get_rooms_for_store()" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($data['venues'] as $venue)
                                <option value="{{ $venue->id }}">
                                {{ $venue->sitename }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select Room</label>
                            <select id="room_select" name="room_id" onchange="get_rooms_for_store()" class="form-control" required>
                            <option value="">Select</option>

                            </select>
                        </div>











                    </div>
                </div>
            </div>






        </div>
    </div>
</body>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>
    <script src="/js/jquery.timepicker.min.js"></script>

<script>


function get_rooms_for_store() {

    var store_id = $("#store_select").val();

    $.ajax({
        url: pathname + 'hipbidvest/storeCategories/' + store_id,
        type: 'get',
        dataType: 'json',
        data: {
            'id': store_id
        },
        success: function(data) {
            debugger;
           alert(data);

           var $dropdown = $("#room_select");
            $.each(result, function() {
                // $dropdown.append($("<option />").val(this.ImageFolderID).text(this.Name));
            });




        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });

}

</script>

@stop