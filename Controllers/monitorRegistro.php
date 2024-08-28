<?php
include_once 'conexion.php';
include 'FuncionesExtras.php';

session_start();

$bandera = isset($_POST['bandera']) ? $_POST['bandera'] : $_GET['bandera'];
if ($bandera == 'LlamarAPI_SMS') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];

    $telefonoRL = $_POST['Telefono_RL'];

    $nombreRL = $_POST['Nombre_RL'];

    //Obtener el teléfono desde la base de datos
    $selectTelefono = "SELECT * FROM tb_web_va_datasignauth WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Persona = 1;";
    $query = $con->query($selectTelefono);
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $telefonoRL = $row['Celular'];



            $curl = curl_init();

            curl_setopt_array($curl, array(
                //CURLOPT_URL => "https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=" . $telefonoRL . "&msg=Convive%20Financiera:%20Para%20continuar%20con%20el%20trámite%20de%20tu%20solicitud%20de%20crédito,%20requerimos%20autorización%20para%20consultar%20su%20historial%20crediticio,%20por%20favor%20ingrese%20a%20la%20siguiente%20liga%20para%20autorizar%20la%20consulta:%20" . "https://convivetufinanciera.com.mx/Firmaserv_VentAcero/index.php?ID_Solicitud=" . $_SESSION['ID_Solicitud'],
                CURLOPT_URL => "https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => '{
                    "phone": "' . $telefonoRL . '",
                    "msg": "Convive Financiera: Para continuar con el trámite de tu solicitus de crédito, requerimos autorización para consultar su historial crediticio"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            header("Location: codigo.php");
            exit();
        }
    } else {
        $res = [
            'estatus' => 400,
            'mensaje' => 'Error en enviar SMS',
            'Id_Solicitud' => $ID_Solicitud_Sesion
        ];
        echo json_encode($res);
        return false;
    }
} else if ($bandera == 'Mostrar_Registros') {

    $filtroSolicitudes = $_POST['Filtro_Solicitudes'];
    $sucursal_ID = $_SESSION['ID_Dependencias'];

    $estatusFiltro = $contrato = "";
    $filtroSucursales = "";

    if ($filtroSolicitudes == 'RECIENTES') {
        //SE OMITEN LAS CANCELADAS
        $estatusFiltro = " AND A.Estatus IN (1,2,3,4) ";
    } else if ($filtroSolicitudes == '5') {
        //Se cargan solicitudes "EN REVISION"
        $estatusFiltro = " AND A.Estatus = 5 ";
    } else if ($filtroSolicitudes == 'D') {
        //Se cargan solicitudes "DESEMBOLSADAS"
        $estatusFiltro = " AND A.Estatus = 'D' ";
    } else if ($filtroSolicitudes == 'A') {
        //Se cargan solicitudes "AUTORIZADAS"
        $estatusFiltro = " AND A.Estatus = 'A' ";
        $contrato = " , IFNULL((SELECT 1 FROM tb_web_va_firmamex WHERE ID_Solicitud = A.ID_Solicitud LIMIT 1), 0) AS 'contrato', IFNULL((SELECT 1 FROM tb_web_va_firmamex WHERE ID_Solicitud = A.ID_Solicitud AND Estatus = 1 LIMIT 1), 0) AS 'contratoCompleto', IFNULL((SELECT Calificacion FROM tb_web_va_scorecredito WHERE ID_Solicitud = A.ID_Solicitud AND Estatus = 'A' LIMIT 1), 0) AS 'Calificacion'";
    } else if ($filtroSolicitudes == 'C') {
        //Se cargan solicitudes "CANCELADAS"
        $estatusFiltro = " AND A.Estatus = 'C' ";
    }

    $rolesSucursalesFiltro = [1, 2, 3];
    if (in_array($_SESSION['role'], $rolesSucursalesFiltro)) {
        $filtroSucursales .= " AND A.ID_Sucursal = $sucursal_ID";
    }

    if ($_SESSION['role'] == 11 && $sucursal_ID != 0) //Significa que trae Sucursal ID correspondiente a una matriz de región
    {
        $filtroSucursales .= "  AND A.ID_Sucursal IN (SELECT DD.ID_Sucursal
                                FROM
                                tb_web_va_sucursales DD
                                WHERE DD.JefeComercial IN (SELECT EE.JefeComercial FROM tb_web_va_sucursales EE WHERE EE.ID_Sucursal = $sucursal_ID)) ";
    }
    if ($_SESSION['role'] == 11 && $sucursal_ID == 0) {
        //No tiene filtro de sucursales
        $filtroSucursales .= "";
    }

    // echo "Rol ". $_SESSION['role'];
    // $filtroSucursales = "";
    $queryMonitor = "SELECT @rownum := @rownum + 1 AS Item, 
                            A.ID, 
                            A.ID_Solicitud, 
                            CONCAT(A.RazonSocial,' ', A.Nombres, ' ', A.ApellidoP, ' ', A.ApellidoM) as 'NomRazon',  
                            CONCAT('$',FORMAT(A.MontoSolicitado,2,'en_US')) as MontoSolicitado,  
                            C.Sucursal,
                            B.Alias AS 'Ejecutivo',
                            DATE_FORMAT(A.FechaAlta, '%Y-%m-%d') as FechaAlta, 
                            A.Estatus, 
                            CASE WHEN A.Estatus * (100/5) > 100 THEN '100.00%' ELSE CONCAT(FORMAT(A.Estatus * (100/5), 2),'%') END AS Estatus, 
                            IF((SELECT COUNT(ID_Observacion) FROM tb_web_va_observaciones D WHERE D.ID_Solicitud = A.ID_Solicitud) > 0, 'Si', 'No') AS 'Observaciones',
                            DATE_FORMAT(A.FechaModi, '%Y-%m-%d') AS FechaModi,
                            A.TipoPersona AS 'Persona',
                            CONCAT('$', FORMAT(A.MontoAutorizado, 2, 'en_US')) AS 'MontoAutorizado',
                            A.ID_Cliente AS 'ClienteID',
                            A.Celular,
                            A.FechaAutoriza $contrato
                        FROM tb_web_va_solicitud A,
                            users B,
                            tb_web_va_sucursales C,
                            (SELECT @rownum := 0) r
                        WHERE A.ID_Usuario = B.id AND
                            A.ID_Sucursal = C.ID_Sucursal $estatusFiltro $filtroSucursales
                            ORDER BY A.FechaAlta DESC;";
    // echo $queryMonitor;
    $result = $con->query($queryMonitor);

    $solicitudes = array();
    while ($row = $result->fetch_assoc()) {
        $solicitudes[] = $row;
    }

    //var_dump($solicitudes);

    echo json_encode($solicitudes);
} else if ($bandera == 'Eliminar_Solicitud') {
    $ID_Solicitud_Eliminar = $_POST['solicitud_eliminar'];

    $updateStatus = $con->query("UPDATE tb_web_va_solicitud SET Estatus = 5 WHERE ID_Solicitud = '$ID_Solicitud_Eliminar';");

    if ($updateStatus) {
        $res = [
            'estatus' => 200,
            'mensaje' => 'La solicitud ha sido eliminada correctamente.'
        ];
        echo json_encode($res);
        return false;
    } else {
        $res = [
            'estatus' => 400,
            'mensaje' => 'La solicitud ' . $ID_Solicitud_Eliminar . ' no se ha podido eliminar.'
        ];
        echo json_encode($res);
        return false;
    }
} else if ($bandera == 'GuardarInfo_Registro') {
    //Se utiliza esta sección para guardar avances o iniciar una solicitud nueva

    $ID_Registro_Sesion = $_SESSION['ID_Registro']; //Se busca para ver si se está avanzando el proceso de una solicitud ya iniciada
    $ID_Cliente = 0;

    $fechaActual = date('Y-m-d H:m:s');

    $nombresUsuario = mysqli_real_escape_string($con, $_POST['nombresUsuario']);
    $apellidoPaterno = mysqli_real_escape_string($con, $_POST['apellidoPaterno']);
    $apellidoMaterno = mysqli_real_escape_string($con, $_POST['apellidoMaterno']);
    $celular = mysqli_real_escape_string($con, $_POST['celular']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $dependencia = mysqli_real_escape_string($con, $_POST['dependencia']);
    $area = mysqli_real_escape_string($con, $_POST['area']);
    $cargo = mysqli_real_escape_string($con, $_POST['cargo']);
    $noEmpleado = mysqli_real_escape_string($con, $_POST['noEmpleado']);

    if ($ID_Registro_Sesion == '') {

        $ID_Dependencia = $_SESSION['ID_Dependencia'];
        $ID_Usuario = $_SESSION['ID_Usuario'];

        //AL GENERAR SOLICITUD ID SE BUSCA AL USUARIO EN LA TABLA 'clientes' O 'microfin_user' en caso de ser administradores con prioridad en clientes, si no existe REGISTRAMOS
        //Iniciamos transacción para finalizar el registro completo
        $con->begin_transaction();

        try {
            //SE BUSCA REGISTRO PREVIO EN clientes
            $buscarCliente = $con->query("SELECT * FROM clientes WHERE TelefonoCelular = '$celular' OR Correo = '$correo';");

            //SE ENCUENTRA REGISTRO
            if ($buscarCliente->num_rows > 0) {
                while ($row = $buscarCliente->fetch_array()) {
                    $telCliente = $row['TelefonoCelular'];
                    $correoCliente = $row['Correo'];
                    $ID_Cliente = $row['ClienteID'];
                    $nombreCompletoCliente = $row['NombreCompleto'];
                }

                if ($correoCliente == $correo || $telCliente == $celular) { //DATOS INGRESADOS YA REGISTRADOS EN OTRO CLIENTE
                    $res = [
                        'estatus' => 206,
                        'mensaje' => 'El correo y/o celular proporcionados ya están registrados con el cliente ' . $ID_Cliente . ', favor de verificar',
                        'toastClass' => 'warning',
                        'toastColor' => 'linear-gradient(to right,  #5087FF, #5087FF)'
                    ];
                    $_SESSION['ID_Registro'] = '';
                    echo json_encode($res);
                    return false;
                }
            }
            //NO SE ENCUENTRA REGISTRO EN CLIENTE
            else {
                $nuevoID = $con->query("SELECT MAX(ClienteID) + 1 AS ClienteIDNuevo FROM clientes");
                while ($row = $nuevoID->fetch_array()) {
                    $ID_Cliente = $row["ClienteIDNuevo"];
                }

                //SE REGISTRA CLIENTE
                $registrarCliente = "INSERT INTO clientes (ClienteID, NombreCompleto, TelefonoCelular, Correo, PrimerNombre, ApellidoPaterno, ApellidoMaterno) VALUES ";

                $registrarCliente .= "( $ID_Cliente, '$nombresUsuario', '$celular', '$correo', '$nombresUsuario', '$apellidoPaterno', '$apellidoMaterno')";

                $resultCliente = $con->query($registrarCliente);

                if ($resultCliente) {
                    $selectUser = $con->query("SELECT * FROM users WHERE email = '$correo' AND celular = '$celular';"); //Se busca registro previo por correo y celular en users

                    //ACTUALIZA USER SI YA HAY REGISTRO
                    if ($selectUser->num_rows > 0) {
                        while ($rowUser = $selectUser->fetch_assoc()) {
                            $ID_User = $rowUser['id'];
                        }

                        $alias = explode(" ", $nombresUsuario); //Del nombre asociado a la solicitud (persona física o representante legal se extrae el primer nombre)

                        $actualizarUser = $con->query("UPDATE users SET empresa = 'Control Viáticos', alias = '$alias[0]', name = '$nombresUsuario', email = '$correo', celular = '$celular' WHERE id = $ID_User");

                        if ($actualizarUser) {
                            $_SESSION['ID_Cliente'] = $ID_Cliente;
                        } else {
                            $_SESSION['ID_Cliente'] = '';
                            $_SESSION['ID_Registro'] = '';
                        }
                    }
                    //INSERTA USER SI NO HAY REGISTRO
                    else {
                        $created_at = date('Y-m-d H:i:s');
                        $alias = explode(" ", $nombresUsuario);
                        $insertUser = $con->query("INSERT INTO users (empresaID, empresa, alias, name, email, celular, password, created_at, role) VALUES (67, 'Control Viáticos', '$alias[0]', '$nombresUsuario', '$correo', '$celular', 1, '$created_at', 'user');");
                        if ($insertUser) {
                            $_SESSION['ID_Cliente'] = $ID_Cliente;
                        } else {
                            $_SESSION['ID_Cliente'] = '';
                            $_SESSION['ID_Registro'] = '';
                        }
                    }
                }
            }

            $insert = $con->query("INSERT INTO tb_web_cv_registro (ID_Cliente, ID_Usuario, Nombres, ApellidoP, ApellidoM, Celular, Correo, ID_Dependencia, Area, Cargo, NoEmpleado, Estatus, FechaAlta)
                                                        VALUES ($ID_Cliente, '$ID_Usuario','$nombresUsuario','$apellidoPaterno','$apellidoMaterno','$celular','$correo', $ID_Dependencia, '$area', '$cargo', '$noEmpleado', 'A','$fechaActual');");

            if ($insert === TRUE) {
                $lastInsertID = $con->insert_id;
                $_SESSION['ID_Registro'] = $lastInsertID;
                $con->commit();
                $res = [
                    'estatus' => 200,
                    'mensaje' => 'Se han guardado el registro correctamente',
                    'toastClass' => 'success',
                    'toastColor' => 'linear-gradient(to right, #4df755, #04c20d)'
                ];
                echo json_encode($res);
                return false;
            } else {
                $res = [
                    'estatus' => 400,
                    'mensaje' => 'No se han podido guardar el registro',
                    'toastClass' => 'error',
                    'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
                ];
                $_SESSION['ID_Cliente'] = '';
                $_SESSION['ID_Registro'] = '';
                $con->rollback();
                echo json_encode($res);
                return false;
            }
        } catch (mysqli_sql_exception $exception) {
            $res = [
                'estatus' => 400,
                'mensaje' => 'No se han podido guardar los cambios.' . ' Error: ' . $exception->getMessage(),
                'toastClass' => 'error',
                'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
            ];
            $con->rollback();
            echo json_encode($res);
            $_SESSION['ID_Registro'] = '';

            //throw $exception;
        }
    }
} else if ($bandera == 'CargarAvances_Registro') {
    $ID_Registro_Sesion = $_SESSION['ID_Registro'];
    //Consulta para traer información de la página 1
    $result = $con->query("SELECT * FROM tb_web_cv_registro where ID = '$ID_Registro_Sesion';");

    $registros = array();
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
        $_SESSION['ID_Cliente'] = $row['ID_Cliente'];
    }

    echo json_encode($solicitudes);
} 