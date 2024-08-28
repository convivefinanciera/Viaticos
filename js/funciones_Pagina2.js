$(document).ready(function () {
    if (cargarAvances == true) {
        CargarAvances_Pagina2();
        // CargarColonias2().then(() =>{
        // }).catch(error => {
        //     console.log("Falla la promesa");
        // })
    }
});

$('#CPP').change(function () {
    let cpp = $("#CPP").val();
    if (cpp.length > 4) {
        CargarColonias2();
    }
});
//Variables para campos de domicilio
let calleP, numeroExtP, numeroIntP, codigoPostalP, coloniaP, municipioP, estadoP, ciudadP, antiguedadP = '';

var inputcalleP = document.getElementById('calleP');
var inputciudadP = document.getElementById('ciudadP');
var inputnumExteriorP = document.getElementById('numExteriorP');
var inputnumInteriorP = document.getElementById('numInteriorP');

// Aplicar la función a los inputs
procesarInput_car(inputcalleP);
procesarInput(inputciudadP);
procesarInput_car(inputnumExteriorP);
procesarInput_car(inputnumInteriorP);

function LimpiarFormulario2() {
    //Reiniciamos variables del formulario
    $("#calleP").val("");
    $("#numExteriorP").val("");
    $("#numInteriorP").val("");
    $("#CPP").val("");
    $("#coloniaP").val("");
    $("#municipioP").val("");
    $("#estadoP").val("");
    $("#ciudadP").val("");
    $("#antiguedadP").val("");
}

function DeshabilitarFormulario2() {
    $("#calleP").prop('disabled', true);
    $("#numExteriorP").prop('disabled', true);
    $("#numInteriorP").prop('disabled', true);
    $("#CPP").prop('disabled', true);
    $("#coloniaP").prop('disabled', true);
    $("#municipioP").prop('disabled', true);
    $("#estadoP").prop('disabled', true);
    $("#ciudadP").prop('disabled', true);
    $("#antiguedadP").prop('disabled', true);
}

function HabilitarFormulario2() {
    $("#calleP").prop('disabled', false);
    $("#numExteriorP").prop('disabled', false);
    $("#numInteriorP").prop('disabled', false);
    $("#CPP").prop('disabled', false);
    $("#coloniaP").prop('disabled', false);
    $("#municipioP").prop('disabled', false);
    $("#estadoP").prop('disabled', false);
    $("#ciudadP").prop('disabled', false);
    $("#antiguedadP").prop('disabled', false);
}

function GetDatosFormulario2() {
    calleP = $("#calleP").val();
    numeroExtP = $("#numExteriorP").val();
    numeroIntP = $("#numInteriorP").val();
    codigoPostalP = $("#CPP").val();
    coloniaP = $("#coloniaP").val();
    municipioP = $("#municipioP").val();
    estadoP = $("#estadoP").val();
    ciudadP = $("#ciudadP").val();
    antiguedadP = $("#antiguedadP").val();
    //console.log(calleP + numeroExtP + numeroIntP + codigoPostalP+coloniaP+municipioP+estadoP+ciudadP+antiguedadP);
}

function VerificarCampos_Pagina2() {
    let faltaCampo = '';

    calleP == '' ? faltaCampo = 'Calle' : calleP;
    numeroExtP == '' ? faltaCampo = 'Número exterior' : numeroExtP;
    //numeroIntP == '' ? faltaCampo = 'Número interior' : numeroIntP;
    codigoPostalP == '' ? faltaCampo = 'CP' : codigoPostalP;
    coloniaP == '' ? faltaCampo = 'Colonia' : coloniaP;
    municipioP == '' ? faltaCampo = 'Municipio' : municipioP;
    estadoP == '' ? faltaCampo = 'Estado' : estadoP;
    ciudadP == '' ? faltaCampo = 'Ciudad' : ciudadP;
    antiguedadP == '' ? faltaCampo = 'Antigüedad' : antiguedadP;

    console.log(faltaCampo);

    if (faltaCampo != '') {
        return false;
    }
    else {
        return true;
    }
}

function GuardarInfo_Pagina2() {
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
    GetDatosFormulario2();
    let verificacionCampos2 = VerificarCampos_Pagina2();
    let datosForm = new FormData();
    datosForm.append('bandera', 'GuardarInfo_Pagina234');

    if (verificacionCampos2 == true) {
        datosForm.append('tipoDireccion', 1); // 1: Particular, 2: Fiscal, 3: Negocio
        datosForm.append('calle1', calleP);
        datosForm.append('numeroExt1', numeroExtP);
        datosForm.append('numeroInt1', numeroIntP);
        datosForm.append('codigoPostal1', codigoPostalP);
        datosForm.append('colonia1', coloniaP);
        datosForm.append('municipio1', municipioP);
        datosForm.append('estado1', estadoP);
        datosForm.append('ciudad1', ciudadP);
        datosForm.append('antiguedad1', antiguedadP);

        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorSolicitudes.php",
            data: datosForm,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (response) {
                response = JSON.parse(response);
                if(response.estatus == 200)
                {
                    $("#domicilioFiscalAccordion").removeAttr("disabled");
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
                    let mismaDirPar = document.getElementById('mismaDireccionParticular3');
                    
                    mismaDirPar.click();
                    // MismaDireccionParticular3(false);
                    ///CargarAvances_Pagina4(); //Negocio
                }
                if(response.estatus == 400)
                {
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
        Toastify({
            text: "Verifica los campos del Domicilio Particular.",
            className: "info",
            duration: 5000,
            gravity: "bottom",
            position: "right",
            style: {
                background: "linear-gradient(to right, #f7db4d, #deb902)",
            }
        }).showToast();
    }
    //}
}

function CargarAvances_Pagina2() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: {
            bandera: "CargarAvances_Pagina234",
            TipoDireccion: 1
        },
        cache: false,
        // processData: false,  // tell jQuery not to process the data
        // contentType: false,   // tell jQuery not to set contentType
        success: async function (response) {
            let res = JSON.parse(response);
            let datosDireccion = res[0];
            if (typeof datosDireccion !== 'undefined') {
                //Habilitamos botón de domicilio fiscal
                $("#domicilioFiscalAccordion").removeAttr("disabled");

                //Ponemos los los valores obtenidos en los elementos HTML
                $('#calleP').val(datosDireccion.Calle);
                $('#numExteriorP').val(datosDireccion.NumExt);
                $('#numInteriorP').val(datosDireccion.NumInt);
                $('#CPP').val(datosDireccion.CP);

                $('#municipioP').val(datosDireccion.Municipio);
                $('#estadoP').val(datosDireccion.Estado);
                $('#ciudadP').val(datosDireccion.Ciudad);
                $('#antiguedadP').val(datosDireccion.Antiguedad);

                GetDatosFormulario2();
                await CargarColonias2();
                $('#coloniaP').val(datosDireccion.Colonia);

                $("#seccionEditarDomPar").show('fast');
                DeshabilitarFormulario2();
                // setTimeout(() => {
                //     $('#coloniaP').val(datosDireccion.Colonia);
                // }, "1000");
            }
            // if (res.TipoPersona == 'F') {
            //     $("#Radio_Persona_Fisica").attr('checked', true);
            // }
            // if(res.T)
        }
    });
}

function CargarColonias2() {
    let cp = $("#CPP").val();
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
                $("#coloniaP").empty();
                $("#municipioP").empty();
                $("#estadoP").empty();
                $("#ciudadP").empty();

                for (let i = 0; i < colonias.length; i++) { /* Llena las colonias */
                    var col_upper = colonias[i].d_asenta.toUpperCase();
                    col_upper = col_upper.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Quita acentos
                    col_upper = col_upper.replace(/[^a-zA-Z\s]/g, ''); // Elimina caracteres no permitidos
                    $("#coloniaP").append(`<option value="${col_upper}">${col_upper.toUpperCase()}</option>`);
                }

                var mun_upper = colonias[0].D_mnpio.toUpperCase();
                mun_upper = mun_upper.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Quita acentos
                mun_upper = mun_upper.replace(/[^a-zA-Z\s]/g, ''); // Elimina caracteres no permitidos

                $("#municipioP").val(mun_upper);
                $("#estadoP").append(`<option value="${colonias[0].d_estado.toUpperCase()}">${colonias[0].d_estado.toUpperCase()}</option>`);
                $("#ciudadP").val(colonias[0].d_ciudad);

                resolve();
            },
            error: function(error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}


function continuar_Pagina2() {

    //guarda los datos
    GuardarInfo_Pagina2();

    //cerrar collapse actual
    $("#collapse2").collapse('hide');

    //abre el siguiente collapse 
    $("#collapse3").collapse('show');

    //cerrar collapse actual
    //$("#collapse2").collapse('hide');

    //abre el siguiente collapse 
    //$("#collapse3").collapse('show');
    //  let currentCollapse = document.closest('.accordion-collapse');
    // const currentCollapse = document.getElementById("accordionSolicitud");
    // console.log(currentCollapse);
    // const currentItem = currentCollapse.closest(".accordion-item");
    // console.log(currentItem);
    // const nextItem = currentItem.nextElementSibling;

    // if (nextItem) {
    //     let nextCollapse = nextItem.querySelector('.accordion-collapse');
    //     let nextInput = nextCollapse.querySelector('input');

    //     // Collapse current section
    //     currentCollapse.classList.remove('show');

    //     // Expand next section
    //     nextCollapse.classList.add('show');

    //     // Use setTimeout to ensure the section has time to expand
    //     setTimeout(() => {
    //         // Scroll to next section
    //         nextItem.scrollIntoView({
    //             behavior: 'smooth'
    //         });

    //         // Focus the first input in the next section
    //         if (nextInput) {
    //             nextInput.focus();
    //         }
    //     }, 300); // Adjust the delay as needed
    // }
}

function cerrar_Pagina2() {

    // Descarta los cambios y redirige a la página de Monitor Solicitudes

    window.location.href = '../MonitorSolicitudes/monitorSolicitudes.php';

}

function regresar_Pagina2() {
    // Guarda la información
    GuardarInfo_Pagina2();

    // Cierra el colapso actual
    $("#collapse2").collapse('hide');

    // Abre el colapso anterior (ajusta el ID según tu estructura)
    // Por ejemplo, si el colapso anterior tiene el ID "collapse1":
    $("#collapse1").collapse('show');
}

function cargarColoniasEstados() {
    return Promise.all([CargarColonias2(), CargarEstados2()]);
  }

  function EditarDireccionParticular(checkEditDom){
    console.log("Edit check "+checkEditDom.checked);
    if(checkEditDom.checked == true)
    {
        LimpiarFormulario2();
        HabilitarFormulario2();
        Toastify({
            text: 'Los domicilios (Fiscal y/o de Negocio) asociados a este domicilio particular serán borrados al editar la información.',
            className: 'info',
            duration: 7000,
            gravity: 'bottom',
            position: 'center',
            style: {
              background: 'linear-gradient(to right, #5087FF, #5087FF)',
            }
          }).showToast();
    }
    if(checkEditDom.checked == false)
    {
        CargarAvances_Pagina2();
    }
}