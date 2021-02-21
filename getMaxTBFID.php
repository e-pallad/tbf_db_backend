<?php
    require './connect.php';

    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];

    $query = "SELECT MAX(TBF_ID) FROM Gesamtdatenbank";

    switch ($method) {
        case 'GET':
            header('Content-Type: application/json;');
            header('Access-Control-Allow-Origin: *');

            $result = mysqli_fetch_all($con->query($query));
            echo json_encode($result[0][0]);
            break;
        default:
            echo http_response_code(403);
            break;
    }

?>