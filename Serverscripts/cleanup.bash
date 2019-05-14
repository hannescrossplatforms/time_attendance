#!/bin/bash

touch /home/mikrotik/deployment/dummy.txt
touch /home/mikrotik/deployment/dummy.delete
touch /home/mikrotik/deployment/dummy.reset
touch /home/mikrotik/deployment/dummy_login.html


$basepath="/home/mikrotik";
$deploymentpath="$basepath/deployment";
$logpath="$basepath/logpath";
NOW=$(date +"%m-%d-%Y : %H:%M:%S");

cleanup=/home/mikrotik/log/cleanup.log;
echo $NOW >> $cleanup

for resetfile in $(find /home/mikrotik/deployment/*.reset -type f)
do
  fullpathnasid=${resetfile%.reset}
  nasid=${fullpathnasid##*/}
  echo "fullpathnasid : $fullpathnasid";
  echo "nasid : $nasid";
  rscfile="$fullpathnasid.rsc"
  loginsuffix="_login.html";
  loginfile=$fullpathnasid$loginsuffix;

  echo "overwriting rsc : $rscfile"  >> $cleanup;
  echo "# Empty script file " > $rscfile

  echo "deleting loginfile : $loginfile"  >> $cleanup;
  rm $loginfile

  echo "deleting reset : $resetfile"  >> $cleanup;
  rm $resetfile
done
touch /home/mikrotik/deployment/dummy_login.html

logfiledir="/home/mikrotik";
for deletefile in $(find /home/mikrotik/deployment/*.delete -type f)
do
  fullpathnasid=${deletefile%.delete}
  rscfile="$fullpathnasid.rsc"
  loginsuffix="_login.html";
  loginfile=$fullpathnasid$loginsuffix;
  logfile="$logfiledir$fullpathnasid.txt"

  echo "deleting log file : $logfile"  >> $cleanup;
  rm $logfile

  echo "deleting rsc : $rscfile"  >> $cleanup;
  rm $deletefile

  echo "deleting login : $loginfile"  >> $cleanup;
  rm $loginfile

  echo "deleting del : $deletefile"  >> $cleanup;
  rm $rscfile
done

touch /home/mikrotik/deployment/dummy.txt
touch /home/mikrotik/deployment/dummy.delete
touch /home/mikrotik/deployment/dummy.reset
touch /home/mikrotik/deployment/dummy_login.html