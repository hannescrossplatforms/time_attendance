<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Vpnip extends Eloquent implements UserInterface, RemindableInterface {

use UserTrait, RemindableTrait;

    

    //////////// NOT USING SOFT DELETES FOR MEDIA ///////

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'vpnips';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */


    public function sensor() {
        return $this->hasOne('Sensor');
    }

   
    
   

    public function getVpnip(){

        $vpnip = \vpnip::where('taken', 0)->first();

        return $vpnip;
    }

    public function setVpnip ($sensor_id, $id) {
          $this->where('id', $id)->update(['sensor_id' => $sensor_id, 'taken'=> 1]);
    }
  


    public function unsetVpnip($sensor_id, $id){
        $this->where('id', $id)->update(['sensor_id' => NULL, 'taken'=> 0]);
    }

    public function getIpAddress($id){
        $vpnObj = \vpnip::find($id);
        $ipaddress = $vpnObj->ip_address;
        return $ipaddress;

    }
}













