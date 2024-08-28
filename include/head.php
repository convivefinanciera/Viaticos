<title>Contol Viáticos</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Favicons -->
<link rel="shortcut icon" href="/viaticos/img/icons/viaticos_ico.ico" type="image/x-icon">
<!-- Google Fontss -->
<link href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<!-- Referencias Bootstrap / Boostrap icons -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->

<!-- Toastify -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script> -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script> -->


<!-- Referencia a JQuery -->
<!-- jQuery UI -->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
TinyMCE
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script> -->


<?php 
   
    #Configuracion zona horaria
    setlocale(LC_ALL, 'es_MX');
    date_default_timezone_set("America/Mexico_City");
    $Fecha = DATE("Y-m-d H:i:s");

    $rutaServer = chr(47).'Viaticos'.chr(47);

    // Configura el tiempo de vida de la sesión a 10 minutos (600 segundos)
    $timeSesion = 3600; //Tiempo en segundos para destruir sesión
    if (!isset($_SESSION)) {
        ini_set('session.gc_maxlifetime', $timeSesion);
    }

    // Inicia la sesión
    //session_start();

    // Actualiza la cookie de la sesión para que su tiempo de vida se reinicie en cada solicitud
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeSesion) {
        // Si la última actividad fue hace más de 600 segundos, destruye la sesión
        // echo "<script>
        //         document.addEventListener('DOMContentLoaded', function () {
        //             location.href = '/ventacero/index.php?SesionFinalizada'
        //             /* Toastify({
        //                 text: 'Tu sesión ha finalizado por inactividad.',
        //                 className: 'error',
        //                 duration: 4000,
        //                 gravity: 'top',
        //                 position: 'center',
        //                 style: {
        //                     background: 'linear-gradient(to right, #ff3636, #de0202)',
        //                 }
        //             }).showToast(); */
        //         })
        //     </script>";
        header("Location: /viaticos/index.php?SesionFinalizada");
        session_unset();     // Elimina todas las variables de sesión
        session_destroy();   // Destruye la sesión
        // // Asegúrate de que la cookie de la sesión tenga el mismo tiempo de vida que la sesión
        // setcookie(session_name(), session_id(), time() + 10, "/");
        echo $rutaServer;

        echo "<script> window.location.replace('". $rutaServer ."index.php?SesionFinalizada'); </script>";
        // echo "Toastify({
        //             text: 'Tu sesión ha finalizado por inactividad.',
        //             className: 'error',
        //             duration: 5000,
        //             destination: '". $rutaServer ."index.php',
        //             gravity: 'bottom',
        //             position: 'right',
        //             style: {
        //                 background: 'linear-gradient(to right, #ff3636, #de0202)',
        //             }
        //         }).showToast();</script>";
    } else {
        // Actualiza el tiempo de la última actividad
        $_SESSION['last_activity'] = time();
    }
?>

<!-- Google Fontss -->
<link href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<!-- Referencia a JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Referencias Bootstrap / Boostrap icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<?php echo '<link rel="stylesheet" href="' . $rutaServer . 'css/bootstrap-select.min.css">'; ?>

<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<?php echo '<script src="' . $rutaServer . 'js/bootstrap-select.min.js"></script>'; ?>

<?php //echo '<script src="'.$rutaServer.'js/bootstrap.bundle.min.js"></script>'; ?>

<!-- Referencia a Data Tables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<!-- Referencia a Xlsx -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Toastify -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Referencia a rev_Inputs.js -->
<?php echo '<script src="' . $rutaServer . 'js/rev_Inputs.js"></script>'; ?>

<script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- <script src="/js/rev_Inputs.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script> -->

<!-- TinyMCE -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script> -->
