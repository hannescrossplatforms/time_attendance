<div class="form-group monitoring-form">
    <form class="form-inline" role="form" style="margin-bottom: 15px;">
        <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
        <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
        <button id="filter" type="submit" class="btn btn-primary">Filter</button>
        <button id="reset" type="submit" class="btn btn-default">Reset</button>
    </form>
</div>

<div class="monitoring-icons">
<a href="" id="listviewicon" title="List view"><i class="fa fa-align-justify fa-3x"></i></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="" id="gridviewicon" title="Grid view"><i class="fa fa-th fa-3x"></i></a>
</div>

<div class="table-responsive clear" id="listview">
    <table id="venueTable" class="table table-striped"> </table>
</div>
<!-- Button trigger modal -->
<button style="display:none;" type="button" id="launch" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong"> Launch demo modal </button>


<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="needed-mheader" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="needed-mheader">Tabletpos Printers</h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>

<div class="table-responsive clear" id="gridview">
    <table id="venueGrid" class="table table-venuegrid"> </table>

</div>
<div class="modal" id="tabletposprinter"></div>
