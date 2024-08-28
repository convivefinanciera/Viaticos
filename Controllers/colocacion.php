<?php
include_once 'conexion.php';
$bandera = $_POST['bandera'];

if ($bandera == 'Mostrar_ReporteColocacion') {

    $tabla = '';
    $tabla .= '<div class="dataTables_scrollBody" style="position: relative; overflow: auto; max-height: 60vh; width: 100%;">
    <table id="example" class="table table-striped">
    <thead>
        <tr>
            <th scope="col"><center>ID Linea De Crédito</center></th>
            <th scope="col"><center>Monto Línea</center></th>
            <th scope="col"><center>Cliente ID</center></th>
            <th scope="col"><center>Nombre Cliente</center></th>
            <th scope="col"><center>Credito ID</center></th>
            <th scope="col"><center>Consumo crédito</center></th>
            <th scope="col"><center>Fecha Inicio</center></th>
            <th scope="col"><center>Fecha Vencimiento</center></th>
            <th scope="col"><center>Saldo Cap Vigente</center></th>
            <th scope="col"><center>Días transcurridos</center></th>
        </tr>
    </thead>
    <tbody>';

    /*$SQL = "SELECT A.LineaCreditoID, 
            A.Solicitado AS 'Monto Línea',
            B.ClienteID, 
            C.NombreCompleto AS 'Nombre Cliente',
            B.CreditoID, 
            B.MontoCredito, 
            B.FechaInicio, 
            B.FechaVencimien, 
            B.SaldoCapVigent, 
            DATEDIFF (B.FechaVencimien, B.FechaInicio) AS 'Dias transcurridos' -- Menor a 30 Verde, entre 30 y 44 Amarillo, mayor o igual a 45 rojo
        FROM Lineascredito A, 
            Creditos B, 
            Clientes C
        WHERE B.LineaCreditoID = A.LineaCreditoID AND 
            A.ProductoCreditoID IN (9000,9001) AND 
            A.ClienteID = C.ClienteID;";*/
    $SQL = "SELECT A.LineaCreditoID, 
            C.Solicitado AS 'MontoLinea',
            A.ClienteID, 
            B.NombreCompleto AS 'NombreCliente',
            A.CreditoID, 
            A.MontoCredito AS 'ConsumoCredito', 
            A.FechaInicio, 
            A.FechaVencimien, 
            A.SaldoCapVigent, 
            DATEDIFF (NOW(), A.FechaInicio) AS 'DiasTranscurridos' -- Menor a 30 Verde, entre 30 y 44 Amarillo, mayor o igual a 45 rojo
        FROM creditos A, 
            clientes B,
            lineascredito C
        WHERE A.ClienteID = B.ClienteID AND 
            A.LineaCreditoID = C.LineaCreditoID AND
            A.ProductoCreditoID IN (9000,9001)
        ORDER BY A.FechaInicio DESC;";

    $query = $con->query($SQL);
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $diasTranscurridos = (int)$row['DiasTranscurridos'];
            // $clasificacion = $row['Clasificacion'];

            $color = ''; // Variable para almacenar el color

            if ($diasTranscurridos >= 1 && $diasTranscurridos <= 30) {
                $color = ' #00cc44';
            } elseif ($diasTranscurridos >= 31 && $diasTranscurridos <= 60) {
                $color = ' #ffff00';
            } else {
                $color = '#ff1a1a';
            }

            // if ($clasificacion >= 1 && $clasificacion <= 30) {
            //     $colorClasificacion = ' #00cc44';
            // } elseif ($clasificacion >= 31 && $clasificacion <= 60) {
            //     $colorClasificacion = ' #ffff00';
            // } else {
            //     $colorClasificacion = '#ff1a1a';
            // }

            $monto = $row['MontoLinea'];
            $montoCredito = $row['ConsumoCredito'];
            $saldo_cap_vigente = $row['ConsumoCredito'];

            // Redondear a cero decimales
            // $monto = round($monto, 0);

            // Formatear el monto en pesos mexicanos con el signo $
            $monto_formateado = '$' . number_format($monto, 2, '.', ',');
            $monto_cre_formateado = '$' . number_format($montoCredito, 2, '.', ',');
            $monto_saldocap_formateado = '$' . number_format($saldo_cap_vigente, 2, '.', ',');

            $tabla .= '
                <tr>

                    <td style="padding-top:20px"><center>' . $row['LineaCreditoID'] . '</center></td>
                    <td style="padding-top:20px"><center>' . $monto_formateado . '</center></td>
                    <td style="padding-top:20px"><center>' . $row['ClienteID'] . '</center></td>
                    <td style="padding-top:20px"><center>' . $row['NombreCliente'] . '</center></td>
                    <td style="padding-top:20px"><center>' . $row['CreditoID'] . '</center></td>
                    <td style="padding-top:20px"><center>' . $monto_cre_formateado . '</center></td>
                    <td style="padding-top:20px"><center>' . $row['FechaInicio'] . '</center></td>
                    <td style="padding-top:20px"><center>' . $row['FechaVencimien'] . '</center></td>
                    <td style="padding-top:20px"><center>' . $monto_saldocap_formateado . '</center></td>
                    <td style="padding-top:20px; background-color: ' . $color . ';"><center>' . $row['DiasTranscurridos'] . '</center></td>
                </tr>';
        }

        // Redondear a cero decimales y formatear el monto
        // $monto_formateado = '$' . number_format($row['MontoLinea'], 2, '.', ',');
        // $montoCredito_formateado = '$' . number_format($row['ConsumoCredito'], 2, '.', ',');
        // $saldoCap_formateado = '$' . number_format($row['SaldoCapVigent'], 2, '.', ',');

        // $tabla .= '
        //     <tr>
        //         <td style="padding-top:20px"><center>' . $row['LineaCreditoID'] . '</center></td>
        //         <td style="padding-top:20px"><center>' . $monto_formateado . '</center></td>
        //         <td style="padding-top:20px"><center>' . $row['ClienteID'] . '</center></td>
        //         <td style="padding-top:20px"><center>' . $row['Nombre Cliente'] . '</center></td>
        //         <td style="padding-top:20px"><center>' . $row['CreditoID'] . '</center></td>
        //         <td style="padding-top:20px"><center>' . $montoCredito_formateado . '</center></td>
        //         <td style="padding-top:20px"><center>' . date('Y/m/d', strtotime($row['FechaInicio'])) . '</center></td>
        //         <td style="padding-top:20px"><center>' . date('Y/m/d', strtotime($row['FechaVencimien'])) . '</center></td>
        //         <td style="padding-top:10px"><center>' . $saldoCap_formateado . '</center></td>
        //         <td style="padding-top:20px; background-color: ' . $color . ';"><center>' . $diasTranscurridos . '</center></td>
        //     </tr>';
    } else {
        $tabla .= '
        <tr>
            <td style="padding-top:20px" colspan="10"><center>No registros</center></td>
        </tr>';
    }

    $tabla .= '</tbody></table></div>';
    echo $tabla;
}
