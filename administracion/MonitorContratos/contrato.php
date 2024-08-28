<?php
// header("Content-type: application/pdf");
require_once('../../Controllers/conexion.php'); #conexion a la base de datos
require_once('../../librerias/fpdf/fpdf.php'); #libreria para generar el PDF
require_once('../../Controllers/CredencialesFIRMAMEX.php'); #credenciales de FIRMAMEX
require_once('../../Controllers/firmamex_services.php'); #clase de firmamex
require_once('../../librerias/CifrasEnLetras.php'); #Cifras a Letras

// Crear instancia de la conexion
/* $conection = new Database();
$db = $conection->getConnection(); */

// Crear instancia del cifrado a letras
$letras = new CifrasEnLetras();

$id_solicitud = isset($_GET['id_solicitud']) ? $_GET['id_solicitud'] : ""; #SOL-6789-FGH M || F SOL-6977-NWF
// $tipoPersona = isset($_GET['tipoPersona']) ? $_GET['tipoPersona'] : 'F'; # esto lo puedo quitar puesto que lo traigo de la solicitud.
/* $datos = json_decode(file_get_contents('php://input'), true);
$id_solicitud = $datos['id_solicitud'];
$tipoPersona = $datos['tipoPersona']; */

//Datos Persona Física o Moral
$consulta = $con->query("SELECT A.Nombres, A.ApellidoP, A.ApellidoM, A.Genero, A.FechaNacimiento, B.Calle, B.NumExt, A.Celular, A.Correo, A.CURP, B.NumInt, B.Colonia, B.CP, B.Municipio, B.Estado, B.ID_direccion,
                            A.MontoAutorizado,
                            A.LugarNacimiento,
                            A.FechaAutoriza,
                            A.RazonSocial, 
                            A.RFC,
                            A.FechaAlta,
                            A.GiroMercantil,
                            A.FechaConstitucion,
                            CASE WHEN A.Nacionalidad = 'M' THEN 'MEXICANA' ELSE 'EXTRANJERA' END AS Nacionalidad,
                            A.LineaCreditoID, 
                            C.FolioContrato AS 'RefPago',
                            A.ID_Cliente,
                            CASE WHEN D.Propietario = 'Si' THEN true ELSE false END AS Propietario,
                            C.SaldoDisponible,
                            A.TipoPersona
                            FROM tb_web_va_solicitud A, tb_web_va_domicilios B, lineascredito C, tb_web_va_prospeccioncliente D
                            WHERE A.ID_Solicitud = B.ID_Solicitud AND C.LineaCreditoID = A.LineaCreditoID AND D.ID_Solicitud = A.ID_Solicitud AND A.ID_Solicitud = '$id_solicitud' AND B.ID_Direccion = 1");
// var_dump($consulta);
if ( $consulta ) {
    $registro = [];
    while ($fila = $consulta->fetch_assoc()) {
        array_push($registro, $fila);
    }

    #TRAER EL VALOR DE LAS COMISIONES
    $comisiones = array();
    $consulta_comisiones = $con->query("SELECT * FROM tb_web_va_catcomisiones");
    while ($fila = $consulta_comisiones->fetch_assoc()) {
        $comisiones[str_replace(' ', '_', $fila['Comision'])] = $fila['Valor'];
    }

    #Traer valores del banco
    $banco_data = array();
    $consulta_bancos = $con->query("SELECT Entidad, Cuenta, CLABE FROM tb_web_va_catbancos WHERE Entidad = 'BBVA'");
    while ($fila_banco = $consulta_bancos->fetch_assoc()) {
        $banco_data['Entidad'] = $fila_banco['Entidad'];
        $banco_data['Cuenta'] = $fila_banco['Cuenta'];
        $banco_data['CLABE'] = $fila_banco['CLABE'];
    }

    # ---
    if (count($registro)) {
        $tipoPersona = $registro[0]['TipoPersona'];
        class PDF extends FPDF
        {
            // MODIFICAMOS LA CABECERA DEL PDF
            function Header()
            {
                global $margen_credito_i, $line_height, $mitad, $font_size_credito, $margen_credito_t, $linea_credito, $total_w_wo_m_credito;

                //Imagen de CONVIVE
                if ($this->PageNo() == 1) {
                    $this->Image('../../img/convivelogo.jpg', 8, 6, 20, null, 'JPG');
                    $this->SetFont('Times', 'B', 8);
                    $this->Cell((28 + (204 - (28 + 30))), 3.5, utf8_decode("CONTRATO INSCRITO EN EL RECA CON NÚMERO:"), 0, 0, 'R');
                    // $this->SetFont('Times', 'BU');
                    $this->Cell(30, 3.5, str_repeat("_", 20), 0, 0);
                    $this->SetY(20);
                }

                if ($this->PageNo() == 2) {
                    $this->Image('../../img/convivelogo.jpg', 87, 3, 38, null, 'JPG');
                    $this->SetY(27.5);
                }

                if ($this->PageNo() > 2 && $this->PageNo() < 13) {
                    $this->Ln(5);
                    $this->Image('../../img/convivelogo.jpg', 10, 3, 27, null, 'JPG');
                    $this->SetFont('Times', 'B', $font_size_credito);
                    $this->Cell($total_w_wo_m_credito, $line_height, utf8_decode('CONTRATO LÍNEA DE CRÉDITO No. ') . $linea_credito, 0, 1, 'R');
                    $this->SetY(22);
                }
            }
            /* function Footer()
            {
                global $total_w_wo_m_credito, $line_height, $font_size_credito, $margen_credito_i;
                if ($this->PageNo() > 1 && $this->PageNo() < 11) {
                    $this->SetXY($margen_credito_i, $this->GetPageHeight() - 20);
                    $this->SetFont('Times', 'B', $font_size_credito);
                    $this->Cell($total_w_wo_m_credito, $line_height, utf8_decode("EL ACREDITANTE"), 0, 1);
                    $this->Ln(5);
                    $this->SetX($margen_credito_i);
                    $this->Cell($total_w_wo_m_credito, $line_height, str_repeat('_', 20), 0, 1);
                }
            } */
        }

        $pdf = new PDF('P','mm','Letter');
        $persona_fisica = ($tipoPersona == 'F' or $tipoPersona == 'PFAE') ? true : false;
        $margen_i = $margen_d = $margen_t = 6;
        $total_w_wo_m = 204;
        $font_size = 8.5;
        $pdf->SetMargins($margen_i, $margen_d, $margen_t);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        # CARATULA 1
            # Rectangulos
                $pdf->SetDrawColor(51, 51, 51);
                $pdf->SetLineWidth(0.1);
                $pdf->Rect($margen_i, 25.5, $total_w_wo_m, 13, 'D'); //seccion1

                $total4 = $total_w_wo_m / 4;
                $pdf->Rect($margen_i + $total4, 38.5, $total4, 13, 'D'); //tasainteres seccion 2
                $pdf->Rect($margen_i + $total4 * 3, 38.5, $total4, 13, 'D'); //tipocredito seccion 2

                $pdf->Rect($margen_i, 51.5, $total4, 24, 'D'); //seccion3 1
                $pdf->Rect($margen_i + $total4, 51.5, $total4, 24, 'D'); //seccion3 2
                $pdf->Rect($margen_i + $total4 * 2, 51.5, $total4, 24, 'D'); //seccion3 3
                $pdf->Rect($margen_i + $total4 * 3, 51.5, $total4, 24, 'D'); //seccion3 4

                $pdf->Rect($margen_i, 75.5, $total4, 21, 'D'); //seccion4 1
                $pdf->Rect($margen_i + $total4, 75.5, $total4 * 3, 21, 'D'); //seccion4 2

                $pdf->Rect($margen_i, 102.5, $total_w_wo_m, 46, 'D'); //seccion6
                
                $pdf->Rect($margen_i, 148.5, $total_w_wo_m, 17.5, 'D'); //seccion7
                
                $pdf->Rect($margen_i, 178, $total_w_wo_m, 12, 'D'); //seccion10
                
                $pdf->Rect($margen_i, 190, $total_w_wo_m, 25, 'D'); //seccion11
                
                $pdf->Rect($margen_i, 215, $total_w_wo_m, 16, 'D'); //seccion12
            # -----------
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($total_w_wo_m, 3.5, utf8_decode("Cáratula de Crédito"), 0, 1, 'C');
            #seccion 1
                $pdf->SetFont('Arial', 'B', $font_size);
                $pdf->Cell($total_w_wo_m, 4, "", 0, 1);
                $pdf->Cell(5, 4, "", 0, 0);
                $pdf->Cell(60, 3.5, utf8_decode("Nombre Comercial del producto: CONVIVE CUENTA CORRIENTE"), 0, 1);
                $pdf->Cell($total_w_wo_m, 2, "", 0, 1);
                $pdf->Cell(5, 4, "", 0, 0);
                $pdf->Cell(60, 3.5, utf8_decode("Tipo de crédito: PERSONA " . ($persona_fisica ? 'FÍSICA' . ($tipoPersona == 'PFAE' ? ' CON ACTIVIDAD EMPRESARIAL.' : '.') : 'MORAL.')), 0, 1);
                $pdf->Ln(2);
            # -----------
            #seccion 2
                $pdf->Cell($total4, 13, utf8_decode("CAT (Costo Anual Total)"), 'LTB', 0, 'C');
                $x1 = $pdf->GetX();
                $pdf->SetXY($x1, $pdf->GetY() + 2.5);
                $pdf->MultiCell($total4, 4, utf8_decode("TASA DE INTERÉS ANUAL ORDINARIA Y MORATORIA"), 0, 'C');
                $pdf->SetXY($x1 + $total4, $pdf->GetY() - 10.5);
                $pdf->Cell($total4, 13, utf8_decode("MONTO O LÍNEA DE CRÉDITO"), 'TB', 0, 'C');
                $x1 += $total4 * 2;
                $pdf->SetXY($x1, $pdf->GetY() + 2.5);
                $pdf->MultiCell($total4, 4, utf8_decode("MONTO TOTAL A PAGAR O MÍNIMO A PAGAR"), 0, 'C');
                $pdf->Ln(4);
            # -----------
            #seccion 3
                #col 1 
                $ys3 = $pdf->GetY();
                $pdf->SetFont('Arial', 'BU', 10);
                $pdf->SetY($ys3 + 2);
                $pdf->Cell($total4, 4, $comisiones['CAT'] . ' %', 0, 1, 'C');
                $pdf->Ln(1.5);
                $pdf->SetFont('Arial', '');
                $pdf->MultiCell($total4, 4, utf8_decode("Sin IVA. \"Para fines informativos y de comparación\""), 0, 'C');
                #col 2
                $pdf->SetXY($total4 + $margen_i + 2, $ys3);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell($total4 - 4, 4, utf8_decode("Ordinaria: NA"), 0, 1);
                $pdf->Ln(1.5);                
                $pdf->SetFont('Arial', '');
                $pdf->SetXY($pdf->GetX() + $total4 + 2, $pdf->GetY());
                $pdf->Cell($total4 - 4, 4, utf8_decode("(Fija)"), 0, 1, 'C');
                $pdf->Ln(1.5);                        
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetXY($pdf->GetX() + $total4 + 2, $pdf->GetY());
                $pdf->Cell($total4 - 4, 4, utf8_decode("Moratoria: 18%"), 0, 1);
                $pdf->Ln(1.5);                
                $pdf->SetFont('Arial', '');
                $pdf->SetXY($pdf->GetX() + $total4 + 2, $pdf->GetY());
                $pdf->Cell($total4 - 4, 4, utf8_decode("(Fija)"), 0, 1, 'C');
                #col 3
                // $pdf->SetXY($total4 * 2 + $margen_i, $ys3);
                $pdf->SetXY($total4 * 2 + $margen_i, $ys3 + 7);
                $pdf->MultiCell($total4, 4, "$" . number_format($registro[0]['MontoAutorizado'], 2, '.', ',') . "\nMONEDA NACIONAL", 0, 'C');
                #col 4
                $pdf->SetXY($total4 * 3 + $margen_i, $ys3 + 7);
                $pdf->MultiCell($total4, 4, "$". number_format($registro[0]['SaldoDisponible'], 2, '.', ',') ."\nMONEDA NACIONAL", 0, 'C');
                $pdf->Ln(8.5);
            # -----------
            #seccion 4
                $ys4 = $pdf->GetY();
                #col 1
                $pdf->SetFont('Arial', 'B');
                $pdf->Cell($total4, 4, utf8_decode("PLAZO DEL CRÉDITO:"), 0, 1);
                $pdf->Ln(1);
                $pdf->SetFont('Arial', '');
                $pdf->Cell($total4, 4, utf8_decode("Número de pagos: 12"), 0, 1);
                $pdf->Ln(1);
                $pdf->Cell($total4, 4, utf8_decode("Frecuencia del pago: mensual"), 0, 1);
                $pdf->Ln(1);
                #col 2
                $pdf->SetXY($total4 + $margen_i + 2, $ys4);
                $pdf->SetFont('Arial', 'B');
                $pdf->Cell(37, 4, utf8_decode("Fecha límite de pago:"), 0, 0);
                $pdf->SetFont('Arial', '');
                $pdf->Cell($total4, 4, substr($registro[0]['FechaAutoriza'], 0, 10), 0, 1);
                $pdf->Ln(1);

                $pdf->SetFont('Arial', 'B');
                $pdf->SetX($total4 + $margen_i + 2);
                $pdf->Cell(27, 4, utf8_decode("Fecha de corte:"), 0, 0);
                $pdf->SetFont('Arial', '');
                
                $fechaAutorizamodify = (new DateTime($registro[0]['FechaAutoriza']))->modify('+12 months');
                $pdf->Cell($total4, 4, $fechaAutorizamodify->format('Y-m-d'), 0, 1);
                $pdf->Ln(1);

                $pdf->SetX($total4 + $margen_i + 2);
                $pdf->MultiCell($total4 * 3 - 4, 4, utf8_decode("Para periodos siguientes, de conformidad con la tabla de amortización que se adjunta al presente contrato y forma parte integrante del mismo."), 0);
                $pdf->Ln(2);
            # -----------
            #seccion 5
                $pdf->SetFont('Arial', 'B');
                $pdf->MultiCell($total_w_wo_m, 2, utf8_decode(" \nCOMISIONES RELEVANTES\n "), 1, 'C');
                $pdf->Ln(1);
            # -----------
            #seccion 6
                #apertura
                    $pdf->Cell(10, 4, "", 0, 0, 'C');
                    $pdf->Cell(5, 4, "*", 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B');
                    $pdf->Cell(17, 4, utf8_decode("Apertura:"), 0, 0);
                    $text_w = $total_w_wo_m - 15 - 17;
                    $pdf->SetFont('Arial', '');
                    $pdf->Cell($text_w, 4, utf8_decode("$" . number_format($registro[0]['MontoAutorizado'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($registro[0]['MontoAutorizado']))) . " m.n.) más IVA. Método de cálculo: Importe fijo que se paga a la firma"), 0, 1);
                    $pdf->Ln(1);
                    $pdf->Cell(15, 4, "", 0, 0);
                    $pdf->Cell($text_w, 4, utf8_decode("del contrato de crédito por única ocasión."), 0, 1);
                    $pdf->Ln(1);
                #reposicion
                    $pdf->Cell(10, 4, "", 0, 0, 'C');
                    $pdf->Cell(5, 4, "*", 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B');
                    $pdf->Cell(67, 4, utf8_decode("Reposición por medios de disposición:"), 0, 0);
                    $text_w = $total_w_wo_m - 15 - 67;
                    $pdf->SetFont('Arial', '');
                    $pdf->Cell($text_w, 4, utf8_decode("$" . number_format($comisiones['Reposicion_Tarjeta'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Reposicion_Tarjeta']))) . " M.N.) más IVA. Método de cálculo: Solo aplica"), 0, 1);
                    $pdf->Ln(1);
                    $pdf->Cell(15, 4, "", 0, 0);
                    $pdf->Cell($text_w, 4, utf8_decode("cuando el. acreditado haya elegido medio de disposición del crédito la tarjeta y se haya extraviado la misma."), 0, 1);
                    $pdf->Ln(1);
                # Reclamacion
                    $pdf->Cell(10, 4, "", 0, 0, 'C');
                    $pdf->Cell(5, 4, "*", 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B');
                    $pdf->Cell(48, 4, utf8_decode("Reclamación improcedente:"), 0, 0);
                    $text_w = $total_w_wo_m - 15 - 48;
                    $pdf->SetFont('Arial', '');
                    $pdf->Cell($text_w, 4, utf8_decode("$" . number_format($comisiones['Comision_por_Aclaracion'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Comision_por_Aclaracion']))) . " M.N.) más IVA. Método de cálculo: Importe fijo"), 0, 1);
                    $pdf->Ln(1);
                    $pdf->Cell(15, 4, "", 0, 0);
                    $pdf->Cell($text_w, 4, utf8_decode("originado por cada reclamación que resulte improcedente. Se cobrará por cada evento de reclamación improcedente."), 0, 1);
                    $pdf->Ln(1);
                # cobranza
                    $pdf->Cell(10, 4, "", 0, 0, 'C');
                    $pdf->Cell(5, 4, "*", 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B');
                    $pdf->Cell(17, 4, utf8_decode("Cobranza:"), 0, 0);
                    $text_w = $total_w_wo_m - 15 - 17;
                    $pdf->SetFont('Arial', '');
                    $pdf->Cell($text_w, 4, utf8_decode("$" . number_format($comisiones['Gastos_de_Administracion'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Gastos_de_Administracion']))) . " M.N.) más IVA. Método de cálculo: Importe fijo que se cobrará por las"), 0, 1);
                    $pdf->Ln(1);
                    $pdf->Cell(15, 4, "", 0, 0);
                    $pdf->Cell($text_w, 4, utf8_decode("gestiones de cobranza derivadas por el incumplimiento de pago del cliente."), 0, 1);
                    $pdf->Ln(1);
                # other
                    $pdf->Cell(10, 4, "", 0, 0, 'C');
                    $pdf->Cell(5, 4, "*", 0, 0, 'C');
                    $pdf->Cell($total_w_wo_m - 15, 4, utf8_decode("Para otras comisiones consulte la clausula OCTAVA del Contrato de Crédito."), 0, 1);
                $pdf->Ln(3);
            # -----------
            #seccion 7
                $pdf->SetFont("Arial", "B");
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("ADVERTENCIA"), 0, 1);
                $pdf->Ln(2);
                $pdf->SetFont("Arial", "");
                $pdf->Cell(10, 4, "", 0, 0);
                $pdf->Cell($total_w_wo_m - 10, 4, utf8_decode("\"Incumplir tus obligaciones te puede generar Comisiones e intereses moratorios\""), 0, 1);
                $pdf->Ln(1);
                $pdf->Cell(10, 4, "", 0, 0);
                $pdf->Cell($total_w_wo_m - 10, 4, utf8_decode("\"Contratar créditos que excedan tu capacidad de pago afecta tu historial crediticio\""), 0, 1);
                $pdf->Ln(0.5);
            # -----------
            #seccion 8
                $pdf->SetFont('Arial', 'B');
                $pdf->MultiCell($total_w_wo_m, 2, utf8_decode(" \nSEGUROS\n "), 1, 'C');
            # -----------
            #seccion 9
                $pdf->SetFont('Arial', '');
                $pdf->Cell($total_w_wo_m / 3, 6, utf8_decode("Seguro de vida: N/A"), 1, 0, 'C');
                $pdf->Cell($total_w_wo_m / 3, 6, utf8_decode("Aseguradora:  N/A"), "TB", 0, 'C');
                $pdf->Cell($total_w_wo_m / 3, 6, utf8_decode("Cláusula:  N/A"), 1, 1, 'C');
                $pdf->Ln(1);
            # -----------
            #seccion 10
                $pdf->SetFont('Arial', 'B');
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("ESTADO DE CUENTA"), 0, 1);
                $pdf->Ln(2);
                $pdf->SetFont('Arial', '');
                $pdf->Cell($total_w_wo_m / 3, 4, utf8_decode("Enviar a: domicilio _______"), 0, 0);
                $pdf->Cell($total_w_wo_m / 3, 4, utf8_decode("Consulta: Vía internet ___X___"), 0, 0);
                $pdf->Cell($total_w_wo_m / 3, 4, utf8_decode("Envío por correo electrónico_______ "), 0, 1);
                $pdf->Ln(1.5);
            # -----------
            #seccion 11
                $pdf->SetFont('Arial', 'B');
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("ACLARACIONES Y RECLAMACIONES:"), 0, 1);
                $pdf->Ln(2);
                $pdf->SetFont('Arial', '');
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("Unidad Especializada de Atención a Usuarios:"), 0, 1);
                $pdf->Ln(.5);
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("Domicilio: Avenida Mayran, No. 756, Col. Torreón Jardín, C.P. 27200, Torreón, Coahuila de Zaragoza."), 0, 1);
                $pdf->Ln(.5);
                $pdf->Cell(80, 4, utf8_decode("Teléfonos: 800 0440156 y 871 7225506 ext. 1112"), 0, 0);
                $pdf->Cell(5, 4, "", 0, 0);
                $pdf->Cell(31, 4, utf8_decode("Correo electrónico:"), 0, 0);
                $pdf->SetTextColor(13, 110, 253); //azul
                $pdf->SetFont('Arial', 'U');
                $pdf->Cell(40, 4, 'une@convivefinanciera.com', 0, 1, 'mailto:www.creditoventacero.com');
                $pdf->SetFont('Arial', '');
                $pdf->SetTextColor(0, 0, 0); //negro
                $pdf->Ln(.5);
                $pdf->Cell(31, 4, utf8_decode('Página de Internet:'), 0, 0);
                $pdf->SetTextColor(13, 110, 253); //azul
                $pdf->SetFont('Arial', 'U');
                $pdf->Cell(40, 4, 'www.convivefinanciera.com', 0, 1, 'www.convivefinanciera.com');
                $pdf->Ln(2);
            # -----------
            #seccion 12
                $pdf->SetFont('Arial', 'B');
                $pdf->SetTextColor(0, 0, 0); //negro
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("Registro de Contratos de Adhesión Núm:"), 0, 1);
                $pdf->Ln(1);
                $pdf->SetFont('Arial', '');
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("Comisión Nacional para la Protección y Defensa de los Usuarios de Servicios Financieros (CONDUSEF):"), 0, 1);
                $pdf->Ln(.5);
                $pdf->Cell($total_w_wo_m, 4, utf8_decode("Teléfono: 800 999 8080 y 55 5340 0999. Página de Internet. www.condusef.gob.mx"), 0, 1);
            # -----------
            #seccion 13
                $pdf->Ln(7);
                $pdf->Cell(($total_w_wo_m - 65), 4, utf8_decode("La presente carátula forma parte integrante del contrato de crédito número"), 0, 0, 'R');
                $pdf->SetFont("Arial", 'B');
                $pdf->Cell(21, 4, $registro[0]['LineaCreditoID'], 0, 0, 'C');
                $pdf->SetFont("Arial", '');
                $pdf->Cell($total_w_wo_m - ($total_w_wo_m - 65) - 21, 4, utf8_decode("celebrado entre"), 0, 1);

                $pdf->Cell($total_w_wo_m - 70, 4, utf8_decode("Convive Financiera, S.A. de C.V., SOFOM, E.N.R., y el Acreditado"), 0, 0, 'R');
                $pdf->SetFont("Arial", 'B');
                $pdf->Cell(60, 4, utf8_decode(($persona_fisica ? ($registro[0]['Nombres'] . ' ' . $registro[0]['ApellidoP'] . ' ' . $registro[0]['ApellidoM'] . ".") : $registro[0]['RazonSocial'] . ".")), 0, 1);
            # -----------
        # ----------

        // $pdf = new PDF('P','mm','Legal');
        // $persona_fisica = $tipoPersona == 'F' ? true : false;
        $margen_i = $margen_d = $margen_t = 6;
        $total_w_wo_m = 204;
        $font_size = 8.5;
        $pdf->SetMargins($margen_i, $margen_d, $margen_t);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage('P', "Legal");
        $pdf->SetFont('Times', 'B', $font_size);
        # Caratula Contrato
            $pdf->SetDrawColor(51, 51, 51);
            $pdf->SetLineWidth(0.4);

            $pdf->SetFillColor(31, 56, 100);
            //Definir rectangulos
                $pdf->Rect($margen_i, 30, $total_w_wo_m, 3.5, 'DF'); //titulo1

                $pdf->SetFillColor(191, 143, 0);
                $pdf->Rect($margen_i, 33.5, $total_w_wo_m, 3.5, 'DF'); //titulo2
                $pdf->Rect($margen_i, ($persona_fisica ? 83.5 : 118.4), $total_w_wo_m, 3.5, 'DF'); //titulo3
                if (!$persona_fisica) $pdf->Rect($margen_i, 79.9, $total_w_wo_m, 3.5, 'DF'); //titulo5

                $pdf->SetFillColor(191, 191, 191);
                $pdf->Rect($margen_i, ($persona_fisica ? 87 : 121.9), $total_w_wo_m, 3.5, 'DF'); //titulo4
                $pdf->Rect($margen_i, 37, 30, 7, 'DF');//Apellido Paterno
                if ($persona_fisica) {
                    $pdf->Rect(68, 37, 30, 7, 'DF');//Apellido Materno
                    $pdf->Rect(133, 37, 25, 7, 'DF');//Nombres
                    $pdf->Rect(36, 44, 32, 8.5, 'D');//Genero Blanco
                    $pdf->Rect(42, 46, 3, 3, 'D');//Genero F
                    $pdf->SetFillColor(51, 51, 51);
                    if ($registro[0]['Genero'] == 'M') $pdf->Rect(42.6, 46.6, 1.8, 1.8, 'F');//Genero M Marcado
                    if ($registro[0]['Genero'] == 'H') $pdf->Rect(55.6, 46.6, 1.8, 1.8, 'F');//Genero H Marcado
                    $pdf->SetFillColor(191, 191, 191);
                    $pdf->Rect(55, 46, 3, 3, 'D');//Genero M
                    $pdf->Rect(98, 44, 35, 8.5, 'D');//Fecha nacimiento Blanco
                    $pdf->Rect(158, 44, 52, 8.5, 'D');//Lugar de Nacimiento Blanco
                }
                $pdf->Rect($margen_i, 44, 30, ($persona_fisica ? 8.5 : 9.9), 'DF');//Genero
                $pdf->Rect(($persona_fisica ? 68 : 73), 44, 30, ($persona_fisica ? 8.5 : 9.9), 'DF');//Fecha nacimiento
                $pdf->Rect(133, 44, 25, ($persona_fisica ? 8.5 : 9.9), 'DF');//Lugar de Nacimiento
                if (!$persona_fisica) {
                    // $pdf->Rect($margen_i, 53.9, 30, 7, 'DF'); //numero serie fiel
                    // $pdf->Rect(103, 53.9, 30, 7, 'DF'); // fecha constitucion
                    $pdf->Rect($margen_i, 83.4, 30, 7, 'DF');#apellido p
                    $pdf->Rect(71, 83.4, 30, 7, 'DF');#apellido m
                    $pdf->Rect(136, 83.4, 23, 7, 'DF');#nombres
                    $pdf->Rect($margen_i, 90.4, 30, 20, 'DF');//Domicilio
                    $pdf->Rect(36, 90.4, 174, 20, 'D');//Domicilio Blanco
                    $pdf->Rect($margen_i, 110.4, 182, 8, 'DF');//propietario real
                    $pdf->Rect(190, 111.3, 2.3, 2.3, 'D');//si
                    $pdf->Rect(190, 114.7, 2.3, 2.3, 'D');//no
                    $pdf->SetFillColor(51, 51, 51);
                    if ($registro[0]['Propietario']) $pdf->Rect(190.4, 111.7, 1.45, 1.45, 'F');
                    if (!$registro[0]['Propietario']) $pdf->Rect(190.4, 115.1, 1.45, 1.45, 'F');
                    $pdf->SetFillColor(191, 191, 191);
                    $pdf->Rect(188, 110.4, 22, 8, 'D');//propietario real blanco
                }
                $pdf->Rect($margen_i, ($persona_fisica ? 52.5 : 53.9), 30, 21, 'DF');//Domicilio
                $pdf->Rect(36, ($persona_fisica ? 52.5 : 53.9), 174, 21, 'D');//Domicilio Blanco
                $pdf->Rect($margen_i, ($persona_fisica ? 73.5 : 74.9), 30, 5, 'DF');#Celular
                $pdf->Rect(96, ($persona_fisica ? 73.5 : 74.9), 30, 5, 'DF');#Correo
                if ($persona_fisica) {
                    $pdf->Rect($margen_i, 78.5, 30, 5, 'DF');#CURP
                    $pdf->Rect(106, 78.5, 20, 5, 'DF');#RFC
                }

                $pdf->Rect($margen_i, ($persona_fisica ? 90.5 : 125.4), 40, 14, 'DF');#tipo credito
                $pdf->Rect(46, ($persona_fisica ? 90.5 : 125.4), 60, 14, 'D');#tipo credito blanco
                $pdf->Rect(106, ($persona_fisica ? 90.5 : 125.4), 30, 14, 'DF');#monto credito
                $pdf->Rect($margen_i, ($persona_fisica ? 104.5 : 139.4), 40, 10, 'DF');#plazo credito
                $pdf->Rect(106, ($persona_fisica ? 104.5 : 139.4), 30, 10, 'DF');#fecha otorgamiento
                $pdf->Rect($margen_i, ($persona_fisica ? 114.5 : 149.4), 40, 13.5, 'DF');#costo anual
                $pdf->Rect(106, ($persona_fisica ? 114.5 : 149.4), 30, 13.5, 'DF');#tasa interes
                /* $pdf->Rect($margen_i, ($persona_fisica ? 127.9 : 171.8), 40, 15, 'DF');#Estados cuenta
                $pdf->Rect(52, ($persona_fisica ? 131 : 173), 3, 3, 'D');//Domicilio check
                $pdf->Rect(52, ($persona_fisica ? 134.8 : 177), 3, 3, 'D');//Correo check
                $pdf->Rect(46, ($persona_fisica ? 129.9 : 171.8), 164, 15.1, 'D');#Estados cuenta blanco */

                $pdf->Rect($margen_i, ($persona_fisica ? 128 : 162.9), 40, 34, 'DF');#Comisiones relevantes
                $pdf->Rect(46, ($persona_fisica ? 128 : 162.9), 164, ($persona_fisica ? 34 : 33.8), 'D');#Comisiones relevantes blanco

                $pdf->Rect($margen_i, ($persona_fisica ? 162 : 196.6), 40, 16.5, 'DF');#cuenta bancaria acreditante
                $pdf->Rect(46, ($persona_fisica ? 162 : 196.6), 164, 16.5, 'D');#cuenta bancaria acreditante blanco

                $pdf->Rect($margen_i, ($persona_fisica ? 178.5 : 213.1), $total_w_wo_m, 13, 'D');#advertencias
                $pdf->Rect($margen_i, ($persona_fisica ? 191.5 : 226.1), $total_w_wo_m, 24.3, 'DF');#aviso privacidad
                $pdf->Rect($margen_i, ($persona_fisica ? 215.8 : 250.4), $total_w_wo_m, 18, 'DF');#aviso privacidad
                $pdf->Rect($margen_i, ($persona_fisica ? 233.8 : 268.4), $total_w_wo_m, 11, 'DF');#datos comision
            // --

            $pdf->SetY(30.2);
            $pdf->SetFont('Times', 'B', $font_size);
            //Titulos 1-2
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell($total_w_wo_m, 3.5, utf8_decode('CARÁTULA DEL CONTRATO DE APERTURA DE CRÉDITO A CUENTA CORRIENTE') . ($persona_fisica ? "" : " (PERSONA MORAL)"), 0, 1, 'C'); //titulo1
            // $pdf->Ln(-6.3);
            $title_persona = 'FÍSICA.';
            if ($tipoPersona == 'PFAE') {
                $title_persona .= 'FÍSICA CON ACTIVIDAD EMPRESARIAL.';
            } else if (!$persona_fisica) {
                $title_persona = 'MORAL.';
            }
            $pdf->Cell($total_w_wo_m, 3.5, 'DATOS DEL ACREDITADO PERSONA ' . utf8_decode($title_persona), 0, 1, 'C'); //titulo2

            $pdf->Ln(-0.2);
            $pdf->SetTextColor(51, 51, 51);

            if ($persona_fisica) {
                //Datos de la PERSONA FiSICA
                $pdf->Cell(30, 3.5, 'Apellido Paterno', 0, 0, 'L');//Apellido Paterno
                // $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell(32, 7, $registro[0]['ApellidoP'], 1, 0, 'C');
                // $pdf->SetFont('Times', 'B', $font_size);
                $pdf->Cell(30, 3.5, 'Apellido Materno', 0, 0, 'L');//Apellido Materno
                // $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell(35, 7, $registro[0]['ApellidoM'], 1, 0, 'C');
                // $pdf->SetFont('Times', 'B', $font_size);
                $pdf->Cell(25, 3.5, 'Nombre(s)', 0, 0, 'L');//Nombres
                // $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell(52, 7, $registro[0]['Nombres'], 1, 1, 'C');
                $pdf->SetFont('Times', 'B', $font_size);
                
                $pdf->Cell(30, 5, utf8_decode('Género'), 0, 0, 'L');//Genero
                $pdf->SetXY(37, 46);
                $pdf->SetFontSize(10);
                $pdf->Cell(5, 3.5, 'M', 0, 0, 'L');//Genero F
                $pdf->SetXY(49, 46);
                $pdf->Cell(30, 3.5, 'H', 0, 0, 'L');//Genero M
                
                $pdf->SetXY(68, 44);
                $pdf->SetFontSize($font_size);
                $pdf->MultiCell(30, 3.5, 'Fecha de Nacimiento', 0, 'L');//Fecha nacimiento
                $pdf->SetXY(98, 44);
                $pdf->Cell(35, 8.5, explode("-", $registro[0]['FechaNacimiento'])[0] . ' / ' . explode("-", $registro[0]['FechaNacimiento'])[1] . ' / ' . explode("-", $registro[0]['FechaNacimiento'])[2], 0, 0, 'C');
                // $pdf->SetXY(98, 48.5);
                // $pdf->SetFont('Times', '', $font_size);
                // $pdf->Cell(35, 3.5, '(DD) (MM) (AAAA)', 0, 1, 'C');
                
                $pdf->SetFont('Times', 'B', $font_size);
                // $pdf->SetXY(133, 44);
                $pdf->Cell(25, 3.5, 'Nacionalidad', 0, 0, 'L');//Lugar de Nacimiento
                // $pdf->SetXY(158, 45);
                $pdf->Cell(52, 8.5, $registro[0]['Nacionalidad'], 0, 0, 'C');
                // $pdf->SetXY(158, 48.5);
                // $pdf->SetFont('Times', '', $font_size);
                // $pdf->Cell(52, 3.5, utf8_decode('(País y Entidad Federativa)'), 0, 1, 'C');
                $pdf->SetFont('Times', 'B', $font_size);
                $pdf->SetY(60);
            } else {
                // $pdf->Ln(1.5);
                $pdf->MultiCell(30, 3.2, utf8_decode('Denominación o Razón Social'), 0, 'C');
                $pdf->SetXY(36, 37);
                $pdf->Cell(174, 7, utf8_decode($registro[0]['RazonSocial']), 1, 1);

                $pdf->MultiCell(30, 3.3 , "Giro Mercantil, Actividad u Objeto Social", 1, 'C');
                $pdf->SetXY(36, 44);
                $pdf->MultiCell(37, 3.5, utf8_decode($registro[0]['GiroMercantil']), 0, 'C');
                $pdf->SetXY(71, 44);
                $pdf->MultiCell(30, 3.5 , utf8_decode("Fecha de Constitución"), 0, 'C');
                $pdf->SetXY(103, 44);
                $pdf->Cell(30, 9.9, date_format(date_create(substr($registro[0]['FechaConstitucion'], 0, 10)), 'Y/m/d'), 1, 0, 'C');
                $pdf->Cell(25, 9.9, "RFC", 1, 0, 'C');
                $pdf->Cell(52, 9.9, $registro[0]['RFC'], 1, 1, 'C');

                /* $pdf->MultiCell(30, 3.3, utf8_decode("Número de Serie FIEL"), 0, 'C');   
                $pdf->SetXY(36, 53.9);
                $pdf->Cell(67, 7, '', 1, 0);
                $pdf->MultiCell(30, 3.3, "Nacionalidad (Nacional o Extranjera)", 0, 'C');
                $pdf->SetXY(133, 53.9);
                $pdf->Cell(77, 7, '', 1, 1); */
                // $pdf->Ln(5);
            }

            // Datos del domicilio (esto es para ambas personas)

            #Si es persona moral, mostramos la direccion con id 2
            #traemos el domicilio que no es particular
            // $consulta_domre = $db->query("SELECT Calle, NumExt, NumInt, CP, Colonia, Municipio, Estado, Ciudad FROM microfin_pruebas.tb_web_va_domicilios WHERE ID_Solicitud = '$id_solicitud' AND ID_Direccion = 2;");

            $pdf->Cell(30, 3.5, 'Domicilio', 0, 0, 'L'); //Domicilio
            $pdf->SetXY(36, ($persona_fisica ? 54.5 : 54.6));
            $pdf->Cell(174, 3.5, utf8_decode($registro[0]['Calle'] . '              ' . $registro[0]['NumExt'] . '       ' .  $registro[0]['NumInt'] . '              ' . $registro[0]['Colonia']), 0, 1, 'C');
            $pdf->SetXY(36, ($persona_fisica ? 55.5 : 55.6));
            $pdf->Cell(174, 3.5, str_repeat("_", 100), 0, 1, 'C');
            $pdf->SetX(36);
            $pdf->SetFont('Times', '', $font_size);
            $pdf->Cell(174, 3.5, utf8_decode('(Calle)              (Número Exterior)       (Número interior)              (Colonia)'), 0, 1, 'C');
            $pdf->Ln(1.9);
            $pdf->SetX(36);
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->Cell(174, 3.5, utf8_decode($registro[0]['Municipio'] . '          ' . $registro[0]['Estado'] . '           ' . $registro[0]['CP']), 0, 1, 'C');
            $pdf->Ln(-2.5);
            $pdf->Cell(30, 3.5, '', 0, 0, 'C');
            $pdf->Cell(174, 3.5, str_repeat("_", 100), 0, 1, 'C');
            $pdf->SetX(36);
            $pdf->SetFont('Times', '', $font_size);
            $pdf->Cell(174, 3.5, utf8_decode('(Municipio/Delegación)          (Estado)               (País)           (C.P.)'), 0, 1, 'C');
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->Ln($persona_fisica ? 1 : 2.4);

            //Numero de telefono (Esto es para ambas personas)
            $pdf->Cell(30, 5, 'Celular', 0, 0, 'L'); #Numero telefono
            // $pdf->Cell(15, 3.5, 'Fijo', 1, 0, 'C'); #fijo
            $pdf->Cell(60, 5, $registro[0]['Celular'], 1, 0);
            $pdf->Cell(30, 5, utf8_decode('Correo electrónico') , 0, 0);
            $pdf->Cell(84, 5, $registro[0]['Correo'], 1, 1);
            // $pdf->Cell(15, 3.5, utf8_decode('Móvil') , 1, 0, 'C');
            // $pdf->Cell(45, 3.5, '', 1, 1);

            if ($persona_fisica) {
                $pdf->SetY(($persona_fisica ? 78.5 : 85.4));
                //CURP/RFC (Para personas fisicas)
                $pdf->Cell(30, 5, 'CURP', 0, 0);
                $pdf->Cell(70, 5, $registro[0]['CURP'], 1, 0);
                $pdf->Cell(20, 5, 'RFC',1, 0);
                $pdf->Cell(84, 5, $registro[0]['RFC'], 1, 1);
            }
            if (!$persona_fisica) {
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell($total_w_wo_m, 3.5, "Representante Legal", 1, 1, 'C');
                $pdf->SetTextColor(51, 51, 51);
                $pdf->Cell(30, 5, "Apellido Paterno", 0, 0);
                $pdf->Cell(35, 7, utf8_decode($registro[0]['ApellidoP']), 1, 0);
                $pdf->Cell(30, 5, "Apellido Materno", 0, 0);
                $pdf->Cell(35, 7, utf8_decode($registro[0]['ApellidoM']), 1, 0);
                $pdf->Cell(23, 5, "Nombre (s)", 0, 0);
                $pdf->Cell(51, 7, utf8_decode($registro[0]['Nombres']), 1, 1);
                
                $pdf->Cell(30, 3.5, 'Domicilio', 0, 0, 'L'); //Domicilio
                // $pdf->SetXY(36, ($persona_fisica ? 54.5 : 54.6));
                $pdf->SetXY(36, $pdf->GetY() + 1);
                $pdf->Cell(174, 3.5, utf8_decode($registro[0]['Calle'] . '              ' . $registro[0]['NumExt'] . '       ' .  $registro[0]['NumInt'] . '              ' . $registro[0]['Colonia']), 0, 1, 'C');
                $pdf->SetXY(36, 92.4);
                $pdf->Cell(174, 3.5, str_repeat("_", 100), 0, 1, 'C');
                $pdf->SetX(36);
                $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell(174, 3.5, utf8_decode('(Calle)              (Número Exterior)       (Número interior)              (Colonia)'), 0, 1, 'C');
                $pdf->Ln(2);
                $pdf->SetX(36);
                $pdf->SetFont('Times', 'B', $font_size);
                $pdf->Cell(174, 3.5, utf8_decode($registro[0]['Municipio'] . '          ' . $registro[0]['Estado'] . '           ' . $registro[0]['CP']), 0, 1, 'C');
                $pdf->Ln(-2.5);
                $pdf->Cell(30, 3.5, '', 0, 0, 'C');
                $pdf->Cell(174, 3.5, str_repeat("_", 100), 0, 1, 'C');
                $pdf->SetX(36);
                $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell(174, 3.5, utf8_decode('(Municipio/Delegación)          (Estado)               (País)           (C.P.)'), 0, 1, 'C');
                $pdf->SetFont('Times', 'B', $font_size);
                $pdf->Ln(1.5);
                $pdf->Cell(23, 3.5, "Propietario Real:", 0, 0,);
                $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell(159, 3.5, utf8_decode("Es la persona física o grupo de personas físicas que ejercen el control sobre la persona moral, el o los verdadero(s) dueño(s) de los"), 0,1);
                $pdf->Cell(159, 3.5, utf8_decode("recursos, al tener sobre estos derechos de uso, disfrute, aprovechamiento, dispersión o disposición."), 0, 1);
                $pdf->SetXY(194, 111.3);    
                $pdf->Cell(5, 3.5, "Si", 0, 0);
                $pdf->SetXY(194, 114.5);
                $pdf->Cell(5, 3.5, "No", 0, 0);
            }

            //Seccion Datos del credito (Se usa en ambos casos)
            //Titulos 3-4
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetXY($margen_i, ($persona_fisica ? 80.3 : 115.4));
            $pdf->Cell(203, 10, utf8_decode('DATOS DEL CRÉDITO'), 0, 1, 'C'); //titulo3
            $pdf->SetTextColor(51, 51, 51);
            $pdf->SetXY($margen_i, ($persona_fisica ? 83.9 : 118.9));
            $pdf->Cell(203, 10, utf8_decode('Nombre Comercial del Producto: ') . ($persona_fisica ? utf8_decode('9000 - Crédito VentAcero Persona Física' . ($tipoPersona == 'PFAE' ? ' con Actividad Empresarial.' : '.')) : utf8_decode('9001 - Crédito VentAcero Persona Moral.')), 0, 1, 'C'); //titulo4
            /* 
                9000 - Credito VentAcero Persona Fisica
                9001 - Credito VentAcero Persona Moral 
            */
            $pdf->SetXY($margen_i, ($persona_fisica ? 92 : 126));
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->MultiCell(40, 3.3, utf8_decode('TIPO DE CRÉDITO SOLICITADO'), 0, 'C'); #tipo credito
            $pdf->SetXY(46, ($persona_fisica ? 94 : 128.4));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->MultiCell(60, 3.3, utf8_decode("Crédito a cuenta corriente \n Medio de Disposición: Tarjeta Plástica"), 0, 'C'); #credito cuenta corriente
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->SetXY(106, ($persona_fisica ? 91.5 : 126));
            $pdf->MultiCell(30, 3.3, utf8_decode("MONTO DEL CRÉDITO \n (En pesos Moneda Nacional)"), 0, 'C'); #monto credito
            $pdf->SetXY(136, ($persona_fisica ? 90.5 : 125.4));
            $pdf->SetFont('Times', '', 10);
            $pdf->cell(74, 14, number_format($registro[0]['MontoAutorizado'], 2, '.', ',') . 'MXN', 1, 1, 'C');
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->SetXY($margen_i, ($persona_fisica ? 105 : 139.9));
            $pdf->MultiCell(40, 3.5, utf8_decode("PLAZO DEL CRÉDITO \n (MESES)"), 0, 'C'); #plazo credito
            $pdf->SetXY(46, ($persona_fisica ? 104.5 : 139.4));
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(60, 10, $comisiones['Plazo_de_Credito'], 1, 0, 'C');
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->MultiCell(30, 3.5, 'FECHA DE OTORGAMIENTO:', 0, 'C'); #fecha otorgamiento
            $pdf->SetXY(136, ($persona_fisica ? 104.5 : 139.4));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->Cell(74, 10, date_format(date_create(substr($registro[0]['FechaAutoriza'], 0, 10)), 'Y/m/d'), 1, 1, 'C');
            $pdf->SetFont('Times', 'B');
            $pdf->MultiCell(40, 4, "COSTO ANUAL TOTAL \n (CAT) (%)", 0, 'C'); #costo anual total
            $pdf->SetFont('Times', '', 7);
            $pdf->MultiCell(40, 2.3, utf8_decode("Sin Iva para fines informativos y de comparación"), 0, 'C'); #sin iva
            $pdf->SetXY(46, ($persona_fisica ? 114.5 : 149.4));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->Cell(60, 13.5, number_format($comisiones['CAT'], 2, ',', '.'), 1, 0, 'C');
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->SetXY(106, ($persona_fisica ? 115 : 149.4));
            $pdf->MultiCell(30, 3.5, utf8_decode("TASA DE INTERÉS MENSUAL MORATORIA"), 0, 'C'); #tasa interes mensual
            $pdf->SetXY(136, ($persona_fisica ? 114.5 : 149.4));
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(74, 13.5, number_format($comisiones['Tasa_Interes_Moratoria'], 2, '.', ',') . '%', 1, 1, 'C');
            $pdf->SetFont('Times', 'B', $font_size);

            /* $pdf->Cell(40, 15, 'Estados de Cuenta', 1, 1, 'C');
            $pdf->SetXY(60, ($persona_fisica ? 131 : 173));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->Cell(20, 3.5, 'Domicilio', 0, 1);
            $pdf->SetXY(60, ($persona_fisica ? 135 : 177));
            $pdf->Cell(30, 3.5, utf8_decode('Correo electrónico'), 0, 1);
            $pdf->SetXY(48, ($persona_fisica ? 141 : 183));
            $pdf->Cell(150, 3.5, utf8_decode('Periodicidad para el Envío de Estados de Cuenta: ***'), 0, 1); */

            $pdf->SetXY($margen_i, ($persona_fisica ? 129.6 : 163));
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->MultiCell(40, 3.3, 'COMISIONES RELEVANTES', 0, 'C');
            $pdf->SetXY(48, ($persona_fisica ? 129.6 : 163.6));
            $pdf->Cell(5, 3.3, 'a)', 0, 0);
            $pdf->SetFont('Times', '', $font_size);

            // Aquí va el monto autorizado que esta en la tabla tb_web_va_solicitudes
            $pdf->SetXY(54, ($persona_fisica ? 129 : 163.1));
            $pdf->MultiCell(153, 3.1, utf8_decode('Apertura de Crédito: La cantidad de $' . number_format($registro[0]['MontoAutorizado'], 2, '.', ',') . ' MXN (' . ucfirst(strtolower($letras->convertirNumeroEnLetras($registro[0]['MontoAutorizado']))) . ' Moneda Nacional) más el Impuesto al Valor Agregado correspondiente;'), 0);
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->SetXY(48, ($persona_fisica ? 134.6 : 169.5));
            $pdf->Cell(5, 5, 'b)', 0, 0);
            $pdf->SetXY(54, ($persona_fisica ? 136 : 170.6));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->MultiCell(153, 3.1, utf8_decode('Reposición de la Tarjeta Plástica: Por cada reposición de Tarjeta Plástica, la cantidad de $' . number_format($comisiones['Reposicion_Tarjeta'], 2, '.', 'a') . ' MXN (' . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Reposicion_Tarjeta']))) . ' Moneda Nacional) más el Impuesto al Valor Agregado correspondiente;'),0);
            $pdf->SetFont('Times', 'B' ,$font_size);
            $pdf->SetXY(48, ($persona_fisica ? 142 : 175.6));
            $pdf->Cell(5, 5, 'c)', 0, 0);
            $pdf->SetXY(54, ($persona_fisica ? 142.5 : 176.8));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->MultiCell(153, 3.1, utf8_decode('Gastos de Cobranza: la cantidad de $' . number_format($comisiones['Gastos_de_Cobranza'], 2, '.', ',') . ' MXN (' . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Gastos_de_Cobranza']))) . ' Moneda Nacional) más el Impuesto al Valor Agregado correspondiente. Dicha comisión será generada por cada evento de incumplimiento.'),0);
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->SetXY(48, ($persona_fisica ? 148.6 : 182.6));
            $pdf->Cell(5, 5, 'd)', 0, 0);
            $pdf->SetXY(54, ($persona_fisica ? 149 : 183.6));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->MultiCell(153, 3.1, utf8_decode('Gastos de Administración: La cantidad de $' . number_format($comisiones['Gastos_de_Administracion'], 2, '.', ',') . ' MXN (' . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Gastos_de_Administracion']))) . ' Moneda Nacional) más el Impuesto al Valor Agregado correspondiente, por las gestiones y gastos que se deriven del Contrato.'),0);
            $pdf->SetFont('Times', 'B',$font_size);
            $pdf->SetXY(48, ($persona_fisica ? 155 : 188.6));
            $pdf->Cell(5, 5, 'e)', 0, 0);
            $pdf->SetXY(54, ($persona_fisica ? 155.6 : 189.9));
            $pdf->SetFont('Times', '', $font_size);
            $pdf->MultiCell(153, 3.1, utf8_decode('Comisión por Aclaración Improcedente: La cantidad de $' . number_format($comisiones['Comision_por_Aclaracion'], 2, '.', ',') . ' MXN (' . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Comision_por_Aclaracion']))) . ' Moneda Nacional) más el Impuesto al Valor Agregado correspondiente.'), 0);
            $pdf->Ln($persona_fisica ? 1 : 2);

            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->MultiCell(40, 3.5, 'CUENTA BANCARIA PARA EL PAGO AL ACREDITANTE', 0, 'C');
            $pdf->SetXY(54, ($persona_fisica ? 163.6 : 198.1));
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(16, 3.5, "Banco: " . $banco_data['Entidad'], 0, 1, '');
            $pdf->SetXY(54, ($persona_fisica ? 167.1 : 201.6));
            $pdf->Cell(32, 3.5, utf8_decode("Número de cuenta: ") . $banco_data['Cuenta'], 0, 1, '');
            $pdf->SetXY(54, ($persona_fisica ? 170.6 : 205.1));
            $pdf->Cell(38, 3.5, "CLABE: " . $banco_data['CLABE'], 0, 0, '');
            $pdf->SetXY(54, ($persona_fisica ? 174.1 : 208.6));
            
            $pdf->Cell(38, 3.5, "Referencia de Pago: " . $registro[0]['RefPago'] , 0, 1, '');
            // $pdf->Cell(38, 3.5, "Referencia de Pago: " . $referencia_pago , 0, 1, '');
            $pdf->Ln(1);

            $pdf->Cell(200, 5, 'ADVERTENCIAS:', 0, 1);
            $pdf->SetFont('Times', '',$font_size);
            $pdf->Ln(-0.5);
            $pdf->Cell(2, 5, '', 0, 0);
            $pdf->Cell(102, 3.5, utf8_decode('-  Contratar créditos que excedan tu capacidad de pago afecta tu historial crediticio.'), 0, 1, '');
            $pdf->Cell(2, 5, '', 0, 0);
            $pdf->Cell(99, 3.5, '-  Incumplir tus obligaciones te puede generar comisiones e intereses moratorios.', 0, 1, '');
            $pdf->Ln(1.5);

            $pdf->SetFont('Times', 'B',$font_size);
            $pdf->Cell(37, 3.5, "AVISO DE PRIVACIDAD:", 0, 0);
            $pdf->SetFont('Times', '', 8.35);
            $pdf->Cell(163, 3.5, utf8_decode("En cumplimiento a los dispuesto por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, CONVIVE "), 0, 1);
            $pdf->MultiCell(200, 3.5, utf8_decode('FINANCIERA, S.A. de C.V., S.O.F.O.M., E.N.R. (en lo sucesivo "CONVIVE"), con domicilio en Avenida Mayran, No.756 Col. Torreon Jardin, C.P. 27000, Torreón, Coahuila de Zaragoza, con número de teléfono 800 044 0156 y 8717225506 ext. 1009, hace de su conocimiento que tratará los datos personales generales y sensibles que Usted proporcione para la evaluación de su solicitud y selección de riesgos y, en su caso, prevención de lavado de dinero y operaciones ilícitas, para información y estadísticas así como para todos los fines relacionados con el cumplimiento de nuestras obligaciones de conformidad con lo establecido en el contrato y la normatividad'), 0);
            $pdf->SetY(($persona_fisica ? 208.5 : 243.6));
            $pdf->Cell(107.5, 3.5, utf8_decode("aplicable. Para su mayor información ponemos a su disposición, nuestra página de internet"), 0, 0);
            $pdf->SetTextColor(13, 110, 253);
            $pdf->Cell(36, 3.5, "www.convivefinanciera.com", 0, 0, "https://www.convivefinanciera.com/");
            $pdf->SetTextColor(51, 51, 51);
            $pdf->Cell(60, 3.5, utf8_decode("en donde usted podrá consultar nuestro Aviso de"), 0, 1);
            $pdf->Cell(200, 3.5, utf8_decode("Privacidad Integral, así como los mecanismos para hacer valer sus derechos ARCO."), 0, 1);
            $pdf->Ln(0.5);
            $pdf->SetFont('Times', 'B',$font_size);
            $pdf->Cell(200, 3.5, "ACLARACIONES Y RECLAMACIONES", 0, 1);
            $pdf->SetFont('Times', '',$font_size);
            $pdf->Cell(200, 3.5, utf8_decode("Unidad Especializada de Atención a Usuarios:"), 0, 1);
            $pdf->Cell(200, 3.5, utf8_decode("Domicilio: Avenida Mayran, No. 756 Col. Torreon Jardin, C.P. 27000, Torreón, Coahuila de Zaragoza,"), 0, 1);
            $pdf->Cell(13.5, 3.5, utf8_decode("Teléfono:"), 0, 0);
            $pdf->Cell(7, 3.5, "800 044 0156", 0, 1, '');
            $pdf->Cell(25, 3.5, utf8_decode("Correo Electrónico:"), 0, 0);
            $pdf->SetTextColor(13, 110, 253);
            $pdf->Cell(50, 3.5, "une@convivefinanciera.com", 0, 1, "mailto:une@convivefinanciera.com");
            $pdf->SetTextColor(51, 51, 51);

            $pdf->SetFont('Times', 'B',$font_size);
            $pdf->Cell(200, 4, utf8_decode("DATOS COMISÓN NACIONAL PARA LA PROTECCIÓN Y DEFENSA DE LOS USUARIOS DE SERVICIOS FINANCIEROS (CONDUSEF):"), 0, 1);
            $pdf->SetFont('Times', '',$font_size);
            $pdf->Cell(200, 3.5, utf8_decode("Domicilio en Insurgentes Sur 762, Colonia Del Valle, Delegación Benito Juárez, Código Postal 03100, en la Ciudad de México, teléfono 555340 0999, correo"), 0, 1);
            $pdf->Cell(14, 3.5, utf8_decode("electrónico"), 0, 0);
            $pdf->SetTextColor(13, 110, 253);
            $pdf->Cell(34, 3.5, "asesoria@condusef.gob.mx", 0, 0, "mailto:asesoria@condusef.gob.mx");
            $pdf->SetTextColor(51, 51, 51);
            $pdf->Cell(53, 3.5, utf8_decode("o consultar la página electrónica en Internet"), 0, 0);
            $pdf->SetTextColor(13, 110, 253);
            $pdf->Cell(20, 3.5, "www.condusef.gob.mx.", 0, 1, "www.condusef.gob.mx");
            $pdf->SetTextColor(51, 51, 51);
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->Ln(3);

            $pdf->Cell($total_w_wo_m, 3.5, "Su firma por duplicado, conservando las partes un ejemplar del mismo", 0, 1, 'C');
            $pdf->Ln(3);


            // PONERLO A DOS COLUMNAS
            $mitad_col1 = $total_w_wo_m / 2;
            $y_sc = $pdf->GetY();

            // Columna 1
            $pdf->Cell($mitad_col1, 3.5, "EL ACREDITANTE", 0, 1, 'C');
            $pdf->Ln(11.6);
            $pdf->Image("../../img/FirmaJuanito.png", $margen_d + (($mitad_col1 - 87) / 2) + 30, $pdf->GetY() - 15.8, null, 20, 'PNG');
            $pdf->Cell((($mitad_col1 - 87) / 2), 3.5, "", 0, 0, 'C');
            $pdf->Cell(87, 3.5, str_repeat("_", 40), 0, 1, 'C');
            $pdf->Cell((($mitad_col1 - 60) / 2), 3.5, "", 0, 0, 'C');
            $pdf->MultiCell(60, 3.5, utf8_decode("CONVIVE FINANCIERA, S.A., DE C.V., S.O.F.O.M., E.N.R."), 0, 'C');
            $pdf->SetY($pdf->GetY());
            $pdf->SetFont('Times', '', $font_size);
            $pdf->Cell($mitad_col1, 3.5, "Representado por", 0, 1, 'C');
            $pdf->SetFont('Times', 'B', $font_size);
            $pdf->Cell($mitad_col1, 3.5, "JUAN RAMIREZ CISNEROS", 0, 1, 'C');
            
            // Columna 2
            $pdf->SetXY($mitad_col1 + $margen_d, $y_sc);
            $pdf->Cell($mitad_col1, 3.5, "EL ACREDITADO", 0, 1, 'C');
            $pdf->Ln(12);
            $pdf->SetXY($mitad_col1 + $margen_d, $pdf->GetY());
            $pdf->Cell((($mitad_col1 - 87) / 2), 3.5, "", 0, 0, 'C');
            $pdf->Cell(87, 3.5, str_repeat("_", 40), 0, 1, 'C');
            $pdf->SetXY($mitad_col1 + $margen_d, $pdf->GetY());
            $pdf->Cell((($mitad_col1 - 60) / 2), 3.5, "", 0, 0, 'C');
            if (!$persona_fisica) {
                $pdf->MultiCell(60, 3.5, utf8_decode($registro[0]['RazonSocial']), 0, 'C');
                $pdf->SetXY($mitad_col1 + $margen_d, $pdf->GetY());
                $pdf->SetFont('Times', '', $font_size);
                $pdf->Cell($mitad_col1, 3.5, "Representado por", 0, 1, 'C');
                $pdf->SetFont('Times', 'B', $font_size);
            }
            $pdf->SetXY($mitad_col1 + $margen_d, $pdf->GetY());
            $pdf->MultiCell($mitad_col1, 3.5, utf8_decode($registro[0]['Nombres'] . ' ' . $registro[0]['ApellidoP'] . ' ' . $registro[0]['ApellidoM']), 0, 'C');
        # ----
        
        $margen_credito_i = $margen_credito_d = 10;
        $margen_credito_t = 50;
        $total_w_wo_m_credito = 196;
        $font_size_credito = 10;
        $line_height = 4;
        $mitad = $total_w_wo_m_credito / 2 - 2;
        $linea_credito = $registro[0]['LineaCreditoID'];
        $pdf->SetMargins($margen_credito_i, $margen_credito_d, $margen_credito_t);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage('P', 'Letter');
        #Contrato de crédito
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->SetTextColor(51, 51, 51); #333

            $pdf->MultiCell($mitad, $line_height, utf8_decode('CONTRATO DE APERTURA DE CRÉDITO EN CUENTA CORRIENTE (EL "CONTRATO"), QUE CELEBRAN: (I) LA SOCIEDAD DENOMINADA "CONVIVE FINANCIERA", S.A. DE C.V., SOFOM, E.N.R." (EN LO SUCESIVO LA "ACREDITANTE"), REPRESENTADA POR EL SENOR JUAN RAMÍREZ CISNEROS, Y (II) LA PERSONA CUYA INFORMACION SE CONTIENE EN LA CARÁTULA DEL PRESENTE CONTRATO (EN LO SUCESIVO EL "ACREDITADO"); A QUIENES DE MANERA CONJUNTA SE LES PODRÁ DENOMINAR COMO "LAS PARTES", Y DE FORMA INDIVIDUAL COMO LA "PARTE", AL TENOR DE LAS SIGUIENTES:'), 0, 'J');
            $pdf->Cell($mitad, $line_height, "D E C L A R A C I O N E S", 0, 1, 'C');
            $pdf->Cell(3, $line_height, "A.", 0, 0, 'C');
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Declara el ACREDITANTE, por conducto de su representante legal, que:'), 0, 'J');

            $pdf->Cell(3, $line_height, "1.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Es una persona moral, legalmente constituida conforme a las leyes de la republica mexicana, mediante escritura publica numero 594, volumen sexto, del protocolo a cargo del licenciado Hector Augusto Goray Valdez, notario publico numero 49 en ejercicio en la ciudad de Torreon, Coahuila. Debidamente inscrita en el registro publico de la propiedad y del comercio de la ciudad de Torreon, Coahuila, bajo el folio mercantil electronico numero 82312*1 con fecha 1 de diciembre de 2010'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->Cell(3, $line_height, "2.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Mediante escritura publica numero 509, volumen decimo quinto, del protocolo a cargo del licenciado Hector Augusto Goray Valdez, notario publico numero 49 en ejercicio en la ciudad de Torreon, Coahuila. Debidamente inscrita en el registro publico de la propiedad y del comercio de la ciudad de Torreon, Coahuila, bajo el folio mercantil electronico numero 82312, con fecha 13 de mayo de 2019, se hizo constar el cambio de denominacion de la sociedad al de "CONVIVE FINANCIERA, S.A. DE C.V., SOFOM E.N.R."'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->Cell(3, $line_height, "3.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Su representante legal cuenta con las facultades suficientes y necesarias para celebrar el presente Contrato y obligar a su representada en terminos del mismo, las cuales a la fecha no le han sido modificadas, suspendidas, limitadas y/o revocadas en forma alguna y constan en la escritura publica numero 659 otorgada el 11 de junio de 2019, por el Lic. Hector Augusto Goray Valdez, Notario Publico numero 49 de Torreon, Coahuila, inscrita en el Registro Publico de Comercio de dicha entidad, con Folio Mercantil numero 82312 *1, el 14 de junio de 2019.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->Cell(3, $line_height, "4.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('De conformidad con lo establecido en el articulo 87-J ochenta y siete, guion, letra "jota", de la Ley General de Organizaciones y Actividades Auxiliares del Credito (la "LGOAAC"), para su constitucion y operacion como sociedad financiera de objeto múltiple, asi como para llevar a cabo operaciones de credito, arrendamiento financiero y factoraje financiero con tal caracter, no requiere de autorizacion de la Secretaria de Hacienda y Credito Publico, sin embargo se encuentra sujeta a la supervision de la Comisión Nacional Bancaria y de Valores, para efectos de lo dispuesto en el articulo 56 cincuenta y seis del mismo ordenamiento legal.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->Cell(3, $line_height, "5.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Dentro de su objeto social se encuentra el otorgamiento de credito, entre otros.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->Cell(3, $line_height, "6.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Ha hecho del conocimiento del ACREDITADO, el contenido del presente contrato y de todos los documentos que seran suscritos a su amparo, los cargos, comisiones y gastos que seran'), 0);
            
            $x = ($mitad + $margen_credito_i + 2.5); $y = 22;
            $pdf->SetXY($x, $y);

            $pdf->SetX($x + 3);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('generados por la celebracion de este, asi como el Costo Anual Total ("CAT") de financiamiento expresado en terminos porcentuales anuales que para fines informativos y de comparacion, incorpora la totalidad de los costos y gastos inherentes al presente credito.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "7.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Las caracteristicas generales del Credito se encuentran previstas en la caratula del presente Contrato, en cumplimiento a lo establecido en las Disposiciones de Caracter General en Materia'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('de Transparencia Aplicables a las Sociedades Financieras de Objeto Multiple; Entidades No Reguladas (las "Disposiciones").'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "8.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Que dará estricto cumplimiento a lo establecido en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (la "LFPDPPP"), y su Reglamento, respecto de la información personal proporcionada por el ACREDITADO, asegurando la confidencialidad de la misma y estableciendo que solamente la utilizará con la finalidad de corroborar la información proporcionada por éste, respecto de su capacidad jurídica y financiera a efectos de contratar los productos y servicios establecidos en el presente Contrato; así como para rendir informes tanto a autoridades administrativas como judiciales que, debidamente justificadas en las disposiciones legales, así lo solicitaren.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "9.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('A la firma del presente Contrato, ha entregado al ACREDITADO un ejemplar en original de este junto con la Carátula y el Anexo A, los cuales forman parte integrante de este Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "10.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Señala como domicilio, para los efectos relacionados con el presente Contrato, el ubicado en Avenida Mayran, No.756, Col. Torreón Jardín, C.P. 27000, Torreón, Coahuila de Zaragoza.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);
            
            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "B.", 0, 0, 'C');
            $pdf->Cell(($mitad - 3), $line_height, 'Declara el ACREDITADO que:', 0, 1, 'J');

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "1.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Tiene plena capacidad para celebrar el presente Contrato y cualesquiera otros documentos que se suscriban a su amparo, por lo que la celebración de estos constituye, según sea el caso, obligaciones válidas y vinculatorias para las Partes y que el ACREDITANTE ha puesto a su disposición el aviso de privacidad a que se refiere la LFPDPPP, mismo que ha consentido y autorizado.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "2.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Autoriza expresamente al ACREDITANTE para que, por conducto de sus funcionarios facultados, lleve a cabo investigaciones sobre su comportamiento crediticio en las sociedades de Información Crediticia que estime conveniente.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "3.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Cuenta con la capacidad económica para hacer frente a las obligaciones contraídas en el presente Contrato y cualquier otro documento que se suscriba a su amparo, lo cual se acredita y refleja mediante la información de negocios y la relación patrimonial entregada al ACREDITANTE, a la fecha de firma de este Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "4.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('La suscripción del presente Contrato, y cualesquiera otros documentos que se suscriban a su amparo, no contravienen ni resultan en conflicto con: (i) cualquier contrato o acto jurídico que tenga celebrado; o (ii) con la legislación, reglamentación o normativa, federal, estatal o municipal vigente de los Estados Unidos Mexicanos que por cualquier causa le pudiese resultar aplicable.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "5.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('No existe ningún contrato, convenio, documento, disposición gubernamental, decreto, sentencia u orden que limite, grave o restrinja el dominio de sus bienes o derechos, ni tiene obligación contingente alguna, salvo los hechos del conocimiento del ACREDITANTE, o que de resultar exigible'), 0, 'J');
            
            $x = ($margen_credito_i);
            $pdf->SetX($x + 3);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('afecte o pudiere tener un efecto adverso en su condición financiera y/o económica que pudiere representar un riesgo en el cumplimiento de las obligaciones que contraiga con el presente Contrato y cualesquiera otros documentos suscritos a su amparo.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "6.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Manifiesta bajo protesta de decir verdad que, los datos proporcionados al ACREDITANTE y los asentados en este Contrato, la Caratula y sus Anexos, son verdaderos, conociendo las repercusiones que se pueden suscitar en su contra, por hacer declaraciones falsas a una entidad financiera; que se dedica a desarrollar una actividad lícita, la cual le permitirá obtener los recursos necesarios para cumplir con todas y cada una de sus obligaciones de pago derivadas del presente Contrato. Manifestando además que, las cantidades que serán recibidas como financiamiento, serán destinadas a fines permitidos por la ley, sin incurrir en alguno de los delitos señalados en los '), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('artículos 139 Quáter y 400 Bis del Código Penal Federal (el "CPF"), mismos que conoce a la letra.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "7.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Antes de la firma del presente Contrato, el ACREDITANTE ha hecho de su conocimiento el contenido del mismo y de todos los documentos que serán suscritos a su amparo, los cargos, comisiones y gastos que serán generados por la celebración del presente Contrato, los descuentos y bonificaciones a los que pueden tener derecho, así como el CAT de financiamiento expresado en términos porcentuales anuales que para fines informativos y de comparación, incorpora la totalidad de los'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(3, $line_height, "8.", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell(($mitad - 3), $line_height, utf8_decode('Manifiesta y hace constar que, a la firma del presente Contrato, ha recibido un ejemplar en original de éste, en conjunto con la Carátula y el Anexo A, los cuales forman parte integrante de este Contrato.'), 0, 'J');

            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode('Conformes las Partes con las declaraciones que anteceden, celebran este Contrato de Apertura de Crédito Simple, en términos de las siguientes:'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->Cell($mitad, $line_height, utf8_decode('CLÁUSULAS'), 0, 1, 'C');
            $pdf->Cell($mitad, $line_height, utf8_decode('CAPÍTULO PRIMERO'), 0, 1, 'C');
            $pdf->Cell($mitad, $line_height, utf8_decode('OBJETIVO, DISPOSICIÓN Y PAGO'), 0, 1, 'C');

            $pdf->SetX($x);
            $pdf->Cell(20, $line_height, "PRIMERA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(26, $line_height, "DEFINICIONES", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(50, $line_height, utf8_decode("Para efectos de este Contrato, o"), 0, 1, 'R');

            $pdf->SetX($x);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell($mitad, $line_height, utf8_decode('cualquier documento suscrito a su amparo, los siguientes términos, ya sea que se encuentren con o sin mayúsculas, con o sin negritas, en plural o singular, tendrán el mismo significado que a continuación se señala, a menos que expresamente se indique lo contrario:'), 0, 'J');

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "1.", 0, 0);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(15, $line_height, "Anexo A:", 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 21), $line_height, utf8_decode('El documento que contienen la transcripción de los'), 0, 1, 'J');
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('preceptos legales contenidos y referidos en el presente Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "2.", 0, 0);
            $pdf->Cell(15, $line_height, utf8_decode("Carátula:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 21), $line_height, utf8_decode('Es el formulario que precede a este Contrato y que'), 0, 1, 'J');
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('forma parte integrante del mismo, la cual contiene la información específica del ACREDITADO y la información del Crédito, es decir, los términos específicos y/o adicionales que regirán durante la vigencia de este Contrato, el que forma parte integrante de este Contrato, para todos los efectos legales conducentes, sujetándose a todas las condiciones de éste, como si a la letra se insertare. En caso de existir un conflicto entre lo previsto por este Contrato y la Caratula, prevalecerá lo previsto en la Caratula.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "3.", 0, 0);
            $pdf->Cell(40, $line_height, utf8_decode("Costo Anual Total (CAT):"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 46), $line_height, utf8_decode('Es el costo anual de financiamiento'), 0, 1, 'J');
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('expresado en términos porcentuales anuales que, para fines informativos y de comparación, incorpora la totalidad de los costos y gastos inherentes al presente crédito, el cual se'), 0, 'J');
            
            $x = ($mitad + $margen_credito_i + 2.5); $y = 22;
            $pdf->SetXY($x, $y);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('encuentra especificado en la Carátula, suscritos al amparo del presente Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "4.", 0, 0);
            $pdf->Cell(8, $line_height, utf8_decode("CCo:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('Código de Comercio.'), 0, 1, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "5.", 0, 0);
            $pdf->Cell(8, $line_height, utf8_decode("CCF:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('Código Civil Federal.'), 0, 1, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "6.", 0, 0);
            $pdf->Cell(8, $line_height, utf8_decode("CPF:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('Código Penal Federal.'), 0, 1, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "7.", 0, 0);
            $pdf->Cell(27, $line_height, utf8_decode("Establecimientos:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 33), $line_height, utf8_decode('Significa cualquiera de los establecimientos'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('físicos de VENTACERO, en los cuales el ACREDITADO podrá hacer uso del crédito para adquirir productos.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "8.", 0, 0);
            $pdf->Cell(50, $line_height, utf8_decode("Fecha de Corte o de Vencimiento:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 56), $line_height, utf8_decode('Día del mes en que termina'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('el periodo de 30 (treinta) días en el que se registran lo movimientos efectuados en la cuenta del ACREDITADO, misma que se indica en la Carátula del presente Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "9.", 0, 0);
            $pdf->Cell(32, $line_height, utf8_decode("Intereses Moratorios:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 38), $line_height, utf8_decode('La cantidad que el ACREDITADO se'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('obliga a pagar al ACREDITANTE, en términos de lo establecido en la Cláusula Séptima de este Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "10.", 0, 0);
            $pdf->Cell(8, $line_height, utf8_decode("IVA:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('El impuesto al Valor Agregado.'), 0, 1, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "11.", 0, 0);
            $pdf->Cell(17, $line_height, utf8_decode("LFPDPPP:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('Ley Federal de Protección de Datos Personales en.'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('Posesión de los Particulares.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "12.", 0, 0);
            $pdf->Cell(17, $line_height, utf8_decode("LGOAAC:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('Ley General de Organizaciones y Actividades.'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('Auxiliares del Crédito.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "13.", 0, 0);
            $pdf->Cell(15, $line_height, utf8_decode("LGTOC:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 21), $line_height, utf8_decode('Ley General de Títulos y Operaciones de Crédito.'), 0, 1, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "14.", 0, 0);
            $pdf->Cell(14, $line_height, utf8_decode("LTOSF:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 20), $line_height, utf8_decode('Ley para la Transparencia y Ordenamiento de los'), 0, 1, 'J');
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->SetX($x);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('Servicios Financieros.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            /* $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y); */
            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "15.", 0, 0);
            $pdf->Cell(35, $line_height, utf8_decode("Pagos por Anticipado:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 41), $line_height, utf8_decode('Las cantidades que el ACREDITADO'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('pagará al ACREDITANTE en los términos de la Cláusula Novena.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);
            
            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "16.", 0, 0);
            $pdf->Cell(25, $line_height, utf8_decode("Tarjeta Plástica:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 31), $line_height, utf8_decode('Medio de disposición del crédito, personal e'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('intransferible, expedida por el ACREDITANTE a nombre del ACREDITADO al amparo del presente Contrato.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);
            
            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "17.", 0, 0);
            $pdf->Cell(8, $line_height, utf8_decode("TPV"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 14), $line_height, utf8_decode('Significa, Terminal Punto de Venta, y serán aquellos'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('dispositivos de uso exclusivo de los Establecimientos para realizar cobros mediante el empleo de las Tarjetas Plásticas.'), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);
            
            $pdf->SetX($x);
            $pdf->Cell(6, $line_height, "18.", 0, 0);
            $pdf->Cell(24, $line_height, utf8_decode("VENTACERO:"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 30), $line_height, utf8_decode('Empresa de comercialización de acero al'), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5.25, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 6), $line_height, utf8_decode('mayoreo, en la cual el ACREDITADO podrá hacer uso del crédito otorgado en el amparo del presente Contrato. El listado de sucursales podrán ser consultadas por el ACREDITADO, a través del sitio'), 0, 'J');
            $pdf->SetXY($x + ($mitad - 40), $pdf->GetY() - $line_height);
            $pdf->SetTextColor(13, 110, 253); //azul
            $pdf->Cell(40, $line_height, 'www.creditoventacero.com', 0, 1, 'www.creditoventacero.com');
            $pdf->SetTextColor(51, 51, 51); //negro
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(20, $line_height, "SEGUNDA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(40, $line_height, utf8_decode("MONTO DEL CRÉDITO"), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 60), $line_height, utf8_decode("En virtud de este Contra-"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell($mitad, $line_height, utf8_decode('to, el ACREDITANTE apertura y otorga al ACREDITADO un crédito en cuenta corriente en moneda nacional hasta por la cantidad establecida en la Caratula y/o el Anexo A, bajo el rubro de "Monto del Crédito" (el "'), 0, 'J');
            $pdf->SetXY($x + 40, $pdf->GetY() - $line_height);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(27, $line_height, utf8_decode("Monto del Crédito"), 0, 1, 'J');
            $pdf->SetXY($x + 68, $pdf->GetY() - $line_height);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(10, $line_height, utf8_decode("\")."), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode('Dentro del límite del Monto del Crédito no quedan comprendidos los intereses, comisiones, ni impuestos que deba pagar el ACREDITADO al ACREDITANTE, en los términos de este Contrato, los cuales serán regulados por separado en los términos pactados, de conformidad con el artículo 292 doscientos noventa y dos de la LGTOC.'), 0, 'J');
            
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "2.1.", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(32, $line_height, utf8_decode("Destino del Crédito."), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 40), $line_height, utf8_decode("El ACREDIRTADO se obliga en todo"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("momento a utilizar el importe de las disposiciones que haga del Monto del Crédito concedido única y exclusivamente para la adquisición de productos y servicios en los Establecimientos de VENTACERO. Queda entendido que, en caso de que el ACREDITADO incumpla con esta obligación, el ACREDITANTE estará facultado para dar por terminado el presente Contrato de forma anticipada."), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);
            
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "2.2.", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(54, $line_height, utf8_decode("Incremento en la Línea de Crédito."), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 62), $line_height, utf8_decode("Trimestralmente el"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE hará un análisis del crédito para que, en caso de que el ACREDITADO mantenga un buen historial de pagos a "), 0, 'J');

            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("criterio del ACREDITANTE, se analice la viabilidad de realizar un incremento de su línea de crédito. La autorización de dicho incremento y el monto a incrementar serán determinados única y exclusivamente por el ACREDITANTE, quien se lo notificará al ACREDITADO que corresponda mediante los medios de comunicación establecidos para ello. Una vez recibida la notificación de incremento, el ACREDITADO podrá aceptarla o rechazarla mediante los medios de comunicación establecidos para ello. En caso de que la acepte, dicho incremento se aplicará de forma inmediata, y el ACREDITADO podrá disponer de los recursos automáticamente."), 0, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);
            
            $pdf->SetX($x);
            $pdf->Cell(20, $line_height, "TERCERA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(71, $line_height, utf8_decode("DISPOSICIÓN DEL MONTO DE CRÉDITO."), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 91), $line_height, utf8_decode("El"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell($mitad, $line_height, utf8_decode('ACREDITADO podrá disponer del Monto del Crédito a través de la Tarjeta Plástica, una vez activada la misma, la cual el ACREDITANTE hará entrega al ACREDITADO a la suscripción del presente Contrato; para lo cual se estará a lo siguiente:'), 0, 'J');

            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "a)", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("En virtud de que el presente Contrato se pacta en cuenta corriente, el ACREDITADO podrá volver a disponer del Monto del Crédito en una o varias disposiciones, hasta por las diferencias que existan entre el importe total del Monto del Crédito y el saldo insoluto del mismo; siempre y cuando el ACREDITADO esté al corriente y en cumplimiento de todos los requisitos y obligaciones que le deriven o le lleguen a derivar de este Contrato y que haya cubierto el saldo total de la o las disposiciones efectuadas en la Fecha de Corte de cada disposición, y en ningún caso, el monto total de las disposiciones podrán exceder el Monto del Crédito."), 0, 'J');

            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "b)", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("La primera disposición podrá realizarse dentro de un plazo de 30 treinta días naturales, contados a partir de la fecha de"), 0, 'J');

            $x = $margen_credito_i;
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("firma del presente Contrato, y siempre y cuando se hayan cumplido los siguientes requisitos:"), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->Cell(8, $line_height, '(i).', 0, 0);
            $pdf->MultiCell(($mitad - 16), $line_height, utf8_decode("Que haya entregado toda la documentación que para la disposición solicite el ACREDITANTE en el presente Contrato;"), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->Cell(8, $line_height, '(ii).', 0, 0);
            $pdf->MultiCell(($mitad - 16), $line_height, utf8_decode("Que el presente Contrato haya quedado debidamente formalizado."), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->Cell(8, $line_height, '(iii).', 0, 0);
            $pdf->MultiCell(($mitad - 16), $line_height, utf8_decode("Que el ACREDITADO haya suscrito y entregado al ACREDITANTE el Pagaré por el monto a ser dispuesto, y con la o las fechas de amortización de la disposición de que se trata."), 0, 'J');

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(18, $line_height, "CUARTA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(20, $line_height, utf8_decode("VIGENCIA."), 0, 0, 'C');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 38), $line_height, utf8_decode("La vigencia del presente Contrato será la"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell($mitad, $line_height, utf8_decode('establecida en la Caratula en el apartado "Plazo del Crédito", pudiendo terminar anticipadamente una vez que el ACREDITADO haya liquidado la totalidad del Monto del Crédito, así como intereses, comisiones, gastos y demás accesorios financieros e impuestos que adeude en favor del ACREDITANTE. Los términos y condiciones establecidos en el presente Contrato, en la Caratula y en el Anexo A continuarán vigentes durante todo el tiempo en el que el ACREDITADO mantenga algún adeudo en favor del ACREDITANTE.'), 0, 'J');

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(18, $line_height, "QUINTA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 18), $line_height, utf8_decode("PROCEDIMIENTO DE DISPOSICIÓN DEL"), 0, 1, 'J');
            $pdf->Cell(41, $line_height, utf8_decode("MONTO DEL CRÉDITO."), 0, 0, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 41), $line_height, utf8_decode("Para hacer uso del Monto del Crédito,"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("el ACREDITADO deberá exhibir la Tarjeta Plástica en los Establecimientos de VENTACERO al momento de adquirir productos, debiendo presentar además de la Tarjeta Plástica, una identificación oficial vigente con fotografía y firma; será responsabilidad del Establecimiento cotejar la firma estampada en la Tarjeta Plástica con la estampada en el comprobante de pago"), 0, 'J');
            
            $x = ($mitad + $margen_credito_i + 2.5); $y = 22;
            $pdf->SetXY($x, $y);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("que se emita mediante el uso de la Terminal Punto de Venta o TPV, al momento de realizar la disposición. El ACREDITADO deberá estampar de manera autógrafa su firma en el comprobante de pago que se imprima mediante el uso de la TPV, el cual documentará la disposición realizada y disminuirá proporcionalmente el Monto del Crédito. Con la firma de cada uno de los comprobantes de pago, el ACREDITADO otorga y confiere a favor del ACREDITANTE, un mandato aplicado a actos de comercio a título gratuito tan amplio como en derecho sea necesario, para que el ACREDITANTE, por cuenta y orden del ACREDITADO, y con cargo al Monto del Crédito y hasta donde el mismo alcance, realice directamente el pago a cada uno de los Establecimientos, respectivamente, por la adquisición o pago de bienes requeridos por el ACREDITADO. El ACREDITADO con la firma de dichos comprobantes de pago se da y dará por recibido a su más entera conformidad de la cantidad dispuesta del Monto del Crédito y extiende a favor del ACREDITANTE el recibo más amplio que conforme a derecho corresponda, por la cantidad efectivamente dispuesta del Monto del Crédito, sin reservarse derecho o acción legal que ejercer en contra del ACREDITANTE, por el cumplimiento del mandato otorgado en el párrafo inmediato anterior, obligándose a sacarla en paz y a salvo. El ACREDITANTE no será responsable por error u omisión derivado de la adquisición o pago de los bienes o servicios solicitados o adquiridos por el ACREDITADO. \n El ACREDITADO NO podrá realizar retiros de efectivo de la Tarjeta Plástica, ya que solo podrá utilizar los recursos del Monto del Crédito en los términos establecidos en el numeral \"2.1\" de la Cláusula Segunda de este Contrato."), 0, 'J');
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(15, $line_height, "SEXTA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(41, $line_height, utf8_decode("COSTO ANUAL TOTAL."), 0, 0, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 38), $line_height, utf8_decode("Es el costo anual de"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("financiamiento expresado en términos porcentuales anuales que, para fines informativos y de comparación, incorpora la totalidad de los costos y gastos inherentes al presente crédito, el cual se encuentra especificado en la Carátula, suscritos al amparo del presente Contrato. El referido Costo Anual Total se calculará utilizando la metodología establecida por el Banco de México, vigente en la fecha del cálculo respectivo."), 0, 'J');
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(19, $line_height, "SEPTIMA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(47, $line_height, utf8_decode("INTERESES MORATORIOS."), 0, 0, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 66), $line_height, utf8_decode("Si el ACREDITADO "), 0, 1, 'J');

            /* $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y); */
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("incurre en mora de cualquier obligación de pago de conformidad con lo establecido en este Contrato o en la Caratula, el ACREDITADO pagará al ACREDITANTE intereses moratorios sobre saldos vencidos a la tasa señalada bajo el rubro \"Tasa Moratoria\" de la Caratula y/o en la Caratula, más el IVA correspondiente. Dichos intereses se causarán desde la fecha en que se actualice el incumplimiento hasta la fecha de regularización de los pagos. Los intereses moratorios se causará: (i) sobre cualquier saldo vencido no pagado oportunamente, (ii) sobre el saldo total de lo adeudado, si se diere por vencido anticipadamente el crédito, y (iii) sobre el importe de cualquier otra obligación patrimonial del ACREDITADO, si no fueran cumplidas en los términos pactados en este Contrato. Lo anterior sin perjuicio de que el ACREDITANTE pueda dar por vencido el adeudo en términos de este Contrato. Los intereses moratorios, en caso de que se cause, junto con los impuestos que generen de acuerdo con las leyes respectivas, deberán pagarse al momento en que se liquide el adeudo. \n En el supuesto en el que el ACREDITADO incurra en incumplimiento de pago de cualquiera de sus obligaciones, la Tarjeta Plástica será bloqueada en automático; una vez que el "), 0, 'J');
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITADO se ponga al corriente en sus pagos, el ACREDITANTE reactivará la Tarjeta Plástica."), 0, 'J');
                
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(18, $line_height, "OCTAVA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(25, $line_height, utf8_decode("COMISIONES."), 0, 0, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 43), $line_height, utf8_decode("El ACREDITADO cubrirá a favor"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("del ACREDITANTE las siguientes comisiones por concepto de: (i) Apertura de Crédito, (ii) Reposición de la Tarjeta Plástica, (iii) Gastos de Cobranza, (iv) Gastos de Administración, y (v) Aclaración Improcedente, mismas que se calculan de la siguiente manera:"), 0, 'J');

            #Traer los datos de comisiones
            // $consulta_comisiones = $con->query("SELECT comision, valor FROM tb_web_va_catcomisiones");
            // $comisiones = $consulta->fetch_assoc();
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "a)", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(30, $line_height, utf8_decode("Apertura de Crédito:"), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 38), $line_height, utf8_decode("El ACREDITANTE cobrará por única"), 0, 1);
            $pdf->SetX($x);
            $pdf->Cell(8, 0, ' ', 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("ocasión, al momento de la celebración del presente Contrato, la cantidad de $ " . number_format($registro[0]['MontoAutorizado'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($registro[0]['MontoAutorizado']))) . " Moneda Nacional) más el Impuesto al Valor Agregado correspondiente;"), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "b)", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(49, $line_height, utf8_decode("Reposición de la Tarjeta Plástica:"), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 57), $line_height, utf8_decode("Por cada reposición de"), 0, 1);
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Tarjeta Plástica que el ACREDITADO solicite, se obliga a pagar al ACREDITANTE la cantidad de $" . number_format($comisiones['Reposicion_Tarjeta'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Reposicion_Tarjeta']))) . " Moneda Nacional)  más el Impuesto al Valor Agregado correspondiente;"), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "c)", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(30, $line_height, utf8_decode("Gastos de Cobranza:"), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 38), $line_height, utf8_decode("En caso de que el ACREDITADO"), 0, 1);
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("incumpla en alguno de los pagos que deba realizar en favor del ACREDITANTE y éste último deba realizar gestiones de cobranza, el ACREDITANTE cobrará la cantidad de $ " . number_format($comisiones['Gastos_de_Cobranza'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Gastos_de_Cobranza']))) . " Moneda Nacional) más el Impuesto al Valor Agregado correspondiente. Dicha comisión será generada por cada evento de incumplimiento en que incurra el ACREDITADO. "), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "d)", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(40, $line_height, utf8_decode("Gastos de Administración:"), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 48), $line_height, utf8_decode("El ACREDITANTE cobrará, por"), 0, 1);
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("única ocasión, la cantidad de $" . number_format($comisiones['Gastos_de_Administracion'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Gastos_de_Administracion']))) . " Moneda Nacional) más el Impuesto al Valor Agregado correspondiente, por las gestiones y gastos que se deriven de la celebración del presente Contrato."), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "e)", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(58, $line_height, utf8_decode("Comisión por Aclaración Improcedente:"), 0, 0);
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->Cell(($mitad - 66), $line_height, utf8_decode("El ACREDITANTE"), 0, 1);
            $pdf->SetX($x);
            $pdf->Cell(8, 0, '', 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("cobrará, por evento de aclaración que resulte improcedente, la cantidad de $" . number_format($comisiones['Comision_por_Aclaracion'], 2, '.', ',') . " MXN (" . ucfirst(strtolower($letras->convertirNumeroEnLetras($comisiones['Comision_por_Aclaracion']))) . " Moneda Nacional) más el Impuesto al Valor Agregado correspondiente."), 0, 'J');

            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El ACREDITANTE se reserva el derecho de cobrar o no las comisiones antes señaladas, así como de establecer un monto inferior al señalado en esta cláusula, para lo cual informará al ACREDITADO dicha situación y lo dejará establecido en la Caratula, bajo los rubros correspondientes. El ACREDITANTE no podrá cobrar comisiones distintas ni importes superiores a los señalados en la presente cláusula, salvo que se suscite lo siguiente: Cuando existan modificaciones a los importes de las comisiones antes descritas, el ACREDITANTE deberá informar al ACREDITADO, con cuando menos 30 treinta días naturales de"), 0, 'J');
            
            // $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("anticipación a la fecha de su aplicación o entrada en vigor, lo cual notificará mediante un aviso incluido en el respectivo Estado de Cuenta o por cualquier otro medio establecido en el presente Contrato, lo que suceda primero, los nuevos importes y conceptos aplicables a las comisiones. El aviso deberá especificar de forma notoria la fecha en que las modificaciones surtirán efectos. En el evento de que el ACREDITADO no esté de acuerdo con las modificaciones propuestas por el ACREDITANTE, podrá solicitar la terminación anticipada del Contrato, debiendo solicitarla dentro de los 30 treinta días naturales siguientes a que reciba la notificación de las modificaciones, sin responsabilidad ni penalidad alguna a su cargo, debiendo pagar, en su caso, los"), 0, 'J');

            $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("adeudos que ya se hubieren generado a la fecha en que solicite dar por terminado anticipadamente el Contrato. Una vez transcurrido el plazo anterior, sin que el ACREDITANTE haya recibido comunicación alguna por parte del ACREDITADO, se tendrán por aceptadas las modificaciones a las comisiones del Contrato."), 0, 'J');
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(18, $line_height, "NOVENA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 18), $line_height, utf8_decode("OBLIGACIONES DE PAGO A CARGO DEL "), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("ACREDITADO."), 0, 1, 'J');
            $pdf->SetFont('Times', 'B', $font_size_credito);

            $pdf->SetX($x);
            $pdf->Cell(7, $line_height, "9.1", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(24, $line_height, "Pago de Capital.", 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 31), $line_height, "El ACREDITADO se obliga a cubrir al ", 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE de tiempo en tiempo, sin necesidad de requerimiento previo y a través de los medios de pago indicados en esta cláusula, la cantidad dispuesta del Monto del Crédito a más tardar en las Fechas de Corte. Si la Fecha de Corte es día inhábil, el ACREDITADO realizará el pago el día hábil inmediato anterior. Entendiéndose como día inhábil, los sábados, domingos y días en que las Instituciones de Crédito del país se encuentren obligadas a cerrar sus puertas al público. El ACREDITANTE se reserva la facultad de reestablecer la línea de crédito, por el monto efectivamente pagado, dentro de las 24 veinticuatro horas hábiles siguientes al pago."), 0, 'J');
            $pdf->SetFont('Times', 'B');

            $pdf->SetX($x);
            $pdf->Cell(7, $line_height, "9.2", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(31, $line_height, "Pago de Comisiones.", 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 38), $line_height, "El ACREDITADO se obliga a cubrir al ", 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE las comisiones, en las fechas establecidas en la Cláusula Octava anterior."), 0, 'J');
            $pdf->SetFont('Times', 'B');

            $pdf->SetX($x);
            $pdf->Cell(7, $line_height, "9.3", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(35, $line_height, "Lugar y Forma de Pago.", 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 42), $line_height, utf8_decode("El ACREDITADO cubrirá a favor del") , 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE todas las cantidades que el ACREDITADO deba pagar por concepto de capital, comisiones e intereses, así como de cualquier otra erogación, mediante transferencia electrónica de fondos a la cuenta bancaria del ACREDITANTE establecida en la Caratula, bajo el rubro \"Cuenta Bancaria para el Pago al Acreditante\". El ACREDITANTE le proporcionará al ACREDITADO una referencia, misma que el ACREDITADO deberá registrar al realizar sus pagos, a efectos de que estos puedan ser identificados y aplicados por el ACREDITANTE; en caso de no utilizar la referencia asignada, en este acto el ACREDITADO libera de toda responsabilidad al ACREDITANTE en caso de que no le sea posible identificar el pago y, en consecuencia, no pueda aplicarlo al pago del crédito, motivo por el cual será responsabilidad del ACREDITADO acreditar dicho pago ante el ACREDITANTE. Los pagos serán validados y podrán aplicarse según los términos y tiempos del banco receptor. \n El ACREDITANTE se reserva el derecho de cambiar la cuenta bancaria de pago para lo cual notificará por escrito, vía fax o cualquier medio electrónico al ACREDITADO sobre dicho cambio, con cuando menos 10 diez días naturales de anticipación. \n Por ningún motivo, el ACREDITADO podrá utilizar su Tarjeta Plástica como una tarjeta de débito, por lo que los depósitos que intente realizar a la Tarjeta Plástica serán asignados directamente a la cuenta del ACREDITANTE y serán tomados a cuenta como pagos anticipados. \n En caso de que el ACREDITADO realice un pago menor al pago correspondiente y llegada la Fecha de Corte este no se haya completado, se tendrá por no realizado en tiempo y forma, por lo que deberá cubrir los intereses moratorios más el IVA correspondiente que se lleguen a generar, en términos de la Cláusula Séptima de este Contrato."), 0, 'J');
            $pdf->SetFont('Times', 'B');

            $pdf->SetX($x);
            $pdf->Cell(7, $line_height, "9.4", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(52, $line_height, utf8_decode("Prelación de Aplicaciones de Pagos."), 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 59), $line_height, utf8_decode("El ACREDITANTE") , 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("aplicará las cantidades que reciba en pago por orden de vencimiento, conforme al orden siguiente: gastos hechos por el ACREDITANTE por cuenta del ACREDITADO, comisiones,"), 0, 'J');
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("impuestos, intereses moratorios y, por último, suerte principal del Monto del Crédito. Para el caso de que el ACREDITANTE hubiere tenido que demandar al ACREDITADO por incumplimiento, los pagos que realice se aplicarán en primer lugar a los gatos y costas del juicio, y después se seguirá el orden estipulado en la presente cláusula."), 0, 'J');
            $pdf->SetFont('Times', 'B');

            $pdf->SetX($x);
            $pdf->Cell(7, $line_height, "9.5", 0, 0);
            $pdf->SetFont('Times', 'U');
            $pdf->Cell(25, $line_height, utf8_decode("Pago Anticipado."), 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 59), $line_height, utf8_decode("El ACREDITADO podrá solicitar al") , 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE la aceptación de pagos del presente crédito en forma anticipada, total o parcialmente. Para tal efecto, deberá cubrir al ACREDITANTE amortizaciones completas. Todo pago anticipado se aplicará a reducir el saldo insoluto del crédito en el orden inverso al de su vencimiento, esto es, se aplicarán a los últimos pagos. \n Todo pago anticipado se aplicará a reducir el saldo insoluto del crédito de los pagos periódicos inmediatos siguientes. En tal caso, los pagos solo serán aceptados si el ACREDITADO se encuentra al corriente en las amortizaciones de capital y accesorios a su cargo, en cuyo caso, los pagos anticipados o adelantados, según sea el caso, los pagos anticipados o adelantados, según sea el caso, se aplicarán al saldo insoluto del crédito."), 0, 'J');
            $pdf->SetFont('Times', 'B');

            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode('CÁPITULO SEGUNDO'), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode('DE LA TARJETA PLÁSTICA'), 0, 1, 'C');

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(18, $line_height, "DECIMA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 18), $line_height, utf8_decode("EXPEDICIÓN DE TARJETAS PLÁSTICAS."), 0, 1, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("Mediante el empleo de la Tarjeta Plástica, el ACREDITADO podrá realizar compras y/o pagos única y exclusivamente en los Establecimientos de VENTACERO, a través de TPV que se tenga para tal efecto, hasta por las cantidades que tenga disponibles del Monto del Crédito. \n El ACREDITADO acepta y reconoce que él será el único y exclusivo responsable del empleo de la Tarjeta Plástica, así como será responsable del cumplimiento de los términos y condiciones de uso de la misma. El ACREDITADO expresamente reconoce y acepta que la Tarjeta Plástica es de uso personal e intransferible, por lo que libera al ACREDITANTE de cualquier responsabilidad derivada de un uso indebido que se le dé. \n La Tarjeta Plástica será entregada al ACREDITADO con un Número de Identificación Personal Inicial (en lo sucesivo el \"NIP INICIAL\") previamente asignado, el cual es del desconocimiento del ACREDITANTE, por lo que será obligación del ACREDITADO, a su discreción y entera responsabilidad, modificar el mismo (en lo sucesivo el \"NIP CONFIDENCIAL\"). El NIP INICIAL será entregado de forma confidencial en conjunto con la Tarjeta Plástica, ambos contenidos en un sobre sellado. El ACREDITADO reconoce y acepta que el NIP CONFIDENCIAL, o cualquier otro número confidencial y/o contraseña que sea generado de manera secreta por el ACREDITADO, equivale a su firma electrónica y será uno de los medios de autentificación que deberá utilizar para identificarse en el momento en que realice disposiciones en del Monto del Crédito. La Tarjeta Plástica tendrá una vigencia definida, la cual se indicará en el frente de la misma y permanecerá activa durante dicha vigencia."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(35, $line_height, "DECIMA PRIMERA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 35), $line_height, utf8_decode("ENTREGA Y ACTIVACIÓN DE LA"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(18, $line_height, utf8_decode("TARJETA."), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 18), $line_height, utf8_decode("El ACREDITANTE hará entrega de la Tarjeta Plástica"), 0, 1, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("al ACREDITADO al momento de suscripción del presente Contrato, por lo que el presente fungirá como acuse de recibido de la Tarjeta Plástica a su más entera conformidad. \nLa Tarjeta Plástica se entregará al ACREDITADO desactivada, debiendo comunicarse al número telefónico que se indique en el"), 0);
            
            $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y);
            // $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("documento entregado anexo a la tarjeta para su activación. Una vez activada, el ACREDITADO podrá hacer uso de ella y disponer libremente del Monto del Crédito. \nEl ACREDITADO acepta y reconoce que entregada la Tarjeta"), 0);

            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("Plástica en los términos antes mencionados, ha leído el presente Contrato y está de acuerdo con los términos y condiciones establecidos en el mismo, asumiendo todas y cada una de las obligaciones consignadas en éste, sujetándose al cumplimiento de las mismas. \nEl ACREDITANTE se reserva el derecho de sustituir la Tarjeta Plástica cuando así lo considere conveniente, en cuyo caso notificará al ACREDITADO sobre la necesidad de llevar a cabo la sustitución de la Tarjeta Plástica, debiendo hacer entrega de una nueva, a fin de que el ACREDITADO pueda continuar disponiendo el Monto del Crédito, dicha sustitución no generará ningún cargo adicional al ACREDITADO. La Tarjeta Plástica sustituida será inhabilitada de manera inmediata una vez entregada la nueva Tarjeta Plástica al ACREDITADO por lo que quedará bajo responsabilidad del ACREDITADO el trato que le dé a esta. Lo anterior, en el entendido que la sustitución de la Tarjeta Plástica no afectará los derechos y obligaciones derivados del presente Contrato. La nueva Tarjeta Plástica no podrá ser utilizada hasta en tanto no sea inhabilitada la tarjeta anterior. \nEl ACREDITANTE no será responsable del uso indebido que el ACREDITADO o un tercero, con o sin el consentimiento del ACREDITADO, den a la Tarjeta Plástica, por lo que, en todo momento, el ACREDITADO deberá mantener bajo su custodia y resguardo la Tarjeta Plástica, así como sus números o claves confidenciales."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(35, $line_height, "DECIMA SEGUNDA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 35), $line_height, utf8_decode("ACEPTACIÓN DE LAS TARJETAS"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(($mitad - 8), $line_height, utf8_decode("Y FUNCIONALIDAD EN LOS ESTABLECIMIENTOS."), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 18), $line_height, utf8_decode("El"), 0, 1, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITADO solo podrá efectuar el pago de bienes y servicios en los Establecimientos. El ACREDITANTE no asume responsabilidad alguna en el caso de que algún Establecimiento se rehúse a admitir la Tarjeta Plástica y/o exija el cumplimiento de requisitos especiales."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(35, $line_height, "DECIMA TERCERA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(45, $line_height, utf8_decode("DE LOS COMPROBANTES."), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 80), $line_height, utf8_decode("Los pagos"), 0, 1, 'J');
            $pdf->SetFont('Times', '', $font_size_credito);
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("que el ACREDITADO realice mediante el empleo de la Tarjeta Plástica, se registrarán y documentarán mediante la suscripción de los comprobantes de pago impresos por las TPV correspondientes y firmados por el ACREDITADO. Los comprobantes de pago firmados por el ACREDITADO se suscribirán a la orden del ACREDITANTE, debiendo firmarse por duplicado y quedando un tanto original en poder del Establecimiento y otro en poder del ACREDITADO. \nEl ACREDITADO deberá resguardar los comprobantes de pago firmados por cada pago efectuado para poder realizar cualquier solicitud, queja, inconformidad o aclaración respecto de cargos realizados de manera incorrecta o por cargos no existentes a consideración de éste."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(34, $line_height, "DECIMA CUARTA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(38, $line_height, utf8_decode("ESTABLECIMIENTOS."), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El ACREDITANTE es ajeno a las relaciones mercantiles o civiles existentes o que surjan entre el ACREDITADO y los Establecimientos. El ACREDITANTE no asumirá responsabilidad alguna por la calidad, cantidad, precio, garantías, plazo de entrega o cualesquiera otras características de los bienes o servicios que se adquieran en los Establecimientos mediante el uso de la Tarjeta Plástica. Consecuentemente, cualquier derecho que llegare a asistir al ACREDITADO por los conceptos citados,"), 0);
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("deberá hacerse valer directamente en contra del Establecimiento. \nEn caso de bonificaciones o ajustes de precios, devolución de mercancías o cancelación de servicios, los Establecimientos no podrán hacer entrega de dinero en efectivo al ACREDITADO. En tal virtud, el abono que en su caso proceda efectuar al Monto del Crédito de la cuenta respectiva, deberá ser solicitado por el Establecimiento que corresponda, en la inteligencia de que, en tanto esa solicitud no se produzca y opere, el ACREDITADO deberá pagar al ACREDITANTE el importe de la cuota de pago correspondiente."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(34, $line_height, "DECIMA QUINTA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 34), $line_height, utf8_decode("ROBO, EXTRAVÍO, DAÑO O"), 0, 1, 'J');
            $pdf->Cell(57, $line_height, utf8_decode("DETERIORO DE LAS TARJETAS."), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 57), $line_height, utf8_decode("El ACREDITADO deberá"), 0, 1, 'J');

            $pdf->MultiCell($mitad, $line_height, utf8_decode("tomar las medidas necesarias para evitar que terceros sin autorización hagan uso de la Tarjeta Plástica. Además, estará obligado a dar aviso inmediato al ACREDITANTE en caso de robo o extravío, debiendo llamar al número de teléfono 800 044 0156 o al correo soporte@creditoventacero.com. El ACREDITANTE proporcionará al ACREDITADO una clave o número de referencia del aviso (en lo sucesivo el \""), 0);
            $pdf->SetXY($x + 72, $pdf->GetY() - $line_height);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(($mitad - 72), $line_height, utf8_decode("Folio de Aclar-"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, utf8_decode("ación"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 8), $line_height, utf8_decode("\"), con fecha y hora que identifiquen o confirmen el"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("reporte dado por el ACREDITADO, la cual servirá para aclaraciones futuras; desde el momento en que el ACREDITANTE otorgue el Folio de Aclaración, cesará la responsabilidad del ACREDITADO respecto al uso que terceros den a la Tarjeta Plástica. El ACREDITANTE bloqueará el uso de la Tarjeta Plástica a partir del momento en que se genere el Folio de Aclaración, para lo cual, el ACREDITANTE generará e informará al ACREDITADO del Folio de Bloqueo de la Tarjeta Plástica. Mediante el Folio de Bloqueo de la Tarjeta Plástica se hará constar el bloqueo de la misma, así como la fecha y hora en que se dio dicho bloqueo; el bloqueo deberá de efectuarse de forma inmediata y a la par del Folio de Aclaración; el ACREDITADO no será responsable de cargos que se efectúen al Monto del Crédito con posterioridad a que se haya levantado el Folio de Aclaración. \nEl ACREDITANTE informará al ACREDITADO el alcance de su responsabilidad en caso de robo o extravío, por transacciones efectuadas antes del aviso, incluyendo el número telefónico para realizar los avisos. \nDicha información podrá ser compartida a través del correo electrónico que el ACREDITADO proporcione y/o consultada en las oficinas del ACREDITANTE. \nEn virtud del bloqueo de la Tarjeta Plástica, el ACREDITADO solo será responsable de los cargos y disposiciones realizadas previa a la notificación y bloqueo de la Tarjeta Plástica. \nEn caso de reclamación, el ACREDITADO estará obligado a proporcionar la información, documentos y realizar las gestiones que el ACREDITANTE le solicite para efecto de verificar la improcedencia del o los cargos. Asimismo, el ACREDITADO, previo al pago de la comisión por Reposición de la Tarjeta Plástica, podrá solicitar la reposición, remplazo y/o sustitución de la misma, al teléfono 800 044 0156 o al correo soporte@creditoventacero.com o acudiendo a las oficinas del ACREDITANTE; el pago de la comisión antes referida deberá ser efectuado por el ACREDITADO en la cuenta que el ACREDITANTE para ello le indique. Dicha comisión se generará por evento de reposición, remplazo y/o sustitución que sea solicitado por el ACREDITADO."), 0);
            
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(29, $line_height, "DECIMA SEXTA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 29), $line_height, utf8_decode("BLOQUEO DE LA TARJETA PLÁSTICA."), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            
            $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y);

            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE podrá bloquear el uso de las Tarjeta Plástica de"), 0, 'J');

            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("manera temporal o permanente, para lo cual se generará un folio de bloqueo, en los casos siguientes:"), 0, 'J');
            
            $pdf->SetX($x);
            $pdf->Cell(5, $line_height, '*', 0, 0);
            $pdf->Cell(($mitad - 8), $line_height, utf8_decode("Por robo o extravío."), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5, $line_height, '*', 0, 0);
            $pdf->Cell($mitad, $line_height, utf8_decode("Por presentar saldo vencido."), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5, $line_height, '*', 0, 0);
            $pdf->MultiCell(($mitad - 5), $line_height, utf8_decode("Por posible uso indebido o fraudulento identificado por el ACREDITANTE a través de sus sistemas de monitoreo, ya sea por robo, extravío o por compras no autorizadas por el ACREDITADO y/o ACREDITANTE."), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5, $line_height, '*', 0, 0);
            $pdf->MultiCell(($mitad - 5), $line_height, utf8_decode("Por cualquier otra causa que a criterio de ACREDITANTE afecte la operación correcta de la Tarjeta Plástica."), 0, 'J');
            $pdf->SetX($x);
            $pdf->Cell(5, $line_height, '*', 0, 0);
            $pdf->Cell($mitad, $line_height, utf8_decode("Por terminación del presente Contrato."), 0, 1, 'J');

            $pdf->SetFont('Times', 'B');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("CAPÍTULO TERCERO"), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("DE LOS ESTADOS DE CUENTA, ACLARACIONES,"), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("RECLAMACIONES, INCONFORMIDADES Y QUEJAS EN "), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("GENERAL."), 0, 1, 'C');
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(34, $line_height, "DECIMA SEPTIMA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(40, $line_height, utf8_decode("ESTADOS DE CUENTA"), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El ACREDITADO podrá conocer su saldo, así como los   cargos y pagos realizados, y en su caso, abonos efectuados por el ACREDITANTE por motivo de aclaraciones procedentes, en los Estados de Cuenta que para ello se generen, los cuales serán"), 0);

            // $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("enviados o proporcionados al ACREDITADO de forma quincenal o mensual, según lo pacten las Partes y se establezca en la Caratula bajo el rubro \"Periodicidad Pactada para el envío de Estados de Cuenta\", ya sea mediante el envío de éste al domicilio del ACREDITADO o a través de los medios electrónicos que las Partes acuerden y se señalen en la Carátula. No obstante, el ACREDITADO podrá solicitar expresamente y por escrito en cualquier momento al ACREDITANTE que el envío del Estado de Cuenta se lleve a cabo por cualquier otro medio diferente a los establecidos en la presente cláusula o a su domicilio, y/o mediante envío al(los) correo(s) electrónico(s) que ha(n) sido proporcionado(s) por el ACREDITADO. \nEl ACREDITANTE pondrá a disposición del ACREDITADO, en sus oficinas y a través de los medios acordados por las Partes, el Estado de Cuenta correspondiente, a más tardar a los siguientes 5 cinco días naturales siguientes a la fecha de pago correspondiente."), 0);

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(33, $line_height, "DECIMA OCTAVA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 33), $line_height, utf8_decode("PROCEDIMIENTO DE ACLARACIO-,"), 0, 1, 'J');$pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("NES,RECLAMACIONES, INCONFORMIDADES Y QUEJAS"), 0, 1, 'J');$pdf->SetX($x);
            $pdf->Cell(25, $line_height, utf8_decode("EN GENERAL."), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 25), $line_height, utf8_decode("El ACREDITADO tendrá un plazo de 90 noventa "), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("días naturales, contados a partir de la fecha en que el ACREDITANTE haya puesto a su disposición el Estado de Cuenta correspondiente, para objetar o solicitar aclaraciones sobre importe que desconozca, debiendo sujetarse a lo establecido en la Cláusula Vigésima Quinta del presente Contrato. El ACREDITADO reconoce que el ACREDITANTE no es ni será responsable por los cargos realizados de manera incorrecta o indebida o que no coincidan con los precios que se ofrecieron por los Establecimientos o se hayan pactado con el ACREDITADO, así como por los bienes o servicios adquiridos, cuando el error, negligencia u omisión sea responsabilidad de los Establecimientos o sus empleados, en cuyo caso, el ACREDITADO deberá contactar de manera directa al Establecimiento donde realizó la compra para solicitar la aclaración del cargo realizado. \nEn caso de que el ACREDITADO tenga alguna aclaración, reclamación, inconformidad o queja respecto de los movimientos o cargos que aparezcan en su Estado de Cuenta, así como por cualquier cuestión relacionada con el presente Contrato, podrá presentar su solicitud de aclaración, reclamación, inconformidad o"), 0);
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("queja por escrito en las oficinas del ACREDITANTE, vía telefónica o por correo electrónico, o a través de la Unidad Especializada, conforme a lo siguiente:"), 0);
            
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "1.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Dentro del plazo de 90 noventa días naturales contados a partir de la fecha de corte establecida en el Estado de Cuenta o la fecha de la realización de la operación o motivo de la aclaración."), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "2.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Una vez transcurrido el plazo señalado en el presente inciso sin que el ACREDITADO solicite la aclaración de los cargos realizados, estará obligado a pagar la totalidad de los mismos."), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "3.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE se reserva el derecho de solicitar al ACREDITADO toda la información y documentación que considere necesaria para comprobar la veracidad de la solicitud de aclaración, reclamación, inconformidad o queja;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "4.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE acusará de recibido dicha solicitud y proporcionará al ACREDITADO un número o folio de aclaración, con la hora y fecha de recepción de la misma;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "5.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Tratándose de cantidades a cargo del ACREDITADO, éste tendrá el derecho de no realizar el pago cuya aclaración solicita, así como el de cualquier otra cantidad relacionada con dicho pago, hasta en tanto se resuelva la aclaración conforme al presente procedimiento y de conformidad con el procedimiento a que refiere el artículo 23 veintitrés de Ley para la Transparencia y Ordenamiento de los Servicios Financieros;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "6.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE llevará a cabo los procesos de investigación que considere necesarios para resolver la solicitud realizada por el ACREDITADO;"), 0);
            
            $pdf->Cell(8, $line_height, "7.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE, en un plazo máximo de 45 cuarenta y cinco días, deberá entregar al ACREDITADO el dictamen correspondiente, anexando copia simple del documento y evidencia considerada para la emisión de dicho dictamen, así como un informe detallado en el que se resuelvan todos los hechos contenidos en la solicitud presentada por el ACREDITADO;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "8.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El dictamen e informe antes mencionados se formularán por escrito y/o por medios electrónicos y serán suscritos por el funcionario facultado por el ACREDITANTE para tal efecto;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "9.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("La respuesta respectiva quedará a disposición del ACREDITADO en el domicilio de la Unidad Especializada del ACREDITANTE, o bien, en las oficinas del ACREDITANTE, por lo que el ACREDITADO quedará obligado a acudir a dicho domicilio a partir del cuadragésimo quinto día natural siguiente contado a partir de que haya presentado el escrito correspondiente;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "10.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Hasta en tanto la solicitud de aclaración de que se trate no quede resuelta de conformidad con el procedimiento señalado en esta cláusula, el ACREDITANTE no podrá reportar como vencidas las cantidades sujetas a dicha aclaración a las Sociedades de Información Crediticia;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "11.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE incluirá los cargos en cuestión en los Estados de Cuenta con una leyenda que indique que se encuentran sujetos a un proceso de aclaración; "), 0);
            
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("En el evento de que, conforme al dictamen que emita el ACREDITANTE resulte o se concluya que la solicitud del ACREDITADO es: (i) IMPROCEDENTE, el ACREDITANTE cobrará al ACREDITADO el cargo cuya aclaración haya"), 0);
            
            $x = ($mitad + $margen_credito_i + 2.5); $y = 22;
            $pdf->SetXY($x, $y);
            
            $pdf->MultiCell($mitad, $line_height, utf8_decode("solicitado, incluyendo los intereses moratorios que en su caso se hubieren generado, más el cargo de la comisión por Aclaración Improcedente; cantidad que se sumara al pago parcial siguiente que el ACREDITADO deba realizar en la fecha de pago establecida, o (ii) PROCEDENTE, el ACREDITANTE eliminará o corregirá el cargo objeto de la solicitud a más tardar a los 10 diez días hábiles siguientes a la fecha en que se concluya el proceso de investigación. \nEl procedimiento antes descrito es sin perjuicio del derecho del ACREDITADO de acudir ante la CONDUSEF o ante la autoridad jurisdiccional correspondiente. Sin embargo, el procedimiento quedará sin efectos a partir de que el ACREDITADO presente su demanda ante la autoridad jurisdiccional o conduzca su reclamación en términos de la legislación aplicable."), 0);
            
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("CAPÍTULO CUARTO"), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("DE LA CESIÓN, TERMINACIÓN, DENUNCIA Y "), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("CANCELACIÓN DEL CONTRATO"), 0, 1, 'C');

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(34, $line_height, "DECIMA NOVENA.", 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(16, $line_height, utf8_decode("CESIÓN"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 53), $line_height, utf8_decode("El ACREDITADO no podrá"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("transferir, traspasar o de cualquier otra forma transmitir sus derechos y obligaciones conforme al presente Contrato o la Tarjeta Plástica sin la previa autorización, por escrito del ACREDITANTE. \nEl ACREDITANTE estará facultada para ceder, transferir, traspasar, descontar, vender o de cualquier otra forma transmitir total o parcialmente sus derechos y/u obligaciones de conformidad con el presente Contrato y cualquier otro documento relacionado con el mismo, sin el consentimiento del ACREDITADO. \nAsimismo, por medio del presente, el ACREDITADO acuerda expresamente con el ACREDITANTE que, en caso de que la legislación aplicable requiera de la notificación al ACREDITADO en el supuesto que se realice una cesión, transferencia, traspaso o cualquier transmisión por parte del ACREDITANTE de sus derechos y/u obligaciones de conformidad con el presente Contrato o cualquier otro documento relacionado con el mismo, dicha notificación podrá realizarse a elección del ACREDITANTE, mediante cualesquiera de los siguientes"), 0);
            
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("medios: (i) mediante entrega de una notificación por escrito suscrita por cualquier funcionario autorizado del ACREDITANTE en el domicilio señalado por el ACREDITADO; (ii) mediante la inclusión de la información relativa a dicha cesión, transferencia, traspaso, venta o transmisión en los estados de cuenta físicos o electrónicos que el ACREDITANTE ponga a disposición del ACREDITADO; (iii) mediante aviso efectuado a través de cualquier medio electrónico como correo electrónico que las Partes de común acuerdo establezcan; o (iv) cualquier otro medio que las Partes de común acuerdo y por escrito establezcan."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(20, $line_height, utf8_decode("VIGÉCIMA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(52, $line_height, utf8_decode("TERMINACIÓN ANTICIPADA"), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El ACREDITADO podrá solicitar en todo momento la terminación anticipada del presente Contrato, bastando para ello la presentación de una solicitud (i) por escrito en las oficinas del ACREDITANTE o ante la Unidad Especializada, debiendo recabar y presentar la información que le sea solicitada por el ACREDITANTE o (ii) por vía telefónica llamando al 800 044 0156 o al correo soporte@creditoventacero.com, siempre y cuando el ACREDITADO no presente ningún adeudo al amparo del presente Contrato. \nEl ACREDITANTE proporcionará al ACREDITADO un número de folio de cancelación. El ACREDITANTE se cerciorará de la"), 0);
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("autenticidad y veracidad de la identidad del ACREDITADO, confirmando los datos personalmente vía telefónica, o a través de cualquier otro medio tecnológico, para lo cual:"), 0);
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(8, $line_height, utf8_decode("a)"), 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE cancelará la Tarjeta Plástica en la fecha de presentación de la solicitud de terminación anticipada por parte del ACREDITADO, debiendo este hacer entrega de la Tarjeta Plástica o manifestar por escrito y bajo protesta de decir verdad, que fue destruida o que no cuenta con ella."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(8, $line_height, utf8_decode("b)"), 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE rechazará cualquier pago que se pretenda efectuar mediante uso la Tarjeta Plástica con posterioridad a la solicitud de cancelación. En consecuencia, no se podrán hacer cargos adicionales al Monto del Crédito a partir del momento en que se realice la cancelación de ésta, excepto los ya generados."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(8, $line_height, utf8_decode("c)"), 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE se abstendrá de condicionar la terminación del Contrato, a la devolución de la Tarjeta Plástica."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(8, $line_height, utf8_decode("d)"), 0, 0);
            $pdf->SetFont('Times', '');
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("DE LA OPERACIÓN: El presente Contrato se dará por terminado el día hábil siguiente al de la presentación de la solicitud por parte del ACREDITADO, o el mismo día hábil en que el ACREDITADO haya realizado la llamada al 800 044 0156 o al correo soporte@creditoventacero.com para solicitar la cancelación del Monto del Crédito y/o de este Contrato, para lo cual, el ACREDITANTE cancelará la Tarjeta Plástica correspondiente. En caso de que existan adeudos pendientes por cubrir por parte del ACREDITADO, el ACREDITANTE, a más tardar el día hábil siguiente a aquel en que haya recibido la solicitud de terminación, comunicará al ACREDITADO el importe de los adeudos que subsistan, debiendo, dentro de los 5 cinco días hábiles siguientes, poner a disposición del ACREDITADO el Estado de Cuenta correspondiente; una vez liquidados los adeudos, se dará por terminado el Contrato y ACREDITANTE procederá a la cancelación inmediata de la Tarjeta Plástica. Liquidados los adeudos, el ACREDITANTE informará al ACREDITADO, mediante el Estado de Cuenta que pondrá a su disposición en sus oficinas, la terminación de la relación contractual y la inexistencia de adeudos derivados exclusivamente de dicha relación."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(39, $line_height, utf8_decode("VIGÉCIMA PRIMERA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(50, $line_height, utf8_decode("RESTRICCIÓN Y DENUNCIA"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(50, $line_height, utf8_decode("  El"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("ACREDITANTE, en términos del artículo 294 doscientos noventa y cuatro de la Ley General de Títulos y Operaciones de Crédito, el cual se encuentra transcrito en el Anexo A del presente Contrato, formando parte integrante del mismo, podrá en cualquier tiempo"), 0);
            
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("restringir el importe del Monto del Crédito o el plazo para disponer de éste, o ambos a la vez, o para denunciar este Contrato en cualquier tiempo, mediante simple aviso por escrito dado al ACREDITADO. \nEn caso de denuncia de este Contrato, el Monto del Crédito se extinguirá en la parte en que el ACREDITADO no hubiere dispuesto, se dará por vencido anticipadamente el plazo pactado y el ACREDITADO deberá pagar a ACREDITANTE de inmediato el importe de las sumas de que haya dispuesto más las que le adeude por cualquier otro concepto. \nEn tanto el ACREDITADO no pague al ACREDITANTE el total de los adeudos, el Contrato no se dará por terminado. En caso de terminación del presente Contrato, cualquiera que sea la causa que la motive, la Tarjeta Plástica será cancelada de manera inmediata."), 0);
            
            $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y);
            // $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("En el caso que el ACREDITADO de por terminado el presente Contrato, el ACREDITANTE, en caso de existir, entregará el saldo a favor en la fecha en que se dé por terminado el presente instrumento, y en el caso en que el ACREDITADO no acuda a la oficina del ACREDITANTE, éste le deberá indicará la forma en la que le será devuelto el saldo a favor. Lo contemplado en este párrafo será aplicable siempre que alguna de las Partes solicite la terminación anticipada del presente Contrato."), 0);

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell(39, $line_height, utf8_decode("VIGÉCIMA SEGUNDA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 39), $line_height, utf8_decode("CAUSAS DE TERMINACIÓN"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(24, $line_height, utf8_decode("ANTICIPADA"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(50, $line_height, utf8_decode("Serán causas de terminación anticipada del prese-"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("nte Contrato y, en consecuencia, se volverá exigible de inmediato el pago del saldo insoluto que hubiere a cargo del ACREDITADO, si éste hiciera uso indebido de la Tarjeta Plástica o incumpliera cualesquiera de las obligaciones que para ellos deriven de la ley y/o del presente Contrato. El ACREDITANTE se reserva el derecho de cancelar el Monto del Crédito otorgado en términos del presente Contrato, en caso de que así lo considere conveniente. Las Partes están de acuerdo que, cualquiera de los siguientes eventos constituirá una causa de terminación anticipada:"), 0);

            $pdf->SetX($x);
            $pdf->Cell(4, $line_height, "1.", 0, 0);
            $pdf->MultiCell(($mitad - 4), $line_height, utf8_decode("Si el ACREDITADO deja de cumplir oportunamente con uno o más pagos que en virtud de este Contrato se encuentre obligado a pagar;"), 0);
            
            $pdf->SetX($x);
            $pdf->Cell(4, $line_height, "2.", 0, 0);
            $pdf->MultiCell(($mitad - 4), $line_height, utf8_decode("Si el ACREDITADO hace un uso indebido de la Tarjeta Plástica;"), 0);
            
            $pdf->SetX($x);
            $pdf->Cell(4, $line_height, "3.", 0, 0);
            $pdf->MultiCell(($mitad - 4), $line_height, utf8_decode("Si el ACREDITADO deja de cumplir con cualquiera de sus obligaciones contractuales, asumidas conforme al presente Contrato;"), 0);
            
            $pdf->SetX($x);
            $pdf->Cell(4, $line_height, "4.", 0, 0);
            $pdf->MultiCell(($mitad - 4), $line_height, utf8_decode("Si el ACREDITADO incumple con cualquier otra obligación de hacer o no hacer establecida en el presente Contrato; "), 0);
            
            $pdf->SetX($x);
            $pdf->Cell(4, $line_height, "5.", 0, 0);
            $pdf->MultiCell(($mitad - 4), $line_height, utf8_decode("En los demás casos en que, conforme a la ley o este Contrato, sea exigible anticipadamente el cumplimiento de la obligación a plazo."), 0);
            
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("En caso de que tenga lugar una causa de terminación anticipada, el ACREDITANTE podrá, sin necesidad de declaración judicial, y mediante previa notificación por escrito al ACREDITADO: (i) dar por vencido anticipadamente este Contrato sin responsabilidad a su cargo, exigiendo al ACREDITADO el pago anticipado de todas las cantidades que le adeude a la fecha de la resolución de la terminación del presente Contrato, por conceptos, de manera enunciativa más no limitativa, de principal, intereses moratorios, comisiones y cualquier accesorio derivado del presente Contrato, así como los impuestos que se generen, en el entendido que el ACREDITADO se obliga para y con el ACREDITANTE de cualquier proceso, demanda o acción, judicial o administrativa, penal o civil, que se presente o pudiere presentarse en su contra por cualquier autoridad y/o tercero, ya sea por cualquier daño ocasionado y/o responsabilidad derivada de dichas acciones, y a indemnizar y/o rembolsar al ACREDITANTE, por todos los gastos y/o costas erogados por ésta última con motivo de las referidas acciones.\nPor su parte, el ACREDITADO podrá solicitar por escrito libre presentado en las oficinas del ACREDITANTE o ante la Unidad"), 0);
            
            
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("de Especializada, y dentro de un plazo que no podrá exceder de 10 (diez) días hábiles posteriores a la celebración del presente Contrato, la cancelación del mismo, sin responsabilidad o penalidad alguna a su cargo, siempre y cuando no hubiese dispuesto del Monto del Crédito otorgado al amparo del presente Contrato, sin que se genere ningún costo adicional por ello,"), 0);
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("procediendo a dar por cancelado el presente Contrato y bloquear de manera inmediata la Tarjeta Plástica.\nUna vez concluido el plazo a que se refiere el párrafo anterior, las Partes convienen en que en el supuesto de que el ACREDITADO solicite la terminación anticipada de este Contrato, se deberá sujetar a lo siguiente:"), 0);

            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "1)", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITADO presentará un escrito ante las oficinas del ACREDITANTE o ante la Unidad Especializada, mediante el cual solicite la terminación anticipada del Contrato y el bloqueo de la Tarjeta Plástica;"), 0);
            $pdf->Cell(8, $line_height, "2)", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("El ACREDITANTE deberá proporcionar al ACREDITADO un folio de cancelación; y, "), 0);
            $pdf->Cell(8, $line_height, "3)", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Previo a la terminación anticipada del Contrato y el bloqueo de la Tarjeta Plástica, el ACREDITANTE deberá confirmar vía telefónica o por cualquier otro medio pactado conforme al presente Contrato, los datos del ACREDITADO."), 0);

            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("En caso de que el ACREDITADO llegase a solicitar la terminación anticipada del presente Contrato, deberá pagar al ACREDITANTE todos los adeudos que se hubiesen generado a su cargo, y hasta la fecha efectiva de terminación del Contrato. Liquidados todos los adeudos que puedan llegar a existir por parte del ACREDITADO, el ACREDITANTE deberá de reportar a la Sociedad de Información Crediticia que la cuenta ha sido cerrada sin adeudo alguno, en los tiempos establecidos en la Ley para Regular las Sociedades de Información Crediticia."), 0);
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B', $font_size_credito);
            $pdf->Cell($mitad, $line_height, utf8_decode("CAPÍTULO QUINTO"), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("DE LAS MODIFICACIONES, AVISOS E INFORMACIÓN"), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->Cell($mitad, $line_height, utf8_decode("GENERAL."), 0, 1, 'C');

            $pdf->SetX($x);
            $pdf->Cell(39, $line_height, utf8_decode("VIGÉCIMA TERCERA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(34, $line_height, utf8_decode("MODIFICACIONES"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 73), $line_height, utf8_decode("Las Partes co-"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("nvienen expresamente que, en caso de que el ACREDITADO quisiera realizar alguna modificación al presente Contrato, deberá presentar un escrito en las oficinas del ACREDITANTE, debiendo especificar en dicho escrito los cambios que desee realizar al Contrato, el ACREDITANTE a su vez tendrá un plazo de 5 cinco días naturales para contestar si acepta las modificaciones solicitadas por el ACREDITADO, y en su caso si se requiere adecuar las condiciones financieras previamente pactadas en la Caratula.\nEn caso de que el ACREDITANTE requiera de modificar las cláusulas del presente Contrato, deberá notificar e informar al ACREDITADO mediante aviso incluido en el Estado de Cuenta o vía electrónica, a través del correo electrónico proporcionado para ello por el ACREDITADO, según lo acuerden las Partes, las modificaciones que se pretenden realizar, con cuando menos 30 treinta días naturales de anticipación a la entrada en vigor de dichas modificaciones.\nEn el evento de que el ACREDITADO no esté de acuerdo con las modificaciones propuestas por ACREDITANTE, éste podrá solicitar la terminación anticipada del Contrato, debiendo solicitarlo dentro de los 30 treinta días naturales posteriores a la entrada en vigor de dichas modificaciones, sin responsabilidad ni comisión alguna a su cargo, debiendo pagar, en su caso, los adeudos que ya se hubieren generado. Lo anterior con excepción de las modificaciones realizadas a los estados de cuenta.\nUna vez transcurrido el plazo conforme a lo señalado en el párrafo anterior, sin que ACREDITANTE haya recibido comunicación alguna por parte del ACREDITADO, se tendrá por otorgado su consentimiento y aceptadas las modificaciones al Contrato. Las Partes convienen expresamente en que ninguno podrá hacer"), 0);
            
            $x = ($mitad + $margen_credito_i) + 2.5; $y = 22;
            $pdf->SetXY($x, $y);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("modificaciones y/o adiciones a los términos y condiciones pactados en el Contrato, sin el previo consentimiento de la otra Parte. Las Partes están de acuerdo que, en el supuesto de que las comisiones establecidas en el presente Contrato se llegasen a incrementar o se establecieran nuevas comisiones, el ACREDITANTE deberá informar al ACREDITADO con por lo menos con 30 treinta días naturales previos a la fecha en que las modificaciones a las comisiones surtan efectos, en términos de lo establecido en el artículo 7º séptimo de la LTOSF. Asimismo, las Partes están de acuerdo en que las comisiones y tasas pactadas en el presente Contrato solo podrán ser modificadas en caso de reestructura y/o con el previo consentimiento del ACREDITADO.\nLos avisos de modificaciones deben indicar:"), 0);

            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "I.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Denominación social y logotipo de ACREDITANTE;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "II.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Nombre del producto o servicio;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "III.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Domicilio y teléfono de contacto de ACREDITANTE, así como domicilio, teléfono y correo electrónico de la UNE;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "IV.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Resumen de todas las modificaciones realizadas y en caso de Comisiones y tasas de interés, mostrando anteriores y nuevas;"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "V.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Fecha a partir de la cual entran en vigor; y"), 0);
            $pdf->SetX($x);
            $pdf->Cell(8, $line_height, "VI.", 0, 0);
            $pdf->MultiCell(($mitad - 8), $line_height, utf8_decode("Derecho del Usuario para dar por terminado el presente Contrato."), 0);

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(36, $line_height, utf8_decode("VIGÉCIMA CUARTA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(42, $line_height, utf8_decode("DOMICILIOS Y AVISOS"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 81), $line_height, utf8_decode("     Para los"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("efectos de este Contrato, las Partes señalan como sus domicilios los referidos en el apartado de declaraciones de este Contrato y en la Caratula, en el apartado de \"Datos del Acreditado\". Los avisos y notificaciones que deban hacerse de conformidad con este Contrato o cualquiera de los documentos suscritos a su amparo, se harán por escrito y entregarán o enviarán a cada una de las Partes que corresponda y según sea necesario, a su dirección o a cualquier otra dirección que designen mediante aviso por escrito dado a su contraparte o a través de los medios electrónicos pactados. Los avisos y comunicaciones surtirán efecto, si se dan por escrito, al ser entregadas, y si se envían por correo electrónico, al ser recibida la confirmación por dicho medio de que dicha comunicación fue recibida.\nEl ACREDITANTE se reserva el derecho de requerir información adicional al ACREDITADO en las notificaciones o escritos que éste le presente, ya sea por escrito o a través de medios tecnológicos, a fin de verificar su identidad."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(36, $line_height, utf8_decode("VIGÉCIMA QUINTA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 36), $line_height, utf8_decode("INFORMACIÓN PARA EL ACRED-"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->Cell(15, $line_height, utf8_decode("ITADO"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 15), $line_height, utf8_decode("Todas las solicitudes, aclaraciones,  inconformidades  y"), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("quejas, u otras relacionados con el presente Contrato, deberán ser presentadas por escrito y debidamente firmadas por el ACREDITADO, debiendo indicar su nombre así como el número de Tarjeta Plástica, una breve narración de los hechos, así como lo que solicite, dicho escrito deberá acompañar copia de su credencial de elector o identificación oficial, así como una copia de los documentos que se relacionen con la solicitud, aclaración, reclamación, inconformidad o queja correspondiente. La documentación antes señalada, podrá ser remitida en imagen digital al correo electrónico de la Unidad Especializada.\nLas solicitudes, aclaraciones, inconformidades y quejas, relacionados con el Monto del Crédito objeto del presente Contrato que formule el ACREDITADO, deberán ser presentados (i) ante la Unidad Especializada del ACREDITANTE o bien (ii) en las oficinas de ACREDITANTE, en días y horas hábiles, en un horario de 9:00 a 14:00 Y de 16:00 a 19:00 horas, de Lunes a"), 0);
            
            $x = $margen_credito_i;
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("Viernes en donde el ACREDITADO dará seguimiento a sus trámite."), 0, 'J');

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(35, $line_height, utf8_decode("VIGÉCIMA SEXTA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(45, $line_height, utf8_decode("UNIDAD ESPECIALIZADA"), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El  domicilio  de la Unidad Especializada se encuentra en: Avenida Mayran , No.756 Col. Torreon Jardin,  C.P. 27000, Torreón, Coahuila de Zaragoza, con correo electrónico: une@convivefinanciera.com, y página de internet www.convivefinanciera.com, o en el teléfono de atención a usuarios es: 800 044 0156 o al correo soporte@creditoventacero.com."), 0);

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(39, $line_height, utf8_decode("VIGÉCIMA SÉPTIMA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(22, $line_height, utf8_decode("CONDUSEF"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 61), $line_height, utf8_decode("En caso de dudas, que-"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("jas o reclamaciones, el ACREDITADO podrá acudir a la Comisión Nacional para la Protección y Defensa de los Usuarios de Servicios Financieros, con domicilio en Insurgentes Sur 762, Colonia Del Valle, Delegación Benito Juárez, Código Postal 03100, en la Ciudad de México, teléfono 55 5340 0999, correo electrónico asesoria@condusef.gob.mx o consultar la página electrónica en Internet  www.condusef.gob.mx."), 0);

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(38, $line_height, utf8_decode("VIGÉCIMA OCTAVA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(42, $line_height, utf8_decode("AVISO DE PRIVACIDAD"), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El ACREDITANTE informa al ACREDITADO que los datos obtenidos en virtud de la celebración del presente Contrato serán tratados de manera confidencial y se encuentran protegidos en los términos del Aviso de Privacidad del ACREDITANTE, el cual está a disposición del ACREDITADO en las oficinas   del   ACREDITANTE   o   a   través   del   Portal   de   Internet: www.convivefinanciera.com.\nPor su parte, el ACREDITADO reconoce tener pleno conocimiento del texto íntegro del Aviso de Privacidad a que alude el párrafo anterior otorga su consentimiento y/o autorización expresa para que el ACREDITANTE recabe datos de carácter sensible, datos personales u otros que sean utilizados para el envío de publicidad y los datos sean compartidos únicamente para los propósitos y fines previstos en dicho Aviso de Privacidad."), 0);

            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(38, $line_height, utf8_decode("VIGÉCIMA NOVENA."), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(25, $line_height, utf8_decode("INTEGRIDAD"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 63), $line_height, utf8_decode("Las Partes  aceptan  y"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("reconocen que el presente clausulado, la Caratula y los Anexos, así como cualquier otro documento que haya sido suscrito al amparo del presente Contrato, son parte integrante del mismo Contrato."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(22, $line_height, utf8_decode("TRIGÉSIMA"), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(47, $line_height, utf8_decode("ENTREGA DE CONTRATO"), 0, 1, 'J');
            $pdf->SetFont('Times', '');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("El ACREDITANTE entregará al ACREDITADO, en la fecha de firma del preste Contrato, una copia original del mismo, así como una copia de la Carátula y el Anexo A, así como copias simples de todos aquellos otros documentos que hayan sido suscritos por las Partes."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(40, $line_height, utf8_decode("TRIGÉSIMA PRIMERA"), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 40), $line_height, utf8_decode("TÍTULOS DE LAS CLÁUSULAS Y "), 0, 1, 'J');
            $pdf->Cell(70, $line_height, utf8_decode("REFERENCIAS A PRECEPTOS LEGALES"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 70), $line_height, utf8_decode("Los títulos que se"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("han incluido en cada cláusula son tan sólo para referencia y fácil manejo, por lo que no deberán tener ninguna trascendencia en la interpretación de las mismas. Asimismo, la transcripción de los preceptos legales referidos en el presente Contrato se encuentra en el Anexo A del presente Contrato."), 0);
            
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(40, $line_height, utf8_decode("TRIGÉSIMA SEGUNDA"), 0, 0);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(($mitad - 40), $line_height, utf8_decode("LEYES Y JURISDICCIÓN APLI-"), 0, 1, 'J');
            $pdf->Cell(12, $line_height, utf8_decode("CABLE"), 0, 0, 'J');
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad - 12), $line_height, utf8_decode("Este Contrato, así como cualquier documento suscrito a su"), 0, 1, 'J');
            $pdf->SetX($x);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("amparo, se interpretará de conformidad con las disposiciones aplicables de la LGTOC, así como de la LGOAAC, el CCo, los usos mercantiles, y de manera supletoria, el CCF y demás leyes mercantiles aplicables en ese orden. En caso de conflicto respecto de su interpretación, cumplimiento y ejecución, las Partes se someten por su libre voluntad expresa e incondicionalmente a la"), 0);
            
            $x = ($mitad + $margen_credito_i + 2.5); $y = 22;
            $pdf->SetXY($x, $y);
            $pdf->MultiCell($mitad, $line_height, utf8_decode("jurisdicción de los Tribunales competentes en la ciudad de Torreón, Coahuila, renunciando a cualquier otro fuero que, en razón de domicilio, presente o futuro, pudiere corresponderles.\nEn caso de que el ACREDITANTE ejerza las acciones en contra del ACREDITADO, para el cumplimiento de las obligaciones de pago, así como la resolución de este Contrato, la Caratula y su Anexo A, será suficiente que el ACREDITANTE exhiba como documentos base de la acción, original o copia certificada de este Contrato con su Caratula y el estado de cuenta certificado por Contador Público seleccionado por el ACREDITANTE."), 0);

            $pdf->SetFont('Times', 'B');
            $pdf->Ln(5);
            #firmas a dos columnas
            $mitad_col2 = $mitad / 2;
            $y_col2 = $pdf->GetY();
            
            // Columna 1
            $pdf->SetX($x);
            $pdf->Cell(($mitad / 2), $line_height, "EL ACREDITANTE", 0, 0, 'C');
            $pdf->Ln(10);
            $pdf->Cell(($mitad / 2), $line_height, '', 0, 1, 'C');
            $pdf->Image("../../img/FirmaJuanito.png", $x + 8, $pdf->GetY() - 16, null, 25, 'PNG');
            $pdf->Ln(4.1);
            $pdf->SetX($x);
            $pdf->Cell(($mitad / 2), $line_height, str_repeat('_', 25), 0, 1, 'C');
            $pdf->SetXY($x, $pdf->GetY());
            $pdf->MultiCell(($mitad / 2), $line_height, utf8_decode("CONVIVE FINANCIERA, S.A. DE C.V., SOFOM, E.N.R."), 0, 'C');
            $pdf->SetX($x);
            $pdf->SetFont('Times', '');
            $pdf->Cell(($mitad / 2), $line_height, utf8_decode("Representado por"), 0, 1, 'C');
            $pdf->SetX($x);
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(($mitad / 2), $line_height, utf8_decode("JUAN RAMIREZ CISNEROS"), 0, 1, 'C');

            // Columna 2
            $pdf->SetXY($x + $mitad_col2, $y_col2);
            $pdf->Cell(($mitad / 2), $line_height, "EL ACREDITADO", 0, 1, 'C');
            $pdf->Ln(14.1);
            $pdf->SetX($x + ($mitad / 2));
            $pdf->Cell(($mitad / 2), $line_height, str_repeat('_', 25), 0, 1, 'C');
            $pdf->SetXY($x + ($mitad / 2), $pdf->GetY());
            if (!$persona_fisica) {
                $pdf->MultiCell(($mitad / 2), $line_height, utf8_decode($registro[0]['RazonSocial']), 0, 'C');
                $pdf->SetX($x + ($mitad / 2));
                $pdf->SetFont('Times', '');
                $pdf->Cell(($mitad / 2), $line_height, utf8_decode("Representado por"), 0, 1, 'C');
                $pdf->SetFont('Times', 'B');
            }
            $pdf->SetX($x + ($mitad / 2));
            $pdf->MultiCell(($mitad / 2), $line_height, utf8_decode($registro[0]['Nombres'] . ' ' . $registro[0]['ApellidoP'] . ' ' . $registro[0]['ApellidoM']), 0, 'C');
            // $pdf->SetX($x);
            // $pdf->Cell(($mitad / 2), $line_height, utf8_decode("CONVIVE FINANCIERA, S.A. DE C.V., SOFOM, E.N.R."), 0, 0, 'C');
            // $pdf->MultiCell(($mitad / 2), $line_height, utf8_decode($registro[0]['Nombres'] . ' ' . $registro[0]['ApellidoP'] . ' ' . $registro[0]['ApellidoM']), 0, 'C');

        # --

        $margen_i_anexo = $margen_d_anexo = 25;
        $margen_t_anexo = 15;
        $total_w_wo_m_anexo = 166;
        $font_size_anexo = 10;
        $line_height_anexo = 4;
        $pdf->SetMargins($margen_i_anexo, $margen_d_anexo, $margen_t_anexo);
        $pdf->SetAutoPageBreak(true, $margen_t_anexo);
        $pdf->AddPage('P', 'Letter');
        # ANEXO 1 (La copie, falta ponerle la decodificacion a utf8, ponerle el addPage, ponerle hacer que funcione y el output para visualizarlo)
            $pdf->SetFont('Times', 'B', $font_size_anexo);
            $pdf->SetTextColor(51, 51, 51);
            
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("ANEXO A"), 0, 1, 'C');
            $pdf->SetFont('Times', 'U');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode('"PRECEPTOS LEGALES"'), 0, 1, 'C');
            $pdf->SetFont('Times', 'B', $font_size_anexo);
            $pdf->Ln(2.8);

            $months = [ 1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE' ];
            $createDate = date_create(substr($registro[0]['FechaAlta'], 0, 10));
            $mes = "\\" . implode('\\', str_split($months[(int)date_format($createDate, 'm')]));
            $today = date_format($createDate, 'd \D\E ' . $mes . ' \D\E\L Y');
            // $today = date("d") . " DE " . $months[(int)date('m')] . " DEL " . date('Y');
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('ANEXO A DEL CONTRATO DE APERTURA DE CRÉDITO EN CUENTA CORRIENTE CELEBRANDO EL DÍA ' . $today . ', ENTRE: (I) LA SOCIEDAD DENOMINADA "CONVIVE FINANCIERA, S.A. DE C.V., S.O.F.O.M, E.N.R.", REPRESENTADA EN ESTE ACTO POR JUAN RAMÍREZ CISNEROS (EN LO SUCESIVO EL "'), 0, 'J');
            
            $pdf->SetXY($pdf->GetX() + ($total_w_wo_m_anexo - 79), $pdf->GetY() - $line_height);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(28, $line_height_anexo, utf8_decode("ACREDITANTE"), 0, 0, 'J');
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(14, $line_height_anexo, utf8_decode("\"), Y (II) LA PERSONA CUYA"), 0, 1, 'J');
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode("INFORMACIÓN SE ENCUENTRA SEÑALADA EN LA CARÁTULA DEL PRESENTE CONTRATO CON EL CARÁCTER DE ACREDITADO (EN LO SUCESIVO EL \""));
            $pdf->SetXY($pdf->GetX() + ($total_w_wo_m_anexo - 60), $pdf->GetY() - $line_height);
            $pdf->SetFont('Times', 'BU');
            $pdf->Cell(26, $line_height_anexo, utf8_decode("ACREDITADO"), 0, 0, 'J');
            $pdf->SetFont('Times', 'B');
            $pdf->Cell(8, $line_height_anexo, utf8_decode("\");"), 0, 1, 'J');
            $pdf->Ln(2.8);
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("A. LEY GENERAL DE ORGANIZACIONES Y ACTIVIDADES AUXILIARES DEL CRÉDITO:"), 0, 1, '');
            $pdf->Ln(2.8);
            
            $pdf->SetFont('Times', '', $font_size_anexo);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 56.- La inspección y vigilancia de las organizaciones auxiliares del crédito, casas de cambio y sociedades financieras de objeto múltiple reguladas queda confiada a la Comisión Nacional Bancaria y de Valores, la que tendrá, en lo que no se oponga a esta Ley, respecto de dichas organizaciones auxiliares del crédito, casas de cambio y sociedades financieras de objeto múltiple reguladas, todas las facultades que en materia de inspección y vigilancia le confiere la Ley de Instituciones de Crédito para instituciones de banca múltiple, quien la llevará a cabo sujetándose a lo previsto en su ley, en el Reglamento respectivo y en las demás disposiciones que resulten aplicables.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('En lo que respecta a las sociedades financieras de objeto múltiple no reguladas, los centros cambiarios y los transmisores de dinero, la inspección y vigilancia de estas sociedades, se llevará a cabo por la mencionada Comisión, exclusivamente para verificar el cumplimiento de los preceptos a que se refiere el artículo 95 Bis de esta Ley y las disposiciones de carácter general que de éste deriven.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Las organizaciones auxiliares del crédito y casas de cambio deberán rendir a la Secretaría de Hacienda y Crédito Público y a la Comisión Nacional Bancaria y de Valores, en la forma y términos que al efecto establezcan, los informes, documentos y pruebas que sobre su organización, operaciones, contabilidad, inversiones o patrimonio les soliciten para fines de regulación, supervisión, control, inspección, vigilancia, estadística y demás funciones que, conforme a esta Ley u otras disposiciones legales y administrativas, les corresponda ejercer."'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 87-J.- En los contratos de arrendamiento financiero, factoraje financiero y crédito, así como en las demás actividades que la ley expresamente les faculte, que celebren las sociedades financieras de objeto múltiple, éstas deberán señalar expresamente que, para su constitución y operación con tal carácter, no requieren de autorización de la Secretaría de Hacienda y Crédito Público, y, en el caso de las sociedades financieras de objeto múltiple no reguladas, deberán en adición a lo anterior, señalar expresamente que están sujetas a la supervisión de la Comisión Nacional Bancaria y de Valores, únicamente para efectos de lo dispuesto por el artículo 56 de esta Ley. Igual mención deberá señalarse en cualquier tipo de información que, para fines de promoción de sus operaciones y servicios, utilicen las sociedades financieras de objeto múltiple no reguladas.".'), 0, 'J');
            
            $pdf->Ln(2.8);
            $pdf->SetFont('Times', 'B', $font_size_anexo);
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("B. CÓDIGO PENAL FEDERAL."), 0, 1, '');
            $pdf->Ln(2.8);
            
            $pdf->SetFont('Times', '', $font_size_anexo);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 139 Quáter.- Se impondrá la misma pena señalada en el artículo 139 de este Código, sin perjuicio de las penas que corresponden por los demás delitos que resulten, al que por cualquier medio que fuere ya sea directa o indirectamente, aporte o recaude fondos económicos o recursos de cualquier naturaleza, con conocimiento de que serán destinados para financiar o apoyar actividades de individuos u organizaciones terroristas, o para ser utilizados, o pretendan ser utilizados, directa o indirectamente, total o parcialmente, para la comisión, en territorio nacional o en el extranjero, de cualquiera de los delitos previstos en los ordenamientos legales siguientes:'), 0, 'J');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("I. Del Código Penal Federal, los siguientes:"), 0, 1, '');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("1) Terrorismo, previstos en los artículos 139, 139 Bis y 139 Ter;"), 0, 1, '');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("2) Sabotaje, previsto en el artículo 140;"), 0, 1, '');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("3) Terrorismo Internacional, previsto en los artículos 148 Bis, 148 Ter y 148 Quáter;"), 0, 1, '');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("4) Ataques a las vías de comunicación, previstos en los artículos 167, fracción IX, y 170,"), 0, 1, '');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("párrafos primero, segundo y tercero, y"), 0, 1, '');
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("5) Robo, previsto en el artículo 368 Quinquies."), 0, 1, '');
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('II. De la Ley que Declara Reservas Mineras los Yacimientos de Uranio, Torio y las demás Substancias de las cuales se obtengan Isótopos Hendibles que puedan producir Energía Nuclear, los previstos en los artículos 10 y 13."'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 400 Bis. Se impondrá de cinco a quince años de prisión y de mil a cinco mil días multa al que, por sí o por interpósita persona realice cualquiera de las siguientes conductas::'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('I. Adquiera, enajene, administre, custodie, posea, cambie, convierta, deposite, retire, dé o reciba por cualquier motivo, invierta, traspase, transporte o transfiera, dentro del territorio nacional, de éste hacia el extranjero o a la inversa, recursos, derechos o bienes de cualquier naturaleza, cuando tenga conocimiento de que proceden o representan el producto de una actividad ilícita, o'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('II. Oculte, encubra o pretenda ocultar o encubrir la naturaleza, origen, ubicación, destino, movimiento, propiedad o titularidad de recursos, derechos o bienes, cuando tenga conocimiento de que proceden o representan el producto de una actividad ilícita.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Para efectos de este Capítulo, se entenderá que son producto de una actividad ilícita, los recursos, derechos o bienes de cualquier naturaleza, cuando existan indicios fundados o certeza de que provienen directa o indirectamente, o representan las ganancias derivadas de la comisión de algún delito y no pueda acreditarse su legítima procedencia.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('En caso de conductas previstas en este Capítulo, en las que se utilicen servicios de instituciones que integran el sistema financiero, para proceder penalmente se requerirá la denuncia previa de la Secretaría de Hacienda y Crédito Público.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Cuando la Secretaría de Hacienda y Crédito Público, en ejercicio de sus facultades de fiscalización, encuentre elementos que permitan presumir la comisión de alguno de los delitos referidos en este Capítulo, deberá ejercer respecto de los mismos las facultades de comprobación que le confieren las leyes y denunciar los hechos que probablemente puedan constituir dichos ilícitos.".'), 0, 'J');
            
            $pdf->Ln(2.8);
            $pdf->SetFont('Times', 'B', $font_size_anexo);
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("C. LEY PARA LA TRANSPARENCIA Y ORDENAMIENTO DE LOS SERVICIOS FINANCIEROS"), 0, 1, '');
            $pdf->Ln(2.8);
            
            $pdf->SetFont('Times', '', $font_size_anexo);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 7. Las Entidades deberán contar en sus sucursales o establecimientos con información actualizada relativa a los montos, conceptos y periodicidad de las Comisiones en carteles, listas y folletos visibles de forma ostensible, y permitir que aquélla se obtenga a través de un medio electrónico ubicado en dichas sucursales o establecimientos, a fin de que cualquier persona que la solicite esté en posibilidad de consultarla gratuitamente, y cuando cuenten con página electrónica en la red mundial "Internet", mantener en ésta dicha información. La Comisión Nacional para la Protección y Defensa de los Usuarios de Servicios Financieros, mediante disposiciones de carácter general, especificará lineamientos estandarizados para que la información sea accesible a los clientes.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Las Entidades, a través de los medios que pacten con sus Clientes, deberán darles a conocer los incrementos al importe de las Comisiones, así como las nuevas Comisiones que pretendan cobrar, por lo menos, con treinta días naturales de anticipación a la fecha prevista para que éstas surtan efectos. Sin perjuicio de lo anterior, los Clientes en los términos que establezcan los contratos, tendrán derecho a dar por terminada la prestación de los servicios que les otorguen las Entidades en caso de no estar de acuerdo con los nuevos montos, sin que la Entidad pueda cobrarle cantidad adicional alguna por este hecho, con excepción de los adeudos que ya se hubieren generado a la fecha en que el Cliente solicite dar por terminado el servicio.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('El incumplimiento a lo previsto en el párrafo anterior, tendrá como consecuencia la nulidad de la Comisión, con independencia de las sanciones que en su caso procedan.".'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 11 Bis 1. Los Clientes contarán con un período de gracia de diez días hábiles posteriores a la firma de un contrato de adhesión que documenten operaciones masivas establecidas por las disposiciones de carácter general a que se refiere el artículo 11 de la presente Ley, con excepción de los créditos con garantía hipotecaria, para cancelarlo, en cuyo caso, las Entidades no podrán cobrar Comisión alguna, regresando las cosas al estado en el que se encontraban antes de su firma, sin responsabilidad alguna para el Cliente. Lo anterior, siempre y cuando el Cliente no haya utilizado u operado los productos o servicios financieros contratados.".'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 23. En todas las operaciones y servicios que las Entidades Financieras celebren por medio de Contratos de Adhesión masivamente celebradas y hasta por los montos máximos que establezca la Comisión Nacional para la Protección y Defensa de los Usuarios de Servicios Financieros en disposiciones de carácter general, aquéllas deberán proporcionarle a sus Clientes la asistencia, acceso y facilidades necesarias para atender las aclaraciones relacionadas con dichas operaciones y servicios.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Al efecto, sin perjuicio de los demás procedimientos y requisitos que impongan otras autoridades financieras facultadas para ello en relación con operaciones materia de su ámbito de competencia, en todo caso se estará a lo siguiente:'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('I. Cuando el Cliente no esté de acuerdo con alguno de los movimientos que aparezcan en el estado de cuenta respectivo o en los medios electrónicos, ópticos o de cualquier otra tecnología que se hubieren pactado, podrá presentar una solicitud de aclaración dentro del plazo de noventa días naturales contados a partir de la fecha de corte o, en su caso, de la realización de la operación o del servicio.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('La solicitud respectiva podrá presentarse ante la sucursal en la que radica la cuenta, o bien, en la unidad especializada de la institución de que se trate, mediante escrito, correo electrónico o cualquier otro medio por el que se pueda comprobar fehacientemente su recepción. En todos los casos, la institución estará obligada a acusar recibo de dicha solicitud.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Tratándose de cantidades a cargo del Cliente dispuestas mediante cualquier mecanismo determinado al efecto por la Comisión Nacional para la Protección y Defensa de los Usuarios de los Servicios Financieros en disposiciones de carácter general, el Cliente tendrá el derecho de no realizar el pago cuya aclaración solicita, así como el de cualquier otra cantidad relacionada con dicho pago, hasta en tanto se resuelva la aclaración conforme al procedimiento a que se refiere este artículo.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('II. Una vez recibida la solicitud de aclaración, la institución tendrá un plazo máximo de cuarenta y cinco días para entregar al Cliente el dictamen correspondiente, anexando copia simple del documento o evidencia considerada para la emisión de dicho dictamen, con base en la información que, conforme a las disposiciones aplicables, deba obrar en su poder, así como un informe detallado en el que se respondan todos los hechos contenidos en la solicitud presentada por el Cliente. En el caso de reclamaciones relativas a operaciones realizadas en el extranjero, el plazo previsto en este párrafo será hasta de ciento ochenta días naturales.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('El dictamen e informe antes referidos deberán formularse por escrito y suscribirse por personal de la institución facultado para ello. En el evento de que, conforme al dictamen que emita la institución, resulte procedente el cobro del monto respectivo, el Cliente deberá hacer el pago de la cantidad a su cargo, incluyendo los intereses ordinarios conforme a lo pactado, sin que proceda el cobro de intereses moratorios y otros accesorios generados por la suspensión del pago realizada en términos de esta disposición.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('III. Dentro del plazo de cuarenta y cinco días naturales contado a partir de la entrega del dictamen a que se refiere la fracción anterior, la institución estará obligada a poner a disposición del Cliente en la sucursal en la que radica la cuenta, o bien, en la unidad especializada de la institución de que se trate, el expediente generado con motivo de la solicitud, así como a integrar en éste, bajo su más estricta responsabilidad, toda la documentación e información que, conforme a las disposiciones aplicables, deba obrar en su poder y que se relacione directamente con la solicitud de aclaración que corresponda y sin incluir datos correspondientes a operaciones relacionadas con terceras personas'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('IV. En caso de que la institucion no diere respuesta oportuna a la solicitud del Cliente o no le entregare el dictamen e informe detallado, asi como la documentacion o evidencia antes referidos, la Comisión Nacional para la Proteccion y Defensa de los Usuarios de los Servicios Financieros, impondra multa en los terminos previstos en la fraccion XI del articulo 43 de esta Ley por un monto equivalente al reclamado por el Cliente en terminos de este articulo, y'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('V. Hasta en tanto la solicitud de aclaración de que se trate no quede resuelta de conformidad con el procedimiento señalado en este artículo, la institución no podrá reportar como vencidas las cantidades sujetas a dicha aclaración a las sociedades de información crediticia.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Lo antes dispuesto es sin perjuicio del derecho de los Clientes de acudir ante la Comisión Nacional para la Protección y Defensa de los Usuarios de Servicios Financieros o ante la autoridad jurisdiccional correspondiente conforme a las disposiciones legales aplicables, así como de las sanciones que deban imponerse a la institución por incumplimiento a lo establecido en el presente artículo. Sin embargo, el procedimiento previsto en este artículo quedará sin efectos a partir de que el Cliente presente su demanda ante autoridad jurisdiccional o conduzca su reclamación en términos y plazos de la Ley de Protección y Defensa al Usuario de Servicios Financieros.".'), 0, 'J');
            
            $pdf->Ln(2.8);
            $pdf->SetFont('Times', 'B', $font_size_anexo);
            $pdf->Cell($total_w_wo_m_anexo, $line_height, utf8_decode("D. LEY GENERAL DE TÍTULOS Y OPERACIONES DE CRÉDITO."), 0, 1, '');
            $pdf->Ln(2.8);
            
            $pdf->SetFont('Times', '', $font_size_anexo);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 292.- Si las partes fijaron límite al importe del crédito, se entenderá, salvo pacto en contrario, que en él quedan comprendidos los intereses, comisiones y gastos que deba cubrir el acreditado.".'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('"Articulo 294.- Aun cuando en el contrato se hayan fijado el importe del crédito y el plazo en que tiene derecho a hacer uso de él el acreditado, pueden las partes convenir en que cualquiera o una sola de ellas estará facultada para restringir el uno o el otro, o ambos a la vez, o para denunciar el contrato a partir de una fecha determinada o en cualquier tiempo, mediante aviso dado a la otra parte en la forma prevista en el contrato, o a falta de ésta, por ante notario o corredor, y en su defecto, por conducto de la primera autoridad política del lugar de su residencia, siendo aplicables al acto respectivo los párrafos tercero y cuarto del artículo 143.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Cuando no se estipule término, se entenderá que cualquiera de las partes puede dar por concluido el contrato en todo tiempo, notificándolo así a la otra como queda dicho respecto del aviso a que se refiere el párrafo anterior.'), 0, 'J');
            $pdf->Ln(2.8);
            $pdf->MultiCell($total_w_wo_m_anexo, $line_height, utf8_decode('Denunciado el contrato o notificada su terminación de acuerdo con lo que antecede, se extinguirá el crédito en la parte de que no hubiere hecho uso el acreditado hasta el momento de esos actos; pero a no ser que otra cosa se estipule, no quedará liberado el acreditado de pagar los premios, comisiones y gastos correspondientes a las sumas de que no hubiere dispuesto, sino cuando la denuncia o la notificación dichas procedan del acreditante.".'), 0, 'J');
        #--

        // $pdf->Output();
        $content = base64_encode($pdf->Output('S'));

        $firmamexServices = new FirmamexServices($webId, $apiKey); #estos datos salen de credenciales firmamex
        #subir documento a Firmamex
        $celular = $registro[0]['Celular'];
        $options = (object) [
            'b64_doc' => (object) [
                'data' => $content,
                'name' => "Convive_contrato_" .  $registro[0]['Celular'],
            ],
            'stickers' => [
                (object) [
                    // 'authority' => 'Vinculada a SMS por Liga',
                    'authority' => 'Vinculada a SMS por Liga',
                    'stickerType' => 'line',
                    'dataType' => 'phone',
                    'data' => '52' . $celular,
                    'imageType' => 'stroke',
                    'page' => 1,
                    'rect' => (object) [
                        'lx' => 385, # x inferior izquierda
                        'ly' => ($persona_fisica ? 236 : 134), # y inferior izquierda
                        'tx' => 510, # x superior derecha
                        'ty' => ($persona_fisica ? 279 : 179), # y superior derecha
                    ],
                ],
                /* 
                    (object) [ a peticion del cliente VentAcero se omiten estas firmas en el pie de pagina de las hojas.
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 1,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 2,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 3,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 4,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 5,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 6,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 7,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 8,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ],
                    (object) [
                        // 'authority' => 'Vinculada a SMS por Liga',
                        'authority' => 'Vinculada a SMS por Liga',
                        'stickerType' => 'line',
                        'dataType' => 'phone',
                        'data' => '528715637622',
                        'imageType' => 'stroke',
                        'page' => 9,
                        'rect' => (object) [
                            'lx' => 30, # x inferior izquierda
                            'ly' => 20, # y inferior izquierda
                            'tx' => 140, # x superior derecha
                            'ty' => 45, # y superior derecha
                        ],
                    ], 
                */
                (object) [
                    // 'authority' => 'Vinculada a SMS por Liga',
                    'authority' => 'Vinculada a SMS por Liga',
                    'stickerType' => 'line',
                    'dataType' => 'phone',
                    'data' => '52' . $celular,
                    'imageType' => 'stroke',
                    'page' => 11,
                    'rect' => (object) [
                        'lx' => 450, # x inferior izquierda
                        'ly' => 540, # y inferior izquierda
                        'tx' => 570, # x superior derecha
                        'ty' => 590, # y superior derecha
                    ],
                ],
            ],
            'workflow' => (object) [
                'language' => 'es',
                /* 
                    'ordered' => [ //esto es el orden de firmado del documento. Es opcional.
                        (object) [ //dato del firmante (debe corresponder a un sticker)
                            // 'data' => 'arciniega1497@hotmail.com' #si es por email
                            'data' => '8715637622'
                        ]
                    ],
                    'remind_every' => '12h', #Por default es cada 1d durante 2 semanas que es lo que dura la expiración del flujo de firmas. Esto es el recordatorio. 
                */
            ],
        ];
        $responseRequest = $firmamexServices->request($options);

        #obtener respuesta de la api firmamex
        $response = json_decode($responseRequest);
        $firmamexId = $response->document_ticket;

        if ($firmamexId) {
            $nombre_contrato = "Contrato_Convive_" . $registro[0]['Celular'];
            $sql_verify = "INSERT INTO tb_web_va_firmamex(firmamexId, ID_Solicitud, TotalFirmas, NombreContrato, FechaAlta, ClienteID, LineaCreditoID) VALUES('$firmamexId', '". $id_solicitud ."', $response->sticker_count, '$nombre_contrato', NOW(), " . $registro[0]['ID_Cliente'] . ", " . $registro[0]['LineaCreditoID'] . ")";
            $consulta_verify = $con->query($sql_verify);
            // var_dump($sql_verify);

            $error = false;
            if (!$consulta_verify) {
                $error = true;
            }
        }

        echo $content;
    } else {
        echo "SinRegistro";
    }

}
?>