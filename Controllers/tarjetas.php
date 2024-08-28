<?php
include_once 'conexion.php';

session_start();

$bandera = $_POST['bandera'];

if ($bandera == 'Resumen') {
    $result = $con->query("SELECT CB_Estatus, COUNT(*) AS 'Total' FROM tb_control_tdc WHERE CB_ProducCreditoID = 12000 GROUP BY 1;");

    $tarjetas = array();
    while ($row = $result->fetch_assoc()) {
        $tarjetas[] = $row;
    }

    echo json_encode($tarjetas);
}elseif ($bandera == 'Detalles') {
    $result = $con->query("SELECT CB_FolioMyCard AS 'Folio', 
       CONCAT(MID(CB_TDC,1,4),'-',MID(CB_TDC,5,4),'-',MID(CB_TDC,9,4),'-',MID(CB_TDC,13,4)) AS 'Numero de Tarjeta',
       CONCAT('***-',MID(CB_TDC,13,4)) AS 'Terminación Tarjeta',
       CB_Estatus AS 'Estatus' , CB_Fecha AS 'Fecha'
       FROM tb_control_tdc WHERE CB_ProducCreditoID = 12000;");

    $tarjetas = array();
    while ($row = $result->fetch_assoc()) {
        $tarjetas[] = $row;
    }

    echo json_encode($tarjetas);
}
