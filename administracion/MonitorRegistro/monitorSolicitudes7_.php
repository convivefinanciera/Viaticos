<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3 text-center">
                        <p for="firstName" class="form-label">¿Se visitó al cliente?</p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionesVisitoCliente" id="visitoSi" value="si">
                            <label class="form-check-label" for="facturaESi">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionesVisitoCliente" id="visitoNo" value="no">
                            <label class="form-check-label" for="facturaENo">No</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputGiro" class="form-label">Giro</label>
                        <input type="text" class="form-control" id="inputGiro" placeholder="Giro">
                    </div>
                    <div class="mb-3">
                        <label for="inputNivel" class="form-label">Nivel</label>
                        <input type="text" class="form-control" id="inputNivel" placeholder="Nivel">
                    </div>
                    <div class="mb-3">
                        <label for="inputQuienVisito" class="form-label">¿Quién lo visitó?</label>
                        <input type="text" class="form-control" id="inputQuienVisito" placeholder="¿Quién lo visitó?">
                    </div>
                    <div class="mb-3">
                        <label for="inputZona" class="form-label">Zona</label>
                        <input type="text" class="form-control" id="inputZona" placeholder="Zona">
                    </div>
                    <div class="mb-3">
                        <label for="inputListaPrecios" class="form-label">Lista de precios</label>
                        <input type="text" class="form-control" id="inputListaPrecios" placeholder="Zona">
                    </div>
                    <div class="mb-3">
                        <label for="inputProductosConsume" class="form-label">¿Qué productos consume?</label>
                        <input type="text" class="form-control" id="inputProductosConsume" placeholder="¿Qué productos consume?">
                    </div>
                    <div class="mb-3">
                        <label for="inputProductosVender" class="form-label">¿Qué productos le vamos a vender?</label>
                        <input type="text" class="form-control" id="inputProductosVender" placeholder="¿Qué productos le vamos a vender?">
                    </div>
                    <div class="mb-3">
                        <label for="inputProyeccionVentas" class="form-label">Proyección de venta (Toneladas)</label>
                        <input type="text" class="form-control" id="inputProyeccionVentas" placeholder="Proyección de venta (Toneladas)">
                    </div>
                    <div class="mb-3">
                        <label for="inputOtrosProveedores" class="form-label">¿A qué otros proveedores les compra acero?</label>
                        <input type="text" class="form-control" id="inputOtrosProveedores" placeholder="¿A qué otros proveedores les compra acero?">
                    </div>
                    <div class="mb-3">
                        <label for="inputConsumoAprox" class="form-label">Consumo aproximado</label>
                        <input type="text" class="form-control" id="inputConsumoAprox" placeholder="Consumo aproximado">
                    </div>
                    <div class="mb-3">
                        <label for="inputProyectoEspecial" class="form-label">Proyecto especial o frecuente</label>
                        <input type="text" class="form-control" id="inputProyectoEspecial" placeholder="Proyecto especial o frecuente">
                    </div>
                    <br>
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2">Regresar</button>
                        <button type="button" class="btn btn-success me-2" onclick="GuardarInfo_Pagina7()">Guardar</button>
                        <button type="button" class="btn btn-primary me-2">Continuar</button>
                        <button type="button" class="btn btn-danger">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>