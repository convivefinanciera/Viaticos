
function enviar_email() {
    var email = $('#email').val();
    
    console.log(email);

    if (!email) {
        //alert("Por favor, ingrese un correo electrónico.");
        Toastify({
            text: "Por favor, ingrese un correo electrónico.",
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
        url: 'Controllers/recuperarPass.php',
        data: { email: email },
        success: function(response) {
            var responseData = JSON.parse(response);
            if (responseData.success) {
                //alert('Correo enviado, por favor revisa tu bandeja de entrada.');
                Toastify({
                    text: "Correo enviado, por favor revisa tu bandeja de entrada y sigue las instrucciones para recuperar tu contraseña.",
                    className: "success",
                    duration: 8000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                    }).showToast();

                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 8000);
                // Aquí puedes redirigir si lo deseas, por ejemplo:
                //window.location.href = '../index.php';
            } else {
                //alert('Error: ' + responseData.error);
                Toastify({
                    text: "Error: " + responseData.error,
                    className: "error",
                    duration: 8000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff3636, #de0202)",
                    }
                    }).showToast();

            }
            
        },
        error: function(xhr, status, error) {
            //console.error('Error al enviar código de verificación:', error);
            alert('Ocurrió un error al enviar la solicitud. Por favor, inténtelo de nuevo más tarde.');
        }
    });
}