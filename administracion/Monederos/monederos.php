<?php
require_once('../../inicio.php');
echo "<style>
    .bg-white {
        background-color: #fff!important;
    }
    /* MODAL VISUALIZADOR DE CONTRATOS */
    #LineasCredito .modal-dialog {
        height: 90vh;
        max-width: 90vw;
        margin: 5vh auto;
    }
    #LineasCredito .modal-dialog .modal-content {
        width: 100%;
        height: 90vh;
    }
    #LineasCredito .modal-dialog .modal-content .modal-body iframe {
        width: 100%;
        height: 100%;
    }
    </style>"
?>

<main id="main" class="main">
    <div class="modal" tabindex="-1" id="LineasCredito">
        <div class="modal-dialog">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="">Detalle Línea de Crédito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2" style="overflow-y: scroll; padding-top: 0!important;">
                    <p style="position: sticky; top: 0;z-index: 2;background-color: white; padding: 10px 0; display: grid; grid-template-columns: repeat(2, 49%); grid-template-rows: auto; gap: 1%">
                        <span id="nombreCliente"></span>
                        <span id="numeroCliente"></span>
                        <span id="LineaCreditoCliente"></span>
                        <span id="NumeroCuentaCliente"></span>
                    </p>
                    <table class="table table-striped cell-border display compact text-center" id="tableDetalle" style="width:100%; font-size: 14px;">
                        <thead>
                            <tr style="width: 100%">
                                <th scope="col" style="">Crédito consumo</th>
                                <th scope="col" style="">Monto consumo</th>
                                <th scope="col" style="">Estatus</th>
                                <th scope="col" style="">Fecha de consumo</th>
                                <th scope="col" style="">Fecha de liquidación</th>
                                <th scope="col" style="">Fecha de Pago</th>
                                <th scope="col" style="width: 4%">Días transcurridos</th>
                            </tr>
                        </thead>
                        <tbody id="tableDetallesLineaCredito"></tbody>
                    </table>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div> -->
            </div>
        </div>
    </div>
    <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('tablaLineasCredito')">Exportar a Excel</button>
    <div class="row">
    <table class="table text-center table-striped" id="tablaLineasCredito" style="font-size: 14px;">
        <thead>
            <tr>
                <th scope="col" style="width: 4%">Número de cliente</th>
                <th scope="col" style="width: 4%">Nombre Cliente</th>
                <th scope="col" style="width: 4%">Tarjeta</th>
                <th scope="col" style="width: 4%">Estatus</th>
                <th scope="col" style="width: 4%">Saldo</th>
                <th scope="col" style="width: 4%">Saldo Disponible</th>
                <th scope="col" style="width: 4%">Saldo Bloqueado</th>
                <th scope="col" style="width: 4%">Ver Detalle</th>
            </tr>
        </thead>
        <tbody id="bodyLineasCredito">
            <!-- Aquí se añadirán las filas -->
        </tbody>
    </table>
    </div>
<?php echo '<script src="' . $rutaServer . 'js/monederos.js"></script>'; ?>
</main>

</html>