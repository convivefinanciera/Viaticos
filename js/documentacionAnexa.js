$('<input type="button" value="-" class="minus">').insertBefore('.quantity input[type="number"]');
$('<input type="button" value="+" class="plus">').insertAfter('.quantity input[type="number"]');

$(document).ready(function () {
    // console.log("Cargar avances documentación anexa");
    $("#seccionDocumentosSolicitud").hide();
    if (cargarAvances == true) {
        CargarAvances_Documentacion(); //Se muestran los archivos cargados
        MostrarDocumentacionSolicitud(); //Se muestran la documentación asociada a la solicitud cuando está finalizada (firma, consulta BC, documento firmado, contrato, pagaré)
        MostrarCalificacionesTabla(); //Carga las calificaciones guardadas en la tabla visual
        MostrarDocsVerificadosTabla(); //Cargar las verificaciones de los documentos
    }
    $('.plus').on('click', function () {
        if ($(this).prev().val() && $(this).prev().val() < 100) {
            $(this).prev().val(+$(this).prev().val() + 1);
        }
    });
    $('.minus').on('click', function () {
        if ($(this).next().val() > 1) {
            if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
        }
    });
});

var archivo1, archivo2, archivo3, archivo4, archivo5, archivo6, archivo71, archivo72, archivo73, archivo8 = '';
var fotos = [];
var elementoIDCalificar = "";
var elementoIDParametro = "";
var elementoNombreDoc = "";
var elementoTipoDoc = "";

var inputElement1 = document.getElementById("subirArchivo1"); //Acta constitutiva
inputElement1.myDocID = "1";
inputElement1.myParam = "-1";
inputElement1.addEventListener("change", handleFiles, false);

var inputElement2 = document.getElementById("subirArchivo2"); //Poder notarial
inputElement2.myDocID = "2";
inputElement2.myParam = "-1";
inputElement2.addEventListener("change", handleFiles, false);

var inputElement3 = document.getElementById("subirArchivo3"); //Constancia de situación fiscal
inputElement3.myDocID = "3";
inputElement3.myParam = "5";
inputElement3.addEventListener("change", handleFiles, false);

var inputElement4 = document.getElementById("subirArchivo4"); //Identificación del representante legal
inputElement4.myDocID = "4";
inputElement4.myParam = "3";
inputElement4.addEventListener("change", handleFiles, false);

var inputElement5 = document.getElementById("subirArchivo5"); //Opinión positiva del SAT
inputElement5.myDocID = "5";
inputElement5.myParam = "4";
inputElement5.addEventListener("change", handleFiles, false);

var inputElement6 = document.getElementById("subirArchivo6"); //Estados financieros
inputElement6.myDocID = "6";
inputElement6.myParam = "-1";
inputElement6.addEventListener("change", handleFiles, false);

var inputElement7 = document.getElementById("subirArchivo7"); //Comprobante de domicilio particular
inputElement7.myDocID = "7";
inputElement7.myParam = "6";
inputElement7.addEventListener("change", handleFiles, false);

var inputElement8 = document.getElementById("subirArchivo8"); //Comprobante de domicilio fiscal
inputElement8.myDocID = "8";
inputElement8.myParam = "6";
inputElement8.addEventListener("change", handleFiles, false);

var inputElement9 = document.getElementById("subirArchivo9"); //Comprobante de domicilio del negocio
inputElement9.myDocID = "9";
inputElement9.myParam = "6";
inputElement9.addEventListener("change", handleFiles, false);

// var inputElement10 = document.getElementById("subirArchivo10"); //Estado de cuenta bancario
// inputElement10.myDocID = "10";
// inputElement10.myParam = "-1";
// inputElement10.addEventListener("change", handleFiles, false);

var inputElement10 = document.getElementById("subirArchivo10"); //Fotografías
inputElement10.myDocID = "11";
inputElement10.myParam = "1";
inputElement10.addEventListener("change", handleFiles, false);

function handleFiles(evt) {
    var fileList = this.files;

    let archivosPermitidos = ["jpg", "jpeg", "png", "pdf", "JPG", "JPEG", "PNG", "PDF"];

    var archivoParam = evt.currentTarget.myParam;   //Obtenemos el número de parámetro (-1 significa que no es parámetro a calificar)
    var tipoArchivoID = evt.currentTarget.myDocID;      //Obtenemos el ID TipoDoc 

    //Si son fotografías se guarda el arreglo
    if (archivoParam == "1") {
        var elementoSelec = fileList;
    }
    //Si es cualquier otro archivo se toma sólo "el primero" del arreglo
    else {
        var elementoSelec = evt.currentTarget = fileList[0];
    }

    //Chequeo de tipo de archivos permitidos
    let esTipoValido = false;
    let superaLongitudNombre = false;
    let tipoDoc = '';

    if (archivoParam == 1) //Si son fotos se revisa todo el arreglo
    {
        for (let i = 0; i < elementoSelec.length; i++) 
        {
            let fotoActual = elementoSelec[i];
            let tipoDocFotoActual = fotoActual.type.split('/');
            let largoNombreFotoActual = fotoActual.name.length;

            superaLongitudNombre = largoNombreFotoActual > 150 ? true : false;
            esTipoValido = (archivosPermitidos.indexOf(tipoDocFotoActual[1]) > -1);

            if (superaLongitudNombre == true || esTipoValido == false) //Si no cumple con las condiciones
            {
                break;
            }
        }
    }
    else { //Sólo se revisa el archivo seleccionado
        tipoDoc = elementoSelec.type.split('/');
        let largoNombre = elementoSelec.name.length;

        superaLongitudNombre = largoNombre > 150 ? true : false;
        esTipoValido = (archivosPermitidos.indexOf(tipoDoc[1]) > -1);
    }
    //Finaliza chequeo de tipo de archivo y largo del nombre del archivo

if (esTipoValido == true) {                     //Si es tipo de documento válido
        if (superaLongitudNombre == false) {    //No supera el largo del nombre 150 caracteres
            if (archivoParam != 1) {            //No son las fotografías (que vienen en arreglo)
                let nombreArchivo = "#" + "nombreArchivo" + tipoArchivoID;
                let tamanoArchivo = "#" + "tamanoArchivo" + tipoArchivoID;
                let tipoArchivo = "#" + "tipoArchivo" + tipoArchivoID;
                let calificacionArchivo = "#" + "calificacionArchivo" + tipoArchivoID;

                let tamanoFormateado = formatBytes(elementoSelec.size);

                // $("#nombreArchivo1").append(elementoSelec.name);
                // $("#tamanoArchivo1").append(elementoSelec.size);

                $(nombreArchivo).empty();
                $(tamanoArchivo).empty();
                $(tipoArchivo).empty();

                if(archivoParam != -1) //Archivos que no comprometen la calificación del crédito no se les quita leyenda 'N/A' de la columna calificación
                {
                    $(calificacionArchivo).empty();
                }

                $(nombreArchivo).append(elementoSelec.name);
                $(tamanoArchivo).append(tamanoFormateado);
                $(tipoArchivo).append(tipoDoc[1]);

                //Guardamos el archivo
                Guardar_DocumentosAnexos(elementoSelec, elementoSelec.name, tamanoFormateado, tipoArchivoID, archivoParam);
            }
            else if (archivoParam == 1) { //SE GUARDAN FOTOS (EN ARREGLO)
                let fotosAux = [...fotos]; //clonamos la variable para salvar las fotos agregadas con aterioridad
                // fotosAux = fotos;
                // console.log("Largo fotos aux " + fotosAux.length);

                for (let f = 0; f < elementoSelec.length; f++) {
                    fotos.push(elementoSelec[f]);
                }

                VaciarRenglonesFotos(fotosAux);
                RenglonesFotos(fotos);
                Guardar_DocumentosAnexos(fotos, '', '', tipoArchivoID, archivoParam);
            }
        }
        else {
            Toastify({
                text: "El nombre del archivo es mayor a 150 caracteres.",
                className: "error",
                duration: 5000,
                gravity: "bottom",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #ff3636, #de0202)",
                }
            }).showToast();
        }
    }
    else {
        Toastify({
            text: "Este tipo de documento no es admitido.",
            className: "error",
            duration: 5000,
            gravity: "bottom",
            position: "right",
            style: {
                background: "linear-gradient(to right, #ff3636, #de0202)",
            }
        }).showToast();
    }
}

function VaciarRenglonesFotos(fotosAuxRender) {
    console.log("Largo fotos aux en remover " + fotosAuxRender.length);
    for (let i = 0; i < fotosAuxRender.length; i++) {
        document.getElementById("foto" + (i + 1)).remove();
    }
}

function RenglonesFotos(fotosRender) {
    for (let i = 0; i < fotosRender.length; i++) {
        let tamanoArchivo = formatBytes(fotosRender[i].size);
        // $("#renglonesFotos").append(``);
        insertAfter(document.getElementById("renglonesFotos"), `
            <tr id="foto${i + 1}">
                <th scope="row"></th>
                <td>Foto ${i + 1}<div style="float: right;"></div></td>
                <td id="nombreArchivo9${i + 1}">${fotosRender[i].name}</td>
                <td></td>
                <td></td>
                <td id="detalleArchivo9${i + 1}">
                    <button class='btn btn-circle' onclick="VerDetalle(${i + 1},'','${fotosRender.name}')" data-bs-toggle="modal" data-bs-target="#detalleModal">
                        <i class="bi bi-eye" style="font-size:15px;"></i>
                    </button>
                </td>
                <td id="tamanoArchivo9${i + 1}">${tamanoArchivo}</td>
                <td id="tipoArchivo9${i + 1}"></td>
                <td id="eliminarArchivo9${i + 1}"></td>
            </tr>
        `);
    }
}

function Guardar_DocumentosAnexos(archivoBin, archivoNom, archivoTam, tipoDocID, paramID) {
    var formDocs = new FormData();

    formDocs.append('bandera', 'SubirDocumentos');

    if (paramID != '1') { //No fotos
        formDocs.append('archivoBinario', archivoBin);
        formDocs.append('archivoNombre', archivoNom);
        formDocs.append('archivoTamano', archivoTam);
        formDocs.append('archivoDocID', tipoDocID);
        formDocs.append('archivoParamID', paramID);
    }
    else if (paramID == '1') { //Fotos
        for (let f of fotos) {
            console.log(f.size);
            formDocs.append('archivoBinario[]', f);
            formDocs.append('tamanoArchivo[]', formatBytes(f.size));
            formDocs.append('archivoDocID', tipoDocID);
            formDocs.append('archivoParamID', paramID);
        }
    }

    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: formDocs,
        cache: false,
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        dataType: "html",
        success: function (response) {
            response = JSON.parse(response);

            if (response.estatus == 200) {
                let datosDocumento = response.datosDocumentos;

                if (paramID != '1') //No son fotos
                {
                    let mensaje = response.mensaje;
                    let id_doc = response.id_documento;
                    $("#detalle" + datosDocumento.ID_TipoDoc).attr("onclick", 'VerDetalle(' + datosDocumento.ID_Documento + ', ' + datosDocumento.ID_Parametro + ' , "' + datosDocumento.Nombre_Archivo + '", ' + tipoDocID + ')');
                    $("#eliminarArchivo" + datosDocumento.ID_TipoDoc).attr("onclick", 'EliminarArchivo(' + datosDocumento.ID_Documento + ', "' + datosDocumento.Nombre_Archivo + '", ' + tipoDocID +  ')');
                    document.getElementById("detalle" + tipoDocID).removeAttribute('disabled');
                    document.getElementById("chkVerif"+tipoDocID).checked = true;
                    //alert(mensaje)
                    Toastify({
                        text: mensaje + ".",
                        className: "success",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #4df755, #04c20d)",
                        }
                    }).showToast();


                }
                else //Fotos
                {
                    $("#eliminarArchivo" + datosDocumento.ID_TipoDoc).attr("onclick", 'EliminarArchivo(' + datosDocumento.ID_Documento + ', "' + datosDocumento.Nombre_Archivo + '", ' + tipoDocID + ')');
                    document.getElementById("detalle10").removeAttribute('disabled');
                }
            }
            if (response.estatus == 400) {
                Toastify({
                    text: "Ocurrió un error al cargar tu archivo.",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
        }
    });
}

const insertAfter = (el, htmlString) =>
    el.insertAdjacentHTML('afterend', htmlString);

function CargarAvances_Documentacion() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: { bandera: "CargarAvances_Documentacion" },
        cache: false,
        success: function (response) {
            let documentos = JSON.parse(response);
            let countF = 1;
            for (let i = 0; i < documentos.length; i++) {

                let docActual = documentos[i];

                // let tamanoArchivoF = formatBytes(docActual.Tamanio_Archivo);
                let tipoArchivo = docActual.Nombre_Archivo.split('.');

                if (docActual.ID_TipoDoc == 10) {  //Tipo de documento 10 (FOTOS)
                    // console.log(tipoArchivo);
                    document.getElementById("detalle10").removeAttribute('disabled');

                    // console.log("Cargar documentos avance " + docActual.ID_TipoDoc);
                    // $("#renglonesFotos").append(``);
                    insertAfter(document.getElementById("renglonesFotos"), `
                            <tr id="foto${countF}">
                                <th scope="row"></th>
                                <td>Foto ${countF}<div style="float: right;"></div></td>
                                <td id="nombreFoto${countF}">${docActual.Nombre_Archivo}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <!-- <td id="detalleFoto${countF}">
                                    <button class='btn btn-circle' id="detalleFoto${countF}" onclick="VerDetalle( ${docActual.ID_Documento} , ${docActual.ID_Parametro} , ' ${docActual.Nombre_Archivo} ', ${10})" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal">
                                        <i class="bi bi-eye" style="font-size:15px;"></i>
                                    </button>
                                </td> -->
                                <td id="tamanoFoto${countF}">${docActual.Tamanio_Archivo}</td>
                                <td id="tipoFoto${countF}">${tipoArchivo[tipoArchivo.length - 1]}</td>
                                <td id="eliminarFoto${countF}">
                                    <button class='btn btn-circle' onclick="EliminarArchivo(${docActual.ID_Documento}, '${docActual.Nombre_Archivo}', ${10})">
                                        <i class="bi bi-file-earmark-x" style="font-size:15px;"></i>
                                    </button>
                                </td>
                            </tr>
                        `);

                    // document.getElementById("detalleFoto"+docActual.ID_TipoDoc).removeAttribute('disabled');

                    countF++;
                }
                else if (docActual.ID_TipoDoc < 10) {
                    // console.log("Cargar documentos avance " + docActual.ID_TipoDoc);
                    $("#detalle" + docActual.ID_TipoDoc).attr("onclick", 'VerDetalle(' + docActual.ID_Documento + ', ' + docActual.ID_Parametro + ', "' + docActual.Nombre_Archivo + '", ' + docActual.ID_TipoDoc + ' )');
                    // $("#eliminarArchivo" + docActual.ID_TipoDoc).attr("onclick", 'EliminarArchivo(' + docActual.ID_Documento + ', "' + docActual.Nombre_Archivo + '", this)');
                    $("#eliminarArchivo" + docActual.ID_TipoDoc).attr("onclick", 'EliminarArchivo(' + docActual.ID_Documento + ', "' + docActual.Nombre_Archivo + '", ' + docActual.ID_TipoDoc + ')');
                    document.getElementById("detalle" + docActual.ID_TipoDoc).removeAttribute('disabled');
                }
                let nombreArchivo = "#" + "nombreArchivo" + docActual.ID_TipoDoc;
                let tamanoArchivo = "#" + "tamanoArchivo" + docActual.ID_TipoDoc;
                let tipoArch = "#" + "tipoArchivo" + docActual.ID_TipoDoc;
                let calificacionArchivo = "#" + "calificacionArchivo" + docActual.ID_TipoDoc;

                $(nombreArchivo).empty();
                $(tamanoArchivo).empty();
                $(tipoArch).empty();
                if(docActual.ID_Parametro != -1)
                {
                    $(calificacionArchivo).empty();
                }

                $(nombreArchivo).append(docActual.Nombre_Archivo);
                $(tamanoArchivo).append(docActual.Tamanio_Archivo);
                $(tipoArch).append(tipoArchivo[tipoArchivo.length - 1]);
            }
        }
    });
}

// function VerDetalle(elementoDetalle, nombreArch) {
function VerDetalle(id_tipoDoc, id_Param, nombreDoc, ID_TipoDoc) {
    //Set de variables para calificar
    elementoIDCalificar = id_tipoDoc;
    elementoIDParametro = id_Param;
    elementoNombreDoc = nombreDoc;
    elementoTipoDoc = ID_TipoDoc;

    $("#labelCalificacion").empty();
    $("#labelCalificacion").append(`<h5>Documento: ${nombreDoc}</h5>`);

    //Si el parámetro es -1 (No influye en calificación), inhabilitamos el botón "Calificar"
    if (id_Param == -1) {
        console.log("Parametro es menos 1");
        $("#calificacionArchivo").hide();
    }
    else {
        $("#calificacionArchivo").show();
    }

    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: {
            bandera: 'Cargar_Calificacion_Documento',
            Documento_ID: id_tipoDoc
        },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);
            if (response.estatus == 200) {
                $("#inputCalif").val(response.calificacion);
                $("#inputCalifSlide").val(response.calificacion);
            }
        }
    });

    $('#Visualizador_Contenido').attr('src', `mostrarDetalle.php?docid=${id_tipoDoc}`);
    // $('#Visualizador_Contenido').attr('type', "image/x-png");
    // $('#Visualizador_Contenido').attr('src', `mostrarDetalle.php?docid=${id_tipoDoc}`);
}

function VerDetalleFotos() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/documentacionAnexa.php",
        data: {
            bandera: 'Ver_DetallesFotos'
        },
        cache: false,
        success: function (response) {
            $("#detallesDiv").append(response);
        }
    });
}

function EliminarArchivo(elementoEliminar, nombreEliminar, ID_TipoDoc) {
    if (confirm('Estás por eliminar el archivo "' + nombreEliminar + '", ¿Estás seguro?')) {
        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorSolicitudes.php",
            data: {
                bandera: 'Eliminar_Documento',
                id_doc_eliminar: elementoEliminar
            },
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.estatus == 200) {
                    
                    Toastify({
                        text: response.mensaje + ".",
                        className: "success",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #4df755, #04c20d)",
                        }
                    }).showToast();

                    let nombreArchivo = "#" + "nombreArchivo" + ID_TipoDoc;
                    let tamanoArchivo = "#" + "tamanoArchivo" + ID_TipoDoc;
                    let tipoArchivo = "#" + "tipoArchivo" + ID_TipoDoc;
                    let calificacionArchivo = "#" + "calificacionArchivo" + ID_TipoDoc;
                    let checkArchivo = "chkVerif" + ID_TipoDoc;

                    $(nombreArchivo).empty();
                    $(tamanoArchivo).empty();
                    $(tipoArchivo).empty();
                    $(calificacionArchivo).empty();
                    document.getElementById(checkArchivo).checked = false;
                }
            }
        });
    }
}

function GuardarCalificacion() {
    let califVal = $("#inputCalif").val();

    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: {
            bandera: 'Guardar_CalificacionArchivo',
            ID_Documento: elementoIDCalificar,
            ID_Parametro: elementoIDParametro,
            Nombre_Doc: elementoNombreDoc,
            Calificacion: califVal
        },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);

            // console.log("#calificacionArchivo"+elementoTipoDoc);

            $("#calificacionArchivo"+elementoTipoDoc).empty();
            $("#calificacionArchivo"+elementoTipoDoc).append(califVal);

            // document.getElementById("chkVerif"+elementoTipoDoc).checked = false;

            Toastify({
                text: response.mensaje,
                className: response.toastClass,
                duration: 5000,
                gravity: "bottom",
                position: "center",
                style: {
                    background: response.toastColor,
                }
            }).showToast();

            if (elementoIDParametro == 2) { //Consulta de buró de crédito
                $("#documentoBC").empty();
                console.log(califVal);

                if (califVal < 70.00) {
                    $("#documentoBC").append("<h5 style='color:#9c0000;'>Calificado:" + califVal + "</h5>");
                }
                if (califVal >= 70.00 && califVal < 85.00) {
                    $("#documentoBC").append("<h5 style='color:#8c5400;'>Calificado:" + califVal + "</h5>");
                }
                if (califVal >= 85.00) {
                    $("#documentoBC").append("<h5 style='color:#158c00;'>Calificado:" + califVal + "</h5>");
                }
            }

            //Ejecutamos modal si se autorizó y la posibilidad de modificar el monto autorizado
            // $.ajax({
            //     type: "POST",
            //     url: "../../Controllers/monitorSolicitudes.php",
            //     data: {
            //         bandera: 'Validar_MontoAutorizado',
            //         ID_Documento: elementoIDCalificar,
            //         ID_Parametro: elementoIDParametro,
            //         Nombre_Doc: elementoNombreDoc,
            //         Calificacion: califVal
            //     },
            //     cache: false,
            //     success: function (response) {
            //         response = JSON.parse(response);

            //         Toastify({
            //             text: response.mensaje,
            //             className: response.toastClass,
            //             duration: 5000,
            //             gravity: "bottom",
            //             position: "center",
            //             style: {
            //                 background: response.toastColor,
            //             }
            //         }).showToast();
            //     }
            // });
        }
    });
}

function MostrarDocumentacionSolicitud() {
    //Se verifica si se mostrará la sección de los documentos asociados a la solicitud (Firma autorización, Consulta buró, documento firmado consulta buró, contrato, pagaré)
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: { bandera: 'Solicitud_EstaFinalizada' },
        cache: false,
        success: function (response) {
            let res = JSON.parse(response);
            // console.log(res);
            if (res.estatus == 200) {
                if (res.estatusAvance >= 3 || res.estatusAvance == 'A' || res.estatusAvance == 'C' || res.estatusAvance == 'D') {
                    $("#seccionDocumentosSolicitud").show();
                }
            }
            else {
                console.log(res.mensaje);
            }
        }
    });
}

function formatBytes(bytes, decimals = 2) {
    if (!+bytes) return '0 Bytes'

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}

document.getElementById('inputCalif').addEventListener("change", ValorCalificacionInput);
document.getElementById('inputCalifFotos').addEventListener("change", ValorCalificacionInputFotos);
document.getElementById('minus').addEventListener("click", ValorCalificacionInput);
document.getElementById('minusFotos').addEventListener("click", ValorCalificacionInputFotos);
document.getElementById('plus').addEventListener("click", ValorCalificacionInput);
document.getElementById('plusFotos').addEventListener("click", ValorCalificacionInputFotos);

function ValorCalificacionInput() {
    let valorInput = $("#inputCalif").val();
    if (valorInput >= 100) {
        $("#inputCalif").val('100');
    }
    // var rangeInput = document.getElementById("inputCalifSlide");
    // rangeInput.value = localStorage.getItem(calif);
    $("#inputCalifSlide").val(valorInput);
}

function ValorCalificacionInputFotos() {
    let valorInput = $("#inputCalifFotos").val();
    if (valorInput >= 100) {
        $("#inputCalifFotos").val('100');
    }
    // var rangeInput = document.getElementById("inputCalifSlide");
    // rangeInput.value = localStorage.getItem(calif);
    $("#inputCalifSlide").val(valorInput);
}

function ValorCalificacionSlide(calif) {
    $("#inputCalif").val(calif);
    // let valSlide = $("#inputCalifSlide").val(calif);
}

function ValorCalificacionSlideFotos(calif) {
    $("#inputCalifFotos").val(calif);
    // let valSlide = $("#inputCalifSlide").val(calif);
}

function MostrarCalificacionesTabla() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/documentacionAnexa.php",
        data: { bandera: "Mostrar_CalificacionesTabla" },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);

            for (let i = 0; i < response.length; i++) {
                let califActual = response[i];
                // console.log(califActual);

                $("#calificacionArchivo" + califActual['ID_TipoDoc']).append(califActual['Calificacion']);
                document.getElementById("subirArchivo" + califActual['ID_TipoDoc']).disabled = true;
                // $("#subirArchivo"+califActual['ID_TipoDoc']).attr('d')
            }
        }
    });
}

function MostrarDocsVerificadosTabla() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/documentacionAnexa.php",
        data: { bandera: "MostrarDocs_Verificados" },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);
            console.log(response);
            
            for (let i = 0; i < response.length; i++) {
                let verActual = response[i];
                let id_tipo_doc = verActual['ID_TipoDoc'];

                // console.log(verActual);

                if (id_tipo_doc !== "8" && id_tipo_doc !== "9") {
                    // console.log(id_tipo_doc + " Diferente 8 y 9");
                    if (verActual['Verificacion'] == 1) {
                        document.getElementById("chkVerif" + verActual['ID_TipoDoc']).checked = true;
                        document.getElementById("subirArchivo" + verActual['ID_TipoDoc']).disabled = true;
                    }
                    else {
                        console.log("TipoDoc checando"+verActual['ID_TipoDoc']);
                        document.getElementById("chkVerif" + verActual['ID_TipoDoc']).checked = false;
                    }
                }

            }
        }
    });
}

function VerificarArchivo(checkDoc) {
    let checkDocID = document.getElementById("chkVerif" + checkDoc);

    let verificado = 0;

    if (checkDocID.checked == true) {
        verificado = 1;
    }
    else {
        verificado = 0;
    }

    $.ajax({
        type: "POST",
        url: "../../Controllers/documentacionAnexa.php",
        data: {
            bandera: "Verificar_Documento",
            Documento_ID: checkDoc,
            Verificacion: verificado
        },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);

            if (response.estatus == 200) {
                Toastify({
                    text: response.mensaje,
                    className: response.toastClass,
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: response.toastColor,
                    }
                }).showToast();
                if (response.examinar == 'D') {
                    document.getElementById("subirArchivo" + checkDoc).disabled = true;
                }
                else {
                    document.getElementById("subirArchivo" + checkDoc).disabled = false;
                }
            }
            if (response.estatus == 400) {
                Toastify({
                    text: "Ocurrió un error en la verificación",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
        }
    });
}

function GuardarCalificacionFotos() {
    let calificacionFotos = $("#inputCalifFotos").val();

    $.ajax({
        type: "POST",
        url: "../../Controllers/documentacionAnexa.php",
        data: {
            bandera: "Calificar_FotosNegocio",
            Calificacion_Fotos: calificacionFotos
        },
        cache: false,
        success: function (response) {
            response = JSON.parse(response);

            if (response.estatus == 200) {
                Toastify({
                    text: response.mensaje,
                    className: response.toastClass,
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: response.toastColor,
                    }
                }).showToast();

                if (response.examinar == 'D') {
                    document.getElementById("subirArchivo" + checkDoc).disabled = true;
                }
                else {
                    document.getElementById("subirArchivo" + checkDoc).disabled = false;
                }
            }
            if (response.estatus == 400) {
                Toastify({
                    text: "Ocurrió un error en la verificación",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                }).showToast();
            }
        }
    });
}
// $("#foto9"+(i+1)).append(`
//     <tr id="${i + 1}>
//         <th scope="row"></th>
//         <td>Foto ${i + 1}<div style="float: right;"></div>
//         </td>
//         <td id="nombreArchivo9${i + 1}">${fotosRender[i].name}</td>
//         <td></td>
//         <td id="calificacionArchivo9${i + 1}"></td>
//         <td id="tamanoArchivo9${i + 1}">${tamanoArchivo}</td>
//         <td id="tipoArchivo9${i + 1}"></td>
//         <td id="eliminarArchivo9${i + 1}"></td>
//     </tr>
// `);

// if (i == 0) {
//     $("#foto91").append(`
//             <th scope="row"></th>
//             <td>Foto ${i + 1}<div style="float: right;"></div>
//             </td>
//             <td id="nombreArchivo9${i + 1}">${fotosRender[i].name}</td>
//             <td></td>
//             <td id="calificacionArchivo9${i + 1}"></td>
//             <td id="tamanoArchivo9${i + 1}">${tamanoArchivo}</td>
//             <td id="tipoArchivo9${i + 1}"></td>
//             <td id="eliminarArchivo9${i + 1}"></td>
//             <tr id="${i + 2}"></tr>
//     `);
// }
// else {
//     $("#foto9" + (i + 1)).append(`
//         <tr id="foto9${i + 1}">
//             <th scope="row"></th>
//             <td>Foto ${i + 1}<div style="float: right;"></div>
//             </td>
//             <td id="nombreArchivo9${i + 1}">${fotosRender[i].name}</td>
//             <td></td>
//             <td id="calificacionArchivo9${i + 1}"></td>
//             <td id="tamanoArchivo9${i + 1}">${tamanoArchivo}</td>
//             <td id="tipoArchivo9${i + 1}"></td>
//             <td id="eliminarArchivo9${i + 1}"></td>
//         </tr>
//     `);
// }