<?php 
    require './connect.php';

    /* */
    
        error_reporting(-1);
        ini_set("display_errors", "1");
        ini_set("log_errors", 1);
        ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");
    
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');

            $table = $_POST['table'];

            if ($table == 'RI-TBF_SEF_Elektrokomponentenliste') {
                $dataArray = json_decode($_POST['data'], 1);
                $dataArray["TableID"] = 4;
                // Check if TBF_ID already exist
                $check = $con->query("SELECT `TBF_ID` FROM `Gesamtdatenbank` WHERE `TBF_ID` = '" . $dataArray['TBF_ID'] . "'");
                // If not perform INSERT
                if ($check->num_rows == 0) {
                    $i = 0;
                    $values = "";
                    $keys = "";
                    foreach ($dataArray as $key => $value) {
                        $values .= "'" . $value . "'";
                        $keys .= "`" . $key . "`";

                        if ($i < count($dataArray) - 1) {
                            $values .= ",";
                            $keys .= ",";
                        }
                        $i++;
                    }

                    $query = "INSERT INTO `Gesamtdatenbank` (" . $keys . ") VALUES (" . $values . ")";
                } else {
                // Else perform UPDATE
                    $query = "UPDATE `Gesamtdatenbank` SET ";

                    $i = 0;
                    foreach ($dataArray as $key => $value) {
                        if ($key == "TBF_ID") {
                            $where = " WHERE `TBF_ID`=$value";
                            continue;
                        } 
                        if (!empty($value) || $value === "0") {
                            $query .= "`". $key . "`" . " = '" . $value . "'";
                        } else {
                            $query .= "`". $key . "`" . " = NULL";
                        }
                        
                        if ($i < count($dataArray) - 2) {
                            $query.= ",";
                        }
                        $i++;
                    }
                    $query .= $where;
                }
            } else {
                $dataArray = json_decode($_POST['data'], 1);
                $query = "UPDATE `Gesamtdatenbank` SET ";

                $i = 0;
                foreach ($dataArray as $key => $value) {
                    if ($key == "TBF_ID") {
                        $where = " WHERE `TBF_ID`=$value";
                        continue;
                    } 
                    
                    if (!empty($value) || $value === "0") {
                        $query .= "`". $key . "`" . " = '" . $value . "'";
                    } else {
                        $query .= "`". $key . "`" . " = NULL";
                    }
                    
                    if ($i < count($dataArray) - 2) {
                        $query.= ",";
                    }
                    $i++;
                }

                if ($where) {
                    $query .= $where;
                } else {
                    $query .= " WHERE 1";
                }

            }

            $insert = $con->query($query);
            if ($insert) {
                $statusMsg[] = "Erfolgreich $con->affected_rows Zeilen importiert";
            } else {
                $statusMsg[] = "Fehlgeschlagen: " . $con->error;
                $statusMsg[] = $query;
                $statusMsg[] = $dataArray;
            };

            echo json_encode($statusMsg);
            break;
        default:
            echo http_response_code(403);
            break;
    }

?>