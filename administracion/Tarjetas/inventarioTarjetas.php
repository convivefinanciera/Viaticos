<div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Resumen</label>
        <table class="table table-striped cell-border display compact text-center" id="tablaResumen" style="width:100%; font-size: 14px;">
                    <thead>
                        <tr style="width: 100%;">
                            <th scope="col" style="width: 10%;">Estado de tarjetas</th>
                            <th scope="col" style="width: 10%;">Total</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaResumen">
                    </tbody>
                </table>
</div>
<div class="col-md-12 col-sm-12 m-bottom">
    <div class="card-body" style="width: 100%;" id="divTabla">
    <label class="label" for="monto">Detalle</label><br>
    <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('tablaDetalle')">Exportar a Excel</button>
        <!-- <div class="container mt-3"> -->
        <table class="table table-striped cell-border display compact text-center" id="tablaDetalle" style="width:100%; font-size: 14px;">
            <thead>
                <tr style="width: 100%;">
                    <th scope="col" style="width: 10%;">Folio</th>
                    <th scope="col" style="width: 22%;">Numero de Tarjeta</th>
                    <th scope="col" style="width: 10%;">Estatus</th>
                    <th scope="col" style="width: 10%;">Fecha de entrega</th>
                </tr>
            </thead>
            <tbody id="bodyTablaDetalle">
            </tbody>
        </table>
        <!-- </div> -->
    </div>
</div>