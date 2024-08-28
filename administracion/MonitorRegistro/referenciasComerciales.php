<?php
require_once('../../inicio.php');
?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        <h6 class="card-title" style="color:  #d90000">Referencia comercial 1</h4>
                    </div>
                    <div class="mb-3">
                        <label for="inputNombreProveedor1" class="form-label">Nombre del proveedor</label>
                        <input type="text" class="form-control" id="inputNombreProveedor1" placeholder="Nombre del proveedor">
                    </div>
                    <div class="mb-3">
                        <label for="inputTelefonoRefCom1" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" maxlength="10" id="inputTelefonoRefCom1" placeholder="Teléfono">
                    </div>
                    <div class="mb-3">
                        <label for="inputPlazo1" class="form-label">Plazo</label>
                        <input type="phone" class="form-control" id="inputPlazo1" placeholder="Plazo">
                    </div>
                    <div class="mb-3">
                        <label for="inputLimite1" class="form-label">Límite</label>
                        <input type="text" class="form-control" id="inputLimite1" placeholder="Límite" onchange="FormatoNumero(this)">
                    </div>

                    <div class="mb-3">
                        <h6 class="card-title" style="color:  #d90000">Referencia comercial 2</h4>
                    </div>
                    <div class="mb-3">
                        <label for="inputNombreProveedor2" class="form-label">Nombre del proveedor</label>
                        <input type="text" class="form-control" id="inputNombreProveedor2" placeholder="Nombre del proveedor">
                    </div>
                    <div class="mb-3">
                        <label for="inputTelefonoRefCom2" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" maxlength="10" id="inputTelefonoRefCom2" placeholder="Teléfono">
                    </div>
                    <div class="mb-3">
                        <label for="inputPlazo2" class="form-label">Plazo</label>
                        <input type="phone" class="form-control" id="inputPlazo2" placeholder="Plazo">
                    </div>
                    <div class="mb-3">
                        <label for="inputLimite2" class="form-label">Límite</label>
                        <input type="text" class="form-control" id="inputLimite2" placeholder="Límite" onchange="FormatoNumero(this)">
                    </div>

                    <div class="mb-3">
                        <h6 class="card-title" style="color:  #d90000">Referencia comercial 3</h4>
                    </div>
                    <div class="mb-3">
                        <label for="inputNombreProveedor3" class="form-label">Nombre del proveedor</label>
                        <input type="text" class="form-control" id="inputNombreProveedor3" placeholder="Nombre del proveedor">
                    </div>
                    <div class="mb-3">
                        <label for="inputTelefonoRefCom3" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" maxlength="10" id="inputTelefonoRefCom3" placeholder="Teléfono">
                    </div>
                    <div class="mb-3">
                        <label for="inputPlazo3" class="form-label">Plazo</label>
                        <input type="phone" class="form-control" id="inputPlazo3" placeholder="Plazo">
                    </div>
                    <div class="mb-3">
                        <label for="inputLimite3" class="form-label">Límite</label>
                        <input type="text" class="form-control" id="inputLimite3" placeholder="Límite" onchange="FormatoNumero(this)">
                    </div>
                    <br>
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button"  id="prev5" class="btn btn-secondary me-2" onclick="regresar_Pagina6()">Regresar</button>
                        <button type="button" class="btn btn-success me-2" onclick="GuardarInfo_ReferenciasComerciales()">Guardar</button>
                        <button type="button" id="next6" class="btn btn-primary me-2" onclick="continuar_Pagina6()">Continuar</button>
                        <button type="button" class="btn btn-danger" onclick="cerrar_Pagina6()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo '<script src="' . $rutaServer . 'js/referenciasComerciales.js"></script>'; ?>