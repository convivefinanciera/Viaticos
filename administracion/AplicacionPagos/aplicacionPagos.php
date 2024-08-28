<?php
require_once('../../inicio.php');
// require_once('../../include/head.php');



$VectorTD = array(
  0 => array(0, "Seleccion tipo de pago"),
  1 => array(1, "PAGOS INTERBANCARIO TRANSFERENCIA ELECTRONICA SPEI"),
  2 => array(2, "PAGOS VENTANILLA BANCARIA"),
  3 => array(3, "PAGOS CORRESPONSALES OXXO"),
  4 => array(4, "PAGOS CHEQUE"),
  5 => array(5, "PAGOS EFECTIVO PRACTICAJA")
  // 6 => array(6, "REVERSA PAGOS"),
  // 7 => array(7, "REVERSA PAGOS INTERBANCARIA SERVIMEX"),
  // 8 => array(8, "REVERSA PAGOS BANCARIA SERVIMEX"),
  // 9 => array(9, "REVERSA PAGOS CORRESPONSALES SERVIMEX"),
  // 10 => array(10, "REVERSA PAGOS CHEQUE SERVIMEX"),
  // 11 => array(11, "REVERSA PAGOS EFECTIVO SERVIMEX")
);

$month = date("n");
$year = date("Y");
$diaActual = date("j");

# Obtenemos el dia de la semana del primer dia
# Devuelve 0 para domingo, 6 para sabado
$diaSemana = date("w", mktime(0, 0, 0, $month, 1, $year)) + 7;
# Obtenemos el ultimo dia del mes
$ultimoDiaMes = date("d", (mktime(0, 0, 0, $month + 1, 1, $year) - 1));

$meses = array(
  1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
  "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
);
?>


<main id="main" class="main">
  <body>
    <div>
      <div class="card-header">
        <h2 class="card-title text-center fw-bold" style="color: #d90000">APLICACION DE PAGOS</h2>
      </div>
    </div>
    
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label for="tipoDispersión" class="label" class="mb-2">Tipo de Dispersión:</label>
        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="tipoDispersion" id="tipoDispersion">
          <?php if (isset($VectorTD) && !empty($VectorTD)) : ?>
            <?php foreach ($VectorTD as $opcion) : ?>
              <?php if (is_array($opcion) && count($opcion) === 2) : ?>
                <option value="<?php echo htmlspecialchars($opcion[0]); ?>"><?php echo htmlspecialchars($opcion[1]); ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <option value="">No hay opciones disponibles</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="monto">Monto</label>
        <input type="text" class="form-control" id="monto" placeholder="Ingresa el monto $" min="0">
      </div>

      <div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="ReferenciaTicket">Referencia Ticket:</label>
        <input type="text" class="form-control" id="referencia_ticket" placeholder="Ingresa la referencia de ticket">
      </div>
      <div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <label class="label" for="fechaDisp">Fecha:</label>
        <input type="text" class="form-control" id="fechaDisp" value="<?php echo date('Y-m-d'); ?>">
      </div>
      <div class="form-group mx-auto mb-3" style="max-width: 30rem;">
        <button type="button" class="btn btn-success" id="aplicar">Enviar</button>
      </div>
    </form>
  </body>


  <?php echo '<script src="'.$rutaServer.'js/aplicacionPagos.js"></script>'; ?>
  <style>
    .title {
      text-align: center;
      color: #123057;
      font-weight: bold;
    }

    .label {
      font-weight: bold;
    }
  </style>