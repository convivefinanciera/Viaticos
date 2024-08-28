let cargarAvances = false;
let montoSolicitadoModal = 0;

$(document).ready(function () {
    //$("#seccionSolicitud").hide();
    //VerificarSolicitud();
    $("#domicilioFiscalAccordion").attr('disabled', 'disabled');
    $("#domicilioNegocioAccordion").attr('disabled', 'disabled');
});

function IniciarProceso() {
    if (confirm("Estás a punto de iniciar un nuevo registro. ¿Continuar?") == true) {
        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorRegistro.php",
            data: { bandera: 'iniciar_solicitud' },
            //dataType: "json",
            success: function (response) {
                let res = JSON.parse(response);
                if (res.estatus == 200) {
                    Toastify({
                        text: "Esta solicitud se estará generando con el folio " + res.folio_solicitud + ".",
                        className: "success",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #4df755, #04c20d)",
                        }
                    }).showToast();
                    //alert("Esta solicitud se estará generando con el folio " + res.folio_solicitud);
                    $("#seccionSolicitud").show('fast');
                    $("#iniciarSolicitud").hide('fast');
                }
            }
        });
    } else {
        //$("#accordionSolicitud").hide();
    }
}

function CancelarProceso() {
    if (confirm("Estás a punto de CANCELAR el proceso de este registro. ¿Continuar?") == true) {
        window.location('../inicio.php');
    }
}

function ResetearRegistro() {
    //Borramos la Solicitud_ID de la sesión para iniciar con una nueva
    // if (confirm("Estás por iniciar un nuevo Registro de Solicitud, ¿Continuar?")) {
    let formDataR = new FormData();

    formDataR.append('bandera', 'resetearRegistro');

    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorRegistro.php",
        data: formDataR,
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        success: function (response) {
            let res = JSON.parse(response);
            if (res.estatus == 200) {
                console.log(res.mensaje);
            }
            else {
                console.log(res.mensaje);
                console.log(res.ID_Solicitud);
            }
        }
    });
    // }
}

function CargarAvancesRegistro() {
    cargarAvances = true;
}


function FinalizarRegistro(EstatusFinalizada) {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorRegistro.php",
        data: {
            bandera: 'Finalizar_Solicitud',
            Estatus_Final: EstatusFinalizada
        },
        cache: false,
        success: function (response) {
            let res = JSON.parse(response);
            if (res.estatus == 200) {
                $("#FinalizadaMontoSolicitado").val(res.montoSolicitado);
                montoSolicitadoModal = res.montoSolicitado;
                FormatoNumero(document.getElementById("FinalizadaMontoSolicitado"));
                Toastify({
                    text: res.mensaje,
                    className: res.toastClass,
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: res.toastColor,
                    }
                }).showToast();
            }
            else {
                Toastify({
                    text: res.mensaje,
                    className: res.toastClass,
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: res.toastColor,
                    }
                }).showToast();
            }
        }
    });
}
