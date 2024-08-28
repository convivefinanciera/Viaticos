$('#cerrarSesion').click(function(){ CerrarSesion(); return false; });

function CerrarSesion() {
    //alert("Cerrar sesion clicked");
    $.ajax({
        type: "POST",
        url: "/Viaticos/Controllers/cerrar_sesion.php",
        data: { cerrar_sesion:"cerrar_sesion", },
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            if (typeof response['success'] === 'undefined') 
            {
                console.log(response);
            }
            else 
            { 
                window.location.replace('/Viaticos/index.php');
            }
        }
    });
}