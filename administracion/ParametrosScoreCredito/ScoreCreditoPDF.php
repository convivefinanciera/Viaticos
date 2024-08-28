<?php

require_once("../../librerias/fpdf/fpdf.php"); //librería para los PDFs
require_once("../../Controllers/conexion.php"); //Conexión a la BD

class PDF extends FPDF {
    function Header () {
        global $linea_credito, $font_size, $line_height, $total_w_wo_m;
        $this->Image('../../img/convivelogo.jpg', 15, 3, 38, null, 'JPG');
        $this->SetFont('Times', 'B', $font_size);
        $this->Cell($total_w_wo_m, $line_height, utf8_decode('CONTRATO LÍNEA DE CRÉDITO No. ') . $linea_credito, 0, 1, 'R');
        $this->SetY(40);
    }
}

$pdf = new PDF('P', 'mm', 'Letter');

#TRAER LOS DATOS DEL SCORE EN BASE AL ID_SOLICITUD
$id_solicitud = isset($_GET['id_solicitud']) ? $_GET['id_solicitud'] : "SOL-1234-AAA";

// $datos = json_decode(file_get_contents('php://input'), true);
$parametros_score = $registro = array();

$consulta_sol = $con->query("SELECT A.TipoPersona, A.RazonSocial, A.NombreDeContacto, A.MontoSolicitado, A.FechaAlta, B.Sucursal, C.LineaCreditoID
                            FROM tb_web_va_solicitud A, tb_web_va_sucursales B, lineascredito C  WHERE A.ID_Solicitud = '" . $id_solicitud . "' AND A.ID_Sucursal = B.ID_Sucursal AND C.ClienteID = A.ID_Cliente;");
while ($fila = $consulta_sol->fetch_assoc()) {
    array_push($registro, $fila);
}
$registro = $registro[0];

$consulta = $con->query("SELECT A.ID_Parametro, A.Parametro, A.Valor, B.Calificacion, FORMAT(( A.Valor * B.Calificacion ) / 100,2) AS 'Puntuacion'
                        FROM tb_web_va_scoreparametros A, tb_web_va_scorecredito B  
                        WHERE B.ID_Parametro = A.ID_Parametro AND 
                        A.ID_Parametro > 1 AND 
                        B.ID_Solicitud = '" . $id_solicitud . "' 
                        UNION SELECT '' AS 'ID_Parametro', 'TOTAL' AS 'Parametro', '-' AS 'Valor', '-' AS 'Calificacion', 
                        FORMAT(SUM(( A.Valor * B.Calificacion )) / 100,2) AS 'Puntuacion'
                        FROM tb_web_va_scoreparametros A, tb_web_va_scorecredito B  
                        WHERE B.ID_Parametro = A.ID_Parametro AND A.ID_Parametro > 1 AND B.ID_Solicitud = '" . $id_solicitud . "';");
while ($fila = $consulta->fetch_assoc()) {
    // $parametro = $fila['Parametro'];
    $parametro = implode('_', explode(' ', str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], strtolower($fila['Parametro']))));
    $parametros_score[$parametro] = $fila['Puntuacion'];
}

$total_w_wo_m = 180;
$margen_i = $margen_d = $margen_t = 15;
$font_size = 10;
$line_height = 4;
$linea_credito = $registro['LineaCreditoID'];

$pdf->SetMargins($margen_i, $margen_d, $margen_t);
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', $font_size);


$pdf->Cell($total_w_wo_m, $line_height, utf8_decode('SCORE CRÉDITO VENTACERO'), 0, 1, 'C');
$pdf->Ln(3);

$pdf->MultiCell(60, $line_height, utf8_decode("Nombre o Razón Social del \n Solicitante"), 1, 'R');
$pdf->SetXY($margen_i + 60, $pdf->GetY() - $line_height * 2);
$pdf->SetFont('Arial', '');
$pdf->Cell(120, $line_height * 2, $registro['TipoPersona'] == 'F' ? $registro['NombreDeContacto'] : $registro['RazonSocial'], 'TRB', 1, 'R');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(60, ($line_height * 2), utf8_decode("Monto Solicitado"), 'RBL', 0, 'R');
$pdf->SetFont('Arial', '');
$pdf->Cell(120, ($line_height * 2), '$ ' . number_format($registro['MontoSolicitado'], 2, '.', ','), 'RB', 1, 'R');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(60, ($line_height * 2), utf8_decode("Sucursal VentAcero"), 'RBL', 0, 'R');
$pdf->SetFont('Arial', '');
$pdf->Cell(120, ($line_height * 2), $registro['Sucursal'], 'RB', 1, 'R');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(60, ($line_height * 2), utf8_decode("Fecha de Registro de Solicitud"), 'RBL', 0, 'R');
$pdf->SetFont('Arial', '');
$pdf->Cell(120, ($line_height * 2), date_format(date_create(substr($registro['FechaAlta'], 0, 10)), 'Y/m/d'), 'RB', 1, 'R');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(60, ($line_height * 2), utf8_decode("Calificación Score"), 'RBL', 0, 'R');
$pdf->SetFont('Arial', '');
$pdf->Cell(120, ($line_height * 2), $parametros_score['total'], 'RB', 1, 'R');

$pdf->Ln(15);

$line_height = 5;
$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("Parámetros"), 1, 0, 'C');
$pdf->Cell(90, $line_height, utf8_decode("Calificación"), 'TRB', 1, 'C');

$pdf->Cell(90, $line_height, utf8_decode("2"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score['fotografias_del_negocio'], "RB", 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("3"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score['consulta_de_buro_de_credito'], 'RB', 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("4"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score["identificacion_oficial_del_representante_legal"], 'RB', 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("5"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score["opinion_positiva_del_sat"], 'RB', 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("6"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score["constancia_de_situacion_fiscal"], 'RB', 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("7"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score["comprobantes_de_domicilio"], 'RB', 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("8"), 'RBL', 0, 'C');
$pdf->SetFont('Arial', '');
$pdf->Cell(90, $line_height, $parametros_score["acta_constitutiva"], 'RB', 1, 'C');

$pdf->SetFont('Arial', 'B');
$pdf->Cell(90, $line_height, utf8_decode("TOTAL"), 'RBL', 0, 'C');
$pdf->Cell(90, $line_height, $parametros_score["total"], 'RB', 1, 'C');

$pdf->Ln(25);

$pdf->Cell($total_w_wo_m, $line_height, utf8_decode("EL ACREDITANTE"), 0, 1, 'C');
$pdf->Image('../../img/FirmaJuanito.png', $pdf->GetX() + 65, $pdf->GetY() - 10, 38, null, 'PNG');
$pdf->Ln(20);
$pdf->Cell($total_w_wo_m, $line_height, str_repeat("_", 60), 0, 1, 'C');
$pdf->Ln(2);
$pdf->MultiCell($total_w_wo_m, $line_height, utf8_decode("CONVIVE FINANCIERA, S.A. DE C.V., SOFOM, E.N.R. \nRepresentado por \nJUAN RAMIREZ CISNEROS"), 0, 'C');

$pdf->Output();

?>