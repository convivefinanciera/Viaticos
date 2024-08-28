
<?php
include_once 'conexion.php';

$vector = array(
    /* array('linea' => 100008639, 'cuenta' => 100302818, 'monto' => 200),
    array('linea' => 100008604, 'cuenta' => 100305480, 'monto' => 100),
    array('linea' => 100008609, 'cuenta' => 100305481, 'monto' => 200),
    array('linea' => 100008603, 'cuenta' => 100305567, 'monto' => 1000),
    array('linea' => 100008610, 'cuenta' => 100307496, 'monto' => 100),
    array('linea' => 100008611, 'cuenta' => 100307497, 'monto' => 200), */
    array('linea' => 100008612, 'cuenta' => 100307498, 'monto' => 300),
    array('linea' => 100008613, 'cuenta' => 100306488, 'monto' => 300),
    /* array('linea' => 100008614, 'cuenta' => 100306541, 'monto' => 1000),
    array('linea' => 100008616, 'cuenta' => 100306696, 'monto' => 50),
    array('linea' => 100008627, 'cuenta' => 100307347, 'monto' => 150),
    array('linea' => 100008628, 'cuenta' => 100307348, 'monto' => 100),
    array('linea' => 100008629, 'cuenta' => 100307349, 'monto' => 75),
    array('linea' => 100008630, 'cuenta' => 100307350, 'monto' => 15),
    array('linea' => 100008631, 'cuenta' => 100307351, 'monto' => 30) */
);

for ($i = 0; $i < count($vector); $i++) {
    // print_r($vector[$i]['linea']);
    $polizas = HaveToDisbursementCredit ('2024-08-01', 'M', $vector[$i]['linea'], $vector[$i]['cuenta'], $vector[$i]['monto'], null, null);
    echo $polizas;
}

/* foreach ($vector as $key => $value) {
    echo $vector[$key] . "<br>";
    // var_dump($key, $value, $vector[$key]);
} */


// HaveToDisbursementCredit('', (fisica o moral), lineacredito, cuentaahoid, montoautorizado, '', '')
function HaveToDisbursementCredit ($Fecha, $TipoCliente, $LineaCredito, $CuentaCredito, $MontoAutorizado, $ComisionApertura, $IVA = 0.16) { #IAE
    global $con;
    $resultados = array('error' => []);

    if (empty($ComisionApertura)) $ComisionApertura = 0.0;
    if (empty($Fecha)) $Fecha = date("Y-m-d");
    $FechaActual = date("Y-m-d H:i:s");
    
    $SQL04 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
    $query = $con->query($SQL04); #$query = HaveToExeSQL($SQL04); 
    if (!$query) {
        $resultados['error']['query1'] = true;
    }
    
    $SQL05 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
    $NumTRx = $con->query($SQL05); #$NumTRx = mysqli_fetch_array(HaveToExeSQL($SQL05),MYSQLI_NUM);
    $NumTRxRows = $NumTRx->fetch_assoc();
    if (!$NumTRx) {
        $resultados['error']['NumTRx'] = true;
    }
  
    $SQL06 = "SELECT (MAX(PolizaID) + 1) AS LASTID FROM PolizaContable";
    $NumPol = $con->query($SQL06); #$NumPol = mysqli_fetch_array(HaveToExeSQL($SQL06),MYSQLI_NUM);
    $NumPolRows = $NumPol->fetch_assoc();
    if (!$NumPol) {
        $resultados['error']['NumPol'] = true;
    }
    
    //Se obtienen los valores de DISPERSIÓN => Monto, Comisón e IVA Comisión
    $VectorMontos[0] = round(abs($MontoAutorizado), 2);
    $VectorMontos[1] = round($VectorMontos[0] * $ComisionApertura, 2);
    $VectorMontos[2] = round($VectorMontos[1] * $IVA, 2);
    /*************************************************************/
    
    /****************** Creación Poliza Contable *****************/
    $concept = '';
    IF($TipoCliente == 'F')            
        $concept .= ", Concepto = 'DESEMBOLSO LINEA DE CREDITO VENTACERO PERSONA FISICA'";
    ELSE        
        $concept .= ", Concepto = 'DESEMBOLSO LINEA DE CREDITO VENTACERO PERSONA MORAL'";

    /* $resultados['query_polizacontable'] = "INSERT INTO polizacontable 
            SET PolizaID = ".$NumPolRows['LASTID'].", 
              Fecha = '".$Fecha."', 
              FechaActual = '".$FechaActual."', 
              NumTransaccion = '".$NumTRxRows['NumeroTransaccion']."',
              ConceptoID = '5000', 
              ProgramaID = 'www.creditoventacero.com' $concept;"; */
    $SQL07 ="INSERT INTO polizacontable 
            SET PolizaID = ".$NumPolRows['LASTID'].", 
              Fecha = '".$Fecha."', 
              FechaActual = '".$FechaActual."', 
              NumTransaccion = '".$NumTRxRows['NumeroTransaccion']."',
              ConceptoID = '5000', 
              ProgramaID = 'www.creditoventacero.com' $concept;";
    
    $q_polizacontable = $con->query($SQL07); #HaveToExeSQL($SQL07);
    if (!$q_polizacontable) {
        $resultados['error']['polizacontable'] = true;
    // } else {
    //     $resultados['numpoliza'] = $NumPolRows['LASTID'];
    }
    /*************************************************************/

    /****************** Creación Detalle Poliza ******************/
    if ($TipoCliente == 'F') {
        /****************** Vector Poliza Desembolso Linea de Credito VentAcero Persona Física ******************/
        $VectorPoliza[1][1] = 130102030801; $VectorPoliza[1][2] = $LineaCredito;  $VectorPoliza[1][3] = $VectorMontos[0]; $VectorPoliza[1][4] = 0;                $VectorPoliza[1][5] = 'DESEMBOLSO LINEA DE CREDITO VENTACERO';  $VectorPoliza[1][6] = '26';
        $VectorPoliza[2][1] = 240701062800; $VectorPoliza[2][2] = $CuentaCredito; $VectorPoliza[2][3] = 0;                $VectorPoliza[2][4] = $VectorMontos[0]; $VectorPoliza[2][5] =  'DESEMBOLSO LINEA DE CREDITO VENTACERO'; $VectorPoliza[2][6] = '2';
        $VectorPoliza[3][1] = 240701062800; $VectorPoliza[3][2] = $CuentaCredito; $VectorPoliza[3][3] = $VectorMontos[1]; $VectorPoliza[3][4] = 0;                $VectorPoliza[3][5] = 'COMISION POR APERTURA';                  $VectorPoliza[3][6] = '26';
        $VectorPoliza[4][1] = 510602030800; $VectorPoliza[4][2] = $LineaCredito;  $VectorPoliza[4][3] = 0;                $VectorPoliza[4][4] = $VectorMontos[1]; $VectorPoliza[4][5] = 'COMISION POR APERTURA';                  $VectorPoliza[4][6] = '2';
        $VectorPoliza[5][1] = 240701062800; $VectorPoliza[5][2] = $CuentaCredito; $VectorPoliza[5][3] = $VectorMontos[2]; $VectorPoliza[5][4] = 0;                $VectorPoliza[5][5] = 'IVA COMISION POR APERTURA';              $VectorPoliza[5][6] = '26';
        $VectorPoliza[6][1] = 240708010300; $VectorPoliza[6][2] = $LineaCredito;  $VectorPoliza[6][3] = 0;                $VectorPoliza[6][4] = $VectorMontos[2]; $VectorPoliza[6][5] = 'IVA COMISION POR APERTURA';              $VectorPoliza[6][6] = '2';
    }
    else {
        /****************** Vector Poliza Desembolso Linea de Credito VentAcero Persona Moral ******************/
        $VectorPoliza[1][1] = 130102030901; $VectorPoliza[1][2] = $LineaCredito;  $VectorPoliza[1][3] = $VectorMontos[0]; $VectorPoliza[1][4] = 0;                $VectorPoliza[1][5] = 'DESEMBOLSO LINEA DE CREDITO VENTACERO';  $VectorPoliza[1][6] = '26';
        $VectorPoliza[2][1] = 240701062700; $VectorPoliza[2][2] = $CuentaCredito; $VectorPoliza[2][3] = 0;                $VectorPoliza[2][4] = $VectorMontos[0]; $VectorPoliza[2][5] =  'DESEMBOLSO LINEA DE CREDITO VENTACERO'; $VectorPoliza[2][6] = '2';
        $VectorPoliza[3][1] = 240701062700; $VectorPoliza[3][2] = $CuentaCredito; $VectorPoliza[3][3] = $VectorMontos[1]; $VectorPoliza[3][4] = 0;                $VectorPoliza[3][5] = 'COMISION POR APERTURA';                  $VectorPoliza[3][6] = '26';
        $VectorPoliza[4][1] = 510602030900; $VectorPoliza[4][2] = $LineaCredito;  $VectorPoliza[4][3] = 0;                $VectorPoliza[4][4] = $VectorMontos[1]; $VectorPoliza[4][5] = 'COMISION POR APERTURA';                  $VectorPoliza[4][6] = '2';
        $VectorPoliza[5][1] = 240701062700; $VectorPoliza[5][2] = $CuentaCredito; $VectorPoliza[5][3] = $VectorMontos[2]; $VectorPoliza[5][4] = 0;                $VectorPoliza[5][5] = 'IVA COMISION POR APERTURA';              $VectorPoliza[5][6] = '26';
        $VectorPoliza[6][1] = 240708010300; $VectorPoliza[6][2] = $LineaCredito;  $VectorPoliza[6][3] = 0;                $VectorPoliza[6][4] = $VectorMontos[2]; $VectorPoliza[6][5] = 'IVA COMISION POR APERTURA';              $VectorPoliza[6][6] = '2';
    }

    // $resultados['vector_polizas'] = $VectorPoliza;
            
    for ($i=1; $i<=6; $i++) {
        $SQL08 = "INSERT INTO detallepoliza 
                SET PolizaID = '".$NumPolRows['LASTID']."',
                Fecha = '".$Fecha."',
                CuentaCompleta = '".$VectorPoliza[$i][1]."',
                Instrumento = '".$VectorPoliza[$i][2]."',
                Cargos = '".$VectorPoliza[$i][3]."',
                Abonos = '".$VectorPoliza[$i][4]."',
                Descripcion = '".$VectorPoliza[$i][5]."',
                Referencia = '".$VectorPoliza[$i][2]."',
                ProcedimientoCont = 'CREDITO VENTACERO',
                FechaActual = '".$FechaActual."',
                TipoInstrumentoID = '".$VectorPoliza[$i][6]."';";
        // $resultados['q'.$i."_detalle_poliza"] = $SQL08;
        $q_detallepoliza = $con->query($SQL08); #HaveToExeSQL($SQL08);
        if (!$q_detallepoliza) {
            $resultados['error']['detallepoliza'] = true;
        }
    }
    /*************************************************************/
    
    /****************** Movimientos Cuenta Crédito ***************/
    $SQL09 ="INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, NumTransaccion)
            VALUES ('".$CuentaCredito."', '".$NumTRxRows['NumeroTransaccion']."', '".$Fecha."', 'A', '".$VectorMontos[0]."', 'DESEMBOLSO LINEA DE CREDITO VENTACERO', 'ABONO A CUENTA', '10000', '".$NumPolRows['LASTID']."', '".$FechaActual."', '".$NumTRxRows['NumeroTransaccion']."');";
    // $resultados['q_mov1'] = $SQL09;
    $query09 = $con->query($SQL09); #HaveToExeSQL($SQL09);
    if (!$query09) {
        $resultados['error']['query09'] = true;
    }
  
    $SQL10 ="INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, NumTransaccion)
            VALUES ('".$CuentaCredito."', '".$NumTRxRows['NumeroTransaccion']."', '".$Fecha."', 'C', '".$VectorMontos[1]."', 'COMISION POR APERTURA', 'CARGO A CUENTA', '83', '".$NumPolRows['LASTID']."', '".$FechaActual."', '".$NumTRxRows['NumeroTransaccion']."');";
    // $resultados['q_mov2'] = $SQL10;
    $query10 = $con->query($SQL10);#HaveToExeSQL($SQL10);
    if (!$query10) {
        $resultados['error']['query10'] = true;
    }
  
    $SQL11 ="INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, NumTransaccion)
            VALUES ('".$CuentaCredito."', '".$NumTRxRows['NumeroTransaccion']."', '".$Fecha."', 'C', '".$VectorMontos[2]."', 'IVA COMISION POR APERTURA', 'CARGO A CUENTA', '84', '".$NumPolRows['LASTID']."', '".$FechaActual."', '".$NumTRxRows['NumeroTransaccion']."');";
    // $resultados['q_mov3'] = $SQL11;
    $query11 = $con->query($SQL11);#HaveToExeSQL($SQL11);
    if (!$query11) {
        $resultados['error']['query11'] = true;
    }
  
    //   HaveToAccountCreditBalance($CuentaCredito, $VectorMontos[0], $VectorMontos[1] + $VectorMontos[2]);

    return $resultados;
}