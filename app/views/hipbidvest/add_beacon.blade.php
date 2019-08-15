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
            <input type="hidden" id="url" name="" value={{$data['url']}}>
            <form role="form" id="category-form" method="post"
                    action=" {{ url('hipbidvest/save_beacon'); }}">
                <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                    <h1 class="page-header">Add Beacon</h1>
                    <!-- To look at errors look at the addvenue.blade file -->
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Beacon UUID*</label>
                                <input type="text" class="form-control" size="6" placeholder="Beacon UUID" name="beacon_uuid" required>
                            </div>

                            <div class="form-group">
                                <label>Beacon Minor*</label>
                                <input type="text" class="form-control" size="6" placeholder="Beacon Minor" name="beacon_minor" required>
                            </div>

                            <div class="form-group">
                                <label>Beacon Major*</label>
                                <input type="text" class="form-control" size="6" placeholder="Beacon Major" name="beacon_major" required>
                            </div>

                            <div class="form-group">
                                <label>Store*</label>
                                <select id="store_select" name="store_id" onchange="get_categories_for_store()" class="form-control" required>
                                <option value="">Select</option>
                                @foreach($data['brands'] as $store)
                                    <option value="{{ $store->id }}">
                                    {{ $store->sitename }}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                            <div id="select_room_container" class="form-group hidden">
                                <label>Category*</label>
                                <select id="room_select" name="category_id" class="form-control" required>
                                @foreach($data['categories'] as $category)
                                    <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                    </option>
                                @endforeach
                                </select>
                            </div>


                            <br>
                            <button id="submitform" class="btn btn-primary">Submit</button>
                            <a href="/hipbidvest/bidvest_beacon_management" class="btn btn-default">Cancel</a>
                            <br>
                        </div>
                    </div>
                </div>
            </form>

        </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/prefixfree.min.js"></script>
<script src="/js/jquery.timepicker.min.js"></script>

<script>


function get_categories_for_store() {

    let pathname = $('#url').val();
    var store_id = $("#store_select").val();

    $textValue = $("#store_select option:selected").text();

    if ($textValue != "Select"){
        debugger;
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
    else {
        $("#select_room_container").addClass('hidden');
    }



}

</script>

@stop