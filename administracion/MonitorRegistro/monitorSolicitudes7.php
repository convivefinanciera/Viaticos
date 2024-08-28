<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body bg-light">
                    <div class="mb-4">
                        <div class="form-check-inline">
                            <label>1. ¿Cuenta con tarjeta de crédito?</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_TDC" id="Radio_TDC_NO" value="No" onclick="Validar_TDC(this)">
                            <label class="form-check-label" for="Radio_TDC_NO">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_TDC" id="Radio_TDC_SI" value="Si" onclick="Validar_TDC(this)">
                            <label class="form-check-label" for="Radio_TDC_SI">Si</label>
                        </div>

                        <div class="mt-2">
                            <label for="input_NumTDC" class="form-label">Número de tarjeta de crédito</label>
                            <input type="text" class="form-control" id="input_NumTDC" placeholder="Escribe el número de tarjeta de crédito" oninput="Formato_DigitosTarjeta(this)" maxlength='19' disabled>
                        </div>

                    </div>

                    <div class="mb-4">
                        <div class="form-check-inline">
                            <label>2. ¿Cuenta con crédito hipotecario</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_CreditoHipo" id="Radio_CreditoHipo_NO" value="No">
                            <label class="form-check-label" for="Radio_CreditoHipo_NO">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_CreditoHipo" id="Radio_CreditoHipo_SI" value="Si">
                            <label class="form-check-label" for="Radio_CreditoHipo_SI">Si</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check-inline">
                            <label>3. ¿Cuenta con crédito automotriz</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_CreditoAuto" id="Radio_CreditoAuto_NO" value="No">
                            <label class="form-check-label" for="Radio_CreditoAuto_NO">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_CreditoAuto" id="Radio_CreditoAuto_SI" value="Si">
                            <label class="form-check-label" for="Radio_CreditoAuto_SI">Si</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label>4. ¿Cómo considera su calificación de buró de crédito?</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_Calificacion" id="Radio_Calificacion_Mala" value="Mala">
                            <label class="form-check-label" for="Radio_Calificacion_Mala">Mala</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_Calificacion" id="Radio_Calificacion_Regular" value="Regular">
                            <label class="form-check-label" for="Radio_Calificacion_Regular">Regular</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_Calificacion" id="Radio_Calificacion_Buena" value="Buena">
                            <label class="form-check-label" for="Radio_Calificacion_Buena">Buena</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_Calificacion" id="Radio_Calificacion_Excelente" value="Excelente">
                            <label class="form-check-label" for="Radio_Calificacion_Excelente">Excelente</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="Radios_Calificacion" id="Radio_Calificacion_Desconozco" value="Desconozco el dato">
                            <label class="form-check-label" for="Radio_Calificacion_Desconozco">Desconozco el
                                dato</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <!-- <input class="form-check-input" type="checkbox" id="check_Autorizacion" onchange="Autorizar_ConsultaBuro(this)"> -->
                            <input class="form-check-input" type="checkbox" id="check_Autorizacion">
                            <label class="form-check-label" for="check_Autorizacion">
                                Acepto que Convive Financiera realice la consulta de mis registros en buró de
                                crédito.
                            </label>
                        </div>
                    </div>

                    <div class="mb-4 ">
                        <div class="text-center">
                            <label class="fw-bold">Firma de autorización de consulta</label>
                        </div>
                        <div class="text-center">
                            <img src="" class="rounded" style="display: inline-block; height: 155px; width: 155px;" alt="..." id="firma_preview" name="firma_preview">
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Solicitar Firma" onclick="Reenviar_FirmaSMS()"><i class="bi bi-pen-fill"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Buscar Firma" onclick="Buscar_FirmaAutorizacion()"><i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2">Regresar</button>
                        <button type="button" class="btn btn-success me-2" onclick="GuardarInfo_Pagina8()">Guardar</button>
                        <button type="button" class="btn btn-primary me-2">Continuar</button>
                        <button type="button" class="btn btn-danger">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>