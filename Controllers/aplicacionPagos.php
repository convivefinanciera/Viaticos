<?php
require_once('conexion.php');
session_start();
date_default_timezone_set('America/Mexico_City');


// if(isset($_SESSION['celular'])) {
    
//     $celular = $_SESSION['celular'];
   
//     echo "El número de celular del usuario es: " . $celular;
// } else {
    
//     echo "No se ha iniciado sesión o no se ha establecido el número de celular.";
// }


// function HaveToExeSQL($SQL_EXE)
// {
// 	$usuario = "root";
// 	$contrasena = "zafy2017";
// 	$servidor = "192.168.1.92";
// 	$basededatos = "microfin_20240222";
// 	$conexion = mysqli_connect($servidor, $usuario, $contrasena);
// 	$db = mysqli_select_db($conexion, $basededatos) or die("Error Conexión Base de Datos");
// 	$SQL_ANS = mysqli_query($conexion, $SQL_EXE);
// 	return $SQL_ANS;
// }
// VARIABLE GLOBAL CuentaDisp
// servimex 10001100
// CuentaAhoID = 100304810; VARIABLE GLOBAL cuenta concentradora 



//VARIABLES GLOBALES
$IVA = 0.16;
$cuentaConcentradora = '100304810';
$CuentaDisp = '100304814';
$ComisionDisp = 0.0;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['tipoDispersion']) && isset($_POST['monto']) && isset($_POST['referencia_ticket']) && isset($_POST['fechaDisp'])) {

		$TipoDisp = $_POST['tipoDispersion'];
		$MontoDisp = $_POST['monto'];
		$DescripcionDisp = $_POST['referencia_ticket'];
		$Fecha = $_POST['fechaDisp'];
		//HaveToDispersion($TipoDisp, $MontoDisp, "", $DescripcionDisp, $cuentaConcentradora, "", "", $IVA);
			//Se obtienen los valores por defecto a utilizar 
		if (empty($CuentaDisp))
		$CuentaDisp = $CuentaConcentradora;
		if (empty($ComisionDisp))
		$ComisionDisp = 0.0;
		if (empty($Fecha))
		$Fecha = date("Y-m-d H:i:s");

//Se obtienen los valores de DISPERSIÓN => Monto, Comisón e IVA Comisión
$VectorMontos[0] = round(abs($MontoDisp), 2);
$VectorMontos[1] = round($VectorMontos[0] * $ComisionDisp, 2);
$VectorMontos[2] = round($VectorMontos[1] * $IVA, 2);

//Se definen los tipos de dispersión a manejar para SERVIMEX dentro del Vector Tipo Dispersion => VectorTD	 
// $VectorTD = array(
// 	0 => array(10000, "INSTRUCCION DE DISPERSION"),
// 	1 => array(10001, "DISPERSION INTERBANCARIA SERVIMEX"),
// 	2 => array(10002, "DISPERSION BANCARIA SERVIMEX"),
// 	3 => array(10003, "DISPERSION CORRESPONSALES SERVIMEX"),
// 	4 => array(10004, "DISPERSION CHEQUE SERVIMEX"),
// 	5 => array(10005, "DISPERSION EFECTIVO SERVIMEX"),
// 	6 => array(10010, "REVERSA INSTRUCCION DE DISPERSION"),
// 	7 => array(10011, "REVERSA DISPERSION INTERBANCARIA SERVIMEX"),
// 	8 => array(10012, "REVERSA DISPERSION BANCARIA SERVIMEX"),
// 	9 => array(10013, "REVERSA DISPERSION CORRESPONSALES SERVIMEX"),
// 	10 => array(10014, "REVERSA DISPERSION CHEQUE SERVIMEX"),
// 	11 => array(10015, "REVERSA DISPERSION EFECTIVO SERVIMEX")
// );

//Se obtienen los saldos iniciales de la CUENTA CONCENTRADORA a la que se va a realizar la dispersión
$SQL1 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
$result = $con->query($SQL1);
$SaldosIniCon = $result->fetch_array(MYSQLI_NUM);
//Se obtienen los saldos iniciales de la CUENTA DESTINO dondé se realiará la instrucción de dispersión
$SQL_001 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = '" . $CuentaDisp . "';";
$result = $con->query($SQL_001);
$SaldosIniDes = $result->fetch_array(MYSQLI_NUM);



echo '#' . $TipoDisp . '#';
echo $MontoDisp;
echo $DescripcionDisp;
echo $Fecha;


switch ($TipoDisp) {
	case 0:   // SÍ SE REALIZA UNA INSTRUCCIÓN DE DISPESIÓN SE EJECUTA EL CASO CERO
		// Se transfieren recursos de la CUENTA CONCENTRADORA SERVIMEX hacia una cuenta de tipo SERVIMEX
		if ($VectorMontos[0] <= $SaldosIniCon[1]) {
			// Se actualizan los saldos de la cuenta de Destino
			$SQL_002 = "UPDATE cuentasaho SET Saldo = Saldo + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaDisp . "';";
			$result = $con->query($SQL_002);
			// Se actualizan los saldos de la cuenta Concentradora
			$SQL_003 = "UPDATE cuentasaho SET Saldo = Saldo - " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon - " . $VectorMontos[0] . ", CargosMes = CargosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
			$result = $con->query($SQL_003);
			$MSG = 'La operación de instrucción de dispersión se realizó con éxito';
			return $MSG;
		} else {
			$MSG = 'NO SE PUEDE REALIZAR LA OPERACIÓN DE INSTRUCCIÓN DE DISPERSIÓN, EL SALDO EN LA CUENTA CONCENTRADORA ES INSUFICIENTE';
			return $MSG;
			exit;
		}

		//2.- SE GENERA LA POLIZA DE DISPERSION
		//2.1 Se genera el numero de transaccion a utilizar en las dierentes tablas
		$SQL4 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
		$query =  $con->query($SQL4);
		//2.2 Se obtiene el numero de transaccion generado en el paso anterior
		$SQL5 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
		$NumTRx = $con->query($SQL5);
		//2.3 Se obtiene la última póliza generada
		$SQL6 = "SELECT MAX(PolizaID) + 1 FROM PolizaContable";
		$NumPol = $con->query($SQL6);
		//2.4 Crea la póliza contable
		$SQL7 = "INSERT INTO polizacontable ( PolizaID, Fecha, ConceptoID, Concepto, FechaActual, DireccionIP, ProgramaID, NumTransaccion) 
		  VALUES (" . $NumPol[0] . ", '" . $Fecha . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL7);

		//3.- SE GENERAN LOS ASIENTOS CONTABLES DE LA POLIZA CREADA
		$SQL8 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '" . $VectorMontos[0] . "', '0', '" . $DescripcionDisp . "', '" . $CuentaConcentradora . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL8);

		$SQL9 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaDisp . "', '0', '" . $VectorMontos[0] . "', '" . $DescripcionDisp . "', '" . $CuentaDisp . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL9);


		//4.- SE GENERAN LOS MOVIMIENTOS DE LA CUENTA
		$SQL14 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL14);

		$SQL15 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaDisp . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL15);


		break;
	case 1:
	case 2:
	case 3:
	case 4:
	case 5:
		//1.- SE ACTUALIZAN LOS SALDOS (Saldo, SaldoDisponible y SaldoBloqueado) de la cuenta destino de Dispersion
		echo "caso 1";
		if ($TipoDisp == 3) {
			if ($VectorMontos[0] <= $SaldosIniCon[2]) {
				if ($SaldosIniCon[0] == $SaldosIniCon[1] + $SaldosIniCon[2]) {
					$SQL2 = "UPDATE cuentasaho SET SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", SaldoBloq = SaldoBloq - '" . $VectorMontos[0] . "', AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
					$query = $con->query($SQL2);
				} else {
					$SQL3 = "UPDATE cuentasaho SET Saldo = SaldoDispon + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", SaldoBloq = SaldoBloq - '" . $VectorMontos[0] . "', AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
					$query = $con->query($SQL3);
				}
				$MSG = 'Instruccion de dispersión realizada correctamente';
			} else {
				$MSG = 'NO SE PUEDE REALIZAR LA OPERACIÓN DE DISPERSION POR CORRESPONSALES, EL SALDO BLOQUEADO EN LA CUENTA ES INSUFICIENTE';
				return $MSG;
				exit;
			}
		} else {
			$SQL1 = "UPDATE cuentasaho SET Saldo = Saldo + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
			$query = $con->query($SQL1);
		}

		//2.- SE GENERA LA POLIZA DE DISPERSION
		//2.1 Se genera el numero de transaccion a utilizar en las dierentes tablas

		echo "entra al caso 2.1";
		$SQL4 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
		$query = $con->query($SQL4);
		//2.2 Se obtiene el numero de transaccion generado en el paso anterior
		$SQL5 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
		$result = $con->query($SQL5);
		$NumTRx = $result->fetch_array(MYSQLI_NUM);
		//2.3 Se obtiene la última póliza generada
		$SQL6 = "SELECT MAX(PolizaID) + 1 FROM PolizaContable";
		$result = $con->query($SQL6);
		$NumPol = $result->fetch_array(MYSQLI_NUM);

		echo $NumPol[0];
		//2.4 Crea la póliza contable
		$SQL7 = "INSERT INTO polizacontable ( PolizaID, Fecha, ConceptoID, Concepto, FechaActual, DireccionIP, ProgramaID, NumTransaccion) 
		  VALUES (" . $NumPol[0] . ", '" . $Fecha . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL7);

		//3.- SE GENERAN LOS ASIENTOS CONTABLES DE LA POLIZA CREADA
		$SQL8 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '110201040000', '110201040000', '" . $VectorMontos[0] . "', '0', '" . $DescripcionDisp . "', '110201040000', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL8);

		$SQL9 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '0', '" . $VectorMontos[0] . "', '" . $DescripcionDisp . "', '" . $CuentaConcentradora . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL9);

		$SQL10 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				   VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '" . $VectorMontos[1] . "', '0', 'COMISION POR DISPERSION SERVIMEX', '" . $CuentaConcentradora . "', 'COMISION POR DISPERSION', '1101', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL10);

		$SQL11 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				   VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '510602071400', '510602071400', '0', '" . $VectorMontos[1] . "', 'COMISION POR DISPERSION SERVIMEX', '240701061700', 'COMISION POR DISPERSION', '1101', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL11);

		$SQL12 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				   VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '" . $VectorMontos[2] . "', '0', 'IVA COMISION DISPERSION SERVIMEX', '" . $CuentaConcentradora . "', 'IVA COMISION DISPERSION', '1102', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL12);

		$SQL13 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				   VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240708010500', '240708010500', '0', '" . $VectorMontos[2] . "', 'IVA COMISION DISPERSION SERVIMEX', '240701061700', 'IVA COMISION DISPERSION', '1102', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL13);


		//4.- SE GENERAN LOS MOVIMIENTOS DE LA CUENTA
		echo "Movimientos cuenta";

		if ($TipoDisp == 3) {
			$SQL14 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . " BLOQUEO', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
			$query = $con->query($SQL14);

			$SQL15 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . " DESBLOQUEO', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
			$query = $con->query($SQL15);
			$MSG = 'Mensaje dispersion realizada con exito';
			return $MSG;
		} else {
			$SQL16 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
			$query = $con->query($SQL16);
			$MSG = 'Mensaje dispersion no realizada';
			return $MSG;
		}

		$SQL17 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
				   VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[1] . "', 'COMISION POR " . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL17);

		$SQL18 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
				   VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[2] . "', 'IVA COMISION POR " . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL18);
		//*************ENVIAR CODIGO */

		break;
	case 6: // SÍ SE REALIZA UNA REVERSA DE INSTRUCCIÓN DE DISPESIÓN SE EJECUTA EL CASO SEIS
		// Se transfieren recursos de la CUENTA DESTINO hacia la CUENTA CONCENTRADORA SERVIMEX
		if ($VectorMontos[0] <= $SaldosIniDes[1]) {
			// Se actualizan los saldos de la cuenta de Destino
			$SQL_002 = "UPDATE cuentasaho SET Saldo = Saldo - " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon - " . $VectorMontos[0] . ", CargosMes = CargosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaDisp . "';";
			$query = $con->query($SQL_002);
			// Se actualizan los saldos de la cuenta Concentradora
			$SQL_003 = "UPDATE cuentasaho SET Saldo = Saldo + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
			$query = $con->query($SQL_003);
			$MSG = 'La operación de reversa de instrucción de dispersión se realizó con éxito';
			return $MSG;
		} else {
			$MSG = 'NO SE PUEDE REALIZAR LA OPERACIÓN DE REVERSA DE INSTRUCCIÓN DE DISPERSIÓN, EL SALDO EN LA CUENTA ES INSUFICIENTE';
			return $MSG;
			exit;
		}
		//2.- SE GENERA LA POLIZA DE DISPERSION
		//2.1 Se genera el numero de transaccion a utilizar en las dierentes tablas
		$SQL4 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
		$query = $con->query($SQL4);
		//2.2 Se obtiene el numero de transaccion generado en el paso anterior
		$SQL5 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
		$query = $con->query($SQL5);
		$NumTRx = $result->fetch_array(MYSQLI_NUM);
		//2.3 Se obtiene la última póliza generada
		$SQL6 = "SELECT MAX(PolizaID) + 1 FROM PolizaContable";
		$query = $con->query($SQL6);
		$NumPol = $result->fetch_array(MYSQLI_NUM);
		//2.4 Crea la póliza contable
		$SQL7 = "INSERT INTO polizacontable ( PolizaID, Fecha, ConceptoID, Concepto, FechaActual, DireccionIP, ProgramaID, NumTransaccion) 
		  VALUES (" . $NumPol[0] . ", '" . $Fecha . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL7);

		//3.- SE GENERAN LOS ASIENTOS CONTABLES DE LA POLIZA CREADA
		$SQL9 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaDisp . "', '" . $VectorMontos[0] . "', '0', '" . $DescripcionDisp . "', '" . $CuentaDisp . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL9);

		$SQL8 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
				  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '0', '" . $VectorMontos[0] . "', '" . $DescripcionDisp . "', '" . $CuentaConcentradora . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL8);

		//4.- SE GENERAN LOS MOVIMIENTOS DE LA CUENTA
		$SQL15 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaDisp . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL15);

		$SQL14 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
					 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
		$query = $con->query($SQL14);

	case 7:
	case 8:
	case 9:
	case 10:
	case 11:
	case 12:


		break;
}
//Se obtienen los saldos finales de la cuenta a la que se realizó la DISPERSIÓN
$SQL20 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = 100304810;";
$query = $con->query($SQL);
$SaldosFinCon = $result->fetch_array(MYSQLI_NUM);
//Se obtienen los saldos finales de la cuenta a la que se realizó la INSTRUCCIÓN DE DISPERSIÓN
$SQL21 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = '" . $CuentaDisp . "';";
$query = $con->query($SQL);
$SaldosFinDes = $result->fetch_array(MYSQLI_NUM);



/* $SQL21 ="INSERT INTO tb_webservimexlog 
					SET   F = '".$Fecha."',				
						 TD = '".$TipoDisp."',			
						 CC = '".$CuentaDisp."',		
						  M = ".$VectorMontos[0].",		
						  C = ".$VectorMontos[1].", 	
						  I = ".$VectorMontos[2].", 	
						  P = ".$NumPol[0].",			
						SCCi = ".$SaldosIniCon[0].", 	
						SCDi = ".$SaldosIniCon[1].",	
						SCBi = ".$SaldosIniCon[2].", 	
						SCCf = ".$SaldosFinCon[0].",	
						SCDf = ".$SaldosFinCon[1].",	
						SCBf = ".$SaldosFinCon[2].",	
						SDCi = ".$SaldosIniDes[0].", 	
						SDDi = ".$SaldosIniDes[1].",	
						SDBi = ".$SaldosIniDes[2].", 	
						SDCf = ".$SaldosFinDes[0].",	
						SDDf = ".$SaldosFinDes[1].",	
						SDBf = ".$SaldosFinDes[2].",	
						U = 555, 
				FechaAlta = '".date("Y-m-d H:i:s")."';";
	$query = HaveToExeSQL($SQL21);
	*/
//Fecha en que se realiza la Dispersión
//Tipo de Dispersión 
//Cuenta de Destino
//Total Monto a Dispersar
//Comisión de la dispersión
//IVA COmisión de dispersión
//Póliza Contable
//Saldo Inicial cuenta concentradora
//Saldo Disponible inicial cuenta concentradora
//Saldo Bloqueado inicial cuenta concentradora
//Saldo Final cuenta concentradora
//Saldo Disponible final cuenta concetradora
//Saldo Inicial cuenta destino
//Saldo Disponible inicial cuenta destino
//Saldo Bloqueado inicial cuenta destino
//Saldo Final cuenta destino
//Saldo Dispoible final cuenta destino
//Saldo Bloqueado final cuenta destino

return 0;
}
	}





// function HaveToDispersion($TipoDisp, $MontoDisp, $ComisionDisp, $DescripcionDisp, $CuentaConcentradora, $CuentaDisp, $Fecha, $IVA = 0.16)
// {

// 	//Se obtienen los valores por defecto a utilizar 
// 	if (empty($CuentaDisp))
// 		$CuentaDisp = $CuentaConcentradora;
// 	if (empty($ComisionDisp))
// 		$ComisionDisp = 0.0;
// 	if (empty($Fecha))
// 		$Fecha = date("Y-m-d H:i:s");

// 	//Se obtienen los valores de DISPERSIÓN => Monto, Comisón e IVA Comisión
// 	$VectorMontos[0] = round(abs($MontoDisp), 2);
// 	$VectorMontos[1] = round($VectorMontos[0] * $ComisionDisp, 2);
// 	$VectorMontos[2] = round($VectorMontos[1] * $IVA, 2);

// 	//Se definen los tipos de dispersión a manejar para SERVIMEX dentro del Vector Tipo Dispersion => VectorTD	 
// 	$VectorTD = array(
// 		0 => array(10000, "INSTRUCCION DE DISPERSION"),
// 		1 => array(10001, "DISPERSION INTERBANCARIA SERVIMEX"),
// 		2 => array(10002, "DISPERSION BANCARIA SERVIMEX"),
// 		3 => array(10003, "DISPERSION CORRESPONSALES SERVIMEX"),
// 		4 => array(10004, "DISPERSION CHEQUE SERVIMEX"),
// 		5 => array(10005, "DISPERSION EFECTIVO SERVIMEX"),
// 		6 => array(10010, "REVERSA INSTRUCCION DE DISPERSION"),
// 		7 => array(10011, "REVERSA DISPERSION INTERBANCARIA SERVIMEX"),
// 		8 => array(10012, "REVERSA DISPERSION BANCARIA SERVIMEX"),
// 		9 => array(10013, "REVERSA DISPERSION CORRESPONSALES SERVIMEX"),
// 		10 => array(10014, "REVERSA DISPERSION CHEQUE SERVIMEX"),
// 		11 => array(10015, "REVERSA DISPERSION EFECTIVO SERVIMEX")
// 	);

// 	//Se obtienen los saldos iniciales de la CUENTA CONCENTRADORA a la que se va a realizar la dispersión
// 	$SQL1 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
// 	$SaldosIniCon = $con->fetch_array(($SQL1), MYSQLI_NUM);
// 	//Se obtienen los saldos iniciales de la CUENTA DESTINO dondé se realiará la instrucción de dispersión
// 	$SQL_001 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = '" . $CuentaDisp . "';";
// 	$SaldosIniDes = mysqli_fetch_array(HaveToExeSQL($SQL_001), MYSQLI_NUM);



// 	echo '#' . $TipoDisp . '#';
// 	echo $MontoDisp;
// 	echo $DescripcionDisp;
// 	echo $Fecha;


// 	switch ($TipoDisp) {
// 		case 0:   // SÍ SE REALIZA UNA INSTRUCCIÓN DE DISPESIÓN SE EJECUTA EL CASO CERO
// 			// Se transfieren recursos de la CUENTA CONCENTRADORA SERVIMEX hacia una cuenta de tipo SERVIMEX
// 			if ($VectorMontos[0] <= $SaldosIniCon[1]) {
// 				// Se actualizan los saldos de la cuenta de Destino
// 				$SQL_002 = "UPDATE cuentasaho SET Saldo = Saldo + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaDisp . "';";
// 				$query = HaveToExeSQL($SQL_002);
// 				// Se actualizan los saldos de la cuenta Concentradora
// 				$SQL_003 = "UPDATE cuentasaho SET Saldo = Saldo - " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon - " . $VectorMontos[0] . ", CargosMes = CargosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
// 				$query = HaveToExeSQL($SQL_003);
// 				$MSG = 'La operación de instrucción de dispersión se realizó con éxito';
// 				return $MSG;
// 			} else {
// 				$MSG = 'NO SE PUEDE REALIZAR LA OPERACIÓN DE INSTRUCCIÓN DE DISPERSIÓN, EL SALDO EN LA CUENTA CONCENTRADORA ES INSUFICIENTE';
// 				return $MSG;
// 				exit;
// 			}

// 			//2.- SE GENERA LA POLIZA DE DISPERSION
// 			//2.1 Se genera el numero de transaccion a utilizar en las dierentes tablas
// 			$SQL4 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
// 			$query = HaveToExeSQL($SQL4);
// 			//2.2 Se obtiene el numero de transaccion generado en el paso anterior
// 			$SQL5 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
// 			$NumTRx = mysqli_fetch_array(HaveToExeSQL($SQL5), MYSQLI_NUM);
// 			//2.3 Se obtiene la última póliza generada
// 			$SQL6 = "SELECT MAX(PolizaID) + 1 FROM PolizaContable";
// 			$NumPol = mysqli_fetch_array(HaveToExeSQL($SQL6), MYSQLI_NUM);
// 			//2.4 Crea la póliza contable
// 			$SQL7 = "INSERT INTO polizacontable ( PolizaID, Fecha, ConceptoID, Concepto, FechaActual, DireccionIP, ProgramaID, NumTransaccion) 
// 			  VALUES (" . $NumPol[0] . ", '" . $Fecha . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL7);

// 			//3.- SE GENERAN LOS ASIENTOS CONTABLES DE LA POLIZA CREADA
// 			$SQL8 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                       VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '" . $VectorMontos[0] . "', '0', '" . $DescripcionDisp . "', '" . $CuentaConcentradora . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL8);

// 			$SQL9 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
// 					  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaDisp . "', '0', '" . $VectorMontos[0] . "', '" . $DescripcionDisp . "', '" . $CuentaDisp . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL9);


// 			//4.- SE GENERAN LOS MOVIMIENTOS DE LA CUENTA
// 			$SQL14 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL14);

// 			$SQL15 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaDisp . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL15);


// 			break;
// 		case 1:
// 		case 2:
// 		case 3:
// 		case 4:
// 		case 5:
// 			//1.- SE ACTUALIZAN LOS SALDOS (Saldo, SaldoDisponible y SaldoBloqueado) de la cuenta destino de Dispersion
// 			echo "caso 1";
// 			if ($TipoDisp == 3) {
// 				if ($VectorMontos[0] <= $SaldosIniCon[2]) {
// 					if ($SaldosIniCon[0] == $SaldosIniCon[1] + $SaldosIniCon[2]) {
// 						$SQL2 = "UPDATE cuentasaho SET SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", SaldoBloq = SaldoBloq - '" . $VectorMontos[0] . "', AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
// 						$query = HaveToExeSQL($SQL2);
// 					} else {
// 						$SQL3 = "UPDATE cuentasaho SET Saldo = SaldoDispon + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", SaldoBloq = SaldoBloq - '" . $VectorMontos[0] . "', AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
// 						$query = HaveToExeSQL($SQL3);
// 					}
// 					$MSG = 'Instruccion de dispersión realizada correctamente';
// 				} else {
// 					$MSG = 'NO SE PUEDE REALIZAR LA OPERACIÓN DE DISPERSION POR CORRESPONSALES, EL SALDO BLOQUEADO EN LA CUENTA ES INSUFICIENTE';
// 					return $MSG;
// 					exit;
// 				}
// 			} else {
// 				$SQL1 = "UPDATE cuentasaho SET Saldo = Saldo + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
// 				$query = HaveToExeSQL($SQL1);
// 			}

// 			//2.- SE GENERA LA POLIZA DE DISPERSION
// 			//2.1 Se genera el numero de transaccion a utilizar en las dierentes tablas

// 			echo "entra al caso 2.1";
// 			$SQL4 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
// 			$query = HaveToExeSQL($SQL4);
// 			//2.2 Se obtiene el numero de transaccion generado en el paso anterior
// 			$SQL5 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
// 			$NumTRx = mysqli_fetch_array(HaveToExeSQL($SQL5), MYSQLI_NUM);
// 			//2.3 Se obtiene la última póliza generada
// 			$SQL6 = "SELECT MAX(PolizaID) + 1 FROM PolizaContable";
// 			$NumPol = mysqli_fetch_array(HaveToExeSQL($SQL6), MYSQLI_NUM);

// 			echo $NumPol[0];
// 			//2.4 Crea la póliza contable
// 			$SQL7 = "INSERT INTO polizacontable ( PolizaID, Fecha, ConceptoID, Concepto, FechaActual, DireccionIP, ProgramaID, NumTransaccion) 
// 			  VALUES (" . $NumPol[0] . ", '" . $Fecha . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL7);

// 			//3.- SE GENERAN LOS ASIENTOS CONTABLES DE LA POLIZA CREADA
// 			$SQL8 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                       VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '110201040000', '110201040000', '" . $VectorMontos[0] . "', '0', '" . $DescripcionDisp . "', '110201040000', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL8);

// 			$SQL9 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
// 					  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '0', '" . $VectorMontos[0] . "', '" . $DescripcionDisp . "', '" . $CuentaConcentradora . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL9);

// 			$SQL10 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                        VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '" . $VectorMontos[1] . "', '0', 'COMISION POR DISPERSION SERVIMEX', '" . $CuentaConcentradora . "', 'COMISION POR DISPERSION', '1101', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL10);

// 			$SQL11 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                        VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '510602071400', '510602071400', '0', '" . $VectorMontos[1] . "', 'COMISION POR DISPERSION SERVIMEX', '240701061700', 'COMISION POR DISPERSION', '1101', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL11);

// 			$SQL12 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                        VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '" . $VectorMontos[2] . "', '0', 'IVA COMISION DISPERSION SERVIMEX', '" . $CuentaConcentradora . "', 'IVA COMISION DISPERSION', '1102', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL12);

// 			$SQL13 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                        VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240708010500', '240708010500', '0', '" . $VectorMontos[2] . "', 'IVA COMISION DISPERSION SERVIMEX', '240701061700', 'IVA COMISION DISPERSION', '1102', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL13);


// 			//4.- SE GENERAN LOS MOVIMIENTOS DE LA CUENTA
// 			echo "Movimientos cuenta";

// 			if ($TipoDisp == 3) {
// 				$SQL14 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . " BLOQUEO', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 				$query = HaveToExeSQL($SQL14);

// 				$SQL15 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . " DESBLOQUEO', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 				$query = HaveToExeSQL($SQL15);
// 				$MSG = 'Mensaje dispersion realizada con exito';
// 				return $MSG;
// 			} else {
// 				$SQL16 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 				$query = HaveToExeSQL($SQL16);
// 				$MSG = 'Mensaje dispersion no realizada';
// 				return $MSG;
// 			}

// 			$SQL17 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 					   VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[1] . "', 'COMISION POR " . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL17);

// 			$SQL18 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 					   VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[2] . "', 'IVA COMISION POR " . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL18);
// 			//*************ENVIAR CODIGO */

// 			break;
// 		case 6: // SÍ SE REALIZA UNA REVERSA DE INSTRUCCIÓN DE DISPESIÓN SE EJECUTA EL CASO SEIS
// 			// Se transfieren recursos de la CUENTA DESTINO hacia la CUENTA CONCENTRADORA SERVIMEX
// 			if ($VectorMontos[0] <= $SaldosIniDes[1]) {
// 				// Se actualizan los saldos de la cuenta de Destino
// 				$SQL_002 = "UPDATE cuentasaho SET Saldo = Saldo - " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon - " . $VectorMontos[0] . ", CargosMes = CargosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaDisp . "';";
// 				$query = HaveToExeSQL($SQL_002);
// 				// Se actualizan los saldos de la cuenta Concentradora
// 				$SQL_003 = "UPDATE cuentasaho SET Saldo = Saldo + " . $VectorMontos[0] . ", SaldoDispon = SaldoDispon + " . $VectorMontos[0] . ", AbonosMes = AbonosMes + '" . $VectorMontos[0] . "' WHERE CuentaAhoID = '" . $CuentaConcentradora . "';";
// 				$query = HaveToExeSQL($SQL_003);
// 				$MSG = 'La operación de reversa de instrucción de dispersión se realizó con éxito';
// 				return $MSG;
// 			} else {
// 				$MSG = 'NO SE PUEDE REALIZAR LA OPERACIÓN DE REVERSA DE INSTRUCCIÓN DE DISPERSIÓN, EL SALDO EN LA CUENTA ES INSUFICIENTE';
// 				return $MSG;
// 				exit;
// 			}
// 			//2.- SE GENERA LA POLIZA DE DISPERSION
// 			//2.1 Se genera el numero de transaccion a utilizar en las dierentes tablas
// 			$SQL4 = "UPDATE TRANSACCIONES SET NumeroTransaccion = NumeroTransaccion + 1";
// 			$query = HaveToExeSQL($SQL4);
// 			//2.2 Se obtiene el numero de transaccion generado en el paso anterior
// 			$SQL5 = "SELECT NumeroTransaccion FROM TRANSACCIONES";
// 			$NumTRx = mysqli_fetch_array(HaveToExeSQL($SQL5), MYSQLI_NUM);
// 			//2.3 Se obtiene la última póliza generada
// 			$SQL6 = "SELECT MAX(PolizaID) + 1 FROM PolizaContable";
// 			$NumPol = mysqli_fetch_array(HaveToExeSQL($SQL6), MYSQLI_NUM);
// 			//2.4 Crea la póliza contable
// 			$SQL7 = "INSERT INTO polizacontable ( PolizaID, Fecha, ConceptoID, Concepto, FechaActual, DireccionIP, ProgramaID, NumTransaccion) 
// 			  VALUES (" . $NumPol[0] . ", '" . $Fecha . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL7);

// 			//3.- SE GENERAN LOS ASIENTOS CONTABLES DE LA POLIZA CREADA
// 			$SQL9 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
// 					  VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaDisp . "', '" . $VectorMontos[0] . "', '0', '" . $DescripcionDisp . "', '" . $CuentaDisp . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL9);

// 			$SQL8 = "INSERT INTO detallepoliza (PolizaID,Fecha,CuentaCompleta,Instrumento,Cargos,Abonos,Descripcion,Referencia,ProcedimientoCont,TipoInstrumentoID,FechaActual,DireccionIP,ProgramaID,NumTransaccion) 
//                       VALUES ('" . $NumPol[0] . "', '" . $Fecha . "', '240701062600', '" . $CuentaConcentradora . "', '0', '" . $VectorMontos[0] . "', '" . $DescripcionDisp . "', '" . $CuentaConcentradora . "', '" . $VectorTD[$TipoDisp][1] . "', '" . $VectorTD[$TipoDisp][0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL8);

// 			//4.- SE GENERAN LOS MOVIMIENTOS DE LA CUENTA
// 			$SQL15 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaDisp . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'C', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'CARGO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL15);

// 			$SQL14 = "INSERT INTO cuentasahomov (CuentaAhoID, NumeroMov, Fecha, NatMovimiento, CantidadMov, DescripcionMov, ReferenciaMov, TipoMovAhoID, PolizaID, FechaActual, DireccionIP, ProgramaID, NumTransaccion)
// 						 VALUES ('" . $CuentaConcentradora . "', '" . $NumTRx[0] . "', '" . $Fecha . "', 'A', '" . $VectorMontos[0] . "', '" . $VectorTD[$TipoDisp][1] . "', 'ABONO A CUENTA', '" . $VectorTD[$TipoDisp][0] . "', '" . $NumPol[0] . "', '" . $Fecha . "', 'WebServimex', 'WebServimex', '" . $NumTRx[0] . "');";
// 			$query = HaveToExeSQL($SQL14);

// 		case 7:
// 		case 8:
// 		case 9:
// 		case 10:
// 		case 11:
// 		case 12:


// 			break;
// 	}
// 	//Se obtienen los saldos finales de la cuenta a la que se realizó la DISPERSIÓN
// 	$SQL20 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = 100304810;";
// 	$SaldosFinCon = mysqli_fetch_array(HaveToExeSQL($SQL20), MYSQLI_NUM);
// 	//Se obtienen los saldos finales de la cuenta a la que se realizó la INSTRUCCIÓN DE DISPERSIÓN
// 	$SQL21 = "SELECT Saldo, SaldoDispon, SaldoBloq FROM CuentasAho WHERE CuentaAhoID = '" . $CuentaDisp . "';";
// 	$SaldosFinDes = mysqli_fetch_array(HaveToExeSQL($SQL21), MYSQLI_NUM);



// 	/* $SQL21 ="INSERT INTO tb_webservimexlog 
// 		                SET   F = '".$Fecha."',				
// 							 TD = '".$TipoDisp."',			
// 							 CC = '".$CuentaDisp."',		
// 							  M = ".$VectorMontos[0].",		
// 							  C = ".$VectorMontos[1].", 	
// 							  I = ".$VectorMontos[2].", 	
// 							  P = ".$NumPol[0].",			
// 							SCCi = ".$SaldosIniCon[0].", 	
// 							SCDi = ".$SaldosIniCon[1].",	
// 							SCBi = ".$SaldosIniCon[2].", 	
// 							SCCf = ".$SaldosFinCon[0].",	
// 							SCDf = ".$SaldosFinCon[1].",	
// 							SCBf = ".$SaldosFinCon[2].",	
// 							SDCi = ".$SaldosIniDes[0].", 	
// 							SDDi = ".$SaldosIniDes[1].",	
// 							SDBi = ".$SaldosIniDes[2].", 	
// 							SDCf = ".$SaldosFinDes[0].",	
// 							SDDf = ".$SaldosFinDes[1].",	
// 							SDBf = ".$SaldosFinDes[2].",	
// 							U = 555, 
// 					FechaAlta = '".date("Y-m-d H:i:s")."';";
// 		$query = HaveToExeSQL($SQL21);
// 		*/
// 	//Fecha en que se realiza la Dispersión
// 	//Tipo de Dispersión 
// 	//Cuenta de Destino
// 	//Total Monto a Dispersar
// 	//Comisión de la dispersión
// 	//IVA COmisión de dispersión
// 	//Póliza Contable
// 	//Saldo Inicial cuenta concentradora
// 	//Saldo Disponible inicial cuenta concentradora
// 	//Saldo Bloqueado inicial cuenta concentradora
// 	//Saldo Final cuenta concentradora
// 	//Saldo Disponible final cuenta concetradora
// 	//Saldo Inicial cuenta destino
// 	//Saldo Disponible inicial cuenta destino
// 	//Saldo Bloqueado inicial cuenta destino
// 	//Saldo Final cuenta destino
// 	//Saldo Dispoible final cuenta destino
// 	//Saldo Bloqueado final cuenta destino

// 	return 0;
// }
?>