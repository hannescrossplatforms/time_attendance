<?php



class Utils extends Eloquent {

    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');


    public static function getTimeZonesArray() {
        return array("Africa/Abidjan","Africa/Accra","Africa/Addis_Ababa","Africa/Algiers","Africa/Asmara","Africa/Bamako","Africa/Bangui","Africa/Banjul","Africa/Bissau","Africa/Blantyre","Africa/Brazzaville","Africa/Bujumbura","Africa/Cairo","Africa/Casablanca","Africa/Ceuta","Africa/Conakry","Africa/Dakar","Africa/Dar_es_Salaam","Africa/Djibouti","Africa/Douala","Africa/El_Aaiun","Africa/Freetown","Africa/Gaborone","Africa/Harare","Africa/Johannesburg","Africa/Juba","Africa/Kampala","Africa/Khartoum","Africa/Kigali","Africa/Kinshasa","Africa/Lagos","Africa/Libreville","Africa/Lome","Africa/Luanda","Africa/Lubumbashi","Africa/Lusaka","Africa/Malabo","Africa/Maputo","Africa/Maseru","Africa/Mbabane","Africa/Mogadishu","Africa/Monrovia","Africa/Nairobi","Africa/Ndjamena","Africa/Niamey","Africa/Nouakchott","Africa/Ouagadougou","Africa/Porto-Novo","Africa/Sao_Tome","Africa/Tripoli","Africa/Tunis","Africa/Windhoek","America/Adak","America/Anchorage","America/Anguilla","America/Antigua","America/Araguaina","America/Argentina/Buenos_Aires","America/Argentina/Catamarca","America/Argentina/Cordoba","America/Argentina/Jujuy","America/Argentina/La_Rioja","America/Argentina/Mendoza","America/Argentina/Rio_Gallegos","America/Argentina/Salta","America/Argentina/San_Juan","America/Argentina/San_Luis","America/Argentina/Tucuman","America/Argentina/Ushuaia","America/Aruba","America/Asuncion","America/Atikokan","America/Bahia","America/Bahia_Banderas","America/Barbados","America/Belem","America/Belize","America/Blanc-Sablon","America/Boa_Vista","America/Bogota","America/Boise","America/Cambridge_Bay","America/Campo_Grande","America/Cancun","America/Caracas","America/Cayenne","America/Cayman","America/Chicago","America/Chihuahua","America/Costa_Rica","America/Creston","America/Cuiaba","America/Curacao","America/Danmarkshavn","America/Dawson","America/Dawson_Creek","America/Denver","America/Detroit","America/Dominica","America/Edmonton","America/Eirunepe","America/El_Salvador","America/Fortaleza","America/Glace_Bay","America/Godthab","America/Goose_Bay","America/Grand_Turk","America/Grenada","America/Guadeloupe","America/Guatemala","America/Guayaquil","America/Guyana","America/Halifax","America/Havana","America/Hermosillo","America/Indiana/Indianapolis","America/Indiana/Knox","America/Indiana/Marengo","America/Indiana/Petersburg","America/Indiana/Tell_City","America/Indiana/Vevay","America/Indiana/Vincennes","America/Indiana/Winamac","America/Inuvik","America/Iqaluit","America/Jamaica","America/Juneau","America/Kentucky/Louisville","America/Kentucky/Monticello","America/Kralendijk","America/La_Paz","America/Lima","America/Los_Angeles","America/Lower_Princes","America/Maceio","America/Managua","America/Manaus","America/Marigot","America/Martinique","America/Matamoros","America/Mazatlan","America/Menominee","America/Merida","America/Metlakatla","America/Mexico_City","America/Miquelon","America/Moncton","America/Monterrey","America/Montevideo","America/Montserrat","America/Nassau","America/New_York","America/Nipigon","America/Nome","America/Noronha","America/North_Dakota/Beulah","America/North_Dakota/Center","America/North_Dakota/New_Salem","America/Ojinaga","America/Panama","America/Pangnirtung","America/Paramaribo","America/Phoenix","America/Port-au-Prince","America/Port_of_Spain","America/Porto_Velho","America/Puerto_Rico","America/Rainy_River","America/Rankin_Inlet","America/Recife","America/Regina","America/Resolute","America/Rio_Branco","America/Santa_Isabel","America/Santarem","America/Santiago","America/Santo_Domingo","America/Sao_Paulo","America/Scoresbysund","America/Sitka","America/St_Barthelemy","America/St_Johns","America/St_Kitts","America/St_Lucia","America/St_Thomas","America/St_Vincent","America/Swift_Current","America/Tegucigalpa","America/Thule","America/Thunder_Bay","America/Tijuana","America/Toronto","America/Tortola","America/Vancouver","America/Whitehorse","America/Winnipeg","America/Yakutat","America/Yellowknife","Antarctica/Casey","Antarctica/Davis","Antarctica/DumontDUrville","Antarctica/Macquarie","Antarctica/Mawson","Antarctica/McMurdo","Antarctica/Palmer","Antarctica/Rothera","Antarctica/Syowa","Antarctica/Troll","Antarctica/Vostok","Arctic/Longyearbyen","Asia/Aden","Asia/Almaty","Asia/Amman","Asia/Anadyr","Asia/Aqtau","Asia/Aqtobe","Asia/Ashgabat","Asia/Baghdad","Asia/Bahrain","Asia/Baku","Asia/Bangkok","Asia/Beirut","Asia/Bishkek","Asia/Brunei","Asia/Choibalsan","Asia/Chongqing","Asia/Colombo","Asia/Damascus","Asia/Dhaka","Asia/Dili","Asia/Dubai","Asia/Dushanbe","Asia/Gaza","Asia/Harbin","Asia/Hebron","Asia/Ho_Chi_Minh","Asia/Hong_Kong","Asia/Hovd","Asia/Irkutsk","Asia/Jakarta","Asia/Jayapura","Asia/Jerusalem","Asia/Kabul","Asia/Kamchatka","Asia/Karachi","Asia/Kashgar","Asia/Kathmandu","Asia/Khandyga","Asia/Kolkata","Asia/Krasnoyarsk","Asia/Kuala_Lumpur","Asia/Kuching","Asia/Kuwait","Asia/Macau","Asia/Magadan","Asia/Makassar","Asia/Manila","Asia/Muscat","Asia/Nicosia","Asia/Novokuznetsk","Asia/Novosibirsk","Asia/Omsk","Asia/Oral","Asia/Phnom_Penh","Asia/Pontianak","Asia/Pyongyang","Asia/Qatar","Asia/Qyzylorda","Asia/Rangoon","Asia/Riyadh","Asia/Sakhalin","Asia/Samarkand","Asia/Seoul","Asia/Shanghai","Asia/Singapore","Asia/Taipei","Asia/Tashkent","Asia/Tbilisi","Asia/Tehran","Asia/Thimphu","Asia/Tokyo","Asia/Ulaanbaatar","Asia/Urumqi","Asia/Ust-Nera","Asia/Vientiane","Asia/Vladivostok","Asia/Yakutsk","Asia/Yekaterinburg","Asia/Yerevan","Atlantic/Azores","Atlantic/Bermuda","Atlantic/Canary","Atlantic/Cape_Verde","Atlantic/Faroe","Atlantic/Madeira","Atlantic/Reykjavik","Atlantic/South_Georgia","Atlantic/St_Helena","Atlantic/Stanley","Australia/Adelaide","Australia/Brisbane","Australia/Broken_Hill","Australia/Currie","Australia/Darwin","Australia/Eucla","Australia/Hobart","Australia/Lindeman","Australia/Lord_Howe","Australia/Melbourne","Australia/Perth","Australia/Sydney","Europe/Amsterdam","Europe/Andorra","Europe/Athens","Europe/Belgrade","Europe/Berlin","Europe/Bratislava","Europe/Brussels","Europe/Bucharest","Europe/Budapest","Europe/Busingen","Europe/Chisinau","Europe/Copenhagen","Europe/Dublin","Europe/Gibraltar","Europe/Guernsey","Europe/Helsinki","Europe/Isle_of_Man","Europe/Istanbul","Europe/Jersey","Europe/Kaliningrad","Europe/Kiev","Europe/Lisbon","Europe/Ljubljana","Europe/London","Europe/Luxembourg","Europe/Madrid","Europe/Malta","Europe/Mariehamn","Europe/Minsk","Europe/Monaco","Europe/Moscow","Europe/Oslo","Europe/Paris","Europe/Podgorica","Europe/Prague","Europe/Riga","Europe/Rome","Europe/Samara","Europe/San_Marino","Europe/Sarajevo","Europe/Simferopol","Europe/Skopje","Europe/Sofia","Europe/Stockholm","Europe/Tallinn","Europe/Tirane","Europe/Uzhgorod","Europe/Vaduz","Europe/Vatican","Europe/Vienna","Europe/Vilnius","Europe/Volgograd","Europe/Warsaw","Europe/Zagreb","Europe/Zaporozhye","Europe/Zurich","GMT","Indian/Antananarivo","Indian/Chagos","Indian/Christmas","Indian/Cocos","Indian/Comoro","Indian/Kerguelen","Indian/Mahe","Indian/Maldives","Indian/Mauritius","Indian/Mayotte","Indian/Reunion","Pacific/Apia","Pacific/Auckland","Pacific/Chatham","Pacific/Chuuk","Pacific/Easter","Pacific/Efate","Pacific/Enderbury","Pacific/Fakaofo","Pacific/Fiji","Pacific/Funafuti","Pacific/Galapagos","Pacific/Gambier","Pacific/Guadalcanal","Pacific/Guam","Pacific/Honolulu","Pacific/Johnston","Pacific/Kiritimati","Pacific/Kosrae","Pacific/Kwajalein","Pacific/Majuro","Pacific/Marquesas","Pacific/Midway","Pacific/Nauru","Pacific/Niue","Pacific/Norfolk","Pacific/Noumea","Pacific/Pago_Pago","Pacific/Palau","Pacific/Pitcairn","Pacific/Pohnpei","Pacific/Port_Moresby","Pacific/Rarotonga","Pacific/Saipan","Pacific/Tahiti","Pacific/Tarawa","Pacific/Tongatapu","Pacific/Wake","Pacific/Wallis");

    }


    public static function getEngageDbConnection() {

        // Originally currentInstance refers to the TA instance, however we will be using it for any distinctive implementation that uses Engage

        $currentInstance = Session::get('currentInstance');

        if($currentInstance == "IM" || $currentInstance == "CE") {
            return 'smollanengage';
        } else {
            return 'hipengage';
        }
    }

    public static function getHiphubDbConnection() {
        return 'hiphub';
    }

    public function getAllowedEngageBrandcodes() {
        $brand = new \Brand();
        $allowedbrands = $brand->getBrandsForEngage();
        $allowedbrandcodes = array();
        foreach($allowedbrands as $brand) {
            array_push($allowedbrandcodes, $brand->code);
        }

        return $allowedbrandcodes;
    }

    public function buildLocationCode($isp_id, $brand_id, $sitename, $countrie_id, $province_id, $citie_id) {

           $isp_code = \Isp::find($isp_id)->code;
    	$brand_code = \Brand::find($brand_id)->code;
    	$countrie_code = \Countrie::find($countrie_id)->code;
    	$province_code = \Province::find($province_id)->code;
    	$citie_code = \Citie::find($citie_id)->code;

        $remove = $vowels = array(" ", "_", "-", ":", "/", "\\", "%", "\t");
        $sitename = str_replace($remove, "", $sitename);

    	$venuecode = strtoupper($sitename);
    	$venuecode = substr ( $venuecode , 0 , 8 );
    	$pad = 8 - strlen($venuecode);
    	for($i=1; $i<=$pad; $i++) { $venuecode = "X" . $venuecode; }

    	$location = $isp_code . $brand_code . $venuecode . "XX" . $citie_code . $province_code . $countrie_code;

        $location = $this->incrementSiteNumber($location);

        return $location;
    }

    public function buildMatchLocationCode($isp_id, $brand_id, $countrie_id, $province_id, $citie_id, $venue_id) {

        error_log("buildMatchLocationCode $venue_id");
        if($venue_id) {
            return \Venue::find($venue_id)->location;

        } else {
            $isp_code = \Isp::find($isp_id)->code;
            $brand_code = \Brand::find($brand_id)->code;
            $countrie_code = \Countrie::find($countrie_id)->code;

            if($province_id) {
                error_log("buildMatchLocationCode : province_id : $province_id");
                $province_code = \Province::find($province_id)->code;
            } else {
                $province_code = "XXX";
            }

            if($citie_id) {
                $citie_code = \Citie::find($citie_id)->code;
            } else {
                $citie_code = "XXX";
            }
        }

        error_log("buildLocationCode province_code $province_code");
        error_log("buildLocationCode province_id $province_id");

        $location = $isp_code . $brand_code . "XXXXXXXXXX" . $citie_code . $province_code . $countrie_code;

        return $location;
    }

    public function updateLocationCode($sitename, $locationold) {

        $firstbit =     substr ( $locationold , 0 , 9 );
        $endbit =       substr ( $locationold , 19, 8 );

        $venuecode = strtoupper($sitename);
        $venuecode = substr ( $venuecode , 0 , 8 );
        $pad = 8 - strlen($venuecode);
        for($i=1; $i<=$pad; $i++) { $venuecode = "X" . $venuecode; }

        $location = $firstbit . $venuecode . $sitenumber . $endbit;

        $location = $this->incrementSiteNumber($location);

        return $location;
    }

    public function incrementSiteNumber($location) {

        $indexes = array();
        $firstbit   =   substr ( $location , 0 , 17 );
        $endbit     =   substr ( $location , 19, 8 );

        $condition = $firstbit . "__" . $endbit;

        $venues = \DB::table('venues')
                ->select("location")
                ->where('location', 'like', $condition)
                ->get();

        foreach($venues as $venue) {
            $index = (int)substr ( $venue->location , 17 , 2 );
            array_push($indexes, $index);
        }

        if($indexes) {
            $maxindex = max($indexes);
            $newindex = $maxindex + 1;
            $newindexpadded = sprintf("%02d", $newindex);
        } else {
            $newindexpadded = "01";
        }

        return $firstbit . $newindexpadded . $endbit;

    }

}