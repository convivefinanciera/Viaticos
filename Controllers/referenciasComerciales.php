<?php
include_once 'conexion.php';
#Configuracion zona horaria
setlocale(LC_ALL, 'es_MX');
date_default_timezone_set("America/Mexico_City");
$Fecha = DATE("Y-m-d H:i:s");

session_start();

$bandera = $_POST['bandera'];

if ($bandera == 'GuardarInfo_ReferenciasComerciales') {
    // Recibir datos del formulario
    $nombre_proveedor1 = $_POST['inputNombreProveedor1'];
    $telefono_ref_com1 = $_POST['inputTelefonoRefCom1'];
    $plazo1 = $_POST['inputPlazo1'];
    $limite1 = $_POST['inputLimite1'];

    $nombre_proveedor2 = $_POST['inputNombreProveedor2'];
    $telefono_ref_com2 = $_POST['inputTelefonoRefCom2'];
    $plazo2 = $_POST['inputPlazo2'];
    $limite2 = $_POST['inputLimite2'];

    $nombre_proveedor3 = $_POST['inputNombreProveedor3'];
    $telefono_ref_com3 = $_POST['inputTelefonoRefCom3'];
    $plazo3 = $_POST['inputPlazo3'];
    $limite3 = $_POST['inputLimite3'];

    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $ID_Cliente = $_SESSION['ID_Cliente'];

    // $fecha_alta = date("Y-m-d H:i:s");
    //$ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    //$id_cliente = $_SESSION['ID_Cliente'];

    // Validar que los campos no sean nulos
    if (empty($nombre_proveedor1) || empty($telefono_ref_com1) || empty($plazo1) || empty($limite1)) {
        $res = [
            'estatus' => 400,
            'mensaje' => 'Los datos de la Referencia Comercial 1 no están completos.'
        ];
        echo json_encode($res);
        exit;
    }

    $con->begin_transaction();

    try {
        $selectReferencias = $con->query("SELECT Id, ID_Ref FROM tb_web_va_refcomerciales WHERE ID_Solicitud = '$ID_Solicitud_Sesion' order by ID_Ref;");
        $refRegistradas = array();
        while ($row = $selectReferencias->fetch_assoc()) {
            $refRegistradas[] = $row;
        }

        for ($r = 1; $r <= 3; $r++) //Son 3 referencias las que se verifican
        {
            if (sizeof($refRegistradas) > 0) //Si ya hay registradas se actualizan las registradas o se insertan las que vengan con datos y no registradas aún
            {

                if (array_search($r, array_column($refRegistradas, 'ID_Ref')) != "") {
                    //Se encuentra la referencia comercial número r (contador) en el arreglo de referencias de la solicitud
                    if ($r == 1) {
                        $updateRef = "UPDATE tb_web_va_refcomerciales SET Proveedor = '$nombre_proveedor1', Telefono = '$telefono_ref_com1', Plazo = '$plazo1', Limite = '$limite1', Fecha_alta = '$Fecha' WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Ref = $r;";
                    }
                    if ($r == 2) {
                        $updateRef = "UPDATE tb_web_va_refcomerciales SET Proveedor = '$nombre_proveedor2', Telefono = '$telefono_ref_com2', Plazo = '$plazo2', Limite = '$limite2', Fecha_alta = '$Fecha' WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Ref = $r;";
                    }
                    if ($r == 3) {
                        $updateRef = "UPDATE tb_web_va_refcomerciales SET Proveedor = '$nombre_proveedor3', Telefono = '$telefono_ref_com3', Plazo = '$plazo3', Limite = '$limite3', Fecha_alta = '$Fecha' WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Ref = $r;";
                    }

                    $res = $con->query($updateRef);
                    if ($res) {
                        //Continúa
                    } else {
                        $con->rollback();
                        $res = [
                            'estatus' => 400,
                            'mensaje' => 'Error al actualizar las referencias comerciales.'
                        ];
                        echo json_encode($res);
                    }
                } else {
                    $hayRegistro = false;
                    $sql = "INSERT INTO tb_web_va_refcomerciales (ID_Solicitud, ID_Cliente, ID_Ref, Proveedor, Telefono, Plazo, Limite, Fecha_alta) VALUES";
                    if (!empty($nombre_proveedor1) && $r == 1) {
                        $hayRegistro = true;
                        $sql .= " ('$ID_Solicitud_Sesion', $ID_Cliente , 1,'$nombre_proveedor1', '$telefono_ref_com1', '$plazo1', '$limite1', '$Fecha')";
                    }
                    if (!empty($nombre_proveedor2) && $r == 2) {
                        $hayRegistro = true;
                        $sql .= " ('$ID_Solicitud_Sesion', $ID_Cliente , 2,'$nombre_proveedor2', '$telefono_ref_com2', '$plazo2', '$limite2', '$Fecha')";
                    }
                    if (!empty($nombre_proveedor3) && $r == 3) {
                        $hayRegistro = true;
                        $sql .= " ('$ID_Solicitud_Sesion', $ID_Cliente , 3,'$nombre_proveedor3', '$telefono_ref_com3', '$plazo3', '$limite3', '$Fecha')";
                    }
                    $sql .= ";";

                    if ($hayRegistro == true) {
                        $res = $con->query($sql);

                        if ($res) {
                            //Continúa
                        } else {
                            $con->rollback();
                            $res = [
                                'estatus' => 400,
                                'mensaje' => 'Error al guardar las referencias comerciales.'
                            ];
                            echo json_encode($res);
                        }
                    }
                }
            } else {
                $sql = "INSERT INTO tb_web_va_refcomerciales (ID_Solicitud, ID_Cliente, ID_Ref, Proveedor, Telefono, Plazo, Limite, Fecha_alta) VALUES ";
                $registrarRef = false;
                if (!empty($nombre_proveedor1) && $r == 1) {
                    $registrarRef = true;
                    $sql .= " ('$ID_Solicitud_Sesion', $ID_Cliente , 1,'$nombre_proveedor1', '$telefono_ref_com1', '$plazo1', '$limite1', '$Fecha')";
                }
                if (!empty($nombre_proveedor2) && $r == 2) {
                    $registrarRef = true;
                    $sql .= " ('$ID_Solicitud_Sesion', $ID_Cliente , 2,'$nombre_proveedor2', '$telefono_ref_com2', '$plazo2', '$limite2', '$Fecha')";
                }
                if (!empty($nombre_proveedor3) && $r == 3) {
                    $registrarRef = true;
                    $sql .= " ('$ID_Solicitud_Sesion', $ID_Cliente , 3,'$nombre_proveedor3', '$telefono_ref_com3', '$plazo3', '$limite3', '$Fecha')";
                }
                $sql .= ";";

                if ($registrarRef == true) {
                    $res = $con->query($sql);
                }
            }
        }

        $updateEstatus = $con->query("UPDATE tb_web_va_solicitud SET Estatus = 4 WHERE ID_Solicitud = '$ID_Solicitud_Sesion'");

        if ($con->commit()) {
            $res = [
                'estatus' => 200,
                'mensaje' => 'Información de Referencias Comerciales guardados'
            ];
            echo json_encode($res);
        }
    } catch (mysqli_sql_exception $exception) {
        $con->rollback();
        $res = [
            'estatus' => 400,
            'mensaje' => 'Error al ejecutar la transacción: '.$exception->getMessage()
        ];
        echo json_encode($res);
    }

    // if(sizeof($refRegistradas) > 0)
    // {
    //     for($i = 0; $i < sizeof($refRegistradas); $i++)
    //     {
    //         // var_dump($refRegistradas[$i]);
    //         echo "ID ".$refRegistradas[$i]['Id']." Ref Numero ".$refRegistradas[$i]['ID_Ref']."\n";

    //         for($r = 1; $r <= 3; $r++)
    //         {
    //             if($r == $i)
    //             {
    //                 $updateRef = "UPDATE tb_web_va_refcomerciales SET Proveedor, Telefono, Plazo, Limite, Fecha_alta WHERE ID_Solicitud = '$ID_Solicitud_Sesion';";
    //             }
    //             else
    //             {
    //                 $sql = "INSERT INTO tb_web_va_refcomerciales (ID_Solicitud, ID_Cliente, ID_Ref, Proveedor, Telefono, Plazo, Limite, Fecha_alta) VALUES ";
    //                 if (!empty($nombre_proveedor1) && $r == 1) {
    //                     $sql .= "('$ID_Solicitud_Sesion', $ID_Cliente , 1,'$nombre_proveedor1', '$telefono_ref_com1', '$plazo1', '$limite1', '$Fecha')";
    //                 }
    //                 if (!empty($nombre_proveedor2) && $r == 2) {
    //                     $sql .= ",('$ID_Solicitud_Sesion', $ID_Cliente , 2,'$nombre_proveedor2', '$telefono_ref_com2', '$plazo2', '$limite2', '$Fecha')";
    //                 }
    //                 if (!empty($nombre_proveedor3) && $r == 3) {
    //                     $sql .= ",('$ID_Solicitud_Sesion', $ID_Cliente , 3,'$nombre_proveedor3', '$telefono_ref_com3', '$plazo3', '$limite3', '$Fecha')";
    //                 }
    //                 $sql .= ";";
    //             }
    //         }
    //     }
    // }

    // Insertar referencias comerciales en la base de datos
    // $sql = "INSERT INTO tb_web_va_refcomerciales (ID_Solicitud, ID_Cliente, ID_Ref, Proveedor, Telefono, Plazo, Limite, Fecha_alta) VALUES ";
    // if (!empty($nombre_proveedor1)) {
    //     $sql .= "('$ID_Solicitud_Sesion', $ID_Cliente , 1,'$nombre_proveedor1', '$telefono_ref_com1', '$plazo1', '$limite1', '$Fecha')";
    // }
    // if (!empty($nombre_proveedor2)) {
    //     $sql .= ",('$ID_Solicitud_Sesion', $ID_Cliente , 2,'$nombre_proveedor2', '$telefono_ref_com2', '$plazo2', '$limite2', '$Fecha')";
    // }
    // if (!empty($nombre_proveedor3)) {
    //     $sql .= ",('$ID_Solicitud_Sesion', $ID_Cliente , 3,'$nombre_proveedor3', '$telefono_ref_com3', '$plazo3', '$limite3', '$Fecha')";
    // }
    // $sql .= ";";


} else if ($bandera == 'CargarAvances_ReferenciasComerciales') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];

    //Consulta para traer información de referencias comerciales
    $result = $con->query("SELECT * FROM tb_web_va_refcomerciales WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");

    $referencias = array();
    while ($row = $result->fetch_assoc()) {
        $referencias[] = $row;
    }

    echo json_encode($referencias);
}
