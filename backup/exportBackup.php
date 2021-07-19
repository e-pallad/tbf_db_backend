<?php
    $method = $_SERVER['REQUEST_METHOD'];
    $file = $_GET['file'];

    switch ($method) {
        case 'GET':
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Access-Control-Allow-Origin: *');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
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