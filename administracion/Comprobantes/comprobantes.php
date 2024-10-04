<?php
require_once('../../inicio.php');
?>

<main id="main" class="main">
    <div class="modal fade" tabindex="-1" id="detalleComprobante">
    <div class="modal-dialog modal-lg"> <!-- Cambiado a modal-lg para aumentar el tamaño -->
        <div class="modal-content my-0 p-0">
            <div class="modal-body p-2" style="max-height: 80vh; overflow-y: auto;"> <!-- Limitar la altura -->
                <p style="position: sticky; top: 0; z-index: 2; background-color: white; padding: 10px 0; display: grid; grid-template-columns: repeat(2, 49%); grid-template-rows: auto; gap: 1%;">
                    <span id="numTarjeta"></span>
                    <span id="nombreComprobante"></span>
                    <span id="tipoComprobante"></span>
                    <span id="tipoMovimiento"></span>
                </p>
                <div id="mostrarComprobante" style="max-height: 70vh;"> <!-- Limitar el área de visualización del archivo --></div>
            </div>
        </div>
    </div>

    </div>
    <!-- <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('tablaLineasCredito')">Exportar a Excel</button> -->
    <div class="row">
        <div>
            <div class="card-header">
                <h1 class="card-title text-center fw-bold" style="color: #F15A26">CONSUMOS</h1>
            </div>
        </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="card mt-3">
                        <h5 class="card-header">
                            <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('tablaComprobantes')">Exportar a Excel</button>
                            <div class="float-end">
                                <button type="button" class="btn btn-success me-1" id="btn_tablasConsumos"
                                    onclick="MostrarComprobantes()">
                                    Actualizar
                                </button>
                            </div>
                        </h5>
                        <div class="table-responsive">
                            <table class="table text-center table-striped" id="tablaComprobantes" style="font-size: 14px;">
                                <thead>
                                    <tr>
                                    <th scope="col" style="width: 10%">Tarjeta</th>
                                        <th scope="col" style="width: 15%">Usuario</th>
                                        <th scope="col" style="width: 10%">Fecha operación</th>
                                        <th scope="col" style="width: 10%">Tipo</th>
                                        <th scope="col" style="width: 10%"># Transacción</th>
                                        <th scope="col" style="width: 10%">Tipo Movimiento</th>
                                        <th scope="col" style="width: 10%">Monto</th>
                                        <th scope="col" style="width: 5%">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyComprobantes">
                                    <!-- Aquí se añadirán las filas -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php echo '<script src="' . $rutaServer . 'js/comprobantes.js"></script>'; ?>
</main>

</html>