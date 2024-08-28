<?php
require_once('../../inicio.php');
?>
<?php echo '<link rel="stylesheet" href="' . $rutaServer . 'css/monitorSolicitudes.css">' ?>
<?php 
if(!isset($_GET['ID_Sol'])) //Si la liga no tiene un ID_Solicitud se resetea sesión ID_Solicitud para evitar modificar solicitudes ajenas
{ 
    $_SESSION['ID_Registro'] = '';
}
?>

<main id="main" class="main">
    <div>
        <div class="card-header">
            <h2 class="card-title text-center fw-bold" style="color: #d90000">ADMINISTRACIÓN DE TARJETAS</h2>
        </div>
    </div>
    <div id="seccionSolicitud" class="accordion" id="accordionSolicitud">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <!-- <h2 class="accordion-header" id="headerParte1"> -->
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    ASIGNACIÓN Y ACTIVACIÓN
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="headerParte1" data-bs-parent="#accordionSolicitud">
                <div class="accordion-body">
                    <?php require_once('asignacionActivacionTarjetas.php'); ?>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-5">
            <h2 class="accordion-header">
                <!-- <h2 class="accordion-header" id="headerParte2"> -->
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                    CANCELACIÓN
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="headerParte2" data-bs-parent="#accordionSolicitud">
                <div class="accordion-body">
                    <?php require_once('cancelacionTarjetas.php'); ?>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-5">
            <h2 class="accordion-header">
                <!-- <h2 class="accordion-header" id="headerParte3"> -->
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                    REEMPLAZO
                </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="headerParte3" data-bs-parent="#accordionSolicitud">
                <div class="accordion-body">
                    <?php require_once('reemplazoTarjetas.php'); ?>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-5">
            <h2 class="accordion-header">
                <!-- <h2 class="accordion-header" id="headerParte3"> -->
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                    INVENTARIO
                </button>
            </h2>
            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="headerParte4" data-bs-parent="#accordionSolicitud">
                <div class="accordion-body">
                    <?php require_once('inventarioTarjetas.php'); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<style>

</style>


<?php echo '<script src="../../js/toast.js"></script>'; ?>
<?php echo '<script src="../../js/tarjetas.js"></script>'; ?>
<?php echo '<script src="../../librerias/growl-alert-box/alert.js"></script>'; ?>
<?php require_once('../../include/footer.php'); ?>