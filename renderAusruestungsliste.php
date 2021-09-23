<?php
    include('connect.php');
    require('libs/fpdf.php');

    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", $_SERVER['DOCUMENT_ROOT'] . "/php-error.log");

    $method = $_SERVER['REQUEST_METHOD'];

    $query = "SELECT CONCAT_WS('.', `AKZ_Gr1_Standort`,`AKZ_Gr2_Anlagenteil`,`AKZ_Gr3_Aggregat`,`AKZ_Gr4_Nummer`,`AKZ_Gr5_Aggregat`,`AKZ_Gr6_Nummer`) AS `AKZ Kodierung`, `Benennung` AS 'Bezeichnung', `Hersteller`, `Typ`, `Medium`, `Nennleistung`, `Nennspannung`, `Fördervolumen`, `Drehzahl`, `max. zul. Druck`, `max. zul. Temperatur`, `Volumen`, `Fläche`, `Gewicht`, `Werkstoff`, `Bauart`, `Zugehörige Sicherheitseinrichtung`, `Bemerkung` FROM `Gesamtdatenbank`";
    
    $data = mysqli_fetch_all($con->query($query));

    $headerLine = array(
        "AKZ Kodierung",
        "Benennung",
        "Hersteller",
        "Typ",
        "Medium",
        "Nennleistung",
        "Nennspannung",
        "Fördervolumen",
        "Drehzahl",
        "max. zul. Druck", 
        "max. zul. Temperatur",
        "Volumen",
        "Fläche",
        "Gewicht",
        "Werkstoff",
        "Bauart",
        "Zugehörige Sicherheitseinrichtung", 
        "Bemerkung"
    );

    class PDF extends FPDF {
        // Page header
        function Header() {
            global $headerLine;

            $this->Image("./img/logo.png",245,5,50);
            $this->Ln(10);
            $this->SetFont('Arial','B',14);
            $this->Cell(105,16,'',1);
            $this->Cell(105,16,utf8_decode('Ausrüstungsliste'),1,0,'C');
            $this->Cell(65,16,'',1);
            $this->Ln();

            foreach($headerLine as $col) {
                $this->SetFont('Arial','B',7);
                if ($col == "AKZ Kodierung") {
                    $this->Cell(20,10,$col,1,0,'C');
                } elseif ($col == "Benennung") {
                    $this->Cell(25,10,$col,1,0,'C');
                } elseif ($col == "Hersteller" || $col == "Typ" || $col == "Medium" || $col == "Drehzahl") {
                    $this->Cell(15,10,$col,1,0,'C');
                } elseif ($col == "Zugehörige Sicherheitseinrichtung") {
                    $col = "Zugehörige\nSicherheits-\neinrichtung";
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 17, 10);
                    $this->SetFont('Arial','B',5);
                    $this->MultiCell(17,3.3,utf8_decode($col),0,'C');
                    $this->SetFont('Arial','B',7);
                    $this->SetXY($x+17,$y);
                } elseif ($col == "Nennleistung" || $col == "Nennspannung" || $col == "Fördervolumen") {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 12, 10);
                    $this->MultiCell(12,5,utf8_decode($col),1,'C');
                    $this->SetXY($x+12,$y);
                } elseif ($col == "max. zul. Druck") {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 12, 10);
                    $this->SetFont('Arial','B',5);
                    $this->MultiCell(12,5,utf8_decode($col),1,'C');
                    $this->SetFont('Arial','B',7);
                    $this->SetXY($x+12,$y);
                } elseif ($col == "max. zul. Temperatur") {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 15, 10);
                    $this->SetFont('Arial','B',5);
                    $this->MultiCell(15,5,utf8_decode($col),1,'C');
                    $this->SetFont('Arial','B',7);
                    $this->SetXY($x+15,$y);
                } elseif ($col == "Bemerkung") {
                    $this->MultiCell(15,5,$col,1,'C');
                } else {
                    $this->Cell(15,10,utf8_decode($col),1,0,'C');
                }
            }
        }

        function BasicTable($data) {

            foreach($data as $row) {
                $this->SetFont('Arial','',7);
                $this->Cell(20,8,utf8_decode($row[0]),1);
                if (strlen($row[1]) > 16) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 25, 8);
                    $this->SetFont('Arial','B',5);
                    $this->MultiCell(25,4,utf8_decode($row[1]),0,'L');
                    $this->SetFont('Arial','B',7);
                    $this->SetXY($x+25,$y);
                } else {
                    $this->Cell(25,8,utf8_decode($row[1]),1);
                }
                if (strlen($row[2]) > 12) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 15, 8);
                    $this->Cell(15,4,utf8_decode($row[2]),0,'L');
                    $this->SetXY($x+15,$y);
                } else {
                    $this->Cell(15,8,utf8_decode($row[2]),1);
                }
                $this->Cell(15,8,utf8_decode($row[3]),1);
                if (strlen($row[4]) > 14) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 15, 8);
                    $this->SetFont('Arial','B',5);
                    $this->Cell(15,4,utf8_decode($row[4]),0,'L');
                    $this->SetFont('Arial','B',7);
                    $this->SetXY($x+15,$y);
                } elseif (strlen($row[4]) > 10) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 15, 8);
                    $this->SetFont('Arial','B',5);
                    $this->Cell(15,8,utf8_decode($row[4]),0,'L');
                    $this->SetFont('Arial','B',7);
                    $this->SetXY($x+15,$y);
                } else {
                    $this->Cell(15,8,utf8_decode($row[4]),1);
                }
                $this->Cell(12,8,utf8_decode($row[5]),1);
                $this->Cell(12,8,utf8_decode($row[6]),1);
                $this->Cell(12,8,utf8_decode($row[7]),1);
                $this->Cell(15,8,utf8_decode($row[8]),1);
                $this->Cell(12,8,utf8_decode($row[9]),1);
                if (strlen($row[10]) > 25) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 15, 8);
                    $this->MultiCell(15,4,utf8_decode($row[10]),0,'L');
                    $this->SetXY($x+15,$y);
                } else {
                    $this->Cell(15,8,utf8_decode($row[10]),1);
                }
                $this->Cell(15,8,utf8_decode($row[11]),1);
                $this->Cell(15,8,utf8_decode($row[12]),1);
                $this->Cell(15,8,utf8_decode($row[13]),1);
                $this->Cell(15,8,utf8_decode($row[14]),1);
                $this->Cell(15,8,utf8_decode($row[15]),1);
                $this->Cell(17,8,utf8_decode($row[16]),1);
                if (strlen($row[17]) > 10) {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x, $y, 15, 8);
                    $this->SetFont('Arial','',5);
                    $this->MultiCell(15,4,utf8_decode($row[17]),0,'L');
                    $this->SetFont('Arial','',7);
                    $this->SetXY($x+15,$y+4);
                } else {
                    $this->Cell(15,8,utf8_decode($row[17]),1);
                }
                $this->Ln();
            }
        }
    
        function Footer() {
            $this->SetY(-20);
            $this->SetFont('Arial','',7.5);
            $this->Cell(8,10,'Datei: ',0,0,'L');
            $this->Cell(30,10,utf8_decode('SEF-Ausrüstungsliste.pdf'),0,0,'L');
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
            $this->MultiCell(30,5,utf8_decode('Ausrüstungsliste Gewerk VA'),0,'C');
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
    $pdf->SetFont('Arial','',8);
    $pdf->AddPage();
    $pdf->BasicTable($data);
    $pdf->AliasNbPages('{nb}');

    switch ($method) {
        case 'GET':
            header('Content-type: application/pdf');
            header('Access-Control-Allow-Origin: *');
            header('Content-Disposition: attachment; filename="SEF-Ausrüstungsliste.pdf";');

            readfile($pdf->Output());
            
            break;
        default:
            echo http_response_code(403);
            break;
    }
?>