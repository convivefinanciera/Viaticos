function Mostrar_ReporteColocacion(){
    //$('#ModalEspera').modal('show');

    let formData = new FormData();
        formData.append("bandera", "Mostrar_ReporteColocacion");

    $.ajax({
        type: "POST",
        url: "../../Controllers/colocacion.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (Respuesta) {
            //console.log(Result);
            $("#div_tablaColocacion").html(Respuesta);
            $("a[name='seccion_Colocacion']").removeClass("active");
            $("#a_ReporteColocacion").addClass("active");

            //$('#ModalEspera').modal('hide');

            $("#btn_tablasColocacion").attr("onclick", " Mostrar_ReporteColocacion()");

           $("#tablaObservaciones").DataTable({
                "paging": true,
                //"order": [9, 'desc'],
                "pageLength": 50,
                "dom": "Bfrtip",
                "buttons": [
                    {
                        extend: "excelHtml5",
                        title: "Reporte Colocacion" +(new Date()).toISOString().slice(0,10).replace(/-/g,""),
                        orientation: 'landscape',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: "pdfHtml5",
                        title: "Reporte Colocacion",
                        orientation: 'landscape',
                    }
                ],

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json"
                }
            });
        },
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
    var filename = "Colocacion_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
}