$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        event.preventDefault(); 

        var correo = $('#email').val();
        var password = $('#contraseña').val();

        
        if (correo.trim() === '' || password.trim() === '') {
            console.log('Uno o ambos campos están vacíos.');
            //alert('Por favor completa todos los campos del formulario.');
            Toastify({
                text: "Por favor completa todos los campos del formulario.",
                className: "warning",
                duration: 5000,
                gravity: "bottom",
                position: "right",
                style: {
                  background: "linear-gradient(to right, #f7db4d, #deb902)",
                }
              }).showToast();    
            return;
        }
        
        $.ajax({
            type: 'POST',
            url: "Controllers/login.php",
            data: {
                email: correo, 
                contraseña: password,
            },
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                if(typeof response['success'] === 'undefined')
                {
                    //alert("Error: " + response['error'] );
                    Toastify({
                        text: "Error: " + response['error']+".",
                        className: "error",
                        duration: 5000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                          background: "linear-gradient(to right, #ff3636, #de0202)",
                        }
                      }).showToast();
                    //window.location.replace('nuevoUsuario.php');
                    
                }
                else
                {   
                    //$('#codigoModal').modal('show');
                    window.location.replace('inicio.php');
                    
                }
            //     if (response.error) {
            //         console.error('Error al iniciar sesión:', response.error);
            //     } else {
            //         alert(response);
            //     }
            // },
            // error: function(xhr, status, error) {
            //     console.error('Error al iniciar sesión:', error);
            //     console.log('Mensaje de error:', xhr.responseText);
            }
        });
    });
});

    // // Función para enviar el código de verificación por correo electrónico
    // function enviarCodigoVerificacion(correo) {
    //     $.ajax({
    //         url: 'Controllers/login.php', 
    //         type: 'POST',
    //         data: {
    //              correo: correo 
    //             },
    //         success: function(response) {
    //             console.log(response);
    //             var responseData = JSON.parse(response);
    //             if (responseData.success) {
    //                 console.log('Código de verificación enviado correctamente');
    //                 window.location.replace('inicio.php');
    //             } else {
    //                 console.error('Error al enviar código de verificación:', responseData.error);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error al enviar código de verificación:', error);
    //         }
    //     });
    // }