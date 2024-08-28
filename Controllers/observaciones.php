<?php
session_start();
include_once 'conexion.php';

$bandera = $_POST['bandera'];
$ID_Solicitud_Sesion = $_SESSION['ID_Solicitud'];
$ID_Usuario_Sesion = $_SESSION['ID_Usuario'];

if ($bandera == 'Mostrar_TablaObservaciones') {
    $tabla = '';
    $tabla .= '<div class="dataTables_scrollBody" style="position: relative; overflow: auto; max-height: 60vh; width: 100%;">
    <table id="tablaObservaciones" class="table table-striped">
    <thead>
        <tr>
            <th scope="col"><center>Observacion</center></th>
            <th scope="col"><center>Usuario</center></th>
            <th scope="col"><center>Sucursal</center></th>
            <th scope="col"><center>Fecha</center></th>     
        </tr>
    </thead>
    <tbody>';

    // Prepare the SQL statement
    $stmt = $con->prepare("SELECT A.Observacion, A.FechaAlta, B.alias, D.Sucursal 
                            FROM tb_web_va_observaciones A, users B, tb_web_va_usuariosroles C, tb_web_va_sucursales D 
                            WHERE A.ID_Usuario = B.id 
                            AND B.id = C.ID_Usuario 
                            AND C.ID_Sucursal = D.ID_Sucursal
                            AND A.ID_Solicitud = ? ORDER BY A.FechaAlta DESC;");

    // Bind the parameter to the statement
    $stmt->bind_param("s", $ID_Solicitud_Sesion);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $resObservaciones = $stmt->get_result();

    // Output the number of rows returned by the query

    if ($resObservaciones->num_rows > 0) {
        while ($row = $resObservaciones->fetch_assoc()) {
            $tabla .= '
            <tr>
                <td style="padding-top:20px"><center>' . $row['Observacion'] . '</center></td>
                <td style="padding-top:20px"><center>' . $row['alias'] . '</center></td>
                <td style="padding-top:20px"><center>' . $row['Dependencia'] . '</center></td>
                <td style="padding-top:20px"><center>' . $row['FechaAlta'] . '</center></td>
            </tr>';
        }
    } else {
        $tabla .= '
        <tr>
            <td style="padding-top:20px" colspan="4"><center>No registros</center></td>
        </tr>';
    }

    $tabla .= '</tbody>
    </table></div>';
    echo $tabla;
}

if ($bandera == 'Agregar_Observacion') {
    $observacion = $_POST['observacion'];
    $fechaAlta = date('Y-m-d H:i:s');

    $SQL = "INSERT INTO tb_web_va_observaciones (ID_Solicitud, ID_Usuario, Observacion, FechaAlta) VALUES ('$ID_Solicitud_Sesion', '$ID_Usuario_Sesion', '$observacion', '$fechaAlta');";

    if ($con->query($SQL) === TRUE) {
        $res = [
            'estatus' => 200,
            'mensaje' => 'Se ha agregado la observación a la solicitud '.$ID_Solicitud_Sesion
        ];
        echo json_encode($res);
        return false;
    } else {
        $res = [
            'estatus' => 400,
            'mensaje' => 'No se ha podido guardar la observación a la solicitud '.$ID_Solicitud_Sesion
        ];
        echo json_encode($res);
        return false;
    }
}
