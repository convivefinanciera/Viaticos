<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Numero de tarjeta / Folio anterior</label>
        <input type="number" class="form-control" id="tarjeta_a" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="16">
</div>
<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
<button type="button" onclick="clearDataCard('#TarjetasRenReem', '#btarjetaReem', '#myModalSCrdReem');" class="btn btn-info" id="buscar">Buscar tarjeta</i></button>
</div>
<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Numero de tarjeta / Folio nuevo</label>
        <input type="number" class="form-control" id="tarjeta_n" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="16">
</div>
<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <button type="button" class="btn btn-success" id="aplicar" onclick="validarCampos('reemplazo');">Reemplazar tarjeta</i></button>
</div>

<!-- inicio Modal para cargar cliente -->
<div class="modal fade" id="myModalSCte" role="dialog" hidden>
    <div class="modal-dialog modalBuscarCliente">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="mtitle" class="modal-title">Buscar Tarjeta</h4>
            </div>
            <div class="modal-body bodyBuscar">
                <input id="crid" name="crid" value="" type="hidden">
                <table class="table">
                    <thead>                                
                        <tr>
                            <td colspan="3" style="text-align: left;">
                                <div class="form-inline"> 
                                    <label id="bamigolb" for="bamigo" class="inp">
                                        <input id="bamigo" name="bamigo" type="text" placeholder="&nbsp;" 
                                                oninput="buscarTarjeta('searchTarjxCli')" value="" style="text-transform: capitalize;"  autofocus/>
                                        <span class="label">Nombre</span>
                                        <span class="border"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody id="ClientesRen" style="overflow: auto;"> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- fin modal para cargar cliente -->

<!-- inicio modal verificar activación -->
<div id="idModalElegirEstadoReemplazo" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">                
            <div class="modal-body">
                <p id="idPregreem"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="idCambiarEstatus" onclick="reemplazarTDC()">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert" id="alert_success_change" style="display: none;">
    Cambio de tarjeta realizado correctamente
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- inicio Modal para cargar cliente -->
<div class="modal fade" id="myModalSCrdReem" role="dialog">
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
                                    <label id="btarjetaReemlb" for="btarjetaReem" class="inp">
                                        <span class="label">Nombre</span>
                                        <input id="btarjetaReem" name="btarjetaReem" type="text" placeholder="&nbsp;"
                                               oninput="buscarTarjeta('searchTarjxCli', '#btarjetaReem', '#TarjetasRenReem', '#myModalSCrdReem', '#tarjeta_a')" value="" style="text-transform: capitalize;" autofocus/>
                                        <span class="border"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody id="TarjetasRenReem" style="overflow: auto;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- fin modal para cargar cliente -->