$(document).ready(() => {
    mostrarLog()
})

function mostrarLog () {
    $.ajax({
        type: "POST",
        url: "../../Controllers/gestion.php",
        data: {
            bandera: 'mostrar_log'
        },
        success: res => {
            res = JSON.parse(res);

            if (!res.error) {
                creartabla(res.datos)
            } else {
                Toastify({
                    text: `No se pudieron cargar los datos.`,
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
/* function exportExcel(tabla) {
    var table = document.getElementById(tabla);
    var clone = table.cloneNode(true);
    
    var wb = XLSX.utils.table_to_book(clone, {sheet: "Hoja 1"});

    // Obtener la fecha actual
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
    var yyyy = today.getFullYear();

    today = yyyy + mm + dd;
    var filename = "LogGestion_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
} */

function creartabla (datos) {
    new DataTable('#TablaGestion', {
        "paging": true,
        "pageLength": 50,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json"
        },
        "dom": "Bfrtip", 
        order: [[]],
        data: datos,
        columns: [
            { data: 'TarDebMovID' },
            { data: 'TipoMensaje' }, //1 NV
            { data: 'TipoOperacionID' },
            { data: 'TarjetaDebID' },
            { data: 'OrigenInst' }, //4 NV
            { data: 'MontoOpe' },
            { data: 'FechaHrOpe' },
            { data: 'NumeroTran' }, //7 NV
            { data: 'GiroNegocio' }, //8 NV
            { data: 'PuntoEntrada' }, //9 NV
            { data: 'TerminalID' }, //10 NV
            { data: 'NombreUbicaTer' },
            { data: 'NIP' }, //12 NV
            { data: 'CodigoMonOpe' }, //13 NV
            { data: 'MontosAdiciona' }, //14 NV
            { data: 'MontoSurcharge' }, //15 NV
            { data: 'MontoLoyaltyfee' }, //16 NV
            { data: 'Referencia' }, //17 NV
            { data: 'DatosTiempoAire' }, //18 NV
            { data: 'EstatusConcilia' }, //19 NV
            { data: 'FolioConcilia' }, //20 NV
            { data: 'DetalleConciliaID' }, //21 NV
            { data: 'TransEnLinea' }, //22 NV
            { data: 'CheckIn' }, //23 NV
            { data: 'CodigoAprobacion' }, //24 NV
            { data: 'Estatus' },
            { data: 'EmpresaID' }, //26 NV
            { data: 'Usuario' }, //27 NV
            { data: 'FechaActual' }, //28 NV
            { data: 'DireccionIP' }, //29 NV
            { data: 'ProgramaID' }, //30 NV
            { data: 'Sucursal' }, //31 NV
            { data: 'NumTransaccion' },
        ],
        columnDefs: [
            {
                target: [ 1, 4, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 26, 27, 28, 29, 30, 31 ],
                visible: false
            }
        ],
        "buttons": [
            { 
                extend: "excelHtml5", 
                title: "Reporte Log Gestión", 
                text: "Exportar a Excel",
                className: "btn btn-success",
                orientation: "landscape", 
            }, 
            { 
                extend: "pdfHtml5", 
                title: "Reporte Log Gestión", 
                orientation: "landscape", 
            }, 
        ], 
    })
}