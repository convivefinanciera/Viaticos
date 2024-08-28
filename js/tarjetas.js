$(document).ready(function () {
    $("#bodyTablaResumen").empty();
    // if (table != null) {
    //     table.clear().draw();
    //     table.destroy();
    // }

    $.ajax({
        type: "POST",
        url: "../../Controllers/tarjetas.php",
        data: {
            bandera: 'Resumen'
        },
        // dataType: "dataType",
        success: function (response) {
            let tarjetas = JSON.parse(response);

            console.log(tarjetas);

            table = new DataTable('#tablaResumen', {
                searching: false,
                paging: false,
                info: false,
                data: tarjetas,
                columns: [
                    {
                        data: 'CB_Estatus'
                    },
                    {
                        data: 'Total'
                    }
                ]
            });
        }
    });

    $("#bodyTablaDetalle").empty();
        // if (table != null) {
        //     table.clear().draw();
        //     table.destroy();
        // }

    $.ajax({
        type: "POST",
        url: "../../Controllers/tarjetas.php",
        data: {
            bandera: 'Detalles'
        },
        // dataType: "dataType",
        success: function (response) {
            let tarjetas = JSON.parse(response);

            console.log(tarjetas);

            table = new DataTable('#tablaDetalle', {
                searching: false,
                pageLength: 100,
                info: false,
                data: tarjetas,
                columns: [
                    {
                        data: 'Folio'
                    },
                    {
                        data: 'Numero de Tarjeta'
                    },
                    {
                        data: 'Estatus'
                    },
                    {
                        data: 'Fecha'
                    }
                ]
            });
        }
    });
});

function exportExcel(tabla) {
    var table = $('#' + tabla).DataTable();
    var data = table.rows().data().toArray();
    var clone = document.createElement('table');
    var thead = document.createElement('thead');
    var tbody = document.createElement('tbody');

    // Crear encabezado manualmente con los nombres corregidos
    var headerRow = document.createElement('tr');
    var headers = ['Folio', 'Terminacion Tarjeta', 'Estatus'];
    headers.forEach(function(header) {
        var th = document.createElement('th');
        th.innerText = header;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    clone.appendChild(thead);

    // Agregar los datos al cuerpo de la tabla clonada, omitiendo la columna "Numero de Tarjeta"
    data.forEach(function(row) {
        var tr = document.createElement('tr');
        var keys = Object.keys(row);
        keys.forEach(function(key) {
            if (key !== 'Numero de Tarjeta') {
                var td = document.createElement('td');
                td.innerText = row[key];
                tr.appendChild(td);
            }
        });
        tbody.appendChild(tr);
    });

    clone.appendChild(tbody);

    var wb = XLSX.utils.table_to_book(clone, {sheet: "Hoja 1"});

    // Obtener la fecha actual
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
    var yyyy = today.getFullYear();

    today = yyyy + mm + dd;
    var filename = "InventarioTarjetas_" + today + ".xlsx";

    XLSX.writeFile(wb, filename);
}

function activarTDC() {
    actualizarTarjeta('11', $('#cliente').val(), $('#tarjeta').val(), '', '', 'activar');
}

function cancelarTDC(cliId, value) {
    const motivo = document.getElementById('idSelecMotivo').value;
    const index = document.getElementById('idSelecMotivo').selectedIndex;
    const descripcion = document.getElementById('idSelecMotivo').options[index].innerText + ' ' + $('#idObser').val();
    actualizarTarjeta('28', cliId, value, descripcion, motivo, 'cancelar');
}

function reemplazarTDC(){
    const tarjetaA = document.getElementById('tarjeta_a').value;
    const tarjetaN = document.getElementById('tarjeta_n').value;
    reemplazarTarjeta(tarjetaA, tarjetaN);
}

/*
 * Función para limpiar los input
 * @returns {undefined}
 */
function clearDataClient() {
    $('#ClientesRen').html('');
    $('#bamigo').val('');
    $('#myModalSCte').modal('show');
}

function clearDataCard(campoRes, campoTar, modal) {
    $(campoRes).html('');
    $(campoTar).val('');
    $(modal).modal('show');
}

function actualizarTarjeta(estatus, clienteID, dato, descripcion, valor, tipo) {
    var datos = {
        estatus: estatus,
        idCliente: clienteID,
        dato: dato,
        descripcion: descripcion,
        valor: valor,
        tipo: tipo,
        numeroUsuario: $('#idUsuarioConec').val(),
        proceso: 'actualizarTarjeta'
    };
    console.log(datos);
    $.ajax({
        url: '../../Controllers/cambiarEstatusTDC.php',
        type: 'POST',
        dataType: "html",
        data: datos,
        cache: false
    }).done(function(response) {
        console.log(response);
        let res = JSON.parse(response);
        console.log(res);
        //return false;

        if(res.code == '200') {
            if (estatus == '11') {
                $('#idModalElegirEstado').modal('hide');
                $('#alert_success').show();
                setTimeout(function() {
                    $('#alert_success').hide(); // Oculta el elemento cambiando su estilo
                }, 5000);
            } else {
                $('#idModalElegirEstadoCancelar').modal('hide');
                $('#alert_success_cancel').show();
                setTimeout(function() {
                    $('#alert_success_cancel').hide(); // Oculta el elemento cambiando su estilo
                }, 5000);
            }
            //$('#alert_success').show();
        }
        else
            alert(res['message']);
    }).fail(function() {
        alert('Fallo la conexión a internet, verifique');
    }).always(function() {
        $('#idCambiarEstatus').text('Aceptar');
        $('#idCambiarEstatus').prop('disabled', false);
    });
}

function reemplazarTarjeta(tarjetaA, tarjetaN) {
    var datos = {
        tarjetaA: tarjetaA,
        tarjetaN: tarjetaN,
        numeroUsuario: $('#idUsuarioConec').val(),
        proceso: 'reemplazarTarjeta'
    };
    console.log(datos);
    $.ajax({
        url: '../../Controllers/cambiarEstatusTDC.php',
        type: 'POST',
        dataType: "html",
        data: datos,
        cache: false
    }).done(function(response) {
        console.log(response);
        let res = JSON.parse(response);
        console.log(res);
        //return false;

        if(res.code == '200') {
            $('#idModalElegirEstadoReemplazo').modal('hide');
            $('#alert_success').show();
            setTimeout(function() {
                $('#alert_success_change').hide(); // Oculta el elemento cambiando su estilo
            }, 5000);
            //$('#alert_success').show();
        }
        else
            alert(res['message']);
    }).fail(function() {
        alert('Fallo la conexión a internet, verifique');
    }).always(function() {
        $('#idCambiarEstatus').text('Aceptar');
        $('#idCambiarEstatus').prop('disabled', false);
    });
}

function validarCampos(accion) {
    var value = "";
    var card_a = "";
    var card_n = "";
    var errores = [];
    if (accion == "activar") {
        var tarjeta = document.getElementById('tarjeta').value;
        var cliente = document.getElementById('cliente').value;
        if (!tarjeta || isNaN(tarjeta) || tarjeta <= 0) {
            errores.push('El número de tarjeta es inválido.');
        }

        if (!cliente || cliente.trim() === "") {
            errores.push('El número de cliente es inválido.');
        }

        if (errores.length > 0) {
            alert('Errores de validación:\n' + errores.join('\n'));
        } else {
            if (accion === 'activar') {
                value = document.getElementById('tarjeta').value; 
            }
        }   
    } else if (accion == "cancelar") {
        var tarjeta = document.getElementById('tarjetac').value;
        console.log(tarjeta);
        if (!tarjeta || isNaN(tarjeta) || tarjeta <= 0) {
            errores.push('El número de tarjeta es inválido.');
        }

        if (errores.length > 0) {
            alert('Errores de validación:\n' + errores.join('\n'));
        } else {
            if (accion === 'cancelar') {
                value = document.getElementById('tarjetac').value;
            }
        }   
    } else if(accion == "reemplazo"){
        var tarjeta_a = document.getElementById('tarjeta_a').value;
        var tarjeta_n = document.getElementById('tarjeta_n').value;
        if (!tarjeta_a || isNaN(tarjeta_a) || tarjeta_a <= 0) {
            errores.push('El número de tarjeta anterior es inválido.');
        }

        if (!tarjeta_n || isNaN(tarjeta_n) || tarjeta_n <= 0) {
            errores.push('El número de tarjeta nuevo es inválido.');
        }

        if (errores.length > 0) {
            alert('Errores de validación:\n' + errores.join('\n'));
        } else {
            if (accion === 'reemplazo') {
                value = document.getElementById('tarjetac').value;
            }
        }
        card_a = document.getElementById('tarjeta_a').value;
        card_n = document.getElementById('tarjeta_n').value;
    }
    
    var cliId;
    if (document.getElementById('cliente'))
        cliId = document.getElementById('cliente').value;
    else
        cliId = 0;
    
    //valida que tenga datos el campo
    if (accion === 'asignar' || accion === 'activar' || accion === 'limpiar' || accion === 'cancelar' || accion === 'reemplazo') {
        if(accion === 'reemplazo'){
            if(card_a.length === 0 || card_n.length === 0){
                console.log("Entra al error");
            }else{
                console.log("Entra al reemplazo");
                $('#idModalElegirEstadoReemplazo').modal('show');
                $('#idPregreem').html('¿Esta seguro que desea hacer el reemplazo de tarjeta?');
            }
        }else if (value.length === 0) {
            $('#tarjeta').attr('data-content', 'Ingresa el numero de tarjeta o folio');
            $('#tarjeta').popover('show');
            console.log("Ingresa el numero de tarjeta o folio");
        } else if (accion === 'limpiar' || accion === 'cancelar'){
            consulDatosCli(0, value, accion);
        }else{
            if (cliId.length === 0) {
                $('#cliente').attr('data-content', 'Ingresa el numero de cliente');
                $('#cliente').popover('show');
                console.log("Ingresa el numero de cliente");
            } else {
                consulDatosCli(cliId, value, accion);
                //console.log("consulDatosCli");
            }
        }
    } else {
        if (value.length > 0)
            //consulDatosCli(cliId, value, accion);
            console.log("consulDatosCli");
        else if (cliId.length > 0)
            //consultaTarjCli(cliId, 0, accion);
        console.log("consultaTarjCli");
        else {
            //muestra los mensajes
            $('#tarjeta').attr('data-content', 'Ingresa el numero de tarjeta o folio');
            $('#tarjeta').popover('show');
            console.log("Ingresa el numero de tarjeta o folio");
            $('#cliente').attr('data-content', 'O ingresa el numero de cliente');
            $('#cliente').popover('show');
            console.log("O ingresa el numero de cliente");
            //quita los mensajes despues de 6 segundos
            setTimeout('quitarMsjCancel()', 6000);
        }
    }
}

function consulDatosCli(cliId, value, accion) {
    const data = {
        clienteID: cliId,
        valor: value,
        accion: accion,
        proceso: 'datosCliente'
    };

    $.ajax({
        url: '../../Controllers/cambiarEstatusTDC.php',
        data: data,
        type: 'POST'
    }).done(function(response) {
        if (response === 'Error')
            alert('No se encontro datos del cliente');
        else {
            response = JSON.parse(response);
            if (response.mensaje !== 'ok')
                alert(response.mensaje);
            else {
                if (accion === 'cancelar') {
                    $('#idModalElegirEstadoCancelar').modal('show');
                    $('#idPregCancelar').html('¿Esta seguro que desea <strong>' + accion + '</strong> la tarjeta: <strong>' + response.numeroTarjeta 
                                    + '</strong> al usuario: <strong>' + response.numeroCliente + ' ' + response.nombreCompleto + '</strong>?');
                    response.numeroTarjeta = response.numeroTarjeta.toString().replace(/ /g, '');
                    response.numeroTarjeta = response.numeroTarjeta.toString().replace(/-/g, '');
                    $('#idCambiarEstatusCancelar').attr('onclick', 'cancelarTDC(' + response.numeroCliente + ', ' + response.numeroTarjeta + ')');
                }
                else{
                    $('#idModalElegirEstado').modal('show');
                    $('#idPreg').html('¿Esta seguro que desea <strong>' + accion + '</strong> la tarjeta: <strong>' + response.numeroTarjeta 
                                    + '</strong> al usuario: <strong>' + response.numeroCliente + ' ' + response.nombreCompleto + '</strong>?');
                }
            }
        }
    }).fail(function() {
        alert('Error, no se pudo obtener los datos del cliente.');
    });
}

function doremplace(word) {
    word = word.replace(/Ñ/g, '|');
    word = word.replace(/ñ/g, '|');
    word = word.replace(/Á/g, '|');
    word = word.replace(/á/g, '|');
    word = word.replace(/É/g, '|');
    word = word.replace(/é/g, '|');
    word = word.replace(/Í/g, '|');
    word = word.replace(/í/g, '|');
    word = word.replace(/Ó/g, '|');
    word = word.replace(/ó/g, '|');
    word = word.replace(/Ú/g, '|');
    word = word.replace(/ú/g, '|');
    word = word.replace(/ /g, '|');
    return word;
}

function buscarCliente(act, prod) {
    var n = $('#bamigo').val().toString().toUpperCase();
    console.log("entra a la funcion");
    if (n !== '') {
        var nombre = '|' + doremplace(n) + '|';
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            try {
                if (this.readyState === 1) {
                    $('#ClientesRen').html('<tr><td colspan="3"><center>Buscando cliente... \n\
                                        <img src="../../img/loading28.gif" style="display: ' +
                            'inline-block; margin: 0 auto; height: 30px;"></center></td></tr>');
                }
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        var data = JSON.parse(this.responseText);
                        if (data.registros.length > 0) {
                            var value = '';
                            for (var i = 0; i < data.registros.length; i++) {
                                value += '<tr><td>' + data.registros[i].ClienteID + '</td><td>' +
                                        data.registros[i].TelefonoCelular + '</td><td>' +
                                        data.registros[i].Nombre.toString().trim().replace(/_/g, '') + '</td><td>' +
                                        '<button type="button" data-dismiss="modal" class="btn btn-link" onclick="getCliente(\'' +
                                        data.registros[i].ClienteID + '\');">Seleccionar</button></td></tr>';

                            }
                        } else {
                            value = '<tr><td colspan="3"><p class="alert alert-warning">' +
                                    'No se encontrarón clientes con ese nombre</p></td></tr>';
                        }
                        $('#ClientesRen').html(value);
                    }
                }
            } catch (err) {
                var divtit = document.getElementById('tit');
                divtit.innerHTML = 'Cliente no válido';
                var divmsj = document.getElementById('msj');
                divmsj.innerHTML = ' <p style="text-align:center;">Ocurrio un error, por favor intenta más tarde</p>';
                divmsj.className = 'alert alert-warning';
                $("#myModalv").modal("show");
                $('#ClientesRen').html('');
            }
        };
        xhttp.open("POST", "../../Controllers/cambiarEstatusTDC.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("proceso=" + act + "&nom=" + nombre + "&prod=" + prod);
    }

    return false;
}

function buscarTarjeta(act, tarjeta, ren, modal, tarjetaCon) {
    var n = $(tarjeta).val();

    if (typeof n !== 'undefined' && n !== null && n !== '') {
        n = n.toString().toUpperCase();

        var nombre = '|' + doremplace(n) + '|';
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            try {
                if (this.readyState === 1) {
                    $(ren).html('<tr><td colspan="2"><center>Buscando cliente... \n\
                                        <img src="../../img/loading28.gif" style="display: ' +
                            'inline-block; margin: 0 auto; height: 30px;"></center></td></tr>');
                }
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        var data = JSON.parse(this.responseText);
                        if (data.registros.length > 0) {
                            var value = '';
                            for (var i = 0; i < data.registros.length; i++) {
                                console.log('getTarjeta(\'' +data.registros[i].Tarjeta + '\', "'+tarjeta+'", "'+modal+'");');
                                value += '<tr><td>' + data.registros[i].Nombre.toString().trim().replace(/_/g, '') + '</td><td>' +
                                        data.registros[i].Tarjeta + '</td><td>' +
                                        '<button type="button" data-bs-dismiss="modal" class="btn btn-link" onclick="getTarjeta(\'' +
                                        data.registros[i].Tarjeta + '\', \''+tarjetaCon+'\', \''+modal+'\');">Seleccionar</button></td></tr>';
                            }
                        } else {
                            value = '<tr><td colspan="2"><p class="alert alert-warning">' +
                                    'No se encontraron clientes con ese nombre</p></td></tr>';
                        }
                        $(ren).html(value);
                    }
                }
            } catch (err) {
                var divtit = document.getElementById('tit');
                divtit.innerHTML = 'Cliente no válido';
                var divmsj = document.getElementById('msj');
                divmsj.innerHTML = ' <p style="text-align:center;">Ocurrió un error, por favor intenta más tarde</p>';
                divmsj.className = 'alert alert-warning';
                $("#myModalv").modal("show");
                $(ren).html('');
            }
        };
        xhttp.open("POST", "../../Controllers/cambiarEstatusTDC.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("proceso=" + act + "&nom=" + nombre);
    } else {
        $(ren).html('<tr><td colspan="2"><p class="alert alert-warning">Por favor, ingresa un nombre válido.</p></td></tr>');
    }

    return false;
}


function getCliente(id) {
    $('#cliente').val(id);
    $('#cliente').focus();
    $('#myModalSCte').modal('hide');
}

function getTarjeta(id, tarjeta, modal) {
    $(tarjeta).val(id);
    $(tarjeta).focus();
    $(modal).modal('hide');
}