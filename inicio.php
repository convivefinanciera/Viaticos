<?php
session_start();
// $rutaServer = $_SERVER['HTTP_HOST'].chr(47).'VentAcero'.chr(47);
// $rutaServer = chr(47).'VentAcero'.chr(47);
//echo $rutaServer.'js/bootstrap.bundle.min.js';

if (!isset($_SESSION['ID_Usuario'])) {
    echo "<script> location.href = '/viaticos/index.php'; </script>";
}

echo '
<!DOCTYPE html>
<html lang="es">';

echo '<head>';
include_once('include/head.php');
echo '
    <title>Control de Viáticos</title>
</head>

<body>
';
include_once 'include/navbar.php';
include_once 'include/sidebar.php';

echo '
    <link href="'.$rutaServer.'css/style.css" rel="stylesheet">
    <script src="'.$rutaServer.'js/main.js"></script>
    <script src="'.$rutaServer.'js/header.js"></script>
</body>

</html>';

//Llamado a importaciones desde vistas se hace con ruta absoluta
//Llamado a Controllers desde  los JS se hace con ruta relativa