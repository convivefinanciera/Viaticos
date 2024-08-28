
$(function() {
    var fechaSeleccionada;

    $("#fechaDisp").datepicker({
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        onClose: function(dateText, inst) {
            $(this).change();
        },
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        dayNamesMin: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        onSelect: function(dateText, inst) {
            fechaSeleccionada = dateText;
        }
    });

    $("#guardarFecha").click(function() {
        alert("Fecha seleccionada: " + fechaSeleccionada);
    });

    $('#aplicar').click(function() {
        var TipoDisp = $('#tipoDispersion').val();
        var MontoDisp = $('#monto').val();
        var DescripcionDisp = $('#referencia_ticket').val();
        var fecha = $('#fechaDisp').val();


        console.log(TipoDisp);
        console.log(MontoDisp);
        console.log(DescripcionDisp);
        console.log(fecha);
        if (TipoDisp.trim() === '' || MontoDisp.trim() === '' || DescripcionDisp.trim() === '' || fecha.trim() === '') {
            alert('Por favor, completa todos los campos del formulario.');
            return;
        }
        // Validar que el monto solo contenga números positivos
        var validMonto = MontoDisp.match(/^\d*\.?\d*$/);
        if (!validMonto) {
            alert('Por favor, ingresa un monto válido (solo números positivos).');
            return;
        }

        $.ajax({
            url: '../../Controllers/aplicacionPagos.php',
            type: 'POST',
            data: {
                tipoDispersion: TipoDisp,
                monto: MontoDisp,
                referencia_ticket: DescripcionDisp,
                fechaDisp: fecha
            },
            success: function(response) {
                console.log('Función de dispersión ejecutada exitosamente');
                console.log(response);
                $('#modal-message').text('La dispersión se realizó con éxito.');
                $('#myModal').css('display', 'block');
            },
            error: function(xhr, status, error) {
                console.error('Error al ejecutar la función de dispersión:', error);
            }
        });
    });

    $('#close').click(function() {
        $('#myModal').css('display', 'none');
    });
});
