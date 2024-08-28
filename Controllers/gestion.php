<?php
include_once "conexion.php";
if (isset($_POST['bandera'])) {
    $bandera = $_POST['bandera'];
    $result = array('error' => false);
    $datos = array();

    if ($bandera == 'mostrar_log') {
        $consulta = $con->query("SELECT *
                                FROM tardebbitacoramovs
                                WHERE Estatus = 'P' AND 
                                tarjetadebid IN (SELECT CB_TDC FROM tb_control_tdc B WHERE B.CB_ProducCreditoID = 9000);");
        if ($consulta and $consulta->num_rows) {
            while ($fila = $consulta->fetch_assoc()) {
                // var_dump($fila);
                $fila['MontoOpe'] = "$" . number_format($fila['MontoOpe'], 2, '.', ',');

                $estatus = '';
                switch ($fila['Estatus']) {
                    case 'P':
                        $estatus = 'Procesada';
                        break;
                    
                    case 'R':
                        $estatus = 'Rechazada';
                        break;
                }
                $fila['Estatus'] = $estatus;
                array_push($datos, $fila);
            }
            $result['datos'] = $datos;
        } else {
            $result['error'] = true;
        }
    } else {
        echo "la bandera no exite";
    }

    echo json_encode($result);
    $con->close();
    die();
}
?>