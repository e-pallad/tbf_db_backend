<?php
    require './connect.php';

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            $listdbtables = mysqli_fetch_all($con->query('SELECT `Tabelle`, `Alias`, `Importieren`, `Bearbeiten`, `Auswerten`, `Export`, `Erzeugen` FROM `Tabellenzuordnung`'));
            echo json_encode($listdbtables);
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>