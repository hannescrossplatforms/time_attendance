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

.btn-manage-default-checklist{
    float: right;
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
                        <input type="hidden" id="url" name="" value={{$data['url']}}>
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

                        <div id="select_room_container" class="form-group hidden">
                            <label>Select Room</label>
                            <select id="room_select" name="room_id" onchange="show_assign_button()" class="form-control" required>
                            <option value="">Select</option>

                            </select>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <a class="btn-manage-default-checklist btn btn-default btn-sm" href="bidvest_manage_default_checklist">Manage default checklist</a>
                </div>
                <br>
                <div class="row">

                    <div id="table-container" class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </table>
                    </div>

                </div>

                <div class="row">
                    <a id="assign_default_checklist_to_room" class="btn-manage-default-checklist btn btn-default btn-sm hidden">Assign default checklist to room</a>
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

$("#assign_default_checklist_to_room").on("click", function(){

    var store_id = $("#venue_select").val();
    var room_id = $("#room_select").val();

    $.ajax({
        url: pathname + 'hipbidvest/bidvest_assign_default_checklist_items',
        type: 'post',
        dataType: 'html',
        data: {
            'store_id': store_id,
            'room_id': room_id
        },
        success: function(result) {
            $("#table-container").html(result);

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });






});

function get_rooms_for_store() {

    pathname = $('#url').val();

    var store_id = $("#venue_select").val();

    $textValue = $("#venue_select option:selected").text();
    if ($textValue == "Select") {
        var $dropdown = $("#room_select");
        $dropdown.empty();
        $("#select_room_container").addClass("hidden");
    }


    $.ajax({
        url: pathname + 'hipbidvest/storeCategories/' + store_id,
        type: 'get',
        dataType: 'json',
        data: {
            'id': store_id
        },
        success: function(result) {

            $("#select_room_container").removeClass("hidden");
            var $dropdown = $("#room_select");

            $dropdown.empty();
            $dropdown.append($("<option />").val('').text("Select"));
            result.forEach((obj) => {
                $dropdown.append($("<option />").val(obj.id).text(obj.name));
            });

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });

}

function show_assign_button(){

    $textValue = $("#room_select option:selected").text();
    if ($textValue == "Select") {
        $("#assign_default_checklist_to_room").addClass('hidden');
    }
    else {
        $("#assign_default_checklist_to_room").removeClass('hidden');
    }

}

</script>

@stop