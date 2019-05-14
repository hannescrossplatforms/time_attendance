<?php



class Mikrotik_old extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    private function rebameLogFile($nasid) {
    }

    private function resetCode() {

        $code = <<<EOF
:log info "Sending reset file"

:local nasid [/system identity get name];
:local ip manage.hipzone.co.za;
:local tmpfile ("tmpfile");
:local deleteFilePath ("deployment/" . "\$nasid" . ".delete");
:local resetFilePath ("deployment/" . "\$nasid" . ".reset");

/file print file=\$tmpfile;
:local fileContents;
:set fileContents ("This is a temporary file");

:set tmpfile ("\$tmpfile" . ".txt");
/file set \$tmpfile contents=\$fileContents;

/tool fetch address=\$ip src-path=\$tmpfile user=mikrotik mode=ftp password=mikmanage dst-path=\$resetFilePath upload=yes;


EOF;

        return $code;

    }


    private function deleteCode() {

        $code = <<<EOF
:log info "Sending delete file"

:local nasid [/system identity get name];
:local ip manage.hipzone.co.za;
:local tmpfile ("tmpfile");
:local deleteFilePath ("deployment/" . "\$nasid" . ".delete");
:local resetFilePath ("deployment/" . "\$nasid" . ".reset");

/file print file=\$tmpfile;
:local fileContents;
:set fileContents ("This is a temporary file");

:set tmpfile ("\$tmpfile" . ".txt");
/file set \$tmpfile contents=\$fileContents;

/tool fetch address=\$ip src-path=\$tmpfile user=mikrotik mode=ftp password=mikmanage dst-path=\$deleteFilePath upload=yes;


EOF;

        return $code;

    }

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

    public function addVenue($venue) {

        error_log("Mikrotik::addVenue" . $venue->sitename);

        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();
        error_log("Mikrotik::addVenue : mikrotikdir : " . $mikrotikdir->value);

        $brand = \Brand::find($venue->brand_id);
        $nasid = preg_replace("/ /", "_", $venue->sitename);
        $serverhostname = \Server::find($venue->server_id)->hostname;
        $serverip = gethostbyname ( $serverhostname );

        // Create the login.html file ////////////////////
        $logincontents = <<<EOF
<html>
<head><title>...</title></head>
<body>
\$(if chap-id)
<noscript>
<center><b>JavaScript required. Enable JavaScript to continue.</b></center>
</noscript>
\$(endif)
<center>If you are not redirected in a few seconds, click 'continue' below<br>
<form name="redirect" action="http://$serverhostname/login" method="get">

<input type="hidden" name="res" value="notyet" />
<input type="hidden" name="nasid" value="$(identity)" />
<input type="hidden" name="mac" value="\$(mac)">
<input type="hidden" name="ip" value="\$(ip)">
<input type="hidden" name="username" value="\$(username)">
<input type="hidden" name="link-login" value="\$(link-login)">
<input type="hidden" name="link-orig" value="\$(link-orig)">
<input type="hidden" name="error" value="\$(error)">
<input type="hidden" name="chap-id" value="\$(chap-id)">
<input type="hidden" name="chap-challenge" value="\$(chap-challenge)">
<input type="hidden" name="link-login-only" value="\$(link-login-only)">
<input type="hidden" name="link-orig-esc" value="\$(link-orig-esc)">
<input type="hidden" name="mac-esc" value="\$(mac-esc)">
<input type="hidden" name="device-type" value="mikrotik">
<input type="submit" value="continue">
</form>
<script language="JavaScript">
<!--
   document.redirect.submit();
//-->
</script></center>
</body>
</html>

EOF;

        $loginfile = $mikrotikdir->value . "deployment/" .  $nasid . "_login.html";
        $handle = fopen($loginfile, 'w');
        fwrite($handle, $logincontents);
        fclose($handle);
        chmod($loginfile, 0666);

        // Create the rsc file ////////////////////
        $ssid = $brand->ssid;
        $rsccontents = <<<EOF

:local nasid [/system identity get name];

##################################################################
# Set variables for uploading the login script
:local ip manage.hipzone.co.za
:local fileName ("\$nasid" . "_login.html");
:local filePath ("deployment/" . "\$fileName");

### Set the device type
:local device ("951-2n");
:if ([:len [/file find name="flash"]] > 0) do={ :set device "cAP-2n"; }

### Ensure login.html is set to the correct path
:local dstPath ("hotspot/login.html");
:if (\$device = "cAP-2n") do={ :set dstPath "flash/hotspot/login.html"; }

:log info "Running config script for $nasid "
:log info ("Config ip : " . \$ip);
:log info ("Config fileName : " . \$fileName);
:log info ("Config filePath  : " . \$filePath);
:log info ("Config device  : " . \$device);
:log info ("Config dstPath  : " . \$dstPath);

# Upload the login script
/tool fetch address=\$ip src-path=\$filePath user=mikrotik mode=ftp password=mikmanage dst-path=\$dstPath keep-result=yes;
##################################################################

:if (\$device = "cAP-2n") do={

# 07_05_2015 by Daniel Titton
# setup for Mikrotik cAP-2n
# Needs initial.rsc to have run on AP (with internet connection) locally first

#FIRST SETUP A FEW THINGS

/ip hotspot profile
add hotspot-address=10.5.50.1 login-by=http-chap,https,http-pap name=hsprof1 \
    radius-interim-update=30s use-radius=yes

/ip hotspot user profile
set [ find default=yes ] idle-timeout=none keepalive-timeout=2m \
    mac-cookie-timeout=3d
    
/ip pool
add name=hs-pool-6 ranges=10.5.50.2-10.5.50.254

/interface wireless security-profiles
set [ find default=yes ] supplicant-identity=MikroTik

/ip dhcp-server
add address-pool=hs-pool-6 disabled=no interface=wlan1 lease-time=1h name=\
    dhcp1
    
/ip address
add address=10.5.50.1/24 comment="hotspot network" interface=wlan1 network=\
    10.5.50.0

/ip dhcp-server network
add address=10.5.50.0/24 comment="hotspot network" gateway=10.5.50.1

/ip firewall filter
add action=passthrough chain=unused-hs-chain comment=\
    "place hotspot rules here" disabled=yes

/ip firewall nat
add action=passthrough chain=unused-hs-chain comment=\
    "place hotspot rules here" disabled=yes
add action=masquerade chain=srcnat comment="masquerade hotspot network" \
    src-address=10.5.50.0/24
    
/radius incoming
set accept=yes

/system routerboard settings
set cpu-frequency=400MHz

#
#
#
#
#    
# THESE NEXT settings need to be adapted for specific venues/servers
#
#
#
#
/ip hotspot
add address-pool=hs-pool-6 disabled=no interface=wlan1 name=\$nasid \
    profile=hsprof1

/radius
add address=$serverip secret=3xb4ndn3t service=hotspot timeout=3s

/interface wireless
# set [ find default-name=wlan1 ] band=2ghz-b/g/n channel-width=20/40mhz-Ce \
#    disabled=no distance=indoors l2mtu=1600 mode=ap-bridge ssid="$ssid"
#
#
#
#    
#END OF SPECIFIC SETTINGS
#
#

/ip hotspot user
add name=admin

/ip hotspot walled-garden
add dst-host=*.hipzone.co.za

/ip upnp
set allow-disable-external-interface=no

#REMOVE BRIDGE 
/interface bridge port remove [find bridge="bridge-local"]
/interface bridge remove [find bridge="bridge-local"]



} else ={
# 07_05_2015 by Daniel Titton
# setup for Mikrotik 951-2n
# Needs initial.rsc to have run on AP (with internet connection) locally first

#FIRST SETUP A FEW THINGS

/ip hotspot profile
add hotspot-address=10.5.50.1 login-by=http-chap,https,http-pap name=hsprof1 \
    radius-interim-update=30s use-radius=yes
    
/ip hotspot user profile
set [ find default=yes ] idle-timeout=none keepalive-timeout=2m \
    mac-cookie-timeout=3d
    
/ip pool
add name=hs-pool-6 ranges=10.5.50.2-10.5.50.254

/interface bridge
add l2mtu=1598 name=bridge1

/interface bridge port
add bridge=bridge1 interface=wlan1
add bridge=bridge1 interface=ether5

/interface wireless security-profiles
set [ find default=yes ] supplicant-identity=MikroTik
    
/ip dhcp-server
add address-pool=hs-pool-6 disabled=no interface=bridge1 lease-time=1h name=\
    dhcp1
    
/ip address
add address=10.5.50.1/24 comment="hotspot network" interface=bridge1 network=\
    10.5.50.0
    
/ip dhcp-server network
add address=10.5.50.0/24 comment="hotspot network" gateway=10.5.50.1

/ip firewall filter
add action=passthrough chain=unused-hs-chain comment=\
    "place hotspot rules here" disabled=yes
    
/ip firewall nat
add action=passthrough chain=unused-hs-chain comment=\
    "place hotspot rules here" disabled=yes to-addresses=0.0.0.0
add action=masquerade chain=srcnat comment="masquerade hotspot network" \
    src-address=10.5.50.0/24 to-addresses=0.0.0.0

/radius incoming
set accept=yes

/system leds
set 0 interface=wlan1

/system routerboard settings
set cpu-frequency=400MHz

#
#
#
#
#    
# THESE NEXT settings need to be adapted for specific venues/servers
#
#
#
#

/ip hotspot
add address-pool=hs-pool-6 disabled=no interface=bridge1 name=\$nasid \
    profile=hsprof1

/radius
add address=$serverip secret=3xb4ndn3t service=hotspot timeout=3s

/interface wireless
set [ find default-name=wlan1 ] band=2ghz-b/g/n disabled=no l2mtu=2290 mode=\
    ap-bridge ssid="$ssid"
#
#
#
#    
#END OF SPECIFIC SETTINGS
#
#

    
/ip hotspot user
add name=admin

/ip hotspot walled-garden
add dst-host=*.hipzone.co.za

/ip upnp
set allow-disable-external-interface=no}




##################################################################


EOF;

$resetcode = $this->resetCode();
$rsccontents = $rsccontents . $resetcode;

        $rscfile = $mikrotikdir->value . "deployment/" .  $nasid . ".rsc";
        $handle = fopen($rscfile, 'w');
        fwrite($handle, $rsccontents);
        fclose($handle);
        chmod($rscfile, 0666);
    

        // Create the log file  ////////////////////
//         $logcontents = <<<EOF
// This is the log file for $nasid
// EOF;

//         $logfile = $mikrotikdir->value . $nasid . ".txt";
//         error_log("logfile : $logfile");
//         $handle = fopen($logfile, 'w');
//         fwrite($handle, $logcontents);
//         fclose($handle);
//         chmod($logfile, 0666);

        return true;
    }


//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////


    public function updateVenue($venue, $old_sitename) {

        $mikrotikdir = \DB::table('systemconfig')->select("*")->where('name', '=', "mikrotikdir")->first();

        $brand = \Brand::find($venue->brand_id);

        $old_nasid = preg_replace("/ /", "_", $old_sitename);
        $new_nasid = preg_replace("/ /", "_", $venue->sitename);

        $serverhostname = \Server::find($venue->server_id)->hostname;
        $serverip = gethostbyname ( $serverhostname );


///////////// Create the login.html file ////////////////////
        $logincontents = <<<EOF
<html>
<head><title>...</title></head>
<body>
\$(if chap-id)
<noscript>
<center><b>JavaScript required. Enable JavaScript to continue.</b></center>
</noscript>
\$(endif)
<center>If you are not redirected in a few seconds, click 'continue' below<br>
<form name="redirect" action="http://$serverhostname/login" method="get">

<input type="hidden" name="res" value="notyet" />
<input type="hidden" name="nasid" value="$(identity)" />
<input type="hidden" name="mac" value="\$(mac)">
<input type="hidden" name="ip" value="\$(ip)">
<input type="hidden" name="username" value="\$(username)">
<input type="hidden" name="link-login" value="\$(link-login)">
<input type="hidden" name="link-orig" value="\$(link-orig)">
<input type="hidden" name="error" value="\$(error)">
<input type="hidden" name="chap-id" value="\$(chap-id)">
<input type="hidden" name="chap-challenge" value="\$(chap-challenge)">
<input type="hidden" name="link-login-only" value="\$(link-login-only)">
<input type="hidden" name="link-orig-esc" value="\$(link-orig-esc)">
<input type="hidden" name="mac-esc" value="\$(mac-esc)">
<input type="hidden" name="device-type" value="mikrotik">
<input type="submit" value="continue">
</form>
<script language="JavaScript">
<!--
   document.redirect.submit();
//-->
</script></center>
</body>
</html>

EOF;

        $loginfile = $mikrotikdir->value . "deployment/" .  $old_nasid . "_login.html";
        $handle = fopen($loginfile, 'w');
        fwrite($handle, $logincontents);
        fclose($handle);
        chmod($loginfile, 0666);


///////////// Create the rsc file for the new nasid ////////////////////
        $newrsccontents = <<<EOF
# This is script is intentionally empty
# Previous nasid was $old_nasid
EOF;

        $newrscfile = $mikrotikdir->value . "deployment/" .  $new_nasid . ".rsc";
        $handle = fopen($newrscfile, 'w');
        fwrite($handle, $newrsccontents);
        fclose($handle);
        chmod($newrscfile, 0666); 


///////////// Create the rsc file for the old nasid ////////////////////
        $ssid = $brand->ssid;
        $rsccontents = <<<EOF
# Get the old sytem identity
:local oldnasid [/system identity get name];

# Set the new sytem identity
/system identity set name=$new_nasid
:local nasid [/system identity get name];

###################################################################
# Set variables for uploading the login script
:local ip manage.hipzone.co.za
:local fileName ("\$oldnasid" . "_login.html");
:local filePath ("deployment/" . "\$fileName");

### Set the device type
:local device ("951-2n");
:if ([:len [/file find name="flash"]] > 0) do={ :set device "cAP-2n"; }

### Ensure login.html is set to the correct path
:local dstPath ("hotspot/login.html");
:if (\$device = "cAP-2n") do={ :set dstPath "flash/hotspot/login.html"; }

:log info "Running config script for \$oldnasid / \$nasid"
:log info ("Config ip : " . \$ip);
:log info ("Config fileName : " . \$fileName);
:log info ("Config filePath  : " . \$filePath);
:log info ("Config device  : " . \$device);
:log info ("Config dstPath  : " . \$dstPath);

# Upload the login script
/tool fetch address=\$ip src-path=\$filePath user=mikrotik mode=ftp password=mikmanage dst-path=\$dstPath keep-result=yes;
###################################################################

# Set the hotspot server name
/ip hotspot
print 
remove 0
:if (\$device="cAP-2n") do={add address-pool=hs-pool-6 disabled=no interface=wlan1 name=\$nasid profile=hsprof1;} else={add address-pool=hs-pool-6 disabled=no interface=bridge1 name=\$nasid profile=hsprof1;}

# Set the radius server address
/radius set 0 address=$serverip;

# Set the SSID
# /interface wireless set [/int wi find name=wlan1] ssid="$ssid";
/in wi set wlan1 ssid="$ssid"


EOF;

        if($new_nasid != $old_nasid) {
            $deletecode = $this->deleteCode();
            $rsccontents = $deletecode . $rsccontents;
        } else {
            $resetcode = $this->resetcode();
            $rsccontents = $rsccontents . $resetcode;            
        }

        $rscfile = $mikrotikdir->value . "deployment/" .  $old_nasid . ".rsc";
        $handle = fopen($rscfile, 'w');
        fwrite($handle, $rsccontents);
        fclose($handle);
        chmod($rscfile, 0666);



// ///////////// Create the log file  ////////////////////
//         $logcontents = <<<EOF
// This is the log file for $new_nasid
// EOF;

//         $newlogfile = $mikrotikdir->value . $new_nasid . ".txt";
//         error_log("newlogfile : $newlogfile");
//         $handle = fopen($newlogfile, 'w');
//         fwrite($handle, $logcontents);
//         fclose($handle);
//         chmod($newlogfile, 0666);

//         $oldlogfile = $mikrotikdir->value . $old_nasid . ".txt";
//         if(file_exists($oldlogfile)) {
//             rename($oldlogfile, $oldlogfile . "_");
//         }

        return true;
    }

}