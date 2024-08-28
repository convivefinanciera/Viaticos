<?php
include 'conexion.php';
// include 'FuncionesExtra.php';
include 'CredencialesFIRMAMEX.php'; //credenciales de producción y pruebas
include 'firmamex_services.php'; // clase que contiene los servicios de firmamex

session_start();

$bandera = isset($_GET['bandera']) ? $_GET['bandera'] : $_POST['bandera'];
$firmamex_services = new FirmamexServices($webId, $apiKey);
$result = array();

if ($bandera) {
    if ($bandera == 'GetSemaforosParams') {
        $consulta = $con->query("SELECT Val_Ini, Val_Fin, Semaforo FROM tb_web_va_semaforos WHERE TipoSemaforo = 'Firmas'");
        if ($consulta) {
            $datos = array();
            while ($fila = $consulta->fetch_assoc()) {
                array_push($datos, $fila);
            }
            $result['datos'] = $datos;
        } else {
            $result['error'] = true;
        }
    }
    if ($bandera == 'Mostrar_Contratos') {
        $opc = isset($_GET['opc']) ? $_GET['opc'] : '';
        if ($opc) {
            if ($opc == 'p') { //devuelve contratos pendientes
                # Esto es para obtener los documentos que tengo en FIRMAMEX
                #obtener la fecha mas baja de los registros que estan con estatus en cero
                $from = strtotime(date_format(date_create('2024-06-12'), 'Y-m-d')) * 1000; #empiezan las pruebas el 12 de junio del 2024

                $consulta_inicio = $con->query("SELECT MIN(FechaAlta) AS 'from' FROM tb_web_va_firmamex WHERE Estatus = 0;");
                $respuesta = $consulta_inicio->fetch_assoc()['from'];
                if ($respuesta) {
                    $from = strtotime(date_format(date_create($respuesta), 'Y-m-d h:m:s')) * 1000;
                }
                $to = strtotime(date('Y-m-d H:i:s')) * 1000; #dia actual

                $contratos = json_decode($firmamex_services->listDocuments($from, $to, ''));
                $results = $contratos->documents;

                #Traer los datos de la BD tabla ..._va_firmamex
                $consulta = $con->query("SELECT A.firmamexId, A.TotalFirmas, A.NombreContrato, A.ClienteID, A.ID_Solicitud, CASE WHEN B.TipoPersona = 'F' THEN 'Física' ELSE 'Moral' END AS 'TipoPersona', CONCAT(B.Nombres, ' ', B.ApellidoP, ' ', B.ApellidoM) AS 'NombreDeContacto', B.MontoAutorizado, B.MontoSolicitado, B.Celular, A.FechaAlta FROM tb_web_va_firmamex A, tb_web_va_solicitud B WHERE A.ID_Solicitud = B.ID_Solicitud AND A.Estatus = 0");
                while ($fila = $consulta->fetch_assoc()) {
                    foreach ($results as $register) { #Este es el bucle de los documentos en Firmamex
                        if ($register->firmamexId == $fila['firmamexId']) {
                            array_push($result, (object) [
                                'SolicitudID' => $fila['ID_Solicitud'],
                                'ClienteID' => $fila['ClienteID'],
                                'NombreCliente' => $fila['NombreDeContacto'],
                                'TipoPersona' => $fila['TipoPersona'],
                                'Celular' => $fila['Celular'],
                                'TotalFirmas' => $fila['TotalFirmas'],
                                'Firmas' => $register->signaturesCount,
                                'FechaCreacion' => $fila['FechaAlta'],
                                'FirmamexId' => $fila['firmamexId'],
                                'NombreContrato' => $register->originalName,
                                'MontoAutorizado' => "$" . number_format($fila['MontoAutorizado'], 2, '.', ','),
                                'MontoSolicitado' => "$" . number_format($fila['MontoSolicitado'], 2, '.', ','),
                                'Estatus' => ($register->stickerStatus == 'STICKERS_SIGNED' ? true : false),
                            ]);
                        }
                    }
                }
            }
            if ($opc == 'c') { // devuelve contratos completados
                $consulta = $con->query("SELECT A.TotalFirmas, A.NombreContrato, A.ID_Solicitud, A.ClienteID AS ID_Cliente, A.FechaAlta,
                                            B.TipoPersona, CONCAT(B.Nombres, ' ', B.ApellidoP, ' ', B.ApellidoM) AS 'NombreDeContacto', B.Celular, C.ID_Documento AS 'ID',
                                            B.MontoAutorizado, B.MontoSolicitado
                                        FROM tb_web_va_firmamex A, tb_web_va_solicitud B, tb_web_va_docs C
                                        WHERE A.ID_Solicitud = B.ID_Solicitud AND
                                        B.ID_Solicitud = C.ID_Solicitud AND
                                        C.ID_TipoDoc = 18 AND A.Estatus = 1;");
                if ($consulta) {
                    while ($fila = $consulta->fetch_assoc()) {
                        $fila['MontoAutorizado'] = "$" . number_format($fila['MontoAutorizado'], 2, '.', ',');
                        $fila['MontoSolicitado'] = "$" . number_format($fila['MontoSolicitado'], 2, '.', ',');
                        array_push($result, $fila);
                    }
                }
                
            }
        }
    }
    if ($bandera == 'Validar_Firmas') {
        if (isset($_GET['clave']) and isset($_GET['solicitud']) and isset($_GET['cliente']) and isset($_GET['contrato'])) {
            $consulta = $con->query("UPDATE tb_web_va_firmamex SET Estatus = 1 WHERE ID_Solicitud = '" . $_GET['solicitud'] . "' AND firmamexId = '". $_GET['clave'] ."'");
            if (!$consulta) { //se realizo correctamente
                $result['error'] = true;
            } else {
                #Subir el documento a la BD
                $firmamexServices = new FirmamexServices($webId, $apiKey);
                $datos = json_decode($firmamexServices->getDocument('original', $_GET['clave']));
                $documento = $datos->original;
                // $report = $firmamex_services->getReport($_GET['clave']);

                // $result['datos_documento'] = json_decode($report, true);
                $result['documento'] = $documento;
                # Crear registro en a tabla docs
                $consulta_docs = $con->query("INSERT INTO tb_web_va_docs(ID_Solicitud, ID_Cliente, ID_TipoDoc, Archivo, Nombre_Archivo, Tamanio_Archivo, Estatus)
                                        VALUES ('". $_GET['solicitud'] ."', '". $_GET['cliente'] ."', '18', '". $documento ."', '". $_GET['contrato'] ."', '" . strlen(base64_decode($documento)) . "', 1)");
                if (!$consulta_docs) {
                    $result['error'] = true;
                    $result['contrato'] = 'no se subio';
                }
            }
        }
    }
    if ($bandera == 'Validar_cuentaho') {
        $AccountNumber = '';
        $ClienteID = isset($_POST['cliente']) ? $_POST['cliente'] : '';
        $solicitud = isset($_POST['solicitud']) ? $_POST['solicitud'] : '';
        $montoSolicitado = isset($_POST['montoSolicitado']) ? $_POST['montoSolicitado'] : '';
        $montoAutorizado = isset($_POST['montoAutorizado']) ? $_POST['montoAutorizado'] : '';
        $celularCliente = isset($_POST['telefono']) ? $_POST['telefono'] : '';
        $tipoPersona = isset($_POST['persona']) ? $_POST['persona'] : '';
        $fechaAutorizacion = isset($_POST['FechaAutoriza']) ? substr($_POST['FechaAutoriza'], 0, 10) : '';

        $consulta = $con->query("SELECT CuentaAhoID, SaldoBloq, Saldo, SaldoDispon FROM cuentasaho WHERE ClienteID = '$ClienteID' AND TipoCuentaID = '26'");
        if ($consulta) {

            if (!$consulta->num_rows) {//No tiene cuentaaho. Crearla.

                // Hay que crear la cuenta en cuentasaho y la línea de crédito. Aquí mismo se actualiza en la solicitud el campo LineasCreditoID
                $result['crearcuentalinea'] = true;
                if (!crearCuentaLinea ($ClienteID, $celularCliente, $montoAutorizado, $tipoPersona, $montoSolicitado, $fechaAutorizacion, $solicitud)) {
                    $result['error']['crearCuentaLinea'] = true;
                }

            } else { // tiene cuenta.
                $lineascredito = [];
                $fail_lineas = false;

                while ($linea = $consulta->fetch_assoc()) { #por cada cuenta que tiene el usuario verificar si tiene cuenta y si no tiene crearla
                    $AccountNumber = $linea['CuentaAhoID'];
                    $saldoDispon = $linea['SaldoDispon'];
                    $saldoC = $linea['Saldo'];

                    $lineaCredito = lineaverify($AccountNumber);
                    if (!$lineaCredito) {
                        if (!$fail_lineas) {
                            $fail_lineas = true;
                        }
                    } else if ($lineaCredito == 'noLine') { #no tiene cuenta, crearla
                        if (!crearLinea($ClienteID, $AccountNumber, $tipoPersona, $montoSolicitado, $montoAutorizado, $fechaAutorizacion, $solicitud) and !$fail_lineas) {
                            $fail_lineas = true;
                            continue;
                        } else {
                            #volver a consultar si la cuenta tiene lineadecredito (si, despues de crearla)
                            $lineaCredito = lineaverify($AccountNumber);
                            if (!$lineaCredito) {
                                if (!$fail_lineas) {
                                    $fail_lineas = true;
                                    continue;
                                }
                            } else if ($lineaCredito != 'noLine') {
                                array_push($lineascredito, array('account' => $AccountNumber, 'lineacredito' => $lineaCredito['lineacredito'], 'linea_dispon' => number_format($lineaCredito['saldodispon'], 2, '.', ','), 'linea_dispu' => number_format($lineaCredito['dispuesto'], 2, '.', ','), 'cuenta_saldo' => number_format($saldoC, 2, '.', ','), 'cuenta_dispon' => number_format($saldoDispon, 2, '.', ',')));
                            }
                        }
                    } else {
                        array_push($lineascredito, array('account' => $AccountNumber, 'lineacredito' => $lineaCredito['lineacredito'], 'linea_dispon' => number_format($lineaCredito['saldodispon'], 2, '.', ','), 'linea_dispu' => number_format($lineaCredito['dispuesto'], 2, '.', ','), 'cuenta_saldo' => number_format($saldoC, 2, '.', ','), 'cuenta_dispon' => number_format($saldoDispon, 2, '.', ',')));
                    }
                }
                if (!$fail_lineas) { # si se realizaron las consultas bien.
                    $result['lineacredito_existente'] = count($lineascredito);
                    $result['lineascredito'] = $lineascredito;
                } else {
                    $result['error']['lineascreditoerror'] = true;
                }
                /* if ($consulta->num_rows) {

                } */ 
                /* else { # Solo tiene una cuenta activa.
                    $consulta_rows = $consulta->fetch_assoc();
                    $AccountNumber = $consulta_rows['CuentaAhoID'];

                    $lineaCredito = lineaverify($AccountNumber);
                    if (!$lineaCredito) {
                        $result['error']['consultalineacredito'] = true;
                    } else if ($lineaCredito == 'noLine') {
                        if (!crearLinea($ClienteID, $AccountNumber, $tipoPersona, $montoSolicitado, $montoAutorizado, $fechaAutorizacion, $solicitud)) {
                            $result['error']['crearlineaunica'] = true;
                        } else {
                            $lineaCredito = lineaverify($AccountNumber);
                            if (!$lineaCredito) {
                                $result['error']['lineacreditounica'] = true;
                            } else if ($lineaCredito != 'noLine') {
                                $result['lineacredito_existente'] = 1;
                                $result['lineascredito'] = array('lineacredito' => $lineaCredito['lineacredito'], 'saldodispo' => $lineascredito['saldodispon'], 'saldodispo' => $lineascredito['dispuesto']);
                            }
                        }
                    } else {
                        $result['lineacredito_existente'] = 1;
                        $result['lineascredito'] = $lineaCredito;
                    }
                }       */          
            }
        } else {
            $result['error']['errorValidarCuentaAho'] = true;
        }
    }
    if ($bandera == 'validar_lineacredito') {
        $result = array('error' => []);
        $opc = isset($_POST['opc']) ? $_POST['opc'] : '';
        $solicitud = isset($_POST['sol']) ? $_POST['sol'] : '';
        $AccountNumber = isset($_POST['cuentaaho']) ? $_POST['cuentaaho'] : '';
        $lineacredito = isset($_POST['lineacredito']) ? $_POST['lineacredito'] : '';
        $clienteID = isset($_POST['cliente']) ? $_POST['cliente'] : '';
        $montoAutorizado = isset($_POST['autorizado']) ? $_POST['autorizado'] : '';
        $montoSolicitado = isset($_POST['solicitado']) ? $_POST['solicitado'] : '';
        $tipoPersona = isset($_POST['persona']) ? $_POST['persona'] : '';
        $fechaAutorizacion = isset($_POST['fechaAutoriza']) ? substr($_POST['fechaAutoriza'], 0, 10) : '';
        $celularCliente = isset($_POST['telefono']) ? $_POST['telefono'] : '';

        switch ($opc) {
            case '1': //Incrementar linea L1 + L2 (lo que tiene la línea más lo que solicita)
                $q_linea = $con->query("SELECT Solicitado, Autorizado FROM lineascredito WHERE LineaCreditoID = $lineacredito AND ClienteID = $clienteID");
                if ($q_linea) {
                    $q_linea_rows = $q_linea->fetch_assoc();

                    $solicitado = (float)$q_linea_rows['Solicitado'] + (float)$montoSolicitado;
                    $autorizado = (float)$q_linea_rows['Autorizado'] + (float)$montoAutorizado;

                    $result['queryULineascredito'] = "UPDATE lineascredito SET Solicitado = $solicitado, Autorizado = $autorizado WHERE LineaCreditoID = $lineacredito AND CuentaID = $AccountNumber  AND ClienteID = $clienteID";
                    $q_ulinea = $con->query("UPDATE lineascredito SET Solicitado = $solicitado, Autorizado = $autorizado WHERE LineaCreditoID = $lineacredito AND CuentaID = $AccountNumber  AND ClienteID = $clienteID");
                    if (!$q_ulinea) {
                        $result['error']['update_line'] = true;
                    }
                } else {
                    $result['error']['lineaconsulta'] = true;
                }
                break;
            case '2': //Reemplazar montos L1 -> L2 (lo que tiene lo quito y le pongo lo que solicita)
                $result['sql_lcremplazo'] = "UPDATE lineascredito SET Solicitado = $montoSolicitado, Autorizado = $montoAutorizado WHERE LineaCreditoID = $lineacredito AND CuentaID = $AccountNumber AND ClienteID = $clienteID";
                $q_linea = $con->query("UPDATE lineascredito SET Solicitado = $montoSolicitado, Autorizado = $montoAutorizado WHERE LineaCreditoID = $lineacredito AND CuentaID = $AccountNumber AND ClienteID = $clienteID");
                if (!$q_linea) {
                    $result['error']['update_line2'] = true;
                }
                break;
            case '3': //Crear una nueva linea LN con el id de la cuenta de ahorro
                crearCuentaLinea($clienteID, $celularCliente, $montoAutorizado, $tipoPersona, $montoSolicitado, $fechaAutorizacion, $solicitud);
                // crearLinea ($clienteID, $AccountNumber, $tipoPersona, $montoSolicitado, $montoAutorizado, $fechaAutorizacion);
                break;
        }

        if ($opc <= 2) {
            // consultar la cuentaaho
            $q_cuentaaho = $con->query("SELECT SaldoBloq, Saldo FROM cuentasaho WHERE CuentaAhoID = $AccountNumber AND ClienteID = '$clienteID' AND TipoCuentaID = '26'");
            $consulta_rows = $q_cuentaaho->fetch_assoc();
            $saldobloqueado = (float)$consulta_rows['SaldoBloq'];
            $saldo = (float)$consulta_rows['Saldo'];

            $saldobloqueado = $saldobloqueado + (float)$montoAutorizado;
            $saldo_actual = $saldo + (float)$montoAutorizado;
            $result['q_cuentasaho_saldobloq'] = "UPDATE cuentasaho SET SaldoBloq = $saldobloqueado, Saldo = $saldo_actual WHERE CuentaAhoID = $AccountNumber AND ClienteID = $clienteID AND TipoCuentaID = '26'";
            $q_cuentasaho_saldobloq = $con->query("UPDATE cuentasaho SET SaldoBloq = $saldobloqueado, Saldo = $saldo_actual WHERE CuentaAhoID = $AccountNumber AND ClienteID = $clienteID AND TipoCuentaID = '26'");
            if (!$q_cuentasaho_saldobloq) {
                $result['error']['saldoBloq'] = true;
            }

            # Poner la línea en la solicitud
            $q_sol_li = $con->query("UPDATE tb_web_va_solicitud SET LineaCreditoID = $lineacredito WHERE ID_Solicitud = '$solicitud'");
            if (!$q_sol_li) {
                $result['error']['solicitud_update'] = true;
            }
        }


        /* if ($opc == 2) {
            $result['sql_cuentaremplazo'] = "UPDATE cuentasaho SET SaldoBloq = $montoAutorizado, Saldo = $montoAutorizado, SaldoDispon = 0 WHERE CuentaAhoID = $AccountNumber AND ClienteID = $clienteID AND TipoCuentaID = '26' AND FechaApertura = '$fechaAutorizacion'";
            $saldos = $con->query("UPDATE cuentasaho SET SaldoBloq = $montoAutorizado, Saldo = $montoAutorizado, SaldoDispon = 0 WHERE CuentaAhoID = $AccountNumber AND ClienteID = $clienteID AND TipoCuentaID = '26' AND FechaApertura = '$fechaAutorizacion'");
            if (!$saldos) {
                $result['error']['remplazarsaldoscuenta'] = true;
            }
        } */
        
    }
}

function lineaverify($account) {
    global $con;

    $consulta = $con->query("SELECT LineaCreditoID, Dispuesto, Autorizado FROM lineascredito WHERE CuentaID = $account");
    if ($consulta->num_rows) { 
        $datos = $consulta->fetch_assoc();

        $datos['Dispuesto'] = number_format($datos['Dispuesto'], 2, '.', ',');
        $datos['SaldoDisponible'] = number_format($datos['Autorizado'], 2, '.', ',');
        return array("lineacredito" => $datos['LineaCreditoID'], "dispuesto" => $datos['Dispuesto'], 'saldodispon' => $datos['Autorizado']);
    } else {
        return "noLine";
    }

    return false;
}
function crearCuentaLinea ($ClienteID, $celularCliente, $montoAutorizado, $tipoPersona, $montoSolicitado, $fechaAutorizacion, $solicitud) {
    $AccountNumber = crearCuenta($ClienteID, $celularCliente, $montoAutorizado, $fechaAutorizacion); #Devuelve el numero de cuenta de ahorro.
    if ($AccountNumber) {
        if (crearLinea($ClienteID, $AccountNumber, $tipoPersona, $montoSolicitado, $montoAutorizado, $fechaAutorizacion, $solicitud) ) {
            return true;
        }
    }

    return false;
}
function crearCuenta ($ClienteID, $celularCliente, $montoAutorizado, $fechaAutorizacion) {
    global $con;
    $consultaLastId = $con->query("SELECT (MAX(A.CuentaAhoID) + 1) AS LASTID FROM cuentasaho A;");
    $AccountNumber = $consultaLastId->fetch_assoc()['LASTID'];
    // CREAR CUENTA
    $sql_cuentaaho ="INSERT INTO cuentasaho
                    SET CuentaAhoID = " . $AccountNumber . ",
                    ClienteID = " . $ClienteID . ",
                    TelefonoCelular = '$celularCliente',
                    FechaActual = NOW(),
                    Etiqueta = 'VENTACERO', 
                    TipoCuentaID = 26,
                    Saldo = $montoAutorizado,
                    SaldoBloq = $montoAutorizado";
    $consulta_cuentaaho = $con->query($sql_cuentaaho);
    if (!$consulta_cuentaaho) {
        return false;
    }

    return $AccountNumber;
}
function crearLinea ($ClienteID, $AccountNumber, $tipoPersona, $montoSolicitado, $montoAutorizado, $fechaAutorizacion, $solicitud) {
    global $con;

    if ($fechaAutorizacion == 'null' || $fechaAutorizacion == null) {
        $fechaAutorizacion = date('Y-m-d');
        //modificar la fecha de autorización de la solicitud.
        $q_sol_autorizada = $con->query("UPDATE tb_web_va_solicitud SET FechaAutoriza = NOW() WHERE ID_Solicitud = '$solicitud'");
    }

    // Crear un numero de referencia aleatorio de 1,000,000 - 9,999,999
    $random_folio = mt_rand(1000000, 9999999);
    $folio_repetido = true;
    
    while ($folio_repetido === true) { # repetir hasta que no coincida el folio con los de la tabla.
        #Consultar si el folio esta en la tabla
        $folio = $con->query("SELECT FolioContrato FROM lineascredito WHERE FolioContrato = '$random_folio'");
        if ($folio) {
            if (!$folio->num_rows) {
                $folio_repetido = false;
            } else {
                $random_folio = mt_rand(1000000, 9999999);
            }
        } else {
            break;
        }
    }

    $lineaCreditoLastID = $con->query("SELECT (MAX(LineaCreditoID) + 1) AS 'LASTID' FROM lineascredito");
    $nextNumber = $lineaCreditoLastID->fetch_assoc()['LASTID'];

    # crear la linea de credito
    $crearLinea = $con->query("INSERT INTO lineascredito(LineaCreditoID, ClienteID, CuentaID, FolioContrato, FechaInicio, FechaVencimiento, ProductoCreditoID, Solicitado, Autorizado, FechaAutoriza)
                VALUES(
                    $nextNumber, 
                    $ClienteID, 
                    " . $AccountNumber . ", 
                    '$random_folio', 
                    NOW(), 
                    '" . ((int)Date("Y") + 1) . '-' . Date("m") . '-' . Date("d") . ' ' . Date("h:i:s") . "', 
                    " . ($tipoPersona == 'F' ? 9000 : 9001) . ", 
                    $montoSolicitado,
                    $montoAutorizado,
                    '$fechaAutorizacion')");
    if (!$crearLinea) {
        return false;
    } else {
        #en la solicitud actual, al campo LineasCreditoID poner el ID de la linea que se acaba de crear
        $u_solicitud = $con->query("UPDATE tb_web_va_solicitud SET LineaCreditoID = $nextNumber WHERE ID_Solicitud = '$solicitud'");
        if (!$u_solicitud) {
            return false;
        }
    }

    return true;
}

echo json_encode($result); //retorno el array con los datos dependiendo de la bandera
$conn = null; //cierro conexion
die();
?>