$(document).ready(function () {
    if (cargarAvances == true) {
        CargarAvances_Pagina3();
    }
});

$('#CPF').change(function () {
    let cpf = $("#CPF").val();
    if (cpf.length > 4) {
        CargarColonias3();
    }
});

//Variables para campos de domicilio
let calleF, numeroExtF, numeroIntF, codigoPostalF, coloniaF, municipioF, estadoF, ciudadF, antiguedadF = '';
let mismaDireccionParticularBand = false;

var inputcalleF = document.getElementById('calleF');
var inputciudadF = document.getElementById('ciudadF');
var inputnumExteriorF = document.getElementById('numExteriorF');
var inputnumInteriorF = document.getElementById('numInteriorF');

// Aplicar la función a los inputs
procesarInput_car(inputcalleF);
procesarInput(inputciudadF);
procesarInput_car(inputnumExteriorF);
procesarInput_car(inputnumInteriorF);

function LimpiarFormulario3() {
    // console.log("Limpiar formulario 3");
    //Reiniciamos variables del formulario
    $("#calleF").val("");
    $("#numExteriorF").val("");
    $("#numInteriorF").val("");
    $("#CPF").val("");
    $("#coloniaF").val("");
    $("#municipioF").val("");
    $("#estadoF").val("");
    $("#ciudadF").val("");
    $("#antiguedadF").val("");
}

function GetDatosFormulario3() {
    calleF = $("#calleF").val();
    numeroExtF = $("#numExteriorF").val();
    numeroIntF = $("#numInteriorF").val();
    codigoPostalF = $("#CPF").val();
    coloniaF = $("#coloniaF").val();
    municipioF = $("#municipioF").val();
    estadoF = $("#estadoF").val();
    ciudadF = $("#ciudadF").val();
    antiguedadF = $("#antiguedadF").val();
    console.log(coloniaF);
    console.log(estadoF);
}

function VerificarCampos_Pagina3() {
    let faltaCampo = '';

    calleF == '' ? faltaCampo = 'Calle' : calleF;
    numeroExtF == '' ? faltaCampo = 'Número exterior' : numeroExtF;
    //numeroIntF == '' ? faltaCampo = 'Número interior' : numeroIntF;
    codigoPostalF == '' ? faltaCampo = 'CP' : codigoPostalF;
    coloniaF == 'D' ? faltaCampo = 'Colonia' : coloniaF;
    municipioF == '' ? faltaCampo = 'Municipio' : municipioF;
    estadoF == 'D' ? faltaCampo = 'Estado' : estadoF;
    ciudadF == '' ? faltaCampo = 'Ciudad' : ciudadF;
    antiguedadF == '' ? faltaCampo = 'Antigüedad' : antiguedadF;

    if (faltaCampo != '') {
        return false;
    }
    else {
        return true;
    }
}

function GuardarInfo_Pagina3() {
    Toastify({
        text: "Se guardarán los avances, podrás continuar esta solicitud en la sección 'Monitor de Solicitudes' en cualquier momento.",
        className: "info",
        duration: 5000,
        gravity: "bottom",
        position: "right",
        style: {
            //background: "linear-gradient(to right, #00b09b, #96c93d)",
            background: "linear-gradient(to right, #0179ff, #0179ff)",
        }
    }).showToast();
    //if (confirm("Se guardarán los avances, podrás continuar esta solicitud en la sección 'Monitor de Solicitudes' en cualquier momento.") == true) {

    if (mismaDireccionParticularBand == true) {
        let datosFormP = new FormData();

        datosFormP.append('bandera', 'mismaDireccionParticular');
        datosFormP.append('tipoDireccion', 2); //Se envía 2 porque queremos actualizar la direccion tipo Fiscal

        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorSolicitudes.php",
            data: datosFormP,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (response) {
                response = JSON.parse(response);

                if (response.estatus == 200) {
                    $("#direccionFiscalAccordion").removeAttr("disabled");
                    Toastify({
                        text: response.mensaje + ".",
                        className: "success",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #4df755, #04c20d)",
                        }
                    }).showToast();
                }
                if (response.estatus == 400) {
                    Toastify({
                        text: "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.",
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

    else {
        GetDatosFormulario3();
        let verificacionCampos3 = VerificarCampos_Pagina3();
        let datosForm = new FormData();
        datosForm.append('bandera', 'GuardarInfo_Pagina234');

        if (verificacionCampos3 == true) {
            datosForm.append('tipoDireccion', 2); // 1: Particular, 2: Fiscal, 3: Negocio
            datosForm.append('calle2', calleF);
            datosForm.append('numeroExt2', numeroExtF);
            datosForm.append('numeroInt2', numeroIntF);
            datosForm.append('codigoPostal2', codigoPostalF);
            datosForm.append('colonia2', coloniaF);
            datosForm.append('municipio2', municipioF);
            datosForm.append('estado2', estadoF);
            datosForm.append('ciudad2', ciudadF);
            datosForm.append('antiguedad2', antiguedadF);

            $.ajax({
                type: "POST",
                url: "../../Controllers/monitorSolicitudes.php",
                data: datosForm,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.estatus == 200) {
                        //alert(response.mensaje);
                        Toastify({
                            text: response.mensaje + ".",
                            className: "success",
                            duration: 5000,
                            gravity: "bottom",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right, #4df755, #04c20d)",
                            }
                        }).showToast();

                    }
                }
            });
        }
        else {
            //alert("Por favor verifica todos los campos de la dirección fiscal");
            Toastify({
                text: "Por favor verifica todos los campos de la dirección fiscal.",
                className: "warning",
                duration: 5000,
                gravity: "bottom",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #f7db4d, #deb902)",
                }
            }).showToast();


        }
    }
}

function MismaDireccionParticular3(valorCheck) {
    if (valorCheck.checked) {
        //console.log("Valor del checked "+valorCheck.checked);
        mismaDireccionParticularBand = true;
        MostrarDatosMismaDireccion();
        LimpiarFormulario3();
        DeshabilitarFormulario3();
    }
    else {
        //console.log("unchecked");
        mismaDireccionParticularBand = false;
        LimpiarFormulario3();
        HabilitarFormulario3();
    }
}

function DeshabilitarFormulario3() {
    $("#calleF").prop('disabled', true);
    $("#numExteriorF").prop('disabled', true);
    $("#numInteriorF").prop('disabled', true);
    $("#CPF").prop('disabled', true);
    $("#coloniaF").prop('disabled', true);
    $("#municipioF").prop('disabled', true);
    $("#estadoF").prop('disabled', true);
    $("#ciudadF").prop('disabled', true);
    $("#antiguedadF").prop('disabled', true);
}

function HabilitarFormulario3() {
    $("#calleF").prop('disabled', false);
    $("#numExteriorF").prop('disabled', false);
    $("#numInteriorF").prop('disabled', false);
    $("#CPF").prop('disabled', false);
    $("#coloniaF").prop('disabled', false);
    $("#municipioF").prop('disabled', false);
    $("#estadoF").prop('disabled', false);
    $("#ciudadF").prop('disabled', false);
    $("#antiguedadF").prop('disabled', false);
}

function MostrarDatosMismaDireccion(direccion = 1) {

    $.ajax({
        type: 'POST',
        url: '../../Controllers/monitorSolicitudes.php',
        data: { bandera: 'getMismaDireccion', direccion_get: direccion },
        success: (response) => {

            /* LLenar el formulario con la dirección que se indica */
            response = JSON.parse(response)[0];
            if (typeof (response) !== "undefined" && response !== null) {
                console.log("Mostrar Datos Misma Direccion ");
                $('#calleF').val(response.Calle);
                $('#CPF').val(response.CP);
                $('#numExteriorF').val(response.NumExt);
                $('#numInteriorF').val(response.NumInt);
                $('#municipioF').val(response.Municipio);
                $('#ciudadF').val(response.Ciudad);
                $('#antiguedadF').val(response.Antiguedad);

                $('#coloniaF').empty();
                $('#coloniaF').append(`<option value="${response.Colonia}">${response.Colonia}</option>`)

                $('#estadoF').empty();
                $('#estadoF').append(`<option value="${response.Estado}">${response.Estado}</option>`)
            }
            else {
                //alert("No se ha registrado una dirección particular en esta solicitud");
                Toastify({
                    text: "No se ha registrado una dirección particular en esta solicitud.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();

                HabilitarFormulario3();
                let mismaDirPar = document.getElementById('mismaDireccionParticular3');
                mismaDirPar.checked = false;
                mismaDireccionParticularBand = false;
            }

        }
    })

    /* Esta función, debe ponerle el evento clic al boton guardar. */
    //$("#btn_save").click();
}

function CargarAvances_Pagina3() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: {
            bandera: "CargarAvances_Pagina234",
            TipoDireccion: 2
        },
        cache: false,
        // processData: false,  // tell jQuery not to process the data
        // contentType: false,   // tell jQuery not to set contentType
        success: async function (response) {
            let res = JSON.parse(response);
            let datosDireccion = res[0];
            if (typeof datosDireccion !== 'undefined') {
                //Ponemos los los valores obtenidos en los elementos HTML
                $('#calleF').val(datosDireccion.Calle);
                $('#numExteriorF').val(datosDireccion.NumExt);
                $('#numInteriorF').val(datosDireccion.NumInt);
                $('#CPF').val(datosDireccion.CP);
                $('#municipioF').val(datosDireccion.Municipio);
                $('#estadoF').val(datosDireccion.Estado);
                $('#ciudadF').val(datosDireccion.Ciudad);
                $('#antiguedadF').val(datosDireccion.Antiguedad);
                let mismaDireccionP = datosDireccion.Misma_ID_Direccion;

                if (mismaDireccionP == 1) {
                    let mismaDirPar = document.getElementById('mismaDireccionParticular3');
                    mismaDirPar.checked = true;
                    // mismaDirPar.click();
                }
                GetDatosFormulario3();

                await CargarColonias3();

                // Agregar un retraso para verificar el valor después de await
                // setTimeout(() => {
                //     console.log("CP DESPUES del await y retraso " + $('#CPF').val());
                // }, 500);

                DeshabilitarFormulario3();
                $("#domicilioNegocioAccordion").removeAttr("disabled");

                // if (res.TipoPersona == 'F') {
                //     $("#Radio_Persona_Fisica").attr('checked', true);
                // }
                // if(res.T)
            }
        }
    });
}

let CodigoPFChange = document.getElementById("CPF");
CodigoPFChange.addEventListener("change", ImprimirCambio);

function ImprimirCambio(e)
{
    console.log("Evento cambiado" + e)
}

function CargarColonias3() {
    // alert("Cargando colonias");
    let cp = $("#CPF").val();
    console.log("CARGAR COLONIAS CP " + cp);
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorSolicitudes.php",
            data: {
                bandera: "Cargar_Colonias",
                CP: cp
            },
            cache: false,
            // processData: false,  // tell jQuery not to process the data
            // contentType: false,   // tell jQuery not to set contentType
            success: function (response) {
                let colonias = JSON.parse(response);
                $("#coloniaF").empty();
                $("#municipioF").empty();
                $("#estadoF").empty();
                $("#ciudadF").empty();

                for (let i = 0; i < colonias.length; i++) {
                    var col_upper = colonias[i].d_asenta.toUpperCase();
                    col_upper = col_upper.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Quita acentos
                    col_upper = col_upper.replace(/[^a-zA-Z\s]/g, ''); // Elimina caracteres no permitidos
                    $("#coloniaF").append(`<option value="${col_upper}">${col_upper}</option>`);
                }

                var mun_upper = colonias[0].D_mnpio.toUpperCase();
                mun_upper = mun_upper.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Quita acentos
                mun_upper = mun_upper.replace(/[^a-zA-Z\s]/g, ''); // Elimina caracteres no permitidos

                $("#municipioF").val(mun_upper);
                $("#estadoF").append(`<option value="${colonias[0].d_estado.toUpperCase()}">${colonias[0].d_estado.toUpperCase()}</option>`);
                $("#ciudadF").val(colonias[0].d_ciudad);

                resolve();
            },
            error: function (error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}


function continuar_Pagina3() {

    //guarda los datos
    GuardarInfo_Pagina3();

    //cerrar collapse actual
    $("#collapse3").collapse('hide');

    //abre el siguiente collapse 
    $("#collapse4").collapse('show');


}

function cerrar_Pagina3() {

    // Descarta los cambios y redirige a la página de Monitor Solicitudes

    window.location.href = '../MonitorSolicitudes/monitorSolicitudes.php';

}

function regresar_Pagina3() {
    // Guarda la información
    GuardarInfo_Pagina3();

    // Cierra el colapso actual
    $("#collapse3").collapse('hide');

    // Abre el colapso anterior (ajusta el ID según tu estructura)
    // Por ejemplo, si el colapso anterior tiene el ID "collapse1":
    $("#collapse2").collapse('show');
}