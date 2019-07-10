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

            @include('hippnp.sidebar')

            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h1 class="page-header">Pick n Pay Category Management</h1>
                <input type="hidden" id="url" name="" value={{$data['url']}}>

                <div class="container-fluid">


                <a class="btn-add-category btn btn-default btn-sm" href="add_category/<?php echo $data['store_id'];?>">Add Category</a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                        <tr>
                            <th>Venu ID</th>
                            <th>Venu Name</th>
                            <th>Actions</th>
                        </tr>
                        @foreach ($data['engageCategories'] as $category)
                        <tr>
                            <td>{{ $category->store_id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                            <a href="{{ route('/hippnp/picknpay_manage_store_categories/remove_category', ['id' => $category->id, 'store_id' => $data['store_id']) }}">Remove</a>


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