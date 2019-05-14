    <div class="panel panel-default">
            <div class="panel-heading">Set Performance Thresholds</div>
            <div class="panel-body">
              <div class="form-group">
                <label>Lateness Threshold (minutes) <i data-original-title="Grace period in minutes before they are registered as being late, as measured from their scheduled start time" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
                <input id="lateness_threshold" class="form-control no-radius" placeholder="" type="text" value="{{$data['lateness_threshold']}}"/>
              </div>
              <div class="form-group">
                <label>Proximity Target <i data-original-title="The minimum of minutes in a day that staff must be at their workstation" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
                <input id="proximity_target" class="form-control no-radius" placeholder="" type="text" value="{{$data['proximity_target']}}"/>
              </div>
              <div class="form-group">
                <label>LATE SMS Trigger <i data-original-title="The number of minutes after scheduled start-time that a 'Staff Member Late' SMS will be sent to RHM" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
                <input id="late_sms_trigger" class="form-control no-radius" placeholder="" type="text" value="{{$data['late_sms_trigger']}}"/>
              </div>
              <div class="form-group">
                <label>ABSENCE SMS Trigger <i data-original-title="The number of minutes after scheduled start-time that a 'Staff Member Absent' SMS will be sent to RHM & OSM" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
                <input id="absence_sms_trigger" class="form-control no-radius" placeholder="" type="text" value="{{$data['absence_sms_trigger']}}"/>
              </div>
              
              <div class="form-group">
                <label>Proximity Settings</label>
              </div>
              <div class="form-group">
                <div class="sethead1">Distance (meters) <i data-original-title="The maximum distance from the workstation which determines that they are within proximity" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></div>

                <div class="sethead2">Time Away (minutes) <i data-original-title="The number of consecutive minutes that they can be out of proximity" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></div>
              </div>
              <div class="clear"></div>
              <div class="form-group">
                Store Type <b>A</b>
                <input id="tnaproximitydistance_a" style="width: 200 !important;" placeholder="Distance In Meters" type="text" value="{{$data['tnaproximitydistance_a']}}"/>
                <input id="tnaproximitytime_a" style="width: 200 !important;" placeholder="Time Away In Minutes" type="text" value="{{$data['tnaproximitytime_a']}}"/>
              </div>
                <div class="form-group" class="clear">
                Store Type <b>B</b>
                <input id="tnaproximitydistance_b" style="width: 200 !important;" placeholder="Distance In Meters" type="text" value="{{$data['tnaproximitydistance_b']}}"/>
                <input id="tnaproximitytime_b" style="width: 200 !important;" placeholder="Time Away In Minutes" type="text" value="{{$data['tnaproximitytime_b']}}"/>
              </div>
              <div class="form-group" class="clear">
                Store Type <b>C</b>
                <input id="tnaproximitydistance_c" style="width: 200 !important;" placeholder="Distance In Meters" type="text" value="{{$data['tnaproximitydistance_c']}}"/>
                <input id="tnaproximitytime_c" style="width: 200 !important;" placeholder="Time Away In Minutes" type="text" value="{{$data['tnaproximitytime_c']}}"/>
              </div>
              <td> <a id="updatethresholds" class="btn btn-default btn-delete btn-sm" href="#">Update Thresholds</a> </td>
            </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">Upload Staff Roster</div>
      <div class="panel-body">
        <div id="errormsg">
          <?php $message = Session::get('msg'); if(isset($message)) { print_r($message . " <br><br>"); Session::set('msg',''); } ?> {{ $errors->first('cfile') }}
        </div>
        <div class="form-group">
          @if ($errors->has())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                {{ $error }}<br>        
              @endforeach
            </div>
          @endif
          <form id="rosterupload" name="rosterupload" enctype='multipart/form-data' action="#" onsubmit="return submitForm(this)" method='post'>
            <input size='50' type='file' id="cfile" name='cfile'><br />
            Select month: 
            <select id="cmonth" name="cmonth" >
              <option value="Jan">Jan</option>
              <option value="Feb">Feb</option>
              <option value="Mar">Mar</option>
              <option value="Apr">Apr</option>
              <option value="May">May</option>
              <option value="Jun">Jun</option>
              <option value="Jul">Jul</option>
              <option value="Aug">Aug</option>
              <option value="Sep">Sep</option>
              <option value="Oct">Oct</option>
              <option value="Nov">Nov</option>
              <option value="Dec">Dec</option>
            </select>
            Select year:
            <select id="cyear" name="cyear" >
            <?php for ($i=2; $i > 0; $i--) { ?>
              <option value="<?php echo date('Y', strtotime('-'.$i.' year')); ?>"><?php echo date('Y', strtotime('-'.$i.' year')); ?></option>
            <?php } ?>
              <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
              <option value="<?php echo date('Y', strtotime('+1 year')); ?>"><?php echo date('Y', strtotime('+1 year')); ?></option>
            </select>
            <br />
            Please enter you email:
            <input type="email" name="cemail" id="cemail"> <span style="font-size: 85%;">You will be notified once the roster has been uploaded.</span>
            <br/><br/>
            <input type="submit" name="submit" value="Upload Roster" onclick="if ( $('#cfile').val() == '' ){ alert( 'select file' ); return false; }">
            <br/>
            <span id="submit_msg" style="color:green;"></span>
          </form> 
        </div>
      </div>
    </div>
    