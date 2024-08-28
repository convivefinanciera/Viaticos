<?php
require_once('../../inicio.php');
?>

<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- Bootstrap-Select CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css"> -->

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col text-center">
                            <div class="mb-3">
                                <p for="firstName" class="form-label">¿Su negocio cuenta con local propio?</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inputlocalPropio" id="inputlocalPropioSi" value="Si" onclick="LocalPropioCheck(this.value)" required>
                                    <label class="form-check-label" for="localPropioSi">Si</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inputlocalPropio" id="inputlocalPropioNo" value="No" onclick="LocalPropioCheck(this.value)" required>
                                    <label class="form-check-label" for="localPropioNo">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <p for="firstName" class="form-label">¿Acepta factura electrónica?</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inputfacturaElectronica" id="inputfacturaElectronicaSi" value="Si" onclick="FacturaElectronica(this.value)" required>
                                    <label class="form-check-label" for="facturaESi">Si</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inputfacturaElectronica" id="inputfacturaElectronicaNo" value="No" onclick="FacturaElectronica(this.value)" required>
                                    <label class="form-check-label" for="facturaENo">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <p for="firstName" class="form-label">¿Es filial de alguna empresa con línea de crédito?</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filialLineaCredito" id="filialLineaCreditoSi" value="Si" onclick="FilialEmpresa(this.value)" required>
                                    <label class="form-check-label" for="facturaELineaSi">Si</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filialLineaCredito" id="filialLineaCreditoNo" value="No" onclick="FilialEmpresa(this.value)" required>
                                    <label class="form-check-label" for="facturaELineaNo">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p for="firstName" class="form-label">¿Es propietario real?</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="propietarioReal" id="propietarioRealSi" value="Si" onclick="PropietarioReal(this.value)" required>
                                    <label class="form-check-label" for="propietarioReal">Si</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="propietarioReal" id="propietarioRealNo" value="No" onclick="PropietarioReal(this.value)" required>
                                    <label class="form-check-label" for="propietarioReal">No</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr class="rounded-bottom">
                    <div class="mb-3">
                        <label for="inputEmpresaFilial" class="form-label">Nombre de la empresa filial</label>
                        <input type="text" class="form-control" id="inputNombreEmpresaFilial" placeholder="Empresa Filial">
                    </div>
                    <div class="mb-3">
                        <label for="inputTiempoEstablecimiento" class="form-label">Tiempo de establecimiento (en años)</label>
                        <input type="number" class="form-control" id="inputTiempoEstablecimiento" placeholder="Tiempo de establecimiento">
                    </div>
                    <div class="mb-3">
                        <label for="inputTelefonoNegocio" class="form-label">Teléfono negocio</label>
                        <input type="number" class="form-control" maxlength="10" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="inputTelefonoNegocio" placeholder="Teléfono negocio">
                    </div>
                    <hr class="rounded-bottom">
                    <div class="mb-3">
                        <label for="inputContactoCompras" class="form-label">Nombre del contacto de compras</label>
                        <input type="text" class="form-control" id="inputNombreContactoCompras" placeholder="Contacto de compras">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div>
                                <label for="inputTelefono" class="form-label">Teléfono</label>
                                <input type="number" class="form-control" maxlength="10" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="inputTelefonoContactoCompras" placeholder="Teléfono Contacto Compras">
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <label for="inputExtension" class="form-label">Extensión</label>
                                <input type="text" class="form-control" id="inputExtensionContactoCompras" placeholder="Extensión Contacto Compras">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputCorreo" class="form-label">Correo</label>
                        <input type="text" class="form-control" id="inputCorreoCompras" placeholder="Correo Correo Compras">
                    </div>
                    <div class="mb-3">
                        <hr class="dropdown-divider">
                    </div>
                    <hr class="rounded-bottom">
                    <div class="mb-3">
                        <label for="inputContactoPagos" class="form-label">Nombre del contacto de pagos</label>
                        <input type="text" class="form-control" id="inputNombreContactoPagos" placeholder="Nombre del contacto de pagos">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div>
                                <label for="inputTelefonoContactoPagos" class="form-label">Teléfono</label>
                                <input type="number" class="form-control" maxlength="10" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="inputTelefonoContactoPagos" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <label for="inputExtensionContactoPagos" class="form-label">Extensión</label>
                                <input type="text" class="form-control" id="inputExtensionContactoPagos" placeholder="Extensión">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputCorreoContactoPagos" class="form-label">Correo</label>
                        <input type="text" class="form-control" id="inputCorreoContactoPagos" placeholder="Correo">
                    </div>
                    <div id="prospeccionConsultaBuro" class="p-4">
                        <hr class="rounded-bottom">
                        <label for="input" class="form-label">Firma de Autorización de Consulta de Buro de Crédito</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="checkAutorizacionBuro">
                            <label class="form-check-label" for="checkAutorizacionBuro">Autorizo a Convive Financiera SA de CV SOFOM ENR (En delante Convive Financiera) para que solicite, obtenga y
                                verifique mi información crediticia por una sola ocasión; declaro que conozco la naturaleza, alcance y uso que
                                Convive Financiera hará de tal información conforme a lo establecido en el artículo 28 de la Ley para Regular las Sociedades de Información Crediticia</label>
                        </div>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-naranja" id="btn_sol_firma" onclick="Prospeccion_LlamarAPI_SMS()">Solicitar Firma <i class="bi bi-envelope-arrow-up"></i></button>
                        </div>
                    </div>

                    <hr class="rounded-bottom">
                    <div class="mb-3 text-center">
                        <p for="firstName" class="form-label">¿Se visitó al cliente?</p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionesVisitoCliente" id="inputVisitoSi" value="Si" onclick="VisitaCliente(this.value)" required>
                            <label class="form-check-label" for="facturaESi">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionesVisitoCliente" id="inputVisitoNo" value="No" onclick="VisitaCliente(this.value)" required>
                            <label class="form-check-label" for="facturaENo">No</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inputNivel" class="form-label">Nivel</label>
                        <select class="form-control" id="inputNivel"> </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputQuienVisito" class="form-label">¿Quién lo visitó?</label>
                        <input type="text" class="form-control" id="inputNombreVisitor" placeholder="¿Quién lo visitó?">
                    </div>
                    <div class="mb-3">
                        <label for="zonaOpt" class="form-label">Zona</label>
                        <select class="form-control" id="zonaOpt"> </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputListaPrecios" class="form-label">Lista de precios</label>
                        <select class="form-control" id="inputListaPrecios"> </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputSector" class="form-label">Sector</label>
                        <select class="form-control" id="inputSectores"> </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputProductosConsume" class="form-label">¿Qué productos consume?</label>
                        <input type="text" class="form-control" id="inputProductosConsume" placeholder="¿Qué productos consume?">
                    </div>
                    <div class="mb-3">
                        <label for="inputProductosVender" class="form-label">¿Qué productos le vamos a vender?</label>
                        <div class="container text-left">
                            <div class="row justify-content-center">
                                <!-- <div class="col-4"> -->
                                    <select id="inputProductosVender" class="selectpicker w-100" multiple title="Selecciona los artículos">
                                        <!-- Las opciones se cargarán dinámicamente -->
                                    </select>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputProyeccionVentas" class="form-label">Proyección de venta (en toneladas)</label>
                        <input type="number" class="form-control" id="inputProyeccionVentas" placeholder="Proyección de venta (Toneladas)">
                    </div>
                    <div class="mb-3">
                        <label for="inputOtrosProveedores" class="form-label">¿A qué otros proveedores les compra acero?</label>
                        <input type="text" class="form-control" id="inputOtrosProveedores" placeholder="¿A qué otros proveedores les compra acero?">
                    </div>
                    <div class="mb-3">
                        <label for="inputConsumoAprox" class="form-label">Consumo aproximado (en pesos $)</label>
                        <input type="text" class="form-control" id="inputConsumoAprox" placeholder="Consumo aproximado" onchange="FormatoNumero(this)">
                    </div>
                    <div class="mb-3">
                        <label for="inputProyectoEspecial" class="form-label">Proyecto especial o frecuente</label>
                        <!-- <select class="form-select" id="inputProyectoEspecial"> -->
                        <select class="form-select" aria-label="Default select example" id="inputProyectoEspecial">
                            <option value="E">Especial</option>
                            <option value="F">Frecuente</option>
                        </select>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button" id="prev4" class="btn btn-secondary me-2" onclick="regresar_Pagina5()">Regresar</button>
                        <button type="button" class="btn btn-success me-2" onclick="GuardarInfo_ProspeccionCliente()">Guardar</button>
                        <button type="button" id="next5" class="btn btn-primary me-2" onclick="continuar_Pagina5()">Continuar</button>
                        <button type="button" class="btn btn-danger" onclick="cerrar_Pagina5()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> -->
<!-- Bootstrap-Select JS -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script> -->


<!-- <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"8b66026dfad76b76","serverTiming":{"name":{"cfL4":true}},"version":"2024.8.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script> -->

<style>
    .autorizo-label {
        font-weight: bold;
        /* Negritas */
        font-size: 0.9em;
        /* Tamaño de fuente un poco más pequeño */
    }

    .btn-naranja {
        background-color: rgba(64, 65, 64);
        /* Color naranja */
        color: white;
        /* Texto blanco */
        border: none;
        /* Sin borde */
        padding: 10px 20px;
        /* Espaciado interno */
        border-radius: 5px;
        /* Bordes redondeados */
        cursor: pointer;
        /* Puntero de mano */
    }

    .btn-naranja:hover {
        background-color: #d90000;
        /* Color naranja más oscuro al pasar el ratón */
        color: #fff !important;
    }

    .text-center {
        text-align: center;
        /* Centrar texto */
    }

    #btn_sol_firma {
        border: 1px solid #d90000;
    }
</style>