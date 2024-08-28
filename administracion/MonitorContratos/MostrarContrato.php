<?php
require_once('../../Controllers/conexion.php');
require_once('../../Controllers/CredencialesFIRMAMEX.php');
require_once('../../Controllers/firmamex_services.php');

if (isset($_GET['firmamexId']) and isset($_GET['ncontrato'])) {
    $firmamexId = $_GET['firmamexId'];
    $nombre_contrato = $_GET['ncontrato'] . '.pdf';

    $firmamexServices = new FirmamexServices($webId, $apiKey);
    $datos = json_decode($firmamexServices->getDocument('original', $firmamexId));
    $documento = $datos->original;
} else if (isset($_GET['docid'])) {
    #Se va a mostrar desde la BD el archivo.
    $consulta = $con->query("SELECT Archivo, Nombre_Archivo FROM tb_web_va_docs WHERE ID_Documento = " . $_GET['docid']);
    $documento = $consulta->fetch_assoc()['Archivo'];
    $nombre_contrato = $consulta->fetch_assoc()['Nombre_Archivo'];
} else if (isset($_GET['solicitud'])) {
    $consulta = $con->query("SELECT Archivo, Nombre_Archivo FROM tb_web_va_docs WHERE ID_Solicitud = '" . $_GET['solicitud'] . "' AND ID_TipoDoc = 18 AND Estatus = 1");

    if($consulta->num_rows) {
        $documento = $consulta->fetch_assoc()['Archivo'];
        $nombre_contrato = $consulta->fetch_assoc()['Nombre_Archivo'];
    }
    else {
        die("ArchivoInexistente");
    }
}

header("Content-Type: application/pdf");
header("Content-disposition: inline; filename='$nombre_contrato'");
header("Content-Transfer-Encoding: binary");
header("Accept-Ranges: bytes");

echo base64_decode($documento);
?>