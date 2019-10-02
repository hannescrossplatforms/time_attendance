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

                <div class="form-group">
                    <label>Select store*</label>
                    <select id="store_select" name="store_id" onchange="get_categories_for_store()" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($data['brands'] as $store)
                        <option value="{{ $store->id }}">
                        {{ $store->sitename }}
                        </option>
                    @endforeach
                    </select>
                </div>


                <a class="btn-add-category btn btn-default btn-sm" href="/hippnp/picknpay_manage_store_categories/add_category">Add Category</a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                        <tr>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                        @foreach ($data['engageCategories'] as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                            <a class="btn btn-default btn-sm" href="{{route('hippnp_remove_category', ['id' => $category->id])}}">Remove</a>
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