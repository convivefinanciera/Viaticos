<?php
require_once('../../inicio.php');
?>
<main id="main" class="main">
    <div>
        <div class="card-header">
            <h2 class="card-title text-center fw-bold" style="color: #d90000">MONITOR DE SOLICITUDES</h2>
        </div>
    </div>
    <ul class="nav nav-tabs nav-justified mb-3" id="NavegadorTablas" name="NavegadorTablas" role="tablist">
        <li class="nav-item" id="Navegador_PorVerificar" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('RECIENTES')"><a class="nav-link active" aria-selected="true" style="color: #404140" id="sol_recientes" data-bs-toggle="tab" role="tab" name="seccioonn">Recientes</a></li>
        <li class="nav-item" aria-selected="false" id="Navegador_Verificadas" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('5')"><a class="nav-link" aria-selected="false" style="color: #404140" id="sol_revision" data-bs-toggle="tab" role="tab" name="seccioonn">En revisión</a></li>
        <li class="nav-item" aria-selected="false" id="Navegador_NoVerificadas" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('D')"><a class="nav-link" aria-selected="false" style="color: #404140" id="sol_aprobadas" data-bs-toggle="tab" role="tab" name="seccioonn">Desembolsadas</a></li>
        <li class="nav-item" aria-selected="false" id="Navegador_OtrosServicios" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('A')"><a class="nav-link" aria-selected="false" style="color: #404140" id="sol_autorizadas" data-bs-toggle="tab" role="tab" name="seccioonn">Autorizadas</a></li>
        <li class="nav-item" aria-selected="false" id="Navegador_Condonaciones" name="NavegadorSeccion" role="presentation" onclick="CargarSolicitudes('C')"><a class="nav-link" aria-selected="false" style="color: #404140" id="sol_canceladas" data-bs-toggle="tab" role="tab" name="seccioonn">Canceladas</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12 col-sm-12 m-bottom">
            <div class="card-body" style="width: 100%;" id="divTabla">
                <!-- <div class="container mt-3"> -->
                <table class="table table-striped cell-border display compact text-center" id="tablaSolicitudes" style="width:100%; font-size: 14px;">
                    <thead>
                        <tr style="width: 100%;">
                            <th scope="col" style="display:none">ID</th>
                            <th scope="col" style="width: 10%;">Folio</th>
                            <th scope="col" style="width: 22%;">Nombre o Razón social</th>
                            <th scope="col" style="width: 10%;">Monto solicitado</th>
                            <th scope="col" style="width: 15%;">Sucursal</th>
                            <th scope="col" style="width: 15%;">Ejecutivo</th>
                            <th scope="col" style="width: 16%;">Fecha alta</th>
                            <th scope="col" style="width: 3%;">Avance</th>
                            <!-- <th scope="col" style="width: 10%;">Estatus</th> -->
                            <th scope="col" style="width: 5%;">Observaciones</th>
                            <th scope="col" style="width: 10%;">Fecha modificaciones</th>
                            <th scope="col" style="width: 3%;">Detalle</th>
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

    <!-- <div class="container margin-top-1"> -->
    <!-- <div class="row mt-2">
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Recientes</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">En revisión</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Aprobadas</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Autorizadas</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Canceladas</button></div>
        </div> -->
    <!-- </div> -->
</main>
</html>
<?php echo '<script src="' . $rutaServer . 'js/monitorSolicitudes.js"></script>'; ?>