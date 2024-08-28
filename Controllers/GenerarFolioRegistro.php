<?php

A:
$folioReg = GenerarFolio();
//echo 'Solicitud: ' . $folioSol."\n";

//Verificación adicional para verificar que no exista el folio
$verificarFolio = "SELECT COUNT(*) AS EXISTE_FOLIO FROM tb_web_cv_registro WHERE ID_Registro = '$folioReg';";
$result = $con->query($verificarFolio)->fetch_assoc();
$conteoFolio = $result['EXISTE_FOLIO'];

//Obtenemos sucursal ID
if ($conteoFolio > 0) {
    //echo "Mayor que cero";
    goto A;
} else if ($conteoFolio == 0) {
    //echo "Igual que cero";
    //$insertSolicitud  = $con->query("INSERT INTO tb_web_va_solicitud (ID_Solicitud) VALUES ('$folioSol')");
    //if ($insertSolicitud) {
    $_SESSION['ID_Solicitud'] = $folioSol;
    //echo "Folio Generado desde script generar ".$_SESSION['ID_Solicitud'];
    // $res = [
    //     'estatus' => 200,
    //     'mensaje' => 'Folio de Solicitud generado.',
    //     'folio_solicitud' => $folioSol
    // ];
    // echo json_encode($res);
    // return false;
    //} else {
    // $res = [
    //     'estatus' => 400,
    //     'mensaje' => 'Folio de Solicitud NO generado.'
    // ];
    // echo json_encode($res);
    // return false;
    //}
}

function GenerarFolio()
{
    //generamos 4 numeros random 
    $digits = 4;
    $numeroAzar = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
    //Generamos 3 letras random
    $caracteres_permitidos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitud = 3;
    $letras = substr(str_shuffle($caracteres_permitidos), 0, $longitud);

    $ID_Solicitud = 'SOL-' . $numeroAzar . '-' . $letras;

    return $ID_Solicitud;
}
