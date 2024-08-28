<?php 
include_once 'conexion.php';

$bandera = $_POST['bandera'];

if ($bandera == 'Mostrar_ReporteConsumos'){

    $tabla = '';
    $tabla.= '<div class="dataTables_scrollBody" style="position: relative; overflow: auto; max-height: 60vh; width: 100%;">
    <table id="example" class="table table-striped">
    <thead>
        <tr>
            <th scope="col"><center>Terminación TDC</center></th>
            <th scope="col"><center>Nombre cliente</center></th>
            <th scope="col"><center>Tipo de Operación</center></th>
            <th scope="col"><center>Monto</center></th>
            <th scope="col"><center>Comercio</center></th>
            <th scope="col"><center>Autorizacion</center></th>
            <th scope="col"><center>Fecha</center></th>
            <th scope="col"><center>Terminal ID</center></th>
            <th scope="col"><center>Referencia</center></th>
        </tr>
    </thead>
    </tbody>';

    $SQL = "SELECT CONCAT('5063 ** ',MID(A.TarjetadebID,13,4)) AS 'Terminación TDC', 
            D.NombreCompleto AS 'Nombre Cliente',
            CASE  
                WHEN A.TipoOperacionID IN (00, 02) THEN 'Compra'   
                WHEN A.TipoOperacionID = 20 THEN 'Devolución' 
                WHEN A.TipoOperacionID = 50 THEN 'Pago Corresponsal' 
                ELSE 'EN Validación' END AS 'Tipo de Operación', 
            A.MontoOpe AS 'Monto', 
                REPLACE(REPLACE(REPLACE(A.NombreUbicaTer , '  ', ' '),'  ',' '),'  ',' ') AS 'Comercio',  
                A.NumTransaccion AS Autorizacion, 
                A.FechaHrOpe AS 'Fecha', 
                A.TerminalID AS TerminalID,  
                A.Referencia AS Referencia   
        FROM tardebbitacoramovs A, 
                Tarjetadebito C, 
                Clientes D
        WHERE C.TarjetaDebID = A.TarjetaDebID AND 
                D.ClienteID = C.ClienteID AND 
                A.Estatus = 'P' AND 
                A.Tarjetadebid IN (SELECT CB_TDC FROM tb_control_tdc B WHERE B.CB_ProducCreditoID = 12000) 
                /*ORDER BY A.FechaHrOpe DESC*/;";

        $query = $con->query($SQL);
        if($query->num_rows > 0)
        {
            while($row = $query->fetch_assoc())
            {
        
                $monto = $row['Monto'];
        // Redondear a cero decimales
        // $monto = round($monto, 0);
        // Formatear el monto en pesos mexicanos con el signo $
        $monto_formateado = '$' . number_format($monto, 2, '.', ',');

                $tabla.='
                <tr>

                    <td style="padding-top:20px"><center>'.$row['Terminación TDC'].'</center></td>
                    <td style="padding-top:20px"><center>'.$row['Nombre Cliente'].'</center></td>
                    <td style="padding-top:20px"><center>'.$row['Tipo de Operación'].'</center></td>
                    <td style="padding-top:20px"><center>'.$monto_formateado.'</center></td>
                    <td style="padding-top:20px"><center>'.$row['Comercio'].'</center></td>
                    <td style="padding-top:20px"><center>'.$row['Autorizacion'].'</center></td>
                    <td style="padding-top:20px"><center>'.$row['Fecha'].'</center></td>
                    <td style="padding-top:20px"><center>'.$row['TerminalID'].'</center></td>
                    <td style="padding-top:20px"><center>'.$row['Referencia'].'</center></td>
                    
                </tr>';
            }
        }
        else {
            $tabla.= '
            <tr>
                <td style="padding-top:20px"><center>No registros</center></td>
                <td style="padding-top:20px"><center></center></td>
                <td style="padding-top:20px"><center></center></td>
            </tr>';
        }

                $tabla.='</tbody>
                </table></div>';
                echo $tabla;
}

// if ($bandera == 'ConsumosFuncion') { # para ligar la linea de credito con el consumo
//     $resultados = array('error' => false);
//     /* -- OBTENER MAX de SdolicitudID y CreditoID
//     SELECT MAX(SolicitudCreditoID) + 1 FROM microfin_pruebas.SolicitudCredito; */
//     $ID_Credito = $ID_Solicit = $TRx = 0; #TRx es el folio de autorización
    
//     // Obtenemos los numeros de transacciones de Tarjetas VentAcero
//     // $sql = "SELECT A.NumTransaccion FROM tardebbitacoramovs A WHERE A.NumTransaccion = 50764 AND A.tarjetadebid IN (SELECT B.CB_TDC FROM tb_control_tdc B WHERE B.CB_ProducCreditoID = 9000) ORDER BY 1 ASC;";
//     $sql = "SELECT A.NumTransaccion FROM tardebbitacoramovs A WHERE A.NumTransaccion > (SELECT FolioID FROM foliosaplic WHERE Tabla = 'VentAceroMovs') AND A.tarjetadebid IN (SELECT B.CB_TDC FROM tb_control_tdc B WHERE B.CB_ProducCreditoID = 12000) ORDER BY 1 ASC;";
//     $result = $con->query($sql);
//     $all_done = true;

//     while ($VectorTRX = $result->fetch_row()) {
//         //por cada elemento vamos a ejecutar el siguiente query:
//         $TRx = $VectorTRX[0];
//         $fncresponse = registrarCreditosTRx($TRx, null);

//         if (!$fncresponse) {
//             $all_done = false;
//             break;
//         }
//     }
    
//     if (!$all_done) {
//         $resultados['error']['fail_insert'] = true;

//         # validar la cuenta y el estatus de la tarjeta de debito de la transaccion
//         $tarjetadebito = $con->query("SELECT Estatus, CuentaAhoID, ClienteID FROM tarjetadebito WHERE TarjetaDebID = (SELECT TarjetaDebID FROM tardebbitacoramovs WHERE NumTransaccion = $TRx);");
//         if ($tarjetadebito and $tarjetadebito->num_rows) {
//             $tarjeta_rows = $tarjetadebito->fetch_assoc();
//             /*
//                 caso: el estatus de la linea es mayor a 7 y la cuentaahoid es 0 hay que buscar la linea de credito que le corresponde al cliente y poner el dato de la cuenta en el registro.
//             */
//             if ($tarjeta_rows['Estatus'] > 7 and $tarjeta_rows['CuentaAhoID'] == 0) {
//                 #buscar la/las linea/s de credito que coincidan con el numero del cliente
//                 $q_lineacredito = $con->query("SELECT A.LineaCreditoID, B.CuentaAhoID FROM lineascredito A, cuentasaho B
//                                                 WHERE A.CuentaID = B.CuentaAhoID AND A.ClienteID =" . $tarjeta_rows['ClienteID'] . " AND B.TipoCuentaID = 26");
//                 if ($q_lineacredito) {
//                     if ($q_lineacredito->num_rows == 1) {
//                         #obtener la cuenta
//                         $fncresponse = registrarCreditosTRx($TRx, $q_lineacredito->fetch_assoc()['LineaCreditoID']);
//                         $resultados['error']['solver_insert'] = true; #si arroja esta propiedad, se llama de nuevo
//                     } else {
//                         $lineas = array();
//                         while ($fila = $q_lineacredito->fetch_assoc()) {
//                             array_push($lineas, $fila);
//                         }
//                         $resultados['lineas_dispon'] = $lineas;
//                     }
//                 }
//             }
//         }
//     }

//     $resultados['folioaplic'] = $TRx;

//     if ($TRx <> 0) {
//         $update_folio = $con->query("UPDATE foliosaplic SET FolioID = $TRx WHERE Tabla = 'VentAceroMovs';");
//         if (!$update_folio) {
//             $resultados['error']['update_folio_fail'] = true;
//         }
//     }

//     echo json_encode($resultados);
//     $con = null;
//     die();
// }

// function registrarCreditosTRx($TRx, $lineacredito) {
//     global $con;
//     $resultados = true;
    
//     $cuentaid = "B.CuentaAhoID";
//     $condicion = "B.CuentaAhoID = C.CuentaID AND";
//     if ($lineacredito) {
//         $cuentaid = "C.CuentaID";
//         $condicion = "C.LineaCreditoID = $lineacredito AND";
//     }    

//     $result_movs = $con->query("SELECT A.TarjetaDebID, B.ClienteID AS 'ID_Cliente', A.TipoOperacionID, $cuentaid AS 'ID_Cuenta', C.LineaCreditoID AS 'ID_LineaCr', C.FolioContrato AS 'ReferenciaPago', C.SaldoDisponible, C.Dispuesto, A.MontoOpe AS 'Consumo', MID(A.FechaHrOpe, 1, 10) AS 'FechaIni'
//                             FROM tardebbitacoramovs A, tarjetadebito B, lineascredito C
//                             WHERE A.TarjetaDebID = B.TarjetaDebID /*AND A.TipoOperacionID IN (00, 02) */AND
//                             $condicion
//                             A.NumTransaccion = " . $TRx . ";");  #Estoy dejando a propósito el elemento cero para hacer pruebas uno a uno
                            
//     if ($result_movs and $result_movs->num_rows) {
//         $rows = $result_movs->fetch_assoc();
//         if($rows['TipoOperacionID'] == "00" or $rows['TipoOperacionID'] == "02"){

//             /*y el valor de TRx se obtiene del valor i-ésimo del $TRx
//             para este caso $TRx =  $TRx*/
            
//             // $folio = $VectorTRX[$contador];
//             $q_lastIDSC = $con->query("SELECT (MAX(SolicitudCreditoID) + 1) AS 'LastID' FROM SolicitudCredito;");
//             if ($q_lastIDSC and $q_lastIDSC->num_rows) {
//                 $ID_Solicit = $q_lastIDSC->fetch_assoc()['LastID'];
//             }
//             /* SELECT MAX(CreditoId) + 1 FROM microfin_pruebas.Creditos; */
//             $q_lastIDC = $con->query("SELECT (MAX(CreditoId) + 1) AS 'LastID' FROM Creditos;");
//             if ($q_lastIDC and $q_lastIDC->num_rows) {
//                 $ID_Credito = $q_lastIDC->fetch_assoc()['LastID'];
//             }
    
//             $ID_LineaCr = $rows['ID_LineaCr'];
//             $ID_Cliente = $rows['ID_Cliente'];
//             $ID_Cuenta = $rows['ID_Cuenta'];
//             $FechaIni = $rows['FechaIni'];
//             $fecha30dias = new DateTime($rows['FechaIni']);
//             $fecha30dias = $fecha30dias->modify('+30 days'); # -- FechaIni + 30 días
//             $FechaFin = $fecha30dias->format('Y-m-d'); # -- FechaIni + 30 días
//             $Consumo  = $rows['Consumo'];
//             $SaldoDisponible  = $rows['SaldoDisponible'];
//             $Dispuesto  = $rows['Dispuesto'];
    
//             # --- se van a crear los insert ---- #
    
//                 # -- ALTA Solicitud
//                 $q_altaSol = $con->query("INSERT INTO solicitudcredito SET 
//                                         SolicitudCreditoID = $ID_Solicit, 
//                                         CreditoID = $ID_Credito,
//                                         ClienteID = $ID_Cliente,
//                                         FechaRegistro = '$FechaIni', 
//                                         FechaAutoriza = '$FechaIni', 
//                                         MontoAutorizado =$Consumo, 
//                                         MontoSolici = $Consumo,
//                                         NumTransaccion = $TRx,
//                                         ProductoCreditoID = 9000 , 
//                                         DestinoCreID = '20001', 
//                                         TipoCalInteres = 1, 
//                                         CalcInteresID = 1, 
//                                         Estatus = 'D', 
//                                         TasaFija = 0, 
//                                         ValorCAT = 0,
//                                         PlazoID = 1;");
//                 if (!$q_altaSol) {
//                     $resultados['error']['insert_solcredito'] = true;
//                 }
    
//                 #-- ALTA CREDITO
//                 $q_altacredito = $con->query("INSERT INTO creditos SET 
//                                             SolicitudCreditoID = $ID_Solicit, 
//                                             LineaCreditoID = $ID_LineaCr, 
//                                             CreditoID = $ID_Credito, 
//                                             ClienteID = $ID_Cliente, 
//                                             CuentaID = $ID_Cuenta, 
//                                             FechaInicioAmor = '$FechaIni', 
//                                             FechaAutoriza = '$FechaIni', 
//                                             FechaVencimien = '$FechaFin',
//                                             FechaActual = NOW(),
//                                             SaldoCapVigent = $Consumo,
//                                             FechaInicio = '$FechaIni',
//                                             MontoCredito = $Consumo,  
//                                             MontoCuota = $Consumo, 
//                                             NumTransaccion = $TRx,
//                                             ProductoCreditoID = 9000, 
//                                             DestinoCreID = '20001', 
//                                             PeriodicidadCap = '30', 
//                                             PeriodicidadInt = '30', 
//                                             TipoPagoCapital = 'C',
//                                             FrecuenciaCap ='M', 
//                                             FrecuenciaInt ='M', 
//                                             NumAmortizacion =1, 
//                                             CalcInteresID = 1, 
//                                             TipoFondeo = 'P', 
//                                             TasaBase = 0.01, 
//                                             FactorMora = 2, 
//                                             Estatus = 'V';");
//                 if (!$q_altacredito) {
//                     $resultados['error']['insert_creditos'] = true;
//                 }
    
//                 # -- ALTA AMORTICREDITO
//                 $q_altamorticredito = $con->query("INSERT INTO amorticredito SET 
//                                                     SaldoCapVigente = $Consumo,
//                                                     FechaExigible = '$FechaFin', 
//                                                     CreditoID = $ID_Credito, 
//                                                     ClienteID = $ID_Cliente, 
//                                                     FechaInicio = '$FechaIni', 
//                                                     FechaVencim = '$FechaFin', 
//                                                     CuentaID = $ID_Cuenta, 
//                                                     Capital = $Consumo,
//                                                     AmortizacionID = 1,
//                                                     Estatus = 'V',
//                                                     Interes = 0, 
//                                                     IVAInteres = 0;");
        
//                 if (!$q_altamorticredito) {
//                     $resultados['error']['insert_amorticredito'] = true;
//                 }
    
//             # --- se actualiza la línea de crédito -- #
//             $Consumo = (float)$Consumo;
//             $SaldoDisponible = (float)$SaldoDisponible;
//             $Dispuesto = (float)$Dispuesto;

//             $SaldoDisponible -= $Consumo;
//             $Dispuesto += $Consumo;

//             $u_lineacredito = $con->query("UPDATE lineascredito SET SaldoDisponible = $SaldoDisponible, Dispuesto = $Dispuesto WHERE LineaCreditoID = $ID_LineaCr");
//         } else {
        
//         }
//     } else {
//         $resultados = false;
//     }

//     return $resultados;
// }
/* NOTA: las transacciones se insertan en las tablas saltandose las que no tienen linea de credito. */



?>