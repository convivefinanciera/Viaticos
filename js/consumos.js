function Mostrar_ReporteConsumos(){
    //$('#ModalEspera').modal('show');

    let formData = new FormData();
        formData.append("bandera", "Mostrar_ReporteConsumos");

    $.ajax({
        type: "POST",
        url: "../../Controllers/consumos.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (Respuesta) {
            //console.log(Result);
            $("#div_tablaConsumos").html(Respuesta);
            $("a[name='seccion_Consumos']").removeClass("active");
            $("#a_ReporteConsumos").addClass("active");

            //$('#ModalEspera').modal('hide');

            $("#btn_tablasConsumos").attr("onclick", " Mostrar_ReporteConsumos()");

           $("#example").DataTable({
                "paging": true,
                "order": [[6, 'desc']],
                "pageLength": 50,
                columnDefs: [
                    {
                        targets: 6,
                        type: "datetime-moment",
                    }
                ],

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json"
                }
            });
        },
    });
}

function funcionConsumos () {
    let formData = new FormData();
        formData.append("bandera", "ConsumosFuncion");

    $.ajax({
        type: "POST",
        url: '../../Controllers/consumos.php',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {
            res = JSON.parse(res);
            console.log(res);

            if (res?.error['fail_insert']) {
                if (res?.error['solver_insert']) {
                    funcionConsumos();
                } else if (res?.error['lineas_dispon']) {
                    
                }
            }
        }
    })
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
    var filename = "Consumos_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
}