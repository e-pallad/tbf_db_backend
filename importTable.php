<?php 
    require './connect.php';
    include './libs/SimpleXLSX.php';

    /* 
        https://askubuntu.com/a/767534
    */
    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    

    ini_set('mysql.allow_local_infile', 1);

    $method = $_SERVER['REQUEST_METHOD'];
    $table = $_POST['table'];
    
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $sourceFile = "./uploads/" . $fileName;
    $tableActivator = 1;
    $updateCount = 0;
    $insertCount = 0;

    function duplicateValues(&$item, $value) {
        $item = $item . "=VALUES(" . $item . ")";
    }

    if ($table == 'RI-TBF_SEF_Apparateliste') {
        $tableSelector = 'APP';
    } elseif ($table == 'RI-TBF_SEF_Armaturenliste' || $table == 'SEF_Armaturenliste') {
        $tableSelector = 'ARM';
    } elseif ($table == 'RI-TBF_SEF_Messstellenliste' || $table == 'SEF_Messstellenliste') {
        $tableSelector = 'MES';
    } elseif ($table == 'RI-TBF_SEF_Elektrokomponentenliste') {
        $tableSelector = 'EKL';
    } elseif ($table == 'RI-TBF_SEF_Elektroangaben') {
        $tableSelector = 'EAN';
    } elseif ($table == 'RI-TBF_SEF_Stoffstromliste') {
        $tableSelector = 'SSL';
    } elseif ($table == 'RI-TBF_SEF_Revit_Liste') {
        $tableSelector = 'REV';
    } elseif ($table == 'SEF_Ausrüstungsliste') {
        $tableSelector = 'AUL';
    } elseif ($table == 'RI-TBF_SEF_Verbraucherliste' || $table == 'SEF_E-Verbraucherliste') {
        $tableSelector = 'VBL';
    } elseif ($table == 'RI-TBF_SEF_PlancalNova_Liste') {
        $tableSelector = 'PLA';
    } else {
        $tableSelector = NULL;
    }
    
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        if ($xlsx = SimpleXLSX::parse($sourceFile)) {

            $colNames = $rows = [];

            $importArray = $xlsx->rows();

            if ($table === 'RI-TBF_SEF_Revit_Liste') {
                // Remove row 1 from import
                array_splice($importArray, 0, 1);
                // Remove row 3 & 4 from import
                array_splice($importArray, 1, 2);

                if (in_array("Typ", $importArray[0])) {
                    $importArray[0][0] = "Revit_ID";
                    $importArray[0][2] = "Revit_Type";
                    $importArray[0][4] = "Länge [m]";
                    $importArray[0][6] = "Höhe";
                    $importArray[0][9] = "TBF_ID";
                }
            } 

            foreach ($importArray as $k => $r) {
                if ( $k === 0 ) {
                    $colNames = $r;
                    if (!is_null($tableSelector)) {
                        array_unshift($colNames,$tableSelector);
                    }
                    continue;
                }

                if (!is_null($tableSelector)) {
                    array_unshift($r, $tableActivator);
                }
                

                $rows[] = array_combine( $colNames, $r );
            }
            
            foreach ($rows as $row) {
                if (array_keys($row)[count($row) - 1] == null || empty(array_keys($row)[count($row) - 1])) {
                    array_pop($row);
                }
                if (isset($row["PnPID"])) {
                    if (isset($row["TBF_ID"])) {
                        unset($row["TBF_ID"]);
                    }
                    if ($tableActivator) {
                        $row[$tableSelector] = $tableActivator;
                    } 
                    
                    // create special case for Rohrleitungsliste
                    if ($table === 'RI-TBF_SEF_Rohrleitungsliste') {
                        // Removing col 'Bezeichnung' and move content to 'RLL_Bezeichnung'
                        if (isset($row["Bezeichnung"])) {
                            $row["RLL_Bezeichnung"] = $row["Bezeichnung"];
                            unset($row["Bezeichnung"]);
                        }

                        // Removing col 'Kurzbezeichnung' and move content to 'RLL_Kurzbezeichnung'
                        if (isset($row["Kurzbezeichnung"])) {
                            $row["RLL_Kurzbezeichnung"] = $row["Kurzbezeichnung"];
                            unset($row["Kurzbezeichnung"]);
                        }

                        // Removing col 'Von Aggregat, Armatur, Rohrltg., (AKZ)' and move content to 'Von Aggregat'
                        if (isset($row["Von Aggregat, Armatur, Rohrltg., (AKZ) "])) {
                            $row["Von Aggregat"] = $row["Von Aggregat, Armatur, Rohrltg., (AKZ) "];
                            unset($row["Von Aggregat, Armatur, Rohrltg., (AKZ) "]);
                        }

                        // Removing col 'Nach Aggregat, Armatur, Rohrltg., (AKZ)' and move content to 'Nach Aggregat'
                        if (isset($row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "])) {
                            $row["Nach Aggregat"] = $row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "];
                            unset($row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "]);
                        }

                        // Removing col 'Durchmesser (DN, DA)' and move content to 'Durchmesser'
                        if (isset($row["Durchmesser (DN, DA)"])) {
                            $row["Durchmesser"] = $row["Durchmesser (DN, DA)"];
                            unset($row["Durchmesser (DN, DA)"]);
                        }

                        // Removing col 'Länge (m)' and move content to 'Länge [m]'
                        if (isset($row["Länge (m)"])) {
                            $row["Länge [m]"] = $row["Länge (m)"];
                            unset($row["Länge (m)"]);
                        }
                    }

                    // Removing col 'Leistung mit Gleichzeitigkeitsfaktor' from imports
                    if (isset($row["Leistung mit Gleichzeitigkeitsfaktor"])) {
                        unset($row["Leistung mit Gleichzeitigkeitsfaktor"]);
                    }

                    // Removing col 'Bezeichnung' and move content to 'Benennung'
                    if (isset($row["Bezeichnung"])) {
                        $row["Benennung"] = $row["Bezeichnung"];
                        unset($row["Bezeichnung"]);
                    }

                    // Removing empty rows'
                    if (isset($row[""])) {
                        unset($row[""]);
                    }
                
                    // Check if PnPID already exist
                    $check = $con->query("SELECT `PnPID` FROM `Gesamtdatenbank` WHERE `PnPID` = '" . $row['PnPID'] . "'");
                    // If so build UPDATE query
                    if ($check->num_rows > 0) {
                        $query = "UPDATE `Gesamtdatenbank` SET ";

                        $i = 0;
                        foreach ($row as $key => $value) {
                            if ($key == "PnPID") {
                                $where = " WHERE `PnPID`=$value";
                                continue;
                            } 
                            if ($value === '') {
                                $query .= "`". $key . "` = NULL";
                            } else {
                                if (preg_match('/([0-9]+.*),([0-9]{0,2})/', $value, $number)) {
                                    $value = str_replace('.', '', $number[1]) . "." . $number[2];
                                }
                                $query .= "`". $key . "`" . " = '" . $value . "'";
                            }
                            
                            if ($i < count($row) - 2) {
                                $query.= ",";
                            }
                            $i++;
                        }

                        if ($where) {
                            $query .= $where;
                        } else {
                            $query .= " WHERE 1";
                        }
                        // Do UPDATE and check response
                        if ($con->query($query)) {
                            $updateCount++;
                            continue;
                        } else {
                            $statusMsg[] = "UPDATE failed!";
                            $statusMsg[] = $row;
                            $statusMsg[] = $query;
                            $statusMsg[] = $con->info;
                            $statusMsg[] = $con->error;
                            break;
                        }
                    } else {
                        // create special case for Rohrleitungsliste
                        if ($table === 'RI-TBF_SEF_Rohrleitungsliste') {
                            // Removing col 'Bezeichnung' and move content to 'RLL_Bezeichnung'
                            if (isset($row["Bezeichnung"])) {
                                $row["RLL_Bezeichnung"] = $row["Bezeichnung"];
                                unset($row["Bezeichnung"]);
                            }

                            // Removing col 'Kurzbezeichnung' and move content to 'RLL_Kurzbezeichnung'
                            if (isset($row["Kurzbezeichnung"])) {
                                $row["RLL_Kurzbezeichnung"] = $row["Kurzbezeichnung"];
                                unset($row["Kurzbezeichnung"]);
                            }

                            // Removing col 'Von Aggregat, Armatur, Rohrltg., (AKZ)' and move content to 'Von Aggregat'
                            if (isset($row["Von Aggregat, Armatur, Rohrltg., (AKZ) "])) {
                                $row["Von Aggregat"] = $row["Von Aggregat, Armatur, Rohrltg., (AKZ) "];
                                unset($row["Von Aggregat, Armatur, Rohrltg., (AKZ) "]);
                            }

                            // Removing col 'Nach Aggregat, Armatur, Rohrltg., (AKZ)' and move content to 'Nach Aggregat'
                            if (isset($row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "])) {
                                $row["Nach Aggregat"] = $row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "];
                                unset($row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "]);
                            }

                            // Removing col 'Durchmesser (DN, DA)' and move content to 'Durchmesser'
                            if (isset($row["Durchmesser (DN, DA)"])) {
                                $row["Durchmesser"] = $row["Durchmesser (DN, DA)"];
                                unset($row["Durchmesser (DN, DA)"]);
                            }

                            // Removing col 'Länge (m)' and move content to 'Länge [m]'
                            if (isset($row["Länge (m)"])) {
                                $row["Länge [m]"] = $row["Länge (m)"];
                                unset($row["Länge (m)"]);
                            }
                        }

                        // Removing empty rows'
                        if (isset($row[""])) {
                            unset($row[""]);
                        }

                        // If PnPID doesnt exist perform default INSERT
                        $cols = "`" . implode("`,`", array_keys($row)) . "`";
                        $values = "'" . implode("','", array_values($row)) . "'";

                        (array_values($row) == "True") ? 1 : 2;
                        $values = str_replace("''", "NULL", $values);

                        $duplicates = explode(",", $cols);
                        array_walk($duplicates, "duplicateValues");

                        $query = "INSERT INTO `Gesamtdatenbank` ($cols) VALUES($values) ON DUPLICATE KEY UPDATE " . implode(",", $duplicates);

                        if ($con->query("INSERT INTO `Gesamtdatenbank` ($cols) VALUES($values) ON DUPLICATE KEY UPDATE " . implode(",", $duplicates))) {
                            $insertCount++;
                            continue;
                        } else {
                            $statusMsg[] = "INSERT failed!";
                            $statusMsg[] = $row;
                            $statusMsg[] = $query;
                            $statusMsg[] = $con->info;
                            $statusMsg[] = $con->error;
                            break;
                        }
                    }
                } else {
                    // create special case for Rohrleitungsliste
                    if ($table === 'RI-TBF_SEF_Rohrleitungsliste') {
                        // Removing col 'Bezeichnung' and move content to 'RLL_Bezeichnung'
                        if (isset($row["Bezeichnung"])) {
                            $row["RLL_Bezeichnung"] = $row["Bezeichnung"];
                            unset($row["Bezeichnung"]);
                        }

                        // Removing col 'Kurzbezeichnung' and move content to 'RLL_Kurzbezeichnung'
                        if (isset($row["Kurzbezeichnung"])) {
                            $row["RLL_Kurzbezeichnung"] = $row["Kurzbezeichnung"];
                            unset($row["Kurzbezeichnung"]);
                        }

                        // Removing col 'Von Aggregat, Armatur, Rohrltg., (AKZ)' and move content to 'Von Aggregat'
                        if (isset($row["Von Aggregat, Armatur, Rohrltg., (AKZ) "])) {
                            $row["Von Aggregat"] = $row["Von Aggregat, Armatur, Rohrltg., (AKZ) "];
                            unset($row["Von Aggregat, Armatur, Rohrltg., (AKZ) "]);
                        }

                        // Removing col 'Nach Aggregat, Armatur, Rohrltg., (AKZ)' and move content to 'Nach Aggregat'
                        if (isset($row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "])) {
                            $row["Nach Aggregat"] = $row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "];
                            unset($row["Nach Aggregat, Armatur, Rohrltg., (AKZ)  "]);
                        }

                        // Removing col 'Durchmesser (DN, DA)' and move content to 'Durchmesser'
                        if (isset($row["Durchmesser (DN, DA)"])) {
                            $row["Durchmesser"] = $row["Durchmesser (DN, DA)"];
                            unset($row["Durchmesser (DN, DA)"]);
                        }

                        // Removing col 'Länge (m)' and move content to 'Länge [m]'
                        if (isset($row["Länge (m)"])) {
                            $row["Länge [m]"] = $row["Länge (m)"];
                            unset($row["Länge (m)"]);
                        }
                    }

                    // Removing col 'Bezeichnung' and move content to 'Benennung'
                    if (isset($row["Bezeichnung"])) {
                        $row["Benennung"] = $row["Bezeichnung"];
                        unset($row["Bezeichnung"]);
                    }

                    // Removing col 'Zeichnung' and move content to 'R&I EB68-Nr.'
                    if (isset($row["Zeichnung"])) {
                        $row["R&I EB68-Nr."] = $row["Zeichnung"];
                        unset($row["Zeichnung"]);
                    }

                    // Removing col 'X'
                    if (isset($row["X"])) {
                        unset($row["X"]);
                    }

                    // Removing empty rows'
                    if (isset($row[""])) {
                        unset($row[""]);
                    }

                    $cols = "`" . implode("`,`", array_keys($row)) . "`";
                    $values = "'" . implode("','", array_values($row)) . "'";
                    (array_values($row) == "True") ? 1 : 2 ;
                    $values = str_replace("''", "NULL", $values);

                    $duplicates = explode(",", $cols);
                    array_walk($duplicates, "duplicateValues");


                    if ($con->query("INSERT INTO `Gesamtdatenbank` ($cols) VALUES($values) ON DUPLICATE KEY UPDATE " . implode(",", $duplicates))) {
                        $insertCount++;
                        continue;
                    } else {
                        $statusMsg[] = $row;
                        $statusMsg[] = $con->info;
                        $statusMsg[] = $con->error;
                        break;
                    }
                }
            }
            $statusMsg[] = $insertCount . " neue Zeilen hinzugefügt und\n" . $updateCount . " Zeilen überschrieben";
            if (mysqli_warning_count($con)) {
                $e = mysqli_get_warnings($con);
                do {
                    $statusMsg[] = "Warning: $e->errno: $e->message";
                } while ($e->next());
            }
        } else {
            $statusMsg[] = "Datei konnte nicht gelesen werden";
            $statusMsg[] = SimpleXLSX::parseError();
        }
    } else {
        $statusMsg[] = "Leider konnte die Datei nicht hochgeladen werden";
    }

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');

            echo json_encode($statusMsg);
            $con->close();
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>