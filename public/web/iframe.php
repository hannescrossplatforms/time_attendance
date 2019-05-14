<?php


 // echo file_get_contents("https://www.facebook.com/pages/Hipzone-Free-Wifi/1411609189100406"); 

$ch = curl_init("https://www.facebook.com/pages/Hipzone-Free-Wifi/1411609189100406");
  curl_setopt( $ch, CURLOPT_POST, false );
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
  curl_setopt( $ch, CURLOPT_HEADER, false );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  $data = curl_exec( $ch );
  echo $data;


  ?>