<?php
    include('connect.php');
    require('libs/rpdf.php');

    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];

    $query = "SELECT CONCAT_WS(' . ', `AKZ_Gr1_Standort`, `AKZ_Gr2_Anlagenteil`, `AKZ_Gr3_Aggregat`, `AKZ_Gr4_Nummer`) AS `AKZ Kodierung`, `Funktion_Stoff`, `Funktion_Cod.`, CONCAT(`Funktion_Signal_High`, ', ', `Funktion_Signal_Low`) AS `Funktion_Signal`, `Schaltanlage`, `Messbereich`, `Ausgangssignal`, `Spannungsversorgung`, `Messverfahren`, `Anzahl der Grenzkontakte`, `Selbstüberwachung + Störmeldekontakt`, `Sicherungsautomat`, `NH-Trenner`, `Überspannungsschutz`, `FI-Schutzschalter`, `Wartungsschalter`, `Vor-Ort-Anzeige`, `Anzeige Schaltschrank`, `Anzeige Bedientafel`, `Anzeige im PLS`, `Erneuern VO`, `Erneuern EMSR`, `Schutzart`, `Ex-Schutz`, `zu Bearbeiten`, `Zusatzgeräte/Bemerkungen`, `Zustand/Bearbeitung`, `Benennung` FROM `Gesamtdatenbank` WHERE `AKZ_Gr4_Nummer` > 0 AND `TableID` = 3";
    
    $data = mysqli_fetch_all($con->query($query));

    class PDF extends RPDF {
        // Page header
        function Header() {
            $this->SetFillColor(75,135,190);
            $this->Image("./img/logo.png",245,5,50);
            $this->Ln(10);
            $this->SetFont('Arial','',10);
            $this->Cell(279,5.5,'ARA Sindlingen',1,0,'L');
            $this->Ln();

            $this->SetFont('Arial','',6);
            $this->SetTextColor(0,0,0);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 4, 25, 'DF');
            $this->TextWithDirection($x+2.5,$y+23,'Lfd. Nr.','U');
            $this->SetXY($x+4,$y);

            $this->Cell(20,25,utf8_decode('AKZ Messgröße'),1,0,'C',1);
            $this->Cell(35,25,'Funktion',1,0,'C',1);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+3.8,$y+23,'Schaltanlage','U');
            $this->SetXY($x+6,$y);
            
            $this->Cell(41,25,'Bezeichnung',1,0,'C',1);
            $this->Cell(20,25,'Messbereich',1,0,'C',1);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 10, 25, 'DF');
            $this->TextWithDirection($x+5.5,$y+23,'Ausgangssignal','U');
            $this->SetXY($x+10,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+2.8,$y+23,"Spannungs- ",'U');
            $this->TextWithDirection($x+4.8,$y+23,"versorgung [V]",'U');
            $this->SetXY($x+6,$y);

            $this->Cell(30,25,'Messverfahren',1,0,'C',1);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+2.8,$y+23,"Anzahl der",'U');
            $this->TextWithDirection($x+4.8,$y+23,"Grenzkontakte",'U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+2.8,$y+23,utf8_decode("Selbstüberwachung +"),'U');
            $this->TextWithDirection($x+4.8,$y+23,utf8_decode("Störmeldekontakt"),'U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Sicherungsautomat",'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"NH-Trenner",'U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,utf8_decode("Überspannungsschutz"),'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"FI-Schutzschalter",'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Wartungsschalter",'U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Vor-Ort-Anzeige",'U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Anzeige Schaltschrank",'U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Anzeige Bedientafel",'U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Anzeige im PLS",'U');
            $this->SetXY($x+3,$y);
            
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Erneuern VO",'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Erneuern EMSR",'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 6, 25, 'DF');
            $this->TextWithDirection($x+4,$y+23,"Schutzart",'U');
            $this->SetXY($x+6,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"Ex-Schutz",'U');
            $this->SetXY($x+3,$y);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 3, 25, 'DF');
            $this->TextWithDirection($x+2,$y+23,"zu Bearbeiten",'U');
            $this->SetXY($x+3,$y);
            
            $this->Cell(30,25,utf8_decode("Zusatzgeräte / Bemerkungen"),1,0,'C',1);

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x, $y, 20, 25, 'DF');
            $this->TextWithDirection($x+8.5,$y+19,"Zustand /",'U');
            $this->TextWithDirection($x+10.5,$y+19,"Bearbeitung",'U');
            $this->SetXY($x+20,$y);

            $this->Ln();

            $this->SetTextColor(75,135,190);
            $this->Cell(4,3,'','LR',0,'C');
            $this->Cell(20,3,'','LR',0,'C');
            $this->Cell(35,3,'1','LR',0,'C');
            $this->Cell(6,3,'2','LR',0,'C');
            $this->Cell(41,3,'3','LR',0,'C');
            $this->Cell(20,3,'4','LR',0,'C');
            $this->Cell(10,3,'5','LR',0,'C');
            $this->Cell(6,3,'6','LR',0,'C');
            $this->Cell(30,3,'7','LR',0,'C');
            $this->Cell(6,3,'8','LR',0,'C');
            $this->Cell(6,3,'9','LR',0,'C');
            $this->Cell(3,3,'10','LR',0,'C');
            $this->Cell(3,3,'11','LR',0,'C');
            $this->Cell(3,3,'12','LR',0,'C');
            $this->Cell(3,3,'13','LR',0,'C');
            $this->Cell(3,3,'14','LR',0,'C');
            $this->Cell(3,3,'15','LR',0,'C');
            $this->Cell(3,3,'16','LR',0,'C');
            $this->Cell(3,3,'17','LR',0,'C');
            $this->Cell(3,3,'18','LR',0,'C');
            $this->Cell(3,3,'19','LR',0,'C');
            $this->Cell(3,3,'20','LR',0,'C');
            $this->Cell(6,3,'21','LR',0,'C');
            $this->Cell(3,3,'22','LR',0,'C');
            $this->Cell(3,3,'23','LR',0,'C');
            $this->Cell(30,3,'24','LR',0,'C');
            $this->Cell(20,3,'25','LR',0,'C');
            $this->Ln();

            $this->Cell(4,3,'','LR',0,'C');
            $this->Cell(20,3,'','LR',0,'C');
            $this->Cell(11,3,'Stoff',0,0,'C');
            $this->Cell(11,3,'Cod.',0,0,'C');
            $this->Cell(13,3,'Signal',0,0,'C');
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(41,3,'','LR',0,'C');
            $this->Cell(20,3,'','LR',0,'C');
            $this->Cell(10,3,'','LR',0,'C');
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(30,3,'','LR',0,'C');
            $this->Cell(6,3,'','LR',0,'C');
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
            $this->Cell(6,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(3,3,'','LR',0,'C');
            $this->Cell(30,3,'','LR',0,'C');
            $this->Cell(20,3,'','LR',0,'C');
            $this->Ln();

            $this->SetTextColor(0,0,0);
        }

        function BasicTable($data) {
            $count = 1;
            foreach($data as $row) {

                $this->SetFont('Arial','',6);
                $this->SetTextColor(75,135,190);
                $this->Cell(4,6,$count,1,0,'C');
                $this->SetTextColor(0,0,0);
                $this->Cell(20,6,$row[0],1,0,'C');
                if (strlen($row[1]) > 10) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 11, 6);
                    $this->MultiCell(11,2.8,utf8_decode($row[1]),0,'L');
                    $this->SetXY($x+11,$y);
                } else {
                    $this->Cell(11,6,utf8_decode($row[1]),1,0,'C');
                }
                $this->Cell(11,6,$row[2],1,0,'C');
                $this->Cell(13,6,$row[3],1,0,'C');
                $this->Cell(6,6,$row[4],1,0,'C');
                if (strlen($row[27]) > 40) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 41, 6);
                    $this->MultiCell(41,2.8,utf8_decode($row[27]),0,'L');
                    $this->SetXY($x+41,$y);
                } else {
                    $this->Cell(41,6,utf8_decode($row[27]),1,0,'C');
                }
                $this->Cell(20,6,$row[5],1,0,'C');
                $this->Cell(10,6,$row[6],1,0,'C');
                $this->Cell(6,6,$row[7],1,0,'C');
                $this->Cell(30,6,$row[8],1,0,'C');
                $this->Cell(6,6,$row[9],1,0,'C');
                $this->Cell(6,6,$row[10],1,0,'C');
                $this->Cell(3,6,$row[11],1,0,'C');
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
                $this->Cell(6,6,$row[22],1,0,'C');
                $this->Cell(3,6,$row[23],1,0,'C');
                $this->Cell(3,6,$row[24],1,0,'C');
                $this->Cell(30,6,$row[25],1,0,'C');
                $this->Cell(20,6,$row[26],1,0,'C');
              
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
            $this->MultiCell(30,5,'Messstellenliste Gewerk SEVA',0,'C');
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
            header('Content-Disposition: attachment; filename="SEF-Messstellenliste.pdf";');

            readfile($pdf->Output());
            
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>