                    <a id="buildtable"></a>

                    <form class="form-inline" role="form" style="margin-bottom: 15px;">
                      <div class="form-group">
                        <label  class="sr-only" for="exampleInputEmail2">Venue</label>
                        <input type="text" class="form-control" id="src-sitename" placeholder="Venue">
                      </div>
                      <div class="form-group">
                        <label class="sr-only" for="exampleInputEmail2">Mac Address</label>
                        <input type="text" class="form-control" id="src-macaddress" placeholder="Mac Address">
                      </div>

                      <button id="filter" type="submit" class="btn btn-primary">Filter</button>
                      <button id="reset" type="submit" class="btn btn-warning">Reset</button>
                    </form>

                    <div class="table-responsive">
                        <table id="venueTable" class="table table-striped"> </table>
                    </div>