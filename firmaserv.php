<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmaserv</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 18px;
            /* overflow: hidden; */
        }
        body.modalOpen::after,
        body.recomendation::after {
            content: '';
            width: 100vw;
            height: 100vh;
            background: rgb(76 76 76 / 65%);
            top: 0;
            left: 0;
            position: absolute;
            z-index: 0;
        }
        body.recomendation::after {
            background: rgb(76 76 76 / 86%);
        }
        section {
            position: relative;
        }
        /* MODAL */
        section article.modal_container {
            z-index: -1;
            overflow: hidden;
            position: absolute;
            top: 0;
            left: 0vw;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: start;
            justify-content: center;
        }
        section article.modal {
            width: 80vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 1px solid #4c4c4c;
            padding: 1em;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 5px #595959;
            /* margin-top: 20px; */
            visibility: hidden;
            opacity: 0;
            transition: visibility, z-index, opacity .3s;
        }
        body.modalOpen article.modal {
            visibility: visible;
            z-index: inherit;
            opacity: 1;
        }
        body.modalOpen section article.modal_container {
            z-index: 2;
        }
        section article.modal .modal-body img {
            width: 50px;
        }
        section article.modal .modal-body p#message_modal {
            text-align: center;
            margin: 2em 0;
        }
        section article.modal .modal-footer {
            border-top: 1px solid #999999;
        }
        section article.modal .modal-footer button {
            padding: 8px 2em;
            font-size: .8em;
            border: 1px solid #adadad;
            margin: 10px auto 0;
            display: block;
            background-color: #d9d9d9;
            border-radius: 3px;
            cursor: pointer;
        }
        /* -- */
        /* ADVICE */
        section article#recomendation_user {
            position: absolute;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100vw;
            background-color: transparent;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1em;
            z-index: -1;
            visibility: hidden;
        }
        body.recomendation section article#recomendation_user {
            visibility: visible;
            z-index: 2;
        }
        section article#recomendation_user article div#movile_advice {
            width: 50px;
            height: 100px;
            border: 3px solid #fff;
            border-bottom-width: 10px;
            background-color: transparent;
            border-radius: 10px;
            animation: rotate 2s ease-in-out infinite alternate;
            margin: 0 auto;
        }
        section article#recomendation_user article p { color: #fff; text-align: center; }
        section article#recomendation_user button {
            background-color: #fff;
            border-color: #4c4c4c;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 1em;
            color: #333;
            cursor: pointer;
        }
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            50% {
                transform: rotate(90deg);
            }
            100% {
                transform: rotate(90deg);
            }
        }
        /* -- */
        section > article:last-of-type {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        section > article:last-of-type canvas ~ div {
            display: flex;
        }
        section > article:last-of-type canvas ~ div button {
            font-size: 1em!important;
            border: none;
            padding: .5em 1em;
            border-radius: 7px;
            width: 100px;
        }
        section > article:last-of-type canvas ~ div button:first-child {
            background-color: hsl(210 54% 92% / 1);
            color: #333;
        }
        section > article:last-of-type canvas ~ div button:last-of-type {
            background-color: rgba(249, 90, 38);
            color: #fff;
        }

        /* PORTRAIT */
        section.portrait > article:last-of-type {
            width: 90vw;
            height: 100vh;
            padding: 1vh 0vw 1vh 10vw;
        }
        section.portrait > article:last-of-type canvas ~ div {
            gap: 4em;
            flex-direction: column-reverse;
            min-width: 70px;
        }
        section.portrait > article:last-of-type canvas ~ div button {
            transform: rotate(-90deg);
            margin-left: -15px;
        }
        /* LANDSCAPE */
        section.landscape > article:last-of-type {
            width: 98vw;
            height: 95vh;
            gap: .5em;
            padding: 5vh 1vw 0;
            flex-direction: column;
        }
        section.landscape > article:last-of-type canvas ~ div {
            justify-content: center;
            gap: 1em;
        }
        #confirmSign {
            margin: unset;
            background-color: rgba(249, 90, 38);
            color: #fff;
            border: none;
            display: none;
        }
        .modal_container.confirmSign #confirmSign {
            display: block;
        }
        .modal_container.confirmSign .modal-footer {
            display: flex;
            justify-content: end;
            gap: 1em;
            padding-top: 10px;
        }
        .modal_container.confirmSign .modal-footer button {
            margin: unset;
        }
        .modal_container.confirmSign {margin: 0.5em 0px;}
        .modal_container.confirmSign .modal-body #message_modal {
            margin: .5em 0;
        }
        .modal_container.confirmSign .modal-body #message_modal div {
            margin-top: 15px;
        }
        .modal_container.confirmSign .modal-body #message_modal div.rotate {
            position: relative;
            width: 100%;
            height: 132px;
        }
        .modal_container.confirmSign .modal-body #message_modal div.rotate img {
            height: 315px;
            width: 130px;
            left: 91px;
            top: -93px;
            transform: rotate(90deg);
            display: block;
            position: absolute;
        }
        .modal_container.confirmSign .modal-body #message_modal div img {
            object-fit: contain;
            width: 100%;
            height: 160px;
        }
        
    </style>
</head>
<body>
    <section id="main_content" class="">
        <article id="recomendation_user">
            <article>
                <div id="movile_advice"></div>
                <p>Gire su dispositivo para una mejor experiencia.</p>
            </article>
            <button onclick="startToSign()">Comenzar</button>
        </article>
        <article class="modal_container">
            <article class="modal">
                <div class="modal-body">
                    <img src="img/ventacero_icono.png" alt="VentAcero Logotipo">
                    <p id="message_modal"></p>
                </div>
                <div class="modal-footer">
                    <button id="closeModal" onclick="openCloseModal()">Cerrar</button>
                    <button id="confirmSign" onclick="Firmar()">Aceptar</button>
                </div>
            </article>
        </article>
        <article>
            <canvas id="cnv" style="border: 1px solid black; border-radius: 10px;"></canvas>
            <div>
                <button id="btnLimpiar" class="btn btn-success">Limpiar</button> 
                <button id="btnFirmar" data="<?= $_GET['sol']; ?>" class="btn btn-success">Firmar</button>
                <p id="dataFirma" style="display: none;"></p>
            </div>
        </article>
    </section>

    <script src="js/firmaserv_buro.js"></script>
</body>
</html>