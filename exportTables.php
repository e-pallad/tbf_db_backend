<?php 
    require './connect.php';
    include './libs/SimpleXLSXGen.php';

    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];
    $table = $_GET['table'];

    $data = array();
    
    #RI-Listen
    if ($table == 'RI-TBF_SEF_Apparateliste') {
        $query = "SELECT `PnPID`,`TBF_ID`,`R&I EB68-Nr.`,`Feld-Nr.`,`Zchn. Rev. Nr.`,`Zustand/Bearbeitung`,`Bemerkung`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`,`Hersteller`,`Typ`,`Medium`,FORMAT(`Nennleistung`,2,'de_DE') AS 'Nennleistung', FORMAT(`Nennspannung`,2,'de_DE') AS 'Nennspannung', FORMAT(`Nennstrom`,2,'de_DE') AS 'Nennstrom', FORMAT(`Fördervolumen`,2,'de_DE') AS 'Fördervolumen', FORMAT(`Drehzahl`,2,'de_DE') AS 'Drehzahl', FORMAT(`max. zul. Druck`,2,'de_DE') AS 'max. zul. Druck', FORMAT(`max. zul. Temperatur`,2,'de_DE') AS 'max. zul. Temperatur', FORMAT(`Volumen`,2,'de_DE') AS 'Volumen', FORMAT(`Fläche`,2,'de_DE') AS 'Fläche', FORMAT(`Gewicht`,2,'de_DE') AS 'Gewicht',`Werkstoff`,`Bauart`,`Zugehörige Sicherheitseinrichtung` FROM `Gesamtdatenbank` WHERE `APP` = 1";
    } elseif ($table == 'RI-TBF_SEF_Armaturenliste') {
        $query = "SELECT `PnPID`,`TBF_ID`,`R&I EB68-Nr.`,`Feld-Nr.`,`Zchn. Rev. Nr.`,`Zustand/Bearbeitung`,`Bemerkung`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`,`Hersteller`,`Typ`,`Medium`,FORMAT(`Nennleistung`,2,'de_DE') AS 'Nennleistung', FORMAT(`Nennspannung`,2,'de_DE') AS 'Nennspannung', FORMAT(`Nennstrom`,2,'de_DE') AS 'Nennstrom', FORMAT(`Fördervolumen`,2,'de_DE') AS 'Fördervolumen', FORMAT(`Drehzahl`,2,'de_DE') AS 'Drehzahl', FORMAT(`max. zul. Druck`,2,'de_DE') AS 'max. zul. Druck', FORMAT(`max. zul. Temperatur`,2,'de_DE') AS 'max. zul. Temperatur', FORMAT(`Volumen`,2,'de_DE') AS 'Volumen', FORMAT(`Fläche`,2,'de_DE') AS 'Fläche', FORMAT(`Gewicht`,2,'de_DE') AS 'Gewicht',`Werkstoff`,`Bauart`,`Zugehörige Sicherheitseinrichtung`,`DN`,`PN`,`NW`,`TBV/ITD Nr.`,`Einbauort bzw. Rohrleitungs Nr.` FROM `Gesamtdatenbank` WHERE `ARM` = 1";
    } elseif ($table == 'RI-TBF_SEF_Messstellenliste') {
        $query = "SELECT `PnPID`,`TBF_ID`,`R&I EB68-Nr.`,`Feld-Nr.`,`Zchn. Rev. Nr.`,`Zustand/Bearbeitung`,`Bemerkung`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`,`Hersteller`,`Typ`,`Medium`,`Funktion_Stoff`,`Funktion_Cod.`,`Funktion_Signal_High`,`Funktion_Signal_Low`,`Schaltanlage`,`Messbereich`,`Ausgangssignal`,`Spannungsversorgung`,`Messverfahren`,`Anzahl der Grenzkontakte`,`Selbstüberwachung + Störmeldekontakt`,`Sicherungsautomat`,`NH-Trenner`,`Überspannungsschutz`,`FI-Schutzschalter`,`Wartungsschalter`,`Anzeige Schaltschrank`,`Vor-Ort-Anzeige`,`Anzeige Bedientafel`,`Erneuern VO`,`Anzeige im PLS`,`Erneuern EMSR`,`Schutzart`,`EX-Schutz`,`zu Bearbeiten`,`Zusatzgeräte/Bemerkungen` FROM `Gesamtdatenbank` WHERE `MES` = 1";
    } elseif ($table == 'RI-TBF_SEF_Elektrokomponentenliste') {
        $query = "SELECT `TBF_ID`,`Komponente`,`Reserve_1`,`Reserve_2`,`Reserve_3`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`,`Hersteller`,`Typ`,`Zustand/Bearbeitung` FROM `Gesamtdatenbank` WHERE `EKL` = 1";
    } elseif ($table == 'RI-TBF_SEF_Elektroangaben') {
        $query = "SELECT `TBF_ID`,`PnPID`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`, `Anzahl AI`,`Anzahl AO`,`Anzahl DO`,`Anzahl DI`,`Anlaufart`,`Gleichzeitigkeitsfaktor`,`Betriebsstunden (Bh) pro Jahr`,`Abwärme (W)`,`Notstromberechtigungsstufe`,`USV-Berechtigung`, FORMAT(`Nennstrom`,2,'de_DE') AS 'Nennstrom', FORMAT(`Nennleistung`,2,'de_DE') AS 'Nennleistung', FORMAT(`Nennspannung`,2,'de_DE') AS 'Nennspannung',`NSV`,`Kabel 1 Typ`,`Kabel 1 Länge`,`Kabel 2 Typ`,`Kabel 2 Länge`,`Kabel 3 Typ`,`Kabel 3 Länge`,`Kabel 4 Typ`,`Kabel 4 Länge`,`Kabel 5 Typ`,`Kabel 5 Länge`, `Reparaturschalter`,`Autonome Steuerung`,`Ex-Zone`,`IP-Schutzart`,`Motorschutz`,`Strommessung`,`Leistungsmessung`,`Effizienzklasse`,`Schaltfeld`,`Einschub Nr.`,`Res.-Spalte`,`Wirkungsgrad`,`cos phi`,`Direktanlauf`,`Stern-Dreieck-Anlauf`,`Sanftanlauf`,`Drehzahlverstellung (FU)`,`Bypass für FU`,`Polumschaltbar`,`Wendeschaltung`,`NH-Trenner`,`SI.-Überwachung`,`FI-Schutzschalter`,`Thermokontakt`,`Kaltleiter`,`Dichte-Schutz`,`Strömungswächter`,`Druckwächter`,`Not-Aus`,`Stromwandler`,`Stromanzeige Schaltschrank`,`Stromanzeige Bedientafel`,`Vor-Ort-Anzeige`,`Schieberheizung`,`Stellungsgeber`,`Bedienung am Schaltschrank`,`Bedienung an Bedientafel`,`Bedienung Prozeßleitsystem`,`Bedientafeln_Betrieb/Störung`,`Bedientafeln_Schaltzustände`,`Bedientafeln_Automatik`,`PLS_Betrieb/Störung`,`PLS_Schaltzustände`,`PLS_Automatik`,`EX-Schutz`,`Erneuern EMSR`,`Schutzart`,`Abschaltung im Ex-Fall`,`Blitzschutz`,`Ausführung Vor-Ort-Steuerstelle`,`Schaltschrank für Einschub`,`TypicalNr. Einschub/Stromlaufplan`,`Typical Nr. MSK`,`Motorsteuerkarte Nr.`,`Zusatzgeräte/Bemerkungen`,`Vor-Ort-Bedienung`,`Funktion_Stoff`,`Funktion_Cod.`,`Schaltanlage`,`Messbereich`,`Ausgangssignal`,`Spannungsversorgung`,`Messverfahren`,`Anzahl der Grenzkontakte`,`Selbstüberwachung + Störmeldekontakt`,`Sicherungsautomat`,`Überspannungsschutz`,`Wartungsschalter` FROM `Gesamtdatenbank`";
    } elseif ($table == 'RI-TBF_SEF_Stoffstromliste') {
        $query = "SELECT `PnPID`,`R&I EB68-Nr.`,`Feld-Nr.`,`Zchn. Rev. Nr.`,`Zustand/Bearbeitung`,`Bemerkung`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`, `Hersteller`,`Typ`,`Volumenstrom min`,`Volumenstrom nom`,`Volumenstrom max`,`Dichte min`,`Dichte nom`,`Dichte max`,`Massenstrom min`,`Massenstrom nom`,`Massenstrom max`,`Druck min hPa_a`,`Druck nom hPa_a`,`Druck max hPa_a`,`Druck min Mpa_a`,`Druck nom Mpa_a`,`Druck max Mpa_a`,`Temperatur min`,`Temperatur nom`,`Temperatur max`,`Feststoffgehalt min`,`Feststoffgehalt nom`,`Feststoffgehalt max` FROM `Gesamtdatenbank` WHERE `SSL` = 1";
    } elseif ($table == 'Gesamtdatenbank') {
        $query = "SELECT * FROM `Gesamtdatenbank`";
    } elseif ($table == 'RI-TBF_SEF_Allplan_Liste') {
        $query = "SELECT `TBF_ID`,`Allplan_ID`,`Typ`,`Familie`,`Raumnnummer`,`Raumlänge`,`Raumbreite`,`Raumhöhe`,`Raumfläche`,`Raumvolumen`,`Ort x-Koordinate`,`Ort y-Koordinate`,`Ort z-Koordinate` FROM `Gesamtdatenbank`";
    } elseif ($table == 'RI-TBF_SEF_PlancalNova_Liste') {
        $query = "SELECT `Index`, `Klasse`, `Benennung` AS 'Bezeichnung', `Medium`, `R&I EB68-Nr.` AS `Zeichnung`, `TBF_ID`, `AKZ_Gr1_Standort`, `AKZ_Gr2_Anlagenteil`, `AKZ_Gr3_Aggregat`, `AKZ_Gr4_Nummer`, `AKZ_Gr5_Aggregat`, `AKZ_Gr6_Nummer`, `Benennung`, `Benennung Zusatz`, `Hersteller`, `Typ`, `Nennleistung`, `Nennspannung`, `Nennstrom`, `Massenstrom`, `Signalart` FROM `Gesamtdatenbank` WHERE `PLA` = 1";
    } elseif ($table == 'RI-TBF_SEF_Revit_Liste') {
        $query = "SELECT `Revit_ID` AS 'ID', `FamilyAndType`,`Revit_Type` AS 'Type', `Benennung` AS 'Bezeichnung', `R&I EB68-Nr.` AS 'Plannummer R+I', CONCAT_WS('.',`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`) AS 'AKZ',`TBF_ID` AS 'Element ID' FROM `Gesamtdatenbank` WHERE `REV` = 1";
    } elseif ($table == 'RI-TBF_SEF_Rohrleitungsliste') {
        $query = "SELECT `PnPID`,`TBF_ID`,`RohrBez_Gr2_Anlagenteil`,`RohrBez_Gr3_Rohrklasse`,`RohrBez_Gr4_Medium`,`RohrBez_Gr5_Nummer`,`RohrBez_Gr6_ Zusatzinformation`,`RLL_Bezeichnung` AS 'Bezeichnung',`RLL_Kurzbezeichnung` AS 'Kurzbezeichnung',`Fördermedium`,`Von Ort`,`Von Aggregat` AS 'Von Aggregat, Armatur, Rohrltg., (AKZ)',`Nach Ort`,`Nach Aggregat` AS 'Nach Aggregat, Armatur, Rohrltg., (AKZ)', `Werkstoff`, `Druckstufe (bar)`,`Betriebsdruck (bar)`,`Durchmesser` AS 'Durchmesser (DN, DA)', `Länge [m]` AS 'Länge (m)', `Datum Änderung`, `Bemerkung` FROM `Gesamtdatenbank` WHERE `RLL` = 1";
    } elseif ($table == 'RI-TBF_SEF_Stoffstromliste') {
        $query = "SELECT `TBF_ID`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`,`R&I EB68-Nr.`,`Feld-Nr.`,`Zchn. Rev. Nr.`,`Hersteller`,`Typ`,`Zustand/Bearbeitung`,`Bemerkung`,`Volumenstrom min`,`Volumenstrom nom`,`Volumenstrom max`,`Dichte min`,`Dichte nom`,`Dichte max`,`Massenstrom min`,`Massenstrom nom`,`Massenstrom max`,`Druck min hPa_a`,`Druck nom hPa_a`,`Druck max hPa_a`,`Druck min Mpa_a`,`Druck nom Mpa_a`,`Druck max Mpa_a`,`Temperatur min`,`Temperatur nom`,`Temperatur max`,`Feststoffgehalt min`,`Feststoffgehalt nom`,`Feststoffgehalt max` FROM `Gesamtdatenbank`";
    # SEF Listen
    } elseif ($table == 'SEF_E-Verbraucherliste') {
        $query = "SELECT `TBF_ID`, `AKZ_Gr1_Standort`, `AKZ_Gr2_Anlagenteil`, `AKZ_Gr3_Aggregat`, `AKZ_Gr4_Nummer`, `AKZ_Gr5_Aggregat`, `AKZ_Gr6_Nummer`, `Schaltanlage`, `Einschub Nr.`, `Benennung`, `Benennung Zusatz`, `Nennleistung`, `Gleichzeitigkeitsfaktor`, `Wirkungsgrad`, `cos phi`, `Nennleistung` * `Gleichzeitigkeitsfaktor` AS `Leistung mit Gleichzeitigkeitsfaktor`, FORMAT(`Nennstrom`,2,'de_DE') AS 'Nennstrom', FORMAT(`Nennspannung`,2,'de_DE') AS 'Nennspannung', `Direktanlauf`, `Stern-Dreieck-Anlauf`, `Sanftanlauf`, `Drehzahlverstellung (FU)`, `Bypass für FU`, `Polumschaltbar`,`Wendeschaltung`,`Motorschutz`, `NH-Trenner`, `SI.-Überwachung`, `FI-Schutzschalter`, `Reparaturschalter`,`Thermokontakt`, `Kaltleiter`, `Dichte-Schutz`, `Strömungswächter`, `Druckwächter`, `Not-Aus`, `Stromwandler`, `Stromanzeige Schaltschrank`, `Stromanzeige Bedientafel`, `Schieberheizung`, `Stellungsgeber`, `Vor-Ort-Bedienung`, `Bedienung am Schaltschrank`, `Bedienung an Bedientafel`, `Bedienung Prozeßleitsystem`, `Bedientafeln_Betrieb/Störung`, `Bedientafeln_Schaltzustände`, `Bedientafeln_Automatik`, `PLS_Betrieb/Störung`, `PLS_Schaltzustände`, `PLS_Automatik`, `Ex-Schutz`, `Schutzart`, `Abschaltung im Ex-Fall`, `Blitzschutz`, `Ausführung Vor-Ort-Steuerstelle`, `Schaltschrank für Einschub`, `TypicalNr. Einschub/Stromlaufplan`, `Typical Nr. MSK`, `Motorsteuerkarte Nr.`, `Zusatzgeräte/Bemerkungen`, `Zustand/Bearbeitung` FROM `Gesamtdatenbank` WHERE `Nennleistung` > 0 AND `elektrischer Verbraucher` = 'Ja'";
    } elseif ($table == 'SEF_Messstellenliste') {
        $query = "SELECT `PnPID`, `AKZ_Gr1_Standort`, `AKZ_Gr2_Anlagenteil`, `AKZ_Gr3_Aggregat`, `AKZ_Gr4_Nummer`, `AKZ_Gr5_Aggregat`, `AKZ_Gr6_Nummer`, `Funktion_Stoff`, `Funktion_Cod.`, `Funktion_Signal_High`, `Funktion_Signal_Low`, `Schaltanlage`, `Benennung` AS 'Bezeichnung', `Messbereich`, `Ausgangssignal`, `Spannungsversorgung`, `Messverfahren`, `Anzahl der Grenzkontakte`, `Selbstüberwachung + Störmeldekontakt`, `Sicherungsautomat`, `NH-Trenner`, `Überspannungsschutz`, `FI-Schutzschalter`, `Wartungsschalter`, `Vor-Ort-Anzeige`, `Anzeige Schaltschrank`, `Anzeige Bedientafel`, `Anzeige im PLS`, `Erneuern VO`, `Erneuern EMSR`, `Schutzart`, `Ex-Schutz`, `zu Bearbeiten`, `Zusatzgeräte/Bemerkungen`, `Zustand/Bearbeitung` FROM `Gesamtdatenbank` WHERE `MES` = 1";
    } elseif ($table == 'SEF_Armaturenliste') {
        $query = "SELECT `PnPID`, `AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`, `AKZ_Gr5_Aggregat`, `AKZ_Gr6_Nummer`, `Benennung`, `Benennung Zusatz`, `NW`, `PN`, `TBV/ITD Nr.`, `Einbauort bzw. Rohrleitungs Nr.`, `R&I EB68-Nr.`, `Feld-Nr.`, `Zchn. Rev. Nr.`, `Bemerkung`, `Zustand/Bearbeitung` FROM `Gesamtdatenbank` WHERE `ARM` = 1";
    } elseif ($table == 'SEF_Ausrüstungsliste') {
        $query = "SELECT `PnPID`, `TBF_ID`, `AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung` AS 'Bezeichnung', `Hersteller`, `Typ`, `Medium`, FORMAT(`Nennleistung`,2,'de_DE') AS 'Nennleistung', FORMAT(`Nennspannung`,2,'de_DE') AS 'Nennspannung', FORMAT(`Fördervolumen`,2,'de_DE') AS 'Fördervolumen', FORMAT(`Drehzahl`,2,'de_DE') AS 'Drehzahl', FORMAT(`max. zul. Druck`,2,'de_DE') AS 'max. zul. Druck', FORMAT(`max. zul. Temperatur`,2,'de_DE') AS 'max. zul. Temperatur', FORMAT(`Volumen`,2,'de_DE') AS 'Volumen', FORMAT(`Fläche`,2,'de_DE') AS 'Fläche', FORMAT(`Gewicht`,2,'de_DE') AS 'Gewicht',`Werkstoff`,`Bauart`,`Zugehörige Sicherheitseinrichtung`, `Bemerkung` FROM `Gesamtdatenbank` WHERE `APP` = 1 OR `ARM` = 1 OR `MES` = 1 OR `PLA` = 1";
    } elseif ($table == 'Verfahrenstechnikangaben') {
        $query = "SELECT `TBF_ID`,`AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`,`Benennung`,`Benennung Zusatz`,`Hersteller`,`Typ`,`Medium`,`Fördervolumen`,`Drehzahl`,`max. zul. Druck`,`max. zul. Temperatur`,`Volumen`,`Fläche`,`Gewicht`,`Werkstoff`,`Bauart`,`Zugehörige Sicherheitseinrichtung`,`Zustand/Bearbeitung`,`Bemerkung`,`Volumenstrom min`,`Volumenstrom nom`,`Volumenstrom max`,`Dichte min`,`Dichte nom`,`Dichte max`,`Massenstrom min`,`Massenstrom nom`,`Massenstrom max`,`Druck min hPa_a`,`Druck nom hPa_a`,`Druck max hPa_a`,`Druck min Mpa_a`,`Druck nom Mpa_a`,`Druck max Mpa_a`,`Temperatur min`,`Temperatur nom`,`Temperatur max`,`Feststoffgehalt min`,`Feststoffgehalt nom`,`Feststoffgehalt max` FROM `Gesamtdatenbank`";
    } elseif ($table == 'Masterliste') {
        $query = "SELECT `TBF_ID`,`PnPID`,`Elektro_ID`,`Revit_ID`,`Allplan_ID` FROM `Gesamtdatenbank`";
    } elseif ($table == 'SEF_Rohrleitungsliste') {
        $query = "SELECT `TBF_ID`,`RohrBez_Gr2_Anlagenteil`,`RohrBez_Gr3_Rohrklasse`,`RohrBez_Gr4_Medium`,`RohrBez_Gr5_Nummer`,`RohrBez_Gr6_ Zusatzinformation`,`RLL_Bezeichnung` AS `Bezeichnung`,`Fördermedium`,`Von Ort`,`Von Aggregat` AS `Von Aggregat, Armatur, Rohrltg., (AKZ)`,`Nach Ort`,`Nach Aggregat` AS `Nach Aggregat, Armatur, Rohrltg., (AKZ)`,`Werkstoff`, `Betriebsdruck (bar)`, `Durchmesser` AS `Durchmesser (DN, DA)`, `Länge [m]` AS `Länge (m)`, `Datum Änderung`, `Bemerkung`  FROM `Gesamtdatenbank` WHERE `RLL` = 1";
    }

    $header = mysqli_fetch_fields($con->query($query));

    foreach ($header as $key => $value) {
        $headerRow[] = $value->name;
    }

    if ($table === 'RI-TBF_SEF_Revit_Liste') {
        $data[] = array("335ccc90-123c-47fd-9d51-c85684a75b39-00545df6");
        $data[] = $headerRow;
        $data[] = array("","","Typ","Bezeichnung","Plannummer R+I","AKZ","Element ID");
        $data[] = array("","","ElementId","Text","Text","Text","Number");
        $mysqlData = mysqli_fetch_all($con->query($query));
        $CSVdata = array_merge($data, $mysqlData);
    } elseif ($table == 'SEF_Messstellenliste') {
        $data[] = $headerRow;
        $mysqlData = mysqli_fetch_all($con->query($query));
        foreach ($mysqlData as $innerArray) {
            if (is_array($innerArray)) {
                foreach ($innerArray as $key => $value) {
                    if ($key == 'False' ) {
                        $key = "";
                    } elseif ($key == 'True') {
                        $key = "x";
                    } else {
                        continue;
                    }
                }
            } else {
                continue;
            }
        }
        $CSVdata = array_merge($data, $mysqlData);
    } else {
        $data[] = $headerRow;
        $mysqlData = mysqli_fetch_all($con->query($query));
        $CSVdata = array_merge($data, $mysqlData);
    }

    switch ($method) {
        case 'GET':
            $xlsx = SimpleXLSXGen::fromArray( $CSVdata );
            
            /*
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8');
            header('Access-Control-Allow-Origin: *');
            header('Content-Disposition: attachment; filename="'. $table .'.xlsx";');

            $xlsx->downloadAs("$table.xlsx");
			*/
            
			header('Content-Type: application/json;');
            header('Access-Control-Allow-Origin: *');

            echo json_encode($key);
            
            break;
        default:
            echo http_response_code(403);
            break;
    }

?>