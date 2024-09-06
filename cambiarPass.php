<?php
include_once 'include/head.php';
?>


<body>
    <div class="form-container">
        <form id="cambiarPasswordForm">
            <img class="mb-3" src="img/viaticos.png" alt="logo viaticos" style="width: 300px; display: block; margin: 0 auto;">
            <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input type="hidden" id="email" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password">
            <label for="confirmarPass">Confirmar Contraseña:</label>
            <input type="password" id="confirmarPass" name="confirmarPass">
            <button type="submit" class="btn btn-orange">Cambiar Contraseña</button>
        </form>
    </div>
</body>



<?php echo '<script src="js/cambiarPass.js"></script>'; ?>
<link href="css/cambiarPass.css" rel="stylesheet">