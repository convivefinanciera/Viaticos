<?php
header("'Content-Type': application/json");
require_once("../Controllers/conexion.php");

$result = array('error' => false);

$consulta = $con->query("SELECT TipoSemaforo, Parametro, Semaforo FROM microfin_pruebas.tb_web_va_semaforos UNION
                        SELECT 'Credito' AS 'TipoSemaforo', Parametro AS 'Parametro', Valor AS 'Semaforo' FROM tb_web_va_scoreparametros;");
if ($consulta) {
    $datos = array();
    while ($fila = $consulta->fetch_assoc()) {
        array_push($datos, $fila);
    }

    $result['datos'] = $datos;
} else {
    $result['error'] = true;
}

$con->close();
echo json_encode($result);
die();

?>