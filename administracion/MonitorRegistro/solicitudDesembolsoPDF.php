<?php
// header("Content-type: application/pdf");
require_once('../../Controllers/conexion.php'); #conexion a la base de datos
require_once('../../librerias/fpdf/fpdf.php'); #libreria para generar el PDF
require_once('../../Controllers/CredencialesFIRMAMEX.php'); #credenciales de FIRMAMEX
require_once('../../Controllers/firmamex_services.php'); #clase de firmamex
require_once('../../librerias/CifrasEnLetras.php'); #Cifras a Letras

// Crear instancia del cifrado a letras
$letras = new CifrasEnLetras();

$id_solicitud = isset($_GET['id_solicitud']) ? $_GET['id_solicitud'] : "";

class PDF extends FPDF
{
    // MODIFICAMOS LA CABECERA DEL PDF
    function Header()
    {
        //Imagen de CONVIVE
            $this->Image('../../img/convivelogo.jpg', 8, 6, 20, null, 'JPG');
            $this->SetFont('Times', 'B', 8);
            $this->Cell((204 - 28), 3.5, utf8_decode("REGISTRO SOLICITUD"), 0, 0, 'R');
            // $this->SetFont('Times', 'BU');
            $this->SetY(20);


            $this->Image('../../img/ventacero_icono_mini.png', 8 + 20 + 5, 6, 20, null, 'PNG');
            $this->SetFont('Times', 'B', 8);
            $this->SetY(20);

    }

    function Separador ($x, $y) {
        global $margen_i;
        global $total_w_wo_m;
        $this->SetDrawColor(51, 51, 51);
        $this->SetLineWidth(0.1);
        $this->Rect($margen_i, 25.5, $total_w_wo_m, 13, 'D'); //seccion1
    }
}

$pdf = new PDF('P','mm','Letter');
$margen_i = $margen_d = $margen_t = 6;
$total_w_wo_m = 204;
$font_size = 8.5;
$pdf->SetMargins($margen_i, $margen_d, $margen_t);
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();

// TODO: si la primera consulta no arroja registros hacer catch
$array_gral_pros_calif = $array_dom = $array_ref = true;
# traer los datos generales y prospección del cliente (sin registro)
$consulta = $con->query("SELECT A.Nombres, A.ApellidoP, A.ApellidoM, A.Genero, A.FechaNacimiento, A.MontoSolicitado, A.TipoPersona, A.LugarNacimiento, A.CURP, A.RFC, A.Celular, A.Correo,
                            A.RazonSocial, A.GiroMercantil, A.FechaConstitucion, A.RFC_RL,
                            B.LocalPropio, B.EsFilial, B.Propietario, B.DescripFilial, B.TiempoNegocio, B.TelefonoNeg, B.ContactoCompras, B.TelefonoCompras, B.ExtCompras, B.CorreoCompras, 
                            B.ContactoPagos, B.TelefonoPagos, B.ExtPagos, B.CorreoPagos, B.AceptaFE, B.VisitoCliente, B.Nivel, B.QuienVisito, B.Zona, B.ListaPrecios, B.Sector, B.ProductosConsume, 
                            B.ProductoAVender, B.ProyeccionVenta, B.OtrosProveedores, B.ConsumoAprox, B.ProyEspecialOFrec, C.Calificacion
                        FROM tb_web_va_solicitud A, tb_web_va_prospeccioncliente B, tb_web_va_scorecredito C
                        WHERE B.ID_Solicitud = A.ID_Solicitud AND
                        A.ID_Solicitud = C.ID_Solicitud AND
                        C.ID_Parametro = 0 AND
                        C.Estatus = 'A' AND
                        A.ID_Solicitud = '$id_solicitud';");
$general_prospeccion_calif = [];
$registro_gpc = [];

if (!$consulta) {
    die("Query failed: " . $con->error);
}

if ($consulta->num_rows > 0) {
    while ($fila = $consulta->fetch_assoc()) {
        array_push($registro_gpc, $fila);
    }
} else {
    die("No se encontraron los registros necesarios para construir el PDF de manera correcta.");
}

if (!count($registro_gpc)) {
    $array_gral_pros_calif = false;
}

# trer los adatos del domicilio
$domicilios = $con->query("SELECT * FROM tb_web_va_domicilios WHERE ID_Solicitud = '$id_solicitud';");
$registro_dom = [];
if ($domicilios) {
    while ($fila = $domicilios->fetch_assoc()) {
        array_push($registro_dom, $fila);
    }
}
if (!count($registro_dom)) {
    $array_dom = false;
}

# traer los datos de referencias comerciales
$referencias = $con->query("SELECT * FROM tb_web_va_refcomerciales WHERE ID_Solicitud = '$id_solicitud';");
$registro_refs = [];
if ($referencias) {
    while ($fila = $referencias->fetch_assoc()) {
        array_push($registro_refs, $fila);
    }
}

# Calificacion crediticia
$query = "
    SELECT B.Parametro, B.Valor, A.Calificacion
    FROM tb_web_va_scorecredito A
    JOIN tb_web_va_scoreparametros B ON A.ID_Parametro = B.ID_Parametro
    WHERE A.ID_Solicitud = '$id_solicitud'
    AND A.Estatus = 'A'
    AND B.Estatus = 'A'
    AND A.ID_Parametro != 0;
";

$calificacion = $con->query($query);

if (!$calificacion) {
    echo "Query failed: " . $con->error;
    exit;
}

$data = [];
$totalCalificacion = 0;

while ($row = $calificacion->fetch_assoc()) {
    $ponderado = (float)$row['Valor'] * (float)$row['Calificacion'] / 100;
    $row['Ponderado'] = number_format($ponderado, 2);
    $data[] = $row;
    $totalCalificacion += $ponderado;
}

if (!count($registro_refs)) {
    $array_ref = false;
}

/* ////// FUNCIONES DE UI ////// */
function makeOneLineField($pdf, $title, $value) {
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $title, 0, 1);
    $pdf->SetFillColor(230, 228, 227);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, utf8_decode($value), 0, 1, 'L', true);
}

function makeTwoFields($pdf, $title1, $title2, $value1, $value2, $totalWidth = 230, $leftMargin = 5, $rightMargin = 20, $titleCellWidth = 100, $valueCellWidth = 100) {
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);

    $gap = $totalWidth - $leftMargin - $rightMargin - 2 * $titleCellWidth;

    $pdf->SetX($leftMargin);
    $pdf->Cell($titleCellWidth, 10, $title1, 0, 0);

    $pdf->SetX($leftMargin + $titleCellWidth + $gap);
    $pdf->Cell($titleCellWidth, 10, $title2, 0, 1);

    $pdf->SetFillColor(230, 228, 227);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);

    $pdf->SetX($leftMargin);
    $pdf->Cell($valueCellWidth, 10, utf8_decode($value1), 0, 0, 'L', true);

    $pdf->SetX($leftMargin + $titleCellWidth + $gap);
    $pdf->Cell($valueCellWidth, 10, utf8_decode($value2), 0, 1, 'L', true);
}

function makeSpace($pdf, $value){
    $bottomMargin = 5;
    $pdf->Cell(0, $value, '', 0, 1);
}

function makeTitleRectangle($pdf, $x, $y, $width, $height, $text) {
    $pdf->SetFillColor(241, 90, 38);
    $pdf->SetDrawColor(252, 151, 50); // Borde
    $pdf->SetLineWidth(0.1);
    $pdf->Rect($x, $y, $width, $height, 'DF');
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetXY($x, $y + 2);
    $pdf->Cell($width, 10, utf8_decode($text), 0, 1, 'C');
}

function makeLineDivision($pdf){
    $pdf->SetDrawColor(255, 165, 0);
    $pdf->SetLineWidth(1);
    $currentY = $pdf->GetY();
    $pdf->Line(10, $currentY + 5, 200, $currentY + 5);
}

/*------------------------CONTENIDO DEL PDF------------------------*/

////////////// DATOS GENERALES DEL CLIENTE //////////////
makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, 'DATOS GENERALES DEL CLIENTE');

//------------TIPO PERSONA-------//
makeSpace($pdf, 5);
$tipoPersona = isset($registro_gpc[0]) ? $registro_gpc[0]['TipoPersona'] : 'No disponible';
if ($tipoPersona == 'F'){
    $tipoPersona = 'Persona Física';
}
if ($tipoPersona == 'M'){
    $tipoPersona = 'Persona Moral';
}
if ($tipoPersona == 'FAE'){
    $tipoPersona = 'Persona Física con Actividad Empresarial';
}
makeOneLineField($pdf, 'Tipo de persona: ', $tipoPersona ? $tipoPersona : 'No disponible');

//---------DIBUJAR CAMPOS DEPENDIENDO DEL TIPO DE PERSONA---------//
if ($tipoPersona == 'Persona Física'){
    //------------NOMBRES-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Nombres: ', isset($registro_gpc[0]) ? $registro_gpc[0]['Nombres'] : 'No disponible');

    //------------APELLIDOS-------//
    makeSpace($pdf, 2);
    $apellidoPaterno = $registro_gpc[0] ? $registro_gpc[0]['ApellidoP'] : 'No disponible';
    $apellidoMaterno = $registro_gpc[0] ? $registro_gpc[0]['ApellidoM'] : 'No disponible';
    makeTwoFields($pdf, 'Apellido Paterno', 'Apellido Materno', $apellidoPaterno, $apellidoMaterno);

    //------------FECHA Y LUGAR DE NACIMIENTO-------//
    makeSpace($pdf, 2);
    $fechaNacimiento = $registro_gpc[0] ? $registro_gpc[0]['FechaNacimiento'] : 'No disponible';
    $lugarNacimiento = $registro_gpc[0] ? $registro_gpc[0]['LugarNacimiento'] : 'No disponible';
    makeTwoFields($pdf, 'Fecha de nacimiento', 'Lugar de nacimiento', $fechaNacimiento, $lugarNacimiento); // TODO: COMPLETAR LUGAR NACIMIENTO (DB SOLO TRAE ACRONIMOS)

    //------------GENERO-------//
    makeSpace($pdf, 2);
    $gender = isset($registro_gpc[0]) ? $registro_gpc[0]['Genero'] : 'No disponible';
    $fullGenderName = $gender === 'H' ? 'Hombre' : ($gender === 'F' ? 'Femenino' : $gender);
    makeOneLineField($pdf, 'Genero:', $fullGenderName);

    //------------CURP-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'CURP:', isset($registro_gpc[0]) ? $registro_gpc[0]['CURP'] : 'No disponible');

    //------------RFC-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'RFC: ', isset($registro_gpc[0]) ? $registro_gpc[0]['RFC'] : 'No disponible');

    //------------CELULAR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Celular: ', isset($registro_gpc[0]) ? $registro_gpc[0]['Celular'] : 'No disponible');

    //------------CORREO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Correo: ', isset($registro_gpc[0]) ? $registro_gpc[0]['Correo'] : 'No disponible');

    //------------MONTO SOLICITADO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Monto Solicitado: ', isset($registro_gpc[0]) ? $registro_gpc[0]['MontoSolicitado'] : 'No disponible');


}

if ($tipoPersona == 'Persona Moral'){
    //------------RAZON SOCIAL-------//
    makeOneLineField($pdf, utf8_decode('Razón Social '), isset($registro_gpc[0]) ? $registro_gpc[0]['RazonSocial'] : 'No disponible');

    //------------RFC DE LA RAZON SOCIAL-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('RFC de la Razón Social '), isset($registro_gpc[0]) ? $registro_gpc[0]['RFC'] : 'No disponible');

    //------------GIRO MERCANTIL-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Giro Mercantil '), isset($registro_gpc[0]) ? $registro_gpc[0]['GiroMercantil'] : 'No disponible');

    //------------FECHA CONSTITUCION-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Fecha Constitución '), isset($registro_gpc[0]) ? $registro_gpc[0]['FechaNacimiento'] : 'No disponible');

    makeLineDivision($pdf);

    //------------DATOS DEL REPRESENTANTE LEGAL------//
    makeSpace($pdf, 7);
    $pdf->SetTextColor(14, 46, 153);
    $pdf->SetFont('Arial', '', 15);
    $pdf->Cell(0, 10, 'Datos del Representante Legal', 0, 1);

    //------------NOMBRES-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Nombre(s): '), isset($registro_gpc[0]) ? $registro_gpc[0]['Nombres'] : 'No disponible');

    //------------APELLIDO PATERNO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Apellido Paterno '), isset($registro_gpc[0]) ? $registro_gpc[0]['ApellidoP'] : 'No disponible');

    //------------APELLIDO PATERNO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Apellido Materno '), isset($registro_gpc[0]) ? $registro_gpc[0]['ApellidoM'] : 'No disponible');

    //------------RFC RL-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('RFC '), isset($registro_gpc[0]) ? $registro_gpc[0]['RFC_RL'] : 'No disponible');

    $pdf->AddPage();

    //------------FECHA NACIMIENTO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Fecha de Nacimiento '), isset($registro_gpc[0]) ? $registro_gpc[0]['RFC_RL'] : 'No disponible');

    //------------CELULAR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Celular '), isset($registro_gpc[0]) ? $registro_gpc[0]['Celular'] : 'No disponible');

    //------------CORREO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Correo '), isset($registro_gpc[0]) ? $registro_gpc[0]['Correo'] : 'No disponible');

    makeLineDivision($pdf);

    //------------MONTO SOLICITADO-------//
    makeSpace($pdf, 7);
    makeOneLineField($pdf, utf8_decode('Monto solicitado '), isset($registro_gpc[0]) ? $registro_gpc[0]['MontoSolicitado'] : 'No disponible');
}

    ////////////// DOMICILIO PARTICULAR //////////////
    $pdf->AddPage();
    makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, 'DOMICILIO PARTICULAR');

    //------------CALLE-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Calle: ', isset($registro_dom[0]) ? $registro_dom[0]['Calle'] : 'No disponible');

    //------------NUMERO EXTERIOR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Número exterior: '), isset($registro_dom[0]) ? $registro_dom[0]['NumExt'] : 'No disponible');

    //------------NUMERO INTERIOR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Número interior: '), isset($registro_dom[0]) ? $registro_dom[0]['NumInt'] : 'No disponible');

    //------------CODIGO POSTAL-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Código postal: '), isset($registro_dom[0]) ? $registro_dom[0]['CP'] : 'No disponible');

    //------------COLONIA-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Colonia: ', isset($registro_dom[0]) ? $registro_dom[0]['Colonia'] : 'No disponible');

    //------------MUNICIPIO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Municipio: ', isset($registro_dom[0]) ? $registro_dom[0]['Municipio'] : 'No disponible');

    //------------ESTADO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Estado: ', isset($registro_dom[0]) ? $registro_dom[0]['Estado'] : 'No disponible');

    //------------CIUDAD-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Ciudad: ', isset($registro_dom[0]) ? $registro_dom[0]['Ciudad'] : 'No disponible');

    //------------ANTIGUEDAD-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Antigüedad (en años): '), isset($registro_dom[0]) ? $registro_dom[0]['Antiguedad'] : 'No disponible');

    ////////////// DOMICILIO FISCAL //////////////
    $pdf->AddPage();
    makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, 'DOMICILIO FISCAL');
    
    //------------CALLE-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Calle: ', isset($registro_dom[1]) ? $registro_dom[1]['Calle'] : 'No disponible');
    
    //------------NUMERO EXTERIOR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Número exterior: '), isset($registro_dom[1]) ? $registro_dom[1]['NumExt'] : 'No disponible');
    
    //------------NUMERO INTERIOR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Número interior: '), isset($registro_dom[1]) ? $registro_dom[1]['NumInt'] : 'No disponible');
    
    //------------CODIGO POSTAL-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Código postal: '), isset($registro_dom[1]) ? $registro_dom[1]['CP'] : 'No disponible');
    
    //------------COLONIA-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Colonia: ', isset($registro_dom[1]) ? $registro_dom[1]['Colonia'] : 'No disponible');
    
    //------------MUNICIPIO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Municipio: ', isset($registro_dom[1]) ? $registro_dom[1]['Municipio'] : 'No disponible');
    
    //------------ESTADO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Estado: ', isset($registro_dom[1]) ? $registro_dom[1]['Estado'] : 'No disponible');
    
    //------------CIUDAD-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Ciudad: ', isset($registro_dom[1]) ? $registro_dom[1]['Ciudad'] : 'No disponible');
    
    //------------ANTIGUEDAD-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Antigüedad (en años): '), isset($registro_dom[1]) ? $registro_dom[1]['Antiguedad'] : 'No disponible');

    ////////////// DOMICILIO NEGOCIO //////////////
    $pdf->AddPage();
    makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, 'DOMICILIO NEGOCIO');
        
    //------------CALLE-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Calle: ', isset($registro_dom[2]) ? $registro_dom[2]['Calle'] : 'No disponible');
        
    //------------NUMERO EXTERIOR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Número exterior: '), isset($registro_dom[2]) ? $registro_dom[2]['NumExt'] : 'No disponible');
        
    //------------NUMERO INTERIOR-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Número interior: '), isset($registro_dom[2]) ? $registro_dom[2]['NumInt'] : 'No disponible');
        
    //------------CODIGO POSTAL-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Código postal: '), isset($registro_dom[2]) ? $registro_dom[2]['CP'] : 'No disponible');
        
    //------------COLONIA-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Colonia: ', isset($registro_dom[2]) ? $registro_dom[2]['Colonia'] : 'No disponible');
        
    //------------MUNICIPIO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Municipio: ', isset($registro_dom[2]) ? $registro_dom[2]['Municipio'] : 'No disponible');
        
    //------------ESTADO-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Estado: ', isset($registro_dom[2]) ? $registro_dom[2]['Estado'] : 'No disponible');
        
    //------------CIUDAD-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, 'Ciudad: ', isset($registro_dom[2]) ? $registro_dom[2]['Ciudad'] : 'No disponible');
        
    //------------ANTIGUEDAD-------//
    makeSpace($pdf, 2);
    makeOneLineField($pdf, utf8_decode('Antigüedad (en años): '), isset($registro_dom[2]) ? $registro_dom[2]['Antiguedad'] : 'No disponible');

////////////// PROSPECCION DEL CLIENTE //////////////
$pdf->AddPage();
makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, utf8_decode('PROSPECCION  DEL CLIENTE'));

//------------CUENTA CON LOCAL-------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿El negocio cuenta con local propio? '), isset($registro_gpc[0]) ? $registro_gpc[0]['LocalPropio'] : 'No disponible');

//------------ACEPTA FACTURA ELECTRONICA-------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿Acepta factura electrónica? '), isset($registro_gpc[0]) ? $registro_gpc[0]['AceptaFE'] : 'No disponible');

//------------ES FILIAL-------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿Es filial de alguna empresa con línea de crédito? '), isset($registro_gpc[0]) ? $registro_gpc[0]['EsFilial'] : 'No disponible');

//------------ES PROPIETARIO REAL-------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿Es propietario real? '), isset($registro_gpc[0]) ? $registro_gpc[0]['Propietario'] : 'No disponible');

makeLineDivision($pdf);

//------------NOMBRE DE LA EMPRESA FILIAL-------//
makeSpace($pdf, 7);
makeOneLineField($pdf, 'Nombre de la empresa filial: ', isset($registro_gpc[0]) ? $registro_gpc[0]['DescripFilial'] : 'No disponible');

//------------TIEMPO DE ESTABLECIMIENTO-------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Tiempo de establecimiento (en años): '), isset($registro_gpc[0]) ? $registro_gpc[0]['TiempoNegocio'] : 'No disponible');

//------------TELEFONO NEGOCIO------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Teléfono negocio: '), isset($registro_gpc[0]) ? $registro_gpc[0]['TelefonoNeg'] : 'No disponible');

makeLineDivision($pdf);

//------------NOMBRE DEL CONTACTO DE COMPRAS-------//
makeSpace($pdf, 7);
makeOneLineField($pdf, utf8_decode('Nombre del contacto de compras: '), isset($registro_gpc[0]) ? $registro_gpc[0]['ContactoCompras'] : 'No disponible');

//------------TELEFONO Y EXTENSION-------//
makeSpace($pdf, 2);
$fechaNacimiento = $registro_gpc[0] ? $registro_gpc[0]['TelefonoCompras'] : 'No disponible';
$lugarNacimiento = $registro_gpc[0] ? $registro_gpc[0]['ExtCompras'] : 'No disponible';
makeTwoFields($pdf, utf8_decode('Teléfono'), utf8_decode('Extensión'), $fechaNacimiento, $lugarNacimiento);

//------------CORREO------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Correo: '), isset($registro_gpc[0]) ? $registro_gpc[0]['CorreoCompras'] : 'No disponible');

$pdf->AddPage();
makeLineDivision($pdf);

//------------NOMBRE DEL CONTACTO DE PAGOS------//
makeSpace($pdf, 7);
makeOneLineField($pdf, utf8_decode('Nombre del contacto de pagos: '), isset($registro_gpc[0]) ? $registro_gpc[0]['ContactoPagos'] : 'No disponible');

//------------TELEFONO Y EXTENSION-------//
makeSpace($pdf, 2);
$fechaNacimiento = $registro_gpc[0] ? $registro_gpc[0]['TelefonoPagos'] : 'No disponible';
$lugarNacimiento = $registro_gpc[0] ? $registro_gpc[0]['ExtPagos'] : 'No disponible';
makeTwoFields($pdf, utf8_decode('Teléfono'), utf8_decode('Extensión'), $fechaNacimiento, $lugarNacimiento);

//------------CORREO------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Correo: '), isset($registro_gpc[0]) ? $registro_gpc[0]['CorreoPagos'] : 'No disponible');

makeLineDivision($pdf);

//------------FIRMA DE AUTORIZACION DE CONSULTA DE BURO DE CREDITO------//
makeSpace($pdf, 7);
makeOneLineField($pdf, utf8_decode('Firma de Autorización de Consulta de Buro de Crédito: '), isset($registro_gpc[0]) ? $registro_gpc[0]['AceptaFE'] : 'No disponible');

makeLineDivision($pdf);

//------------¿SE VISITO AL CLIENTE?------//
makeSpace($pdf, 7);
makeOneLineField($pdf, utf8_decode('¿Se visitó al cliente?'), isset($registro_gpc[0]) ? $registro_gpc[0]['VisitoCliente'] : 'No disponible');

//------------NIVEL------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Nivel: '), isset($registro_gpc[0]) ? $registro_gpc[0]['Nivel'] : 'No disponible');

//------------¿QUIEN LO VISITO?------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿Quién lo visitó?'), isset($registro_gpc[0]) ? $registro_gpc[0]['QuienVisito'] : 'No disponible');

//------------ZONA------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Zona: '), isset($registro_gpc[0]) ? $registro_gpc[0]['Zona'] : 'No disponible');

//------------LISTA DE PRECIOS------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Lista de precios: '), isset($registro_gpc[0]) ? $registro_gpc[0]['ListaPrecios'] : 'No disponible');

//------------SECTOR------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Sector: '), isset($registro_gpc[0]) ? $registro_gpc[0]['Sector'] : 'No disponible');

//------------¿QUE PRODUCTOS CONSUME?------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿Qué productos consume?'), isset($registro_gpc[0]) ? $registro_gpc[0]['ProductosConsume'] : 'No disponible');

$pdf->AddPage();

//------------¿QUE PRODUCTOS LES VAMOS A VENDER?------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿Qué productos le vamos a vender?'), isset($registro_gpc[0]) ? $registro_gpc[0]['ProductoAVender'] : 'No disponible');

//------------PROYECCION DE VENTA EN TONELADAS------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Proyección de venta (en toneladas)'), isset($registro_gpc[0]) ? $registro_gpc[0]['ProyeccionVenta'] : 'No disponible');

//------------A QUE OTROS PROVEEDORES LES COMPRA ACERO?------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('¿A qué otros proveedores les compra acero?'), isset($registro_gpc[0]) ? $registro_gpc[0]['OtrosProveedores'] : 'No disponible');

//------------CONSUMO APROXIMADO EN PESOS------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Consumo aproximado (en pesos $)'), isset($registro_gpc[0]) ? $registro_gpc[0]['ConsumoAprox'] : 'No disponible');

//------------PROYECTO ESPECIAL O FRECUENTE------//
makeSpace($pdf, 2);
makeOneLineField($pdf, utf8_decode('Proyecto especial o frecuente'), isset($registro_gpc[0]) ? $registro_gpc[0]['ProyEspecialOFrec'] : 'No disponible');



////////////// REFERENCIAS COMERCIALES //////////////
$pdf->AddPage();
makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, 'REFERENCIAS COMERCIALES');

//------------REFERENCIA 1-------//
makeSpace($pdf, 5);
$pdf->SetTextColor(241, 90, 38);
$pdf->SetFont('Arial', '', 15);
$pdf->Cell(0, 10, 'Referencia comercial 1', 0, 1);

//------------NOMBRE DEL PROVEEDOR-------//
makeOneLineField($pdf, 'Nombre del proveedor: ', isset($registro_refs[2]) ? $registro_refs[2]['Proveedor'] : 'No disponible');

//------------TELEFONO-------//
makeOneLineField($pdf, 'Telefono: ', isset($registro_refs[2]) ? $registro_refs[2]['Telefono'] : 'No disponible');

//------------PLAZO-------//
makeOneLineField($pdf, 'Plazo: ', isset($registro_refs[2]) ? $registro_refs[2]['Plazo'] : 'No disponible');

//------------LIMITE-------//
makeOneLineField($pdf, 'Limite: ', isset($registro_refs[2]) ? $registro_refs[2]['Limite'] : 'No disponible');

//------------REFERENCIA 2-------//
makeSpace($pdf, 5);
$pdf->SetTextColor(241, 90, 38);
$pdf->SetFont('Arial', '', 15);
$pdf->Cell(0, 10, 'Referencia comercial 2', 0, 1);

//------------NOMBRE DEL PROVEEDOR-------//
makeOneLineField($pdf, 'Nombre del proveedor: ', isset($registro_refs[1]) ? $registro_refs[1]['Proveedor'] : 'No disponible');

//------------TELEFONO-------//
makeOneLineField($pdf, 'Telefono: ', isset($registro_refs[1]) ? $registro_refs[1]['Telefono'] : 'No disponible');

//------------PLAZO-------//
makeOneLineField($pdf, 'Plazo: ', isset($registro_refs[1]) ? $registro_refs[1]['Plazo'] : 'No disponible');

//------------LIMITE-------//
makeOneLineField($pdf, 'Limite: ', isset($registro_refs[1]) ? $registro_refs[2]['Limite'] : 'No disponible');

$pdf->AddPage();

//------------REFERENCIA 3-------//
makeSpace($pdf, 5);
$pdf->SetTextColor(241, 90, 38);
$pdf->SetFont('Arial', '', 15);
$pdf->Cell(0, 10, 'Referencia comercial 3', 0, 1);

//------------NOMBRE DEL PROVEEDOR-------//
makeOneLineField($pdf, 'Nombre del proveedor: ', isset($registro_refs[0]) ? $registro_refs[0]['Proveedor'] : 'No disponible');

//------------TELEFONO-------//
makeOneLineField($pdf, 'Telefono: ', isset($registro_refs[0]) ? $registro_refs[0]['Telefono'] : 'No disponible');

//------------PLAZO-------//
makeOneLineField($pdf, 'Plazo: ', isset($registro_refs[0]) ? $registro_refs[0]['Plazo'] : 'No disponible');

//------------LIMITE-------//
makeOneLineField($pdf, 'Limite: ', isset($registro_refs[0]) ? $registro_refs[0]['Limite'] : 'No disponible');



////////////// OBSERVACIONES //////////////
$pdf->AddPage();
makeTitleRectangle($pdf, $margen_i, 25.5, $total_w_wo_m, 13, 'OBSERVACIONES');

makeSpace($pdf, 5);
$pdf->SetTextColor(241, 90, 38);
$pdf->SetFont('Arial', '', 15);
$pdf->Cell(0, 10, utf8_decode('CALIFICACIÓN CREDITICIA OBTENIDA'), 0, 1, 'C');

makeSpace($pdf, 5);

// TABLA //
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);

$pdf->Cell(114, 10, utf8_decode('Parámetro'), 0, 0, 'L');
$pdf->Cell(30, 10, utf8_decode('Valor'), 0, 0, 'L');
$pdf->Cell(30, 10, utf8_decode('Calificación'), 0, 0, 'L');
$pdf->Cell(30, 10, utf8_decode('Ponderado'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 12);

foreach ($data as $row) {
    $pdf->Cell(114, 10, utf8_decode($row['Parametro']), 'B', 0, 'L');
    $pdf->Cell(30, 10, utf8_decode($row['Valor']), 'B', 0, 'L');
    $pdf->Cell(30, 10, utf8_decode($row['Calificacion']), 'B', 0, 'L');
    $pdf->Cell(30, 10, utf8_decode($row['Ponderado']), 'B', 1, 'L');
}

$pdf->Cell(174, 10, utf8_decode('CALIFICACIÓN TOTAL:'), 'B', 0, 'L');
$pdf->Cell(30, 10, number_format($totalCalificacion, 2), 'B', 1, 'L');





//------------OUTPUT-------//
$pdf->Output();
?>