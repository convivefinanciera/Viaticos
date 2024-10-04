<?php
//ini_set('display_errors', 0);
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    //Conexión a BD de Pruebas
    $con = mysqli_connect("192.168.1.92", "root", "zafy2017", "microfin_pruebas") or die("Error de la conexion a la base de datos");
    //$con = mysqli_connect("192.168.1.92", "root", "zafy2017", "microfin") or die("Error de la conexion a la base de datos");
    $con->set_charset("utf8mb4");
    $bd = mysqli_select_db($con, 'microfin_pruebas') or die("No existe la base de datos");
    // $bd = mysqli_select_db($con, 'microfin') or die("No existe la base de datos");
}
catch(Exception $ex){

}

// Conexion a BD de Producción
//$con = mysqli_connect("192.168.1.92", "root", "zafy2017", "microfin") or die("Error de la conexion a la base de datos");
//$con->set_charset("utf8mb4");
//$bd = mysqli_select_db($con, 'microfin') or die("No existe la base de datos");

// //Base URL
// $protocol = isset($_SERVER['HTTPS']) &&
// $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
// $base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';

?>