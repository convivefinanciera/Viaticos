$(document).ready(function () {
    if (cargarAvances == true) {
        CargarAvances_Pagina4();
    }
});

$('#CPN').change(function () {
    let cpn = $("#CPN").val();
    if (cpn.length > 4) {
        CargarColonias4();
    }
});
//Variables para campos de domicilio
let calleN, numeroExtN, numeroIntN, codigoPostalN, coloniaN, municipioN, estadoN, ciudadN, antiguedadN = '';
let mismaDireccionParticularBand4, mismaDireccionFiscalBand4 = false;

var inputcalleN = document.getElementById('calleN');
var inputciudadN = document.getElementById('ciudadN');
var inputnumExteriorN = document.getElementById('numExteriorN');
var inputnumInteriorN = document.getElementById('numInteriorN');

// Aplicar la función a los inputs
procesarInput_car(inputcalleN);
procesarInput(inputciudadN);
procesarInput_car(inputnumExteriorN);
procesarInput_car(inputnumInteriorN);

function LimpiarFormulario4() {
    //Reiniciamos variables del formulario
    $("#calleN").val("");
    $("#numExteriorN").val("");
    $("#numInteriorN").val("");
    $("#CPN").val("");
    $("#coloniaN").val("");
    $("#municipioN").val("");
    $("#estadoN").val("");
    $("#ciudadN").val("");
    $("#antiguedadN").val("");
}

function GetDatosFormulario4() {
    calleN = $("#calleN").val();
    numeroExtN = $("#numExteriorN").val();
    numeroIntN = $("#numInteriorN").val();
    codigoPostalN = $("#CPN").val();
    coloniaN = $("#coloniaN").val();
    municipioN = $("#municipioN").val();
    estadoN = $("#estadoN").val();
    ciudadN = $("#ciudadN").val();
    antiguedadN = $("#antiguedadN").val();
}

function VerificarCampos_Pagina4() {
    let faltaCampo = '';

    calleN == '' ? faltaCampo = 'Calle' : calleN;
    numeroExtN == '' ? faltaCampo = 'Número exterior' : numeroExtN;
    //numeroIntN == '' ? faltaCampo = 'Número interior' : numeroIntN;
    codigoPostalN == '' ? faltaCampo = 'CP' : codigoPostalN;
    coloniaN == '' ? faltaCampo = 'Colonia' : coloniaN;
    municipioN == '' ? faltaCampo = 'Municipio' : municipioN;
    estadoN == '' ? faltaCampo = 'Estado' : estadoN;
    ciudadN == '' ? faltaCampo = 'Ciudad' : ciudadN;
    antiguedadN == '' ? faltaCampo = 'Antigüedad' : antiguedadN;

    if (faltaCampo != '') {
        return false;
    }
    else {
        return true;
    }
}
function GuardarInfo_Pagina4() {
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

        if (mismaDireccionParticularBand4 == true) {
            let datosForm2 = new FormData();

            datosForm2.append('bandera', 'mismaDireccionParticular');
            datosForm2.append('tipoDireccion', 3); //Se manda 3 porque queremos actualizar la direccion de tipo negocio

            $.ajax({
                type: "POST",
                url: "../../Controllers/monitorSolicitudes.php",
                data: datosForm2,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (response) {
                    response = JSON.parse(response);
                    Toastify({
                        text: response.mensaje+".",
                        className: "success",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                          background: "linear-gradient(to right, #4df755, #04c20d)",
                        }
                      }).showToast();
                    //alert(response.mensaje);
                }
            });
        }
        else if (mismaDireccionFiscalBand4 == true) {
            let datosForm3 = new FormData();

            datosForm3.append('bandera', 'mismaDireccionFiscal');
            datosForm3.append('tipoDireccion', 3); //Se manda 3 porque queremos actualizar la direccion dew tipo negocio

            $.ajax({
                type: "POST",
                url: "../../Controllers/monitorSolicitudes.php",
                data: datosForm3,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (response) {
                    response = JSON.parse(response);
                    Toastify({
                        text: response.mensaje+".",
                        className: "success",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                          background: "linear-gradient(to right, #4df755, #04c20d)",
                        }
                      }).showToast();
                    //alert(response.mensaje);
                }
            });
        }
        else {
            GetDatosFormulario4();
            let verificacionCampos4 = VerificarCampos_Pagina4();
            let datosForm = new FormData();
            datosForm.append('bandera', 'GuardarInfo_Pagina234');

            if (verificacionCampos4 == true) {
                datosForm.append('tipoDireccion', 3); // 1: Particular, 2: Fiscal, 3: Negocio
                datosForm.append('calle3', calleN);
                datosForm.append('numeroExt3', numeroExtN);
                datosForm.append('numeroInt3', numeroIntN);
                datosForm.append('codigoPostal3', codigoPostalN);
                datosForm.append('colonia3', coloniaN);
                datosForm.append('municipio3', municipioN);
                datosForm.append('estado3', estadoN);
                datosForm.append('ciudad3', ciudadN);
                datosForm.append('antiguedad3', antiguedadN);

                $.ajax({
                    type: "POST",
                    url: "../../Controllers/monitorSolicitudes.php",
                    data: datosForm,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response.estatus == 200) {
                            Toastify({
                                text: response.mensaje+".",
                                className: "success",
                                duration: 5000,
                                gravity: "bottom",
                                position: "right",
                                style: {
                                  background: "linear-gradient(to right, #4df755, #04c20d)",
                                }
                              }).showToast();
                            //alert(response.mensaje);
                        }
                        else {
                            Toastify({
                                text: response.mensaje+".",
                                className: "error",
                                duration: 5000,
                                gravity: "bottom",
                                position: "right",
                                style: {
                                  background: "linear-gradient(to right, #ff3636, #de0202)",
                                }
                              }).showToast();
                            //alert(response.mensaje);
                        }
                    }
                });
            }
        }

    //}
}

function MostrarDatosMismaDireccion4 (direccion) {
    $.ajax({
        type: 'POST', 
        url: '../../Controllers/monitorSolicitudes.php',
        data: { bandera: 'getMismaDireccion', direccion_get: direccion },
        success: (response) => {

            /* LLenar el formulario con la dirección que se indica */
            response = JSON.parse(response)[0];
            $('#calleN').val(response.Calle);
            $('#CPN').val(response.CP);
            $('#numExteriorN').val(response.NumExt);
            $('#numInteriorN').val(response.NumInt);
            $('#municipioN').val(response.Municipio);
            $('#ciudadN').val(response.Ciudad);
            $('#antiguedadN').val(response.Antiguedad);
            
            $('#coloniaN').empty();
            $('#coloniaN').append(`<option value="${ response.Colonia }">${ response.Colonia }</option>`)
            
            $('#estadoN').empty();
            $('#estadoN').append(`<option value="${ response.Estado }">${ response.Estado }</option>`)

        }
    })

    /* Esta función, debe ponerle el evento clic al boton guardar. */
    //$("#btn_save4").click();
}

function mismaDireccion (e) {
    let opcion = $(e).val();

    LimpiarFormulario4();
    if (opcion == 3) { /* 3 es para decir que si es otra dirección */
        HabilitarFormulario4();
        mismaDireccionParticularBand4 = false;
        mismaDireccionFiscalBand4 = false;
    } else { /* 1 y 2 es para decir que es la misma de la particular o la fiscal */
        DeshabilitarFormulario4();
        mismaDireccionParticularBand4 = opcion == 1 ? true : false;
        mismaDireccionFiscalBand4 = opcion == 1 ? false : true;
        
        MostrarDatosMismaDireccion4(opcion);
    }
}

/* function MismaDireccionParticular4(valorCheck) {
    if (valorCheck.checked) {
        document.getElementById('mismaDireccionFiscal4').checked = false;
        LimpiarFormulario4();
        DeshabilitarFormulario4();

        mismaDireccionParticularBand4 = true;
        mismaDireccionFiscalBand4 = false;
    }
    else {
        HabilitarFormulario4();
        mismaDireccionParticularBand4 = false;
        mismaDireccionFiscalBand4 = false;
    }
}

function MismaDireccionFiscal4(valorCheck) {
    if (valorCheck.checked) {
        document.getElementById('mismaDireccionParticular4').checked = false;
        LimpiarFormulario4();
        DeshabilitarFormulario4();

        mismaDireccionParticularBand4 = false;
        mismaDireccionFiscalBand4 = true;
    }
    else {
        HabilitarFormulario4();
        mismaDireccionParticularBand4 = false;
        mismaDireccionFiscalBand4 = false;
    }
} */

function DeshabilitarFormulario4() {
    $("#calleN").prop('disabled', true);
    $("#numExteriorN").prop('disabled', true);
    $("#numInteriorN").prop('disabled', true);
    $("#CPN").prop('disabled', true);
    $("#coloniaN").prop('disabled', true);
    $("#municipioN").prop('disabled', true);
    $("#estadoN").prop('disabled', true);
    $("#ciudadN").prop('disabled', true);
    $("#antiguedadN").prop('disabled', true);
}

function HabilitarFormulario4() {
    $("#calleN").prop('disabled', false);
    $("#numExteriorN").prop('disabled', false);
    $("#numInteriorN").prop('disabled', false);
    $("#CPN").prop('disabled', false);
    $("#coloniaN").prop('disabled', false);
    $("#municipioN").prop('disabled', false);
    $("#estadoN").prop('disabled', false);
    $("#ciudadN").prop('disabled', false);
    $("#antiguedadN").prop('disabled', false);
}
// let codigoPosN = 0;
function CargarAvances_Pagina4() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: {
            bandera: "CargarAvances_Pagina234",
            TipoDireccion: 3
        },
        cache: false,
        // processData: false,  // tell jQuery not to process the data
        // contentType: false,   // tell jQuery not to set contentType
        success: async function (response) {
            let res = JSON.parse(response);
            let datosDireccion = res[0];

            if (typeof datosDireccion !== 'undefined') {
                $("#domicilioNegocioAccordion").removeAttr("disabled");
                //Ponemos los los valores obtenidos en los elementos HTML
                console.log(datosDireccion);                
                $('#calleN').val(datosDireccion.Calle);
                $('#numExteriorN').val(datosDireccion.NumExt);
                $('#numInteriorN').val(datosDireccion.NumInt);
                let dom3cargado = $('#CPN').val(datosDireccion.NumExt);
                console.log("Domicilio 3 cargado" + dom3cargado);
                $('#CPN').val(datosDireccion.CP);

                $('#municipioN').val(datosDireccion.Municipio);
                $('#estadoN').val(datosDireccion.Estado);
                $('#ciudadN').val(datosDireccion.Ciudad);
                $('#antiguedadN').val(datosDireccion.Antiguedad);

                let mismaDireccionPF = datosDireccion.Misma_ID_Direccion;

                let mismaDirPar = document.getElementById('mismaDireccionParticular4');
                let mismaDirFis = document.getElementById('mismaDireccionFiscal4');
                let otraDir = document.getElementById('otro');

                console.log("Mismo tipo de dirección: " + mismaDireccionPF);
                
                if(mismaDireccionPF == 1)
                {
                    console.log("Entro 1");
                    mismaDirPar.checked = true;
                    //mismaDirPar.click();
                    DeshabilitarFormulario4();
                }
                if (mismaDireccionPF == 2)
                {
                    console.log("Entro 2");
                    mismaDirFis.checked = true;
                    //mismaDirFis.click();
                    DeshabilitarFormulario4();
                }
                if (mismaDireccionPF == 0){
                    console.log("Entro otro");
                    otraDir.checked = true;
                    //otraDir.click();
                }

                GetDatosFormulario4();
                
                await CargarColonias4();

                setTimeout(() => {
                    $('#coloniaN').val(datosDireccion.Colonia);
                }, "1000");

                DeshabilitarFormulario4();
                // if (res.TipoPersona == 'F') {
                //     $("#Radio_Persona_Fisica").attr('checked', true);
                // }
                // if(res.T)
            }
        }
    });
}

function CargarColonias4() {
    let cp = $("#CPN").val();
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
                $("#coloniaN").empty();
                $("#municipioN").empty();
                $("#estadoN").empty();
                $("#ciudadN").empty();

                for (let i = 0; i < colonias.length; i++) {
                    var col_upper = colonias[i].d_asenta.toUpperCase();
                    col_upper = col_upper.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Quita acentos
                    col_upper = col_upper.replace(/[^a-zA-Z\s]/g, ''); 
                    $("#coloniaN").append(`<option value="${col_upper}">${col_upper}</option>`);
                }

                var mun_upper = colonias[0].D_mnpio.toUpperCase();
                mun_upper = mun_upper.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Quita acentos
                mun_upper = mun_upper.replace(/[^a-zA-Z\s]/g, ''); // Elimina caracteres no permitidos
                
                $("#municipioN").val(mun_upper);
                $("#estadoN").append(`<option value="${ colonias[0].d_estado.toUpperCase() }">${ colonias[0].d_estado.toUpperCase() }</option>`);
                $("#ciudadN").val(colonias[0].d_ciudad);
            }
        });
    });
}



function continuar_Pagina4(){

    //guarda los datos
    GuardarInfo_Pagina4();

    //cerrar collapse actual
    $("#collapse4").collapse('hide');

    //abre el siguiente collapse 
    $("#collapse5").collapse('show');


}

function cerrar_Pagina4(){

        // Descarta los cambios y redirige a la página de Monitor Solicitudes
      
        window.location.href = '../MonitorSolicitudes/monitorSolicitudes.php';
    
}

function regresar_Pagina4(){
    // Guarda la información
    GuardarInfo_Pagina4();

    // Cierra el colapso actual
    $("#collapse4").collapse('hide');

   // abre el collapse anterior
    $("#collapse3").collapse('show');
}