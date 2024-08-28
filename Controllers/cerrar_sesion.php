<?php
if (isset($_POST['cerrar_sesion'])) {
    CerrarSesion();
}

function CerrarSesion(){
    try
    {
        session_start();
        session_destroy();

        echo json_encode(array(
            "success" => true,
            "message" => "Se cerró sesión"
        ));
    }
    catch (Exception $ex){
        echo json_encode(array(
            "error" => "Error al cerrar sesión. Msj: " . $ex->getMessage()
        ));
    }
}
?>