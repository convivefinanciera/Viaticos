<?php
include_once 'conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../librerias/PHPMailer-master/src/Exception.php';
require '../librerias/PHPMailer-master/src/PHPMailer.php';
require '../librerias/PHPMailer-master/src/SMTP.php';


if (!empty($_POST['email']) && !empty($_POST['contraseña'])) {
    $correo = $_POST['email'];
    $password = $_POST['contraseña'];

    // echo "Correo " .$correo . " pass ".$password;
    // Consulta para buscar al usuario por su correo electrónico
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        $hashed_password = $row['password'];  // Aquí se usa 'password' en lugar de 'contraseña'
        $empresa_id = $row["empresaID"];
        $rol = $row["role"];

        //echo "empresa id ".$empresa_id." rol ".$rol;

        //Verificamos empresa y rol
        //Contro Viáticos id 67 - Convive Financiera 1
        //Buscar tabla usuariosroles
        if ((($empresa_id == 67 || $empresa_id == 1) && ($rol == 'admin' || $rol == 'superadmin'))) {
             //echo "Si verifica admin y 63";
            // Verificar si la contraseña proporcionada coincide con el hash almacenado
            if (password_verify($password, $hashed_password)) {
                $result = $con->query("SELECT ur.*, d.Dependencia, r.Rol as rol_viaticos from tb_web_cv_usuariosroles ur join tb_web_cv_roles r on ur.ID_Rol = r.ID_Rol join tb_web_cv_dependencias d on ur.ID_Dependencia = d.ID_Dependencia where ur.ID_Usuario =" . $row['id'] . ";");
                if ($result->num_rows > 0) {
                    $rowRol = $result->fetch_assoc();

                        
                    //$numero_validacion = generarNumeroValidacion();
                    //$_SESSION['numero_validacion'] = $numero_validacion;

                    // $UPDATE = "UPDATE users SET verify_code = ? WHERE email = ?";
                    // $updateStmt = $con->prepare($UPDATE);
                    // $updateStmt->bind_param("is", $numero_validacion, $correo);
                    // $updateStmt->execute();
                    // Contraseña válida, empresa correcta y rol asignado, iniciar sesión
                    session_start();
                    $_SESSION['ID_Usuario'] = $row["id"];                   //Dato de tabla users
                    $_SESSION['Nombre_Usuario'] = $row["name"];                       //Dato de tabla users
                    $_SESSION['empresaID'] = $row["empresaID"];             //Dato de tabla users
                    $_SESSION['role'] = $rowRol["ID_Rol"];                  //Dato de tabla tb_web_cv_usuariosroles
                    $_SESSION['email'] = $rowRol["Correo"];                 //Dato de tabla tb_web_cv_usuariosroles
                    $_SESSION['celular'] = $rowRol["Celular"];              //Dato de tabla tb_web_cv_usuariosroles
                    $_SESSION['rol_viaticos'] = $rowRol["rol_viaticos"];  //Dato de la tabla tb_web_cv_roles
                    $_SESSION['ID_Dependencia'] = $rowRol["ID_Dependencia"];      //Dato de la tabla tb_web_cv_usuariosroles
                    $_SESSION['Dependencia'] = $rowRol["Dependencia"];            //Dato de la tabla tb_web_cv_dependencias

                    // var_dump($_SESSION);
                    //enviarCorreoVerificacion($correo, $numero_validacion);

                    echo json_encode(array(
                        "success" => true,
                        "message" => "Se inició sesión", 
                        
                    ));
                   
                } else {
                    echo json_encode(array(
                        "error" => "El usuario no tiene rol asignado en la plataforma."
                    ));
                }
            } else {
                echo json_encode(array(
                    "error" => "Usuario y/o contraseña incorrectos"
                ));
            }
        } else {
            echo json_encode(array(
                "error" => "El usuario no tiene las credenciales para acceder a la plataforma."
            ));
        }
    } else {
        // Usuario no encontrado
        echo json_encode(array(
            "error" => "Usuario no encontrado",
            header("Location: nuevoUsuario.php")
            
        ));
    }
} else {
    echo json_encode(array(
        "error" => "Por favor, completa todos los campos del formulario."
    ));
}

function generarNumeroValidacion() {
    return rand(100000, 999999);
}


function enviarCorreoVerificacion($correo, $numero_validacion) {
  
    // Crear una instancia de PHPMailer
            $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.convivefinanciera.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'soporte@convivefinanciera.com';                     //SMTP username
        $mail->Password   = 'Soporte2023%';                               //SMTP password
        $mail->SMTPSecure = 'ssl';           //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet    = 'UTF-8';
        //Recipients
        $mail->setFrom('soporte@convivefinanciera.com', 'Credito ');
        $mail->addAddress($correo);     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Código de verificación';
        $mail->Body = "Su código de verificación es: $numero_validacion";


        $mail->send();
        echo 'Enviado correctamente';
        } catch (Exception $e) {
            echo "Error al enviar correo: {$mail->ErrorInfo}";
        }
}
