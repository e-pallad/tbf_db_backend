<?php
    include('connect.php');
    require('libs/rpdf.php');

    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];

    $query = "SELECT CONCAT_WS( '.', `AKZ_Gr1_Standort`, `AKZ_Gr2_Anlagenteil`, `AKZ_Gr3_Aggregat`, `AKZ_Gr4_Nummer`, `AKZ_Gr5_Aggregat`, `AKZ_Gr6_Nummer`) AS `AKZ Kodierung`, `Schaltanlage`, `Einschub Nr.`, `Res.-Spalte`, `Benennung`, `Benennung Zusatz`, `Nennleistung`, `Gleichzeitigkeitsfaktor`, `Wirkungsgrad`, `cos phi`, `Nennleistung` * `Gleichzeitigkeitsfaktor` AS `Leistung mit Gleichzeitigkeitsfaktor`, `Nennstrom`, `Nennspannung`, `Direktanlauf`, `Stern-Dreieck-Anlauf`, `Sanftanlauf`, `Drehzahlverstellung (FU)`, `Bypass für FU`, `Polumschaltbar`,`Wendeschaltung`,`Motorschutz`, `NH-Trenner`, `SI.-Überwachung`, `FI-Schutzschalter`, `Reparaturschalter`, `Thermokontakt`, `Kaltleiter`, `Dichte-Schutz`, `Strömungswächter`, `Druckwächter`, `Not-Aus`, `Stromwandler`, `Stromanzeige Schaltschrank`, `Stromanzeige Bedientafel`, `Schieberheizung`, `Stellungsgeber`, `Vor-Ort-Bedienung`, `Bedienung am Schaltschrank`, `Bedienung an Bedientafel`, `Bedienung Prozeßleitsystem`, `Bedientafeln_Betrieb/Störung`, `Bedientafeln_Schaltzustände`, `Bedientafeln_Automatik`, `PLS_Betrieb/Störung`, `PLS_Schaltzustände`, `PLS_Automatik`, `Ex-Schutz`, `Schutzart`, `Abschaltung im Ex-Fall`, `Blitzschutz`, `Ausführung Vor-Ort-Steuerstelle`, `Schaltschrank für Einschub`, `TypicalNr. Einschub/Stromlaufplan`, `Typical Nr. MSK`, `Motorsteuerkarte Nr.`, `Zusatzgeräte/Bemerkungen`, `Zustand/Bearbeitung` FROM `Gesamtdatenbank` WHERE `Nennleistung` > 0 AND `elektrischer Verbraucher` = 'Ja'";
    
    $data = mysqli_fetch_all($con->query($query));

    class PDF extends RPDF {
        // Page header
        function Header() {
            global $headerLine;

            $this->SetFillColor(232,160,132);
            $this->Image("./img/logo.png",245,5,50);
            $this->Ln(10);
            $this->SetFont('Arial','',10);
            $this->Cell(281,5.5,'ARA Sindlingen',1,0,'L');
            $this->Ln();

            $this->SetFont('Arial','',4);
            $this->SetTextColor(0,0,0);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Lfd. Nr.','U');
            $this->SetXY($x+3,$y);

            $this->Cell(19,25,'AKZ Kodierung',1,0,'C',1);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Schaltfeld Bestand','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Einschub Nr.','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Res.-Spalte','U');
            $this->SetXY($x+3,$y);

            $this->Cell(25,25,'Benennung',1,0,'C',1);
            $this->Cell(25,25,'Benennung Zusatz',1,0,'C',1);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+3,$y+23,'Leistung (kW)','U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Gleichzeitigkeitsfaktor','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Wirkungsgrad','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Cos phi','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 12, 25, 'DF');
            $this->TextWithDirection($x+5.5,$y+23,"Leistung (kW) mit",'U');
            $this->TextWithDirection($x+8,$y+23,"Gleichzeitigkeitsfaktor",'U');
            $this->SetXY($x+12,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+3,$y+23,"Strom [A]",'U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Spannung (V)','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Direktanlauf','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Stern-Dreieck-Anlauf','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Sanftanlauf','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Drehzahlverstellung (FU)','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Bypass für FU'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Polumschaltbar','U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Wendeschaltung','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Motorschutz','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'NH-Trenner','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('SI.-Überwachung'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'FI-Schutzschalter','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Reparaturschalter','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Thermokontakt','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Kaltleiter','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Dichte-Schutz','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Strömungswächter'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Druckwächter'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Not-Aus','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Stromwandler','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Stromanzeige Schaltschrank','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Stromanzeige Bedientafel','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Schieberheizung','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Stellungsgeber','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Vor-Ort-Bedienung','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Bedienung am Schaltschrank','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Bedienung an Bedientafel','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Bedienung Prozeßleitsystem'),'U');
            $this->SetXY($x+3,$y);

            $resety=$this->GetY();

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 9, 6, 'D');
            $this->Text($x+1,$y+3,'Bedientafel',1,0,'C');
            $this->SetXY($x,$y+6);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 19, 'DF');
            $this->TextWithDirection($x+2,$y+17,utf8_decode('Betrieb/Störung'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 19, 'DF');
            $this->TextWithDirection($x+2,$y+17,utf8_decode('Schaltzustände'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 19, 'DF');
            $this->TextWithDirection($x+2,$y+17,'Automatik','U');
            $this->SetXY($x+3,$resety);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 9, 6, 'D');
            $this->Text($x+3,$y+3,'PLS',1,0,'C');
            $this->SetXY($x,$y+6);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 19, 'DF');
            $this->TextWithDirection($x+2,$y+17,utf8_decode('Betrieb/Störung'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 19, 'DF');
            $this->TextWithDirection($x+2,$y+17,utf8_decode('Schaltzustände'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 19, 'DF');
            $this->TextWithDirection($x+2,$y+17,'Automatik','U');
            $this->SetXY($x+3,$resety);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Ex-Schutz','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+3,$y+23,'Schutzart','U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Abschaltung im Ex-Fall','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Blitzschutz (ÜS)'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Ausführung Vor-Ort-Steuerstelle'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode('Schaltschrank für Einschub'),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 12, 25, 'DF');
            $this->TextWithDirection($x+5.5,$y+23,"Typical Nr.",'U');
            $this->TextWithDirection($x+8,$y+23,"Einschub/Stromlaufplan",'U');
            $this->SetXY($x+12,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+3,$y+23,'Typical Nr. MSK','U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,'Motorsteuerkarte Nr.','U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 12, 25, 'DF');
            $this->TextWithDirection($x+5,$y+23,utf8_decode('Zusatzgeräte / Bemerkungen'),'U');
            $this->SetXY($x+10,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 13, 25, 'DF');
            $this->TextWithDirection($x+5,$y+23,"Zustand / Bearbeitung",'U');
            $this->SetXY($x+10,$y);

            $this->Ln();

            $this->SetTextColor(75,135,190);
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(19,3,'1','LR',0,'C');
            $this->Cell(3,3,'2','LR',0,'C');
            $this->Cell(3,3,'3','LR',0,'C');
            $this->Cell(3,3,'4','LR',0,'C');
            $this->Cell(25,3,'5','LR',0,'C');
            $this->Cell(25,3,'6','LR',0,'C');
            $this->Cell(6,3,'7','LR',0,'C');
            $this->Cell(3,3,'8','LR',0,'C');
            $this->Cell(3,3,'9','LR',0,'C');
            $this->Cell(3,3,'10','LR',0,'C');
            $this->Cell(12,3,'11','LR',0,'C');
            $this->Cell(6,3,'12','LR',0,'C');
            $this->Cell(3,3,'13','LR',0,'C');
            $this->Cell(3,3,'14','LR',0,'C');
            $this->Cell(3,3,'15','LR',0,'C');
            $this->Cell(3,3,'16','LR',0,'C');
            $this->Cell(3,3,'17','LR',0,'C');
            $this->Cell(3,3,'18','LR',0,'C');
            $this->Cell(3,3,'19','LR',0,'C');
            $this->Cell(3,3,'20','LR',0,'C');
            $this->Cell(3,3,'21','LR',0,'C');
            $this->Cell(3,3,'22','LR',0,'C');
            $this->Cell(3,3,'23','LR',0,'C');
            $this->Cell(3,3,'24','LR',0,'C');
            $this->Cell(3,3,'25','LR',0,'C');
            $this->Cell(3,3,'26','LR',0,'C');
            $this->Cell(3,3,'27','LR',0,'C');
            $this->Cell(3,3,'28','LR',0,'C');
            $this->Cell(3,3,'29','LR',0,'C');
            $this->Cell(3,3,'30','LR',0,'C');
            $this->Cell(3,3,'31','LR',0,'C');
            $this->Cell(3,3,'32','LR',0,'C');
            $this->Cell(3,3,'33','LR',0,'C');
            $this->Cell(3,3,'34','LR',0,'C');
            $this->Cell(3,3,'35','LR',0,'C');
            $this->Cell(3,3,'36','LR',0,'C');
            $this->Cell(3,3,'37','LR',0,'C');
            $this->Cell(3,3,'38','LR',0,'C');
            $this->Cell(3,3,'39','LR',0,'C');
            $this->Cell(3,3,'40','LR',0,'C');
            $this->Cell(3,3,'41','LR',0,'C');
            $this->Cell(3,3,'42','LR',0,'C');
            $this->Cell(3,3,'43','LR',0,'C');
            $this->Cell(3,3,'44','LR',0,'C');
            $this->Cell(3,3,'45','LR',0,'C');
            $this->Cell(3,3,'46','LR',0,'C');
            $this->Cell(3,3,'47','LR',0,'C');
            $this->Cell(6,3,'48','LR',0,'C');
            $this->Cell(3,3,'49','LR',0,'C');
            $this->Cell(3,3,'50','LR',0,'C');
            $this->Cell(3,3,'51','LR',0,'C');
            $this->Cell(3,3,'52','LR',0,'C');
            $this->Cell(12,3,'53','LR',0,'C');
            $this->Cell(6,3,'54','LR',0,'C');
            $this->Cell(3,3,'55','LR',0,'C');
            $this->Cell(10,3,'56','LR',0,'C');
            $this->Cell(13,3,'57','LR',0,'C');

            $this->Ln();

            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(19,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(25,3,'','LR',0,'C');
            $this->Cell(25,3,'','LR',0,'C');
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(12,3,'','LR',0,'C');
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(12,3,'','LR',0,'C');
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(10,3,'','LR',0,'C');
            $this->Cell(13,3,'','LR',0,'C');
            $this->Ln();

            $this->SetTextColor(0,0,0);
        }

        function BasicTable($data) {
            $count = 1;
            foreach($data as $row) {

                $this->SetFont('Arial','',5);
                $this->SetTextColor(75,135,190);
                $this->Cell(3,6,$count,1,0,'C');
                $this->SetTextColor(0,0,0);
                $this->Cell(19,6,$row[0],1,0,'C');
                $this->Cell(3,6,$row[1],1,0,'C');
                $this->Cell(3,6,$row[2],1,0,'C');
                $this->SetTextColor(232,160,132);
                $this->Cell(3,6,$row[3],1,0,'C');
                $this->SetTextColor(0,0,0);
                if (strlen($row[4]) > 22) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 25, 6);
                    $this->MultiCell(25,2.8,utf8_decode($row[4]),0,'L');
                    $this->SetXY($x+25,$y);
                } else {
                    $this->Cell(25,6,utf8_decode($row[4]),1,0,'L');
                }
                if (strlen($row[5]) > 22) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 25, 6);
                    $this->MultiCell(25,2.8,utf8_decode($row[5]),0,'L');
                    $this->SetXY($x+25,$y);
                } else {
                    $this->Cell(25,6,utf8_decode($row[5]),1,0,'L');
                }
                $this->Cell(6,6,$row[6],1,0,'C');
                $this->Cell(3,6,$row[7],1,0,'C');
                $this->Cell(3,6,$row[8],1,0,'C');
                $this->Cell(3,6,$row[9],1,0,'C');
                $this->Cell(12,6,$row[10],1,0,'C');
                $this->Cell(6,6,$row[11],1,0,'C');
                $this->Cell(3,6,$row[12],1,0,'C');
                $this->Cell(3,6,$row[13],1,0,'C');
                $this->Cell(3,6,$row[14],1,0,'C');
                $this->Cell(3,6,$row[15],1,0,'C');
                $this->Cell(3,6,$row[16],1,0,'C');
                $this->Cell(3,6,$row[17],1,0,'C');
                $this->Cell(3,6,$row[18],1,0,'C');
                $this->Cell(3,6,$row[19],1,0,'C');
                $this->Cell(3,6,$row[20],1,0,'C');
                $this->Cell(3,6,$row[21],1,0,'C');
                $this->Cell(3,6,$row[22],1,0,'C');
                $this->Cell(3,6,$row[23],1,0,'C');
                $this->Cell(3,6,$row[24],1,0,'C');
                $this->Cell(3,6,$row[25],1,0,'C');
                $this->Cell(3,6,$row[26],1,0,'C');
                $this->Cell(3,6,$row[27],1,0,'C');
                $this->Cell(3,6,$row[28],1,0,'C');
                $this->Cell(3,6,$row[29],1,0,'C');
                $this->Cell(3,6,$row[30],1,0,'C');
                $this->Cell(3,6,$row[31],1,0,'C');
                $this->Cell(3,6,$row[32],1,0,'C');
                $this->Cell(3,6,$row[33],1,0,'C');
                $this->Cell(3,6,$row[34],1,0,'C');
                $this->Cell(3,6,$row[35],1,0,'C');
                $this->Cell(3,6,$row[36],1,0,'C');
                $this->Cell(3,6,$row[37],1,0,'C');
                $this->Cell(3,6,$row[38],1,0,'C');
                $this->Cell(3,6,$row[39],1,0,'C');
                $this->Cell(3,6,$row[40],1,0,'C');
                $this->Cell(3,6,$row[41],1,0,'C');
                $this->Cell(3,6,$row[42],1,0,'C');
                $this->Cell(3,6,$row[43],1,0,'C');
                $this->Cell(3,6,$row[44],1,0,'C');
                $this->Cell(3,6,$row[45],1,0,'C');
                $this->Cell(3,6,$row[46],1,0,'C');
                $this->Cell(6,6,$row[47],1,0,'C');
                $this->Cell(3,6,$row[48],1,0,'C');
                $this->Cell(3,6,$row[49],1,0,'C');
                $this->Cell(3,6,$row[50],1,0,'C');
                $this->Cell(3,6,$row[51],1,0,'C');
                $this->Cell(12,6,$row[52],1,0,'C');
                $this->Cell(6,6,$row[53],1,0,'C');
                $this->Cell(3,6,$row[54],1,0,'C');
                $this->Cell(10,6,$row[55],1,0,'C');
                $this->Cell(13,6,$row[56],1,0,'C');
              
                $count++;
                $this->Ln();
            }
        }
    
        function Footer() {
            $this->SetY(-20);
            $this->SetFont('Arial','',7.5);
            $this->SetTextColor(0,0,0);
            $this->Cell(8,10,'Datei: ',0,0,'L');
            $this->Cell(30,10,'Dateiname?',0,0,'L');
            $this->Cell(78,10,'',0,0);
            $this->SetFont('Arial','',10.7);
            $this->Cell(101,10);
            $this->Ln(3);

            $this->SetFont('Arial','',7.5);
            $this->Cell(18,10,utf8_decode('geändert:'),0,0,'L');
            $this->Cell(18,10,date("d.m.Y"),0,0,'L');
            $this->Cell(80,10,'',0,0);
            $this->SetFont('Arial','',10.7);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->MultiCell(30,5,'E-Verbraucherliste Gewerk SEVA',0,'C');
            $this->SetXY($x+30,$y);
            $this->Ln(3);

            $this->SetFont('Arial','',7.5);
            $this->Cell(18,10,'Druckdatum:',0,0,'L');
            $this->Cell(18,10,date("d.m.Y"),0,0,'L');
            $this->Cell(80,10,'',0,0);
            $this->Cell(25,15,'',0,0,'C');
            $this->Cell(101,10);
            $this->Cell(15,10,'Seite '.$this->PageNo().' von {nb}',0,0,'C');
        }
    }
    
    $pdf = new PDF('L');
    $pdf->AliasNbPages('{nb}');
    $pdf->AddPage();
    $pdf->BasicTable($data);

    switch ($method) {
        case 'GET':
            header("Content-type: application/pdf");
            header('Access-Control-Allow-Origin: *');
            header('Content-Disposition: attachment; filename="SEF-EVerbraucherliste.pdf";');

            readfile($pdf->Output());
            
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>
?>