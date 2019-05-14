@extends('layout')

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Media Management</h1>
            <form role="form">
            </form>
            <div class="table-responsive">
                <table id="mediaTable" class="table table-striped">                
                  <thead>
                    <tr>
                      <th>Brand Name</th>
                      <th>Target</th>
                      <th>
                      </th>
                    </tr>
                  </thead>
                  <tbody>  
                    <tr>
                      <td> Kauai</td>
                      <td> South Africa</td>
                      <td><a href="{{ url('hiprm_editmedia/1'); }}" class="btn btn-default btn-sm">edit</a>
                          <a class="btn btn-default btn-delete btn-sm" data-mediaid="92" href="#">delete</a>
                      </td>
                    </tr>
                                        <tr>
                      <td> Kauai</td>
                      <td> South Africa, Free State, Bloemfontein</td>
                      <td><a href="{{ url('hiprm_editmedia/1'); }}" class="btn btn-default btn-sm">edit</a>
                          <a class="btn btn-default btn-delete btn-sm" data-mediaid="93" href="#">delete</a>
                      </td>
                    </tr>
                                        <tr>
                      <td> HIPBUNGLW</td>
                      <td> South Africa</td>
                      <td><a href="{{ url('hiprm_editmedia/1'); }}" class="btn btn-default btn-sm">edit</a>
                          <a class="btn btn-default btn-delete btn-sm" data-mediaid="94" href="#">delete</a>
                      </td>
                    </tr>
                                        <tr>
                      <td> HIPMOVING</td>
                      <td> South Africa, Kwazulu Natal, Durban</td>
                      <td><a href="{{ url('hiprm_editmedia/1'); }}" class="btn btn-default btn-sm">edit</a>
                          <a class="btn btn-default btn-delete btn-sm" data-mediaid="97" href="#">delete</a>
                      </td>
                    </tr>
                                        <tr>
                      <td> TACDOT</td>
                      <td> South Africa</td>
                      <td><a href="{{ url('hiprm_editmedia/1'); }}" class="btn btn-default btn-sm">edit</a>
                          <a class="btn btn-default btn-delete btn-sm" data-mediaid="99" href="#">delete</a>
                      </td>
                    </tr>
                                       </tbody>
                </table>
            </div>
            <a href="{{ url('hiprm_addmedia'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Media</a>
        
        </div>
      </div>
    </div>

  </body>

@stop