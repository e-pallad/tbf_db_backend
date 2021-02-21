<?php 
    $host = "localhost"; 
    $user = "tbf_db_admin"; 
    $password = "1p@9wl4R"; 
    $dbname = "tbf_db";

    $con = mysqli_connect($host, $user, $password, $dbname);
    mysqli_options($con, MYSQLI_OPT_LOCAL_INFILE, true);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error() . PHP_EOL);
    }
?>