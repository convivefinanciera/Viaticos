<?php
    include("../../inicio.php");
?>
<main id="main" class="main">
    <body>
        <article>
            <p class="card-header">
                <h1 class="card-title text-center fw-bold" style="color: #d90000">LOG CONSUMOS VENTACERO</h1>
            </p>
        </article>
        <article class="container-fluid">
        <!-- <button class="btn btn-success me-2" id="exportButton" onClick="exportExcel('TablaGestion')">Exportar a Excel</button> -->
            <table class="table table-striped cell-border display compact text-center" id="TablaGestion" style="width:100%; font-size: 14px;">
                <thead>
                    <tr style="width: 100%;">
                        <th scope="col">Tarjeta</th>
                        <th scope="col">Tipo Mensaje</th>
                        <th scope="col">Tipo Operacion</th>
                        <th scope="col">Tarjeta Debito</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Numero tran</th>
                        <th scope="col">Giro Negocio</th>
                        <th scope="col">Punto de Entrada</th>
                        <th scope="col">Terminal ID</th>
                        <th scope="col">Ubicacion Terminal</th>
                        <th scope="col">NIP</th>
                        <th scope="col">Codigo Monto Op.</th>
                        <th scope="col">Monto Adicional</th>
                        <th scope="col">Monto Surcharge</th>
                        <th scope="col">Monto LoyaltyFree</th>
                        <th scope="col">Referencia</th>
                        <th scope="col">Datos Tiempo Aire</th>
                        <th scope="col">Estatus conciliacion</th>
                        <th scope="col">Folio Conciliacion</th>
                        <th scope="col">Detalle Conciliacion ID</th>
                        <th scope="col">Transaccion en linea</th>
                        <th scope="col">Check In</th>
                        <th scope="col">Codigo Aprobacion</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Empresa ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Fecha Alta</th>
                        <th scope="col">Dirección IP</th>
                        <th scope="col">Programa ID</th>
                        <th scope="col">Sucursal</th>
                        <th scope="col">Num. Transaccion</th>
                    </tr>
                </thead>
                <tbody id="bodyTablaGestion">
                </tbody>
            </table>
        </article>
    </body>
</main>
</html>
<?php 
    echo '<script src="' . $rutaServer . 'js/gestion.js"></script>'; 
    require_once ("../../include/footer.php");
?>