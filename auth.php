<?php 

    require './connect.php';

    $method = $_SERVER['REQUEST_METHOD'];
    header('Access-Control-Allow-Origin: * always');

    switch ($method) {
        case 'OPTIONS':
            header("Access-Control-Allow-Origin: https://tbf-db.ep-projekte.de");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range");
            header("Access-Control-Max-Age: 1728000");
            echo http_response_code(204);
        case 'POST':
            $username = $_POST['username'];
            $password = $_POST['password'];
            if (!isset($username) || !isset($password)) {
                header($_SERVER["SERVER_PROTOCOL"] . ' 401 Unauthorized');
                header("Access-Control-Allow-Origin: https://tbf-db.ep-projekte.de");
                echo 'Username/Password not set!';
                exit;
            } else {
                $options = [
                    'cost' => 11
                ];
                $userData = mysqli_fetch_array($con->query("SELECT * FROM `Benutzer` WHERE `username` = '" . $username . "'"));
                if (password_verify($password, $userData['password'])) {
                    header("Access-Control-Allow-Origin: https://tbf-db.ep-projekte.de/login");
                    echo json_encode(bin2hex(random_bytes(20)));
                    break;
                } else {
                    header($_SERVER["SERVER_PROTOCOL"] . ' 401 Unauthorized');
                    header("Access-Control-Allow-Origin: https://tbf-db.ep-projekte.de");
                    echo 'Username/Password wrong!';
                    break;
                exit;
                }
            }
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>