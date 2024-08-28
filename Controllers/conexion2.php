<?php
class Conexion
{
    function connect()
    {
        //Conexión a BD de Pruebas
        $con = mysqli_connect("192.168.1.92", "root", "zafy2017", "microfin_20240222") or die("Error de la conexion a la base de datos");
        $con->set_charset("utf8mb4");
        $bd = mysqli_select_db($con, 'microfin_20240222') or die("No existe la base de datos");
    }
}
