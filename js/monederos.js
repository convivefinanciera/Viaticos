$(document).ready(function() {
    // $('#tablaLineasCredito').DataTable();
    MostrarMonederos();
});

function CrearTabla(datos) {
    new DataTable('#tablaLineasCredito', {
        'paging': true,
        'pageLength': 50,
        'language': {
            "url": "../../include/espanol_MX.json"
        },
        data: datos,
        columns: [
            {data: 'ClienteID'},
            {data: 'NombreCompleto'},
            {data: 'TarjetaDebid'},
            {data: 'Estatus'},
            {data: 'Saldo'},
            {data: 'SaldoDispon'},
            {data: 'SaldoBloq'},
            {data: ''},
        ],
        columnDefs: [
            {
                target: 3,
                orderable: false,
                render: (data, type, row) => {
                    switch (row['Estatus']) {
                        case 6: /* Vigente */
                            estatus_tarjeta = "Inactiva";
                            break;
                        case 7: /* Vigente */
                            // estatus = `<span style="width: 13px; display: block; height: 13px; background-color: #03C988; border-radius: 7px; margin: 0 auto; border: 1px solid hsl(160 97% 35% / 1); box-shadow: 0 0 5px -2px #03C988;"></span>`;
                            class_estatus = "bi bi-check-circle-fill";
                            style_estatus = "color: #03C988;";
                            estatus_tarjeta = "Activa";
                            break;
                        default: /* Cancelada */
                            estatus_tarjeta = "Cancelada"
                            // estatus = `<span style="width: 13px; display: block; height: 13px; background-color: #03C988; border-radius: 7px; margin: 0 auto; border: 1px solid hsl(160 97% 35% / 1); box-shadow: 0 0 5px -2px #03C988;"></span>`;
                            break;
                    }

                    return `<span>${ estatus_tarjeta }</span>`;
                }
            },
            {
                target: -1,
                orderable: false,
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-circle" onclick="verDetalle(${ row['ClienteID'] }, ${ row['TarjetaDebid'] })">
                            <i class="bi bi-eye" style="font-size: 15px"></i>
                        </button>
                    `
                }
            }
        ]
    })
}

function MostrarMonederos() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monederos.php",
        data: { bandera: 'MostrarMonederos' },
        dataType: "text",
        success: function (response) {
            response = JSON.parse(response)
            // $("#bodyLineasCredito").append(response);
            CrearTabla(response)
        }
    });
}

// document.getElementById('exportButton').addEventListener('click', function() {
//     exportExcel('tablaLineasCredito');
// });

function exportExcel(tabla) {
    var table = document.getElementById(tabla);
    var clone = table.cloneNode(true);

    // Elimina la columna "Ver Detalle"
    var columnIndex = -1;
    for (var i = 0; i < clone.rows[0].cells.length; i++) {
        if (clone.rows[0].cells[i].innerText === "Ver Detalle") {
            columnIndex = i;
            break;
        }
    }

    if (columnIndex !== -1) {
        for (var j = 0; j < clone.rows.length; j++) {
            if (clone.rows[j].cells.length > columnIndex) {
                clone.rows[j].deleteCell(columnIndex);
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
    var filename = "Monederos_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
}

// Modal ver contratos
const myModal = new bootstrap.Modal('#LineasCredito', {
    keyboard: true
})

$("#LineasCredito").on('hidden.bs.modal', (e) => {
})
async function verDetalle (cliente) {
    try {
        if ($.fn.DataTable.isDataTable('#tableDetalle')) {
            $('#tableDetalle').DataTable().destroy();
        }
        $.ajax({
            type: "POST",
            url: "../../Controllers/monederos.php",
            data: {
                bandera: 'MostrarDetalle',
                clienteid: cliente,
            },
            cache: false,
            success: (res) => {
                res = JSON.parse(res);

                if (res.datos.length) {
                    nombreCliente.innerHTML = `Nombre del usuario: <b>${ res.datos[0]['Nombre del Cliente VentAcero'] }</b>`;
                    numeroCliente.innerHTML = `Número del usuario: <b>${ res.datos[0]['Número de cliente'] }</b>`;
                    NumeroCuentaCliente.innerHTML = `Número de Cuenta: <b>${ res.datos[0]['Número de Cuenta'] }</b>`;
    
                    new DataTable('#tableDetalle', {
                        'paging': true,
                        'pageLength': 50,
                        "dom": "Bfrtip", 
                        'language': {
                            "url": "../../include/espanol_MX.json"
                        },
                        "order": [[3, 'desc']],
                        data: res.datos,
                        columns: [
                            {data: 'Crédito Consumo'},
                            {data: 'Monto Consumo'},
                            {data: 'Estatus'},
                            {data: 'Fecha de Consumo'},
                            {data: 'Fecha de liquidación'},
                            {data: 'Fecha Pago'},
                            {data: 'Días transcurridos'},
                        ],
                        columnDefs: [
                            {
                                targets: 3,
                                type: "datetime-moment",
                            },
                            {
                                target: -2,
                                orderable: false,
                                render: (data, type, row) => {
                                    if (!row['Fecha Pago'].includes("1900")) {
                                        return row['Fecha Pago']
                                    } else {
                                        return "Pago Pendiente"
                                    }
                                }
                            },
                            {
                                target: -1,
                                render: function (data, type, row) {
                                    $diasTranscurridos = parseInt(row['DiasTranscurridos']);
                                    // $clasificacion = $row['Clasificacion'];

                                    let $color = ''; // Variable para almacenar el color

                                    if ($diasTranscurridos >= 1 && $diasTranscurridos <= 30) {
                                        $color = '#00cc44';
                                    } else if ($diasTranscurridos >= 31 && $diasTranscurridos <= 60) {
                                        $color = '#ffff00';
                                    } else {
                                        $color = '#ff1a1a';
                                    }
                                    

                                    return `<p style="background-color: ${ $color }; margin: 0; text-align: center;">${ $diasTranscurridos }</p>`;
                                }
                            },
                        ],
                        "buttons": [{ 
                                extend: "excelHtml5", 
                                title: "Reporte Línea de Crédito.", 
                                orientation: "landscape", 
                            }, 
                            // { 
                            //     extend: "pdfHtml5", 
                            //     title: "Reporte Solicitudes Concretadas", 
                            //     orientation: "landscape", 
                            // }, 
                        ], 
                    })
    
                    myModal.show()
                } else {
                    Toastify({
                        text: `El monedero de viáticos no cuenta con registros.`,
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
    } catch (error) {
        console.log(error.message);
        
    }
}