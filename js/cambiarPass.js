$(document).ready(function () {
    $('#cambiarPasswordForm').submit(function (event) {
        event.preventDefault();

        var token = $('#token').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmarPass').val();


        console.log(token);
        console.log(email);
        console.log(password);
        console.log(confirmPassword);

        if (password !== confirmPassword) {
            //alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
            Toastify({
                text: "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.",
                className: "error",
                duration: 5000,
                gravity: "bottom",
                position: "right",
                style: {
                  background: "linear-gradient(to right, #ff3636, #de0202)",
                }
              }).showToast();

            return;
        }

        $.ajax({
            type: 'POST',
            url: 'Controllers/cambiarPass.php',
            data: {
                token: token,
                email: email,
                password: password
            },
            success: function (response) {
                //alert(response);
                Toastify({
                    text: response+".",
                    className: "success",
                    duration: 5000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                      background: "linear-gradient(to right, #4df755, #04c20d)",
                    }
                  }).showToast();
                // Puedes redirigir al usuario a otra página si es necesario
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 5000);
            },
            error: function (xhr, status, error) {
                console.error('Error al cambiar la contraseña:', error);
                window.location.href = 'index.php';
            }
        });
    });
});
