function GuardarInfo_Pagina8() {
    toast('Correcto', 'CHIDO', 'Correcto');
    return false;
    // let ID_Solicitud = 'SOL-0000001';

    let TDC = $('input[name="Radios_TDC"]:checked').val();
    let NumTDC = ($("#input_NumTDC").val()).replace(/\D/g, '');
    let CreditoHipo = $('input[name="Radios_CreditoHipo"]:checked').val();
    let CreditoAuto = $('input[name="Radios_CreditoAuto"]:checked').val();
    let Calificacion = $('input[name="Radios_Calificacion"]:checked').val();
    let Autorizado = $("#check_Autorizacion").is(':checked');

    if (TDC == '' || TDC == undefined) {
        alert('Favor de responder la primera pregunta...');
        return false;
    }
    else if (TDC == 'Si' && NumTDC.length != 16) {
        alert('Favor de escribir el numero de tarjeta a 16 dígitos...');
        return false;
    }
    else if (CreditoHipo == '' || CreditoHipo == undefined) {
        alert('Favor de responder la segunda pregunta...');
        return false;
    }
    else if (CreditoAuto == '' || CreditoAuto == undefined) {
        alert('Favor de responder la tercera pregunta...');
        return false;
    }
    else if (Calificacion == '' || Calificacion == undefined) {
        alert('Favor de responder la cuarta pregunta...');
        return false;
    }
    else if (!Autorizado) {
        alert('Para guardar los datos debe autorizar la consulta a buró!');
        return false;
    }

    $.ajax({
        url: "../../Controllers/monitorSolicitudes.php",
        type: "POST",
        data: {
            bandera: "GuardarInfo_Pagina8",
            ID_Solicitud: ID_Solicitud,
            TDC: TDC,
            NumTDC: NumTDC,
            CreditoHipo: CreditoHipo,
            CreditoAuto: CreditoAuto,
            Calificacion: Calificacion,
            Autorizado: Autorizado
        },
        cache: false,
        success: function (Resultado) {
            console.log(Resultado);
            let res = JSON.parse(Resultado);
            console.log(res);

            if (res.estatus == 200) {
                alert(res.mensaje);
            }
            else {
                alert(res.mensaje);
            }
        }
    });
}

function Validar_TDC(dato) {
    let Respuesta = $(dato).val();

    if (Respuesta == 'Si') {
        $("#input_NumTDC").removeAttr('disabled');
    }
    else {
        $("#input_NumTDC").attr('disabled', true).val('');
    }
    console.log(Respuesta);
}

function Formato_DigitosTarjeta(dato) {
    let numero = $(dato).val().split('-').join('');
    if (numero.length > 0) {
        numero = numero.match(new RegExp('.{1,4}', 'g')).join("-");
    }
    $(dato).val(numero);
}

function Autorizar_ConsultaBuro(checkbox) {
    let Autorizado = $(checkbox).is(':checked');
    //let telefono = 8711106886;
    //let ID_Solicitud = 'SOL-0000001';

    $.ajax({
        url: "../../Controllers/monitorSolicitudes.php",
        type: "POST",
        data: {
            bandera: "Autorizar_ConsultaBuro",
            Autorizado: Autorizado,
            ID_Solicitud: ID_Solicitud
        },
        cache: false,
        success: function (Resultado) {
            console.log(Resultado);
            let res = JSON.parse(Resultado);
            console.log(res);

            if (res.estatus == 200) {
                Toastify({
                    text: res.mensaje+".",
                    className: "success",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                      background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                  }).showToast();
                //alert(res.mensaje);

                if (Autorizado && res.enviar_SMS == 'true') {
                    Enviar_FirmaSMS();
                }
            }
            else {
                Toastify({
                    text: res.mensaje+".",
                    className: "error",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                      background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                  }).showToast();
                //alert(res.mensaje);
            }
        }
    });
}

function Enviar_FirmaSMS() {
    //Se consulta el numero donde se enviará el mensaje
    let telefonoRepresentante = $("#inputCelularRepresentante").val();
    $.ajax({
        url: "../../Controllers/monitorSolicitudes.php",
        type: "POST",
        data: {
            bandera: "Buscar_TelefonoAutorizacionBuro"
        },
        cache: false,
        success: function (Resultado) {
            let res = JSON.parse(Resultado);
            console.log(res);
            let nombreRepresentanteLegal = '';
            let celularRepresentanteLegal = '';
            let ID_Solicitud_text = '';

            if (res.estatus == 200) {
                celularRepresentanteLegal = res.telefono;
                nombreRepresentanteLegal = res.nombre;
                if (confirm("Se solicitará la firma de autorización a Buró de Crédito a " + res.nombre + " al teléfono " + res.telefono + "¿Confirma la información?")) {
                    LlamarAPI_SMS(celularRepresentanteLegal, nombreRepresentanteLegal);
                    // let new_window = open('https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=' + (celularRepresentanteLegal) + '&msg=Convive%20Financiera:%20Para%20continuar%20con%20el%20tramite%20de%20tu%20solicitud%20de%20crédito,%20requerimos%20autorización%20para%20consultar%20su%20historial%20crediticio', '_blank');
                    // Close this window
                    // setTimeout(() => { new_window.close(); }, 150);

                    // let new_window1 = open('https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=' + (celularRepresentanteLegal) + '&msg=https://convivetufinanciera.com.mx/Firmaserv_VentAcero/index.php?ID_Solicitud=' + (ID_Solicitud_text), '_blank');
                    // Close this window
                    //setTimeout(() => { new_window1.close(); }, 150);
                }
                else {
                    Toastify({
                        text: "No se ha enviado la autorización.",
                        className: "warning",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                          background: "linear-gradient(to right, #f7db4d, #deb902)",
                        }
                      }).showToast();            
                    //alert("No se ha enviado la autorización.");
                }
            }
            else if (res.estatus == 400) {
                //Si no hay un número guardado en BD tomamos los valores de la sección "Prospección cliente: Nombre del representante legal/Celular representante legal"
                nombreRepresentanteLegal = $("#inputRepresentanteLegal").val();
                celularRepresentanteLegal = $("#inputCelularRepresentante").val();

                if (nombreRepresentanteLegal.length > 5) {
                    if (celularRepresentanteLegal.length == 10) {
                        LlamarAPI_SMS(celularRepresentanteLegal, nombreRepresentanteLegal);
                        // let new_window = open('https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=' + (celularRepresentanteLegal) + '&msg=Convive%20Financiera:%20Para%20continuar%20con%20el%20tramite%20de%20tu%20solicitud%20de%20crédito,%20requerimos%20autorización%20para%20consultar%20su%20historial%20crediticio', '_blank');
                        // Close this window
                        //setTimeout(() => { new_window.close(); }, 150);

                        // let new_window1 = open('https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=' + (celularRepresentanteLegal) + '&msg=https://convivetufinanciera.com.mx/Firmaserv_VentAcero/index.php?ID_Solicitud=' + (ID_Solicitud_text), '_blank');
                        // Close this window
                        //setTimeout(() => { new_window1.close(); }, 150);
                    }
                    else {
                        Toastify({
                            text: "Revisa el número de teléfono del Representante Legal.",
                            className: "warning",
                            duration: 5000,
                            gravity: "bottom",
                            position: "right",
                            style: {
                              background: "linear-gradient(to right, #f7db4d, #deb902)",
                            }
                          }).showToast();   
                        //alert("Revisa el número de teléfono del Representante Legal");
                    }
                }
                else {
                    Toastify({
                        text: "Ingresa el nombre del Representante Legal completo.",
                        className: "warning",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                          background: "linear-gradient(to right, #f7db4d, #deb902)",
                        }
                      }).showToast();            
                    //alert("Ingresa el nombre del Representante Legal completo");
                }
            }
        }
    });

    // let new_window = open('https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=' + (telefono) + '&msg=Convive%20Financiera:%20Para%20continuar%20con%20el%20tramite%20de%20tu%20solicitud%20de%20crédito,%20requerimos%20autorización%20para%20consultar%20su%20historial%20crediticio', '_blank');
    // // Close this window
    // setTimeout(() => { new_window.close(); }, 150);

    // let new_window1 = open('https://sms.contacta.mx:51943/api/v2/sms/send?auth=dXhS-WkJQ-V1o4-ajZu-RUNw-TVJj-MzM0-UT09&phone=' + (telefono) + '&msg=https://convivetufinanciera.com.mx/Firmaserv_VentAcero/index.php?ID_Solicitud=' + (ID_Solicitud), '_blank');
    // // Close this window
    // setTimeout(() => { new_window1.close(); }, 150);
}

function Reenviar_FirmaSMS() {
    let Autorizado = $("#check_Autorizacion").is(':checked');
    //let telefono = 8711106886;
    // let ID_Solicitud = 'SOL-0000001';

    if (Autorizado) {
        Enviar_FirmaSMS();
    }
    else {
        Toastify({
            text: "Favor de autorizar la consulta a buró de crédito.",
            className: "error",
            duration: 5000,
            gravity: "bottom",
            position: "right",
            style: {
              background: "linear-gradient(to right, #ff3636, #de0202)",
            }
          }).showToast();
        //alert('Favor de autorizar la consulta a buró de crédito.');
    }
}

function Buscar_FirmaAutorizacion() {
    //let ID_Solicitud = 'SOL-0000001';

    $.ajax({
        url: "../../Controllers/monitorSolicitudes.php",
        type: "POST",
        data: {
            bandera: "Buscar_FirmaAutorizacion"
        },
        cache: false,
        success: function (Resultado) {
            console.log(Resultado);
            let res = JSON.parse(Resultado);
            console.log(res);

            if (res.estatus == 200) {
                $("#firma_preview").attr('src', res.Archivo);
            }
            else {
                $("#firma_preview").attr('src', '');
            }
        }
    });
}

function LlamarAPI_SMS(tel, nom) {
    let datosAPI = new FormData();

    datosAPI.append('Telefono_RL', tel);
    datosAPI.append('Nombre_RL', tel);

    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorSolicitudes.php",
        data: datosAPI,
        dataType: "dataType",
        success: function (response) {
            
        }
    });
}


function continuar(){

    //guarda los datos
    GuardarInfo_Pagina8();

    //cerrar collapse actual
    $("#collapse8").collapse('hide');

    //abre el siguiente collapse 
    $("#collapse9").collapse('show');


}

function cerrar(){

        // Descarta los cambios y redirige a la página de Monitor Solicitudes
      
        window.location.href = '../administracion/MonitorSolicitudes/monitorSolicitudes.php';
    
}

function regresar(){
    // Guarda la información
    GuardarInfo_Pagina8();

    // Cierra el colapso actual
    $("#collapse8").collapse('hide');

    // Abre el colapso anterior (ajusta el ID según tu estructura)
    // Por ejemplo, si el colapso anterior tiene el ID "collapse1":
    $("#collapse7").collapse('show');
}