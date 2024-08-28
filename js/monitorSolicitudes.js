var table;
var filtroSel = '';
$(document).ready(function () {
    //$('#tablaSolicitudes').DataTable();
    let rol = parseInt($("main").attr('data_role'));
    
    if ((rol >= 1 && rol <= 3) || rol == 9) {
        CargarSolicitudes("RECIENTES");
    } else if (rol >= 4 && rol <= 5) {
        CargarSolicitudes("5");
    } else if (rol >= 8) {
        CargarSolicitudes("D");
    }
});

document.getElementById('exportButton').addEventListener('click', function () {
    var table = document.getElementById('tablaSolicitudes');
    var clone = table.cloneNode(true);

    // Elimina las columnas "Detalle" y "Acción" del encabezado
    for (var i = clone.rows[0].cells.length - 1; i >= 0; i--) {
        if (clone.rows[0].cells[i].innerText === "Detalle" || clone.rows[0].cells[i].innerText === "Acción") {
            for (var j = 0; j < clone.rows.length; j++) {
                clone.rows[j].deleteCell(i);
            }
        }
    }
    var wb = XLSX.utils.table_to_book(clone, { sheet: "Hoja 1" });

    // Obtener la fecha actual
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
    var yyyy = today.getFullYear();

    today = yyyy + mm + dd;
    var filename = "MonitorSolicitudes_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
});

// $.getScript("https://cdn.datatables.net/plug-ins/2.0.8/dataRender/datetime.js", function() {
//     // alert("Script loaded but not necessarily executed.");
//  });

function CargarSolicitudes(filtro) {
    //Cargamos los contratos para mostrar la tabla
    filtroSel = filtro;

    $("#bodyTablaSolicitudes").empty();
    if (table != null) {
        table.clear().draw();
        table.destroy();
    }

    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: {
            bandera: 'Mostrar_Registros',
            Filtro_Solicitudes: filtro,
        },
        // dataType: "dataType",
        success: function (response) {
            let solicitudes = JSON.parse(response);

            table = new DataTable('#tablaSolicitudes', {
                layout: {
                    topStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
                    }
                },
                "language": {
                    "url": "../../include/espanol_MX.json"
                },
                "pageLength": 50,
                order: [[11, 'desc']],
                data: solicitudes,
                columns: [{
                    data: 'ID'},
                {   data: 'ID_Solicitud'},
                {   data: 'NomRazon'},
                {   data: 'MontoSolicitado'},
                {   data: 'MontoAutorizado'},
                {   data: 'Sucursal'},
                {   data: 'Ejecutivo'},
                {   data: "FechaAlta"},
                {   data: "Estatus"},
                {   data: "Calificacion"},
                {   data: "Detalle"},
                {   data: "FechaModi"},
                {   data: ""},
                {   data: "Eliminar"},
                ],
                
                columnDefs: [{
                    targets: 0, visible: false
                },
                {
                    targets: 11, // Índice de la columna "FechaModi"
                    type: 'date' // Especifica que la columna es de tipo fecha
                },
                {
                    targets: -1,
                    orderable: false,
                    data: null,
                    render: function (data, type, row, meta) {
                        let fila = meta.row, botones = '';
                        if (filtro == 'A' && row['contrato'] == 1 && row['contratoCompleto'] == 1) {
                            botones = `
                                <button class="btn btn-circle" title="Desembolsar" onclick="predesembolso('${row['ID_Solicitud']}', '${row['MontoAutorizado']}', '${row['NomRazon']}', ${row['ClienteID']}); return false;">
                                    <!-- img src="../../img/icono_desembolso.png" style="width: 20px" / -->
                                    <i class="bi bi-coin"></i>
                                </button>
                            `;
                        } else if (filtro != 'D') {
                            botones = `
                                    <button class='btn btn-circle' title="Eliminar Solicitud"
                                        onclick="EliminarSolicitud('${row['ID_Solicitud']}')"; return false;'>
                                        <!-- i class="bi bi-trash" style="font-size:15px;"></!-- -->
                                        <i class="bi bi-file-earmark-excel"></i>
                                    </button>`;
                        }
                        return botones;
                    }
                },
                {
                    targets: -4,
                    orderable: false,
                    data: null,
                    render: function (data, type, row, meta) {
                        // let fila = meta.row;
                        let botones = `
                                <button class='btn btn-circle' title="Ver solicitud"
                                    onclick="DetalleSolicitud('${row['ID_Solicitud']}')"; return false;'>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>`;
                        return botones;
                    }
                },
                {
                    targets: -2,
                    orderable: false,
                    data: null,
                    visible: filtro == 'A',
                    render: function (data, type, row, meta) {
                        // let fila = meta.row;
                        if (filtro == 'A') {
                            let MontoSolicitado = (row['MontoSolicitado'].replace('$', '')).replace(',', '');
                            let MontoAutorizado = (row['MontoAutorizado'].replace('$', '')).replace(',', '');
                            // Contrato = 0 | No hay contrato, icono +
                            // Contrato = 1 y ContratoCompleto = 1 | Contrato terminado, icono file pdf en color verde
                            // Contrato = 1 y ContratoCompleto = 0 | Contrato sin terminar, icono file pdf pero sin color
                            let icono = row['contrato'] == 1 && row['contratoCompleto'] == 0 ? "<i class='bi bi-file-earmark-pdf' style='font-size: 15px;'></i>" :
                                row['contrato'] == 1 && row['contratoCompleto'] == 1 ? "<i class='bi bi-file-earmark-pdf-fill' style='font-size: 15px; color: #02a16d; '></i>" :
                                    "<i class='bi bi-file-earmark-plus-fill' style='font-size: 15px;'></i>";
                            let title = row['contrato'] == 1 && row['contratoCompleto'] == 0 ? "Contrato en proceso" :
                                row['contrato'] == 1 && row['contratoCompleto'] == 1 ? "Contrato completo" :
                                    "Generar contrato";
                            let onclickFnc = row['contrato'] == 1 && row['contratoCompleto'] == 0 ? "showContrato(1)" :
                                row['contrato'] == 1 && row['contratoCompleto'] == 1 ? `showContrato(0, '${row['ID_Solicitud']}')` :
                                    `validarCuentaAhoLineaCredio(${ row['ClienteID'] }, '${ row['ID_Solicitud'] }', ${ MontoSolicitado }, '${ row['Celular'] }', '${ row['Persona'] }', ${ MontoAutorizado }, '${ row['FechaAutoriza'] }', '${ row['NomRazon'] }')`;

                            let botones = `
                                    <button class='btn btn-circle' title="${title}"
                                        onclick="${onclickFnc}"; return false;'>
                                        ${icono}
                                    </button>`;
                            return botones;
                        }
                        return false;
                    }
                },
                {
                    targets: -5,
                    orderable: false,
                    data: null,
                    visible: filtro == 'A',
                    render: function (data, type, row, meta) {
                        if (filtro == 'A') {
                            return botones = `<span>${row['Calificacion']}</span>`;
                        }
                        return false;
                    }
                },
                {
                    className: "dt-center",
                    targets: "_all"
                }
                ],
                layout: {
                    topStart: 'info',
                    bottom: 'paging',
                    bottomStart: null,
                    bottomEnd: null
                }
            });

            if(filtro == 'D')
            {
                table.column(8).visible(false);
                table.column(5).visible(true);
            }
            if(filtro == 'C')
            {
                table.column(8).visible(false);
            }
            if(filtro == 'A')
            {
                table.column(8).visible(false);
            }
        }
    });
}

function DetalleSolicitud(id_solicitud_det) {
    let rutaActual = window.location.host;
    console.log(rutaActual);
    location.href = "/VentAcero/Administracion/RegistroSolicitud/registroSolicitud.php?ID_Solicitud=" + id_solicitud_det;
    // location.replace(rutaActual+"/VentAcero/Administracion/RegistroSolicitud/registroSolicitud.php?Solicitud="+id_solicitud_det);
    // console.log(rutaActual+"/VentAcero/Administracion/RegistroSolicitud/registroSolicitud.php");
}

function EliminarSolicitud(id_sol_eliminar) {
    if (confirm("¿Está seguro de CANCELAR esta solicitud " + id_sol_eliminar + "?") == true) {
        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorSolicitudes.php",
            data: {
                bandera: "Eliminar_Solicitud",
                solicitud_eliminar: id_sol_eliminar
            },
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                alert(response.mensaje);
                CargarSolicitudes(filtroSel);
            }
        });
    }
}

function validarCuentaAhoLineaCredio (...datos) { //Cliente, solicitud, MontoSolicitado, Celular, Persona, MontoAutorizado, fechaAutoriza, razon social
    if (datos[5] <= 0) {
        Toastify({
            text: `El monto autorizado no puede ser $0.00. Favor de validar.`,
            className: "error",
            duration: 5000,
            gravity: "bottom",
            position: "center",
            style: {
                background: "linear-gradient(to right, #ff3636, #de0202)",
            }
        }).showToast();
        return false;
    }

    try {
        // crear cuentaaho y lineascredito
        $.ajax({
            type: 'POST',
            url: "../../Controllers/monitorContratos.php",
            data: {
                bandera: 'Validar_cuentaho',
                cliente: datos[0],
                solicitud: datos[1],
                montoSolicitado: datos[2],
                montoAutorizado: datos[5],
                telefono: datos[3],
                persona: datos[4],
                FechaAutoriza: datos[6],
            },
            cache: false,
            success: async (result) => {
                result = await JSON.parse(result);
                // Aquí para validar la linea de credito
                if (result?.lineacredito_existente) { //ya existe una línea de crédito ver opciones. (Mostrar modal con acciones.)
                    let content_lineas = document.createElement('div');
                    content_lineas.classList.add('lineas_content_box');

                    document.querySelector('#message').innerHTML = `El cliente <b>${ datos[7] }</b> ya cuenta con una o más líneas de crédito. <br/ > Seleccione una opción para continuar.`
                    document.querySelector('#message').style.textAlign = 'center'

                    let titles = document.createElement("p");
                    titles.style.width = '95%';
                    titles.style.marginLeft = 'auto';
                    titles.style.display = 'flex'
                    titles.style.justifyContent = 'space-between'

                    let title1 = document.createElement("span");
                    let title2 = document.createElement("span");
                    let title3 = document.createElement("span");
                    let title4 = document.createElement("span");
                    let title5 = document.createElement("span");

                    title1.innerHTML = "Línea de crédito";
                    title1.style.width = '18%';
                    title1.style.fontWeight = 'bold'
                    title1.style.fontSize = '.75em'
                    title1.style.textAlign = 'center'
                    title2.innerHTML = "Monto Autorizado";
                    title2.style.width = '18%';
                    title2.style.fontWeight = 'bold'
                    title2.style.fontSize = '.75em'
                    title2.style.textAlign = 'center'
                    title3.innerHTML = "Saldo Dispuesto";
                    title3.style.width = '18%';
                    title3.style.fontWeight = 'bold'
                    title3.style.fontSize = '.75em'
                    title3.style.textAlign = 'center'
                    title4.innerHTML = "Saldo en Cuenta";
                    title4.style.width = '18%';
                    title4.style.fontWeight = 'bold'
                    title4.style.fontSize = '.75em'
                    title4.style.textAlign = 'center'
                    title5.innerHTML = "Saldo Disponible en Cuenta";
                    title5.style.width = '18%';
                    title5.style.fontWeight = 'bold'
                    title5.style.fontSize = '.75em'
                    title5.style.textAlign = 'center'
                    titles.append(title1, title2, title3, title4, title5);

                    content_lineas.appendChild(titles)

                    let lineascredito_content = document.createElement('div');
                    lineascredito_content.style.margin = '20px 0';

                    (result.lineascredito).forEach((e, ix) => { /* Por cada cuenta */

                        let button_linea = document.createElement('button');
                        button_linea.style.display = "flex";
                        button_linea.style.width = "100%";
                        button_linea.style.border = "none";
                        button_linea.style.backgroundColor = 'transparent';
                        button_linea.style.cursor = 'pointer';
                        button_linea.style.alignItems = "center";
                        button_linea.style.justifyContent = "center";

                        let radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.value = e.lineacredito;
                        radio.id = "linea" + ix;
                        radio.name = 'lineacreditoselect'
                        // radio.style.marginRight = '10px';

                        button_linea.addEventListener('click', function () {
                            LineaSelected(e.account, e.lineacredito, radio.id)
                        })

                        let parrafo = document.createElement('p')
                        parrafo.id = 'lineas_content'
                        parrafo.style.display = 'flex'
                        parrafo.style.justifyContent = 'space-between'
                        parrafo.style.alignItems = 'center'
                        parrafo.style.width = '100%'
                        parrafo.style.margin = '0'

                        let s_lineaid = document.createElement('span')
                        s_lineaid.style.width = '18%';
                        s_lineaid.innerHTML = e.lineacredito

                        let s_saldo = document.createElement('span')
                        s_saldo.style.width = '18%';
                        s_saldo.innerHTML = "$" + e.linea_dispon

                        let s_dispuesto = document.createElement('span')
                        s_dispuesto.style.width = '18%';
                        s_dispuesto.innerHTML = "$" + e.linea_dispu
                        
                        let s_cuenta = document.createElement('span')
                        s_cuenta.style.width = '18%';
                        s_cuenta.innerHTML = "$" + e.cuenta_saldo

                        let s_dispo_cuenta = document.createElement('span')
                        s_dispo_cuenta.style.width = '18%';
                        s_dispo_cuenta.innerHTML = "$" + e.cuenta_dispon

                        parrafo.append(radio, s_lineaid, s_saldo, s_dispuesto, s_cuenta, s_dispo_cuenta);
                        button_linea.appendChild(parrafo);

                        lineascredito_content.append(button_linea)
                    })
                    
                    content_lineas.appendChild(lineascredito_content)

                    document.querySelector("#ContratosModal .modal-body").appendChild(content_lineas)

                    let div_buttons = document.createElement('div')
                    let btn_incrementar = document.createElement('button')
                    let btn_remplazar = document.createElement('button')
                    let btn_nueva = document.createElement('button')
                    let btn_cancelar = document.createElement('button')

                    btn_incrementar.classList.add('btn','mr-1','btn-sm');
                    btn_incrementar.style.backgroundColor = '#d90000';
                    btn_incrementar.style.color = '#fff';
                    btn_incrementar.innerHTML = 'Incrementar Línea';
                    btn_incrementar.id = 'incrementar_btn'
                    btn_incrementar.disabled = true
                    btn_incrementar.addEventListener('click', () => {
                        let linea = $(btn_incrementar).attr('data_lc')
                        let account = $(btn_incrementar).attr('data_a')
                        lineaCredito(1, account, linea, datos);
                        closeModal.click()
                    })
                    
                    btn_remplazar.classList.add('btn','button-secondary','mr-1','btn-sm');
                    btn_remplazar.innerHTML = 'Remplazar Línea';
                    btn_remplazar.id = 'remplazar_btn'
                    btn_remplazar.disabled = true
                    btn_remplazar.addEventListener('click', () => {
                        let linea = $(btn_remplazar).attr('data_lc')
                        let account = $(btn_remplazar).attr('data_a')
                        lineaCredito(2, account, linea, datos);
                        closeModal.click()
                    })
                    
                    btn_nueva.classList.add('btn','button-secondary','mr-1','btn-sm');
                    btn_nueva.innerHTML = 'Crear Nueva Linea';
                    btn_nueva.addEventListener('click', () => {
                        lineaCredito(3, '', '', datos);
                        closeModal.click()
                    })
                    
                    btn_cancelar.classList.add('btn','btn-light','btn-sm');
                    btn_cancelar.innerHTML = 'Cancelar';
                    btn_cancelar.addEventListener('click', () => {
                        Toastify({
                            text: "No se pudo crear el contrato.",
                            className: "error",
                            duration: 5000,
                            gravity: "bottom",
                            position: "center",
                            style: {
                                background: "linear-gradient(to right, #ff3636, #de0202)",
                            }
                        }).showToast();
                        closeModal.click()
                    })

                    div_buttons.classList.add('modal-footer');
                    div_buttons.append(btn_incrementar, btn_remplazar, btn_nueva, btn_cancelar);

                    document.querySelector("#ContratosModal").classList.add("lineacredito");
                    document.querySelector("#ContratosModal .modal-content").appendChild(div_buttons)

                    myModal.show()
                    return;
                }

                if (result?.error?.length) {
                    if (result.error['crearCuentaLinea']) {
                        // La cuenta no existia, tuvimos que crear una nueva cuentaaho y una lineacredito. Pero falló.
                        Toastify({
                            text: "Falló al crear la cuenta de ahorro y la línea de credito.",
                            className: "error",
                            duration: 5000,
                            gravity: "bottom",
                            position: "center",
                            style: {
                                background: "linear-gradient(to right, #ff3636, #de0202)",
                            }
                        }).showToast();
                    } else if (result.error['crearLinea']) {
                        // La cuenta ya existia pero la línea no. Creamos la lineacredito pero falló.
                        Toastify({
                            text: "Falló al crar la línea de crédito para la cuenta de ahorro existente.",
                            className: "error",
                            duration: 5000,
                            gravity: "bottom",
                            position: "center",
                            style: {
                                background: "linear-gradient(to right, #ff3636, #de0202)",
                            }
                        }).showToast();
                    } else {
                        // Los demás errores corresponden a fallos en la consulta.
                        Toastify({
                        text: "Fallo con las consultas. Error: " + result.error['consultalineacredito'] ? 'consultalineacredito' : 'errorValidarCuentaAho',
                            className: "error",
                            duration: 5000,
                            gravity: "bottom",
                            position: "center",
                            style: {
                                background: "linear-gradient(to right, #ff3636, #de0202)",
                            }
                        }).showToast();
                    }
                } else {
                    // si errores. Todo se creo correctamente. Crear el contrato.
                    GenerarContrato (datos[1]);
                    // alert("Creando el contrato");
                }
            }
        })

    } catch (error) {

    }
}
function LineaSelected (account, linea, radio) {
    /* Activar el botton */
    $("#" + radio).click()

    /* Desbloquear botones */
    $('#incrementar_btn').attr('disabled', false);
    $('#remplazar_btn').attr('disabled', false);

    /* poner la lineadecredito seleccionada */
    $('#incrementar_btn').attr('data_lc', linea);
    $('#remplazar_btn').attr('data_lc', linea);
    
    /* poner la cuenta de la lineadecredito seleccionada */
    $('#incrementar_btn').attr('data_a', account);
    $('#remplazar_btn').attr('data_a', account);

}
function lineaCredito (opc, cuentaaho, lineacredito, ...datos) {
    datos = datos[0];

    $.ajax({
        type: 'POST',
        url: "../../Controllers/monitorContratos.php",
        data: {
            bandera: 'validar_lineacredito',
            opc,
            cuentaaho,
            lineacredito,
            cliente: datos[0],
            sol: datos[1],
            solicitado: datos[2],
            autorizado: datos[5],
            telefono: datos[3],
            persona: datos[4],
            fechaAutoriza: datos[6],
        },
        success: function (result) {
            result = JSON.parse(result)

            if (!result?.error?.length) {
                GenerarContrato (datos[1]);
            } else {
                Toastify({
                    text: "No se pudo crear el contrato.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
        }
    })
}
function GenerarContrato (sol) {
    $.ajax({
        type: 'GET',
        url: "../../administracion/MonitorContratos/contrato.php",
        data: {
            id_solicitud: sol,
        },
        cache: false,
        success: function (response) {
            // console.log(JSON.parse(response));
            if (!response.includes('SinRegistro')) {
                Toastify({
                    text: "Contrato generado correctamente.",
                    className: "success",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                }).showToast();
                return;
            } else {
                Toastify({
                    text: "No se pudo crear el contrato.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
        }
    })
}

// Modal ver contratos
const myModal = new bootstrap.Modal('#ContratosModal', {
    keybodar: true
})
$("#ContratosModal").on('hidden.bs.modal', (e) => {
    if (document.querySelector("#ContratosModal").classList.contains("lineacredito")) {
        document.querySelector("#ContratosModal .modal-content .modal-body .lineas_content_box").remove()
    }
    if (document.querySelector("#ContratosModal").classList.contains("desembolso") || document.querySelector("#ContratosModal").classList.contains("lineacredito")) {
        document.querySelector("#ContratosModal").classList.remove("desembolso");
        document.querySelector("#ContratosModal").classList.remove("lineacredito");
        document.querySelector("#ContratosModal .modal-content .modal-footer").remove()
    }
    $('#Visualizador_Contrato').attr('src', '')
})
function showContrato(opc = 0, solicitud = '') {
    if (opc) {
        // Mostrar el tostify
        Toastify({
            text: "El contrato esta en proceso. Puedes ver el avance de las firmas en el monitor de contratos.",
            className: "info",
            duration: 5000,
            gravity: "center",
            position: "center",
            style: {
                background: "linear-gradient(to right, #0179ff, #0179ff)",
            }
        }).showToast();
        return false;
    }

    // Mostar el archivo en un modal
    let params = `?solicitud=${solicitud}`
    $('#Visualizador_Contrato').attr('src', `../MonitorContratos/MostrarContrato.php${params}`)
    myModal.show()
}

function predesembolso(solicitud, montoAutorizado = '0', nombre = '', cliente) {
    document.querySelector('#message').innerHTML = `Se realizará el desembolso por: ${montoAutorizado}. \n\r Para el cliente ${nombre}.`
    document.querySelector('#message').style.textAlign = 'justify'

    let div_buttons = document.createElement('div')
    let btn_close = document.createElement('button')
    let btn_confirm = document.createElement('button')

    btn_close.classList.add('btn', 'btn-circle', 'mr-2');
    btn_close.innerHTML = 'Cancelar';
    btn_close.addEventListener('click', () => {
        closeModal.click()
    })

    btn_confirm.classList.add('btn', 'btn-circle');
    btn_confirm.innerHTML = 'Continuar';
    btn_confirm.style.backgroundColor = '#d90000';
    btn_confirm.style.color = '#fff';
    btn_confirm.addEventListener('click', () => {
        desembolsarSolicitud(solicitud, cliente)
        closeModal.click()
    })

    div_buttons.classList.add('modal-footer');
    div_buttons.append(btn_close, btn_confirm);

    document.querySelector("#ContratosModal").classList.add("desembolso");
    document.querySelector("#ContratosModal .modal-content").appendChild(div_buttons)

    myModal.show()
}
function desembolsarSolicitud(sol, cliente) {
    $.ajax({
        url: "../../Controllers/monitorSolicitudes.php",
        type: 'POST',
        data: {
            bandera: "desembolsar",
            sol,
            cliente,
        },
        cache: false,
        success: (result) => {
            result = JSON.parse(result)
            console.log(result);

            // errores
            if (result.error['q_solicitud_cons']) {
                Toastify({
                    text: "Fallo al hacer la consulta del montoAutorizado.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['solicitud_update']) {
                Toastify({
                    text: "Fallo al cambiar el estatus de la solicitud a D.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['saldodisponible_cons']) {
                Toastify({
                    text: "Fallo al consultar el saldoDispon de la linea de credito del cliente.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['lineascredito_update']) {
                Toastify({
                    text: "Fallo al hacer el update del SaldoDisponible y el Estatus a V de la linea de credito.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['cuentasaho_saldos_cons']) {
                Toastify({
                    text: "Fallo al hacer la consulta de los saldos de la cuentaaho del cliente.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['saldos_update']) {
                Toastify({
                    text: "Fallo al hacer el update de los saldos en la cuentaaho del cliente.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['saldoBloq_desembolso']) {
                Toastify({
                    text: "Error al realizar el desembolso, el saldo Autorizado es mayor al saldo bloqueado. Favor de contactar a Soporte.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else if (result.error['sin_registros']) {
                Toastify({
                    text: "El cliente no cuenta con una línea de crédito. Favor de validar.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
            else {
                Toastify({
                    text: "Desembolso realizado con éxito.",
                    className: "success",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                }).showToast();
            }

            /* setTimeout(function () {
                location.reload()
            }, 5000) */
        }
    })
}