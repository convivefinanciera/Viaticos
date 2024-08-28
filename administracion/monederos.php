<?php
require_once('inicio.php');
?>

<script src="js/monederos.js"></script>

<main id="main" class="main">
    
    <div>
        <div class="card-header">
            <h1 class="card-title text-center fw-bold" style="color: #d90000">MONEDEROS DISPERSADOS</h1>
        </div>
    </div>
    <div class="row">
        <!-- Modal para visualizar el contrato -->
        
        <table class="table text-center table-striped" id="tablaLineasCredito" style="font-size: 14px;">
            <thead>
                <tr>
                    <th scope="col">Número de cliente</th>
                    <th scope="col">Nombre Cliente</th>
                    <th scope="col">Tarjeta</th>
                    <th scope="col">Estatus</th>
                    <th scope="col">Saldo</th>
                    <th scope="col">Saldo Disponible</th>
                    <th scope="col">Saldo Bloqueado</th>
                    <th scope="col">Ver Detalle</th>
                </tr>
            </thead>
            <tbody id="bodyLineasCredito">
               
            </tbody>
        </table>
    </div>

    <!-- <div class="container margin-top-1"> -->
    <!-- <div class="row mt-2">
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Recientes</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">En revisión</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Aprobadas</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Autorizadas</button></div>
            <div class="col text-center"><button class="btn" type="button" style="background-color: #d90000; color:white;">Canceladas</button></div>
        </div> -->
    <!-- </div> -->
     
</main>

</html>