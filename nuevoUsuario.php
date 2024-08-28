<?php
require_once('Controllers/conexion.php');
require_once('include/head.php');

?>

<body>
    <div class="form-container">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nombreCompleto" placeholder="Nombre Completo">
            <label for="nombreCompleto">Nombre Completo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="correo@correo.com">
            <label for="floatingInput">Correo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="num" class="form-control" id="celular" placeholder="Celular">
            <label for="celular">Celular</label>
        </div>
        <div class="mb-3">
            <select class="form-select" aria-label="Default select example">
                <option selected>Sucursal</option>
            </select>
        </div>
        <button type="submit" class="btn btn-orange">Enviar</button>
    </div>
</body>


<style>
 /* Fondo de la página */
body {
    background-image: url('img/Montaje10.jpg');
    background-size: cover;
    background-position: center;
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Contenedor del formulario */
.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255, 255, 255, 0.8); /* Fondo blanco semi-transparente */
    padding: 20px;
    border-radius: 10px;
}

/* Estilos para los campos del formulario */
.form-floating, .form-select, .btn {
    width: 300px;
    margin-bottom: 15px;
}

/* Botón de envío */
.btn-orange {
    background-color: rgba(64, 65, 64);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-orange:hover {
    background-color: #d90000;
}
</style>