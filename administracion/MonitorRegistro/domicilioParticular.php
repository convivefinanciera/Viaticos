<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="row my-4" id="seccionEditarDomPar">
                        <div class="col text-center">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="1" id="editarDireccionParticular" onchange="EditarDireccionParticular(this);">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Editar dirección particular
                                </label>
                            </div>
                        </div>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="calleP" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="calleP" placeholder="Calle">
                        </div>
                        <div class="mb-3">
                            <label for="numExteriorP" class="form-label">Número exterior</label>
                            <input type="text" class="form-control" id="numExteriorP" placeholder="Número exterior">
                        </div>
                        <div class="mb-3">
                            <label for="numInteriorP" class="form-label">Número interior</label>
                            <input type="text" class="form-control" id="numInteriorP" placeholder="Número interior">
                        </div>
                        <div class="mb-3">
                            <label for="CPP" class="form-label">Código postal</label>
                            <input type="number" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" id="CPP" placeholder="CP" maxlength="5">
                        </div>
                        <div class="mb-3">
                            <label for="coloniaP" class="form-label">Colonia</label>
                            <select class="form-control" id="coloniaP" placeholder="Selecciona">
                                <option value="Selecciona" selected>Selecciona colonia...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="municipioP" class="form-label">Municipio</label>
                            <input type="text" class="form-control" id="municipioP" placeholder="Municipio">
                        </div>
                        <div class="mb-3">
                            <label for="estadoP" class="form-label">Estado</label>
                            <select id="estadoP" class="form-select">
                                <option selected>Selecciona...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ciudadP" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudadP" placeholder="Ciudad">
                        </div>
                        <div class="mb-3">
                            <label for="antiguedadP" class="form-label">Antigüedad (en años)</label>
                            <input type="number" class="form-control" id="antiguedadP" placeholder="Antigüedad">
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" id="prev1" class="btn btn-secondary me-2" onclick="regresar_Pagina2()">Regresar</button>
                            <button type="button" class="btn btn-success me-2" onclick="GuardarInfo_Pagina2()">Guardar</button>
                            <button type="button" id="next2" class="btn btn-primary me-2" onclick="continuar_Pagina2()">Continuar</button>
                            <button type="button" class="btn btn-danger" onclick="cerrar_Pagina2()">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>