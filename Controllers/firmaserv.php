<?php
require("conexion.php");

$action = isset($_GET["action"]) ? $_GET['action'] : '';
$result = array('error' => false);

if ($action == 'firmar') {
    $datos = json_decode(file_get_contents("php://input"), true);
    if ($datos['solicitud']) {
        $solicitud = $datos['solicitud'];
        $blob_image = $datos['url'];
        $orientation = $datos['orientation'];

        #Buscar los datos que faltan para el insert
        $consultar = $con->query("SELECT ID_Cliente FROM tb_web_va_datasignauth WHERE ID_Solicitud = '$solicitud'");
        // $result['sql_1'] = "SELECT ID_Cliente FROM tb_web_va_datasignauth WHERE ID_Solicitud = '$solicitud'";
        if ($consultar and $consultar->num_rows) {
            $ID_Cliente = $consultar->fetch_assoc()['ID_Cliente'];
            $nombre_archivo = "Autorizacion_Buro_$solicitud";
            $tamanio_archivo = strlen(base64_decode($blob_image));
            // $sql = "INSERT INTO tb_web_va_docs(ID_Solicitud, ID_Cliente, ID_TipoDoc, Archivo, Nombre_Archivo, Tamanio_Archivo) VALUES('$solicitud', '$ID_Cliente', 14, '$blob_image', '$nombre_archivo', '" .formatBytes(explode(',', $blob_image)[1]) ."')";
            $sql = "INSERT INTO tb_web_va_docs(ID_Solicitud, ID_Cliente, ID_TipoDoc, Archivo, Nombre_Archivo, OrientacionFirma, Tamanio_Archivo, Estatus) VALUES('$solicitud', '$ID_Cliente', 14, '$blob_image', '$nombre_archivo', '$orientation', '$tamanio_archivo', 1)";
            // $result['sql'] = $sql;
            $consulta = $con->query($sql);
            
            if (!$consulta) {
                $result['error'] = true;
            }
        } else {
            $result['error'] = true;
            $result['select'] = true;
        }
    } else {
        $result['error'] = true;
        $result['solicitud'] = true;
    }
}

if ($action == 'validar') {
    $result['firmada'] = false;
    $datos = json_decode(file_get_contents("php://input"), true);
    
    if ($datos['solicitud']) {
        $solicitud = $datos['solicitud'];
        
        #Primero, verificar que la firma no este en la tabla.
        $validar_firma = $con->query("SELECT ID_Solicitud FROM tb_web_va_docs WHERE ID_Solicitud = '$solicitud' AND ID_TipoDoc = '14' AND Estatus = 1");
        if ($validar_firma->num_rows) {
            $result['firmada'] = true;
        }
    } else {
        $result['error'] = true;
    }
}

/* function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    $bytes = max($bytes, 0);
    echo $bytes;
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Calculate bytes divided by the appropriate power of 1024 and round it to $precision decimal places
    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
} */
        
echo json_encode($result); //retorno el array con los datos dependiendo de la bandera
$conn = null; //cierro conexion
die();
?>