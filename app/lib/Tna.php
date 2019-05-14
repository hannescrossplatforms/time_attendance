<?php 
 
class Tna{



   public static function getBeaconMessagesForStaffMemberByDate($staffmember, $currentdate) {
      $tna = new Tna();
      $begin_day = $currentdate->format('Y-m-d') . " 00:00:01";
      $end_day = $currentdate->format('Y-m-d') . " 23:59:59";

      $bm = Beaconmessage::where('external_user_id', "like", $staffmember->external_user_id)
                          ->where('created_at', '<', $end_day)
                          ->where('created_at', '<', $end_day)
                          ->where('event_code', '=', "HIPJAM0002")
                          ->orderby('created_at', 'asc')
                          ->get();

      return $bm;
   }


   public static function getPunctuality($staffmember, $currentdate) {

      // error_log("getPunctuality : " . $staffmember["external_user_id"]);

      $tna = new Tna();
      $begin_day = $currentdate->format('Y-m-d') . " 00:00:01";
      $end_day = $currentdate->format('Y-m-d') . " 23:59:59";
      // error_log("getPunctuality : begin_day : $begin_day");
      // error_log("getPunctuality : end_day : $end_day");

      $bm = Beaconmessage::select('created_at')
                          ->where('external_user_id', "like", $staffmember["external_user_id"])
                          ->where('created_at', '>', $begin_day)
                          ->where('created_at', '<', $end_day)
                          ->where('event_code', '=', "HIPJAM0002")
                          ->orderby('created_at', 'asc')
                          ->first();

      if($bm) {

        // error_log("getPunctuality created_at = " . $bm->created_at);

        $scheduledStartTime = $tna->getShiftstart($staffmember, $currentdate) . ":00";
        if($scheduledStartTime == "00:00:00") return "---";
        // error_log("getPunctuality scheduledStartTime = $scheduledStartTime 1");
        if (preg_match('/^\d:\d{2}:\d{2}$/', $scheduledStartTime)) $scheduledStartTime = "0" . $scheduledStartTime; // Just in case there is a missing leading zero - this needs to be fixed in the roster upload.
        // error_log("getPunctuality scheduledStartTime = $scheduledStartTime 2");
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $scheduledStartTime)) return "---";

        $actualStartTimeObj = new \DateTime($bm->created_at);
        $actualStartTime = $actualStartTimeObj->format('H:i:s');;
        // error_log("getPunctuality scheduledStartTime = $scheduledStartTime");
        // error_log("getPunctuality actualStartTime = $actualStartTime");
        $ast = strtotime($actualStartTime); $sst = strtotime($scheduledStartTime);
        
        $punctuality = floor(($ast - $sst) / 60);

        // error_log("getPunctuality punctuality = $punctuality");
        return $punctuality;

      } else {

        // error_log("getPunctuality bm is null");
        return null;

      }


      return 999;
   }

   public static function getAttendance($staffmember, $currentdate) {

      $tna = new Tna();

      $beaconMessages = $tna->getBeaconMessagesForStaffMemberByDate($staffmember, $currentdate);

      if(sizeof($beaconMessages) > 0) {
        return "present";
      } else {
        return "absent";
      }
      return "xxx";
   }

   public static function getProximity($staffmember, $currentdate) {

      $proximityThreshold = 11;

      $tna = new Tna();
      $beaconMessages = $tna->getBeaconMessagesForStaffMemberByDate($staffmember, $currentdate);

      $totalAwayTime = 0;
      $previousMessageTime = null;

      foreach($beaconMessages as $currentMessage) {

          $currentMessageTime = new \DateTime($currentMessage->created_at);
          if($previousMessageTime) {
            $diff = floor(abs($currentMessageTime->getTimestamp() - $previousMessageTime->getTimestamp()) / 60);
            if($diff > $proximityThreshold) $totalAwayTime = $totalAwayTime + $diff;
          }
          $previousMessageTime = $currentMessageTime;

      }

      return $totalAwayTime;
   }

   public static function cleanupTime($t) {

      $t = str_replace(" ","", $t);

      if (preg_match('/^\d:\d{2}:\d{2}$/', $t)) $t = "0" . $t;

      if (preg_match('/^\d{2}:\d{2}$/', $t)) {
        return $t;
      } else {
        return "00:00";
      }
   }

   public static function getShiftstart($staffmember, $currentdate) {

      $tna = new TnA();

      $dayofweek = $currentdate->format('l');

      $start_column = strtolower($dayofweek) . "_start";
      $startTime = $staffmember[$start_column];

      return $tna->cleanupTime($startTime);
   }

   public static function getShiftend($staffmember, $currentdate) {

      $tna = new TnA();

      $dayofweek = $currentdate->format('l');

      $end_column = strtolower($dayofweek) . "_end";
      $endTime = $staffmember[$end_column];

      return $tna->cleanupTime($endTime);
   }

   // This doesn't subtract lunch break - so 09:00-17:00 is 8 hours
   public static function getShiftduration($staffmember, $currentdate) {

      $tna = new Tna();

      $startTime = $tna->getShiftstart($staffmember, $currentdate);
      $endTime = $tna->getShiftend($staffmember, $currentdate);

      // Get the time difference
      $f = strtotime($startTime . ":00"); $t = strtotime($endTime . ":00");
      $configuredMinutes = floor(abs($t - $f) / 60);
      if(!$configuredMinutes) $configuredMinutes = 0;

      return $configuredMinutes;

   }




   public static function getLunchlength($staffmember, $currentdate) {

    $start_column = "lunch_start";
    $end_column = "lunch_end";

    $startTime = $staffmember->$start_column . ":00";
    $endTime = $staffmember->$end_column. ":00";

    $f = strtotime($startTime); $t = strtotime($endTime);
    $lunchMinutes = floor(abs($t - $f) / 60);
    if(!$lunchMinutes) $lunchMinutes = 0;

    return $lunchMinutes;

   }

   // public static function generateTnaTable() {

   //    $tna = new Tna();

   //    $startdatetext = Tnatest::max('date');
   //    $currentdate = new \DateTime($startdatetext);
   //    error_log("begin currentdate : " . $currentdate->format('Y-m-d'));

   //    $date = new \DateTime(date('Y-m-d'));
   //    $date->sub(new \DateInterval('P1D'));

   //    $staff = Staff::All();

   //    while($currentdate < $date) {
   //      $currentdate->add(new \DateInterval('P1D'));
   //      error_log("currentdate : " . $currentdate->format('Y-m-d'));

   //      foreach($staff as $staffmember) {

   //         $punctuality = $tna->getPunctuality($staffmember, $currentdate); 
   //         $attendance = $tna->getAttendance($staffmember, $currentdate);
   //         $proximity = $tna->getProximity($staffmember, $currentdate);
   //         $shift_start = $tna->getShiftstart($staffmember, $currentdate);
   //         $shift_end = $tna->getShiftend($staffmember, $currentdate);
   //         $shift_duration = $tna->getShiftduration($staffmember, $currentdate);
   //         $lunch_start = $staffmember->lunch_start;
   //         $lunch_end = $staffmember->lunch_end;
   //         $lunch_length = $tna->getLunchlength($staffmember, $currentdate);

   //         Tnatest::insert(array( "external_user_id" => $staffmember->external_user_id, 
   //                                 "date" => $currentdate,
   //                                 "punctuality" => $punctuality,
   //                                 "attendance" => $attendance,
   //                                 "proximity" => $proximity,
   //                                 "shift_start" => $shift_start,
   //                                 "shift_end" => $shift_end,
   //                                 "shift_duration" => $shift_duration,
   //                                 "lunch_start" => $lunch_start,
   //                                 "lunch_end" => $lunch_end,
   //                                 "lunch_length" => $lunch_length,
   //                                 "day_off" => 0
   //                                )); 

   //      }
   //    }
   // }
}
