$(document).ready(function () {
    if (cargarAvances == true) {
        Mostrar_TablaObservaciones();
    }
});

function Mostrar_TablaObservaciones() {
    let formData = new FormData();
    formData.append("bandera", "Mostrar_TablaObservaciones");

    $.ajax({
        type: "POST",
        url: "../../Controllers/observaciones.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (Respuesta) {
            $("#div_tablaObservaciones").html(Respuesta);
            $("a[name='seccion_Observaciones']").removeClass("active");
            $("#a_ReporteObservaciones").addClass("active");
            
            if(!Respuesta.includes('No registros'))
            {
                $("#tablaObservaciones").DataTable({
                    "order": [],
                    paging: true,
                    pageLength: 50,
                    dom: "Bfrtip",
                    buttons: [
                        {
                            extend: "excelHtml5",
                            title: "Reporte Observaciones " + (new Date()).toISOString().slice(0, 10).replace(/-/g, ""),
                            filename: "Reporte Observaciones " + (new Date()).toISOString().slice(0, 10).replace(/-/g, ""),
                            text: 'Excel'
                        },
                    ],
                    language: {
                        url: "../../include/espanol_MX.json"
                    }
                });
            }
        },
    });
}


function agregarObservacion() {
    
    let formData = new FormData();
    formData.append("bandera", "Agregar_Observacion");
    formData.append("observacion", $("#observacion").val());

    $.ajax({
        type: "POST",
        url: "../../Controllers/observaciones.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (Respuesta) {
            Respuesta = JSON.parse(Respuesta);
            if (Respuesta.estatus == 200) {
                Toastify({
                    text: Respuesta.mensaje,
                    className: "success",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                }).showToast();

                $("#observacion").val("");
            }
            if (Respuesta.estatus == 400) {
                Toastify({
                    text: Respuesta.mensaje,
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }

            $("#agregarObservacionModal").modal("hide");

            Mostrar_TablaObservaciones(); // Actualiza la tabla después de agregar la observación
        },
    });
}
