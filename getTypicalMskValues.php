<?php

    require './connect.php';

    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];

    $mskquery = mysqli_fetch_all($con->query("SELECT * FROM `Typical_MSK_Zuordnung`"));

    switch ($method) {
        case 'GET':
            header('Content-Type: application/json;');
            header('Access-Control-Allow-Origin: *');
            
            echo json_encode($mskquery);
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>