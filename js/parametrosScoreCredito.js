/* $(document).ready(function () {
    CargarParametrosScore();
});

function CargarParametrosScore() {
    $.ajax({
        type: "POST",
        url: "../../Controllers/parametrosScore.php",
        data: {
            bandera: "CargarParametrosScore"
        },
        cache: false,
        // processData: false,  // tell jQuery not to process the data
        // contentType: false,   // tell jQuery not to set contentType
        success: function (response) {
            let parametrosScore = JSON.parse(response);
            // let parametrosScore = res[0];
            if (typeof parametrosScore !== 'undefined' || (parametrosScore !== 'undefined' && parametrosScore.estatus != 400)) {
                for (let i = 0; i < parametrosScore.length; i++) {
                    $("#bodyParametrosScore").append(`<tr>
                                                        <td>${parametrosScore[i].ID_Parametro}</td>
                                                        <td>${parametrosScore[i].Parametro}</td>
                                                        <td>${parametrosScore[i].Valor}</td>
                                                    </tr>`);
                }
            }
        }
    })
} */
$(document).ready(() => {
    get_parametros();
})

let get_parametros = () => {
    $.ajax({
        url: "../../Controllers/parametrosScoreCredito.php",
        success: (response) => {
            response = JSON.parse(response);

            if (!response.error) { /* A-OK */
                /* Discriminar los resultados. */
                (response.datos).filter(e => e.TipoSemaforo == 'Credito').map((e, ix) => {
                    $("#scorecreditobody").append(`
                        <tr>
                            <td style="width: 12%;">${ ix + 1 }</td>
                            <td style="width: 70%;">${ e.Parametro }</td>
                            <td>${ e.Semaforo }</td>
                        </tr>
                    `);
                });
                (response.datos).filter(e => e.TipoSemaforo == 'Firmas').map((e, ix) => {
                    $("#firmastobody").append(`
                        <tr>
                            <td style="width: 12%;">${ ix + 1 }</td>
                            <td style="width: 70%;">${ e.Parametro }</td>
                            <td style="background-color: ${ e.Semaforo == 'Verde' ? '#03C988' : e.Semaforo == 'Amarillo' ? '#F9D923' : '#FF6464' }; color: ${ e.Semaforo == 'Verde' || e.Semaforo == 'Rojo' ? '#fff' : '#333' }">${ e.Semaforo }</td>
                        </tr>
                    `);
                });
                (response.datos).filter(e => e.TipoSemaforo == 'Colocacion').map((e, ix) => {
                    $("#colocacionBody").append(`
                        <tr>
                            <td style="width: 12%;">${ ix + 1 }</td>
                            <td style="width: 70%;">${ e.Parametro }</td>
                            <td style="background-color: ${ e.Semaforo == 'Verde' ? '#03C988' : e.Semaforo == 'Amarillo' ? '#F9D923' : '#FF6464' }; color: ${ e.Semaforo == 'Verde' || e.Semaforo == 'Rojo' ? '#fff' : '#333' }">${ e.Semaforo }</td>
                        </tr>
                    `);
                });
            } else {
                console.error("Algo salio mal con la consulta. Contacta a Soporte.");
            }
        }
    })
}