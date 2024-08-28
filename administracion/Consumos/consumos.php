<?php
require_once('../../inicio.php');
?>

<!-- <script src="js/consumos.js"></script> -->
<?php echo '<script src="' . $rutaServer . 'js/consumos.js"></script>'; ?>

<main id="main" class="main">
    <div>
        <div class="card-header">
            <h1 class="card-title text-center fw-bold" style="color: #d90000">CONSUMOS</h1>
        </div>
    </div>
    <body  onload="Mostrar_ReporteConsumos(); funcionConsumos();">
    <div class="container-fluid">
        <div class="row">
                <ul class="nav nav-tabs nav-justified mb-3" id="NavegadorTablas_Consumos" name="NavegadorTablas_Consumos"
                    role="tablist">
                </ul>
                <div class="card">
                    <h5 class="card-header">
                        <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('example')">Exportar a Excel</button>
                        <div class="float-end">
                            <button type="button" class="btn btn-success me-1" id="btn_tablasConsumos"
                                onclick="Mostrar_ReporteConsumos()">
                                Actualizar
                            </button>
                        </div>
                    </h5>
                    <div class="card-body">
                        <div id="div_tablaConsumos"></div>
                    </div>
                </div>
        </div>
    </div>
</main>