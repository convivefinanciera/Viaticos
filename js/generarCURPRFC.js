/** DEFINICIÓN DE VARIABLES **/
var vocales = ['A', 'E', 'I', 'O', 'U', '/', '-', '.'];

var consona = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'Ñ',
    'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'];

var valorLetras = {
    '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5,
    '6': 6, '7': 7, '8': 8, '9': 9, 'A': 10, 'B': 11, 'C': 12,
    'D': 13, 'E': 14, 'F': 15, 'G': 16, 'H': 17, 'I': 18, 'J': 19,
    'K': 20, 'L': 21, 'M': 22, 'N': 23, 'Ñ': 24, 'O': 25, 'P': 26,
    'Q': 27, 'R': 28, 'S': 29, 'T': 30, 'U': 31, 'V': 32, 'W': 33,
    'X': 34, 'Y': 35, 'Z': 36
};

var valorLetrasRfc1 = {' ': '00', '0': '00', '1': '01', '2': '02', '3': '03',
    '4': '04', '5': '05', '6': '06', '7': '07', '8': '08', '9': '09', '&': '10',
    'A': '11', 'B': '12', 'C': '13', 'D': '14', 'E': '15', 'F': '16', 'G': '17',
    'H': '18', 'I': '19', 'J': '21', 'K': '22', 'L': '23', 'M': '24', 'N': '25',
    'O': '26', 'P': '27', 'Q': '28', 'R': '29', 'S': '32', 'T': '33', 'U': '34',
    'V': '35', 'W': '36', 'X': '37', 'Y': '38', 'Z': '39', 'Ñ': '40'};

var valorLetrasRfc2 = {
    0: '1', 1: '2', 2: '3', 3: '4', 4: '5', 5: '6', 6: '7',
    7: '8', 8: '9', 9: 'A', 10: 'B', 11: 'C', 12: 'D', 13: 'E', 14: 'F', 15: 'G',
    16: 'H', 17: 'I', 18: 'J', 19: 'K', 20: 'L', 21: 'M', 22: 'N', 23: 'P',
    24: 'Q', 25: 'R', 26: 'S', 27: 'T', 28: 'U', 29: 'V', 30: 'W', 31: 'X',
    32: 'Y', 33: 'Z'
};

var valorLetrasRfc3 = {
    '0': '00', '1': '01', '2': '02', '3': '03', '4': '04', '5': '05',
    '6': '06', '7': '07', '8': '08', '9': '09', 'A': '10', 'B': '11', 'C': '12',
    'D': '13', 'E': '14', 'F': '15', 'G': '16', 'H': '17', 'I': '18', 'J': '19',
    'K': '20', 'L': '21', 'M': '22', 'N': '23', '&': '24', 'O': '25', 'P': '26',
    'Q': '27', 'R': '28', 'S': '29', 'T': '30', 'U': '31', 'V': '32', 'W': '33',
    'X': '34', 'Y': '35', 'Z': '36', ' ': '37', 'Ñ': '38'
};

/**
 * Función para generar la CURP del Cliente según el documento
 * "INSTRUCTIVO NORMATIVO" del Marzo del 2006.
 * @returns CURP
 */
function generarCURP() {
    // $("#curp").html('<h2>Generando CURP... <img src="img/loading.gif" style="display: ' +
    //     'inline-block; margin: 0 auto; height: 25px;"> </h2>');
    $("#generaRFCCURP").html('Generando... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    var nombres = doremove(dochange($("#nombresClientes").val().toString().toUpperCase())).toString().trim();
    var apaterno = doremove(dochange($("#apellidoPaterno").val().toString().toUpperCase()).toString().trim());
    var amaterno = doremove(dochange($("#apellidoMaterno").val().toString().toUpperCase()).toString().trim());
    var nombrecompleto = [nombres, apaterno, amaterno];
    var fnacimiento = $("#fechaNacimiento").val();
    genero = genero;
    var estado = $("#lugarNacimiento").find(":selected").attr('name');
    console.log(estado);
    // estado = estado.attr('name').toString().toUpperCase();
    // console.log(estado);

    console.log(genero+nombres+apaterno+amaterno+nombrecompleto+fnacimiento);

    var val = validaDatos(genero, nombres, apaterno, fnacimiento, estado);

    setTimeout(function () {
        if (val == "") {
            var curp = '';
            curp += getParteUno(nombrecompleto);
            curp += getParteDos(fnacimiento);
            curp += genero;
            curp += estado;
            curp += getParteCinco(nombrecompleto);
            curp += getParteSeis(fnacimiento);
            curp += getDigitoVerificador(curp);
            $("#curp").html('<h2>' + curp + '</h2>');
            $("#curp").val(curp);
            $("#curp").focus();

        } else {
            $("#curp").html('<h2>XXXX000000XXXXXX00</h2>');
            $("#snackbar-error").html(val);
            $("#snackbar-error").toggleClass("show");
            setTimeout(function () {
                $("#snackbar-error").toggleClass("show");
            }, 3000);
        }
        $("#generaRFCCURP").empty();
        $("#generaRFCCURP").html('Generar RFC y CURP');

    }, 1000);
}

/**
 * Función para validar que los datos esten completos
 * @param {type} genero
 * @param {type} nombres
 * @param {type} apaterno
 * @param {type} amaterno
 * @param {type} fnacimiento
 * @param {type} estado
 * @returns {String}
 */
function validaDatos(genero, nombres, apaterno, fnacimiento, estado) {
    var msj = '';

    if (genero.toString().length == 0) {
        msj += 'Falta el Genero <br>';
    }
    if (nombres.toString().length == 0) {
        msj += 'Faltan los Nombres <br>';
    }
    if (apaterno.toString().length == 0) {
        msj += 'Falta el Apellido Paterno <br>';
    }
    if (fnacimiento.toString().length == 0) {
        msj += 'Falta el Fecha Nacimiento <br>';
    }
    if (estado.toString().length == 0) {
        msj += 'Falta el Estado Nacimiento <br>';
    }
    return msj;
}

/**
 * Función para obtener los primeros 4 caracteres del CURP
 * @param {type} nombres
 * @returns {String}
 */
function getParteUno(nombres) {
    var resultado = '';
    var nomb = nombres[0];
    var apat = nombres[1];
    var amat = nombres[2];
    resultado = apat[0];
    var found = true;
    var cnt = 0;
    var cnt1 = apat.length;
    for (var c in apat) {
        if (c > 0) {
            var found = vocales.find(function (element) {
                return element == apat[c];
            });
            if (typeof found != 'undefined') {
                resultado += apat[c];
                rev = true;
                break;
            }
        }
        cnt++;
    }
    if (cnt == cnt1)
        found = false;
    if (!found) {
        resultado += 'X';
    }

    if (typeof amat[0] == 'undefined') {
        resultado += 'X' + nomb[0];
    } else {
        resultado += amat[0] + nomb[0];
    }
    /*** Valida palabras prohibidas y ñ ***/
    resultado = doremplace(resultado);
    return resultado;
}

/**
 * Función para devolver la parte dos del CURP
 * @param {type} fnac
 * @returns {String}
 */
function getParteDos(fnac) {
    return fnac.toString().substr(2, 2)
        + fnac.toString().substr(5, 2)
        + fnac.toString().substr(8);
}

/**
 * Función para obtener los 3 caracteres del CURP
 * @param {type} nombres
 * @returns {String}
 */
function getParteCinco(nombres) {
    var resultado = '';
    var nomb = nombres[0];
    var apat = nombres[1];
    var amat = nombres[2];
    var found = true;
    var cnt = 0;
    var cnt1 = apat.length;
    for (var c in apat) {
        if (c > 0) {
            var found = consona.find(function (element) {
                return element == apat[c];
            });
            if (typeof found != 'undefined') {
                resultado += apat[c];
                rev = true;
                break;
            }
        }
    }
    if (cnt == cnt1)
        found = false;
    if (!found) {
        resultado += 'X';
    }

    found = true;
    cnt = 0;
    cnt1 = amat.length;
    for (var c in amat) {
        if (c > 0) {
            var found = consona.find(function (element) {
                return element == amat[c];
            });
            if (typeof found != 'undefined') {
                resultado += amat[c];
                rev = true;
                break;
            }
        }
    }
    if (cnt == cnt1)
        found = false;
    if (!found) {
        resultado += 'X';
    }

    found = true;
    cnt = 0;
    cnt1 = nomb.length;
    for (var c in nomb) {
        if (c > 0) {
            var found = consona.find(function (element) {
                return element == nomb[c];
            });
            if (typeof found != 'undefined') {
                resultado += nomb[c];
                rev = true;
                break;
            }
        }
    }
    if (cnt == cnt1)
        found = false;
    if (!found) {
        resultado += 'X';
    }

    /*** Valida palabras prohibidas y ñ ***/
    resultado = doremplace(resultado);
    return resultado;
}

/**
 * Función para devolver el verificador de homonimia
 * @param {type} fnac
 * @returns {String}
 */
function getParteSeis(fnac) {
    var annio = parseInt(fnac.toString().substr(0, 4));
    var resultado = '0';
    if (annio >= 2000) {
        resultado = 'A'
    }
    return resultado;
}

/**
 * Función para obtener el dígito verificador de la CURP
 * @param {type} curp
 * @returns {String}
 */
function getDigitoVerificador(curp) {
    var r = 0;
    var cnt = 18;
    for (var c in curp) {
        var v = valorLetras[curp[c]];
        r += (v * cnt);
        cnt--;
    }
    r = ((r % 10) - 10);

    if (r < 0) {
        if (r < -9) {
            r = 0;
        } else {
            r = (r * -1);
        }
    } else {
        if (r > 9) {
            r = 0;
        }
    }

    return r;
}

/**
 * Función para omitir palabras prohibidas.
 * @param {type} word
 * @returns {type}
 */
function doremplace(word) {
    word = word.replace(/\//g, 'X');
    word = word.replace(/-/g, 'X');
    word = word.replace(/\./g, 'X');
    word = word.replace(/'/g, 'X');
    word = word.replace(/BACA/g, 'BXCA');
    word = word.replace(/BAKA/g, 'BXKA');
    word = word.replace(/BUEI/g, 'BXEI');
    word = word.replace(/BUEY/g, 'BXEY');
    word = word.replace(/CACA/g, 'CXCA');
    word = word.replace(/CACO/g, 'CXCO');
    word = word.replace(/CAGA/g, 'CXGA');
    word = word.replace(/CAGO/g, 'CXGO');
    word = word.replace(/CAKA/g, 'CXKA');
    word = word.replace(/CAKO/g, 'CXKO');
    word = word.replace(/COGE/g, 'CXGE');
    word = word.replace(/COGI/g, 'CXGI');
    word = word.replace(/COJA/g, 'CXJA');
    word = word.replace(/COJE/g, 'CXJE');
    word = word.replace(/COJI/g, 'CXJI');
    word = word.replace(/COJO/g, 'CXJO');
    word = word.replace(/COLA/g, 'CXLA');
    word = word.replace(/CULO/g, 'CXLO');
    word = word.replace(/FALO/g, 'FXLO');
    word = word.replace(/FETO/g, 'FXTO');
    word = word.replace(/GETA/g, 'GXTA');
    word = word.replace(/GUEI/g, 'GXEI');
    word = word.replace(/GUEY/g, 'GXEY');
    word = word.replace(/JETA/g, 'JXTA');
    word = word.replace(/JOTO/g, 'JXTO');
    word = word.replace(/KACA/g, 'KXCA');
    word = word.replace(/KACO/g, 'KXCO');
    word = word.replace(/KAGA/g, 'KXGA');
    word = word.replace(/KAGO/g, 'KXGO');
    word = word.replace(/KAKA/g, 'KXKA');
    word = word.replace(/KAKO/g, 'KXKO');
    word = word.replace(/KOGE/g, 'KXGE');
    word = word.replace(/KOGI/g, 'KXGI');
    word = word.replace(/KOJA/g, 'KXJA');
    word = word.replace(/KOJE/g, 'KXJE');
    word = word.replace(/KOJI/g, 'KXJI');
    word = word.replace(/KOJO/g, 'KXJO');
    word = word.replace(/KOLA/g, 'KXLA');
    word = word.replace(/KULO/g, 'KXLO');
    word = word.replace(/LILO/g, 'LXLO');
    word = word.replace(/LOCA/g, 'LXCA');
    word = word.replace(/LOCO/g, 'LXCO');
    word = word.replace(/LOKA/g, 'LXKA');
    word = word.replace(/LOKO/g, 'LXKO');
    word = word.replace(/MAME/g, 'MXME');
    word = word.replace(/MAMO/g, 'MXMO');
    word = word.replace(/MEAR/g, 'MXAR');
    word = word.replace(/MEAS/g, 'MXAS');
    word = word.replace(/MEON/g, 'MXON');
    word = word.replace(/MIAR/g, 'MXAR');
    word = word.replace(/MION/g, 'MXON');
    word = word.replace(/MOCO/g, 'MXCO');
    word = word.replace(/MOKO/g, 'MXKO');
    word = word.replace(/MULA/g, 'MXLA');
    word = word.replace(/NACA/g, 'NXCA');
    word = word.replace(/NACO/g, 'NXCO');
    word = word.replace(/PEDA/g, 'PXDA');
    word = word.replace(/PEDO/g, 'PXDO');
    word = word.replace(/PENE/g, 'PXNE');
    word = word.replace(/PIPI/g, 'PXPI');
    word = word.replace(/PITO/g, 'PXTO');
    word = word.replace(/POPO/g, 'PXPO');
    word = word.replace(/PUTA/g, 'PXTA');
    word = word.replace(/PUTO/g, 'PXTO');
    word = word.replace(/QULO/g, 'QXLO');
    word = word.replace(/RATA/g, 'RXTA');
    word = word.replace(/ROBA/g, 'RXBA');
    word = word.replace(/ROBE/g, 'RXBE');
    word = word.replace(/ROBO/g, 'RXBO');
    word = word.replace(/RUIN/g, 'RXIN');
    word = word.replace(/SENO/g, 'SXNO');
    word = word.replace(/TETA/g, 'TXTA');
    word = word.replace(/VACA/g, 'VXCA');
    word = word.replace(/VAGA/g, 'VXGA');
    word = word.replace(/VAGO/g, 'VXGO');
    word = word.replace(/VAKA/g, 'VXKA');
    word = word.replace(/VUEI/g, 'VXEI');
    word = word.replace(/VUEY/g, 'VXEY');
    word = word.replace(/WUEI/g, 'WXEI');
    word = word.replace(/WUEY/g, 'WXEY');
    return word;
}

/**
 * Función para omitir palabras prohibidas.
 * @param {type} word
 * @returns {type}
 */
function doremove(word) {
    word = word.replace(/MA /g, '');
    word = word.replace(/MA. /g, '');
    word = word.replace(/J /g, '');
    word = word.replace(/J. /g, '');
    word = word.replace(/DA /g, '');
    word = word.replace(/DAS /g, '');
    word = word.replace(/DE /g, '');
    word = word.replace(/DEL /g, '');
    word = word.replace(/DER /g, '');
    word = word.replace(/DI /g, '');
    word = word.replace(/DIE /g, '');
    word = word.replace(/DD /g, '');
    word = word.replace(/EL /g, '');
    word = word.replace(/LA /g, '');
    word = word.replace(/LOS /g, '');
    word = word.replace(/LAS /g, '');
    word = word.replace(/LE /g, '');
    word = word.replace(/LES /g, '');
    word = word.replace(/MAC /g, '');
    word = word.replace(/MC /g, '');
    word = word.replace(/VAN /g, '');
    word = word.replace(/VON /g, '');
    word = word.replace(/Y /g, '');
    word = word.replace(/MARIA /g, '');
    word = word.replace(/JOSE /g, '');
    return word;
}

/**
 * Función para omitir palabras prohibidas.
 * @param {type} word
 * @returns {type}
 */
function dochange(word) {
    word = word.replace(/Á/g, 'A');
    word = word.replace(/É/g, 'E');
    word = word.replace(/Í/g, 'I');
    word = word.replace(/Ó/g, 'O');
    word = word.replace(/Ú/g, 'U');
    word = word.replace(/Ä/g, 'A');
    word = word.replace(/Ë/g, 'E');
    word = word.replace(/Ï/g, 'I');
    word = word.replace(/Ö/g, 'O');
    word = word.replace(/Ü/g, 'U');
    word = word.replace(/Ñ/g, 'X');
    return word;
}

/**
 * Función para generar la CURP del Cliente según el documento
 * "INSTRUCTIVO NORMATIVO" del Marzo del 2006.
 * @returns CURP
 */
function generarRFC() {
    // $("#rfc").html('<h2>Generando RFC... <img src="img/loading.gif" style="display: ' +
    //         'inline-block; margin: 0 auto; height: 25px;"> </h2>');
    var nombres = doremoveRfc(dochangeRfc($("#nombresClientes").val().toString().toUpperCase())).toString().trim();
    var apaterno = doremoveRfc(dochangeRfc($("#apellidoPaterno").val().toString().toUpperCase()).toString().trim());
    var amaterno = doremoveRfc(dochangeRfc($("#apellidoMaterno").val().toString().toUpperCase()).toString().trim());
    var nombrecompleto = [nombres, apaterno, amaterno];
    var nombrecompleto2 = nombres + ' ' + apaterno + ' ' + amaterno;
    var fnacimiento = $("#fechaNacimiento").val();

    //console.log(nombres+apaterno+amaterno+nombrecompleto+nombrecompleto2+fnacimiento);

    var val = validaDatosRFC(nombres, apaterno);

    setTimeout(function () {
        if (val == "") {
            var rfc = '';
            rfc += getParteUnoRfc(nombrecompleto);
            rfc += getParteDosRfc(fnacimiento);
            rfc += getHomonimiaRfc(nombrecompleto2);
            rfc += getDigitoVerificadorRfc(rfc);
            rfc = rfc.toString().substring(0,13);
            $("#rfc").html('<h2>' + rfc + '</h2>');
            $("#rfc").val(rfc);
        } else {
            $("#rfc").html('<h2>XXXX000000XXX</h2>');
        }
    }, 1000);
}

/**
 * Función para validar que los datos esten completos
 * @param {type} genero
 * @param {type} nombres
 * @param {type} apaterno
 * @param {type} amaterno
 * @param {type} fnacimiento
 * @param {type} estado
 * @returns {String}
 */
function validaDatosRFC(nombres, apaterno) {
    var msj = '';
    if (nombres.toString().length == 0) {
        msj += 'Faltan los Nombres \n';
    }
    if (apaterno.toString().length == 0) {
        msj += 'Falta el Apellido Paterno \n';
    }
    return msj;
}

/**
 * Función para obtener los primeros 4 caracteres del CURP
 * @param {type} nombres
 * @returns {String}
 */
function getParteUnoRfc(nombres) {
    var resultado = '';
    var nomb = nombres[0];
    var apat = nombres[1];
    var amat = nombres[2];
    resultado = apat[0];
    var found = true;
    var cnt = 0;
    var cnt1 = apat.length;
    for (var c in apat) {
        if (c > 0) {
            var found = vocales.find(function (element) {
                return element == apat[c];
            });
            if (typeof found != 'undefined') {
                resultado += apat[c];
                rev = true;
                break;
            }
        }
        cnt++;
    }
    if (cnt == cnt1)
        found = false;
    if (!found) {
        resultado += amat[0] + nomb[0] + nomb[1];
    } else {
        if (typeof amat[0] == 'undefined') {
            resultado += nomb[0] + nomb[1];
        } else {
            resultado += amat[0] + nomb[0];
        }
    }
    /*** Valida palabras prohibidas y ñ ***/
    resultado = doremplaceRfc(resultado);
    return resultado;
}

/**
 * Función para devolver la parte dos del CURP
 * @param {type} fnac
 * @returns {String}
 */
function getParteDosRfc(fnac) {
    console.log("parte 2 rfc");
    console.log(fnac);
    return fnac.toString().substr(2, 2)
            + fnac.toString().substr(5, 2)
            + fnac.toString().substr(8);
}

/**
 * Función para obtener el dígito verificador de la RFC
 * @param {type} nombres
 * @returns {String}
 */
function getHomonimiaRfc(nombres) {
    var r = 0;
    var r1 = 0;
    var r2 = 0;
    var v = '0';
    for (var c in nombres) {
        v += valorLetrasRfc1[nombres[c]];
    }
    
    for (var n in v) {
        var n2 = v.toString().substr(n, 2);
        if (n < (v.length - 1)) {
            r += (parseInt(n2) * parseInt(n2[1]))
        }
    }
    r = r.toString().substr((r.toString().length - 3));
    r1 = Math.trunc(r / 34);
    r2 = Math.trunc(r % 34);

    return valorLetrasRfc2[r1] + valorLetrasRfc2[r2];
}

/**
 * Función para obtener el dígito verificador del RFC
 * @param {type} rfc
 * @returns {String}
 */
function getDigitoVerificadorRfc(rfc) {
    var r = -1;
    var cnt = 13;
    for (var c in rfc) {
        var v = valorLetrasRfc3[rfc[c]];
        r += (v * cnt);
        cnt--;
    }
    
    r = (r % 11);
    if (r > 0 && r < 10) {
        r = (10 - r);
    } 
    
    if (r > 10){
        r = 'A';
    }
    return r;
}

/**
 * Función para omitir palabras prohibidas.
 * @param {type} word
 * @returns {type}
 */
function doremplaceRfc(word) {
    word = word.replace(/\//g, 'X');
    word = word.replace(/-/g, 'X');
    word = word.replace(/\./g, 'X');
    word = word.replace(/'/g, 'X');
    word = word.replace(/BACA/g, 'BACX');
    word = word.replace(/BAKA/g, 'BAKX');
    word = word.replace(/BUEI/g, 'BUEX');
    word = word.replace(/BUEY/g, 'BUEX');
    word = word.replace(/CACA/g, 'CACX');
    word = word.replace(/CACO/g, 'CACX');
    word = word.replace(/CAGA/g, 'CAGX');
    word = word.replace(/CAGO/g, 'CAGX');
    word = word.replace(/CAKA/g, 'CAKX');
    word = word.replace(/CAKO/g, 'CAKX');
    word = word.replace(/COGE/g, 'COGX');
    word = word.replace(/COGI/g, 'COGX');
    word = word.replace(/COJA/g, 'COJX');
    word = word.replace(/COJE/g, 'COJX');
    word = word.replace(/COJI/g, 'COJX');
    word = word.replace(/COJO/g, 'COJX');
    word = word.replace(/COLA/g, 'COLX');
    word = word.replace(/CULO/g, 'CULX');
    word = word.replace(/FALO/g, 'FALX');
    word = word.replace(/FETO/g, 'FETX');
    word = word.replace(/GETA/g, 'GETX');
    word = word.replace(/GUEI/g, 'GUEX');
    word = word.replace(/GUEY/g, 'GUEX');
    word = word.replace(/JETA/g, 'JETX');
    word = word.replace(/JOTO/g, 'JOTX');
    word = word.replace(/KACA/g, 'KACX');
    word = word.replace(/KACO/g, 'KACX');
    word = word.replace(/KAGA/g, 'KAGX');
    word = word.replace(/KAGO/g, 'KAGX');
    word = word.replace(/KAKA/g, 'KAKX');
    word = word.replace(/KAKO/g, 'KAKX');
    word = word.replace(/KOGE/g, 'KOGX');
    word = word.replace(/KOGI/g, 'KOGX');
    word = word.replace(/KOJA/g, 'KOJX');
    word = word.replace(/KOJE/g, 'KOJX');
    word = word.replace(/KOJI/g, 'KOJX');
    word = word.replace(/KOJO/g, 'KOJX');
    word = word.replace(/KOLA/g, 'KOLX');
    word = word.replace(/KULO/g, 'KULX');
    word = word.replace(/LILO/g, 'LILX');
    word = word.replace(/LOCA/g, 'LOCX');
    word = word.replace(/LOCO/g, 'LOCX');
    word = word.replace(/LOKA/g, 'LOKX');
    word = word.replace(/LOKO/g, 'LOKX');
    word = word.replace(/MAME/g, 'MAMX');
    word = word.replace(/MAMO/g, 'MAMX');
    word = word.replace(/MEAR/g, 'MEAX');
    word = word.replace(/MEAS/g, 'MEAX');
    word = word.replace(/MEON/g, 'MEOX');
    word = word.replace(/MIAR/g, 'MIAX');
    word = word.replace(/MION/g, 'MIOX');
    word = word.replace(/MOCO/g, 'MOCX');
    word = word.replace(/MOKO/g, 'MOKX');
    word = word.replace(/MULA/g, 'MULX');
    word = word.replace(/NACA/g, 'NACX');
    word = word.replace(/NACO/g, 'NACX');
    word = word.replace(/PEDA/g, 'PEDX');
    word = word.replace(/PEDO/g, 'PEDX');
    word = word.replace(/PENE/g, 'PENX');
    word = word.replace(/PIPI/g, 'PIPX');
    word = word.replace(/PITO/g, 'PITX');
    word = word.replace(/POPO/g, 'POPX');
    word = word.replace(/PUTA/g, 'PUTX');
    word = word.replace(/PUTO/g, 'PUTX');
    word = word.replace(/QULO/g, 'QULX');
    word = word.replace(/RATA/g, 'RATX');
    word = word.replace(/ROBA/g, 'ROBX');
    word = word.replace(/ROBE/g, 'ROBX');
    word = word.replace(/ROBO/g, 'ROBX');
    word = word.replace(/RUIN/g, 'RUIX');
    word = word.replace(/SENO/g, 'SENX');
    word = word.replace(/TETA/g, 'TETX');
    word = word.replace(/VACA/g, 'VACX');
    word = word.replace(/VAGA/g, 'VAGX');
    word = word.replace(/VAGO/g, 'VAGX');
    word = word.replace(/VAKA/g, 'VAKX');
    word = word.replace(/VUEI/g, 'VUEX');
    word = word.replace(/VUEY/g, 'VUEX');
    word = word.replace(/WUEI/g, 'WUEX');
    word = word.replace(/WUEY/g, 'WUEX');
    return word;
}

/**
 * Función para omitir palabras prohibidas.
 * @param {type} word
 * @returns {type}
 */
function doremoveRfc(word) {
    word = word.replace(/MA /g, '');
    word = word.replace(/MA. /g, '');
    word = word.replace(/J /g, '');
    word = word.replace(/J. /g, '');
    word = word.replace(/DA /g, '');
    word = word.replace(/DAS /g, '');
    word = word.replace(/DE /g, '');
    word = word.replace(/DEL /g, '');
    word = word.replace(/DER /g, '');
    word = word.replace(/DI /g, '');
    word = word.replace(/DIE /g, '');
    word = word.replace(/DD /g, '');
    word = word.replace(/EL /g, '');
    word = word.replace(/LA /g, '');
    word = word.replace(/LOS /g, '');
    word = word.replace(/LAS /g, '');
    word = word.replace(/LE /g, '');
    word = word.replace(/LES /g, '');
    word = word.replace(/MAC /g, '');
    word = word.replace(/MC /g, '');
    word = word.replace(/VAN /g, '');
    word = word.replace(/VON /g, '');
    word = word.replace(/Y /g, '');
    word = word.replace(/MARIA /g, '');
    word = word.replace(/JOSE /g, '');
    return word;
}

/**
 * Función para omitir palabras prohibidas.
 * @param {type} word
 * @returns {type}
 */
function dochangeRfc(word) {
    word = word.replace(/Á/g, 'A');
    word = word.replace(/É/g, 'E');
    word = word.replace(/Í/g, 'I');
    word = word.replace(/Ó/g, 'O');
    word = word.replace(/Ú/g, 'U');
    word = word.replace(/Ä/g, 'A');
    word = word.replace(/Ë/g, 'E');
    word = word.replace(/Ï/g, 'I');
    word = word.replace(/Ö/g, 'O');
    word = word.replace(/Ü/g, 'U');
    word = word.replace(/Ñ/g, 'X');
    return word;
}