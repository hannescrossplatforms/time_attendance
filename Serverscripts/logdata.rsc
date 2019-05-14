:log info "Running logdata script"

:local nasid [/system identity get name];
:local mac [/interface ethernet get 0 mac-address];
:local gateway [ip dhcp-client get 0 gateway ];
:local uptime [/system resource get uptime];
:local ssid [/int wireless get wlan1 ssid];
:local nasid [/system identity get name]

/tool fetch url="http://myip.dnsomatic.com/mypublicip.txt" mode=http
:local externalIP [file get mypublicip.txt contents ]


:local bytes;
/queue simple;
:foreach i in=[find dynamic=yes] do={
:set bytes [get $i bytes];
:log info ("i : " . "$i");
:log info ("bytes : " . "$bytes");
}

/log info "CheckIn : got nasid : $nasid";
/log info "CheckIn got mac : $mac";
/log info "CheckIn got gateway : $gateway";
/log info "CheckIn got externalIP $externalIP";
/log info "CheckIn got uptime $uptime";
/log info "CheckIn got bytes $bytes";
/log info "CheckIn got ssid $ssid";
/log info "CheckIn got nasid $nasid";


:local ip manage.hipzone.co.za
:local fileContents;
:local fileName ("$nasid" . ".txt");
:local destPath ("log/" . "$nasid" . ".txt");
:set fileContents ("Log data for : " . $fileName . "\n");
:set fileContents ($fileContents . "nasid\t$nasid\n");
:set fileContents ($fileContents . "mac\t$mac\n");
:set fileContents ($fileContents . "gateway\t$gateway\n");
:set fileContents ($fileContents . "externalIP\t$externalIP\n");
:set fileContents ($fileContents . "uptime\t$uptime\n");
:set fileContents ($fileContents . "bytes\t$bytes\n");
:set fileContents ($fileContents . "ssid\t$ssid\n");
:set fileContents ($fileContents . "nasid\t$nasid\n");

/file print file=$fileName;

:log info ("fileName : " . $fileName);

/file set $fileName contents=$fileContents;

:log info ("fileContents; : " . $fileContents);

/tool fetch address=$ip src-path=$fileName user=mikrotik mode=ftp password=mikmanage dst-path=$destPath upload=yes;
