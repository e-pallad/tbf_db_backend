<?php 

    require './connect.php';

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            $username = $_POST['username'];
            $password = $_POST['password'];
            if (!isset($username) || !isset($password)) {
                header($_SERVER["SERVER_PROTOCOL"] . ' 401 Unauthorized');
                header("Access-Control-Allow-Origin: *");
                echo 'Username/Password not set!';
                exit;
            } else {
                $options = [
                    'cost' => 11
                ];
                $userData = mysqli_fetch_array($con->query("SELECT * FROM `Benutzer` WHERE `username` = '" . $username . "'"));
                if (password_verify($password, $userData['password'])) {
                    header("Access-Control-Allow-Origin: *");
                    echo json_encode(bin2hex(random_bytes(20)));
                    break;
                } else {
                    header($_SERVER["SERVER_PROTOCOL"] . ' 401 Unauthorized');
                    header("Access-Control-Allow-Origin: *");
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