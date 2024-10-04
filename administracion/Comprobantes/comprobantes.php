<?php
require_once('../../inicio.php');
// echo "<style>
//     .bg-white {
//         background-color: #fff!important;
//     }
//     /* MODAL VISUALIZADOR DE CONTRATOS */
//     #LineasCredito .modal-dialog {
//         height: 90vh;
//         max-width: 90vw;
//         margin: 5vh auto;
//     }
//     #LineasCredito .modal-dialog .modal-content {
//         width: 100%;
//         height: 90vh;
//     }
//     #LineasCredito .modal-dialog .modal-content .modal-body iframe {
//         width: 100%;
//         height: 100%;
//     }
//     </style>"
?>

<main id="main" class="main">
    <!-- <div class="modal fade" tabindex="-1" id="detalleComprobante">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; width: 90%;">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title w-100 text-center">Detalle del comprobante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <div style="display: flex; align-items: stretch; height: 100%; justify-content: space-between; padding: 20px 5px;">
                        <p style="position: sticky; top: 0;z-index: 2;background-color: white; padding: 10px 0; display: grid; grid-template-columns: repeat(2, 49%); grid-template-rows: auto; gap: 1%">
                            <span id="numTarjeta"></span>
                            <span id="nombreComprobante"></span>
                            <span id="tipoComprobante"></span>
                            <span id="tipoMovimiento"></span>
                        </p>
                        <div class="container text-center" style="overflow: hidden; width: 100%; height: 95%" id="mostrarComprobante">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="modal fade" tabindex="-1" id="detalleComprobante" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <h1 class="card-title w-100 text-center">DETALLE DEL COMPROBANTE</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <div style="display: flex; align-items: stretch; height: 100%; justify-content: space-between; padding: 20px 5px;">
                        <div class="container text-center" style="overflow: hidden; width: 100%; height: 95%">
                            <!-- <iframe id="Visualizador_Contenido" style="/* top: 0; *//* left: 0; *//* bottom: 0; *//* right: 0; */width: calc(100% - 30%);height: 100%;" class="text-center">
                            </iframe> -->
                            <div id="mostrarComprobante" style="/* top: 0; *//* left: 0; *//* bottom: 0; *//* right: 0; */width: calc(100% - 30%);height: 100%;" class="text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('tablaLineasCredito')">Exportar a Excel</button> -->
    <div class="row">
        <table class="table text-center table-striped" id="tablaComprobantes" style="font-size: 14px;">
            <thead>
                <tr>
                    <th scope="col" style="width: 15%">Archivo</th>
                    <th scope="col" style="width: 5%">Tamaño</th>
                    <th scope="col" style="width: 10%">Tipo</th>
                    <th scope="col" style="width: 10%">No. Transacción</th>
                    <th scope="col" style="width: 10%">Tipo de Movimiento</th>
                    <th scope="col" style="width: 10%">Tarjeta</th>
                    <th scope="col" style="width: 15%">Usuario</th>
                    <th scope="col" style="width: 10%">Fecha/Hora operación</th>
                    <th scope="col" style="width: 10%">Monto</th>
                    <th scope="col" style="width: 5%">Detalle</th>
                </tr>
            </thead>
            <tbody id="bodyComprobantes">
                <!-- Aquí se añadirán las filas -->
            </tbody>
        </table>
    </div>

    <?php echo '<script src="' . $rutaServer . 'js/comprobantes.js"></script>'; ?>
</main>

</html>