$(document).ready(function() {
    MostrarComprobantes();
});

function MostrarComprobantes() {
    let formData = new FormData();
    formData.append("bandera", "MostrarComprobantes");

    $.ajax({
        type: "POST",
        url: "../../Controllers/comprobantes.php",
        data: { bandera: 'MostrarComprobantes' },
        dataType: "text",
        success: function (response) {
            response = JSON.parse(response);
            
            console.log(response);

            // Destruir la tabla existente antes de volver a crearla
            if ($.fn.DataTable.isDataTable('#tablaComprobantes')) {
                $('#tablaComprobantes').DataTable().clear().destroy();
            }

            CrearTablaComprobantes(response);
        }
    });
}

function CrearTablaComprobantes(datos) {
    new DataTable('#tablaComprobantes', {
        'paging': true,
        'pageLength': 50,
        'language': {
            "url": "../../include/espanol_MX.json"
        },
        data: datos,
        columns: [
            {data: 'nombre'}, //Nombre comprobante
            {data: 'tamanio'},
            {data: 'descripcion'}, //Tipo de comprobante
            {data: 'NumTransaccion'},
            {data: 'TipoMovimiento'},
            {data: 'TarjetaDebID'},
            {data: 'NombreCompleto'},
            {data: 'FechaHrOpe'},
            {data: 'MontoOperacion'},
            {data: ''},
        ],
        columnDefs: [
            {
                target: -1,
                orderable: false,
                render: (data, type, row) => {
                    return `
                        <button class="btn btn-circle" data-bs-target="#detalleComprobante" data-bs-toggle="modal" onclick="VerDetalleComprobante(${ row['ID'] }, '${ row['TarjetaDebID'] }', '${ row['nombre'] }', '${ row['descripcion'] }', '${ row['TipoMovimiento'] }')">
                            <i class="bi bi-eye" style="font-size: 15px"></i>
                        </button>
                    `
                }
            }
        ]
    })
}

function VerDetalleComprobante(id_comprobante, tarjeta, nomComprobante, tipoComprobante, tipoMovimiento){
    $("#numTarjeta").empty();
    $("#nombreComprobante").empty();
    $("#tipoComprobante").empty();
    $("#tipoMovimiento").empty();

    // Llenar los campos con los datos proporcionados
    $("#numTarjeta").append(tarjeta);
    $("#nombreComprobante").append(nomComprobante);
    $("#tipoComprobante").append(tipoComprobante);
    $("#tipoMovimiento").append(tipoMovimiento);

    // Realizar la solicitud AJAX
    $.ajax({
        type: "POST",
        url: "../../Controllers/comprobantes.php",  // URL del controlador PHP
        data: { 
            bandera: 'CargarComprobante',   // Parámetro para determinar qué acción realizar
            id_comp : id_comprobante        // ID del comprobante a cargar
        },
        dataType: "text",   // Espera recibir HTML como respuesta
        success: function (response) {
            // Limpiar el área donde se mostrará el comprobante
            $("#mostrarComprobante").empty();
            
            // Insertar la respuesta (que puede ser una imagen o un PDF)
            $("#mostrarComprobante").append(response);
        },
        error: function(xhr, status, error) {
            // Manejo de errores si la solicitud falla
            console.error("Error al cargar el comprobante: ", error);
            alert("Hubo un problema al cargar el comprobante. Por favor, inténtalo de nuevo.");
        }
    });
}

function exportExcel(tabla) {
    var table = document.getElementById(tabla);
    var clone = table.cloneNode(true);
    
    var wb = XLSX.utils.table_to_book(clone, {sheet: "Hoja 1"});

    // Obtener la fecha actual
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
    var yyyy = today.getFullYear();

    today = yyyy + mm + dd;
    var filename = "Comprobantes" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
}