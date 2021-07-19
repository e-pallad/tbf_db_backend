<?php
    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];
    $file = $_GET['file'];

    switch ($method) {
        case 'GET':
            if (file_exists($file)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Access-Control-Allow-Origin: *');
                header('Content-Length: ' . filesize($file));
                readfile('./' + $file);
                exit;
            } else {
                echo http_response_code(403);
                break;
            }
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>