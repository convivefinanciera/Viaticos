<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="row my-4">
                        <div class="col text-center">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mismaDireccion" type="radio" value="1" id="mismaDireccionParticular4" onchange="mismaDireccion(this);">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Misma dirección que domicilio particular
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mismaDireccion" type="radio" value="2" id="mismaDireccionFiscal4" onchange="mismaDireccion(this);">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Misma dirección que domicilio fiscal
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mismaDireccion" type="radio" value="3" id="otro" onchange="mismaDireccion(this);">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Otra Dirección
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="calleN" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="calleN" placeholder="Calle">
                    </div>
                    <div class="mb-3">
                        <label for="numExteriorN" class="form-label">Número exterior</label>
                        <input type="text" class="form-control" id="numExteriorN" placeholder="Número exterior">
                    </div>
                    <div class="mb-3">
                        <label for="numInteriorN" class="form-label">Número interior</label>
                        <input type="text" class="form-control" id="numInteriorN" placeholder="Número interior">
                    </div>
                    <div class="mb-3">
                        <label for="CPN" class="form-label">Código postal</label>
                        <input type="text" class="form-control" id="CPN" placeholder="CP" maxlength="5">
                    </div>
                    <div class="mb-3">
                        <label for="coloniaN" class="form-label">Colonia</label>
                        <select class="form-control" id="coloniaN" placeholder="Selecciona">
                            <option value="Selecciona" selected>Selecciona colonia...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="municipioN" class="form-label">Municipio</label>
                        <input type="text" class="form-control" id="municipioN" placeholder="Municipio">
                    </div>
                    <div class="mb-3">
                        <label for="estadoN" class="form-label">Estado</label>
                        <select id="estadoN" class="form-select">
                            <option selected>Selecciona...</option>
                            <option>California</option>
                            <option>Texas</option>
                            <option>Florida</option>
                            
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ciudadN" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudadN" placeholder="Ciudad">
                    </div>
                    <div class="mb-3">
                        <label for="antiguedadN" class="form-label">Antigüedad (en años)</label>
                        <input type="number" class="form-control" id="antiguedadN" placeholder="Antigüedad">
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button"  id="prev3" class="btn btn-secondary me-2" onclick="regresar_Pagina4()">Regresar</button>
                        <button type="button" class="btn btn-success me-2" id="btn_save4" onclick="GuardarInfo_Pagina4()">Guardar</button>
                        <button type="button" id="next4" class="btn btn-primary me-2" onclick="continuar_Pagina4()">Continuar</button>
                        <button type="button" class="btn btn-danger" onclick="cerrar_Pagina4()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
