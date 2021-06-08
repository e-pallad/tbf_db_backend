<?php
    include_once('../connect.php');
    $dbhost = 'localhost';
    $dbuser = 'username';
    $dbpassword = 'password';
    $dbname = 'datenbankname';
    
    $dumpfile = $dbname . "_" . date("Y-m-d_H-i-s") . ".sql";
    
    echo "Start dump\n";
    exec("mysqldump --user=$user --password=$password --host=$host $dbname > $dumpfile");
    echo "-- Dump completed -- ";
    echo $dumpfile;
?>