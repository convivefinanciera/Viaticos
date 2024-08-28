<?php
require_once("../../librerias/fpdf/fpdf.php");
require_once("../../Controllers/conexion.php");

$id_solicitud = isset($_GET['id_solicitud']) ? $_GET['id_solicitud'] : '';

class PDF extends FPDF {
    function Footer () {
        global $line_height, $total_width;
        $this->SetY($this->GetPageHeight() - 10);
        $this->SetFont('Times', 'B');
        $this->Cell($total_width, $line_height, utf8_decode('Página '). $this->PageNo().' de {nb}', 0, 1, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$total_width = 180; $line_height = 4;
$pdf->SetMargins(15, 15, 0);
$pdf->SetAutoPageBreak(true, 10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 10);
$pdf->SetY(2);
$pdf->MultiCell($total_width, $line_height, utf8_decode("Formato de autorización definido para las SOFOM E.N.R.\nAutorización para solicitar Reportes de Crédito\nPersonas Físicas / Personas Morales"), 0, 'C');

#Traer los datos en base al ID_solicitud
$registro = array();
$consulta = $con->query("SELECT A.TipoPersona, A.ID_Cliente, CONCAT(A.Nombres, ' ', A.ApellidoP, ' ', A.ApellidoM) AS NombreDeContacto, A.RFC, A.CURP, A.Celular, B.Calle, B.Colonia, B.Municipio, B.Estado, B.CP, C.FolioConsulta, C.FechaConsulta, D.Archivo, D.OrientacionFirma
                        FROM tb_web_va_solicitud A, tb_web_va_domicilios B, tb_web_va_buro C, tb_web_va_docs D
                        WHERE A.ID_Solicitud = '$id_solicitud' AND 
                        B.ID_Solicitud = A.ID_Solicitud AND 
                        B.ID_Direccion = '1' AND 
                        C.ID_Solicitud = A.ID_Solicitud AND 
                        C.ID_Cliente = A.ID_Cliente AND 
                        D.ID_Solicitud = A.ID_Solicitud AND
                        D.ID_TipoDoc = 14 AND
                        D.Estatus = 1;");

while ($fila = $consulta->fetch_assoc()) {
    array_push($registro, $fila);
}
$error = false;
if ($registro) {
    $registro = $registro[0];
    $pdf->Ln(10);
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(61, $line_height, utf8_decode("Por este conducto autorizo expresamente a "), 0, 0, 'J');
    $pdf->SetFont('Times', 'B', 10);
    $pdf->Cell(71, $line_height, utf8_decode("Convive Financiera SA de CV SOFOM, E.N.R."), 0, 0, 'J');
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(($total_width - (66 + 72)), $line_height, utf8_decode(", para que por conducto de sus"), 0, 1, 'J');
    $pdf->MultiCell($total_width, $line_height, utf8_decode("funcionarios facultados lleve a cabo Investigaciones, sobre mi comportamiento crediticio o el de la Empresa que represento en Trans Union de México, S. A. SIC y/o Dun & Bradstreet, S.A. SIC "), 0, 'J');
    
    $pdf->Ln(5);
    $pdf->MultiCell($total_width, $line_height, utf8_decode("Asimismo, declaro que conozco la naturaleza y alcance de las sociedades de información crediticia y de la información contenida en los reportes de crédito y reporte de crédito especial, declaro que conozco la naturaleza y alcance de la información que se solicitará, del uso que"), 0, 'J');
    $pdf->SetXY(75, $pdf->GetY() - $line_height);
    $pdf->SetFont('Times', 'B', 10);
    $pdf->Cell(72, $line_height, utf8_decode("Convive Financiera SA de CV SOFOM, E.N.R."), 0, 0, 'J');
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(($total_width - (71 + 74)), $line_height, utf8_decode("hará de tal información y de que"), 0, 1, 'J');
    $pdf->MultiCell($total_width, $line_height, utf8_decode("ésta podrá realizar consultas periódicas sobre mi historial o el de la empresa que represento, consintiendo que esta autorización se encuentre vigente por un período de 3 años contados a partir de su expedición y en todo caso durante el tiempo que se mantenga la relación jurídica."), 0, 'J');
    
    $pdf->Ln(5);
    $pdf->MultiCell($total_width, $line_height, utf8_decode("En caso de que la solicitante sea una Persona Moral, declaro bajo protesta de decir verdad Ser Representante Legal de la empresa mencionada en esta autorización; manifestando que a la fecha de firma de la presente autorización los poderes no me han sido revocados, limitados, ni modificados en forma alguna."), 0, 'J');
    
    $pdf->Ln(5);
    $pdf->Cell($total_width, $line_height, utf8_decode("Autorización para:"), 0, 1, 'J');
    $pdf->SetFont('Times', 'B');
    $pdf->Cell($total_width, $line_height, utf8_decode("Persona Física (PF): ") . ($registro['TipoPersona'] == 'F' ? "_X_" : "___") . utf8_decode(" Persona Física con Actividad Empresarial (PFAE): ") . ($registro['TipoPersona'] == 'FAE' ? "_X_" : "___") . utf8_decode(" Persona Moral (PM): ") . ($registro['TipoPersona'] == 'M' ? "_X_" : "___"), 0, 1, 'J');
    
    $pdf->Ln(5);
    $pdf->SetFont('Times', '');
    $pdf->Cell($total_width, $line_height, utf8_decode("Nombre del solicitante (Persona Física o Razón Social de la Persona Moral):"), 0, 1, 'J');
    $pdf->SetFont('Times', 'B');
    $pdf->Cell($total_width, $line_height, utf8_decode($registro['NombreDeContacto']), 0, 1, 'J');
    $pdf->SetFont('Times', '');
    
    $pdf->Ln(5);
    $pdf->Cell($total_width, $line_height, utf8_decode("Para el caso de Persona Moral, nombre del Representante Legal:"), 0, 1, 'J');
    $pdf->SetFont('Times', 'B');
    $pdf->Cell($total_width, $line_height, $registro['TipoPersona'] != 'F' ? $registro['NombreDeContacto'] : '', 0, 1, 'J');
    $pdf->Ln(-4);
    $pdf->Cell($total_width, $line_height, str_repeat("_", 50), 0, 1, 'J');
    
    $pdf->Ln(5);
    $pdf->Cell(22, $line_height, utf8_decode("RFC o CURP:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 22), $line_height, $registro['TipoPersona'] == 'F' ? $registro['CURP'] : $registro['RFC'], 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(17, $line_height, utf8_decode("Domicilio:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 17), $line_height, utf8_decode($registro['Calle']), 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(14, $line_height, utf8_decode("Colonia:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 14), $line_height, utf8_decode($registro['Colonia']), 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(17, $line_height, utf8_decode("Municipio:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 17), $line_height, utf8_decode($registro['Municipio']), 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(12, $line_height, utf8_decode("Estado:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 12), $line_height, utf8_decode($registro['Estado']), 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(23, $line_height, utf8_decode("Código Postal:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 23), $line_height, utf8_decode($registro['CP']), 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(19, $line_height, utf8_decode("Teléfono(s):"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 19), $line_height, utf8_decode($registro['Celular']), 0, 1);
    $pdf->SetFont('Times', '');
    
    $pdf->Cell(67, $line_height, utf8_decode("Lugar y Fecha en que se firma la autorización:"), 0, 0);
    $pdf->Cell(($total_width - 67), $line_height, str_repeat("_", 50), 0, 1);
    
    $pdf->Cell($total_width, $line_height, utf8_decode("Nombre del funcionario que recaba la autorización: Alejandra Guillen Bustamante"), 0, 1, '');
    
    $pdf->Ln(5);
    $pdf->SetFont('Times', 'B');
    $pdf->MultiCell($total_width, $line_height, utf8_decode("Estoy consciente y acepto que este documento quede bajo custodia de Convive Financiera SA de CV SOFOM, E.N.R y/o Sociedad de Información Crediticia consultada para efectos de control y cumplimiento del artículo 28 de la Ley para Regular las Sociedades de Información Crediticia; mismo que señala que las Sociedades sólo podrán proporcionar información a un Usuario, cuando éste cuente con la autorización expresa del Cliente mediante su firma autógrafa."), 0, 'J');
    
    #Obtenemos el tipo de imagen.
    // echo $registro['Archivo'];
    $decoded_base64 = explode(',', $registro['Archivo'], 2);
    $tipo = explode(';', $decoded_base64[0])[0];
    $tipo = explode('/', $tipo)[1];

    $landscapeimage = true; $x = 70; $width = 70; $height = 25; $y = $pdf->GetY() - 3;
    if ($registro['OrientacionFirma'] == 'V') {
        $landscapeimage = false; $x = 70; $width = 70; $height = 30; $y = $pdf->GetY() - 3;

        if (base64_encode(base64_decode($decoded_base64[1])) === $decoded_base64[1]) {
            $imageData = base64_decode($decoded_base64[1]);
            // Create temporary image resource
            $source = imagecreatefromstring($imageData);
            // Rotate the image
            $rotated = imagerotate($source, 90, 0);
            
            ob_start();
            imagesavealpha($rotated, true);
            imagepng($rotated);
            $base64ImageRotated = $decoded_base64[0] . ',' . base64_encode(ob_get_clean());
            // var_dump(strlen($base64ImageRotated) % 4); #2
            imagedestroy($source);
            // imagedestroy($rotated);
        }

        // echo $base64ImageRotated;
    }

    $pdf->Image($landscapeimage ? $registro['Archivo'] : $base64ImageRotated, $x, $y, $width, $height, $tipo); #Mostramos la imagen que viene de la base de datos.
    
    // $pdf->SetXY($x, $y);
    // $pdf->Cell($width, $height, '', 1, 0);
    
    $pdf->Ln(20);
    $pdf->SetFont('Times', '');
    $pdf->Cell($total_width, $line_height, str_repeat('_', 40), 0, 1, 'C');
    $pdf->Cell($total_width, $line_height, utf8_decode($registro['NombreDeContacto']), 0, 1, 'C');
    
    $pdf->Ln(5);
    
    $pdf->SetFillColor(184, 184, 184);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $total_width, 16, 'F');
    
    $pdf->SetFont('Times', 'B');
    $pdf->Cell($total_width, $line_height, utf8_decode("Para uso exclusivo de la Empresa que efectúa la consulta Convive Financiera, S.A de C.V SOFOM E.N.R,"), 0, 1);
    
    $pdf->SetFont('Times', '');
    $pdf->Cell(34, $line_height, utf8_decode("Fecha de Consulta BC:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 34), $line_height, date_format(date_create(substr($registro['FechaConsulta'], 0, 10)), 'Y/m/d'), 0, 1);
    
    $pdf->SetFont('Times', '');
    $pdf->Cell(33, $line_height, utf8_decode("Folio de Consulta BC:"), 0, 0);
    $pdf->SetFont('Times', 'U');
    $pdf->Cell(($total_width - 33), $line_height, $registro['FolioConsulta'], 0, 1);
    
    $pdf->Ln(8);
    $pdf->SetFont('Times', 'BU');
    $pdf->Cell(27, $line_height, utf8_decode("IMPORTANTE:"), 0, 0);
    $pdf->SetFont('Times', '');
    $pdf->MultiCell(($total_width - 27), $line_height, utf8_decode("Este formato debe ser llenado individualmente, para una sola persona física ó para una sola empresa. En"), 0, 'J');
    $pdf->Cell($total_width, $line_height, utf8_decode("caso de requerir el Historial crediticio del representante legal, favor de llenar un formato adicional."), 0, 1, 'J');
    // $pdf->Output(); #comentar cuando se descomente el Output de abajo.
    $content = base64_encode($pdf->Output('S')); #descomentar para subirlo como base64 a la base de datos.
    //subir el archivo a la base de datos.
    $tamanio_archivo = strlen($content);
    $id_cliente = $registro['ID_Cliente'];
    $nombre_carta = "CartaAutorizacion_" . $id_solicitud;
    $sql = "INSERT INTO tb_web_va_docs(ID_Solicitud, ID_Cliente, ID_TipoDoc, Archivo, Nombre_Archivo, Tamanio_Archivo, Estatus) 
                    VALUES('$id_solicitud', '$id_cliente', 15, '$content', '$nombre_carta', '$tamanio_archivo', 1)";
    $consulta = $con->prepare($sql);
    
    if (!$consulta->execute()) {
        $error = true;
    }
}
else {
    // $pdf->Ln(12);
    // $pdf->Cell($total_width, $line_height, "Sin Registros.", 0, 1, 'C');
    $error = true;
}
$conn = null;
// var_dump($registro);
die(!$error ? 'Creado' : 'Fallo');

?>