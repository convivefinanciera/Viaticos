<?php

try{
    //Conexión a BD de Comprobantes
    $conC = mysqli_connect("192.168.1.92", "root", "zafy2017", "comprobantes") or die("Error de la conexion a la base de datos");
    $conC->set_charset("utf8mb4");
    $bdC = mysqli_select_db($conC, 'comprobantes') or die("No existe la base de datos");
}
catch(Exception $ex){

}

?>