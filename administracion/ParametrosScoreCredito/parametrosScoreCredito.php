<?php
require_once('../../inicio.php');
echo '<link rel="stylesheet" href="' . $rutaServer . 'css/monitorSolicitudes.css">'
?>
<style>
    .accordion-button, .accordion-button.collapsed, .accordion-button:hover {
        border: 1px solid #dedede!important;
    }
</style>
<main id="main" class="main">
    <div>
        <div class="card-header">
            <h2 class="card-title text-center fw-bold text-uppercase" style="color: #d90000">Parámetros y Semáforos Crédito VentAcero</h2>
        </div>
    </div>
    <section class="accordion" id="accordion_parametros">
        <article class="accordion-item mt-3">
            <h2 class="accordion-header">
                <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#score_credito" aria-expanded="true" aria-controls="score_credito">
                    Ponderación de Score Crédito
                </button>
            </h2>
            <div id="score_credito" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Ítem</th>
                                <th scope="col">Concepto</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody id="scorecreditobody"></tbody>
                    </table>
                </div>
            </div>
        </article>
        <div class="accordion-item mt-5">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#firmas" aria-expanded="false" aria-controls="firmas">
                    Monitor Firmas
                </button>
            </h2>
            <div id="firmas" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Ítem</th>
                                <th scope="col">Concepto</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody id="firmastobody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="accordion-item mt-5">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#colocacion" aria-expanded="false" aria-controls="colocacion">
                    Colocación Créditos
                </button>
            </h2>
            <div id="colocacion" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Ítem</th>
                                <th scope="col">Concepto</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody id="colocacionBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
</html>
<?php 
    echo '<script src="' . $rutaServer . 'js/parametrosScoreCredito.js"></script>'; 
    require("../../include/footer.php");
?>
