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
                <h1 class="page-header">Pick n Pay Category Management</h1>
                <input type="hidden" id="url" name="" value={{$data['url']}}>

                <div class="container-fluid">

                    <div class="table-responsive">
                        <table class="table table-striped">
                        <tr>
                            <th>Venu ID</th>
                            <th>Venu Name</th>
                            <th>Actions</th>
                        </tr>
                        @foreach ($data['venues'] as $venue)
                        <tr>
                            <td>{{ $venue->id }}</td>
                            <td>{{ $venue->sitename }}</td>
                            <td>
                            <a class="btn btn-default btn-sm" href="{{ 'hippnp/picknpay_manage_store_categories/$venue->id }}">Manage categories</a>

                            <!-- <a href="{{ url('hipjam_editvenue'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n'; -->
                            </td>
                        </tr>
                        @endforeach

                        </table>
                    </div>


                    <!-- <table style="width:100%">

                    </table> -->
                </div>
            </div>
        </div>
</body>


@stop