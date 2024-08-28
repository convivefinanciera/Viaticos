<?php
include_once 'conexion.php';
include 'FuncionesExtras.php';

session_start();

$bandera = isset($_POST['bandera']) ? $_POST['bandera'] : $_GET['bandera'];

if ($bandera == 'Finalizar_Solicitud') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $EstatusFinalizada = $_POST['Estatus_Final'];

    $finalizaSolicitud = $con->query("UPDATE tb_web_va_solicitud SET Estatus = '$EstatusFinalizada' WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");

    if ($EstatusFinalizada == 'A') {
        $selectMontoSolicitado = $con->query("SELECT MontoSolicitado FROM tb_web_va_solicitud WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");

        $montoSol = 0;
        while ($row = $selectMontoSolicitado->fetch_assoc()) {
            $montoSol = $row['MontoSolicitado'];
        }

        if ($finalizaSolicitud) {
            $res = [
                'estatus' => 200,
                'mensaje' => 'La solicitud ' . $ID_Solicitud_Sesion . ' se ha marcado como FINALIZADA',
                'montoSolicitado' => $montoSol,
                'toastClass' => 'success',
                'toastColor' => 'linear-gradient(to right, #4df755, #04c20d)'
            ];
            echo json_encode($res);
            return false;
        } else {
            $res = [
                'estatus' => 400,
                'mensaje' => 'No se han podido cambiar el estatus a la solicitud ' . $ID_Solicitud_Sesion,
                'toastClass' => 'error',
                'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
            ];
            echo json_encode($res);
            return false;
        }
    }
    if ($EstatusFinalizada == 'C') {
        if ($finalizaSolicitud) {
            $res = [
                'estatus' => 200,
                'mensaje' => 'La solicitud has sido FINALIZADA y NO HA SIDO APROBADA.',
                'toastClass' => 'success',
                'toastColor' => 'linear-gradient(to right, #f7db4d, #deb902))'
            ];
            echo json_encode($res);
            return false;
        } else {
            $res = [
                'estatus' => 400,
                'mensaje' => 'No se han podido cambiar el estatus a la solicitud ' . $ID_Solicitud_Sesion,
                'toastClass' => 'error',
                'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
            ];
            echo json_encode($res);
            return false;
        }
    }
}
if ($bandera == 'Autorizar_Monto') {
    $montoAutorizado = $_POST['Monto_Autorizado'];
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $fecha = date("Y-m-d H:i:s");

    $updateMonto = $con->query("UPDATE tb_web_va_solicitud SET MontoAutorizado = $montoAutorizado, Estatus = 'A', FechaAutoriza = '$fecha' WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");

    if ($updateMonto) {
        $res = [
            'estatus' => 200,
            'mensaje' => 'Se han autorizado ' . $montoAutorizado . ' MXN para la Solicitud ' . $ID_Solicitud_Sesion . '.',
            'toastClass' => 'success',
            'toastColor' => 'linear-gradient(to right, #4df755, #04c20d)'
        ];
        echo json_encode($res);
        return false;
    } else {
        $res = [
            'estatus' => 400,
            'mensaje' => 'Ocurrió un error al autorizar el monto para la solicitud ' . $ID_Solicitud_Sesion . '.',
            'toastClass' => 'error',
            'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
        ];
        echo json_encode($res);
        return false;
    }
}
if ($bandera == 'Mostrar_CalificacionesTabla') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];

    $selectCalificaciones = $con->query("SELECT A.*, B.ID_TipoDoc FROM tb_web_va_scorecredito A, tb_web_va_docs B 
                            WHERE A.ID_Documento = B.ID_Documento AND A.Estatus = 'A' AND B.Estatus = 1 AND A.ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_TipoDoc <= 11 ORDER BY B.ID_TipoDoc;");

    $calificacionesObtenidas = array();
    while ($row = $selectCalificaciones->fetch_assoc()) {
        $calificacionesObtenidas[] = $row;
    }

    echo json_encode($calificacionesObtenidas);
}

if ($bandera == 'MostrarDocs_Verificados') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];

    $selectVerificados = $con->query("SELECT ID_TipoDoc, Verificacion FROM tb_web_va_docs WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND Estatus = '1' AND ID_TipoDoc <= 11 AND (ID_TipoDoc != 8 OR ID_TipoDoc != 9) ORDER BY ID_TipoDoc;");
    $verificaciones = array();

    while ($row = $selectVerificados->fetch_assoc()) {
        $verificaciones[] = $row;
    }

    echo json_encode($verificaciones);
}
if ($bandera == 'Verificar_Documento') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $ID_TipoDoc = $_POST['Documento_ID'];
    $Verificado = $_POST['Verificacion'];
    $mensaje = $Verificado == 1 ? 'Se ha marcado como "VERIFICADO" el archivo.' : 'Se ha marcado como "NO VERIFICADO" el archivo.';
    $examinar = $Verificado == 1 ? 'D' : 'H';
    $estadoVerificacion = $con->query("UPDATE tb_web_va_docs SET Verificacion = $Verificado WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_TipoDoc = $ID_TipoDoc AND Estatus = 1;");

    $updateEstatus5 = $con->query("UPDATE tb_web_va_solicitud
                                    SET Estatus = 5
                                    WHERE ID_Solicitud = '$ID_Solicitud_Sesion'
                                    AND (SELECT COUNT(*) 
                                        FROM tb_web_va_docs 
                                        WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND Estatus = 1) = 
                                        (SELECT COUNT(*) 
                                        FROM tb_web_va_docs 
                                        WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND Estatus = 1 AND Verificacion = 1);");

    if ($estadoVerificacion) {
        $res = [
            'estatus' => 200,
            'mensaje' => $mensaje,
            'examinar' => $examinar,
            'toastClass' => 'success',
            'toastColor' => 'linear-gradient(to right, #4df755, #04c20d)'
        ];
        echo json_encode($res);
        return false;
    } else {
        $res = [
            'estatus' => 400,
            'mensaje' => 'Ocurrió un error al cambiar el estado de la verificación del archivo.',
            'toastClass' => 'error',
            'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
        ];
        echo json_encode($res);
        return false;
    }
}
if ($bandera == 'Ver_DetallesFotos') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];

    $obtenerFotos = $con->query("SELECT * FROM tb_web_va_docs WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND Estatus = 1 AND ID_Parametro = 1;");
    $carrouselFotos = "";
    if ($obtenerFotos->num_rows > 0) {
        $carrouselFotos .= '<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">';
        $carrouselFotos .= '<div class="carousel-inner">';
        $cont = 0;
        while ($row = $obtenerFotos->fetch_assoc()) {
            $nomDocArch = explode(".", $row['Nombre_Archivo']);
            $tipoDocArch = $nomDocArch[count($nomDocArch) - 1];
            $archivo = $row['Archivo'];

            if ($cont == 0) {
                $carrouselFotos .= '<div class="carousel-item active">';
            } else if ($cont > 0) {
                $carrouselFotos .= '<div class="carousel-item">';
            }
            if ($tipoDocArch == "png") {
                $carrouselFotos .= '<img src="data:image/png;base64,' . $archivo . '" "class="d-block w-100 ">';
            }
            if ($tipoDocArch == "jpg" || $tipoDocArch == "jpeg") {
                $carrouselFotos .= '<img src="data:image/jpeg;base64,' . $archivo . '" "class="d-block w-100 ">';
            }
            $carrouselFotos .= '</div>';

            $cont++;
        }
        $carrouselFotos .= '</div>';
        $carrouselFotos .= '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>';
        $carrouselFotos .= '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>';

        $carrouselFotos .= '</div>';

        echo $carrouselFotos;
    }
}

if ($bandera == 'Calificar_FotosNegocio') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $ID_Cliente = $_SESSION['ID_Cliente'];
    $Calificacion = $_POST['Calificacion_Fotos'];
    $fechaActual = date("Y-m-d H:i:s");

    $con->begin_transaction();
    try {
        $updateEstatusCal = $con->query("UPDATE tb_web_va_scorecredito SET Estatus = 'C' WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Parametro = 1 AND Estatus = 'A';");

        //Se guarda la calificación del documento
        $insertCalificacion = $con->query("INSERT INTO tb_web_va_scorecredito (ID_Solicitud, ID_Cliente, ID_Documento, ID_Parametro, Calificacion, Estatus, FechaAlta) 
                                                VALUES ('$ID_Solicitud_Sesion', $ID_Cliente, 0, 1, $Calificacion, 'A', '$fechaActual');");

        //Se cambia el estatus de Verificado a 1 en la tabla docs
        $verificarDocumento = $con->query("UPDATE tb_web_va_docs SET Verificacion = 1 WHERE ID_TipoDoc = 11 AND ID_Parametro = 1 AND ID_Solicitud = '$ID_Solicitud_Sesion';");

        //Se calcula el ponderado si ya han sido calificados la totalidad de los documentos que son parámetros
        $calificarSolicitud = $con->query("SELECT IF
        ((SELECT COUNT(DISTINCT A.ID_Parametro) FROM tb_web_va_scoreparametros A, tb_web_va_scorecredito B 
        WHERE A.ID_Parametro = B.ID_Parametro AND A.ID_Parametro > 0 AND A.Estatus = 'A' AND B.Estatus = 'A' AND B.ID_Solicitud = '$ID_Solicitud_Sesion')
        =
        (SELECT COUNT(*) FROM tb_web_va_scoreparametros WHERE ID_Parametro > 0 AND Estatus = 'A'),
        (SELECT FORMAT(SUM(((A.Valor * B.Calificacion) / 100)), 2) AS Ponderado FROM tb_web_va_scoreparametros A, tb_web_va_scorecredito B  
        WHERE A.ID_Parametro = B.ID_Parametro AND A.ID_Parametro > 0 AND A.Estatus = 'A' AND B.Estatus = 'A' AND B.ID_Solicitud = '$ID_Solicitud_Sesion'),
        0) AS Ponderado;");

        while ($row = $calificarSolicitud->fetch_assoc()) {
            $ponderado = $row['Ponderado'];
        }

        if ($ponderado > 0) { //Si se cumple la condición se pondera la calificación y se inserta el registro como ID_Parametro 0 (Solicitud de crédito)
            $califTotalRecalculada = $con->query("UPDATE tb_web_va_scorecredito SET Estatus = 'C' WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Parametro = 0;");

            $calificarSolicitud = $con->query("INSERT INTO tb_web_va_scorecredito (ID_Solicitud, ID_Cliente, ID_Documento, ID_Parametro, Calificacion, Estatus, FechaAlta) 
                                                    VALUES ('$ID_Solicitud_Sesion', $ID_Cliente, 0, 0, $ponderado, 'A', '$fechaActual');");

            $estatusFinalizada = $ponderado >= 70 ? 'A' : 'C';

            $updateEstatusSol = $con->query("UPDATE tb_web_va_solicitud SET Estatus = '$estatusFinalizada' WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");

            if ($updateEstatusSol) {
                $con->commit();
                $res = [
                    'estatus' => 200,
                    'mensaje' => 'La calificación de las Fotografías se ha guardado.',
                    'toastClass' => 'success',
                    'toastColor' => 'linear-gradient(to right, #4df755, #04c20d)'
                ];
                echo json_encode($res);
                return false;
            } else {
                $con->rollback();
                $res = [
                    'estatus' => 400,
                    'mensaje' => 'Ocurrió un error al calificar las fotografías.',
                    'toastClass' => 'error',
                    'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
                ];
                echo json_encode($res);
                return false;
            }
        } else { //No cumple condición, finaliza script con la pura inserción de la calificación
            $con->commit();
            $res = [
                'estatus' => 200,
                'mensaje' => 'La calificación de las Fotografías se ha guardado.',
                'toastClass' => 'success',
                'toastColor' => 'linear-gradient(to right, #4df755, #04c20d)'
            ];
            echo json_encode($res);
            return false;
        }
    } catch (mysqli_sql_exception $exception) {
        $con->rollback();

        $res = [
            'estatus' => 400,
            'mensaje' => 'Error al guardar la calificación: ' . $exception->getMessage(),
            'toastClass' => 'error',
            'toastColor' => 'linear-gradient(to right, #ff3636, #de0202)'
        ];
        echo json_encode($res);
        return false;
    }
}
if ($bandera == 'imagen_rotada') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $img_rotada = file_get_contents($_FILES['imagen_blob']['tmp_name']);

    $img_rotada_blob = base64_encode($img_rotada);

    var_dump($img_rotada);

    $updateImg = $con->prepare("UPDATE tb_web_va_docs SET Archivo = ? WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_TipoDoc = 14;");
    $updateImg->bind_param('b', $img_rotada_blob);
    $updateImg->send_long_data(0, $img_rotada_blob);

    if ($updateImg->execute()) {
        $res = [
            "estatus" => 200,
            "mensaje" => "Imagen rotada"
        ];

        echo json_encode($res);
    } else {
        $res = [
            "estatus" => 400,
            "mensaje" => "Error al rotar imagen"
        ];

        echo json_encode($res);
    }
}
if ($bandera == 'Rotar_Firma') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $sentido_rotacion = $_POST['sentido_rotacion'];
    $grados = 0;
    if($sentido_rotacion == 'I')
    {
        $grados = 90;
    }
    if($sentido_rotacion == 'D')
    {
        $grados = -90;
    }

    $selectRotar = $con->query("SELECT ID_Documento, Archivo from tb_web_va_docs WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_TipoDoc = 14 AND Estatus = 1;");

    $row = $selectRotar->fetch_assoc();
    $img_blob = $row['Archivo'];
    $ID_Img_Rotar = $row['ID_Documento'];

    // var_dump($img_blob);

    // Verifica si la cadena tiene el prefijo 'data:image/png;base64,'
    if (strpos($img_blob, 'data:image/png;base64,') === 0) {
        // Remover el prefijo
        $img_blob = str_replace('data:image/png;base64,', '', $img_blob);
    }

    // var_dump($img_blob);

    // $imageDecoded = base64_decode($img_blob);

    // var_dump($imageDecoded);

    $imagen_creada = imagecreatefromstring(base64_decode($img_blob));

    if ($imagen_creada === false) {
        $res = [
            'estatus' => 400,
            'mensaje' => 'Error al cargar imagen desde la base de datos.'
        ];

        echo json_encode($res);
    } else {
        $ancho = imagesx($imagen_creada);
        $alto = imagesy($imagen_creada);

        //Lienzo con fondo blanco
        $lienzo = imagecreatetruecolor($ancho, $alto);

        $fondo_blanco = imagecolorallocate($lienzo, 255, 255, 255);
        imagefill($lienzo, 0, 0, $fondo_blanco);

        // Habilitar la transparencia en el lienzo
        imagealphablending($lienzo, true);
        imagesavealpha($lienzo, true);

        // Copiar la imagen original al lienzo con fondo blanco
        imagecopy($lienzo, $imagen_creada, 0, 0, 0, 0, $ancho, $alto);

        $imagen_rotada = imagerotate($lienzo, $grados, $fondo_blanco); // Fondo blanco para las áreas expuestas

        if ($imagen_rotada == false) {
            $res = [
                "estatus" => 400,
                "mensaje" => "Error al rotar la imagen"
            ];
            echo json_encode($res);
        } else {
            // Convertir la imagen rotada a un BLOB
            ob_start(); // Iniciar el buffer de salida
            imagepng($imagen_rotada); // Generar la imagen en formato PNG y almacenarla en el buffer
            $imagen_rotada_blob = ob_get_contents(); // Obtener el contenido del buffer como cadena (BLOB)
            ob_end_clean(); // Limpiar y cerrar el buffer
            // Obtener el tamaño del archivo BLOB rotado en bytes
            $tamano_blob = strlen($imagen_rotada_blob);

            // Convertir el tamaño a una unidad legible
            $tamano_legible = convertirBytes($tamano_blob);

            // echo $tamano_legible;

            $blob_img_db = 'data:image/png;base64,' . base64_encode($imagen_rotada_blob);

            try
            {
                $updateImgRot = $con->prepare("UPDATE tb_web_va_docs SET Archivo = ?, Tamanio_Archivo = ? WHERE ID_Documento = ?;");
                $updateImgRot->bind_param('bsi', $blob_img_db, $tamano_legible, $ID_Img_Rotar);
                $updateImgRot->send_long_data(0, $blob_img_db);
    
                if($updateImgRot->execute())
                {
                    $res = [
                        "estatus" => 200,
                        "mensaje" => "rotada",
                        "blob_img" => base64_encode($blob_img_db)
                    ];
                    echo json_encode($res);
                }
                else{
                    $res = [
                        "estatus" => 400,
                        "mensaje" => "error"
                    ];
                    echo json_encode($res);
                }
            }
            catch(Exception $ex)
            {
                $res = [
                    "estatus" => 400,
                    "mensaje" => "error ".$ex->getMessage()
                ];
                echo json_encode($res);
            }
        }
    }
}

function convertirBytes($bytes) {
    $unidad = ['B', 'KB', 'MB', 'GB', 'TB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $unidad[$factor];
}
