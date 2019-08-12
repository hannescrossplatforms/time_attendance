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
                                @foreach($data['brands'] as $store)
                                    <option value="{{ $store->id }}">
                                    {{ $store->sitename }}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Category*</label>
                                <select id="isplist" name="category_id" class="form-control" required>
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


<script>


function get_categories_for_store() {

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
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        }
    });

}

</script>

@stop