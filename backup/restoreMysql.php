<?php
    include_once('../connect.php');

    $file = $_POST['backup'];

    $sql = file_get_contents($file);
    $mysqli = new mysqli($host, $user, $password, $dbname);

    /* execute multi query */
    $mysqli->multi_query($sql);
?>