<?php
function EnviarSMS($Celular, $Mensaje){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone='.$Celular.'&msg='.$Mensaje);  
    curl_setopt($ch, CURLOPT_TIMEOUT, 45); //timeout after 45 seconds 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', "Authorization: Bearer dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09"));  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , false );
    
    $respuesta = curl_exec($ch); 
    curl_close($ch);

    $respuesta = substr($respuesta, strpos($respuesta, "error")+8, 5);
    if($respuesta == 'false'){
        $respuesta = 200;
    }
    else{
        $respuesta = 500;
    }

    return $respuesta;
}
?>