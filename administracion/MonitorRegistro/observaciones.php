<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css"> -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons CSS y JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <h5 class="card-header">
                    <div class="float-end">
                        <button class="btn btn-success me-1" id="btn_tablasObservaciones" onclick="Mostrar_TablaObservaciones()">
                            Actualizar
                        </button>
                    </div>
                    <div class="float-end">
                        <button class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#agregarObservacionModal">
                            Agregar
                        </button>
                    </div>
                </h5>
                <div class="card-body">
                    <div id="div_tablaObservaciones"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar observaciones -->
<div class="modal fade" id="agregarObservacionModal" tabindex="-1" role="dialog" aria-labelledby="agregarObservacionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarObservacionModalLabel">Agregar Observación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <form id="formAgregarObservacion"> -->
                    <div class="form-group mb-2">
                        <label for="observacion">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="agregarObservacion()">Guardar</button>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>
