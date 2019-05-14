@extends('layout')

@section('content')

  <body class="hipRM">

    <div class="container-fluid">
      <div class="row">

        @include('hiprm.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

      <h1 class="page-header">Insight Management</h1>
      <div role="tabpanel"> 
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#demographic" aria-controls="demographic" role="tab" data-toggle="tab">Demographic</a></li>
          <li role="presentation"><a href="#nondemographic" aria-controls="nondemographic" role="tab" data-toggle="tab">Non-Demographic</a></li>
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="demographic"> <br>
            <form role="form">
              <div class="form-group">
                <label for="exampleInputEmail1">Filter Brands</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Start typing brand name to filter" type="email">
              </div>
              <a href="{{ url('hiprm_addinsight'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Display Group</a>
              <div class="form-group check-inline">
                <br>
                <label for="exampleInputEmail1">Show</label>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Hidden </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Mandatory </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Inactive </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Desktop </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Mobile </label>
              </div>
              </div>
            </form>
            <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Display Group</th>
                          <th>Brand</th>
                          <th>Active</th>
                          <th>Desktop</th>
                          <th>Mobile</th>
                          <th>Sample Size</th>
                          <th>Respondents</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>001</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Gender-Age</a></td>
                        <td>APRIDE</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="{{ url('hiprm_editinsight/1'); }}"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>001</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Gender-Age</a></td>
                        <td>PROTEA</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>001</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Gender-Age</a></td>
                        <td>KAUAI</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>002</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Language – Home Town</a></td>
                        <td>PROTEA</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>002</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Language – Home Town</a></td>
                        <td>KAUAI</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                      </tbody>
                      </table>
           </div>
           
          </div>
          <div role="tabpanel" class="tab-pane" id="nondemographic"> <br>
            <form role="form">
              <div class="form-group">
                <label for="exampleInputEmail1">Filter Brands</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Start typing brand name to filter" type="email">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Filter Campaign</label>
                <select class="form-control">
                  <option>Prokard</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
              <a href="hipRM_questionManagement_addCampaign.html" class="btn btn-primary"><i class="fa fa-plus"></i> Add Campaign</a> <a href="hipRM_questionManagement_add.html" class="btn btn-primary"><i class="fa fa-plus"></i> Add Display Group</a>
              <div class="form-group check-inline">
                <br>
                <label for="exampleInputEmail1">Show</label>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Hidden </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Inactive </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Desktop </label>
              </div>
              <div class="checkbox">
                <label>
                  <input value="" checked="" type="checkbox">
                  Mobile </label>
              </div>
              </div>
            </form>
            <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Display Group</th>
                          <th>Brand</th>
                          <th>Campaign</th>
                          <th>Active</th>
                          <th>Desktop</th>
                          <th>Mobile</th>
                          <th>Sample Size</th>
                          <th>Respondents</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>001</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Lindt</a></td>
                        <td>PROTEA</td>
                        <td>Christmas</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>002</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Card Usage</a></td>
                        <td>PROTEA</td>
                        <td>Prokard</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>003</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Card Usage</a></td>
                        <td>APRIDE</td>
                        <td>Prokard</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>004</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Local Hotels</a></td>
                        <td>APRIDE</td>
                        <td>Search En</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                        <tr>
                        <td>005</td>
                        <td><a data-original-title="Questions" href="#" data-toggle="popover" data-trigger="focus" title="" data-content="Would you return to this hotel again?
Would you recommend this hotel to a friend?">Luxury Hotels</a></td>
                        <td>KAUAI</td>
                        <td>November</td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td><input value="" type="checkbox"></td>
                        <td>0</td>
                        <td>2346</td>
                        <td><a class="btn btn-default btn-sm" href="hipRM_questionManagement_edit.html"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-default btn-sm" href="#"><i class="fa fa-copy"></i> Copy</a></td>
                        </tr>
                      </tbody>
                      </table>
           </div>
            
          </div>
        </div>
      </div>
      <a href="#" class="btn btn-primary"><i class="fa fa-refresh"></i> Update</a>
    

        
        </div>
      </div>
    </div>

  </body>

@stop