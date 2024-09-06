<?php
include_once '../Controllers/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../librerias/PHPMailer-master/src/Exception.php';
require '../librerias/PHPMailer-master/src/PHPMailer.php';
require '../librerias/PHPMailer-master/src/SMTP.php';

$token = bin2hex(random_bytes(32));
$response = [];

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = "SELECT * FROM users WHERE email = '$email' AND status = 1";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
       
        $expiry = date("Y-m-d H:i:s", strtotime('+5 minutes'));

        $con->query("UPDATE users SET access_token = '$token', token_expiry = '$expiry' WHERE email = '$email' AND status = 1");

		$enviocorreo = enviarCorreoVerificacion($token, $email);
       
        if ($enviocorreo) {
            $response['success'] = true;
        } else {
            $response['error'] = $enviocorreo;
        }
    } else {
        $response['error'] = 'El correo electrónico no existe o no está activo, favor de consultar a su administrador.';
    }
} else {
    $response['error'] = 'El correo electrónico no fue proporcionado.';
}

function enviarCorreoVerificacion($token, $email) {
    global $response;
	 $url = 'https://convivetufinanciera.com.mx/Viaticos/cambiarPass.php?token=' . $token . '&email=' . urlencode($email);
	 $url_img = 'https://creditoventacero.mx/VentAcero/img/RestorePass.png';
    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug  = 0;				                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.convivefinanciera.com';           //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'soporte@convivefinanciera.com';        //SMTP username
        $mail->Password   = 'Soporte2023%';                         //SMTP password
        $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom('soporte@convivefinanciera.com', 'Soporte TI Control de Víaticos');
        $mail->addAddress($email);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body = "
			<!DOCTYPE html>
			<html xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office' lang='en'>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1.0'>
				<style>
					* { box-sizing: border-box; }
					p { line-height: 1.5; margin: 0; }
					img { display: block; }
				</style>
			</head>
			<body style='background-color: #f3f6f9; font-size: 16px; margin: 0; padding: 0;'>
				<table role='presentation' cellspacing='0' cellpadding='0' border='0' style='width: 100%; max-width: 800px; margin: 0 auto; background-color: #ffffff;'>
					<tr>
						<td style='padding: 20px;'>
							<!-- Header with Logo -->
							<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>
								<tr>
									<td style='text-align: center;'>
										<img src='https://convivetufinanciera.com.mx/Viaticos/img/convivelogo.jpg' alt='Convive Financiera Logo' style='height: 60px;'>
									</td>
								</tr>
							</table>
							<!-- Main Content -->
							<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%' style='background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
								<tr>
									<td style='padding: 20px; text-align: center;'>
										<p style='font-weight: 600; font-size: 18px; margin-bottom: 10px;'>Hemos recibido una solicitud para restablecer su contraseña.</p>
										<hr style='width: 150px; height: 3px; background-color: #d90000; border: none; margin: 10px auto;'>
										<p>Para restablecer su contraseña seleccione el siguiente enlace.</p>
										<a href='$url' style='display: inline-block; text-decoration: none; background-color: #A19F9F; color: #ffffff; padding: 12px 20px; border-radius: 8px; font-weight: bold; font-size: 16px;'>Recuperar contraseña</a>
										<p style='margin-top: 20px;'>Este enlace estará disponible únicamente por 5 minutos a partir de la recepción de este correo.</p>
										<p style='font-weight: 600;'>Cualquier duda ¡Contáctanos!</p>
										<p>Todo México:<br>800 044 01 56</p>
										<p>Correo:<br>soporte@convivefinanciera.com</p>
										<p style='font-size: 12px;'>© Convive Financiera<br>Todos los derechos reservados 2024<br>Para políticas de privacidad y Términos de uso, consultar:<br><a href='https://www.convivefinanciera.com/'>www.convivefinanciera.com</a></p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</body>
			</html>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        $response['error'] = 'No se pudo enviar el correo. Error: ' . $mail->ErrorInfo;
        return false;
    }
}

// Imprimir la respuesta como JSON
echo json_encode($response);
?>