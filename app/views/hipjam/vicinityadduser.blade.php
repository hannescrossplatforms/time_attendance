@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

<body class="HipADMIN">

    <form role="form" id="useradmin-form" method="post" action=" @if ($edit) {{ url('useradmin_edit'); }} @else {{ url('useradmin_add'); }} @endif ">
        <div class="container-fluid">
            <div class="row">

                @include('admin.sidebar')

                <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"> -->
                <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                    <h1 class="page-header">@if ($edit) Edit @else Add @endif User</h1>
                    @if ($errors->has())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                        @endforeach
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">

                            <!-- form was here -->

                            {{ Form::hidden('id', $data['user']->id) }}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Full Name</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="fullname" placeholder="" value="@if(Input::old('fullname')){{Input::old('fullname')}}@else{{ $data['user']->fullname }}@endif" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="email" placeholder="" value="@if(Input::old('email')){{Input::old('email')}}@else{{ $data['user']->email }}@endif" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Password</label>
                                <input type="password" class="form-control" id="exampleInputEmail1" name="password" placeholder="" @if(!$edit) required @endif>
                            </div>
                            <h2 class="sub-header">Access Level</h2>
                            @if (\User::hasAccess("superadmin"))
                            <div class="radio">
                                <label>
                                    <input type="radio" name="level_code" id="level_code" value="superadmin" @if ( $data['user']->level_code == "superadmin" ) checked @endif >
                                    {{ $data['level_names']['superadmin']; }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="level_code" id="level_code" value="admin" @if ( $data['user']->level_code == "admin" ) checked @endif >
                                    {{ $data['level_names']['admin']; }}
                                </label>
                            </div>
                            @endif
                            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin"))
                            <div class="radio">
                                <label>
                                    <input type="radio" name="level_code" id="level_code" value="reseller" @if ( ($data['user']->level_code == "reseller") || !$edit ) checked @endif >

                                    {{ $data['level_names']['reseller']; }}
                                </label>
                            </div>
                            @endif
                            @if (\User::hasAccess("superadmin") || \User::hasAccess("admin") || \User::hasAccess("reseller"))
                            <div class="radio">
                                <label>
                                    <input type="radio" name="level_code" id="level_code" value="brandadmin" @if ( ($data['user']->level_code == "brandadmin") || !$edit ) checked @endif >
                                    {{ $data['level_names']['brandadmin']; }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="level_code" id="level_code" value="mediamanager" @if ( ($data['user']->level_code == "mediamanager") || !$edit ) checked @endif >
                                    {{ $data['level_names']['mediamanager']; }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="level_code" id="level_code" value="defaultuser" @if ( ($data['user']->level_code == "defaultuser") || !$edit ) checked @endif >
                                    {{ $data['level_names']['defaultuser']; }}
                                </label>
                            </div>
                            @endif

                            <h2 class="sub-header">Venues Managed</h2>
                            <div class="row">
                                <?php if (!empty($data['venues'])) {
                                    foreach ($data['venues'] as $venue) { ?>
                                        <div class="col-md-3">
                                        <input type="checkbox" /> {{$venue->sitename}}
                                        </div>
                                <?php  }
                                } ?>
                            </div>



                            <br>
                            <button id="submitform" class="btn btn-primary">Submit</button>
                            <a href="{{ url('useradmin_showusers'); }}" class="btn btn-default">Cancel</a>
                            <!-- form ended here -->


                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Page Modals
    ================================================== -->

        <!-- Add Brand Modal -->


        <!-- hipRM Modal -->




        <!-- hipJAM Modal -->
        <div class="modal fade" id="hipJAMModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h6 class="modal-title" id="myModalLabel">HipJAM Admins - View/Change Settings</h6>
                    </div>
                    <div class="modal-body">
                        <p>content needed</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Bootstrap core JavaScript
    ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/prefixfree.min.js') }}"></script>



    </form>
</body>
@stop