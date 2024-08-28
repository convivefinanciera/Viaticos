$(document).ready(function () {
    if (cargarAvances == true) {
        CargarAvances_Registro();
    }
});

let nombresUsuario, apellidoPaterno, apellidoMaterno, celular, correo, dependencia, area, cargo, noEmpleado = "";
var faltaCampo = '';

function GuardarInfo_Registro() {
    GetDatosFormulario();
    let verificacionCampos = VerificarCampos();

    if (verificacionCampos == true) {
        let datosForm = new FormData();
        datosForm.append('bandera', 'GuardarInfo_Registro');

        datosForm.append('nombresUsuario', nombresUsuario);
        datosForm.append('apellidoPaterno', apellidoPaterno);
        datosForm.append('apellidoMaterno', apellidoMaterno);
        datosForm.append('celular', celular);
        datosForm.append('correo', correo);
        datosForm.append('dependencia', dependencia);
        datosForm.append('area', area);
        datosForm.append('cargo', cargo);
        datosForm.append('noEmpleado', noEmpleado);

        $.ajax({
            type: "POST",
            url: "../../Controllers/monitorRegistro.php",
            data: datosForm,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            // dataType: "dataType",
            success: function (response) {
                response = JSON.parse(response);
                if (response.estatus == 206) {
                    Toastify({
                        text: response.mensaje + ".",
                        className: "warning",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #f7db4d, #deb902)",
                        }
                    }).showToast();
                }
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
                }
                if (response.estatus == 400) {
                    Toastify({
                        text: response.mensaje,
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
    else {
        Toastify({
            text: "Por favor verifica el campo '" + faltaCampo + "'",
            className: "warning",
            duration: 5000,
            gravity: "bottom",
            position: "right",
            style: {
                background: "linear-gradient(to right, #ff3636, #de0202)",
            }
        }).showToast();
    }
}

function GetDatosFormulario() {
    //Obtenemos datos del cliente
    nombresUsuario = $('#nombresUsuario').val();
    apellidoPaterno = $('#apellidoPaterno').val();
    apellidoMaterno = $('#apellidoMaterno').val();
    celular = $('#celular').val();
    correo = $('#correo').val();
    dependencia = $('#dependencia').val();
    area = $('#area').val();
    cargo = $('#cargo').val();
    noEmpleado = $('#noEmpleado').val();
}

function VerificarCampos() {
    faltaCampo = '';

    nombresUsuario == '' ? (faltaCampo = 'Nombres usuario', document.getElementById("nombresUsuario").focus()) : nombresUsuario;
    celular == '' ? (faltaCampo = 'Celular', document.getElementById("celular").focus()) : celular;
    correo == '' ? (faltaCampo = 'Correo', document.getElementById("correo").focus()) : correo;

    if (faltaCampo != '') {
        return false; //Verificar campos
    }
    else if (faltaCampo == '') {
        return true; //Campos validados
    }
}


function MostrarMensaje(articulo, campoFaltante) {
    //alert("Verifica " + articulo + " " + campoFaltante);
    Toastify({
        text: "Verifica " + articulo + " " + campoFaltante + ".",
        className: "warning",
        duration: 5000,
        gravity: "bottom",
        position: "right",
        style: {
            background: "linear-gradient(to right, #f7db4d, #deb902)",
        }
    }).showToast();

}
function LimpiarFormulario() {
    //Reiniciamos variables del formulario
    $('#nombresUsuario').val("");
    $('#apellidoPaterno').val("");
    $('#apellidoMaterno').val("");
    $('#celular').val("");
    $('#correo').val("");
    $('#dependencia').val("");
    $('#area').val("");
    $('#cargo').val("");
    $('$noEmpleado').val("");
}

function DeshabilitarFormulario1() {
    $('#nombresUsuario').prop('disabled', true);
    $('#apellidoPaterno').prop('disabled', true);
    $('#apellidoMaterno').prop('disabled', true);
    $('#celular').prop('disabled', true);
    $('#correo').prop('disabled', true);
    $('#dependencia').prop('disabled', true);
    $('#area').prop('disabled', true);
    $('#cargo').prop('disabled', true);
    $('$noEmpleado').prop('disabled', true);
}

function CargarAvances_Registro() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/monitorRegistro.php",
        data: { bandera: "CargarAvances_Registro" },
        cache: false,
        // processData: false,  // tell jQuery not to process the data
        // contentType: false,   // tell jQuery not to set contentType
        success: function (response) {

            let res = JSON.parse(response);
            let datosRegistro = res[0];

            GetDatosFormulario();
        }
    });
}
