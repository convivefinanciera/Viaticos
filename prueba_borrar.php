<!-- PARA SUBIR LOS DOCUMENTOS DE BURO DE CREDITO Y CARTA AUTORIZACION A LA TABLA DOCS -->
<?php
    // $conn = null;
    // try {
    //     $conn = new PDO("mysql:host=192.168.1.92;dbname=microfin_pruebas", "root", "zafy2017");
    //     $conn->exec("set names utf8");
    // } catch (PDOException $exception) {
    //     echo "Connection error: " . $exception->getMessage();
    // }


    // $solicitud = isset($_GET['sol']) ? $_GET['sol'] : '';

    // # consultar le RFC de cliente por medio de la solicitud
    // $q_rfc = $conn->prepare("SELECT RFC, ID_Cliente FROM tb_web_va_solicitud WHERE ID_Solicitud = '$solicitud'");
    // if ($q_rfc->execute()) {
    //     $rows = $q_rfc->fetch(PDO::FETCH_NUM);
    //     $rfc_cliente = $rows[0];
    //     $id_cliente = $rows[1];

    //     #Traer el documento del buro y pasarlo a base64
    //     $documento = base64_encode(file_get_contents('https://convivetufinanciera.com.mx/api_buro/docBuroCredito/' . $rfc_cliente . '.pdf'));
    //     $tamanio = strlen($documento);

        #Subir el documento a la tabla docs con el tipodoc = 16
        /* $q_documento = $conn->prepare("INSERT INTO tb_web_va_docs(ID_Solicitud, ID_Cliente, ID_TipoDoc, Archivo, Nombre_Archivo, Tamanio_Archivo, Estatus) VALUES(
            '$solicitud', $id_cliente, 16, '$documento', '$rfc_cliente.pdf', $tamanio, 1)");
        if ($q_documento->execute()) {
            echo "Se subio la consulta a buro correctamente";
        } else {
            echo "No se pudo hacer el insert del documento consulta a buro";
        } */

        # --------------------------------------
        #Crear la carta
        /* $data = ["id_solicitud" => $solicitud];
        $url = $_SERVER["HTTP_HOST"] . '/VentAcero/administracion/ParametrosScoreCredito/AutorizacionBuro.php?';
        $url .= http_build_query($data);

        // Inicializar cURL
        $ch = curl_init($url);

        // Configurar cURL para enviar datos mediante POST
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/pdf"));

        // Opcional: recibir la respuesta del archivo destino
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la petición
        $response = curl_exec($ch);
        var_dump($response);

        // Verificar si hubo algún error
        if (curl_errno($ch)) {
            echo "fallo la url";
        }

        $info = curl_getinfo($ch);
        $url_response = $info['url'];
        echo $url_response;

        // Cerrar la sesión cURL
        curl_close($ch); */
    // } else {
    //     echo "No se pudo realizar la consulta";
    // }

?>
 
<!-- < ?php  
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $result = array('error' => false);
    if ($action == 'consultar') {
        $conn = null;
        try {
            $conn = new PDO("mysql:host=192.168.1.92;dbname=microfin_pruebas", "root", "zafy2017");
            $conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        // en base al ID de cliente se traen los datos para la consulta
        $id_cliente = "22698";
        
        $sql = "SELECT D.Estado, D.Calle, D.Colonia, D.Municipio, D.CP, S.RFC, S.ApellidoP, S.ApellidoM, S.ID_Solicitud, C.PrimerNombre, C.SegundoNombre, C.FechaNacimiento, C.ClienteID
                FROM tb_web_va_solicitud S, clientes C, tb_web_va_domicilios D
                WHERE C.ClienteID = S.ID_Cliente AND
                D.ID_Solicitud = S.ID_Solicitud AND
                D.ID_Cliente = C.ClienteID AND
                D.ID_Direccion = 1 AND
                C.ClienteID = '$id_cliente';";
        $consulta = $conn->query("$sql");
        // var_dump($consulta->rowCount());
        $datos = array();
        if ($consulta and $consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                array_push($datos, $fila);
            }

            $result['datos'] = $datos;
        } else {
            $result['error'] =  true;
            echo "Sin registros";
        }

    }

    echo json_encode($result);
    $conn = null;
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba</title>
</head>
<body>
    <section>
        <p>Datos del solicitante</p>
        <ul id="listado_datos"></ul>
        <button onclick="consultarCredito()">Consultar Crédito</button>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="http://192.168.1.109/api_buro/buroCredito.js"></script>
    <script>
        function consultarCredito (id) {
            fetch("prueba_borrar.php?action=consultar")
            .then(e => e.json())
            .then(datos => {
                let dato = datos.datos[0]
                let parametros = {
                    'estado': dato.Estado,
                    'rfc': dato.RFC,
                    'apellidoPaterno': dato.ApellidoP,
                    'apellidoMaterno': dato.ApellidoM,
                    'primerNombre': dato.PrimerNombre,
                    'segundoNombre': dato.SegundoNombre,
                    'fechaNacimiento': dato.FechaNacimiento,
                    'direccion': dato.Calle,
                    'coloniaPoblacion': dato.Colonia,
                    'delegacionMunicipio': dato.Municipio,
                    'codigoPostal': dato.CP,
                    'autenticador': 'V', /* Hardcodeo */
                    'tarjetaCredito': '', /* Hardcodeo */
                    'creditoHipotecario': '', /* Hardcodeo */
                    'creditoAutomotriz': '', /* Hardcodeo */
                    'ca_soluni_id': dato.ID_Solicitud,
                    'cb_clienteid': dato.ClienteID
                }

                buscarBuroCredito(parametros)
            })
        }
    </script>
</body>
</html> -->




<?php
    require("Controllers/conexion.php");
    $sql = "SELECT ID_Solicitud, Archivo FROM tb_web_va_docs WHERE ID_TipoDoc = '14'";
    $consulta = $con->query($sql);
    $datos = array();
    while ($fila = $consulta->fetch_assoc()) {
        array_push($datos, $fila);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagenes</title>
    <style>
        section {
            display: flex;
        }
    </style>
</head>
<body>
    <section>
        <article>
            <table>
                <thead>
                    <tr>
                        <td>ID Solicitud</td>
                        <td>Archivo</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($datos as $dato) {
                            echo "
                                <tr>
                                    <td>" . $dato["ID_Solicitud"] . "</td>
                                    <td><button onclick='verImagen(\"" . $dato['Archivo'] . "\")'>Ver imagen</button></td>
                                </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </article>
        <article>
            <img src="" alt="Selecciona para visualizar firma" style="border:1px solid #333" id="image_preview">
            <!-- <! -- <iframe iframe="image_preview" width="300" height="300"></iframe>  -->
        </article>
    </section>

    <script>
        const imagen =  document.querySelector("#image_preview");
        const verImagen = (sol) => {
            imagen.src = sol
        }
    </script>
</body>
</html> 