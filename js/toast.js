function toast(title, msg, icono) {
    var img = "";
    var tipo = "";
    var opciones = {
        delay: "15000",
        "container-id": "alerta",
    };

    var $container = $("#" + opciones["container-id"]);
    
    if (icono == "") {
        img = "bi bi-chat-right-text-fill";
        tipo = "text-white bg-secondary ";
    } else if (icono == "Info") {
        img = "bi bi-info-circle-fill";
        tipo = "text-white bg-primary";
    } else if (icono == "Correcto") {
        img = "bi bi-check-lg";
        tipo = "text-white bg-success";
    } else if (icono == "Atencion") {
        img = "bi bi-exclamation-lg";
        tipo = "text-white bg-warning";
    } else if (icono == "Error") {
        img = "bi bi-exclamation-octagon-fill";
        tipo = "text-white bg-danger";
    }

    var html = $(
        '<div id="t" class="toast" style="z-index: 5000;" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header ' +
        tipo +
        '"><i class="istyle ' +
        img +
        '"> </i><strong class="me-auto"> ' +
        title +
        '</strong><small>Ahora</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">' +
        msg +
        "</div></div>"
    );
    $container.append(html);

    html.on("click", function () {
        $(this).remove();
    });

    setTimeout(function () {
        html.remove();
    }, opciones["delay"]);
}