<?php



class Mikrotik extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    public function deleteVenue($venue) {

        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();

        $nasid = preg_replace("/ /", "_", $venue->sitename);

        $rscfile = $mikrotikdir->value . "deployment/" .  $nasid . ".rsc";
        error_log("deleteVenue Deleting rscfile : " . $rscfile);
        touch($rscfile); // Only needed otherwise it throws an error if file does not exist
        unlink($rscfile);

        $htmlfile = $mikrotikdir->value . "deployment/" .  $nasid . "_login.html";
        error_log("deleteVenue Deleting htmlfile : " . $htmlfile);
        touch($htmlfile); // Only needed otherwise it throws an error if file does not exist
        unlink($htmlfile);

        $logfile = $mikrotikdir->value . "log/" .  $nasid . ".txt";
        touch($logfile); // Only needed otherwise it throws an error if file does not exist
        error_log("deleteVenue Deleting logfile : " . $logfile);
        unlink($logfile);

    }

    public function substituteInFile($file, $old_nasid, $nasid, $radius_ip, $hostname, $ssid) {


        \Log::info("HANNES substituteInFile: file $file");
        \Log::info("HANNES substituteInFile: old_nasid $old_nasid");
        \Log::info("HANNES substituteInFile: nasid $nasid");
        \Log::info("HANNES substituteInFile: radius_ip $radius_ip");
        \Log::info("HANNES substituteInFile: hostname $hostname");
        \Log::info("HANNES substituteInFile: ssid $ssid");




        $file_contents = file_get_contents($file);
        $file_contents = str_replace("[[nasid]]",$nasid,$file_contents);
        $file_contents = str_replace("[[radius_ip]]",$radius_ip,$file_contents);
        $file_contents = str_replace("[[ssid]]",$ssid,$file_contents);
        $file_contents = str_replace("[[hostname]]",$hostname,$file_contents);
        $file_contents = str_replace("[[old_nasid]]",$old_nasid,$file_contents);

        \Log::info("HANNES substituteInFile: file contents: $file_contents");

        file_put_contents($file, $file_contents);
    }

   public function modifyAdminWifiTemplate($adminssid, $password, $type, $filename, $venueobj){
        /*This function copies an add_admin_ssid template file into a venue specific add_admin_ssid file and 
        then replaces the fields that are needed to be replaced
        variable explanations
        $adminssid = will be the admin wifi name supplied by client
        $password = will be the admin wifi password supplied by client
        $type = will be the network type for the admin wifi either open or hidden.
        $filename = this will be the file to fetch from the adminwifi template folder depending on the admin wifi number either 1, 2 or 3.
        $venuename = will be the name of the venue where admin wifi is needed.*/
        
        $venuename = str_replace(' ', '_', $venueobj->sitename);
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first(); //fetches the home/mikrotik
        $srcfile = $mikrotikdir->value . "/deployment/templates/extravenueconfigtemplates/" . $filename; // eval to home/mikrotik/deployment/templates/adminwifi/
        $destdir = $mikrotikdir->value . "/deployment/templates/extravenueconfigs/" . $venuename. '/';
        if (!file_exists($destdir)) {
            $createdestdir = mkdir($mikrotikdir->value . "/deployment/templates/extravenueconfigs/" . $venuename);
            }
        
        
        $destfile = $destdir . $adminssid; // create a file name with the path of the venue admin config directory and the admin ssid
        //$destfile = touch($destdir.$adminssid); // create this file which will be empty at this point.
        //chmod($destfile, 0777);
        copy($srcfile, $destfile);  // copy the source file into the destination file.
        $this->customiseAdminWifiTemplate($destfile, $adminssid, $password, $type, $venueobj); // run this function on the destination file created above.

    }

    public function customiseAdminWifiTemplate($file, $adminssid, $password, $type, $venueobj)
    {
        $overidersc = "on";
        $networktype = "hide-ssid=no";
        if ($type == "hidden"){

            $networktype = "hide-ssid=yes";
        }
        $readfile = file_get_contents($file);
        $readfile = str_replace("adminssid", $adminssid, $readfile);
        \Log::info("HANNES PASSWORD!!!! $password");
        $readfile = str_replace("password", $password, $readfile);
        $readfile = str_replace("hide-ssid=no", $networktype, $readfile);
        file_put_contents($file, $readfile);
        $content = file_get_contents($file);
        $time = time();
        DB::table('venues')->where('id', $venueobj->id)->update(['rscfilemodtime' => $time]);
        $this->deployRsc($venueobj, $content, $overidersc, $scriptmenu="off");

    }

    public function deleteadminssid($adminssid, $venue)
    {
        $overidersc = "on";
        $venuename = $venue->sitename;
        $modvenuename = str_replace(' ', '_', $venuename);
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $templatefile = $mikrotikdir->value . "/deployment/templates/extravenueconfigtemplates/deleteadminwifi.rsc";
        $readfile = file_get_contents($templatefile);
        $readfile = str_replace('adminssid', $adminssid, $readfile);
        $time = time();
        DB::table('venues')->where('id', $venue->id)->update(['rscfilemodtime' => $time]);
        $this->deployRsc($venue, $readfile, $overidersc, $scriptmenu="off");
    }

    public function deletebypassmac($venue, $bypassmac)
    {
        $overidersc = "on";
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $srcfile = $mikrotikdir->value . "deployment/templates/extravenueconfigtemplates/deletebypassmac.rsc";
        $readfile = file_get_contents($srcfile);
        $readfile = str_replace('bypassmacaddress', $bypassmac, $readfile);
        $this->deployRsc($venue, $readfile, $overidersc, $scriptmenu="off");

    }

    public function deployRsc($venue, $scripttext, $overridersc, $scriptmenu="on") {

        \Log::info("HANNES KOM HIER: deployRsc _951-2n.rsc");
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $macaddress = $venue->macaddress;

        $dest = $mikrotikdir->value . "deployment/" . $macaddress .  "_951-2n.rsc";
        $dest2 = $mikrotikdir->value . "deployment/" . $macaddress .  "_cAP-2n.rsc";
        $scripttext = str_replace("\x0D","",$scripttext); // Get rid of the ^M characters

        error_log("MIKROTIK : deployRsc : dest = $dest");
        error_log("MIKROTIK : deployRsc : scripttext = $scripttext");

       
         if ($scriptmenu == "on"){
            \Log::info("HANNES KOM HIER: deployRsc scriptmenu is on");
         $lines = count(file($dest));
             if($lines <= 1 or $overridersc == "on") {
                     file_put_contents($dest, $scripttext);
                     return "<div class='rscdeployed'>Your script has been deployed.</div>";
                    } 
                else {
                    return "<div class='rscnotdeployed'>Your script has not been deployed. The existing destination file has not yet been read by the AP.</div>";
                }
        }
        elseif ($scriptmenu == "off"){
            \Log::info("HANNES KOM HIER: deployRsc scriptmenu is off");
            $file = fopen($dest, 'a');
            fwrite($file, $scripttext);            
            fclose($file);
            }

        if ($scriptmenu == "on"){
            \Log::info("HANNES KOM HIER: deployRsc scriptmenu is on2");
            $lines = count(file($dest2));
                if($lines <= 1 or $overridersc == "on") {
                        file_put_contents($dest2, $scripttext);
                        return "<div class='rscdeployed'>Your script has been deployed.</div>";
                        }
                    else {
                        return "<div class='rscnotdeployed'>Your script has not been deployed. The existing destination file has not yet been read by the AP.</div>";
                    }

            }
            elseif ($scriptmenu == "off"){
                \Log::info("HANNES KOM HIER: deployRsc scriptmenu is off 2");
                $file = fopen($dest2, 'a');
                fwrite($file, $scripttext);            
                fclose($file);
                }
                
        return true;
    }

   public function addMacAddressBypass($venue, $bypassmac, $comment){

    \Log::info("HANNES KOM HIER: addMacAddressBypass");

         $overridersc = "on";
         $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
         //$macaddress = $venue->macaddress;
         //$dest = $mikrotikdir->value . "deployment/" . $macaddress .  "_951-2n.rsc";

        $srcfile = $mikrotikdir->value. "deployment/templates/extravenueconfigtemplates/addbypassmac.rsc";
        $readfile = file_get_contents($srcfile);
        $readfile = str_replace("bypassmacaddress", $bypassmac, $readfile);
        $readfile = str_replace("bypasscomment", $comment, $readfile); 
        //$content = file_get_contents($readfile);
        $time = time();
        DB::table('venues')->where('id', $venue->id)->update(['rscfilemodtime' => $time]);
        $this->deployRsc($venue, $readfile, $overridersc, $scriptmenu="off");

        
    }

    public function addVenue($venue) {

        // create an resc file for both 951 and cAP
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $macaddress = $venue->macaddress;

        $old_nasid = "";
        $nasid = preg_replace("/ /", "_", $venue->sitename);

        $brand = \Brand::find($venue->brand_id);
        $ssid = $venue->ssid;
 
        $hostname = \Server::find($venue->server_id)->hostname;
        $radius_ip = gethostbyname($hostname);

        // Set up the 951 script
        $source = $mikrotikdir->value . "deployment/templates/951-2n.setup.template";
        $dest = $mikrotikdir->value . "deployment/" . $macaddress .  "_951-2n.rsc";
        copy($source, $dest);
        $this->substituteInFile($dest, $old_nasid, $nasid, $radius_ip, $hostname, $ssid);

        // Set up the cAP script
        $source = $mikrotikdir->value . "deployment/templates/cAP-2n.setup.template";
        $dest = $mikrotikdir->value . "deployment/" . $macaddress .  "_cAP-2n.rsc";
        copy($source, $dest);
        $this->substituteInFile($dest, $old_nasid, $nasid, $radius_ip, $hostname, $ssid);

        // Setup the login.html
        $source = $mikrotikdir->value . "deployment/templates/_login.html.template";
        $dest = $mikrotikdir->value . "deployment/" . $macaddress .  "_login.html";
        copy($source, $dest);
        $this->substituteInFile($dest, $old_nasid, $nasid, $radius_ip, $hostname, $ssid);

        // $macaddress = \Input::get('macaddress');
        $this->genTabletposcode($macaddress);


        // error_log("Mikrotik : addVenue : copying $macaddress :: $source, $dest");

        return true;
    }


//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////


    public function updateVenue($venue, $old_sitename) {
        \Log::info("HANNES EDIT VENUE old sitename: $old_sitename");
        // create an resc file for both 951 and cAP
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $macaddress = $venue->macaddress;

        $old_nasid = preg_replace("/ /", "_", $old_sitename);
        $nasid = preg_replace("/ /", "_", $venue->sitename);

        $brand = \Brand::find($venue->brand_id);
        $ssid = $venue->ssid;

        $server = \Server::find($venue->server_id);
        $hostname = $server->hostname;
        $radius_ip = $server->ipaddress;

        // Set up the 951 script
        $source1 = $mikrotikdir->value . "deployment/templates/edit_venue_template";
        $dest1 = $mikrotikdir->value . "deployment/" . $macaddress .  "_951-2n.rsc";
        copy($source1, $dest1);

        $this->substituteInFile($dest1, $old_nasid, $nasid, $radius_ip, $hostname, $ssid);
    
        \Log::info("HANNES EDIT VENUE source 1: $source1");
        \Log::info("HANNES EDIT VENUE dest 1: $dest1");
        \Log::info("HANNES EDIT VENUE sub dest: $dest1, old nas id: $old_nasid, nasId: $nasid, radius ip: $radius_ip, hostname: $hostname, ssid: $ssid");

        // Set up the cAP script
        
        $source2 = $mikrotikdir->value . "deployment/templates/edit_venue_template";
        $dest2 = $mikrotikdir->value . "deployment/" . $macaddress .  "_cAP-2n.rsc";
        copy($source2, $dest2);

        $this->substituteInFile($dest2, $old_nasid, $nasid, $radius_ip, $hostname, $ssid);
        

        \Log::info("HANNES EDIT VENUE source 2: $source2");
        \Log::info("HANNES EDIT VENUE dest 2: $dest2");
        \Log::info("HANNES EDIT VENUE sub 2 dest: $dest2, old nas id: $old_nasid, nasId: $nasid, radius ip: $radius_ip, hostname: $hostname, ssid: $ssid");
        

        // Setup the login.html
        $source = $mikrotikdir->value . "deployment/templates/_login.html.template";
        $dest = $mikrotikdir->value . "deployment/" . $macaddress .  "_login.html";
        copy($source, $dest);
        $this->substituteInFile($dest, $old_nasid, $nasid, $radius_ip, $hostname, $ssid);
        
        // $this->genTabletposcode($macaddress);
        
        return true;
    }

    function secondsToTime($seconds) {

        $minutes = sprintf("%02d", floor(($seconds%3600)/60));
        $hours = sprintf("%02d", floor(($seconds%86400)/3600));
        $days = sprintf("%02d", floor(($seconds%2592000)/86400));

        $time = $minutes . "m";
        if($hours != "00") $time = $hours . "h:" . $time;
        if($days != "00") $time = $days . "d:" . $time;

        return $time;

    }

    public function getAllVenueMonitoringData() {
        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();   
        $logDir = $mikrotikdir->value . "log/*.txt"; // -- /home/mikrotik/log/*.txt
        

        $data = array();
        $venuename = array();             
        $status = array();                
        foreach(glob($logDir) as $file) {
            if(!is_dir($file)) { 
                $nasid = preg_replace("/.txt/", "", basename($file)); 
                $sitename = preg_replace("/_/", " ", $nasid); 
                $data[$sitename] = array();
                $timediff = time() - filemtime($file);
                array_push($venuename, $sitename);                     

                error_log("getAllVenueMonitoringData : sitename = $sitename");
                error_log("getAllVenueMonitoringData : seconds = $timediff");
                $data[$sitename]['lastcheckin'] = $this->secondsToTime($timediff);

                if($timediff > 900) {
                    $data[$sitename]['status'] = "Offline";
                    $data[$sitename]['statuscolor'] = "redBg";
                } else {
                    $data[$sitename]['status'] = "Online";
                    $data[$sitename]['statuscolor'] = "greenBg";
                }

                array_push($status, $data[$sitename]['status']);       

                $contents = file($file);
                foreach($contents as $line) {
                    $lineArray = array();
                    $lineArray = explode("\t", $line);
                    if (!preg_match('/Log data for/',$lineArray[0])) {
                        $data[$sitename][$lineArray[0]] = $lineArray[1];
                    }
                }
            }
        }

        //for loop below added by Dare

        for ($i=0; $i<count($status); $i++) {
                \DB::table('venues')->where('sitename', '=', $venuename[$i])->update(['status' => $status[$i]]);

        }

        return $data;
    }



    public function getVenueMonitoringForUser() {

        $allVenueData = $this->getAllVenueMonitoringData();

        //dd($allVenueData);
        $user = Auth::user();
       // if($user->level_code == "superadmin") {
        //   return $allVenueData;
        //}

        $venue = new \Venue();
        $venuesForUser = $venue->getVenuesForUser("hipwifi", null, "mikrotik");
        //dd($venuesForUser);

        $filerteredVenueData = array();

        foreach($venuesForUser as $venue) {

            $sitename = $venue->sitename;
            //update the status of tabletpos printers configured for the venue
            $mac = $venue->macaddress;
            $id = $venue->id;
            $this->monitorTabletposPrinters($mac, $id);

            if (array_key_exists($sitename, $allVenueData)) { 
                $filerteredVenueData[$sitename] = $allVenueData[$sitename]; // Undefined index: OceanBXX Bedfordview

                if($allVenueData[$sitename]['status'] == 'Online') { // Status comments are for offline venues only
                    $venue->statuscomment = "No comment"; 
                    $venue->save();
                }
                $printer = new \Tabletposprinter();

                $filerteredVenueData[$sitename]["statuscomment"] = $venue->statuscomment;
                $filerteredVenueData[$sitename]["id"] = $id;
                //$filerteredVenueData[$sitename]["tabletposprinters"] = $printer->getPrintersForVenue($id);
            }
        }

        //dd($filerteredVenueData);
        return $filerteredVenueData;
    }


    //   public function genTabletposRsc($printers, $macaddress){
    //         error_log("genTabletposRsc");
    //     $printerip = array();
    //     foreach($printers as $printer){
    //         $ip = $printer->ipaddress;
    //         array_push($printerip, $ip);
    //     }
    //     $ipstring = implode(',', $printerip);
    //     $mikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
    //     $chkprintertpl = $mikdir->value. "deployment/templates/tabletposprinter.rsc";
    //     $modtpl = file_get_contents($chkprintertpl);
    //     $modedtpl = str_replace("[[addresses]]", $ipstring, $modtpl);
    //     $destfile = $mikdir->value . "deployment/tabletpos/script/" . $macaddress . "_TPIP.rsc";
    //     file_put_contents($destfile,$modedtpl);
    //     //fetch the main macaddress_rsc file and append the instruction for the AP to fetch the printer rsc and also create a scheduler for it.
    //     $tabletposcodefile = $mikdir->value. "deployment/templates/tabletposcode.template";
    //     $printerscript = file_get_contents($tabletposcodefile);
    //     $printerscript = str_replace("[[macaddress]]", $macaddress, $printerscript);


    //     $mainrsc = $mikdir->value . "deployment/" . $macaddress . "_951-2n.rsc";
    //     // $printerscript = "\n". "/system script add\ name=chkprinter policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive source=/tool fetch address=manage.hipzone.co.za\ src-path=deployment/templates/tabletpos/script/".$macaddress . "_TPIP.rsc user=mikrotik mode=ftp password=mikmanage dst-path=/printer.rsc keep-result=yes;/import file-name=/runall.rsc \n\n/system scheduler add interval=5m name=chkprinter on-event=/system script run printer.rsc\ policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive start-date=nov/25/2014 start-time=07:05:05 ";
    //     $file = fopen($mainrsc, 'a');
    //     fwrite($file, $printerscript);

    // }  


    public function genTabletposRsc($printers, $macaddress){
            error_log("genTabletposRsc");
        $printerip = array();
        foreach($printers as $printer){
            $ip = $printer->ipaddress;
            array_push($printerip, $ip);
        }
        $ipstring = implode(',', $printerip);
        $mikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        $chkprintertpl = $mikdir->value. "deployment/templates/tabletposprinter.rsc";
        $modtpl = file_get_contents($chkprintertpl);
        $modedtpl = str_replace("[[addresses]]", $ipstring, $modtpl);
        $destfile = $mikdir->value . "deployment/tabletpos/script/" . $macaddress . "_TPIP.rsc";
        file_put_contents($destfile,$modedtpl);

    }

    public function genTabletposcode($macaddress){

        \Log::info("HANNES KOM HIER: genTabletpostcode _951-2n.rsc");
        $mikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();

        //fetch the main macaddress_rsc file and append the instruction for the AP to fetch the printer rsc and also create a scheduler for it.
        $tabletposcodefile = $mikdir->value. "deployment/templates/tabletposcode.template";
        $printerscript = file_get_contents($tabletposcodefile);
        $printerscript = str_replace("[[macaddress]]", $macaddress, $printerscript);
        $mainrsc = $mikdir->value . "deployment/" . $macaddress . "_951-2n.rsc";
        $file = fopen($mainrsc, 'a');
        fwrite($file, $printerscript);

    }


    public function monitorTabletposPrinters($macaddress, $venueid){
            $mikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
            $dir = $mikdir->value;
            $time = time();
          
            $time = date("Y-m-d H:i:s");

            if(file_exists($dir . "deployment/tabletpos/result/" . $macaddress . "_TP.log")){
                $file = file($dir . "deployment/tabletpos/result/" . $macaddress . "_TP.log");
                //dd($file);
      
                foreach($file as $line){
                    $entry = explode(" ", $line);
                    $ip = $entry[0];
                    $status = trim($entry[1]);
                    $currentstatus =  \DB::table("tabletposprinters")->where("venue_id", $venueid)->where("ipaddress", $ip)->pluck("status");
                    if (($currentstatus == "Offline") && ($status == "Offline")){
                         \DB::table("tabletposprinters")->where("venue_id", $venueid)->where("ipaddress", $ip)->update(["status" => $status]);
                         //dd("not updating time");
                    }
                    else{
                            
                           \DB::table("tabletposprinters")->where("venue_id", $venueid)->where("ipaddress", $ip)->update(["status" => $status, "updated_at" => $time]);
                           //dd("updating time");
                    }
                }
           }


    }

}