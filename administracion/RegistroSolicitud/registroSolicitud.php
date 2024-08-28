<?php
require_once('../../inicio.php');
require_once('../../Controllers/conexion.php'); 
?>
<?php echo '<link rel="stylesheet" href="' . $rutaServer . 'css/monitorSolicitudes.css">' ?>
<?php echo '<link rel="stylesheet" href="' . $rutaServer . 'css/registroSolicitud.css">' ?>
<?php echo '<script src="' . $rutaServer . 'js/registroSolicitud.js"></script>'; ?>
<?php 

if (!isset($_GET['ID_Solicitud'])) //Si la liga no tiene un ID_Solicitud se verifica que se siga en trabajando en la misma solicitud de sesión
{
    // echo '<script> ResetearSolicitud(); </script>';
    $_SESSION['ID_Solicitud'] = '';
} else if (isset($_GET["ID_Solicitud"])) //Si se contiene un ID_Solicitud se toma de la ruta y se setea en sesión para continuar trabajando con esa solicitud
{
    $ID_Solicitud_Get = $_GET["ID_Solicitud"];
    $_SESSION['ID_Solicitud'] = $ID_Solicitud_Get;
    $ID_Solicitud_Consulta = $_SESSION['ID_Solicitud'];
    echo "ID SOLICITUD CONSULTA " . $ID_Solicitud_Consulta;
    echo "<script>CargarAvancesSolicitud();</script>";
}
?>

<main id="main" class="main">
    <div>
        <div class="card-header">
            <h2 class="card-title text-center fw-bold" style="color: #d90000">REGISTRO DE MONEDEROS</h2>
        </div>
    </div>
    <!-- <div id="seccionSolicitud" class="accordion" id="accordionSolicitud"> -->
    <div class="accordion mt-3" id="accordionSolicitud">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <!-- <h2 class="accordion-header" id="headerParte1"> -->
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    DATOS GENERALES DE USUARIO
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="headerParte1" data-bs-parent="#accordionSolicitud">
                <div class="accordion-body">
                    <?php require_once('../MonitorRegistro/datosGenerales.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para visualizar el detalle -->
    <div class="modal fade" tabindex="-1" id="detalleArchivosModal">
        <div class="modal-dialog">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <!-- <h5 class="modal-title" id="nombreArchivoModal">Evaluación de documentos</h5> -->
                    <h1 class="card-title w-100 text-center" style="color:  #d90000">EVALUACIÓN DE DOCUMENTOS</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <div style="display: flex; align-items: stretch; height: 100%; justify-content: space-between; padding: 20px 5px;">
                        <div id="calificacionArchivo" class="text-center mb-5" style="width: 30%; /* padding: 20px; */">
                            <div class="col text-center">
                                <div class="row mt-3">
                                    <label for="customRange1" class="form-label" id="labelCalificacion"></label>
                                </div>
                                <div class="row mt-3 text-center justify-content-center">
                                    <!-- <input type="number" id="inputCalif" style="width: 200px;" min="0" max="100"></input> -->
                                    <div>
                                        <label for="calificacion" style="font-family: 'Rubik', sans-serif; font-weight: 400;">Calificación</label>
                                    </div>
                                    <div class="number-input-container">
                                        <input id="minus" type="button" value="-" class="minus">
                                        <input id="inputCalif" type="number" min="0" max="100" step="1" value="1" style="text-align:center;">
                                        <input id="plus" type="button" value="+" class="plus">
                                    </div>
                                </div>
                                <div class="row mt-3 justify-content-center">
                                    <input id="inputCalifSlide" type="range" class="form-range" min="0" max="100" step="1" value="1" style=" width:400px;" onchange="ValorCalificacionSlide(this.value)">
                                </div>
                                <div id="seccionBtnCalificar" class="row mt-3 justify-content-center">
                                    <button type="button" class="btn" style="background:#d90000; color: white; width: 150px;" onclick="GuardarCalificacion();">Calificar</button>
                                </div>
                            </div>
                        </div>
                        <div class="container text-center" style="overflow: hidden; width: 100%; height: 95%">
                            <iframe id="Visualizador_Contenido" style="/* top: 0; *//* left: 0; *//* bottom: 0; *//* right: 0; */width: calc(100% - 30%);height: 100%;" class="text-center"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CARROUSEL -->
    <div class="modal fade" tabindex="-1" id="detalleArchivosModalFotos">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <h1 class="card-title w-100 text-center" style="color:  #d90000">EVALUACIÓN DE DOCUMENTOS</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <div style="display: flex; align-items: stretch; height: 100%;  padding: 20px 5px;">
                        <div id="calificacionArchivo" class="text-center mb-5" style="width: 30%; /* padding: 20px; */">
                            <div class="col text-center">
                                <div class="row mt-3">
                                    <label for="customRange1" class="form-label" id="labelCalificacionFotos"></label>
                                </div>
                                <div class="row mt-3 text-center justify-content-center">
                                    <div>
                                        <label for="calificacion" style="font-family: 'Rubik', sans-serif; font-weight: 400;">Calificación</label>
                                    </div>
                                    <div class="number-input-container">
                                        <input id="minusFotos" type="button" value="-" class="minus">
                                        <input id="inputCalifFotos" type="number" min="0" max="100" step="1" value="1" style="text-align:center;">
                                        <input id="plusFotos" type="button" value="+" class="plus">
                                    </div>
                                </div>
                                <div class="row mt-3 justify-content-center">
                                    <input id="inputCalifSlide" type="range" class="form-range" min="0" max="100" step="1" value="1" style=" width:400px;" onchange="ValorCalificacionSlideFotos(this.value)">
                                </div>
                                <div id="seccionBtnCalificar" class="row mt-3 justify-content-center">
                                    <button type="button" class="btn" style="background:#d90000; color: white; width: 150px;" onclick="GuardarCalificacionFotos();">Calificar</button>
                                </div>
                            </div>
                        </div>
                        <div class="container text-center" style="overflow: hidden; width: 100%; height: 95%" id="detallesDiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<style>
    .accordion-button,
    .accordion-button.collapsed,
    .accordion-button:hover {
        border: 1px solid #dedede !important;
    }

    .accordion-button {
        /* border-radius: 15px; */
        font-weight: bold;
    }

    .modal-fullscreen {
        width: 100%;
        height: 100%;
        max-width: 100%;
        margin: 0;
    }

    .carousel-item {
        height: 90vh;
    }

    .carousel-item img {
        max-width: 95%;
        /* height: 100%; */
        object-fit: cover;
    }

    .btn-circle-rot {
        width: 50px;
        height: 50px;
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 25px;
    }

    /* .carousel-control-prev-icon {
        color: red;
    }

    .carousel-control-next-icon {
        color: red;
    } */
</style>
<?php require_once('../../include/footer.php'); ?>
<?php echo '<script src="' . $rutaServer . 'js/funciones_Pagina1.js"></script>'; ?>