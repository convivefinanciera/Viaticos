<?php
include_once 'conexion.php';

$bandera = $_POST['bandera'];

if ($bandera == 'MostrarComprobantes') {

    $consultaComp = $con->query("SELECT A.*, B.descripcion, C.NombreCompleto FROM comprobantes.tb_comprobante A, comprobantes.tb_cattipodocs B, microfin.clientes C WHERE A.ID_Tipodoc = B.ID AND A.ClienteID = C.ClienteID AND A.estatus = 1;");

    $comprobantes = array();

    if($consultaComp == TRUE)
    {
        while($row = $consultaComp->fetch_assoc())
        {
            $row['MontoOperacion']    = "$" . number_format($row['MontoOpe'], 2, '.', ',');
            $comprobantes [] = $row;
        }

        echo json_encode($comprobantes);
    }
}

if($bandera == 'CargarComprobante')
{
    $id_comprobante = $_POST['id_comp'];

    $selectDoc = $con->query("SELECT archivo FROM comprobantes.tb_comprobante WHERE ID = $id_comprobante;");

    if($selectDoc == TRUE)
    {
        $archivoBlob = '';
        while($row = $selectDoc->fetch_assoc())
        {
            $archivoBlob = $row['archivo'];  // El archivo ya incluye la codificación base64
        }

        // Validar si el archivo es imagen o PDF
        if (strpos($archivoBlob, 'data:image/') === 0) {
            // Si es una imagen, simplemente lo mostramos con la etiqueta <img>
            echo '<img src="' . $archivoBlob . '" class="object-fit-xl-contain border rounded">';
        } elseif (strpos($archivoBlob, 'data:application/pdf') === 0) {
            // Si es un PDF, lo mostramos con <embed> para PDFs
            echo '<embed src="' . $archivoBlob . '" type="application/pdf" width="100%" height="600px" />';
        } else {
            // Si el formato no es soportado
            echo 'Formato de archivo no soportado.';
        }
    }
}


?>