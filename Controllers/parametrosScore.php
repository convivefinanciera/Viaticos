<?php 
include_once 'conexion.php';

$bandera = $_POST['bandera'];

if ($bandera == 'CargarParametrosScore') {
    $result = $con->query("SELECT * FROM tb_web_va_scoreparametros;");

    if($result)
    {
        $parametros = array();
    
        while($row = $result->fetch_assoc())
        {
            $parametros[] = $row;
        }

        echo json_encode($parametros);
        return false;
    }
    else
    {
        $res = [
            'estatus' => 400,
            'mensaje' => 'No se han podido guardar los cambios'
        ];
        echo json_encode($res);
        return false;
    }

}
?>