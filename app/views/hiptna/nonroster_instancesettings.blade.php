<style type="text/css">
    .gray-overlap {
        background: #CCCC;
        opacity: 0.4;
    }
</style>
    <div class="panel panel-default disable_punctual">
        <div class="panel-heading">Set Punctuality</div>
        <div class="panel-body">
            <div class="form-group">
                <label>Start Time <i data-original-title="Start time in minutes , seconds before they are registered as being late, as measured from their scheduled start time" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
                <?php
                    $start_time         =   explode( ":" , $data['start_time'] );
                ?>
                <select name="start_minute" id="start_minute">
                    <option>MM</option>
                    @for ($i = 1; $i <= 60; $i++)
                        <?php 
                        $selected       =   "";
                        if ($i < 10) $i = "0" . $i;
                        if( $i == $start_time[0] )
                            $selected       =   "selected='selected'";
                        ?>
                        {{ '<option value="'.$i.'" '.$selected.'>'.$i.'</option>' }}
                    @endfor
                </select>
                :
                <select name="start_second" id="start_second">
                    <option>SS</option>
                    @for ($i = 1; $i <= 60; $i++)
                    <?php   
                            $selected       =   "";
                            if ($i < 10) 
                                $i = "0" . $i;
                            if( $i == $start_time[1] )
                            $selected       =   "selected='selected'";
                                                ?>
                        {{ '<option value="'.$i.'" '.$selected.'>'.$i.'</option>' }}
                    @endfor
                </select>
            
            </div>
            <div class="form-group">
              <label>Lateness Threshold <i data-original-title="Grace period in minutes before they are registered as being late, as measured from their scheduled start time" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
              <input id="lateness_threshold" class="form-control no-radius" placeholder="" type="text" value="{{$data['lateness_threshold']}}"/>
            </div>
            <td> <a id="update_nr_thresholds" class="btn btn-default btn-delete btn-sm" href="#">Update Thresholds</a> </td>
        </div>
        
    </div>
    <div class="form-group">
            <label>Disable Punctuality <i data-original-title="Disable punctuality feature" class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title=""></i></label>
            <input type="checkbox" name="punctuality_enable" id="punctuality_enable">
    </div>