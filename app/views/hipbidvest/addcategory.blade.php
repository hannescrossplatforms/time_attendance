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
.btn-add-category{
    float:right;
    margin-bottom:20px;
}
</style>

<body class="hipTnA">

    <div class="container-fluid">
        <div class="row">

            @include('hipbidvest.sidebar')

            <form role="form" id="category-form" method="post"
                    action=" {{ url('hipbidvest/save_category'); }}">
                <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                    <h1 class="page-header">Add Room</h1>
                    <!-- To look at errors look at the addvenue.blade file -->

                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Select Venue</label>
                                <select id="venue_select" name="venue_id" class="form-control" required>
                                <option value="">Select</option>
                                @foreach($data['venues'] as $venue)
                                    <option value="{{ $venue->id }}">
                                    {{ $venue->sitename }}
                                    </option>
                                @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label>Room name*</label>
                                <input type="text" class="form-control" size="6" placeholder="Room name" name="category_name" required>
                            </div>
                            <br>
                            <button id="submitform" class="btn btn-primary">Submit</button>
                            <a href="/hipbidvest/bidvest_manage_store_categories" class="btn btn-default">Cancel</a>
                            <br>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>


@stop





