<?php echo '<link rel="stylesheet" href="' . $rutaServer . 'css/documentacionAnexa.css">' ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- <div class="card"> -->
            <!-- <div class="card-body"> -->
            <div class="table-responsive">
                <table class="table table-bordered"">
                    <thead>
                        <tr>
                            <th scope=" col" style="width: 5%;">Ítem</th>
                    <th scope="col" style="width: 35%;">Descripción</th>
                    <th scope="col" style="width: 30%;">Nombre del archivo</th>
                    <th scope="col" style="width: 5%;" class="d-none d-md-table-cell">Verificado</th>
                    <th scope="col" style="width: 5%;">Calificación</th>
                    <th scope="col" style="width: 5%;">Detalle</th>
                    <th scope="col" style="width: 5%;">Tamaño</th>
                    <th scope="col" style="width: 5%;">Tipo</th>
                    <th scope="col" style="width: 5%;">Eliminar</th>
                    </tr>
                    </thead>
                    <tbody id="documentosBody">
                        <tr>
                            <th scope="row">1</th>
                            <td>Acta constitutiva<div style="float: right;"><input id="subirArchivo1" type="file" accept=".jpg,.jpeg,.png,.pdf" hidden><label class="estiloInput" for="subirArchivo1">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo1"></td>
                            <!-- <td id="verificadoArchivo1" class="text-center"><input id="chkVerif1" type="checkbox" onchange="VerificarArchivo(1)" /></td> -->
                            <td id="verificadoArchivo1" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif1" type="checkbox" onclick="VerificarArchivo(1)" /></div>
                            </td>
                            <td id="calificacionArchivo1">N/A</td>
                            <td id="detalleArchivo1">
                                <button class='btn btn-circle' id="detalle1" data-bs-target="#detalleArchivosModal" data-bs-toggle="modal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo1"></td>
                            <td id="tipoArchivo1"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo1">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Poder notarial<div style="float: right;"><input id="subirArchivo2" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo2">Examinar</label></input></div>
                            </td>
                            <!-- <td>Poder notarial<input class="position-absolute top-0 end-0" id="subirArchivo2" type="file" hidden><label class="estiloInput" for="subirArchivo2">Examinar</label></input></td> -->
                            <td id="nombreArchivo2"></td>
                            <!-- <td></td> -->
                            <td id="verificadoArchivo2" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif2" type="checkbox" onclick="VerificarArchivo(2)" /></div>
                            </td>
                            <td id="calificacionArchivo2">N/A</td>
                            <td id="detalleArchivo2">
                                <button class='btn btn-circle' id="detalle2" data-bs-target="#detalleArchivosModal" data-bs-toggle="modal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo2"></td>
                            <td id="tipoArchivo2"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo2">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Constancia de situación fiscal<div style="float: right;"><input id="subirArchivo3" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo3">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo3"></td>
                            <td id="verificadoArchivo3" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif3" type="checkbox" onclick="VerificarArchivo(3)" /></div>
                            </td>
                            <td id="calificacionArchivo3"></td>
                            <td id="detalleArchivo3">
                                <button class='btn btn-circle' id="detalle3" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo3"></td>
                            <td id="tipoArchivo3"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo3">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Identificación Oficial<div style="float: right;"><input id="subirArchivo4" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo4">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo4"></td>
                            <td id="verificadoArchivo4" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif4" type="checkbox" onclick="VerificarArchivo(4)" /></div>
                            </td>
                            <td id="calificacionArchivo4"></td>
                            <td id="detalleArchivo4">
                                <button class='btn btn-circle' id="detalle4" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo4"></td>
                            <td id="tipoArchivo4"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo4">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Opinión positiva del SAT<div style="float: right;"><input id="subirArchivo5" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo5">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo5"></td>
                            <td id="verificadoArchivo5" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif5" type="checkbox" onclick="VerificarArchivo(5)" /></div>
                            </td>
                            <td id="calificacionArchivo5"></td>
                            <td id="detalleArchivo5">
                                <button class='btn btn-circle' id="detalle5" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo5"></td>
                            <td id="tipoArchivo5"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo5">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Estados financieros<div style="float: right;"><input id="subirArchivo6" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo6">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo6"></td>
                            <td id="verificadoArchivo6" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif6" type="checkbox" onclick="VerificarArchivo(6)" /></div>
                            </td>
                            <td id="calificacionArchivo6">N/A</td>
                            <td id="detalleArchivo6">
                                <button class='btn btn-circle' id="detalle6" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo6"></td>
                            <td id="tipoArchivo6"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo6">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>Comprobantes de domicilio <div style="float: right;"></div>
                            </td>
                            <td></td>
                            <td id="verificadoArchivo7" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif7" type="checkbox" onclick="VerificarArchivo(7)" /></div>
                            </td>
                            <td id="calificacionArchivo7"></td>
                            <td id="detalleArchivo7">
                                <button class='btn btn-circle' id="detalleComprobantes" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo71"></td>
                            <td id="tipoArchivo71"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>Comprobante domicilio particular<div style="float: right;"><input id="subirArchivo7" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo7">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo7"></td>
                            <td></td>
                            <td>-</td>
                            <td id="detalleArchivo7">
                                <!-- <button class='btn btn-circle' id="detalle7" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button> -->
                            </td>
                            <td id="tamanoArchivo7"></td>
                            <td id="tipoArchivo7"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo7">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>Comprobante domicilio fiscal<div style="float: right;"><input id="subirArchivo8" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo8">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo8"></td>
                            <td></td>
                            <td>-</td>
                            <td id="detalleArchivo8">
                                <!-- <button class='btn btn-circle' id="detalle8" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button> -->
                            </td>
                            <td id="tamanoArchivo8"></td>
                            <td id="tipoArchivo8"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo8">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>Comprobante domicilio negocio<div style="float: right;"><input id="subirArchivo9" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo9">Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo9"></td>
                            <td></td>
                            <td>-</td>
                            <td id="detalleArchivo9">
                                <!-- <button class='btn btn-circle' id="detalle9" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button> -->
                            </td>
                            <td id="tamanoArchivo9"></td>
                            <td id="tipoArchivo9"></td>
                            <td>
                                <button class='btn btn-circle' id="eliminarArchivo9">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- <tr>
                            <th scope="row">8</th>
                            <td>Estado de cuenta bancario<div style="float: right;"><input id="subirArchivo10" type="file" accept=".jpg, .jpeg, .png, .pdf" hidden><label class="estiloInput" for="subirArchivo10" >Examinar</label></input></div>
                            </td>
                            <td id="nombreArchivo10"></td>
                            <td id="verificadoArchivo10" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif10" type="checkbox" onclick="VerificarArchivo(10)" /></div>
                            </td>
                            <td id="calificacionArchivo10">N/A</td>
                            <td id="detalleArchivo10">
                                <button class='btn btn-circle' id="detalle10" data-bs-toggle="modal" data-bs-target="#detalleArchivosModal" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td id="tamanoArchivo10"></td>
                            <td id="tipoArchivo10"></td>
                            <td id="eliminarArchivo10"></td>
                        </tr> -->
                        <tr id="renglonesFotos">
                            <th scope="row">8</th>
                            <td>Fotografías<div style="float: right;"><input id="subirArchivo10" type="file" multiple accept=".jpg, .jpeg, .png, .svg, .gif" hidden><label class="estiloInput ms-1" for="subirArchivo10"> Añadir </label></input></div>
                            </td>
                            <td></td>
                            <td id="verificadoArchivo10" class="text-center">
                                <div class="form-check form-switch" style="padding-left: 2.5em;"><input class="form-check-input" role="switch" id="chkVerif10" type="checkbox" onclick="VerificarArchivo(10)" /></div>
                            </td>
                            <td id="calificacionArchivo10"></td>
                            <td id="detalleArchivo10">
                                <button class='btn btn-circle' id="detalle10" data-bs-toggle="modal" data-bs-target="#detalleArchivosModalFotos" onclick="VerDetalleFotos()" disabled>
                                    <i class="bi bi-eye" style="font-size:15px;"></i>
                                </button>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="mt-4 d-flex justify-content-center">
                <button type="button" id="prev6" class="btn btn-secondary me-2" onclick="regresar()">Regresar</button>

                <!-- <button type="button" id="next7" class="btn btn-primary me-2" onclick="continuar()">Continuar</button> -->
                <!-- <button type="button" class="btn btn-danger" onclick="cerrar()">Cerrar</button> -->
            </div>
        </div>
    </div>
</div>

<style>
    .estiloInput {
        background-color: #d90000;
        color: white;
        padding: 5px 5px 3px 6px;
        border-radius: 0.4rem;
        cursor: pointer;
        height: 30px;
        font-size: .8em;
        text-align: center;
        transition: all .1000s;
        /* margin-top: 1rem; */
    }

    .estiloInput:hover {
        background-color: white;
        color: black;
        border: 2px solid #ccc !important;
    }

    /* .estiloInput:disabled {
        background-color: #404140;
        color: white;
    } */

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity input {
        width: 45px;
        height: 42px;
        line-height: 1.65;
        float: left;
        display: block;
        padding: 0;
        margin: 0;
        padding-left: 20px;
        border: 1px solid #eee;
    }

    .quantity input:focus {
        outline: 0;
    }

    .quantity-nav {
        float: left;
        position: relative;
        height: 42px;
    }

    .quantity-button {
        position: relative;
        cursor: pointer;
        border-left: 1px solid #eee;
        width: 20px;
        text-align: center;
        color: #333;
        font-size: 13px;
        font-family: "Trebuchet MS", Helvetica, sans-serif !important;
        line-height: 1.7;
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .quantity-button.plus {
        position: absolute;
        height: 50%;
        top: 0;
        border-bottom: 1px solid #eee;
    }

    .quantity-button.minus {
        position: absolute;
        bottom: -1px;
        height: 50%;
    }

    #documentosBody .form-check-input:checked {
        background-color: #04c20d;
        border-color: #04c20d;
        border-top-color: rgb(0, 213, 45);
        border-right-color: rgb(0, 213, 45);
        border-bottom-color: rgb(0, 213, 45);
        border-left-color: rgb(0, 213, 45);
    }
</style>