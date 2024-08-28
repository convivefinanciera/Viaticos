<?php
include_once 'conexion.php';

session_start();

$bandera = $_POST['bandera'];

if ($bandera == 'Resumen') {
    $result = $con->query("SELECT CB_Estatus, COUNT(*) AS 'Total' FROM tb_control_tdc WHERE CB_ProducCreditoID = 9000 GROUP BY 1;");

    $tarjetas = array();
    while ($row = $result->fetch_assoc()) {
        $tarjetas[] = $row;
    }

    echo json_encode($tarjetas);
}elseif ($bandera == 'Detalles') {
    $result = $con->query("SELECT B.LineaCreditoID, 
       B.ClienteID, 
       C.NombreCompleto AS 'Nombre cliente',
       A.CreditoID, 
       A.FechaPago, 
       A.MontoTotPago AS 'TOTAL PAGO', 
       A.MontoCapOrd + A.MontoCapAtr + A.MontoCapVen AS 'Capital', 
       A.MontoIntOrd + A.MontoIntOrd + A.MontoIntVen As 'Interes',
       A.MontoIntMora AS 'Moratorio',
       A.MontoIVA AS 'IVA'
      FROM detallepagcre A
      JOIN lineascredito B ON B.ClienteID = A.ClienteID
      JOIN clientes C ON C.ClienteID = A.ClienteID
      WHERE B.ProductoCreditoID IN (9000,9001);");

    $datos = array();
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    echo json_encode($datos);
}
?>
