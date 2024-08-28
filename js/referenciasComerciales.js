$(document).ready(function () {
    if (cargarAvances == true) {
        CargarAvances_ReferenciasComerciales();
    }
});

var inputNombreProveedor1 = document.getElementById('inputNombreProveedor1');

// Aplicar la función a los inputs
procesarInput(inputNombreProveedor1);

function GuardarInfo_ReferenciasComerciales() {
    var nombre_proveedor1 = $('#inputNombreProveedor1').val();
    var telefono_ref_com1 = $('#inputTelefonoRefCom1').val();
    var plazo1 = $('#inputPlazo1').val();
    var limite1 = $('#inputLimite1').val();
    limite1 = (limite1).replace(',', ''); /* Se usa el replace para quitarle las comas y evitar errores en el backend */
    limite1 = (limite1).replace('$', '');


    var nombre_proveedor2 = $('#inputNombreProveedor2').val();
    var telefono_ref_com2 = $('#inputTelefonoRefCom2').val();
    var plazo2 = $('#inputPlazo2').val();
    var limite2 = $('#inputLimite2').val();
    limite2 = (limite2).replace(',', ''); /* Se usa el replace para quitarle las comas y evitar errores en el backend */
    limite2 = (limite2).replace('$', '');

    var nombre_proveedor3 = $('#inputNombreProveedor3').val();
    var telefono_ref_com3 = $('#inputTelefonoRefCom3').val();
    var plazo3 = $('#inputPlazo3').val();
    var limite3 = $('#inputLimite3').val();
    limite3 = (limite3).replace(',', ''); /* Se usa el replace para quitarle las comas y evitar errores en el backend */
    limite3 = (limite3).replace('$', '');

    if (nombre_proveedor1.trim() === '' || telefono_ref_com1.trim() === '' || plazo1.trim() === '' || limite1.trim() === ''||
        nombre_proveedor2.trim() === '' || telefono_ref_com2.trim() === '' || plazo2.trim() === '' || limite2.trim() === '') {
        Toastify({
            text: "Por favor, completa todos los campos de al menos la Referencia Comercial 1 y Referencia Comercial 2 para continuar.",
            className: "error",
            duration: 5000,
            gravity: "bottom",
            position: "right",
            style: {
                background: "linear-gradient(to right, #ff3636, #de0202)",
            }
        }).showToast();
        return;
    }

    var data = {
        bandera: 'GuardarInfo_ReferenciasComerciales',

        inputNombreProveedor1: nombre_proveedor1,
        inputTelefonoRefCom1: telefono_ref_com1,
        inputPlazo1: plazo1,
        inputLimite1: limite1,

        inputNombreProveedor2: nombre_proveedor2,
        inputTelefonoRefCom2: telefono_ref_com2,
        inputPlazo2: plazo2,
        inputLimite2: limite2,

        inputNombreProveedor3: nombre_proveedor3,
        inputTelefonoRefCom3: telefono_ref_com3,
        inputPlazo3: plazo3,
        inputLimite3: limite3
    };

    $.ajax({
        url: '../../Controllers/referenciasComerciales.php',
        type: 'POST',
        data: data,
        success: function (response) {
            var res = JSON.parse(response);
            if (res.estatus == 200) {
                Toastify({
                    text: res.mensaje,
                    className: "success",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                }).showToast();
            }
            if (res.estatus == 400) {
                Toastify({
                    text: res.mensaje,
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
        }
    });
}

function CargarAvances_ReferenciasComerciales() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/referenciasComerciales.php",
        data: {
            bandera: "CargarAvances_ReferenciasComerciales",
            // TipoDireccion: 3
        },
        cache: false,
        // processData: false,  // tell jQuery not to process the data
        // contentType: false,   // tell jQuery not to set contentType
        success: function (response) {
            let res = JSON.parse(response);
            // console.log(res);

            for (let i = 0; i < res.length; i++) {
                if (res[i].ID_Ref == 1) {
                    $("#inputNombreProveedor1").val(res[i].Proveedor);
                    $("#inputTelefonoRefCom1").val(res[i].Telefono);
                    $("#inputPlazo1").val(res[i].Plazo);
                    $("#inputLimite1").val(res[i].Limite);
                }
                else if (res[i].ID_Ref == 2) {
                    $("#inputNombreProveedor2").val(res[i].Proveedor);
                    $("#inputTelefonoRefCom2").val(res[i].Telefono);
                    $("#inputPlazo2").val(res[i].Plazo);
                    $("#inputLimite2").val(res[i].Limite);
                }
                else if (res[i].ID_Ref == 3) {
                    $("#inputNombreProveedor3").val(res[i].Proveedor);
                    $("#inputTelefonoRefCom3").val(res[i].Telefono);
                    $("#inputPlazo3").val(res[i].Plazo);
                    $("#inputLimite3").val(res[i].Limite);
                }
            }
        }
    });
}


function continuar_Pagina6() {

    //guarda los datos
    GuardarInfo_ReferenciasComerciales();

    //cerrar collapse actual
    $("#collapse6").collapse('hide');

    //abre el siguiente collapse 
    $("#collapse7").collapse('show');


}

function cerrar_Pagina6() {

    // Descarta los cambios y redirige a la página de Monitor Solicitudes
    window.location.href = '../MonitorSolicitudes/monitorSolicitudes.php';

}

function regresar_Pagina6() {

    // Cierra el colapso actual
    $("#collapse6").collapse('hide');

    // abre el collapse anterior
    $("#collapse5").collapse('show');

    Toastify({
        text: "No se han realizado modificaciones.",
        className: "info",
        duration: 5000,
        gravity: "bottom",
        position: "right",
        style: {
            background: "linear-gradient(to right, #f7db4d, #deb902)",
        }
    }).showToast();
}