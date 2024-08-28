<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Numero de tarjeta / Folio</label>
        <input type="number" class="form-control" id="tarjeta" maxlength="16" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
</div>
<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Numero de cliente</label>
        <input type="text" class="form-control" id="cliente" min="0">
</div>
<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <button type="button" onclick="clearDataClient();" class="btn btn-info" id="buscar">Buscar cliente</i></button>
        <button type="button" class="btn btn-success" id="aplicar" onclick="validarCampos('activar');">Activar tarjeta</i></button>
</div>

<!-- inicio modal verificar activación -->
<div id="idModalElegirEstado" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">                
            <div class="modal-body">
                <p id="idPreg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="idCambiarEstatus" onclick="activarTDC()">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Inicio Modal para cargar cliente -->
<div class="modal fade" id="myModalSCte" role="dialog">
    <div class="modal-dialog modalBuscarCliente">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="mtitle" class="modal-title">Buscar Cliente</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bodyBuscar">
                <input id="crid" name="crid" value="" type="hidden">
                <table class="table">
                    <thead>                                
                        <tr>
                            <td colspan="3" style="text-align: left;">
                                <div class="form-inline"> 
                                    <label id="bamigolb" for="bamigo" class="inp">
                                        <span class="label">Nombre/Celular</span>
                                        <input id="bamigo" name="bamigo" type="text" placeholder="&nbsp;" 
                                                oninput="buscarCliente('searchClixProd')" value="" style="text-transform: capitalize;"  autofocus/>
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
<!-- Fin modal para cargar cliente -->

<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert" id="alert_success" style="display: none;">
    Tarjeta activada correctamente
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
