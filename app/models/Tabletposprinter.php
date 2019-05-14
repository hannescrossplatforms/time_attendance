<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Tabletposprinter extends Eloquent implements UserInterface, RemindableInterface {

        use UserTrait, RemindableTrait;

    

    //////////// NOT USING SOFT DELETES FOR MEDIA ///////

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tabletposprinters';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

            public function addPrinter($name, $ip, $venueid){
                $name_exists = $this->where('name', $name)->where('venue_id', $venueid)->pluck('id');  //using this to test if a printer name already exists at a venue
                $ip_exists = $this->where('ipaddress', $ip)->where('venue_id', $venueid)->pluck('id');   //using this to test if a printer ip already exists at a venue
                
                if($name_exists || $ip_exists){
                    return false;
                }
                else{
                    $this->name = $name;
                    $this->ipaddress = $ip;
                    $this->venue_id = $venueid;
                    $this->save();

                    return $this->id;
                 }

            }

            public function getPrinter($id){
                $entry= $this->find($id);
                return $entry;
            }

            public function getPrintersForVenue($venueid){
                $printers = $this->where('venue_id', $venueid)->get();
                return $printers;

            }

            public function editPrinter($id, $newname, $newip, $venueid){
                $entry = $this->find($id);
                $oldname = $entry->name;
                $oldip = $entry->ipaddress;
                if(($oldname != $newname) && ($oldip != $newip)){
                    error_log("oldname and oldip different from newname and newip");
                     $name_exists = $this->where('name', $newname)->where('venue_id', $venueid)->pluck('id');  //using this to test if a printer name already exists at a venue
                     $ip_exists = $this->where('ipaddress', $newip)->where('venue_id', $venueid)->pluck('id');   //using this to test if a printer ip already exists at a venue
                        if($name_exists || $ip_exists){
                        return false;
                         }
                         else{
                            $this->where("id", $id)->update(["name" => $newname, "ipaddress" => $newip]);
                            return true;
                         }
                
                }
                if(($oldname != $newname) && ($oldip == $newip)){

                            error_log("oldname different from newname but ip stays the same");
                            $name_exists = $this->where('name', $newname)->where('venue_id', $venueid)->pluck('id');
                            if($name_exists){
                                 return false;
                            }
                            else{
                             $this->where("id", $id)->update(["name" => $newname]);
                             return true;
                             }

                }

                 if(($oldname == $newname) && ($oldip != $newip)){
                            error_log("oldip different from newip but name stays the same");
                            $ip_exists = $this->where('ipaddress', $newip)->where('venue_id', $venueid)->pluck('id');
                            if($ip_exists){
                                return false;
                            }
                            else{
                                 $this->where("id", $id)->update(["ipaddress" => $newip]);
                                 return true;
                            }

                 }
            }

            public function deletePrinter($id){
                $del = $this->where('id', $id)->delete();
                return true;
            }

            

}


   












