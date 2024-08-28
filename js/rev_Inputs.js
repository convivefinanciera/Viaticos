function procesarInput(input) {
    input.addEventListener("input", function() {
        // Obtener el valor del input
        var valor = input.value;

        // Eliminar signos y números usando una expresión regular
        valor = valor.replace(/[^a-zA-Z\s]/g, '');

        // Convertir a mayúsculas
        valor = valor.toUpperCase();

        // Asignar el valor modificado de nuevo al input
        input.value = valor;
    });
}

function procesarInput_car(input) {
    input.addEventListener("input", function() {
        // Obtener el valor del input
        var valor = input.value;

        // Eliminar signos y números usando una expresión regular
        valor = valor.replace(/[^a-zA-Z0-9ÁÉÍÓÚáéíóúÑñ\s]/g, '');

        // Convertir a mayúsculas
        valor = valor.toUpperCase();

        // Asignar el valor modificado de nuevo al input
        input.value = valor;
    });
}

function procesarInput_mayus(input) {
    input.addEventListener("input", function() {
        // Obtener el valor del input
        var valor = input.value;

        // Eliminar acentos y otros signos diacríticos
        valor = valor.normalize("NFD").replace(/[\u0300-\u036f]/g, '');

        // Convertir a mayúsculas
        valor = valor.toUpperCase();

        // Asignar el valor modificado de nuevo al input
        input.value = valor;
    });
}