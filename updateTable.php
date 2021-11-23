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
                $dataArray["EKL"] = 1;
                // Check if TBF_ID already exist
                $check = $con->query("SELECT `TBF_ID` FROM `Gesamtdatenbank` WHERE `TBF_ID` = '" . $dataArray['TBF_ID'] . "'");
                // If not perform INSERT
                if ($check->num_rows == 0) {
                    $i = 0;
                    $values = "";
                    $keys = "";
                    foreach ($dataArray as $key => $value) {
                        // Check for comma seperated float values and convert to dot seperated if so
                        if (preg_match('/([0-9]+.*),([0-9]{0,2})/', $value, $number)) {
                            $value = str_replace('.', '', $number[1]) . "." . $number[2];
                        }
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
                            // Check for comma seperated float values and convert to dot seperated if so
                            if (preg_match('/([0-9]+.*),([0-9]{0,2})/', $value, $number)) {
                                $value = str_replace('.', '', $number[1]) . "." . $number[2];
                            }
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

                // If an Update to PlancalNova-Liste is happening switch array keys of Zeichnung to R&I EB68-Nr.
                if ($table === "RI-TBF_SEF_PlancalNova_Liste") {
                    $dataArray["R&I EB68-Nr."] = $dataArray["Zeichnung"];
                    unset($dataArray["Zeichnung"]);
                }

                // If an Update to Revit-Liste is happening switch array keys accordingly
                if ($table === "RI-TBF_SEF_Revit_Liste") {
                    if (array_key_exists("Plannummer R+I", $dataArray)) {
                        $dataArray["R&I EB68-Nr."] = $dataArray["Plannummer R+I"];
                        unset($dataArray["Plannummer R+I"]);
                    }
                    if (array_key_exists("Typ", $dataArray)) {
                        $dataArray["Typ"] = $dataArray["Revit_Type"];
                        unset($dataArray["Revit_Type"]);
                    }
                }

                if ($table === "RI-TBF_SEF_Rohrleitungsliste") {
                    if (array_key_exists("Bezeichnung", $dataArray)) {
                        $dataArray["RLL_Bezeichnung"] = $dataArray["Bezeichnung"];
                        unset($dataArray["Bezeichnung"]);
                    }
                    if (array_key_exists("Kurzbezeichnung", $dataArray)) {
                        $dataArray["RLL_Kurzbezeichnung"] = $dataArray["Kurzbezeichnung"];
                        unset($dataArray["Kurzbezeichnung"]);
                    }
                    if (array_key_exists("Von Aggregat, Armatur, Rohrltg., (AKZ)", $dataArray)) {
                        $dataArray["Von Aggregat"] = $dataArray["Von Aggregat, Armatur, Rohrltg., (AKZ)"];
                        unset($dataArray["Von Aggregat, Armatur, Rohrltg., (AKZ)"]);
                    }
                    if (array_key_exists("Nach Aggregat, Armatur, Rohrltg., (AKZ)", $dataArray)) {
                        $dataArray["Nach Aggregat"] = $dataArray["Nach Aggregat, Armatur, Rohrltg., (AKZ)"];
                        unset($dataArray["Nach Aggregat, Armatur, Rohrltg., (AKZ)"]);
                    }
                    if (array_key_exists("Durchmesser (DN, DA)", $dataArray)) {
                        $dataArray["Durchmesser"] = $dataArray["Durchmesser (DN, DA)"];
                        unset($dataArray["Durchmesser (DN, DA)"]);
                    }
                    if (array_key_exists("Länge (m)", $dataArray)) {
                        $dataArray["Länge [m]"] = $dataArray["Länge (m)"];
                        unset($dataArray["Länge (m)"]);
                    }
                }

                $query = "UPDATE `Gesamtdatenbank` SET ";

                $i = 0;
                foreach ($dataArray as $key => $value) {
                    if ($key == "TBF_ID") {
                        $where = " WHERE `TBF_ID`=$value";
                        continue;
                    } 
                    
                    if (!empty($value) || $value === "0") {
                        // Check for comma seperated float values and convert to dot seperated if so
                        if (preg_match('/([0-9]+.*),([0-9]{0,2})/', $value, $number)) {
                            $value = str_replace('.', '', $number[1]) . "." . $number[2];
                        }
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
                $statusMsg[] = $query;
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