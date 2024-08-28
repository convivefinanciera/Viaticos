<?php 
    require_once('../../inicio.php');
    echo '<link rel="stylesheet" href="' . $rutaServer . 'css/monitorContratos.css">' 
?>
<main id="main" class="main">
    <!-- Notificacion -->
    <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center w-100">
        <div class="toast align-items-center bg-success text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" id="notification_validacion">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!--  -->
    <div>
        <div class="card-header">
            <h2 class="card-title text-center fw-bold" style="color: #d90000">MONITOR DE PAGOS</h2>
        </div>
    </div>
    <!-- Modal para visualizar el contrato -->
    <div class="modal" tabindex="-1" id="ContratosModal">
        <div class="modal-dialog">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="Nombre_Contrato"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <iframe id="Visualizador_Contrato"></iframe>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div> -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 pb-2 pt-3 px-4 d-md-flex justify-content-md-end bg-white">
            <button class="btn btn-circle btn-success btn-sm" onclick="ActualizarRegistros()">Actualizar</button>
        </div>
        <div class="col-md-12 col-sm-12 m-bottom bg-white tab-content">
            <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="tablacontratos-tab" style="width: 100%;" id="divTabla">
                <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel()">Exportar a Excel</button>
                <!-- <div class="container mt-3"> -->
                <table class="table table-striped cell-border display compact text-center" id="tablaPagos" style="width:100%; font-size: 14px;">
                    <thead>
                        <tr style="width: 100%;">
                            <th scope="col" style="width: 10%;">Linea de Crédito</th>
                            <th scope="col" style="width: 10%;">Cliente ID</th>
                            <th scope="col" style="width: 22%;">Nombre Cliente</th>
                            <th scope="col" style="width: 10%;">Credito ID</th>
                            <th scope="col" style="width: 10%;">Fecha de Pago</th>
                            <th scope="col" style="width: 10%;">Total Pago</th>
                            <th scope="col" style="width: 10%;">Capital</th>
                            <th scope="col" style="width: 10%;">Interés</th>
                            <th scope="col" style="width: 10%;">Moratorio</th>
                            <th scope="col" style="width: 10%;">IVA</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaDetalle">
                    </tbody>
                </table>

                <!-- </div> -->
            </div>
        </div>
    </div>

</main>
</html>
<?php 
    echo '<script src="' . $rutaServer . 'js/monitorPagos.js"></script>'; 
    require("../../include/footer.php");
?>