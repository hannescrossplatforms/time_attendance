@extends('layout')

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

      <h1 class="page-header">Hip Reports</h1>
      <form role="form">
        <div class="form-group">
          <input class="form-control input-lg" id="exampleInputEmail1" placeholder="start typing report name to filter" type="email">
        </div>
      </form>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Report Name</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Eighty/20 405</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Breakfast</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Minibars</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Lindt 419 - Jan 16</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Lindt 420 - Jan 16</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Lindt 419</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Eighty/20 405</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Lindt 420</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Lindt 420 -200</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
            <tr>
              <td>Lindt 419-200</td>
              <td class="text-success">Completed</td>
              <td>
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_report.pdf"><i class="fa fa-download"></i> Report</a> 
                <a class="btn btn-default btn-sm" href="http://madedev.co.za/hip/09/hipRM_hipReports_data.txt"><i class="fa fa-download"></i>  Data</a> 
                <a class="btn btn-default btn-sm" href="hipRM_hipReports_edit.html">Edit</a> 
                <a class="btn btn-default btn-sm btn-delete" href="#">Delete</a></td>
            </tr>
          </tbody>
        </table>
      </div>
      <a href="hipRM_hipReports_add.html" class="btn btn-primary"><i class="fa fa-plus"></i> Add Report</a> 
        </div>
      </div>
    </div>

  </body>

@stop