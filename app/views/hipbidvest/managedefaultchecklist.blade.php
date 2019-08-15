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
                <h1 class="page-header">Bidvest Default Checklist Management</h1>

                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="url" name="" value={{$data['url']}}>


                    </div>
                </div>

                <div class="row">
                    <a class="btn-manage-default-checklist btn btn-default btn-sm" href="bidvest_add_default_checklist_item">Add checklist item</a>
                </div>
                <br>
                <div class="row">






                    <div class="table-responsive">
                        <table class="table table-striped">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>

                        @foreach ($data['defaultChecklistItems'] as $checklistItem)
                        <tr>
                            <td>{{ $checklistItem->title }}</td>
                            <td>{{ $checklistItem->description }}</td>
                            <td>
                            <a href="bidvest_delete_checklist_item/<?php echo $checklistItem->id;?>" class="btn btn-default btn-sm">Delete checklist item</a>
                            </td>
                        </tr>
                        @endforeach


                        </table>
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



</script>

@stop