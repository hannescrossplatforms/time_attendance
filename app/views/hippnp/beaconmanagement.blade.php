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

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">Pick n Pay Beacon Management</h1>
                <input type="hidden" id="url" name="" value={{$data['url']}}>

                <div class="container-fluid">

                    <div class="table-responsive">
                        <table class="table table-striped">
                        <tr>
                            <th>Beacon UUID</th>
                            <th>Beacon Minor</th>
                            <th>Beacon Major</th>
                            <th>Store name</th>
                            <th>Category name</th>
                            <th>Actions</th>
                        </tr>
                        @foreach ($data['beacons'] as $beacon)
                        <tr>
                            <td>{{ $beacon->beacon_uuid }}</td>
                            <td>{{ $beacon->beacon_minor }}</td>
                            <td>{{ $beacon->beacon_major }}</td>
                            <td>{{ $beacon->store_name }}</td>
                            <td>{{ $beacon->category_name }}</td>
                            <td>
                            <a href="picknpay_manage_store_categories/<?php echo $beacon->id;?>" class="btn btn-default btn-sm">Manage categories</a>
                            </td>
                        </tr>
                        @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
</body>


@stop