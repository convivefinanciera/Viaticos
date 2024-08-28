<?php
    include_once "connDB.php";
    include_once "../model/tarjetaDebito.php";
    
    //conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();
    $databaseLoc = new DatabaseLoc();
    $dbLoc = $databaseLoc->getConnection();
    
    switch($_POST["proceso"])
    {
        //en este case se retorna todas las tarjetas asociadas al cliente
        case "buscarDatos":
            try {
                include_once "../model/clientes.php";                
                
                $credenciales = obtenerCredenciales($db);
                
                //se almacenan las varibles recividas
                $clienteID = $_POST["clienteID"];
                $tarjetaID = $_POST["tarjetaID"];
                $usuario = $credenciales['usuario'];
                $contrasena = $credenciales["contrasena"];
                
                //creación de los objetos necesarios                
                $cliente = new Clientes($dbLoc);
                $tarjeta = new TarjetaDebito($dbLoc);
                $arrayPeticion = array();
                $objResult = new stdClass();
                
                //validación de si no me dieron el numero de cliente significa que ingresaron el numero de tarjeta así que se busca el cliente por la tarjeta
                if($clienteID == "" || $clienteID == null) {
                    $tarjeta->TarjetaDebID = $tarjetaID;
                    $consulta = $tarjeta->getClientexTarjeta();
                    $clienteID = $consulta["ClienteID"] > 0 ? $consulta["ClienteID"] : die("noTiene");                    
                }
                else if($clienteID == 0) {
                    die("noValido");
                }
                
                //se hace las consultas
                $cliente->ClienteID = $clienteID;
                $nombreC = $cliente->getNombreCompleto();
                $tarjeta->ClienteID = $clienteID;
                $numTarjeta = $tarjeta->buscarTarjetaxCliente();
                $tarjetasCliente = $tarjeta->getTarjetasEstatus();
                
                //objeto que tendrá los datos a retornar
                $objResult->nombreCliente = $nombreC["NombreCompleto"];
                $objResult->numeroCliente = $clienteID;                
                $objResult->numTarjeta = substr($numTarjeta["TarjetaDebID"], 0, 4) . "-" . substr($numTarjeta["TarjetaDebID"], 4, 4) . "-" . substr($numTarjeta["TarjetaDebID"], 8, 4) . "-" . substr($numTarjeta["TarjetaDebID"], 12, 4);
                $objResult->arrayTarjetas = $tarjetasCliente;
                $objResult->arrayEstadosWS = array();
                
                //se recorre todas las tarjetas del cliente
                foreach($tarjetasCliente as $tc) {
                    //se llama a la función que busca el estatus de una tarjeta
                    $curl_response = getStatusClassAndStatusCards($tc["TarjetaDebID"], $usuario, $contrasena);
                    
                    if ($curl_response == false) { //si tuvo error entonces se guarda como mensaje en el objeto
                        array_push($objResult->arrayEstadosWS, "Fallo");
                    }
                    else {
                        //si no tuvo problemas se convierte string xml a un objeto xml y luego se hace json para después decodificarlo a una variable
                        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $curl_response);
                        $xml = new SimpleXMLElement($response);
                        $body = $xml->xpath('//soapBody')[0];
                        $json = json_encode($body);
                        $verificar = json_decode($json);
                        
                        $arrayCodigos = array( //array que contiene los posibles mensajes de errores enviados por el web service
                            -1 => "Error",
                            -2 => "Los parametros enviados son invalidos",
                            -5 => "Información de la tarjeta no encontrada"
                        );                        
                        if(array_key_exists($verificar->ns2getStatusClassAndStatusCardsResponse->return->RETURN_CODE, $arrayCodigos)) {
                            //si hay error se añade a un arreglo con el mensaje
                            array_push($objResult->arrayEstadosWS, $arrayCodigos[$verificar->ns2getStatusClassAndStatusCardsResponse->return->RETURN_CODE]);
                        }
                        else {
                            //si salio todo bien, se añade al array el mensaje recivido por el web service
                            array_push($objResult->arrayEstadosWS, $verificar->ns2getStatusClassAndStatusCardsResponse->return->STATUS_DESCRIPTION);
                        }
                    }
                }
                
                echo json_encode($objResult);
            } catch (Exception $ex) {
                echo "Fallo";
            }
        break;
        
        //en este case se actualiza el estatus de las tarjetas seleccionadas
        case "actualizarDatos":
            try {
                include_once "../model/clientes.php";
                include_once "../model/cuentasaho.php";
                include_once "../model/bitacoraTarDeb.php";
                include_once "../model/tb_control_tdc.php";
                
                $credenciales = obtenerCredenciales($db);
                
                //se alamacenan las variables recividas
                $arrayDatos = $_POST["arrayDatos"];
                $usuario = $credenciales['usuario'];
                $contrasena = $credenciales["contrasena"];
                $estatus = $_POST["estatus"];
                $numeroUsuario = $_POST["numeroUsuario"];
                //variables para la cancelación de las tarjetas seleccionadas
                $descripcion = $_POST["descripcion"];
                $valorEleccion = $_POST["valor"];
                
                //creación de los objetos necesarios
                $tarjeta = new TarjetaDebito($dbLoc);
                $cliente = new Clientes($dbLoc);
                $cuentaaho = new Cuentasaho($dbLoc);
                $bitacora = new BitacoraTarDeb($dbLoc);
                $controlTDC = new TB_Control_TDC($dbLoc);
                $arrayErrores = array();
                
                //consultas para extraer datos
                $tarjeta->TarjetaDebID = $arrayDatos[0]["tarjeta"];
                $clienteCredito = $tarjeta->getClienteCredito();
                //$observaciones = $clienteCredito["ClienteID"] . " " . $clienteCredito["MAX(c.CreditoID)"];                
                $cliente->ClienteID = $clienteCredito["ClienteID"];
                $nombreC = $cliente->getNombreCompleto();                                
                $cuentaaho->ClienteID = $clienteCredito["ClienteID"];
                $cuentaahoid = $cuentaaho->getCuentaAhoID();
                
                //se recorre los datos recividos
                foreach($arrayDatos as $dato) {
                    //creación del objeto para los posibles errores
                    $objError = new stdClass();
                    $objError->tarjeta = $dato["tarjeta"];
                    $objError->estatus = $estatus;
                    $objError->mensajePayware = "";
                    $objError->mensajeSafi = "";
                    
                    if($dato["payware"] == "true") { //valida si se quiere cancelar o activar en payware
                        if(($dato["estatusPayware"] == "TARJETA CANCELADA" && $estatus == 27) || ($dato["estatusPayware"] == "")  || 
                            ($dato["estatusSafi"] == "TARJETA BLOQUEADA POR ZAFY" && $estatus == 25) || ($dato["estatusSafi"] == "TARJETA CANCELADA POR INACTIVIDAD" && $estatus == 28)) {
                            //si tiene el mismo estatus se guarda mensaje del error
                            $objError->mensajePayware = "Mismo estatus de la tarjeta";
                            array_push($arrayErrores, $objError);                            
                        }
                        else {
                            //se llama a la función que hace el web service de cambiar el estatus
                            $curl_response = changeCardStatus($dato["tarjeta"], $estatus, $descripcion, $usuario, $contrasena);
                            
                            if($curl_response == false) { //si fallo se almacena el error
                                $objError->mensajePayware = "Fallo la comunicación con PROSA";
                                array_push($arrayErrores, $objError);
                            }
                            else {
                                $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $curl_response);
                                if(strpos($response, "-35")) { //se verifica que el string xml tenga un -35 de ser true se guarda como error
                                    $objError->mensajePayware = "La tarjeta no puede ser activada";
                                    array_push($arrayErrores, $objError);
                                }
                                else {
                                    //se hace un objeto xml del string y posteriormente se hace json y se decodifica a una variable
                                    $xml = new SimpleXMLElement($response);
                                    $body = $xml->xpath('//soapBody')[0];
                                    $json = json_encode($body);
                                    $verificar = json_decode($json);
                                    
                                    //lista de los posibles errores que puede retornar el web service
                                    $arrayCodigos = array(
                                        -1 => "Error",
                                        -2 => "Los parámetros enviados son invalidos",
                                        -5 => "Información de la tarjeta no encontrada",
                                        -29 => "No se puede bloquear la tarjeta",
                                        -34 => "Mismo estatus de la tarjeta",
                                        //-35 => "Activación no permitida",
                                        -36 => "Error en el mensaje de activación"
                                    );
                                    //si se encontro algún error se guarda el objeto en el array
                                    if(array_key_exists($verificar->ns2changeCardStatusResponse->return->RETURN_CODE, $arrayCodigos)) {
                                        $objError->mensajePayware = $arrayCodigos[$verificar->ns2changeCardStatusResponse->return->RETURN_CODE];
                                        array_push($arrayErrores, $objError);
                                    }                                    
                                }
                            }
                        }
                    }
                    
                    if($dato["safi"] == "true") { //valida se se quiere cancelar o activar en el safi
                        if(($dato["estatusSafi"] == "TARJETA CANCELADA" && $estatus == 27) || ($dato["estatusSafi"] == "TARJETA BLOQUEADA POR ZAFY" && $estatus == 25) || 
                            ($dato["estatusSafi"] == "TARJETA CANCELADA POR INACTIVIDAD" && $estatus == 28)) {
                            //si tiene el mismo estatus que la tarjeta se guarda el error
                            $objError->mensajeSafi = "Mismo estatus de la tarjeta";
                            array_push($arrayErrores, $objError);
                        }
                        else {
                            date_default_timezone_set("America/Monterrey");
                            if($estatus == 27 || $estatus == 25 || $estatus == 28) { //se valida si es 27 cancelar o 25 bloqueada por zafy o 28 cancelada por inactividad
                                $mensaje = guardarDatosCancelado($tarjeta, $dato["tarjeta"], $nombreC["NombreCompleto"], $bitacora, $controlTDC, $numeroUsuario, $descripcion, $valorEleccion);
                            }
                            else { //si no fue ninguna anterior entonces es 6 y se debe limpiar la tarjeta
                                $mensaje = limpiarTarjeta($tarjeta, $dato["tarjeta"], $bitacora);
                            }
                            if($mensaje != "ok") { //si la variable es diferente de "ok" entonces se guarda el error
                                $objError->mensajeSafi = $mensaje;
                                array_push($arrayErrores, $objError);
                            }
                        }
                    }
                }
                
                if(count($arrayErrores) == 0) { //si tiene 0 significa que no hubo errores en el proceso y se manda el "ok"
                    echo "ok";
                }
                else { //si hay errores se manda codificado a json el arreglo
                    echo json_encode($arrayErrores);
                }
            } catch (Exception $ex) {
                die("Error en el proceso de activación y cancelación de las tarjetas.");
            }
        break;
        
        //case para actualizar el estado de una tarjeta dado un folio
        case "actualizarTarjeta":

            $url = "https://www.convivetufinanciera.com.mx/api_tarjetas/actualizarTarjeta.php";

            // Configura los datos para enviar al intermediario
            $postData = [
                'estatus' => $_POST["estatus"] ?? null,
                'clienteID' => $_POST["idCliente"] ?? null,
                'dato' => $_POST["dato"] ?? null,
                'descripcion' => $_POST["descripcion"] ?? null,
                'valor' => $_POST["valor"] ?? null,
                'tipo' => $_POST["tipo"] ?? null,
                'rev' => $_POST["rev"] ?? null
            ];

            // Inicia cURL
            $ch = curl_init();

            // Configura cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactiva la verificación del SSL para localhost
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            // Ejecuta la solicitud
            $response = curl_exec($ch);

            // Maneja errores
            if(curl_errno($ch)) {
                $error_msg = curl_error($ch);
                $response = json_encode([
                    'code' => '500',
                    'status' => 'error',
                    'message' => $error_msg
                ]);
            }

            // Cierra cURL
            curl_close($ch);

            // Muestra la respuesta
            echo $response;
            
            // echo json_last_error();
            // return $response;
            // try {
            //     include_once "../model/clientes.php";
            //     include_once "../model/cuentasaho.php";
            //     include_once "../model/bitacoraTarDeb.php";
            //     include_once "../model/tb_control_tdc.php";
                
            //     $credenciales = obtenerCredenciales($db);
                
            //     //se guardan las variables recividas
            //     $estatus = $_POST["estatus"];
            //     $idCliente = $_POST["idCliente"];
            //     $dato = $_POST["dato"];
            //     $usuario = $credenciales['usuario'];
            //     $contrasena = $credenciales["contrasena"];
            //     $numeroUsuario = $_POST["numeroUsuario"];
            //     //variables con la descipcion ingresada por el usuairo para la cancelacón
            //     $descripcion = $_POST["descripcion"];
            //     $valorEleccion = $_POST["valor"];
                
            //     //creación de los objetos
            //     $tarjeta = new TarjetaDebito($dbLoc);
            //     $cliente = new Clientes($dbLoc);
            //     $cuentaaho = new Cuentasaho($dbLoc);
            //     $bitacora = new BitacoraTarDeb($dbLoc);
            //     $control = new TB_Control_TDC($dbLoc);                                
                
            //     //se busca el numero de folio por el valor
            //     $control->CB_TDC = $dato;
            //     $folio = $control->buscarFolioxTarjeta()["CB_FolioMyCard"];
            //     if($folio != null) { //si hay datos entonces se toma el folio encontrado
            //         $numFolio = $folio;
            //     } else {
            //         $numFolio = $dato;
            //     }
                
            //     //validaciones de los datos
            //     $control->CB_FolioMyCard = $numFolio;
            //     $numTarjeta = $control->buscarTarjetaxFolio();
            //     if($numTarjeta["CB_TDC"] == "" || $numTarjeta["CB_TDC"] == null) {
            //         die("El folio no tiene una tarjeta asignada");
            //     }
                
            //     if ($idCliente == 0) {
            //         //sino hay numero de cliente, se busca los datos mediante la tarjeta
            //         $tarjeta->TarjetaDebID = $numTarjeta["CB_TDC"];
            //         $datosTar = $tarjeta->search();
            //         $idCliente = $datosTar['ClienteID'];
            //     }
                
            //     $control->CB_TDC = $numTarjeta["CB_TDC"];
            //     $estatusTarjeta = $control->buscarEstatus();
            //     if($estatus == "10" && $estatusTarjeta["CB_Estatus"] == "Entregada") {
            //         die("La tarjeta ya fue asginada a otro cliente.");
            //     }
            //     $cliente->ClienteID = $idCliente;
            //     $nombreC = $cliente->getNombreCompleto();
            //     if($nombreC["NombreCompleto"] == "" || $nombreC["NombreCompleto"] == null) {
            //         die("No existe el cliente");
            //     }
            //     $cuentaaho->ClienteID = $idCliente;
            //     $numCuentaAho = $cuentaaho->getCuentaAhoID();
            //     if($numCuentaAho["CuentaAhoID"] == "" || $numCuentaAho["CuentaAhoID"] == null) {
            //         die("El cliente no tiene una cuenta asignada");
            //     }
            //     $observaciones = $idCliente . " " . $numCuentaAho["CuentaAhoID"];
                
            //     if($estatus != "12" && $estatus != "6") {
            //         $estatus = $estatus == "11" ? "10" : $estatus;
            //         if($estatus == "27") { //si se cancelará la tarjeta, se cambia las observaciones por la descripcion dada por el usuario
            //             $observaciones = $descripcion;
            //         }
            //         //se llama la función para generar el web service
            //         $curl_response = changeCardStatus($numTarjeta["CB_TDC"], $estatus, $observaciones, $usuario, $contrasena);
                                
            //         if($curl_response == false) { //valida si la conexión fue exitosa
            //             die("Fallo en la conexión de red");
            //         }
            //         $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $curl_response);
            //         if(strpos($response, "-35")) { //valida si la tarjeta pudo ser activada
            //             die("La tarjeta no puede ser activada");
            //         }
            //         //se hace json el resultado y se almacena en variable
            //         $xml = new SimpleXMLElement($response);
            //         $body = $xml->xpath('//soapBody')[0];
            //         $json = json_encode($body);	
            //         $verificar = json_decode($json);
            //         $arrayCodigos = array(
            //             -1 => "Exception error",
            //             -2 => "Los parametros enviados son invalidos",
            //             -5 => "Información de la tarjeta no encontrada",
            //             -29 => "No se puede bloquear la tarjeta",
            //             -34 => "Mismo estatus de la tarjeta",
            //             //-35 => "Activación no permitida",
            //             -36 => "Error en el mensaje de activación"
            //         );
            //         if (array_key_exists($verificar->ns2changeCardStatusResponse->return->RETURN_CODE, $arrayCodigos)) { //valida que no tuviera los errores del array
            //             die($arrayCodigos[$verificar->ns2changeCardStatusResponse->return->RETURN_CODE]);
            //         } else {
            //             $mensaje = 'ok';
            //         }
            //     }
                
            //     if($estatus != "11") {
            //         $estatus = $estatus == "12" ? "10" : $estatus;
            //         date_default_timezone_set("America/Monterrey");                    
            //         if($estatus == "27") { //se manda a guardar los datos en el caso de cancelar la tarjeta
            //             $mensaje = guardarDatosCancelado($tarjeta, $numTarjeta["CB_TDC"], $nombreC["NombreCompleto"], $bitacora, $control, $numeroUsuario, $descripcion, $valorEleccion);                        
            //         }
            //         else if($estatus == "6") {
            //             $mensaje = limpiarTarjeta($tarjeta, $numTarjeta["CB_TDC"], $bitacora, $control);                        
            //         }
            //         else { //se alamacena los datos en caso de activar y asignar la tarjeta
            //             $mensaje = guardarDatosActivar($tarjeta, $idCliente, $numCuentaAho["CuentaAhoID"], $nombreC["NombreCompleto"], $numTarjeta["CB_TDC"], $bitacora, $control, $numeroUsuario);
            //         }                                        
            //     }
                
            //     echo $mensaje;
            // } catch (Exception $ex) {
            //     die("Error en el proceso de activar o cancelar la tarjeta.");
            // }


        break;

        //case para actualizar el estado de una tarjeta dado un folio
        case "reemplazarTarjeta":

            $url = "https://www.convivetufinanciera.com.mx/api_tarjetas/reemplazarTarjeta.php";

            // Configura los datos para enviar al intermediario
            $postData = [
                'tarjetaA' => $_POST["tarjetaA"] ?? null,
                'tarjetaN' => $_POST["tarjetaN"] ?? null
            ];

            // Inicia cURL
            $ch = curl_init();

            // Configura cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactiva la verificación del SSL para localhost
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            // Ejecuta la solicitud
            $response = curl_exec($ch);

            // Maneja errores
            if(curl_errno($ch)) {
                $error_msg = curl_error($ch);
                $response = json_encode([
                    'code' => '500',
                    'status' => 'error',
                    'message' => $error_msg
                ]);
            }

            // Cierra cURL
            curl_close($ch);

            // Muestra la respuesta
            echo $response;

        break;
        
        //case para debolver el nombre completo del cliente
        case "datosCliente":
            try {
                include_once "../model/clientes.php";
                include_once "../model/tb_control_tdc.php";
                
                //obtencón de los datos recividos
                $numCliente = $_POST["clienteID"];
                $valor = $_POST["valor"];
                $accion = $_POST['accion'];
                
                //se hace la creación del objeto
                $cliente = new Clientes($dbLoc);
                $controlTDC = new TB_Control_TDC($dbLoc);
                $objResult = new stdClass();
                $tarjeta = new TarjetaDebito($dbLoc);
                                            
                //se busca el numero de folio por el valor
                if(strlen($valor) == 7 || strlen($valor) == 13){
                    $numFolio = $valor;
                }
                elseif(strlen($valor) == 16){
                    $controlTDC->CB_TDC = $valor;
                    $folio = $controlTDC->buscarFolioxTarjeta();
                    if($folio["CB_FolioMyCard"] != null) { //si hay datos entonces se toma el folio encontrado
                        $numFolio = $folio["CB_FolioMyCard"];
                    } else {
                        $numFolio = $valor;
                    }
                }   

                //consulta de la tarjeta y folio
                $controlTDC->CB_FolioMyCard = $numFolio;
                $numeroTarjeta = $controlTDC->buscarTarjetaxFolio();
                $producto = $controlTDC->getProducto();
                $controlTDC->CB_TDC = $numeroTarjeta["CB_TDC"];
                $estadoTarjeta = $controlTDC->buscarEstatus();                

                if ($numCliente == 0) {
                    //sino hay numero de cliente, se busca los datos mediante la tarjeta
                    $tarjeta->TarjetaDebID = $numeroTarjeta["CB_TDC"];
                    $datosTar = $tarjeta->search();
                    $numCliente = $datosTar['ClienteID'];
                }

                //consulta del nombre del cliente
                $cliente->ClienteID = $numCliente;
                $nombreC = $cliente->getNombreCompleto();                                

                //resultado
                if ($accion == 'asignar' && $estadoTarjeta["CB_Estatus"] != 'Disponible') {
                    $objResult->mensaje = 'No se puede asignar la tarjeta porque no esta disponible';
                } else if ($nombreC["NombreCompleto"] == null) {
                    $objResult->mensaje = 'Esta tarjeta no esta asignada a un usuario';
                } else {
                    $objResult->mensaje = 'ok';
                    $objResult->nombreCompleto = $nombreC["NombreCompleto"];
                    $objResult->numeroCliente = $numCliente;
                    $objResult->numeroTarjeta = substr($numeroTarjeta["CB_TDC"], 0, 4) . "-" . substr($numeroTarjeta["CB_TDC"], 4, 4) 
                                                . "-" . substr($numeroTarjeta["CB_TDC"], 8, 4) . "-" . substr($numeroTarjeta["CB_TDC"], 12, 4); 
                    $objResult->producto = $producto["CB_ProductoID"];
                    $objResult->estadoTarjeta = $estadoTarjeta["CB_Estatus"];
                }
                                
                echo json_encode($objResult);
            } catch (Exception $ex) {
                die("Error");
            }
        break;
        
        //case para buscar los clientes de un producto
        case 'searchClixProd':
            include_once "../model/solicitudUnica.php";
            
            $solicitud = new SolicitudUnica($dbLoc);
            
            $solicitud->CB_ProductoID = isset($_POST['prod']) ? $_POST['prod'] : die();
            $solicitud->Valor = isset($_POST['nom']) ? str_replace("|", "%", $_POST['nom']) : die();
            $stmt = $solicitud->searchClixProd();
            # Crea el arreglo para los registros
            $relacion_arr = array();
            $relacion_arr["registros"] = array();
            # Obtiene los registros
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $relacion_item = array(
                    "ClienteID" => $ClienteID,
                    "Nombre" => $Nombre,
                    "TelefonoCelular" => $TelefonoCelular
                );
                array_push($relacion_arr["registros"], $relacion_item);
            }
            echo json_encode($relacion_arr);
        break;

        //case para buscar las tarjetas de un cliente
        case 'searchTarjxCli':
            include_once "../model/solicitudUnica.php";
            
            $solicitud = new SolicitudUnica($dbLoc);
            
            $solicitud->Valor = isset($_POST['nom']) ? str_replace("|", "%", $_POST['nom']) : die();
            $stmt = $solicitud->searchTarjxCli();
            # Crea el arreglo para los registros
            $relacion_arr = array();
            $relacion_arr["registros"] = array();
            # Obtiene los registros
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $relacion_item = array(
                    "Nombre" => $row["NombreCompleto"],
                    "Tarjeta" => $row["TarjetaDebID"]
                );
                array_push($relacion_arr["registros"], $relacion_item);
            }
            echo json_encode($relacion_arr);
        break;
        
        default:
            die("falloEleccion");
        break;
    }

    
    
    /*
     * Función para buscar las credenciales de Payware
     */
    function obtenerCredenciales($db) {
        include_once "../model/configuracionGral.php";
        
        //se crean los objetos necesarios
        $configuracion = new ConfiguracionGral($db);

        //se hacen las consultas
        $configuracion->CB_Clave = "UWEBSB";
        $usuarioWSB = $configuracion->getCredenciales();
        $configuracion->CB_Clave = "PWEBSB";
        $passwordWSB = $configuracion->getCredenciales();
        
        return array(
            'usuario' => $usuarioWSB["CB_Valor"],
            'contrasena' => $passwordWSB["CB_Valor"]
        );
    }
    
    /*
     * Metodo para hacer la petición al web service changeCardStatus
     */
    function changeCardStatus($numTarjeta, $estatus, $observaciones, $usuario, $contrasena) {
        //Creación del xml
        $xml = xmlwriter_open_memory();
        xmlwriter_set_indent($xml, 1);
        $res = xmlwriter_set_indent_string($xml, " ");
        xmlwriter_start_document($xml, "1.0", "UTF-8"); //empieza el documento xml

            xmlwriter_start_element($xml, "soapenv:Envelope"); //inicia el soapenv:Envelope
                xmlwriter_start_attribute($xml, "xmlns:soapenv");
                xmlwriter_text($xml, "http://schemas.xmlsoap.org/soap/envelope/");
                xmlwriter_start_attribute($xml, "xmlns:pros");
                xmlwriter_text($xml, "http://prosa.wsdl.cms.verifone.com/");
                xmlwriter_start_element($xml, "soapenv:Header"); //inicia el soapenv:Header
                xmlwriter_end_element($xml); //finaliza el soapenv:Header
                xmlwriter_start_element($xml, "soapenv:Body"); //inicia el soapenv:Body

                    xmlwriter_start_element($xml, "pros:changeCardStatus"); //inicia el changecardstatus
                        xmlwriter_start_element($xml, "card_number"); //inicia el card_number
                            xmlwriter_text($xml, $numTarjeta);
                        xmlwriter_end_element($xml); //finaliza el card_number
                        xmlwriter_start_element($xml, "card_status"); //inicia el card_status
                            xmlwriter_text($xml, $estatus);
                        xmlwriter_end_element($xml); //finaliza el card_status
                        xmlwriter_start_element($xml, "observation"); //inicia el observation
                            xmlwriter_text($xml, $observaciones);
                        xmlwriter_end_element($xml); //finaliza el observation
                        xmlwriter_start_element($xml, "reason"); //inicia el reason
                            xmlwriter_text($xml, "01");
                        xmlwriter_end_element($xml); //finaliza el reason
                        xmlwriter_start_element($xml, "language"); //inicia el language
                            xmlwriter_text($xml, "ES");
                        xmlwriter_end_element($xml); //finaliza el language                            
                    xmlwriter_end_element($xml); //finaliza el changecardstatus

                xmlwriter_end_element($xml); //finaliza el soapenv:Body
            xmlwriter_end_element($xml); //termina el soapenv:Envelope

        xmlwriter_end_document($xml); //termina el documento xml

        $xmlResult = xmlwriter_output_memory($xml); //se prepara el xml a ser enviado

        //Aqui se genera la petición a PROSA
        $url = "https://192.168.251.36:443/CMS-STD-WEB-SERVICES-ISSUER/PROSAws?wsdl";

        $curl = curl_init();   
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml", "username: " . $usuario, "password: " . $contrasena));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlResult);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);        
        curl_close($curl);
        
        return $curl_response;
    }   
    
    /*
     * Metodo que hace la petición del web service getStatusClassAndStatusCards
     */
    function getStatusClassAndStatusCards($tarjeta, $usuario, $contrasena) {
        //Creación del xml
        $xml = xmlwriter_open_memory();
        xmlwriter_set_indent($xml, 1);
        $res = xmlwriter_set_indent_string($xml, " ");
        xmlwriter_start_document($xml, "1.0", "UTF-8"); //empieza el documento xml

            xmlwriter_start_element($xml, "soapenv:Envelope"); //inicia el soapenv:Envelope
                xmlwriter_start_attribute($xml, "xmlns:soapenv");
                xmlwriter_text($xml, "http://schemas.xmlsoap.org/soap/envelope/");
                xmlwriter_start_attribute($xml, "xmlns:pros");
                xmlwriter_text($xml, "http://prosa.wsdl.cms.verifone.com/");
                xmlwriter_start_element($xml, "soapenv:Header"); //inicia el soapenv:Header
                xmlwriter_end_element($xml); //finaliza el soapenv:Header
                xmlwriter_start_element($xml, "soapenv:Body"); //inicia el soapenv:Body

                    xmlwriter_start_element($xml, "pros:getStatusClassAndStatusCards"); //inicia el getStatusClassAndStatusCards
                        xmlwriter_start_element($xml, "card_number"); //inicia el card_number
                            xmlwriter_text($xml, $tarjeta);
                        xmlwriter_end_element($xml); //finaliza el card_number                                                
                        xmlwriter_start_element($xml, "language"); //inicia el language
                            xmlwriter_text($xml, "ES");
                        xmlwriter_end_element($xml); //finaliza el language                            
                    xmlwriter_end_element($xml); //finaliza el getStatusClassAndStatusCards

                xmlwriter_end_element($xml); //finaliza el soapenv:Body
            xmlwriter_end_element($xml); //termina el soapenv:Envelope

        xmlwriter_end_document($xml); //termina el documento xml

        $xmlResult = xmlwriter_output_memory($xml); //se prepara el xml a ser enviado        
        //Aqui se genera la petición a PROSA
        $url = "https://192.168.251.36:443/CMS-STD-WEB-SERVICES-ISSUER/PROSAws?wsdl";

        $curl = curl_init();   
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml", "username: " . $usuario, "password: " . $contrasena));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlResult);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);        
        curl_close($curl);
        
        return $curl_response;
    }
    
    /*
     * Metodo para guardar los datos en la tarjetas a cancelar
     */
    function guardarDatosCancelado($tarjeta, $numTarjeta, $nombreC, $bitacora, $controlTDC, $numeroUsuario, $descripcion, $valorEleccion) {
        $tarjeta->TarjetaDebID = $numTarjeta;
        $tarjeta->Estatus = 9;
        $tarjeta->FechaCancelacion = date("Y") . "-" . date("m") . "-" . date("d");
        $tarjeta->MotivoCancelacion = 12;                            
        if($tarjeta->cancelarTarjeta()) {//si se cancela la tarjeta, se crea una bitacora
            $bitacora->TarjetaDebID = $numTarjeta;
            $bitacora->TipoEvenTDID = 9;
            $bitacora->MotivoBloqID = $valorEleccion;
            $bitacora->DescripAdicio = $descripcion;
            $bitacora->Fecha = date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s");
            $bitacora->NombreCliente = $nombreC;
            $bitacora->EmpresaID = 1;
            $bitacora->Usuario = $numeroUsuario;
            $bitacora->FechaActual = date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s");
            $bitacora->DireccionIP = "255.255.255.255";
            $bitacora->ProgramaID = "TarjetaDebitoDAO";
            $bitacora->Sucursal = 1;
            $bitacora->NumTransaccion = 209191;

            if($bitacora->create()) {//al crearse la bitacora, se pasa a guardar los datos en la tabla de control_tdc
                $controlTDC->CB_TDC = $numTarjeta;
                $controlTDC->CB_Estatus = "Cancelada";
                $controlTDC->CB_Comentarios = "Cancelada el " . date("d") . "-" . date("m") . "-" . date("Y");
                if($controlTDC->update()) {//si se guardo correctamente, se manda el mensaje de ok, lo que quiere decir que todo el proceso se hizo correctamente
                    $mensaje = "ok";
                }
                else {
                    $mensaje = "Se cancelo la tarjeta en PAYWARE, SAFI y se guardaron los datos. No se pudo actualizar en el control TDC.";
                }
            }
            else {
                $mensaje = "Se cancelo la tarjeta en PAYWARE y en SAFI. No se pudo guardar la bitácora.";
            }
        }
        else {
            $mensaje = "Error no se pudo actualizar los datos en la base de datos.";
        }
        
        return $mensaje;
    }
    
    /*
     * 27/05/2024 Funcion depurada de codigo
     * Metodo para guardar los datos y activar la tarjeta a un usuario
     */
    /*function guardarDatosActivar($tarjeta, $clienteID, $cuentaAhoID, $nombreC, $numTarjeta, $bitacora, $controlTDC, $numeroUsuario) {
        $tarjeta->TarjetaDebID = $numTarjeta;
        $tarjeta->FechaActivacion = date("Y") . "-" . date("m") . "-" . date("d");
        $tarjeta->Estatus = 7;
        $tarjeta->ClienteID = $clienteID;
        $tarjeta->CuentaAhoID = $cuentaAhoID;
        $tarjeta->NombreTarjeta = $nombreC;
        $tarjeta->Relacion = "T";
        $tarjeta->TipoCobro = "NSC";                                                        
        if($tarjeta->activarAsignarTarjeta()) {//si se activa la tarjeta se pasa a crear la bitacora
            $bitacora->TarjetaDebID = $numTarjeta;
            $bitacora->TipoEvenTDID = 6;
            $bitacora->MotivoBloqID = 0;
            $bitacora->DescripAdicio = "Asignada a Cuenta/Cliente";
            $bitacora->Fecha = date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s");
            $bitacora->NombreCliente = $nombreC;
            $bitacora->EmpresaID = 1;
            $bitacora->Usuario = $numeroUsuario;
            $bitacora->FechaActual = date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s");
            $bitacora->DireccionIP = "255.255.255.255";
            $bitacora->ProgramaID = "TarjetaDebitoDAO";
            $bitacora->Sucursal = 1;
            $bitacora->NumTransaccion = 209188;
            if($bitacora->create()) {//si se crea bien la bitacora de cuenta asignada, entonces se pasa a crear otro registro para la tarjeta activa
                $bitacora->TipoEvenTDID = 7;
                $bitacora->DescripAdicio = "Tarjeta Activada";
                $bitacora->NumTransaccion = 209189;
                if($bitacora->create()) {//al crearse la bitacora, se pasa a guardar los datos en la tabla de control_tdc
                    $controlTDC->CB_TDC = $numTarjeta;
                    $controlTDC->CB_Estatus = "Entregada";
                    $controlTDC->CB_Comentarios = "Entregada el " . date("d") . "-" . date("m") . "-" . date("Y");
                    if($controlTDC->update()) {//si se guardo correctamente, se manda el mensaje de ok, lo que quiere decir que todo el proceso se hizo correctamente
                        $mensaje = "ok";
                    }
                    else {
                        $mensaje = "Se activo y asignó la tarjeta en PAYWARE y SAFI. No se pudo actualizar en el control TDC.";
                    }
                }
                else {
                    $mensaje = "Se activo y asignó la tarjeta en PAYWARE y en el SAFI. No se pudo guardar la bitácora de tarjeta activada.";
                }
            }
            else {
                $mensaje = "Se activo y asignó la tarjeta en el PAYWARE y en SAFI, no se pudo crear la bitácora.";
            }
        }
        else {
            $mensaje = "Error no se pudo actualizar los datos en la dase de datos.";
        }
        
        return $mensaje;
    }*/
    
    /*
     * Metodo para limpiar las tarjetas
     */
    function limpiarTarjeta($tarjeta, $numTarjeta, $bitacora, $controlTDC) {
        $tarjeta->FechaActivacion = "1900-01-01";
        $tarjeta->Estatus = 6;
        $tarjeta->ClienteID = 0;
        $tarjeta->CuentaAhoID = 0;
        $tarjeta->NombreTarjeta = "";
        $tarjeta->Relacion = "";
        $tarjeta->TipoCobro = "";
        $tarjeta->TarjetaDebID = $numTarjeta;
        if($tarjeta->limpiarTarjeta()) {
            $bitacora->TipoEvenTDID = 6;
            $bitacora->TarjetaDebID = $numTarjeta;
            if($bitacora->delete()) {
                $bitacora->TipoEvenTDID = 7;
                if($bitacora->delete()) {
                    $controlTDC->CB_TDC = $numTarjeta;
                    $controlTDC->CB_Estatus = 'Disponible';
                    $controlTDC->CB_Comentarios = '';
                    if($controlTDC->update()) {//si se guardo correctamente, se manda el mensaje de ok, lo que quiere decir que todo el proceso se hizo correctamente
                        $mensaje = "ok";
                    }
                    else {
                        $mensaje = "Se limpio la tarjeta pero no se pudo actualizar su estatus en ";
                    }
                }
                else {
                    $mensaje = "Se limpio la tarjeta pero no se pudo eliminar la última bitacora.";
                }
            }
            else {
                $mensaje = "Se limpio la tarjeta pero no se eliminaron las bitacoras.";
            }
        }
        else {
            $mensaje = "No se pudo limpiar la tarjeta.";
        }
        
        return $mensaje;
    }