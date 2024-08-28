const $canvas = document.querySelector("#cnv"), 
    $btnLimpiar = document.querySelector("#btnLimpiar"),
    contexto = $canvas.getContext("2d"),
    COLOR_PINCEL = "black",
    COLOR_FONDO = "white",
    GROSOR = 2,
    section_elmnt = document.querySelector("#main_content"),
    solicitud = document.querySelector("#btnFirmar").getAttribute('data'),
    modal = document.querySelector("body");

let initialX, initialY, haComenzadoDibujo = false, firmado = false, registrado = false;
/* Monitorear la orientacion de la pantalla */
document.addEventListener("DOMContentLoaded", (event) => {
    definePortraitLandscape()
});
window.addEventListener('orientationchange', function() {
    let orientationChange = () => {
        definePortraitLandscape()
        window.removeEventListener('resize', orientationChange);
    }
    window.addEventListener('resize', orientationChange);
});
function definePortraitLandscape () {
    if (section_elmnt.classList.contains('landscape')) section_elmnt.classList.remove("landscape");
    if (section_elmnt.classList.contains('portrait')) section_elmnt.classList.remove("portrait");

    if (window.matchMedia('(orientation: portrait)').matches === true) {
        section_elmnt.classList.add("portrait");
        $canvas.width = 300; //window.innerWidth * .70
        $canvas.height = 650; //window.innerHeight * .90
    } else {
        section_elmnt.classList.add("landscape");
        $canvas.width = 650; //window.innerWidth * .90
        $canvas.height = 250; //window.innerHeight * .70
    }
}
function startToSign () {
    modal.classList.toggle("recomendation")
}
/* ------ */
/* VALIDAR QUE NO ESTE LA FIRMA PARA BURO EN EL DOCS */
(function () {
    fetch("Controllers/firmaserv.php?action=validar", {
        method: 'POST',
        body: JSON.stringify({
            solicitud
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.error) {
            throw new Error("No se pudo realizar la consulta.");
        } else {
            if (res.firmada) {
                // openCloseModal("Ya se ha autorizado la consulta del historial crediticio para esta solicitud de crédito.");
                openCloseModal("Ya existe una autorizacion previa para la solicitud " + solicitud);
                closeModal.style.display = 'none'
                document.querySelector("#btnFirmar").disabled = true;
                registrado = true;
            } else {
                if (section_elmnt.classList.contains("portrait")) {
                    modal.classList.add("recomendation")
                }
            }
        }
    })
})()
/* ----- */



/* Limpiar Canvas */
const limpiarCanvas = () => {
    contexto.fillStyle = COLOR_FONDO;
    contexto.fillRect(0, 0, $canvas.width, $canvas.height);
    firmado = false;
};
$btnLimpiar.onclick = limpiarCanvas;
/* ------ */
/* Comienza Dibujar en el Canvas */
const dibujar = (cursorX, cursorY) => {
    contexto.beginPath();
    contexto.moveTo(initialX, initialY);
    
    const rect = $canvas.getBoundingClientRect();
    const mouseX = cursorX - rect.left;
    const mouseY = cursorY - rect.top;

    contexto.lineWidth = GROSOR;
    contexto.stokeStyle = COLOR_PINCEL;
    contexto.lineCap = "round";
    contexto.lineJoin = "round";
    contexto.lineTo(mouseX, mouseY);
    contexto.stroke();

    initialX = mouseX;
    initialY = mouseY;
}
/* ------ */
/* EVENTOS DEL MOUSE CUANDO DIBUJA */
$canvas.addEventListener("mousedown", e => {
    const rect = $canvas.getBoundingClientRect();
    initialX = e.clientX - rect.left;
    initialY = e.clientY - rect.top;

    haComenzadoDibujo = true;
});
$canvas.addEventListener("mousemove",(evento) => {
    if (!haComenzadoDibujo) {
        return;
    }
    dibujar(evento.clientX, evento.clientY);
    firmado = true;
    
});
["mouseup", "mouseout"].forEach(nombreDeEvento => {
    $canvas.addEventListener(nombreDeEvento, () => {
        haComenzadoDibujo = false;
    });
});
/* ------ */
/* EVENTOS DEL TOUCH EN EL CELULAR */
$canvas.addEventListener("touchstart", function(e) { 
    getTouchPos(e)
    var touch = e.touches[0]; 
    var mouseEvent = new MouseEvent("mousedown", { 
        clientX: touch.clientX, 
        clientY: touch.clientY 
    }); 
    $canvas.dispatchEvent(mouseEvent); 
});
$canvas.addEventListener("touchend", function(e) { 
    var mouseEvent = new MouseEvent("mouseup", {}); 
    $canvas.dispatchEvent(mouseEvent);
}, false);
$canvas.addEventListener("touchmove", function(e) { 
    firmado = true;
    var touch = e.touches[0]; 
    var mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    $canvas.dispatchEvent(mouseEvent);
}, false);
function getTouchPos(touchEvent) {
    var rect = $canvas.getBoundingClientRect();
    initialX = touchEvent.touches[0].clientX - rect.left;
    initialY = touchEvent.touches[0].clientY - rect.top;
}
/* ------ */
// Evitar Scroll cuando esta dibujando en el canvas (mobile)
document.body.addEventListener("touchstart", function(e) {
    if (e.target == $canvas) { 
        e.preventDefault(); 
    } 
}, {passive: false }); /* Lo del passive false sirve para que este evento no tome como passiva la funcion. O algo así */
document.body.addEventListener("touchend", function(e) {
    if (e.target == $canvas) { 
        e.preventDefault(); 
    } 
}, {passive: false }); 
document.body.addEventListener("touchmove", function(e) {
    if (e.target == $canvas) { 
        e.preventDefault(); 
    }
}, {passive: false });
/* ------ */



/* VALIDAR QUE EL USUARIO FIRMO */
function validarFirma () {
    if (!firmado) {
        return false;
    }

    return true;
}
/* ----- */
/* ABRIR O CERRAR MODAL */
function openCloseModal (text = '') {
    message_modal.innerHTML = text;
    modal.classList.toggle("modalOpen");
}
/* ----- */
/* BTN FIRMAR */
document.querySelector("#btnFirmar").addEventListener('click', () => {
    
    if (validarFirma()) {
        
        let dataUrl = $canvas.toDataURL();
        let span = document.createElement('span');
        let image_box = document.createElement('div');
        let image = new Image;

        if (section_elmnt.classList.contains("portrait")) {
            image_box.classList.add('rotate');
        } else {
            image_box.classList.remove('rotate');
        }
        image.src = convertBase64ToLinkBlob(dataUrl);
        
        image_box.append(image)
        
        span.innerHTML = "Tu firma se verá de la siguiente manera. ¿Deseas continuar?";
        
        message_modal.innerHTML = ''
        message_modal.append(span, image_box)

        closeModal.innerHTML = "Reintentar" /* Este boton va a cerrar el modal. */
        closeModal.addEventListener('click', () => {
            if (document.querySelector(".modal_container").classList.contains("confirmSign")) {
                document.querySelector(".modal_container").classList.remove("confirmSign")
            }
        })
        confirmSign.addEventListener('click', () => {
            closeModal.innerHTML = closeModal.innerHTML == "Reintentar" ? 'Cerrar' : closeModal.innerHTML;
            if (document.querySelector(".modal_container").classList.contains("confirmSign")) {
                document.querySelector(".modal_container").classList.remove("confirmSign")
            }
        })

        document.querySelector(".modal_container").classList.add("confirmSign");
        modal.classList.toggle("modalOpen");
    } else {
        openCloseModal("Firma en el recuadro para continuar.");
    }

})
/* ----- */
/* BTN CONFIRMAR FIRMA */
function Firmar() {
    let dataUrl = $canvas.toDataURL();

    if (validarFirma()) {
        try {
            fetch("Controllers/firmaserv.php?action=firmar", {
                method: 'POST',
                body: JSON.stringify({ 'url': dataUrl, solicitud, 'orientation': (section_elmnt.classList.contains('portrait') ? 'V' : 'H') }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (!res.error) {
                    document.querySelector("#btnFirmar").disabled = true;
                    openCloseModal("", true); /* no borrar */
                    openCloseModal("Firma Guardada correctamente. Puede continuar su trámite de solicitud de crédito.")
                } else {
                    if (res.solicitud) {
                        openCloseModal("No se especifico una solicitud.")
                    } else {
                        throw new Error("Fallo la consulta");
                    }
                }
            });
            
        } catch (error) {
            throw new Error(error.message)
        }
    } else {
        openCloseModal("Firma en el recuadro para continuar.");
    }
}
/* CONVERTIR EL CANVAS A IMAGEN. PARA LA VISUALIZACION DE LA FIRMA */
function convertBase64ToLinkBlob (stringBase64) {
    let dataImage = stringBase64.split(',')
    //convertir la base64 a arraybuffer
    let bs = window.atob(dataImage[1]);
    let len = bs.length;
    let bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = bs.charCodeAt(i);
    }
    let blob = new Blob([ bytes.buffer ], { type: 'application/png' });
    let urlToReturn = URL.createObjectURL(blob);

    let iOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    if (iOS) {
        // Crear un nuevo objeto URL utilizando el constructor
        urlToReturn = new URL(urlToReturn).href;
    }

    return urlToReturn;
}
/* ----- */