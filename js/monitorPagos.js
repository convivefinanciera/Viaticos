$.ajax({
    type: "POST",
    url: "../../Controllers/monitorPagos.php",
    data: {
        bandera: 'Detalles'
    },
    success: function (response) {
        let datos = JSON.parse(response);

        console.log(datos);

        table = new DataTable('#tablaPagos', {
            searching: false,
            pageLength: 100,
            info: false,
            data: datos,
            columns: [
                { data: 'LineaCreditoID', title: 'Linea de Crédito' },
                { data: 'ClienteID', title: 'Cliente ID' },
                { data: 'Nombre cliente', title: 'Nombre Cliente' },
                { data: 'CreditoID', title: 'Credito ID' },
                { data: 'FechaPago', title: 'Fecha de Pago' },
                { data: 'TOTAL PAGO', title: 'Total Pago' },
                { data: 'Capital', title: 'Capital' },
                { data: 'Interes', title: 'Interés' },
                { data: 'Moratorio', title: 'Moratorio' },
                { data: 'IVA', title: 'IVA' }
            ]
        });
    }
});

function exportExcel() {
    const table = document.querySelector('#tablaPagos');
    const wb = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
    // Obtener la fecha actual
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
    var yyyy = today.getFullYear();

    today = yyyy + mm + dd;
    var filename = "MonitorPagos_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
}


