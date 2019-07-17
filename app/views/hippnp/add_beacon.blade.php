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

            @include('hippnp.sidebar')

            <form role="form" id="category-form" method="post"
                    action=" {{ url('hippnp/save_beacon'); }}">
                <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                    <h1 class="page-header">Add Beacon</h1>
                    <!-- To look at errors look at the addvenue.blade file -->
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Beacon UUID*</label>
                                <input type="text" class="form-control" size="6" placeholder="Beacon UUID" name="beacon_uuid" required>
                            </div>
                            <br>
                            <button id="submitform" class="btn btn-primary">Submit</button>
                            <a href="/hippnp/picknpay_beacon_management" class="btn btn-default">Cancel</a>
                            <br>
                        </div>
                    </div>
                </div>
            </form>

        </div>
</body>


@stop