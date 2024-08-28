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
            <h2 class="card-title text-center fw-bold" style="color: #d90000">MONITOR DE CONTRATOS</h2>
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
    <!--  -->
    <ul class="nav nav-tabs nav-justified" id="NavegadorTablas" name="NavegadorTablas" role="tablist">
        <!-- <li class="nav-item" id="Navegador_generarContrato" name="NavegadorSeccion" role="presentation" onclick=""><a class="nav-link active" style="color: #404140" id="a_NoVERIFICADAS" name="seccioonn">Contratos Por Generar</a></li> -->
        <li class="nav-item" id="Navegador_EnProceso" name="NavegadorSeccion" role="presentation" onclick="">
            <a class="nav-link active" style="color: #404140" id="tablacontratos-tab" name="seccioonn" data-bs-toggle="tab" data-bs-target="#divTabla" role="tab" aria-controls="divTabla" aria-selected="true" onclick="CargarRegistros('enproceso', 1)">Contratos En Proceso</a>
        </li>
        <li class="nav-item" id="Navegador_Finalizadas" name="NavegadorSeccion" role="presentation" onclick="">
            <a class="nav-link" style="color: #404140" id="tablacontratoscompletos-tab" name="seccioonn" data-bs-toggle="tab" data-bs-target="#contatosCompletos" role="tab" aria-controls="contatosCompletos" aria-selected="false" onclick="CargarRegistros('finalizado', 1)">Contratos Completados</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-12 col-sm-12 pb-2 pt-3 px-4 d-md-flex justify-content-md-end bg-white">
            <button class="btn btn-circle btn-success btn-sm" onclick="ActualizarRegistros()">Actualizar</button>
        </div>
        <div class="col-md-12 col-sm-12 m-bottom bg-white tab-content">
            <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="tablacontratos-tab" style="width: 100%;" id="divTabla">
                <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('TablaContratos')">Exportar a Excel</button>
                <!-- <div class="container mt-3"> -->
                <table class="table table-striped cell-border display compact text-center" id="TablaContratos" style="width:100%; font-size: 14px;">
                    <thead>
                        <tr style="width: 100%;">
                            <th scope="col" style="width: 1%">#</th>
                            <th scope="col" style="width: 3%;">SolicitudID</th>
                            <th scope="col" style="width: 3%;">ClienteID</th>
                            <th scope="col" style="width: 22%;">Nombre Cliente</th>
                            <th scope="col" style="width: 3%;">Tipo persona</th>
                            <th scope="col" style="width: 3%;">Monto Autorizado</th>
                            <th scope="col" style="width: 3%;">Monto Solicitado</th>
                            <th scope="col" style="width: 10%;">Celular</th>
                            <th scope="col" style="width: 3%;">Total Firmas</th>
                            <th scope="col" style="width: 3%;">Firmas</th>
                            <!-- <th scope="col" style="width: 10%;">Estatus</th> -->
                            <th scope="col" style="width: 8%;">Fecha Creación</th>
                            <th scope="col" style="width: 5%;">Acciones</th>
                            <!-- <th scope="col" style="width: 3%;">Validar</th> -->
                        </tr>
                    </thead>
                    <tbody id="bodyTablaContratos">
                    </tbody>
                </table>
                <!-- </div> -->
            </div>
            <div class="tab-pane fade" role="tabpanel" aria-labelledby="tablacontratoscompletos-tab" id="contatosCompletos">
                <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('TablaContratosCompletos')">Exportar a Excel</button>
                <table class="table table-striped cell-border display compact text-center" id="TablaContratosCompletos" style="width:100%; font-size: 14px;">
                    <thead>
                        <tr style="width: 100%;">
                            <th scope="col" style="width: 1%">#</th>
                            <th scope="col" style="width: 3%;">SolicitudID</th>
                            <th scope="col" style="width: 3%;">ClienteID</th>
                            <th scope="col" style="width: 22%;">Nombre Cliente</th>
                            <th scope="col" style="width: 3%;">Tipo persona</th>
                            <th scope="col" style="width: 3%;">Monto Autorizado</th>
                            <th scope="col" style="width: 3%;">Monto Solicitado</th>
                            <th scope="col" style="width: 10%;">Celular</th>
                            <th scope="col" style="width: 11%;">Fecha Creación</th>
                            <th scope="col" style="width: 3%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaContratos">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
</html>
<?php 
    echo '<script src="' . $rutaServer . 'js/monitorContratos.js"></script>'; 
    require("../../include/footer.php");
?>