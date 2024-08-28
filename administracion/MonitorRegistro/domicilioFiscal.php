<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <!-- <div> -->
                    <div class="row my-4">
                        <div class="col text-center">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="1" id="mismaDireccionParticular3" onchange="MismaDireccionParticular3(this);">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Misma dirección que domicilio particular
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                    <form>
                        <div class="mb-3">
                            <label for="calleF" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="calleF" placeholder="Calle">
                        </div>
                        <div class="mb-3">
                            <label for="numExteriorF" class="form-label">Número exterior</label>
                            <input type="text" class="form-control" id="numExteriorF" placeholder="Número exterior">
                        </div>
                        <div class="mb-3">
                            <label for="numInteriorF" class="form-label">Número interior</label>
                            <input type="text" class="form-control" id="numInteriorF" placeholder="Número interior">
                        </div>
                        <div class="mb-3">
                            <label for="CPF" class="form-label">Código postal</label>
                            <input type="text" class="form-control" id="CPF" placeholder="CP" maxlength="5">
                        </div>
                        <div class="mb-3">
                            <label for="coloniaF" class="form-label">Colonia</label>
                            <select class="form-control" id="coloniaF" placeholder="Selecciona">
                                <option value="D" selected>Selecciona colonia...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="municipioF" class="form-label">Municipio</label>
                            <input type="text" class="form-control" id="municipioF" placeholder="Municipio">
                        </div>
                        <div class="mb-3">
                            <label for="estadoF" class="form-label">Estado</label>
                            <select id="estadoF" class="form-select">
                                <option value="D" selected>Selecciona Estado...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ciudadF" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudadF" placeholder="Ciudad">
                        </div>
                        <div class="mb-3">
                            <label for="antiguedadF" class="form-label">Antigüedad (en años)</label>
                            <input type="number" class="form-control" id="antiguedadF" placeholder="Antigüedad">
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" id="prev2" class="btn btn-secondary me-2" onclick="regresar_Pagina3()">Regresar</button>
                            <button type="button" class="btn btn-success me-2" id="btn_save" onclick="GuardarInfo_Pagina3()">Guardar</button>
                            <button type="button" id="next3" class="btn btn-primary me-2" onclick="continuar_Pagina3()">Continuar</button>
                            <button type="button" class="btn btn-danger" onclick="cerrar_Pagina3()">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>