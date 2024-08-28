$(document).ready(function () {
    CargarZonas();
    CargarNiveles();
    CargarListaPrecios();
    CargarProductosVenta();
    CargarSectores();

    if (cargarAvances == true) {
        CargarAvances_ProspeccionCliente();
        AutorizacionDeBuro();
    }

    

    // $('#inputProductosVender').append('<option value="test">Prueba dinámica</option>');
    
    // $('#my-select').selectpicker('refresh');
});

var facturaElectronica, filialEmpresa, propietarioReal, visitaCliente, localPropio = '';
var articulosAVender = [];
// var localPropio = false;
// var visitaCliente = false;
// var propietarioReal = false;
// var filialEmpresa = false;
// var facturaElectronica = false;
var btn_sol_firma = document.getElementById("btn_sol_firma");

function CargarNiveles() {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "../../Controllers/prospeccionDelCliente.php",
            data: { bandera: "Cargar_Niveles" },
            cache: false,
            success: function (response) {
                let niveles = JSON.parse(response);

                for (let i = 0; i < niveles.length; i++) {
                    $("#inputNivel").append(`<option value="${niveles[i].ID_Nivel}">${niveles[i].Nivel}</option>`);
                }

                resolve(); // Resolver la promesa una vez que las opciones se han cargado
            },
            error: function (error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}

function CargarZonas() {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "../../Controllers/prospeccionDelCliente.php",
            data: { bandera: "Cargar_Zonas" },
            cache: false,
            success: function (response) {
                let zonas = JSON.parse(response);

                for (let i = 0; i < zonas.length; i++) {
                    $("#zonaOpt").append(`<option value="${zonas[i].ID_Zona}">${zonas[i].Zona}</option>`);
                }

                resolve(); // Resolver la promesa una vez que las opciones se han cargado
            },
            error: function (error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}

function CargarListaPrecios() {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "../../Controllers/prospeccionDelCliente.php",
            data: { bandera: "Cargar_ListaPrecios" },
            cache: false,
            success: function (response) {
                let listaPrecios = JSON.parse(response);

                for (let i = 0; i < listaPrecios.length; i++) {
                    $("#inputListaPrecios").append(`<option value="${listaPrecios[i].ID_Precio}">${listaPrecios[i].Precios}</option>`);
                }

                resolve(); // Resolver la promesa una vez que las opciones se han cargado
            },
            error: function (error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}

function CargarProductosVenta() {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "../../Controllers/prospeccionDelCliente.php",
            data: { bandera: "Cargar_Productos" },
            cache: false,
            success: function (response) {
                let listaPrecios = JSON.parse(response);

                for (let i = 0; i < listaPrecios.length; i++) {
                    $("#inputProductosVender").append(`<option value="${listaPrecios[i].ID_Producto}">${listaPrecios[i].Producto}</option>`);
                }

                $('#inputProductosVender').selectpicker('refresh');
                resolve(); // Resolver la promesa una vez que las opciones se han cargado
            },
            error: function (error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}

function CargarSectores() {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "../../Controllers/prospeccionDelCliente.php",
            data: { bandera: "Cargar_Sectores" },
            cache: false,
            success: function (response) {
                let listaSectores = JSON.parse(response);

                for (let i = 0; i < listaSectores.length; i++) {
                    $("#inputSectores").append(`<option value="${listaSectores[i].ID_Sector}">${listaSectores[i].Sector}</option>`);
                }

                resolve(); // Resolver la promesa una vez que las opciones se han cargado
            },
            error: function (error) {
                reject(error); // Rechazar la promesa en caso de error
            }
        });
    });
}

function LocalPropioCheck(value) {
    localPropio = value;
}
function FacturaElectronica(value) {
    facturaElectronica = value;
}
function FilialEmpresa(value) {
    filialEmpresa = value;

    if (value == 'No') {
        document.getElementById("inputNombreEmpresaFilial").value = '';
        document.getElementById("inputNombreEmpresaFilial").disabled = true;
    }
    if (value == 'Si') {
        document.getElementById("inputNombreEmpresaFilial").disabled = false;
    }
}
function PropietarioReal(value) {
    propietarioReal = value;
}
function VisitaCliente(value) {
    visitaCliente = value;
    console.log(visitaCliente);
}

var inputNombreEmpresaFilial = document.getElementById('inputNombreEmpresaFilial');
var inputNombreContactoCompras = document.getElementById('inputNombreContactoCompras');
var inputExtensionContactoCompras = document.getElementById('inputExtensionContactoCompras');
var inputNombreContactoPagos = document.getElementById('inputNombreContactoPagos');
var inputExtensionContactoPagos = document.getElementById('inputExtensionContactoPagos');
var inputNivel = document.getElementById('inputNivel');
var inputNombreVisitor = document.getElementById('inputNombreVisitor');
// var inputZona = document.getElementById('inputZona');
var inputListaPrecios = document.getElementById('inputListaPrecios');
var inputProductosConsume = document.getElementById('inputProductosConsume');
var inputProductosVender = document.getElementById('inputProductosVender');
var inputOtrosProveedores = document.getElementById('inputOtrosProveedores');
var inputConsumoAprox = document.getElementById('inputConsumoAprox');
var inputProyectoEspecial = document.getElementById('inputProyectoEspecial');

// Aplicar la función a los inputs
procesarInput_car(inputNombreEmpresaFilial);
procesarInput_car(inputNombreContactoCompras);
procesarInput_car(inputExtensionContactoCompras);
procesarInput_car(inputNombreContactoPagos);
procesarInput_car(inputExtensionContactoPagos);
procesarInput_car(inputNivel);
procesarInput_car(inputNombreVisitor);
// procesarInput_car(inputZona);
procesarInput_car(inputListaPrecios);
procesarInput_mayus(inputProductosConsume);
// procesarInput_mayus(inputProductosVender);
procesarInput_mayus(inputOtrosProveedores);
procesarInput_mayus(inputConsumoAprox);
procesarInput_mayus(inputProyectoEspecial);

var nombreEmpresaFilial, tiempoEstablecimiento, telefonoNegocio, nombreContactoCompras, telefonoContacto, extensionContacto,
    correo, nombreContactoPagos, telefonoContactoPagos, extensionContactoPagos, correoContactoPagos, giro, nivel, nombreVisitor, zona, listaPrecios, productosConsume, productosVender,
    proyeccionVentas, otrosProveedores, consumoAprox, proyectoEspecial, sector = '';

function GuardarInfo_ProspeccionCliente() {
    GetDatosFormulario5();
    let verificacionCampos = VerificarCampos_Pagina5();
    if (verificacionCampos == true) {
        let datosForm = new FormData();
        datosForm.append('bandera', 'GuardarInfo_ProspeccionCliente');

        datosForm.append('LocalPropio', localPropio);
        datosForm.append('FilialEmpresa', filialEmpresa);
        datosForm.append('PropietarioReal', propietarioReal);
        datosForm.append('inputNombreEmpresaFilial', nombreEmpresaFilial);
        datosForm.append('inputTiempoEstablecimiento', tiempoEstablecimiento);
        datosForm.append('inputTelefonoNegocio', telefonoNegocio);
        datosForm.append('inputNombreContactoCompras', nombreContactoCompras);
        datosForm.append('inputTelefonoContactoCompras', telefonoContacto);
        datosForm.append('inputExtensionContactoCompras', extensionContacto);
        datosForm.append('inputCorreoCompras', correo);
        datosForm.append('inputNombreContactoPagos', nombreContactoPagos);
        datosForm.append('inputTelefonoContactoPagos', telefonoContactoPagos);
        datosForm.append('inputExtensionContactoPagos', extensionContactoPagos);
        datosForm.append('inputCorreoContactoPagos', correoContactoPagos);
        datosForm.append('FacturaElectronica', facturaElectronica);
        datosForm.append('VisitaCliente', visitaCliente);
        // datosForm.append('inputGiro', giro);
        datosForm.append('inputNivel', nivel);
        datosForm.append('inputNombreVisitor', nombreVisitor);
        datosForm.append('inputZona', zona);
        datosForm.append('inputListaPrecios', listaPrecios);
        datosForm.append('inputSectores', sector);
        datosForm.append('inputProductosConsume', productosConsume);
        datosForm.append('inputProductosVender', JSON.stringify(articulosAVender));
        datosForm.append('inputProyeccionVentas', proyeccionVentas);
        datosForm.append('inputOtrosProveedores', otrosProveedores);
        datosForm.append('inputConsumoAprox', consumoAprox);
        datosForm.append('inputProyectoEspecial', proyectoEspecial);

        // Enviar los datos usando AJAX
        $.ajax({
            type: "POST",
            url: "../../Controllers/prospeccionDelCliente.php",
            data: datosForm,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (response) {
                response = JSON.parse(response);
                if (response.estatus == 200) {
                    Toastify({
                        text: response.mensaje,
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
                        text: response.mensaje,
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
    } else {
        //alert("Verificar los campos");
        Toastify({
            text: "Verificar los campos.",
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

function GetDatosFormulario5() {
    
    nombreEmpresaFilial = $('#inputNombreEmpresaFilial').val();
    tiempoEstablecimiento = $('#inputTiempoEstablecimiento').val();
    telefonoNegocio = $('#inputTelefonoNegocio').val();
    nombreContactoCompras = $('#inputNombreContactoCompras').val();
    telefonoContacto = $('#inputTelefonoContactoCompras').val();
    extensionContacto = $('#inputExtensionContactoCompras').val();
    correo = $('#inputCorreoCompras').val();
    nombreContactoPagos = $('#inputNombreContactoPagos').val();
    telefonoContactoPagos = $('#inputTelefonoContactoPagos').val();
    extensionContactoPagos = $('#inputExtensionContactoPagos').val();
    correoContactoPagos = $('#inputCorreoContactoPagos').val();
    // giro = $('#inputGiro').val();
    nombreVisitor = $('#inputNombreVisitor').val();
    nivel = $('#inputNivel').val();
    zona = $('#inputZona').val();
    listaPrecios = $('#inputListaPrecios').val();
    sector = $('inputSectores').val();
    productosConsume = $('#inputProductosConsume').val();
    articulosAVender = $("#inputProductosVender").val(); // Array con los valores seleccionados
    proyeccionVentas = $('#inputProyeccionVentas').val();
    otrosProveedores = $('#inputOtrosProveedores').val();
    consumoAprox = $('#inputConsumoAprox').val();
    consumoAprox = (consumoAprox).replace(',', ''); /* Se usa el replace para quitarle las comas y evitar errores en el backend */
    consumoAprox = (consumoAprox).replace('$', '');
    proyectoEspecial = $('#inputProyectoEspecial').val();
}

function VerificarCampos_Pagina5() {
    // verificamos que los valores no esten vacíos

    if (localPropio === '') return MostrarAlertaCampo('Local propio');
    if (facturaElectronica === '') return MostrarAlertaCampo('Factura Electrónica');
    if (propietarioReal === '') return MostrarAlertaCampo('Propietario real');
    if (filialEmpresa === '') return MostrarAlertaCampo('Filial de alguna empresa');

    if (filialEmpresa == 'Si') {
        if (nombreEmpresaFilial === '') return MostrarAlertaCampo('Empresa Filial');
    }

    if (tiempoEstablecimiento === '') return MostrarAlertaCampo('Tiempo Establecimiento');
    if (telefonoNegocio === '') return MostrarAlertaCampo('Teléfono Negocio');
    if (nombreContactoCompras === '') return MostrarAlertaCampo('Nombre Contacto Compras');
    if (telefonoContacto === '') return MostrarAlertaCampo('Teléfono Contacto Compras');
    if (correo === '') return MostrarAlertaCampo('Correo Contacto Compras');
    if (nombreContactoPagos === '') return MostrarAlertaCampo('Nombre Contacto Pagos');
    if (telefonoContactoPagos === '') return MostrarAlertaCampo('Teléfono Contacto Pagos');
    if (correoContactoPagos === '') return MostrarAlertaCampo('Correo Contacto Pagos');
    // if(giro === '') return MostrarAlertaCampo('Giro');
    if (visitaCliente === '') return MostrarAlertaCampo('Visita a cliente');
    if (nivel === '') return MostrarAlertaCampo('Nivel');
    if (nombreVisitor === '') return MostrarAlertaCampo('Quién visitó al cliente');
    if (zona === '') return MostrarAlertaCampo('Zona');
    if (listaPrecios === '') return MostrarAlertaCampo('Lista de precios');
    if (sector === '') return MostrarAlertaCampo('Sector');
    if (productosConsume === '') return MostrarAlertaCampo('Qué productos consume');
    console.log(articulosAVender);
    if (articulosAVender.length == 0) return MostrarAlertaCampo('Qué productos le vamos a vender');
    if (proyeccionVentas === '') return MostrarAlertaCampo('Proyección de ventas');
    if (otrosProveedores === '') return MostrarAlertaCampo('A qué otros proveedores les compra acero');
    if (consumoAprox === '') return MostrarAlertaCampo('Consumo aproximado');
    if (proyectoEspecial === '') return MostrarAlertaCampo('Proyecto especial o frecuente');

    return true;
    // else if (filialEmpresa == false) {
    //     if (nombreEmpresaFilial.trim() == '')
    //     {
    //         return true;
    //     }
    // }
    // else
    // {
    //     return false;
    // }

}

function MostrarAlertaCampo(campo) {
    //alert(`Por favor, completa el campo: ${campo}`);
    Toastify({
        text: `Por favor, completa el campo: ${campo}.`,
        className: "warning",
        duration: 5000,
        gravity: "bottom",
        position: "right",
        style: {
            background: "linear-gradient(to right, #f7db4d, #deb902)",
        }
    }).showToast();

    return false;
}
function Prospeccion_LlamarAPI_SMS(opc) {
    let checkConsultaBuro = document.getElementById("checkAutorizacionBuro").checked;
    console.log(checkConsultaBuro);
    if ((opc != 1 && checkConsultaBuro == true) || opc == 1) {
        console.log("TRUE CHECKED");
        $.ajax({
            url: '../../Controllers/prospeccionDelCliente.php',
            type: 'POST',
            data: { bandera: 'LlamarAPI_SMS', opc },
            beforeSend: function () {
                $("#btn_sol_firma").empty();
                btn_sol_firma.disabled = true;
                $("#btn_sol_firma").append("<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Enviando...</span></div>");
            },
            success: function (response) {
                response = JSON.parse(response);
                // if (JSON.parse(response.response).status === false && JSON.parse(response.response2).status === false) {
                if (response.estatus == 400) {
                    //alert("No se pudo completar el envio de mensajes.");
                    $("#btn_sol_firma").empty();
                    btn_sol_firma.disabled = false;
                    $("#btn_sol_firma").append('Solicitar Firma <i class="bi bi-envelope-arrow-up"></i>');
                    Toastify({
                        text: "No se pudo enviar el mensaje.",
                        className: "error",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #ff3636, #de0202)",
                        }
                    }).showToast();

                } else if (response.response === true && response.response2 === true && response.estatus == 200) {
                    setTimeout(() => {
                        Toastify({
                            text: "SMS enviado exitosamente al número " + response.mensaje,
                            className: "success",
                            duration: 5000,
                            gravity: "bottom",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right, #4df755, #04c20d)",
                            }
                        }).showToast();

                        $("#btn_sol_firma").empty();
                        btn_sol_firma.disabled = false;
                        $("#btn_sol_firma").append("Solicitar Firma <i class='bi bi-envelope-arrow-up'></i>");
                    }, 2000);

                }
            },
            error: function (response) {
                Toastify({
                    text: "Error al enviar SMS",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
                $("#btn_sol_firma").empty();
                btn_sol_firma.disabled = false;
                $("#btn_sol_firma").append("Solicitar Firma");
            }
        });
    }
    else if (opc != 1 && checkConsultaBuro == false) {
        Toastify({
            text: "Autoriza la consulta de Buró de Crédito para solicitarle la firma al cliente. ",
            className: 'info',
            duration: 7000,
            gravity: 'bottom',
            position: 'right',
            style: {
                background: 'linear-gradient(to right, #5087FF, #5087FF)',
            }
        }).showToast();
    }

}

// function SolicitarFirmaButton() {
//     btn_sol_firma.disabled = !.checked
// }

function LimpiarFormulario() {
    //Reiniciamos variables del formulario
    $('#inputNombreEmpresaFilial').val("");
    $('#inputTiempoEstablecimiento').val("");
    $('#inputTelefonoNegocio').val("");
    $('#inputNombreContactoCompras').val("");
    $('#inputExtensionContactoCompras').val("");
    $('#inputCorreoCompras').val("");
    $('#inputNombreContactoPagos').val("");
    $('#inputTelefonoContactoPagos').val("");
    $('#inputExtensionContactoPagos').val("");
    $('#inputCorreoContactoPagos').val("");

    // $('#inputGiro').val("");
    $('#inputNivel').val("");
    $('#inputNombreVisitor').val("");
    $('#inputZona').val("");
    $('#inputListaPrecios').val("");
    $('#inputProductosConsume').val("");
    $('#inputProductosVender').val("");
    $('#inputProyeccionVentas').val("");
    $('#inputOtrosProveedores').val("");
    $('#inputConsumoAprox').val("");

    $('#inputProyectoEspecial').val("");
}


function continuar_Pagina5() {

    //guarda los datos
    GuardarInfo_ProspeccionCliente();

    //cerrar collapse actual
    $("#collapse5").collapse('hide');

    //abre el siguiente collapse 
    $("#collapse6").collapse('show');

    let input6 = document.getElementById("inputNombreProveedor1");

    setTimeout(() => {
        input6.scrollIntoView({
            behavior: 'smooth'
        });
        input6.focus();
    }, 300); // Adjust the delay as needed

}

function cerrar_Pagina5() {
    // Descarta los cambios y redirige a la página de Monitor Solicitudes

    window.location.href = '../MonitorSolicitudes/monitorSolicitudes.php';

}

function regresar_Pagina5() {
    // Guarda la información
    //GuardarInfo_ProspeccionCliente();

    // Cierra el colapso actual
    $("#collapse5").collapse('hide');

    // abre el collapse anterior
    $("#collapse4").collapse('show');

    let input6 = document.getElementById("inputNombreProveedor1");

    setTimeout(() => {
        input6.scrollIntoView({
            behavior: 'smooth'
        });
        input6.focus();
    }, 300); // Adjust the delay as needed
}

function CargarAvances_ProspeccionCliente() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/prospeccionDelCliente.php",
        data: { bandera: "CargarAvances_ProspeccionCliente" },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);
            if (response.estatus != 200 && response.count != 0) {
                // console.log("Cumple condición");
                // console.log(response);
                response = response[0];
                $('#inputNombreEmpresaFilial').val(response.DescripFilial);
                $('#inputTiempoEstablecimiento').val(response.TiempoNegocio);
                $('#inputTelefonoNegocio').val(response.TelefonoNeg);
                $('#inputNombreContactoCompras').val(response.ContactoCompras);
                $('#inputTelefonoContactoCompras').val(response.TelefonoCompras);
                $('#inputExtensionContactoCompras').val(response.ExtCompras);
                $('#inputCorreoCompras').val(response.CorreoCompras);
                $('#inputNombreContactoPagos').val(response.ContactoPagos);
                $('#inputTelefonoContactoPagos').val(response.TelefonoPagos);
                $('#inputExtensionContactoPagos').val(response.ExtPagos);
                $('#inputCorreoContactoPagos').val(response.CorreoPagos);
                // giro = $('#inputGiro').val();
                $('#inputNivel').val(response.Nivel);
                $('#inputNombreVisitor').val(response.QuienVisito);
                $('#inputZona').val(response.Zona);
                $('#inputListaPrecios').val(response.ListaPrecios);
                $('#inputProductosConsume').val(response.ProductosConsume);

                // console.log(response.ProductoAVender);

                if(response.ProductoAVender)
                {
                    var arregloProctosSeleccionados = response.ProductoAVender.split("|");
                }

                // await CargarProductosVenta();
                setTimeout(function(){
                    $('#inputProductosVender').val(arregloProctosSeleccionados).selectpicker('refresh');
                }, 2000);
                
                
                // $('#inputProductosVender').val(response.ProductoAVender);
                $('#inputProyeccionVentas').val(response.ProyeccionVenta);
                $('#inputOtrosProveedores').val(response.OtrosProveedores);
                $('#inputConsumoAprox').val(response.ConsumoAprox);
                $('#inputProyectoEspecial').val(response.ProyEspecialOFrec);

                if (response.LocalPropio == 'Si') {
                    localPropio = 'Si';
                    document.getElementById("inputlocalPropioSi").checked = true;
                    document.getElementById("inputlocalPropioNo").checked = false;
                }
                if (response.LocalPropio == 'No') {
                    localPropio = 'No';
                    document.getElementById("inputlocalPropioNo").checked = true;
                    document.getElementById("inputlocalPropioSi").checked = false;
                }

                if (response.AceptaFE == 'Si') {
                    facturaElectronica = 'Si';
                    document.getElementById("inputfacturaElectronicaSi").checked = true;
                    document.getElementById("inputfacturaElectronicaNo").checked = false;
                }
                if (response.AceptaFE == 'No') {
                    facturaElectronica = 'No';
                    document.getElementById("inputfacturaElectronicaNo").checked = true;
                    document.getElementById("inputfacturaElectronicaSi").checked = false;
                }

                if (response.EsFilial == 'Si') {
                    filialEmpresa = 'Si';
                    document.getElementById("filialLineaCreditoSi").checked = true;
                    document.getElementById("filialLineaCreditoNo").checked = false;
                }
                if (response.EsFilial == 'No') {
                    filialEmpresa = 'No';
                    document.getElementById("filialLineaCreditoNo").checked = true;
                    document.getElementById("filialLineaCreditoSi").checked = false;
                    document.getElementById("inputNombreEmpresaFilial").value = '';
                    document.getElementById("inputNombreEmpresaFilial").disabled = true;
                }

                if (response.Propietario == 'Si') {
                    propietarioReal = 'Si';
                    document.getElementById("propietarioRealSi").checked = true;
                    document.getElementById("propietarioRealNo").checked = false;
                }
                if (response.Propietario == 'No') {
                    propietarioReal = 'No';
                    document.getElementById("propietarioRealNo").checked = true;
                    document.getElementById("propietarioRealSi").checked = false;
                }
                if (response.VisitoCliente == 'Si') {
                    visitaCliente = 'Si';
                    document.getElementById("inputVisitoSi").checked = true;
                    document.getElementById("inputVisitoNo").checked = false;
                }
                if (response.VisitoCliente == 'No') {
                    visitaCliente = 'No';
                    document.getElementById("inputVisitoSi").checked = false;
                    document.getElementById("inputVisitoNo").checked = true;
                }
            }
        }
    });
}

function AutorizacionDeBuro() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/prospeccionDelCliente.php",
        data: { bandera: "Autorizacion_BuroCredito" },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);
            if (response.estatus == 200 && response.firmado == true) {
                //Ya ha firmado la autorización de consulta de buró
                document.getElementById("checkAutorizacionBuro").checked = true;
                document.getElementById("checkAutorizacionBuro").disabled = true;
                document.getElementById("btn_sol_firma").hidden = true;
            }
        }
    });
}