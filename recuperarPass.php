<?php
include_once 'include/head.php';
//require_once('../../inicio.php');
?>

<body>
    <div class="form-container">
        <img class="mb-3" src="img/viaticos.png" alt="logo viaticos" style="width: 300px;display: block; margin: 0 auto;">
        <h2 class="mb-4 text-center" style="font-size: 24px">Restablecer Contraseña</h2>
        <div class="form-group mb-3">
            <label for="email" class="form-label">Escribe tu correo para continuar:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="correo@ventacero.com" required>
        </div>
        <!-- Botón personalizado -->
        <button type="submit" class="btn btn-orange" onclick="enviar_email()">Enviar</button>
    </div>

<style>
  
  </style>

  
<?php echo '<script src="js/recuperarPass.js"></script>'; ?>
<link href="css/recuperarPass.css" rel="stylesheet">