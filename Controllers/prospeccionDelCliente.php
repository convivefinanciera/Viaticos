<?php
include_once 'conexion.php';
#Configuracion zona horaria
setlocale(LC_ALL, 'es_MX');
date_default_timezone_set("America/Mexico_City");
$Fecha = DATE("Y-m-d H:i:s");

session_start();

$bandera = isset($_POST['bandera']) ? $_POST['bandera'] : $_GET['bandera'];

if ($bandera == 'GuardarInfo_ProspeccionCliente') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $ID_Cliente = $_SESSION['ID_Cliente'];
    // Recibir datos del formulario
    $localPropio = $_POST['LocalPropio'];
    $facturaElectronica = $_POST['FacturaElectronica'];
    $filialEmpresa = $_POST['FilialEmpresa'];
    $propietario = $_POST['PropietarioReal'];
    $visitaCliente = $_POST['VisitaCliente'];

    $nombreEmpresaFilial = $_POST['inputNombreEmpresaFilial'];
    $tiempoEstablecimiento = $_POST['inputTiempoEstablecimiento'];
    $telefonoNegocio = $_POST['inputTelefonoNegocio'];
    $contactoCompras = $_POST['inputNombreContactoCompras'];
    $telefonoContacto = $_POST['inputTelefonoContactoCompras'];
    $extensionContacto = $_POST['inputExtensionContactoCompras'];
    $correoContacto = $_POST['inputCorreoCompras'];

    $contactoPagos = $_POST['inputNombreContactoPagos'];
    $nombreContactoPagos = $_POST['inputNombreContactoPagos'];
    $telefonoContactoPagos = $_POST['inputTelefonoContactoPagos'];
    $extensionContactoPagos = $_POST['inputExtensionContactoPagos'];
    $correoContactoPagos = $_POST['inputCorreoContactoPagos'];

    // $giro = $_POST['inputGiro'];
    $nivel = $_POST['inputNivel'];
    $quienVisito = $_POST['inputNombreVisitor'];
    $zona = $_POST['inputZona'];
    $listaPrecios = $_POST['inputListaPrecios'];
    $productosConsume = $_POST['inputProductosConsume'];
    $productosVenderArray = json_decode($_POST['inputProductosVender']);
    $productosVender = "";
    if(sizeof($productosVenderArray) > 0)
    {
        for($p = 0; $p < sizeof($productosVenderArray); $p++)
        {
            $productosVender .= $productosVenderArray[$p]."|";
        }
    }
    $proyeccionVentas = $_POST['inputProyeccionVentas'];


    $otrosProveedores = $_POST['inputOtrosProveedores'];
    $consumoAprox = $_POST['inputConsumoAprox'];
    $proyectoEspecial = $_POST['inputProyectoEspecial'];


    if ($ID_Solicitud_Sesion != '') {
        $solicitud = "SELECT * FROM tb_web_va_solicitud WHERE ID_Solicitud = '$ID_Solicitud_Sesion';";
        $result = $con->query($solicitud);
        $num = $result->num_rows;
        if ($num > 0) {
            $query = $con->query("UPDATE tb_web_va_solicitud SET Estatus = 3 WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");
            if ($query) {
                $res = [
                    'estatus' => 200,
                    'mensaje' => 'Se han guardado los cambios.'
                ];
            } else {
                $res = [
                    'estatus' => 400,
                    'mensaje' => 'No se han podido guardar los cambios'
                ];
            }
        }
    }
    $existeProspeccion = $con->query("SELECT * FROM tb_web_va_prospeccioncliente WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");
    if ($existeProspeccion->num_rows > 0) {
        $guardarProspeccion = "UPDATE tb_web_va_prospeccioncliente SET
            LocalPropio = '$localPropio',  
            EsFilial = '$filialEmpresa', 
            Propietario = '$propietario',
            DescripFilial = '$nombreEmpresaFilial', 
            TiempoNegocio = '$tiempoEstablecimiento', 
            TelefonoNeg = '$telefonoNegocio', 
            ContactoCompras = '$contactoCompras',
            TelefonoCompras = '$telefonoContacto', 
            ExtCompras = '$extensionContacto', 
            CorreoCompras = '$correoContacto', 
            ContactoPagos = '$nombreContactoPagos', 
            TelefonoPagos = '$telefonoContactoPagos', 
            ExtPagos = '$extensionContactoPagos', 
            CorreoPagos = '$correoContactoPagos', 
            AceptaFE = '$facturaElectronica',
            VisitoCliente = '$visitaCliente', 
            Nivel = '$nivel', 
            QuienVisito = '$quienVisito', 
            Zona = '$zona', 
            ListaPrecios = '$listaPrecios', 
            ProductosConsume = '$productosConsume', 
            ProductoAVender = '$productosVender', 
            ProyeccionVenta = '$proyeccionVentas',
            OtrosProveedores = '$otrosProveedores', 
            ConsumoAprox = '$consumoAprox', 
            ProyEspecialOFrec = '$proyectoEspecial', 
            FechaAlta = '$Fecha'
            WHERE ID_Solicitud = '$ID_Solicitud_Sesion';";
    } else {
        $guardarProspeccion = "INSERT INTO tb_web_va_prospeccioncliente (
            ID_Solicitud,
            ID_Cliente,
            LocalPropio,  
            EsFilial, 
            Propietario,
            DescripFilial, 
            TiempoNegocio, 
            TelefonoNeg, 
            ContactoCompras,
            TelefonoCompras, 
            ExtCompras, 
            CorreoCompras, 
            ContactoPagos, 
            TelefonoPagos, 
            ExtPagos, 
            CorreoPagos, 
            AceptaFE,
            VisitoCliente, 
            Nivel, 
            QuienVisito, 
            Zona, 
            ListaPrecios, 
            ProductosConsume, 
            ProductoAVender, 
            ProyeccionVenta,
            OtrosProveedores, 
            ConsumoAprox, 
            ProyEspecialOFrec, 
            FechaAlta
        ) VALUES (
            '$ID_Solicitud_Sesion',
            $ID_Cliente,
            '$localPropio', 
            '$filialEmpresa', 
            '$propietario',
            '$nombreEmpresaFilial',
            '$tiempoEstablecimiento', 
            '$telefonoNegocio', 
            '$contactoCompras', 
            '$telefonoContacto', 
            '$extensionContacto', 
            '$correoContacto', 
            '$nombreContactoPagos', 
            '$telefonoContactoPagos', 
            '$extensionContactoPagos', 
            '$correoContactoPagos',
            '$facturaElectronica',
            '$visitaCliente',
            '$nivel', 
            '$quienVisito', 
            '$zona', 
            '$listaPrecios', 
            '$productosConsume', 
            '$productosVender', 
            '$proyeccionVentas', 
            '$otrosProveedores', 
            '$consumoAprox', 
            '$proyectoEspecial',
            '$Fecha'
        )";
    }


    if ($con->query($guardarProspeccion)) {
        $res = [
            'estatus' => 200,
            'mensaje' => 'Se ha guardado la información.'
        ];
        echo json_encode($res);
        return false;
    } else {
        $res = [
            'estatus' => 400,
            'mensaje' => 'Ocurrió un error al guardar la información.'
        ];
        echo json_encode($res);
        return false;
    }
}
if ($bandera == 'LlamarAPI_SMS') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];

    if (isset($_POST['opc']) and !empty($_POST['opc'])) {
        $query1 = $con->query("UPDATE tb_web_va_docs SET Estatus = 0 WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_TipoDoc = 14 AND Estatus = 1");
    }

    //Obtener el teléfono desde la base de datos
    $selectTelefono = "SELECT Celular FROM tb_web_va_datasignauth WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Persona = 1;";
    $query = $con->query($selectTelefono);

    if ($query->num_rows > 0) {
        $telefonoRL = $query->fetch_assoc()['Celular'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sms.contacta.mx:51943/api/v2/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        #Enviamos el primer mensaje.
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            "auth" => "dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09",
            "phone" => $telefonoRL,
            "msg" => "Convive Financiera requiere su autorización para consultar su historial crediticio y continuar con el trámite de solicitud de crédito $ID_Solicitud_Sesion",
        )));

        $response = curl_exec($curl);

        #Enviamos el segundo mensaje.
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            "auth" => "dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09",
            "phone" => $telefonoRL,
            "msg" => "Ingrese a la siguiente liga para registrar su firma: https://creditoventacero.mx/VentAcero/firmaserv.php?sol=$ID_Solicitud_Sesion",
        )));

        $response2 = curl_exec($curl);

        curl_close($curl);

        echo json_encode(["estatus" => 200, 'mensaje' => $telefonoRL,'response' => $response === false ? false : true, 'response2' => $response2 === false ? false : true, 'responseJSON' => $response, 'response2JSON' => $response2]);
        // echo json_encode(["sql" => $selectTelefono, 'response' => true, 'response2' => true]);
        exit();
        // // }
    } else {
        echo json_encode(['estatus' => 400, 'message' => 'Error en enviar SMS']);
        exit();
    }
}
if ($bandera == 'CargarAvances_ProspeccionCliente') {
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    $selectProspeccion = $con->query("SELECT * FROM tb_web_va_prospeccioncliente WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");

    $prospeccionClientes = array();
    if($selectProspeccion->num_rows > 0)
    {
        while ($row = $selectProspeccion->fetch_assoc()) 
        {
            $prospeccionClientes[] = $row;
        }
    }
    else
    {
        $prospeccionClientes = [
            'estatus' => 200,
            'count' => 0
        ];
    }

    echo json_encode($prospeccionClientes);
}
if ($bandera == 'Autorizacion_BuroCredito')
{
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    // $selectFirmaBuro = $con->query("SELECT * FROM tb_web_va_buro WHERE ID_Solicitud = '$ID_Solicitud_Sesion';");
    $selectFirmaBuro = $con->query("SELECT * FROM tb_web_va_docs WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_TipoDoc = 14 AND Estatus = 1;");

    if($selectFirmaBuro->num_rows > 0)
    {
        $res = [
            "estatus" => 200,
            "firmado" => true
        ];
    }
    else
    {
        $res = [
            "estatus" => 200,
            "firmado" => false
        ];
    }

    echo json_encode($res);
}
if ($bandera == 'Cargar_Niveles')
{
    $selectNiveles = $con->query("SELECT * FROM tb_web_va_catniveles;");
    $niveles = array();
    while($row = $selectNiveles->fetch_assoc())
    {
        $niveles [] = $row;
    }

    echo json_encode($niveles);
}
if ($bandera == 'Cargar_Zonas')
{
    $selectZonas = $con->query("SELECT * FROM tb_web_va_catzonas;");
    $zonas = array();
    while($row = $selectZonas->fetch_assoc())
    {
        $zonas [] = $row;
    }

    echo json_encode($zonas);
}
if ($bandera == 'Cargar_ListaPrecios')
{
    $selectListaPrecios = $con->query("SELECT * FROM tb_web_va_catprecios;");
    $listaPrecios = array();
    while($row = $selectListaPrecios->fetch_assoc())
    {
        $listaPrecios [] = $row;
    }

    echo json_encode($listaPrecios);
}
if ($bandera == 'Cargar_Productos')
{
    $selectProductos = $con->query("SELECT * FROM tb_web_va_catproductos;");
    $productos = array();
    while($row = $selectProductos->fetch_assoc())
    {
        $productos [] = $row;
    }

    echo json_encode($productos);
}
if ($bandera == 'Cargar_Sectores')
{
    $selectSectores = $con->query("SELECT * FROM tb_web_va_catsectores;");
    $sectores = array();
    while($row = $selectSectores->fetch_assoc())
    {
        $sectores [] = $row;
    }

    echo json_encode($sectores);
}
/* if ($bandera == 'LlamarAPI_SMS') { //Función original
    $ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
    
    //$telefonoRL = $_POST['Telefono_RL'];
    //$nombreRL = $_POST['Nombre_RL'];
    
    //Obtener el teléfono desde la base de datos
    // $selectTelefono = "SELECT * FROM tb_web_va_datasignauth WHERE ID_Solicitud = '$ID_Solicitud_Sesion' AND ID_Persona = 1;";
    $selectTelefono = "SELECT Celular FROM tb_web_va_datasignauth WHERE ID_Solicitud = 'SOL-1234-AAA' AND ID_Persona = 1;";
    $query = $con->query($selectTelefono);

    echo $query;

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $telefonoRL = $row['Celular'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                //CURLOPT_URL => "https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=" . $telefonoRL . "&msg=Convive%20Financiera:%20Para%20continuar%20con%20el%20tr%C3%A1mite%20de%20tu%20solicitud%20de%20cr%C3%A9dito,%20requerimos%20autorizaci%C3%B3n%20para%20consultar%20su%20historial%20crediticio,%20por%20favor%20ingrese%20a%20la%20siguiente%20liga%20para%20autorizar%20la%20consulta:%20" . "https://convivetufinanciera.com.mx/Firmaserv_VentAcero/index.php?ID_Solicitud=" . $_SESSION['ID_Solicitud'],
                // CURLOPT_URL => "https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09",
                CURLOPT_URL => "https://convivetufinanciera.com.mx/firmaserv/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => '{
                            "phone": "' . $telefonoRL . '",
                            "msg": "Convive Financiera: Para continuar con el tr%C3%A1mite de tu solicitus de crédito, requerimos autorización para consultar su historial crediticio"
                        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09'
                ),
            ));

            $response = curl_exec($curl);
        
            curl_close($curl);
            
            echo json_encode(['status' => 'success', 'message' => 'SMS enviado exitosamente']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en enviar SMS']);
        exit();
    }
} */
