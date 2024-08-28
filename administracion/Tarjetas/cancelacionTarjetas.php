<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Numero de tarjeta / Folio</label>
        <input type="number" class="form-control" id="tarjetac" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="16">
</div>
<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <button type="button" onclick="clearDataCard('#TarjetasRenCan','#btarjetaCan','#myModalSCrdCan');" class="btn btn-info" id="buscar">Buscar tarjeta</i></button>
        <button type="button" class="btn btn-success" id="aplicar" onclick="validarCampos('cancelar');">Cancelar tarjeta</i></button>
</div>

<div id="idModalElegirEstadoCancelar" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">                
                <div class="modal-body">
                    <p id="idPregCancelar"></p>
                    <div id="idPanelMotivoC">
                        <hr />          
                        <h5 class="tituloMotivoC"><strong>Motivo de cancelación</strong></h5>
                        <div class="row">
                            <div class="col-md-5">
                                <select title="Motivo de cancelación" id="idSelecMotivo">
                                    <option value="1">Solicitud de cliente</option>
                                    <option value="2">Petición de área interna</option>
                                    <option value="3">Solicitud de cliente corporativo</option>                                                        
                                    <option value="4">Reporte de robo</option>
                                    <option value="5">Reporte de extravio</option>
                                    <option value="6">Otros</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-6">
                                <textarea id="idObser" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="idCambiarEstatusCancelar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <div id="tit"></div>
<div id="msj"></div>

<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert" id="alert_success_cancel" style="display: none;">
    Tarjeta cancelada correctamente
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- inicio Modal para cargar cliente -->
<div class="modal fade" id="myModalSCrdCan" role="dialog">
    <div class="modal-dialog modalBuscarCliente">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="mtitle" class="modal-title">Buscar Tarjeta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bodyBuscar">
                <input id="crid" name="crid" value="" type="hidden">
                <table class="table">
                    <thead>
                        <tr>
                            <td colspan="3" style="text-align: left;">
                                <div class="form-inline">
                                    <label id="btarjetaCanlb" for="btarjetaCan" class="inp">
                                        <span class="label">Nombre</span>
                                        <input id="btarjetaCan" name="btarjetaCan" type="text" placeholder="&nbsp;"
                                               oninput="buscarTarjeta('searchTarjxCli', '#btarjetaCan', '#TarjetasRenCan', '#myModalSCrdCan', '#tarjetac')" value="" style="text-transform: capitalize;" autofocus/>
                                        <span class="border"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody id="TarjetasRenCan" style="overflow: auto;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- fin modal para cargar cliente -->