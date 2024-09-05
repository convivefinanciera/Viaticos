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

       
        if (enviarCorreoVerificacion($token, $email)) {
            $response['success'] = true;
        } else {
            $response['error'] = 'No se pudo enviar el correo.';
        }
    } else {
        $response['error'] = 'El correo electrónico no existe o no está activo, favor de consultar a su administrador.';
    }
} else {
    $response['error'] = 'El correo electrónico no fue proporcionado.';
}

function enviarCorreoVerificacion($token, $email) {
    global $response;
	 $url = 'https://creditoventacero.mx/VentAcero/cambiarPass.php.php?token=' . $token . '&email=' . urlencode($email);
	 $url_img = 'https://creditoventacero.mx/VentAcero/img/RestorePass.png';
    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;				                      //Enable verbose debug output
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
        $mail->Body = '
        <!DOCTYPE html>
		<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--[if mso]><xml><o:OfficeDocumentSettings>
		<o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/>
		</o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!--><!--<![endif]-->
		<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		.image_block img+div {
			display: none;
		}

		#converted-body .list_block ul,
		#converted-body .list_block ol,
		.body [class~="x_list_block"] ul,
		.body [class~="x_list_block"] ol,
		u+.body .list_block ul,
		u+.body .list_block ol {
			padding-left: 20px;
		}

		@media (max-width:620px) {
			.desktop_hide table.icons-inner {
			display: inline-block !important;
			}

			.icons-inner {
			text-align: center;
			}

			.icons-inner td {
			margin: 0 auto;
			}

			.image_block div.fullWidth {
			max-width: 100% !important;
			}

			.mobile_hide {
			display: none;
			}

			.row-content {
			width: 100% !important;
			}

			.stack .column {
			width: 100%;
			display: block;
			}

			.mobile_hide {
			min-height: 0;
			max-height: 0;
			max-width: 0;
			overflow: hidden;
			font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
			display: table !important;
			max-height: none !important;
			}
		}
		</style>
		</head>

		<body align = "center" margin="0" padding="0" style="background-color: #ededed;">
		<br>
		<table width="65%" height="10%" border ="0" align="center">
		<tr width="50%">
			<td style="background-color:#d90000; border-radius: 15px; text-align: center;">
				<br><h3 style="color: white;">CONVIVE FINANCIERA HA RECIBIDO UNA SOLICITUD PARA RESTABLECER SU CONTRASEÑA</h3><br>
			</td>
		</tr>
		<tr>
			<td width="50%">
			
			<table width="100%" border="0" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word; color: #101112; direction: ltr; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight: 400; letter-spacing: 0px; line-height: 120%; text-align: center; mso-line-height-alt: 19.2px;">
			<tr>
			<td>
			<img style="border-radius: 15px;" src="'.$url_img.'">
			</td>
			</tr>
			</table>
		<tr style="text-align: center;">
			<td>
				<br>Si desea restablecer su contraseña,<br>seleccione el siguiente enlace: <br><br>

				<div style="border-radius: 8px;float:center;display:inline-block;min-height:43px;background-color:#A19F9F;color:white;padding:0px 15px;line-height:43px;font-size:16px;font-weight:bold;letter-spacing:1px">
					<a style="text-decoration: none; color: white;" href="' . $url . '">Recuperar contraseña</a>
				</div>
				<br><br> Este vínculo estará disponible únicamente por 5<br>minutos a partir de la recepción de este correo<br><br>
			</td>
		</tr>
		<tr style="text-align: center;">
			<td>
				<br>
				Cualquier duda... ¡Contáctanos!<br><br>

				Todo México:<br>

				800 044 01 56<br><br>

				Correo:<br>

				soporte@convivefinanciera.com <br><br><br>

				© Convive Financiera<br>
				Todos los derechos reservados 2024 <br><br>
				Para políticas de privacidad y Términos de uso, consultar: <br>
				<a href="https://www.convivefinanciera.com/">www.convivefinanciera.com</a><br>

			</td>
		</tr>
	</table><br><br>  

</body>
</html>';

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