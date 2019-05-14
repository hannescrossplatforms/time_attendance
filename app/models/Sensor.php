
<?php

class Sensor extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level', 'vpnip_id');

    //protected $table = 'sensors';
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table


    public function permissions() {
        return $this->belongsToMany('Venues');
    }   

    
    public function getSensorsForVenue($venueid){
     
            $sensors = $this->where('venue_id', $venueid)->get();
            $this->updateSensorStatus();
            return $sensors;
        
       
    }


    public function vpnip() {
        return $this->belongsTo('Vpnip');

    }

    public function updateSensorStatus(){
         $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();   
         $monitoringdir = $mikrotikdir->value . "sensor/monitoring/*";
         foreach (glob($monitoringdir) as $file) {
            $name = basename($file);
            $now = time();
            $lastmodified = filemtime($file);
            $readabletime = new \Mikrotik();
            $readabletime = $readabletime->secondsToTime($now - $lastmodified);
            $this->where("mac", "=", $name)->update(['lastreportedin' => $readabletime]);
            if ($now - $lastmodified  > 900){
                $this->where("mac", "=", $name)->update(['status' => "Offline"]);
            }
            else{
                $this->where("mac", "=", $name)->update(['status' => "Online"]);
            }
           
        }

    }


}