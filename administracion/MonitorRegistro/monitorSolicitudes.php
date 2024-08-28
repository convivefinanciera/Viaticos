<?php
require_once('../../inicio.php');
echo '<link rel="stylesheet" href="' . $rutaServer . 'css/monitorContratos.css">';
echo "<style>
    #ContratosModal.desembolso .modal-dialog {
        max-width: 40vw !important;
    }
    #ContratosModal.lineacredito .modal-dialog {
        max-width: fit-content !important;
    }
    #ContratosModal.desembolso .modal-dialog .modal-content, 
    #ContratosModal.lineacredito .modal-dialog .modal-content {
        height: auto !important;
    }
    #ContratosModal .modal-dialog .modal-content #message {
        display: none;
    }
    #ContratosModal.desembolso .modal-dialog .modal-content #message,
    #ContratosModal.lineacredito .modal-dialog .modal-content #message {
        display: block
    }
    #ContratosModal.desembolso .modal-dialog .modal-content #Visualizador_Contrato,
    #ContratosModal.lineacredito .modal-dialog .modal-content #Visualizador_Contrato {
        display: none;
    }
    .button-secondary {
        border: 1px solid #4c4c4c;
        color: #4c4c4c;
        transition: all .3s
    }
    .button-secondary:hover {
        border-color: #333;
        color: inherit;
    }
</style>";
?>
<?php echo '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">'; ?>
<main id="main" class="main" data_role="<?= $_SESSION['role']?>">
    <!-- Modal para visualizar el contrato -->
    <div class="modal" tabindex="-1" id="ContratosModal">
        <div class="modal-dialog">
            <div class="modal-content my-0 p-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="Nombre_Contrato"></h5>
                    <button id="closeModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <p id="message"></p>
                    <iframe id="Visualizador_Contrato"></iframe>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div> -->
            </div>
        </div>
    </div>
    <!--  -->
    <div>
        <div class="card-header">
            <h2 class="card-title text-center fw-bold" style="color: #d90000">MONITOR DE SOLICITUDES</h2>
        </div>
    </div>
    <ul class="nav nav-tabs nav-justified mb-3" id="NavegadorTablas" name="NavegadorTablas" role="tablist">
        <?php if (((int)$_SESSION['role'] >= 1 and (int)$_SESSION['role'] <= 3) or (int)$_SESSION['role'] == 9 or (int)$_SESSION['role'] == 11): ?>
            <li class="nav-item" id="Navegador_PorVerificar" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('RECIENTES')"><a class="nav-link active" aria-selected="true" style="color: #404140" id="sol_recientes" data-bs-toggle="tab" role="tab" name="seccioonn">Recientes</a></li>
        <?php endif; ?>
        <?php if (((int)$_SESSION['role'] >= 4 and (int)$_SESSION['role'] <= 5) or (int)$_SESSION['role'] == 9 or (int)$_SESSION['role'] == 11): ?>
            <li class="nav-item" aria-selected="true" id="Navegador_Verificadas" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('5')"><a class="nav-link" aria-selected="true" style="color: #404140" id="sol_revision" data-bs-toggle="tab" role="tab" name="seccioonn">En revisión</a></li>
            <li class="nav-item" aria-selected="false" id="Navegador_OtrosServicios" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('A')"><a class="nav-link" aria-selected="false" style="color: #404140" id="sol_autorizadas" data-bs-toggle="tab" role="tab" name="seccioonn">Autorizadas</a></li>
        <?php endif; ?>
        <?php if ((int)$_SESSION['role'] === 8 or (int)$_SESSION['role'] == 9 or (int)$_SESSION['role'] == 11): ?>
            <li class="nav-item" aria-selected="true" id="Navegador_NoVerificadas" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('D')"><a class="nav-link" aria-selected="true" style="color: #404140" id="sol_aprobadas" data-bs-toggle="tab" role="tab" name="seccioonn">Desembolsadas</a></li>
        <?php endif; ?>
        <?php if (((int)$_SESSION['role'] >= 4 and (int)$_SESSION['role'] <= 5) or (int)$_SESSION['role'] == 9 or (int)$_SESSION['role'] == 11): ?>
            <li class="nav-item" aria-selected="false" id="Navegador_Condonaciones" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('C')"><a class="nav-link" aria-selected="false" style="color: #404140" id="sol_canceladas" data-bs-toggle="tab" role="tab" name="seccioonn">Canceladas</a></li>
        <?php endif; ?>
    </ul>
    <div class="row">
        <div class="col-md-12 col-sm-12 m-bottom">
            <div class="card-body" style="width: 100%;" id="divTabla">
            <button class="btn btn-success me-2" id="exportButton">Exportar a Excel</button>
                <!-- <div class="container mt-3"> -->
                <table class="table table-striped cell-border display compact text-center" id="tablaSolicitudes" style="width:100%; font-size: 14px;">
                    <thead>
                        <tr style="width: 100%;">
                            <th scope="col" style="display:none">ID</th>
                            <th scope="col" style="width: 10%;">Folio</th>
                            <th scope="col" style="width: 22%;">Nombre o Razón social</th>
                            <th scope="col" style="width: 10%;">Monto solicitado</th>
                            <th scope="col" style="width: 10%;">Monto autorizado</th>
                            <th scope="col" style="width: 15%;">Sucursal</th>
                            <th scope="col" style="width: 15%;">Ejecutivo</th>
                            <th scope="col" style="width: 16%;">Fecha alta</th>
                            <th scope="col" style="width: 3%;">Avance</th>
                            <!-- <th scope="col" style="width: 10%;">Estatus</th> -->
                            <th scope="col" style="width: 5%;">Calificación</th>
                            <th scope="col" style="width: 3%;">Detalle</th>
                            <th scope="col" style="width: 10%;">Fecha modificación</th>
                            <th scope="col" style="width: 10%;">Contrato</th>
                            <th scope="col" style="width: 3%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaSolicitudes">
                    </tbody>
                </table>
                <!-- </div> -->
            </div>
        </div>
    </div>

</main>

</html>
<?php echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>' ?>
<?php echo '<script src="' . $rutaServer . 'js/monitorRegistro.js"></script>'; ?>