

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <input type="text" class="form-control" name="filtresitename" id="filtresitename" placeholder="Venue Name">
              </div>

              <div class="form-group">
                {{ Form::text('date', null, 
                array('name' => 'from', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", 'class' => 'form-control datepicker','placeholder' => 'From Date', 'id' => 'from')) }}
              </div>

              <div class="form-group">
                {{ Form::text('date', null, array('name' => 'to', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", 'class' => 'form-control datepicker','placeholder' => 'To Date', 'id' => 'to')) }}
              </div>

              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button>

            </form>
            
            <div class="table-responsive">
              <div class="table-responsive">
                  <table id="statsTable" class="table table-striped"> </table>
              </div>
            </div> 