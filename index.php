<?php
include_once 'include/head.php';
?>


<link href="css/loginstyle.css" rel="stylesheet">

<div class="fadeInDown" style="width: 100%;
    height: 100vh;
    display: grid;
    grid-template: 1fr 3vh / 1fr;
    place-items: center;">
  <div id="formContent" style="box-shadow: 0 40px 49px -15px #d9d9d9; border: 1px solid #e3e3e3;">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first">
      <!-- <img src="../assets/img/tarjeta_VentAcero.jpg" id="icon" alt="User Icon" /> -->
    </div>

    <!-- Login Form -->
    <form id="loginForm" method="POST" class="d-grip gap-3">
      <img class="mt-3" src="img/viaticos.png" alt="logo viaticos" style="width: 70%;">
      <h1 style="    font-size: 24px;
    margin-top: 20px;
    margin-bottom: 20px;">Control de Viáticos</h1>
      <input type="text" id="email" class="fadeIn second" name="email" placeholder="Correo electrónico">
      <input type="password" id="contraseña" class="fadeIn third" name="password" placeholder="Contraseña">
      <input type="submit" class="fadeIn fourth" value="Iniciar Sesión" style="    margin-top: 23px;
    margin-bottom: 10px;">
    </form>
    <div id="formFooter">
      <a class="underlineHover" href="recuperarPass.php">Recuperar Contraseña</a>
    </div>
  </div>
  <div>
    <h5 class="m-0" style="font-size: 14px;">Viáticos 1.0.0</h5>
  </div>
</div>

<style>
  .contenedor-centro {
    display: flex;
    justify-content: center;
    /* Centra horizontalmente */
    align-items: flex-end;
    /* Alinea al fondo (parte inferior) */
    height: 300px;
    /* Altura del contenedor */
    border: 1px solid #000;
    position: relative;
    /* Para asegurar que el contenedor mantenga la posición relativa */
  }

  .centro-fondo {
    background-color: lightblue;
    padding: 10px;
  }
</style>


<!-- Tu archivo JavaScript -->
<script src="js/login.js"></script>
<?php
if (isset($_GET["SesionFinalizada"])) {
  echo "<script> Toastify({
                        text: 'Sesión finalizada por inactividad',
                        className: 'success',
                        duration: 7000,
                        gravity: 'top',
                        position: 'center',
                        style: {
                          background: 'linear-gradient(to right, #5087FF, #5087FF)',
                        }
                      }).showToast(); </script>";
}
?>
<style>
  body {
    font-family: "Poppins", sans-serif;
    height: 100vh;
    /*background-image: url('../assets/img/tarjeta_VentAcero.png');
    /* Reemplaza 'ruta_de_tu_imagen.jpg' con la ruta de tu imagen de fondo */
    background-size: cover;
    /* Para cubrir todo el área del body */
    background-position: center;
    /* Para centrar la imagen */
    /* filter: blur(2px); /* Ajusta el valor de desenfoque según sea necesario */
  }
</style>