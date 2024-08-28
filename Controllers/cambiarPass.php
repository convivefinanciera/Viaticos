<?php
include_once '../Controllers/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $email = $_POST['email'];

    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //echo "Toke: $token, Email: $email";

    // Verificar el token y su expiración
    $query = "SELECT * FROM users WHERE access_token = '$token' AND email = '$email' AND token_expiry > NOW()";
    $result = $con->query($query);

    if ($result->num_rows > 0) {

        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));
        // Actualizar la contraseña
        $con->query("UPDATE users SET password = '$newPassword', access_token = NULL, token_expiry = NULL WHERE email = '$email'");
     
        echo "Contraseña actualizada con éxito.";
    } else {
        echo "Token inválido o expirado.";
        }
    } else {
        echo "Método de solicitud no válido.";
}
?>