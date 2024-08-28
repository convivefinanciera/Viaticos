<?php
include_once 'conexion.php';

$bandera = $_POST['bandera'];

if ($bandera == 'MostrarMonederos') {
    //Consulta para la tabla de solicitudes de crédito
    $consulta = $con->query("SELECT X.ClienteID, X.NombreCompleto, Y.TarjetaDebid, Y.Estatus, X.CuentaAhoID, X.Saldo, X.SaldoDispon, X.SaldoBloq FROM 
                            (SELECT A.ClienteID, C.NombreCompleto, A.CuentaAhoID, A.Saldo, A.SaldoDispon, A.SaldoBloq
                            FROM cuentasaho A, clientes C
                            WHERE A.ClienteID = C.ClienteID AND
                                A.TipoCuentaID = 29 ) X LEFT JOIN tarjetadebito Y ON
                                X.CuentaAhoID = Y.CuentaAhoID;");

    $result = array();
    //$bodyTabla = '';

    // $tr = '';
    while ($row = $consulta->fetch_assoc()) {
        $row['Monto Crédito']    = "$" . number_format($row['Saldo'], 2, '.', ',');
        $row['Monto Dispuesto']  = "$" . number_format($row['SaldoDispon'], 2, '.', ',');
        $row['Monto Pagado']     = "$" . number_format($row['SaldoBloq'], 2, '.', ',');
        array_push($result, $row);
    }

    echo json_encode($result);
}

if ($bandera == 'MostrarDetalle') {
    $cliente = isset($_POST['clienteid']) ? $_POST['clienteid'] : '';

    if ($cliente) {
        $response = array('error' => false);
        $datos = [];

        $consulta = $con->query("SELECT C.NombreCompleto AS 'Nombre del Cliente VentAcero',
                                    A.ClienteID AS 'Número de cliente',
                                    A.LineaCreditoID AS 'Línea de crédito',
                                    A.CuentaID AS 'Número de Cuenta',
                                    A.CreditoID AS 'Crédito Consumo',
                                    A.MontoCredito AS 'Monto Consumo',             
                                    CASE WHEN A.Estatus = 'V' THEN 'Vigente'
                                        WHEN A.Estatus = 'P' THEN 'Pagado'
                                        WHEN A.Estatus = 'A' THEN 'Atrasado'
                                        WHEN A.Estatus = 'B' THEN 'Vencido'    
                                        ELSE 'POR VALIDAR' END AS 'Estatus',
                                    A.FechaInicio AS 'Fecha de Consumo',
                                    A.FechaVencimien AS 'Fecha de liquidación',
                                    D.FechaLiquida AS 'Fecha Pago',
                                    DATEDIFF (NOW(), D.FechaInicio) AS 'DiasTranscurridos' -- Menor a 30 Verde, entre 30 y 44 Amarillo, mayor o igual a 45 rojo
                                FROM creditos A, lineascredito B, clientes C, amorticredito D
                                WHERE A.LineacreditoID = B.LineacreditoID AND
                                    A.ClienteID = C.ClienteID AND 
                                    A.CreditoID = D.CreditoID AND
                                    A.ClienteID = $cliente AND A.ProductoCreditoID IN (9000,9001);");

        while ($fila = $consulta->fetch_assoc()) {
            $fila['Monto Consumo'] = "$" . number_format($fila['Monto Consumo'], 2, '.', ',');
            array_push($datos, $fila);
        }

        $response['datos'] = $datos;
    } else {
        $response['error'] = true;
    }

    echo json_encode($response);
}

$con->close();
