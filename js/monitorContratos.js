let semaforos = [];
$(document).ready(() => {
    CargarRegistros('enproceso');
})

function exportExcel (tabla) {
    var table = document.getElementById(tabla);
    var clone = table.cloneNode(true);

    // Elimina las columnas "Detalle" y "Acción" del encabezado
    for (var i = clone.rows[0].cells.length - 1; i >= 0; i--) {
        if (clone.rows[0].cells[i].innerText === "Acciones") {
            for (var j = 0; j < clone.rows.length; j++) {
                clone.rows[j].deleteCell(i);
            }
        }
    }
    var wb = XLSX.utils.table_to_book(clone, {sheet: "Hoja 1"});

    // Obtener la fecha actual
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
    var yyyy = today.getFullYear();

    today = yyyy + mm + dd;
    var filename = "MonitorContratos_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
};

function CargarRegistros (view, opc) {
    let datos;
    switch(view){
        case 'generar':
            break;
        case 'enproceso':
            $.ajax({
                url: "../../Controllers/monitorContratos.php",
                data: {
                    bandera: 'GetSemaforosParams'
                },
                success: (response) => {
                    response = JSON.parse(response)
                    semaforos = (response.datos).map(e => Object.assign({}, e))
                }
            })
            if (opc && $.fn.DataTable.isDataTable('#TablaContratos')) {
                $('#TablaContratos').html('');
                $('#TablaContratos').DataTable().destroy();
            }
            $.ajax({
                url: "../../Controllers/monitorContratos.php",
                data: {
                    bandera: 'Mostrar_Contratos',
                    opc: 'p'
                },
                success: (response) => {
                    CrearTabla(JSON.parse(response))
                }
            })
            break;
        case 'finalizado':
            if (opc && $.fn.DataTable.isDataTable('#TablaContratosCompletos')) {
                $('#TablaContratosCompletos').html('');
                $('#TablaContratosCompletos').DataTable().destroy();
                console.log("Tabla contratos compeltos destruir");
                
            }
            $.ajax({
                url: '../../Controllers/monitorContratos.php',
                data: {
                    bandera: 'Mostrar_Contratos',
                    opc: 'c',
                },
                success: (response) => {
                    CrearTabla(JSON.parse(response),'c');
                }
            })
            break;
    }

    return datos;
}

function ActualizarRegistros() {
    if ($('#divTabla').hasClass('active')) {
        CargarRegistros('enproceso', 1)
    } else {
        CargarRegistros('finalizado', 1)
    }
}

function CrearTabla (datos, nodo) {
    if (!nodo) {
        new DataTable('#TablaContratos', {
            "paging": true,
            "pageLength": 50,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json"
            },
            order: [[]],
            data: datos,
            columns: [
                { data: '#' },
                { data: 'SolicitudID' },
                { data: 'ClienteID' },
                { data: 'NombreCliente' },
                { data: 'TipoPersona' },
                { data: 'MontoAutorizado' },
                { data: 'MontoSolicitado' },
                { data: 'Celular' },
                { data: 'TotalFirmas' },
                { data: 'Firmas' },
                { data: 'FechaCreacion' },
                { data: '' },
            ],
            columnDefs: [
                /* {
                    target: -3,
                    orderable: false,
                    render: (data) => {
                        return (data ? `<i class="bi bi-check-lg text-success"></i>` : '') 
                    }
                }, */
                {
                    target: 0,
                    data: null, 
                    render: (data, type, row, meta) => {
                        return `
                            <span>${ meta.row + 1 }</span>
                        `
                    }
                },
                {
                    target: -2,
                    orderable: false,
                    data: null,
                    createdCell: (td, cellData, rowData, row, col) => {
                        let fechaAlta = new Date(cellData), fechaActual = new Date(), diff = Math.floor((fechaActual - fechaAlta) / (1000 * 60 * 60 * 24)), clase = Semaforo(diff);
                        $(td).addClass(clase);

                    },
                    render: (data) => {
                        let fecha = new Date(data);
                        return `
                            <span>${ fecha.getFullYear() }-${ (fecha.getMonth() + 1) < 10 ? '0' + (fecha.getMonth() + 1) : (fecha.getMonth() + 1) }-${ fecha.getDate() < 10 ? '0' + fecha.getDate() : fecha.getDate() }</span>
                        `
                    }
                },
                {
                    target: -1,
                    orderable: false,
                    render: (data, type, row) => {
                        /* Si queremos agarrar dos datos, en vez de 'data' usamos 'row' y el indice asociativo del campo que queremos de todo el objeto que se le manda a DataTable. */
                        let botones = `
                            <button title="Ver Contrato" class="btn btn-circle" onclick="Mostrar_Contrato('${ row['FirmamexId'] }', '${ row['NombreContrato'] }')">
                                <i class="bi bi-eye" style="font-size: 15px"></i>
                            </button>
                        `;
                        if (row['Estatus']) {
                            botones += `
                                <button title="Validar Firmas" class="btn btn-circle" onclick="Validar_Firmas({ clave: '${ row['FirmamexId'] }', solicitud: '${ row['SolicitudID'] }', cliente: '${ row['ClienteID'] }', contrato: '${ row['NombreContrato'] }'})">
                                    <i class="bi bi-file-check" style="font-size: 15px"></i>
                                </button>
                            `
                        }
                        return botones;
                    }
                },
                /* {
                    target: -1,
                    orderable: false,
                    render: (data, type, row) => {
                        if (row['Estatus']) {
                            return `
                                <button class="btn btn-circle" onclick="Validar_Firmas({ clave: '${ row['FirmamexId'] }', solicitud: '${ row['SolicitudID'] }', cliente: '${ row['ClienteID'] }', contrato: '${ row['NombreContrato'] }'})">
                                    <i class="bi bi-file-check" style="font-size: 15px"></i>
                                </button>
                            `
                        } else {
                            return ''
                        }
                    }
                }, */
            ]
        })
    } else {
        console.log("entro a contratos completos");
        
        new DataTable('#TablaContratosCompletos', {
            "paging": true,
            "pageLength": 50,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json"
            },
            order: [[]],
            data: datos,
            columns: [
                { data: '#' },
                { data: 'ID_Solicitud' },
                { data: 'ID_Cliente' },
                { data: 'NombreDeContacto' },
                { data: 'TipoPersona' },
                { data: 'MontoAutorizado' },
                { data: 'MontoSolicitado' },
                { data: 'Celular' },
                { data: 'FechaAlta' },
                { data: '' },
            ],
            columnDefs: [
                {
                    target: 0,
                    data: null, 
                    render: (data, type, row, meta) => {
                        return `
                            <span>${ meta.row + 1 }</span>
                        `
                    }
                },
                {
                    target: -2,
                    orderable: false,
                    data: null,
                    render: (data) => {
                        let fecha = new Date(data);
                        return `
                            <span>${ fecha.getFullYear() }-${ (fecha.getMonth() + 1) < 10 ? '0' + (fecha.getMonth() + 1) : (fecha.getMonth() + 1) }-${ fecha.getDate() < 10 ? '0' + fecha.getDate() : fecha.getDate() }</span>
                        `
                    }
                },
                {
                    target: -1,
                    orderable: false,
                    render: (data, type, row) => {
                        /* Si queremos agarrar dos datos, en vez de 'data' usamos 'row' y el indice asociativo del campo que queremos de todo el objeto que se le manda a DataTable. */
                        return `
                            <button title="Ver Contrato" class="btn btn-circle" onclick="Mostrar_Contrato('${ row['ID'] }', '', 1)">
                                <i class="bi bi-eye" style="font-size: 15px"></i>
                            </button>
                        `;
                    }
                },
            ]
        })
    }
}

// Modal ver contratos
const myModal = new bootstrap.Modal('#ContratosModal', {
    keybodar: true
})
$("#ContratosModal").on('hidden.bs.modal', (e) => {
    $('#Visualizador_Contrato').attr('src', '')
})

function Mostrar_Contrato (clave, nombre = 'Contrato_Credito', completo) {
    let params = `?firmamexId=${ clave }&ncontrato=${ nombre }`;
    if (completo) {
        params = `?docid=${ clave }`
    }
    $('#Visualizador_Contrato').attr('src', `MostrarContrato.php${ params }`)
    myModal.show()
}

function Mostrar_Notificacion (type, message) {
    $(toast).find(".toast-body").html(message);
    switch (type) {
        case 'fail':
            toast.addClass('bg-danger text-white');
            break;
        case 'success':
            toast.addClass('bg-success text-white');
            break;
        default: //info
            toast.addClass('bg-primary text-white');
            break;
    }

    notification.show();
}

function Semaforo (deff) {/* Semaforo de las firmas. */
    let semaforo = {
        'verde': {...semaforos.find(e => e.Semaforo == 'Verde'), 'class': 'created' },
        'amarillo': {...semaforos.find(e => e.Semaforo == 'Amarillo'), 'class': 'pending'},
        'rojo': {...semaforos.find(e => e.Semaforo == 'Rojo'), 'class': 'exceded'},
    }, clase = '';
    
    clase = deff >= Number((semaforo.verde).Val_Ini) && deff <= Number((semaforo.verde).Val_Fin) ? (semaforo.verde).class :
            deff >= Number((semaforo.amarillo).Val_Ini) && deff <= Number((semaforo.amarillo).Val_Fin) ? (semaforo.amarillo).class :
            deff > Number((semaforo.rojo).Val_Ini) ? (semaforo.rojo).class :
            '';

    return clase
}

// Notificacion Box (Toast)
const toast = $('#notification_validacion')
const notification = new bootstrap.Toast(toast, {
    animation:	true,
    autohide:	true,
    delay:      4000,
});

function Validar_Firmas (data) {
    let { clave, solicitud, cliente, contrato } = data
    $.ajax({
        url: '../../Controllers/monitorContratos.php',
        data: {
            bandera: 'Validar_Firmas',
            clave,
            solicitud,
            cliente,
            contrato,
        },
        success: (response) => {
            let results = JSON.parse(response)
            if (!results.error) {
                CargarRegistros('enproceso', 1);
                Mostrar_Notificacion('success', 'El contrato se valido correctamente.');
            } else {
                Mostrar_Notificacion('fail', 'Algo salió mal. Contacte a soporte.');
            }
        }
    })
}