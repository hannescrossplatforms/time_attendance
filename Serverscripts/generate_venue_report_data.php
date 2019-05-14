<?php

$contents = <<<EOF
insert into partner_copy (id, quickie_id, tracker, naslookup_id, sitename, answer, macaddress, ispuser, is_havent, created_at) values
EOF;

$sqlfile = "./generate_venue_report_data.sql";
$handle = fopen($sqlfile, 'w');

$locations = array("HIPXKAUAIGCWTESTU01PTYXGTZA", "HIPXKAUAIGREENPOINTCPTXWCZA", "HIPXKAUAIGREENSTONEJNBXGTZA", "HIPXKAUAIHATFIELD01PTYXGTZA", "HIPXKAUAIHILLCRES01DURXNLZA", "HIPXKAUAIINITIAL_01CPTXWCZA", "HIPXKAUAILALUCIA201DURXNLZA", "HIPXKAUAILONGSTREETCPTXWCZA", "HIPXKAUAIMELROSEARCJNBXGTZA", "HIPXKAUAIRONDEBOSCHCPTXWCZA", "HIPXKAUAIROSEBANK01JNBXGTZA", "HIPXKAUAISEAPOINT01CPTXWCZA", "HIPXKAUAISHORTMARKTCPTXWCZA", "HIPXKAUAISTELLBOSCHCPTXWCZA", "HIPXKAUAITYGERVALLYCPTXWCZA", "HIPXKAUAIVICENTPARKPLZXECZA", "HIPXKAUAIVINCENTPRKELSXECZA", "HIPXKAUAIWALMERPARKPLZXECZA", "HIPXKAUAIWESTVILLEMDURXNLZA", "HIPXKAUAIXBLUEROUTECPTXWCZA", "HIPXKAUAIXCANALWALKCPTXWCZA", "HIPXKAUAIXCAVENDISHCPTXWCZA", "HIPXKAUAIXKILLARNEYJNBXGTZA", "HIPXKAUAIXLOCHLOGANBFNXFSZA", "HIPXKAUAIXPAVILLIONJNBXGTZA", "HIPXKAUAIXWESTVILLEDURXNLZA", "HIPXKAUAIXXCAMPSBAYCPTXWCZA", "HIPXKAUAIXXDARE7401CPTXWCZA", "HIPXKAUAIXXEASTGATEPTYXGTZA", "HIPXKAUAIXXGCWTTT01DURXNLZA", "HIPXKAUAIXXGCWXXX01DURXNLZA", "HIPXKAUAIXXMUSGRAVEDURXNLZA", "HIPXKAUAIXXUMHLANGADURXNLZA", "HIPXKAUAIXXXBALLITODURXNLZA", "HIPXKAUAIXXXBAYSIDECPTXWCZA", "HIPXKAUAIXXXBENMOREJNBXGTZA", "HIPXKAUAIXXXGATEWAYDURXNLZA", "HIPXKAUAIXXXLALUCIADURXNLZA", "HIPXKAUAIXXXSANDTONJNBXGTZA", "HIPXKAUAIXXXXCRESTAJNBXGTZA", "HIPXKAUAIXXXXMENLYNPTYXGTZA", "HIPXKAUAIXXXXTEST04CPTXWCZA", "HIPXKAUAIXXXXXCEDARJNBXGTZA", "HIPXKAUAIXXXXXWEDGEJNBXGTZA");
// $locations = array("HIPXKAUAIGCWTESTU01PTYXGTZA", "HIPXKAUAIGREENPOINTCPTXWCZA", "HIPXKAUAIGREENSTONEJNBXGTZA");
$q89 = array("male", "female");
$q90 = array("Under 18","18-22", "23-29", "30-45", "46-60", "Over 60");
$q140 = array("Bloemfontein", "Cape Town", "Durban", "Johannesburg", "Pretoria", "Other in SA", "Other outside SA", );
$q143 = array("Less than R30 000", "R30 000 - R40 000", "More than R40 000");

$date = new DateTime(date('Y-m-d'));
$today =  $date->format('Y-m-d');
$date->sub(new DateInterval('P2D')); 
$startDate = $date->format('Y-m-d');

$ispuserprefix = "venuetestuser";
$ispusernum = 1;
$id = 0;

for($day = 2; $day > 0; $day--) {
    $date->add(new DateInterval('P1D'));
    $nextDay =  $date->format('Y-m-d');
    echo "nextDay = $nextDay \n";

	foreach($locations as $location) {
		echo "location = $location ::: ";

		$numrespondents = rand(1, 20);
		// echo "numrespondents = $numrespondents ::: \n";
		for($i = $numrespondents; $i > 0; $i--) {
			$ispuser = $ispuserprefix . $ispusernum++;

			$index89 = rand(0,1); $id++;
			$answer = $q89[$index89];
			$row89 = "($id, 89, 'xxxxxxxxxxxxx', 0, '$location', '$answer', 'AA:AA:AA:AA:AA:AA', '$ispuser', 0, '$nextDay'),\n";
			
			$index90 = rand(0,5); $id++;
			$answer = $q90[$index90];
			$row90 = "($id, 90, 'xxxxxxxxxxxxx', 0, '$location', '$answer', 'AA:AA:AA:AA:AA:AA', '$ispuser', 0, '$nextDay'),\n";
			
			$index140 = rand(0,6); $id++;
			$answer = $q140[$index140];
			$row140 = "($id, 140, 'xxxxxxxxxxxxx', 0, '$location', '$answer', 'AA:AA:AA:AA:AA:AA', '$ispuser', 0, '$nextDay'),\n";
			
			$index143 = rand(0,2); $id++;
			$answer = $q143[$index143];
			$row143 = "($id, 143, 'xxxxxxxxxxxxx', 0, '$location', '$answer', 'AA:AA:AA:AA:AA:AA', '$ispuser', 0, '$nextDay'),\n";

			$contents = $contents . $row89 . $row90 . $row140 . $row143;
			$contents = substr_replace($contents,";",0,-1);
		}
	}
}

fwrite($handle, $contents);
fclose($handle);

?>