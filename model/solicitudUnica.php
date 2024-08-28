<?php

/**
 * Clase para definir el objeto de Solicitud Unica
 */
class SolicitudUnica {
# Conección a la base de datos y nombre de la tabla.

    private $conn;
    private $TB_SolicitudUnicaCT = "TB_SolicitudUnicaCT";
    private $TB_SolicitudUnicaEME = "TB_SolicitudUnicaEME";
    private $TB_SolicitudUnicaRefCT = "TB_SolicitudUnicaRefCT";
    private $TB_RelacionCT = "TB_RelacionCT";
    private $TB_RegistroAppCT = "TB_RegistroAppCT";
    private $TB_OrdenPagoCT = "TB_OrdenPagoCT";
    private $TB_ProspectosCR = "TB_ProspectosCR";
    private $TB_CentroCapCT = "TB_CentroCapCT";
    private $INSTITNOMINA = "INSTITNOMINA";
    private $PRODUCTOSCREDITO = "PRODUCTOSCREDITO";
    private $TB_DocumentsEME = "TB_DocumentsEME";
    private $SOLICITUDCREDITO = "SOLICITUDCREDITO";
    private $clientes = "clientes";
    private $tarjetadebito = "tarjetadebito";
# Propiedades del objeto
    public $action;
    public $limit;
    public $start;
    public $total;
    public $CA_Id;
    public $CB_ClienteID;
    public $CB_SolicitudID;
    public $CB_SolicitudEsta;
    public $CB_CreditoID;
    public $CB_PNombre;
    public $CB_SNombre;
    public $CB_TNombre;
    public $CB_APaterno;
    public $CB_AMaterno;
    public $CB_Celular;
    public $CB_Correo;
    public $CB_ProductoID;
    public $CB_Tasa;
    public $CB_Monto;
    public $CB_Plazo;
    public $CB_ActividadEco;
    public $CB_AntiguedadLab;
    public $CB_Ingreso;
    public $CB_Egreso;
    public $CB_GradoEscolarID;
    public $CB_DependientesEco;
    public $CB_TDC;
    public $CB_NumeroTarjeta;
    public $CB_Hipotecario;
    public $CB_Automotriz;
    public $CB_INEAn;
    public $CB_INERe;
    public $CB_INENum;
    public $CB_INEEmi;
    public $CB_INEVig;
    public $CB_Comprobante;
    public $CB_Genero;
    public $CB_Titulo;
    public $CB_FNacimiento;
    public $CB_CURP;
    public $CB_RFC;
    public $CB_RFCOficial;
    public $CB_LNacimiento;
    public $CB_ENacimiento;
    public $CB_EsMismaDir;
    public $CB_Calle;
    public $CB_Numero;
    public $CB_Numero_Int;
    public $CB_ColoniaID;
    public $CB_ColoniaDes;
    public $CB_CP;
    public $CB_MunicipioID;
    public $CB_MunicipioDes;
    public $CB_EstadoID;
    public $CB_EstadoDes;
    public $CB_FechaAlta;
    public $CB_FechaModi;
    public $CB_Reclutador;
    public $CB_Patrocinador;
    public $CB_Observaciones;
    public $CC_Usuario;
    public $CC_Canal;
    public $CC_Estatus;
    public $CC_CreEsta;
    public $CB_TelefonoAval;
    public $CB_AvalID;
    public $RecNombre;
    public $PatNombre;
    public $CliNombre;
    public $alias;
    public $Anterior;
    public $Siguiente;
    public $Campo;
    public $Valor;
    public $Orden;
    public $OrdenDir;
    public $ValorAnt;
    public $fini;
    public $ffin;
    public $sqlvalor1;
    public $perfil;
    public $centro;
    public $Producto;
    public $Patronum;
    public $EstatusVeri;
    public $AreaVeri;
    public $Consecutivo;
    public $NombresRef;
    public $ApellidosRef;
    public $TelefonoRef;
    public $CB_Frecuencia;
    public $CB_AntiguedadLabM;
    public $CB_InstitNominaID;
    public $NombreInstit;
    public $Domicilio;
    public $ProductoCredito;
    public $CB_TelefonoCasa;
    public $CB_EntreCalle1;
    public $CB_EntreCalle2;
    public $CB_PagoDomicilio;
    public $SolicitudCreditoID;
    public $MontoAutorizado;
    public $CB_NoEmpleado;
    public $TelefonoEmp;
    public $Bandera;

    /** Datos complementarios convive   * */
    public $CK_prospectosID;
    public $CK_type_housing;
    public $CK_year_living;
    public $CK_referred_name;
    public $CK_why_unemployed;
    public $CK_why_need_loan;
    public $CK_job;
    public $CK_company_name;
    public $CK_company_phone;
    public $CK_salary;
    public $CK_address_company;
    public $CK_status_credit_bureau;
    public $CK_proof_payroll;

    /**
     * Constructor con la conección a la base de datos.
     * @param type $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' ";
# Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
# Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar todos los datos de una solicitud por el CA_Id
     */

    function searchSolicitud() {
        #consulta a ejecutar
        $query = "SELECT * "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CA_Id = '" . $this->CA_Id . "' ";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para buscar los registros de la tatbla por el numero de solicitud
     */

    function searchSolitudxID() {
        #consulta a ejecutar
        $query = "SELECT * "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_SolicitudID = '" . $this->CB_SolicitudID . "' ";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para buscar la solicitud por el numero de credito
     */

    function searchSolicitudxCre() {
        #consulta a ejecutar
        $query = "SELECT *"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CreditoID = '" . $this->CB_CreditoID . "' ";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para buscar la solicitud por el numero de celular
     */

    function searchSolicitudxCel() {
        #consulta a ejecutar
        $query = "SELECT *"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->CB_Celular . "' ";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Función para buscar la última solicitud hecha por un cliente mediante el celular
     */

    function searchSolUnixCel() {
        #consulta a ejecutar
        $query = "SELECT *"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->CB_Celular . "' "
                . " ORDER BY CA_Id DESC LIMIT 1";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para buscar la solicitud del aval
     */

    function searchSolUniAval() {
        #creación de la consulta
        $query = "SELECT *"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_AvalID = '" . $this->CB_AvalID . "' ";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function searchProspect() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT *, concat(trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) as Nombre "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE concat(trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " GROUP BY Nombre ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # bind id of product to be updated     
        $stmt->bindParam(':Valor', $this->Valor);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function searchCteByCel() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CA_Id, "
                . " (SELECT Descripcion FROM microfin.PRODUCTOSCREDITO "
                . " WHERE ProducCreditoID = tsu.CB_ProductoID) as Producto,"
                . " (SELECT B.TarjetaDebID FROM microfin.CUENTASAHO A, microfin.TARJETADEBITO B "
                . " WHERE A.TelefonoCelular = '" . $this->Valor . "' AND A.TipoCuentaID = 7 AND B.CuentaAhoID = A.CuentaAhoID) as Tarjeta,"
                . " CB_Celular, CB_Correo, CB_CreditoID, "
                . " concat(trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) as Nombre, "
                . " coalesce((SELECT concat(trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_Celular = (SELECT CB_CteCel FROM TB_ProspectosCR WHERE CB_Celular = '" . $this->Valor . "') "
                . " ORDER BY CA_Id LIMIT 1), '')  as PatNombre "
                . " FROM " . $this->TB_SolicitudUnicaCT . " tsu "
                . " WHERE CB_Celular = '" . $this->Valor . "' "
                . " AND tsu.CB_ProductoID IN(5000, 5002, 50003, 5004) "
                . " ORDER BY CA_Id DESC LIMIT 1 ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();

        $num = $stmt->rowCount();
        # Verifica que encuentre registros
        if ($num > 0) {
            # get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            # set values to object properties
            $this->CA_Id = $row['CA_Id'];
            $this->CB_Celular = $row['CB_Celular'];
            $this->CB_Correo = $row['CB_Correo'];
            $this->CliNombre = $row['Nombre'];
            $this->PatNombre = $row['PatNombre'];
            $this->Producto = $row['Producto'];
            $this->CB_CreditoID = $row['CB_CreditoID'];
            $this->Tarjeta = $row['Tarjeta'];
        } else {
            $this->CA_Id = "0";
            $this->CB_Celular = "";
            $this->CB_Correo = "";
            $this->CliNombre = "";
            $this->PatNombre = "";
            $this->Producto = "";
        }
    }

    /**
     * Función para obtener las pre solicitudes 
     * @return type
     */
    function searchPreSol() {
        //se valida el tipo de estatus
        if ($this->CC_Estatus == 1) {
            $queryWhere = " WHERE (CC_Estatus = 1 OR CC_Estatus = 3) ";
        } else if ($this->CC_Estatus == 5) {
            $queryWhere = " WHERE  (CC_Estatus = 5 OR CC_Estatus = 6 OR CC_Estatus = 7 OR CC_Estatus = 8) ";
        } else {
            $queryWhere = " WHERE CC_Estatus = '" . $this->CC_Estatus . "' ";
        }
        #Constulta para filtrar los registros.
        $query = "SELECT *, "
                . " (concat( trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno))) as Nombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CB_Referencia FROM " . $this->TB_OrdenPagoCT
                . " WHERE CB_Telefono = tsu.CB_Celular ORDER BY CA_Id DESC LIMIT 1), '') as Referencia, "
                . " coalesce((SELECT CB_OrdenID FROM " . $this->TB_OrdenPagoCT
                . " WHERE CB_Telefono = tsu.CB_Celular ORDER BY CA_Id DESC LIMIT 1), '') as OrdenID, "
                . " coalesce((SELECT CB_EstatusPago FROM " . $this->TB_OrdenPagoCT
                . " WHERE CB_Telefono = tsu.CB_Celular ORDER BY CA_Id DESC LIMIT 1), '') as EstatusPago, "
                . " coalesce((SELECT CB_FechaActual FROM " . $this->TB_OrdenPagoCT
                . " WHERE CB_Telefono = tsu.CB_Celular ORDER BY CA_Id DESC LIMIT 1), '') as FechaPago, "
                . " (SELECT coalesce(COUNT(*), 0) FROM " . $this->TB_ProspectosCR
                . " WHERE CB_CteCel = tsu.CB_Celular) as Invitados, "
                . " COALESCE((SELECT CB_CteCel as cc FROM " . $this->TB_ProspectosCR
                . " WHERE CB_Celular = tsu.CB_Celular), 'No Patrocinado') as PatroCel,"
                . " (SELECT COUNT(*) FROM " . $this->TB_SolicitudUnicaCT . " " . $queryWhere
                . " AND CB_ProductoID IN (5000,5002,5003,5004)) as numRegistros,"
                . " (SELECT Estatus FROM microfin.SOLICITUDCREDITO WHERE SolicitudCreditoID = tsu.CB_SolicitudID) as EstatusSolicitud"
                . " FROM  . $this->TB_SolicitudUnicaCT tsu ";

        $query .= $queryWhere . " AND CB_ProductoID IN (5000,5002,5003,5004) ";

        if ((int) $this->perfil == 2) {
            $query .= " AND cast(CB_Reclutador as unsigned) = cast('" . $this->CB_Reclutador . "' as unsigned)  ";
        }

        if ($this->CB_Reclutador == '0035' || $this->CB_Reclutador == '0020') {
            $query .= " AND CB_EstadoID " . $this->sqlvalor1 . " ";
        }

        if (strlen($this->Valor) > 0) {
            $query .= " AND (concat( trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                    . " trim(CB_APaterno),' ',trim(CB_AMaterno)) like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                    . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                    . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                    . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                    . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                    . " OR CA_Id like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                    . " OR CB_Celular like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                    . " OR CB_ClienteID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                    . " OR CB_SolicitudID like '" . $this->Valor . "'  COLLATE 'utf8_bin' "
                    . " OR CB_CreditoID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                    . " OR (coalesce((SELECT CB_Referencia FROM " . $this->TB_OrdenPagoCT
                    . " WHERE CB_Telefono = tsu.CB_Celular AND CB_Correo = tsu.CB_Correo "
                    . " ORDER BY CA_Id DESC LIMIT 1), '')) like '" . $this->Valor . "'  COLLATE 'utf8_bin' "
                    . " OR  (CASE WHEN (SELECT CB_EstatusPago FROM TB_OrdenPagoCT "
                    . " WHERE CB_Telefono = tsu.CB_Celular AND CB_Correo = tsu.CB_Correo "
                    . " ORDER BY CA_Id DESC LIMIT 1) = 'paid' THEN 'PAGADA' "
                    . " WHEN (SELECT CB_EstatusPago FROM TB_OrdenPagoCT "
                    . " WHERE CB_Telefono = tsu.CB_Celular AND CB_Correo = tsu.CB_Correo "
                    . " ORDER BY CA_Id DESC LIMIT 1) = 'expired' THEN 'CANCELADA' "
                    . " ELSE 'PENDIENTE' END) like '" . $this->Valor . "' COLLATE 'utf8_bin') ";
        }

        if (strlen($this->Orden) > 0) {
            $query .= " ORDER BY " . $this->Orden . " " . $this->OrdenDir . " ";
        } else {
            $query .= " ORDER BY CA_Id DESC ";
        }

        $query .= " LIMIT " . $this->start . ", " . $this->limit;

        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        if (strlen($this->Valor) > 0) {
            $this->Valor = str_replace("|", "%", $this->Valor);
            # bind id of product to be updated     
            $stmt->bindParam(":Valor", $this->Valor);
        }
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    function search_filtro() {
        $this->Valor = str_replace("|", "%", $this->Valor);
        #Constulka para filtrar los registros.
        $query = "SELECT *, (SELECT COUNT(CA_Id) FROM " . $this->TB_SolicitudUnicaCT . " tsu1"
                . " WHERE "
                . " (concat( trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu1.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu1.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin')) as total, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioOV, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioCT, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_CorreoE = tsu.CB_Correo AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCorreo, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = tsu.CB_Celular AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCelular, "
                . " coalesce((SELECT COUNT(*) c FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = tsu.CB_Celular "
                . " GROUP BY CB_Celular HAVING c > 1), 0) as ValDup "
                . " FROM  . $this->TB_SolicitudUnicaCT tsu "
                . " WHERE "
                . " (concat( trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CA_Id like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CB_Celular like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR CB_ClienteID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR CB_SolicitudID like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CB_CreditoID like '" . $this->Valor . "' COLLATE 'utf8_bin') "
                . " ORDER BY CA_Id DESC ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

#INICIO DE FUNCIONES DE ANALYTICS Creditu
    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CA_Id not IN "
                . " (SELECT CB_SolicitudID FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID between 2000 and 2002) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID between 2000 and 2002) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID between 2000 and 2002) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID between 2000 and 2002) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDet() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */(SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_ProductoID between 2000 and 2002) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '4')) "
                . " + */(SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID between 2000 and 2002) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID between 2000 and 2002) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID between 2000 and 2002) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */(SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID between 2000 and 2002) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHistoVende() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */(SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " /*AND CB_ProductoID between 2000 and 2002*/ ) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " /* AND CB_ProductoID between 2000 and 2002*/) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " /*AND CB_ProductoID between 2000 and 2002*/) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " /*AND CB_ProductoID between 2000 and 2002*/) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGralCtes45() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE CB_Estatus IN ('V', 'B') AND CB_ProductoCreditoID IN ('2000', '2001') "
                . " AND CB_DiasAtraso <= 45) as Minus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE CB_Estatus IN ('V', 'B') AND CB_ProductoCreditoID IN ('2002') "
                . " AND CB_DiasAtraso <= 45) as RenovMinus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE CB_Estatus IN ('V', 'B') AND  CB_ProductoCreditoID IN ('2000', '2001') "
                . " AND CB_DiasAtraso > 45) as Plus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . "  WHERE CB_Estatus IN ('V', 'B') AND CB_ProductoCreditoID IN ('2002') "
                . "  AND CB_DiasAtraso > 45) as RenovPlus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V', 'B') AND CB_ProductoCreditoID IN ('2000', '2001') ) as Total";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetCtes45() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " SUM(IF((CB_Estatus IN ('V','B') AND CB_ProductoCreditoID IN ('2000','2001') "
                . " 	 AND CB_DiasAtraso <= 45), 1 , 0))  as Minus45, "
                . " SUM(IF((CB_Estatus IN ('V','B') AND CB_ProductoCreditoID IN ('2002') "
                . " 	 AND CB_DiasAtraso <= 45 ), 1 , 0))  as RenovMinus45, "
                . " SUM(IF((CB_Estatus IN ('V','B') AND CB_ProductoCreditoID IN ('2000','2001') "
                . " 	 AND CB_DiasAtraso > 45), 1 , 0))  as Plus45, "
                . " SUM(IF((CB_Estatus IN ('V','B') AND CB_ProductoCreditoID IN ('2002') "
                . " 	 AND CB_DiasAtraso > 45), 1 , 0))  as RenovPlus45 "
                . " FROM TB_CreditosCT A INNER JOIN TB_SolicitudesCT B ON B.CB_CreditoID = A.CB_CreditoID "
                . " WHERE B.CB_EstadoID " . $this->sqlvalor1 . ";";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

#FIN DE FUNCIONES DE ANALYTICS CREDITU
#INICIO DE FUNCIONES DE ANALYTICS SIMSA / FANOS
    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral_Simsa() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CA_Id not IN "
                . " (SELECT CB_SolicitudID FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1100) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1100) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1100) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1100) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }
    
    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral_SimsaDet() {
# Consulta para seleccionar todos los registros.
        /*$query = "SELECT "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1100) as Revision, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1100) as Aprobadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1100) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1100) as NoObjetivos";*/
        $query = " 
            SELECT Empresa, sum(Revision) as Revision, sum(Aprobada) as Aprobadas, sum(Rechazada) as Rechazadas, sum(NoObjetiva) as NoObjetivos  " 
            . " FROM (SELECT   " 
            . " (SELECT NombreInstit FROM microfin.INSTITNOMINA WHERE InstitNominaID = a.CB_InstitNominaID) as Empresa, " 
            . " (case when CC_Estatus in ('1','2','3') then 1 Else 0 end) as Revision, " 
            . " (case when CC_Estatus in ('4') AND CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' then 1 Else 0 end) as Aprobada, " 
            . " (case when CC_Estatus in ('5') AND CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' then 1 Else 0 end) as Rechazada, " 
            . " (case when CC_Estatus in ('6','7') AND CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' then 1 Else 0 end) as NoObjetiva " 
            . " FROM microfin.TB_SolicitudUnicaEME a " 
            . " WHERE CB_ProductoID = 1100)z " 
            . " GROUP BY Empresa;" ;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral_Fanos() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CA_Id not IN "
                . " (SELECT CB_SolicitudID FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1200) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1200) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1200) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1200) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral_Carta() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CA_Id not IN "
                . " (SELECT CB_SolicitudID FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1300) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1300) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1300) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1300) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDet_Simsa() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */(SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDet_Fanos() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */(SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDet_Carta() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */(SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Simsa() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1100) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1100) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1100) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1100) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Fanos() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1200) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1200) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1200) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1200) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Carta() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_ProductoID = 1300) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 1300) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 1300) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 1300) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Simsa_Aprob() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT CB_SolicitudID as SolId "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = '1100' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Fanos_Aprob() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT CB_SolicitudID as SolId "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = '1200' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Carta_Aprob() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT CB_SolicitudID as SolId "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = '1300' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto_Simsa() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1100 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }
    
    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto_SimsaDet() {
# Consulta para seleccionar todos los registros.
        /*$query = "SELECT "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Revision, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Aprobadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1100) as NoObjetivos";*/
        $query = " 
            SELECT Empresa, sum(Revision) as Revision, sum(Aprobada) as Aprobadas, sum(Rechazada) as Rechazadas, sum(NoObjetiva) as NoObjetivos  " 
            . " FROM (SELECT   " 
            . " (SELECT NombreInstit FROM microfin.INSTITNOMINA WHERE InstitNominaID = a.CB_InstitNominaID) as Empresa, " 
            . " (case when CC_Estatus in ('1','2','3') then 1 Else 0 end) as Revision, " 
            . " (case when CC_Estatus in ('4') AND CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' then 1 Else 0 end) as Aprobada, " 
            . " (case when CC_Estatus in ('5') AND CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' then 1 Else 0 end) as Rechazada, " 
            . " (case when CC_Estatus in ('6','7') AND CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' then 1 Else 0 end) as NoObjetiva " 
            . " FROM microfin.TB_SolicitudUnicaEME a " 
            . " WHERE CB_ProductoID = 1100)z " 
            . " GROUP BY Empresa;" ;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto_Fanos() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1200 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1200) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto_Carta() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 1300 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM microfin.TB_SolicitudUnicaEME WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 1300) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGralCtes45_Simsa() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . " AND CB_DiasAtraso <= 25) as Minus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . " AND CB_DiasAtraso <= 25) as RenovMinus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND  CB_ProductoCreditoID IN ('1100') "
                . " AND CB_DiasAtraso > 25) as Plus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . "  WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . "  AND CB_DiasAtraso > 25) as RenovPlus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') ) as Total";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetCtes45_Simsa() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . " 	 AND CB_DiasAtraso <= 25), 1 , 0))  as Minus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . " 	 AND CB_DiasAtraso <= 25 ), 1 , 0))  as RenovMinus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . " 	 AND CB_DiasAtraso > 25), 1 , 0))  as Plus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('1100') "
                . " 	 AND CB_DiasAtraso > 25), 1 , 0))  as RenovPlus45 "
                . " FROM TB_CreditosCT A INNER JOIN TB_SolicitudesCT B ON B.CB_CreditoID = A.CB_CreditoID "
                . " WHERE B.CB_EstadoID " . $this->sqlvalor1 . ";";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

#FIN DE FUNCIONES DE ANALYTICS SIMSA
#INICIO DE FUNCIONES DE ANALYTICS CONVIVE 
    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral_Con() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CA_Id not IN "
                . " (SELECT CB_SolicitudID FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID = 4000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 4000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 4000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 4000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDet_Con() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */(SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Con() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_ProductoID = 4000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 4000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 4000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 4000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_Con_Aprob() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT CB_SolicitudID as SolId "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = '4000' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto_Con() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 4000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 4000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGralCtes45_Con() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4000') "
                . " AND CB_DiasAtraso <= 25) as Minus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4001') "
                . " AND CB_DiasAtraso <= 25) as RenovMinus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND  CB_ProductoCreditoID IN ('4000') "
                . " AND CB_DiasAtraso > 25) as Plus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . "  WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4001') "
                . "  AND CB_DiasAtraso > 25) as RenovPlus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4000') ) as Total";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetCtes45_Con() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4000') "
                . " 	 AND CB_DiasAtraso <= 25), 1 , 0))  as Minus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4001') "
                . " 	 AND CB_DiasAtraso <= 25 ), 1 , 0))  as RenovMinus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4000') "
                . " 	 AND CB_DiasAtraso > 25), 1 , 0))  as Plus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('4001') "
                . " 	 AND CB_DiasAtraso > 25), 1 , 0))  as RenovPlus45 "
                . " FROM TB_CreditosCT A INNER JOIN TB_SolicitudesCT B ON B.CB_CreditoID = A.CB_CreditoID "
                . " WHERE B.CB_EstadoID " . $this->sqlvalor1 . ";";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

#FIN DE FUNCIONES DE ANALYTICS CONVIVE
#INICIO DE FUNCIONES DE ANALYTICS CONVIVE RED
    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGral_ConRed() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CA_Id not IN "
                . " (SELECT CB_SolicitudID FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_ProductoID = 5000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 5000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 5000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 5000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDet_ConRed() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */(SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_ConRed() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_ProductoID = 5000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '4')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = 5000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') "
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_ProductoID = 5000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_ProductoID = 5000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepHisto_ConRed_Aprob() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT CB_SolicitudID as SolId "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_ProductoID = '5000' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetHisto_ConRed() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('A', 'I', 'L') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('1','2','3'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaAlta between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('1','2','3') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as Revision, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('D') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus in ('4'))) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('4') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as Aprobadas, "
                . " /*(SELECT count(CA_id) FROM TB_SolicitudesCT "
                . " WHERE CB_ProductoID = 5000 "
                . " AND CB_FechaActual between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('R', 'C') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CA_Id not IN (SELECT CB_SolicitudID "
                . " FROM TB_SolicitudUnicaCT WHERE CC_Estatus = '5')) "
                . " + */ (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('5') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as Rechazadas, "
                . " (SELECT count(CA_Id) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_FechaModi between '" . $this->fini . "' and '" . $this->ffin . "' "
                . " AND CC_Estatus IN ('6', '7') AND CB_EstadoID " . $this->sqlvalor1
                . " AND CB_ProductoID = 5000) as NoObjetivos";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGralCtes45_ConRed() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5000') "
                . " AND CB_DiasAtraso <= 45) as Minus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5001') "
                . " AND CB_DiasAtraso <= 45) as RenovMinus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A  "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND  CB_ProductoCreditoID IN ('5000') "
                . " AND CB_DiasAtraso > 45) as Plus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . "  WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5001') "
                . "  AND CB_DiasAtraso > 45) as RenovPlus45, "
                . " (SELECT Count(*) FROM TB_CreditosCT A "
                . " WHERE (CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5000') ) as Total";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetCtes45_ConRed() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5000') "
                . " 	 AND CB_DiasAtraso <= 25), 1 , 0))  as Minus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5001') "
                . " 	 AND CB_DiasAtraso <= 25 ), 1 , 0))  as RenovMinus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5000') "
                . " 	 AND CB_DiasAtraso > 25), 1 , 0))  as Plus45, "
                . " SUM(IF(((CB_Estatus IN ('V', 'B') OR (CB_Estatus IN ('P') AND CB_FechaVencimien >= now()))"
                . " AND CB_ProductoCreditoID IN ('5001') "
                . " 	 AND CB_DiasAtraso > 25), 1 , 0))  as RenovPlus45 "
                . " FROM TB_CreditosCT A INNER JOIN TB_SolicitudesCT B ON B.CB_CreditoID = A.CB_CreditoID "
                . " WHERE B.CB_EstadoID " . $this->sqlvalor1 . ";";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

#FIN DE FUNCIONES DE ANALYTICS CONVIVE RED

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepGralCtes() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V', 'B') "
                . " AND CB_ProductoCreditoID IN ('2001') "
                . " AND (SELECT count(CA_Id) FROM TB_ActividadCreditoCT "
                . " WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B')) = 0) as KitVigentes, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2001') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') LIMIT 1) <= 30) as KitMinus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2001') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') LIMIT 1) > 30) as KitPlus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V', 'B') "
                . " AND CB_ProductoCreditoID IN ('2002') "
                . " AND (SELECT count(CA_Id) FROM TB_ActividadCreditoCT "
                . " WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B')) = 0) as RenovVigentes, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2002') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') lIMIT 1) <= 30) as RenovMinus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2002') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') LIMIT 1) > 30) as RenovPlus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT WHERE CB_Estatus IN ('V','B') AND CB_ProductoCreditoID IN ('2001','2002')) as Total ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_RepDetCtes() {
# Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V', 'B') "
                . " AND CB_ProductoCreditoID IN ('2001') "
                . " AND (SELECT count(CA_Id) FROM TB_ActividadCreditoCT "
                . " WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B')) = 0 "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as KitVigentes, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2001') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') LIMIT 1) <= 30 "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as KitMinus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2001') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') LIMIT 1) > 30 "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as KitPlus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V', 'B') "
                . " AND CB_ProductoCreditoID IN ('2002') "
                . " AND (SELECT count(CA_Id) FROM TB_ActividadCreditoCT "
                . " WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B')) = 0 "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as RenovVigentes, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2002') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') lIMIT 1) <= 30 "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as RenovMinus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') "
                . " AND CB_ProductoCreditoID IN ('2002') "
                . " AND (SELECT COALESCE(DATEDIFF(now(), CB_FechaVencimiento), DATEDIFF(now(), CB_FechaEstatus)) "
                . " FROM TB_ActividadCreditoCT WHERE CB_CreditoID = A.CB_CreditoID AND CB_AmortEstatus IN ('A','B') LIMIT 1) > 30 "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as RenovPlus30, "
                . " (SELECT Count(*) FROM TB_CreditosCT A WHERE CB_Estatus IN ('V','B') AND CB_ProductoCreditoID IN ('2001','2002') "
                . " AND coalesce((SELECT CB_EstadoID FROM TB_SolicitudesCT WHERE CB_CreditoID = A.CB_CreditoID),0) " . $this->sqlvalor1 . ") as Total;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_Nombre() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_Nombre "
                . " FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = '" . $this->Valor . "' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function search_Cel_Ant() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CA_Id = '" . $this->CA_Id . "' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función que regresa el id principal de la tabla TB_SolicitudUnicaCT por la curp
     */

    function searchxCurp() {
# Consulta para seleccionar la CA_Id
        $query = "SELECT CA_Id, CB_ClienteID "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' LIMIT 1";
# Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
# Ejecución de la consulta.
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Función que regresa el id principal de la tabla TB_SolicitudUnicaCT por la curp
     */

    function searchCidByCurp() {
# Consulta para seleccionar la CA_Id
        $query = "SELECT CB_ClienteID "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' LIMIT 1";
# Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
# Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['CB_ClienteID'];
    }

    /*
     * Busca la solicitud del prospecto para convive red
     */

    function searchSolicitudProspecto() {
        # consulta
        $query = "SELECT *"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->CB_Celular . "' AND CB_Correo = '" . $this->CB_Correo . "' ORDER BY CA_Id DESC LIMIT 1";
        # preparación de la consullta
        $stmt = $this->conn->prepare($query);
        # ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función que busca las solicitudes convive más de los clientes y no de los avales
     */

    function searchSolCM() {
        if ($this->CB_SolicitudEsta == "D") {
            //if($this->Valor == "4") {
            $queryWhere = " AND A.CC_Estatus = " . $this->CC_Estatus
                    . " AND A.CB_SolicitudEsta = 'D'";
            //. " AND (SELECT CB_ValorNue FROM TB_AuditoriaCT WHERE CB_RID = A.CA_Id AND CB_Campo = 'Saldo') = 0";
            /* } else {
              $queryWhere .= " AND A.CC_Estatus = " . $this->CC_Estatus
              . " AND A.CB_SolicitudEsta = 'D' AND"
              . " (SELECT CB_ValorNue FROM TB_AuditoriaCT WHERE CB_RID = A.CA_Id AND CB_Campo = 'Saldo') > 0";
              } */
        } else if ($this->CB_SolicitudEsta == "L") {
            $queryWhere = " AND A.CC_Estatus = " . $this->CC_Estatus . " AND A.CB_SolicitudEsta = 'L'";
        } else if ($this->CC_Estatus == "5") {
            $queryWhere = " AND A.CC_Estatus IN (5, 6, 7)";
        } else {
            $queryWhere = " AND A.CC_Estatus = " . $this->CC_Estatus;
        }
        # consulta
        $query = "SELECT A.CA_Id AS numero, CONCAT(TRIM(A.CB_PNombre), ' ', TRIM(A.CB_SNombre), ' ', TRIM(A.CB_TNombre), ' ', TRIM(A.CB_APaterno), ' ',"
                . " TRIM(A.CB_AMaterno)) AS nombre, A.CB_Celular AS celular, A.CB_FechaAlta AS registro, A.CB_Observaciones AS observaciones,"
                . " (SELECT COUNT(*) FROM " . $this->TB_SolicitudUnicaCT . " WHERE CB_TelefonoAval = celular) AS aval,"
                . " (SELECT COUNT(*) FROM " . $this->TB_SolicitudUnicaCT . " WHERE CB_TelefonoAval = celular AND CC_Estatus = 23) AS avalAprobado,"
                . " A.CB_CreditoID AS credito, A.CB_ClienteID as cliente, A.CB_SolicitudID AS solicitudID, IFNULL(B.Estatus, '') AS estatus,"
                . " A.CB_Monto AS monto,"
                . " (SELECT COUNT(*) FROM " . $this->TB_SolicitudUnicaCT . " A WHERE A.CB_ProductoID = 6000 AND A.CB_TelefonoAval = ''"
                . $queryWhere . ") AS numRegistros"
                . " FROM " . $this->TB_SolicitudUnicaCT . " A"
                . " LEFT JOIN microfin.SOLICITUDCREDITO B"
                . " ON A.CB_SolicitudID = B.SolicitudCreditoID"
                . " WHERE A.CB_ProductoID = 6000 AND A.CB_TelefonoAval = ''";
        $query .= $queryWhere;
        $query .= " ORDER BY " . $this->Orden . " " . $this->OrdenDir;
        $query .= " LIMIT " . $this->start . ", " . $this->limit;
        # preparación de la consulta
        $stmt = $this->conn->prepare($query);
        # se pasan los datos
        //$stmt->bindParam(":CC_Estatus", $this->CC_Estatus);
        //$stmt->bindParam(":CB_SolicitudEsta", $this->CB_SolicitudEsta);
        # ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Función que busca los avales de una solicitud convive más
     */

    function searchSolAvales() {
        # creación de la consulta
        $query = "SELECT *"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_TelefonoAval = '" . $this->CB_TelefonoAval . "'";
        # preparación de la consulta
        $stmt = $this->conn->prepare($query);
        # ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Función que busca la cantidad de solicitudes rechazadas, prospectos rechazados y cancelados de avales
     */

    function searchSolAvalEst() {
        # creación de la consulta
        $query = "SELECT COUNT(*) as solRechazadas,"
                . " (SELECT COUNT(*) FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_TelefonoAval = '" . $this->CB_TelefonoAval . "' and CC_Estatus = 6) as proRechazadas,"
                . " (SELECT COUNT(*) FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_TelefonoAval = '" . $this->CB_TelefonoAval . "' and CC_Estatus = 7) as proCanceladas"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_TelefonoAval = '" . $this->CB_TelefonoAval . "' AND CC_Estatus = 5";
        # preparación de la consulta
        $stmt = $this->conn->prepare($query);
        # ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function valid() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT IF(EXISTS( "
                . " SELECT CC_Estatus FROM " . $this->TB_SolicitudUnicaCT . " tsu "
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' AND CB_ProductoID = '" . $this->CB_ProductoID . "'"
                . " AND (SELECT datediff(now(), CB_FechaAlta) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' AND CB_ProductoID != '" . $this->CB_ProductoID . "') >= 90), "
                . " (SELECT CC_Estatus FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CURP = '" . $this->CB_CURP . "'  AND CB_ProductoID = '" . $this->CB_ProductoID . "' "
                . " ORDER BY CA_Id DESC LIMIT 1), 0) as CC_Estatus ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['CC_Estatus'];
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function validCelMail() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT IF((SELECT COUNT(*) "
                . " FROM TB_SolicitudUnicaCT "
                . " WHERE CB_Celular = '" . $this->CB_Celular . "' "
                . " AND CB_CURP != '" . $this->CB_CURP . "') > 0 "
                . " OR (SELECT COUNT(*) FROM TB_SolicitudUnicaCT "
                . " WHERE CB_Correo = '" . $this->CB_Correo . "' "
                . " AND CB_CURP != '" . $this->CB_CURP . "') > 0, 0, 1) as valido;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['valido'];
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function getFechaAlta() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT IF(EXISTS( "
                . " SELECT CB_FechaAlta FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' AND CB_ProductoID = '" . $this->CB_ProductoID . "'), "
                . " (SELECT CB_FechaAlta FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_CURP = '" . $this->CB_CURP . "' AND CB_ProductoID = '" . $this->CB_ProductoID . "' "
                . " ORDER BY CA_Id DESC LIMIT 1), 0) as CB_FechaAlta";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['CB_FechaAlta'];
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function getRecluta() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT coalesce(CB_Reclutador, '0002') as CB_Reclutador "
                . " FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = '" . $this->CB_Patrocinador . "';";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['CB_Reclutador'];
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function getReclutaByCel() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT coalesce(CB_Reclutador, '0002') as CB_Reclutador "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->Patronum . "' "
                . " ORDER BY CA_ID DESC LIMIT 1; ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['CB_Reclutador'];
    }

    /*
     * Función para buscar los registros de la base de datos.
     */

    function getTasa() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT COALESCE((SELECT CB_Tasa "
                . " FROM TB_CreditosConfCT tc "
                . " WHERE tc.CB_Plazo = '" . $this->CB_Plazo . "' limit 1), '262.0000') as CB_Tasa;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['CB_Tasa'];
    }

    /*
     * Función para crear el registro
     */

    function create() {
        // query to insert record
        $query = "INSERT INTO " . $this->TB_SolicitudUnicaCT
                . " (CB_ClienteID, CB_SolicitudID, CB_CreditoID, CB_PNombre, CB_SNombre, CB_TNombre, CB_APaterno, "
                . " CB_AMaterno, CB_Celular, CB_Correo, CB_ProductoID, CB_Tasa, CB_Monto, CB_Plazo, CB_ActividadEco, CB_AntiguedadLab, CB_Ingreso, "
                . " CB_Egreso, CB_GradoEscolarID, CB_DependientesEco, CB_TDC, CB_NumeroTarjeta, CB_Hipotecario, CB_Automotriz, CB_INEAn, CB_INERe, CB_INENum, CB_INEEmi, CB_INEVig, CB_Comprobante, "
                . " CB_Genero, CB_Titulo, CB_FNacimiento, CB_CURP, CB_RFC, CB_RFCOficial, CB_LNacimiento, CB_ENacimiento, CB_EsMismaDir, CB_Calle, "
                . " CB_Numero, CB_Numero_Int, CB_ColoniaID, CB_ColoniaDes, CB_CP, CB_MunicipioID, CB_MunicipioDes, CB_EstadoID, CB_EstadoDes, CB_FechaAlta, "
                . " CB_FechaModi, CB_Reclutador, CB_Patrocinador, CC_Usuario, CC_Canal, CC_Estatus, CK_prospectosID, CK_type_housing, CK_year_living, CK_referred_name, "
                . " CK_why_unemployed, CK_why_need_loan, CK_job, CK_company_name, CK_company_phone, CK_salary, CK_address_company, CK_status_credit_bureau, CK_proof_payroll) "
                . " VALUES "
                . " ('" . $this->CB_ClienteID . "', '" . $this->CB_SolicitudID . "', '" . $this->CB_CreditoID . "', '" . $this->CB_PNombre . "', "
                . " '" . $this->CB_SNombre . "', '" . $this->CB_TNombre . "', '" . $this->CB_APaterno . "', '" . $this->CB_AMaterno . "', "
                . " '" . $this->CB_Celular . "', '" . $this->CB_Correo . "', '" . $this->CB_ProductoID . "', '" . $this->CB_Tasa . "', "
                . " '" . $this->CB_Monto . "', '" . $this->CB_Plazo . "', '" . $this->CB_ActividadEco . "', '" . $this->CB_AntiguedadLab . "', '" . $this->CB_Ingreso . "', "
                . " '" . $this->CB_Egreso . "', '" . $this->CB_GradoEscolarID . "', '" . $this->CB_DependientesEco . "', '" . $this->CB_TDC . "', "
                . " '" . $this->CB_NumeroTarjeta . "', '" . $this->CB_Hipotecario . "', '" . $this->CB_Automotriz . "', '" . $this->CB_INEAn . "', "
                . " '" . $this->CB_INERe . "', '" . $this->CB_INENum . "', '" . $this->CB_INEEmi . "', '" . $this->CB_INEVig . "', '" . $this->CB_Comprobante . "', "
                . " '" . $this->CB_Genero . "', '" . $this->CB_Titulo . "', '" . $this->CB_FNacimiento . "', '" . $this->CB_CURP . "', '" . $this->CB_RFC . "', "
                . " '" . $this->CB_RFCOficial . "', '" . $this->CB_LNacimiento . "', '" . $this->CB_ENacimiento . "', '" . $this->CB_EsMismaDir . "', '" . $this->CB_Calle . "', "
                . " '" . $this->CB_Numero . "', '" . $this->CB_Numero_Int . "', '" . $this->CB_ColoniaID . "', '" . $this->CB_ColoniaDes . "', "
                . " '" . $this->CB_CP . "', '" . $this->CB_MunicipioID . "', '" . $this->CB_MunicipioDes . "', '" . $this->CB_EstadoID . "', "
                . " '" . $this->CB_EstadoDes . "', '" . $this->CB_FechaAlta . "', '" . $this->CB_FechaModi . "', '" . $this->CB_Reclutador . "', "
                . " '" . $this->CB_Patrocinador . "', '" . $this->CC_Usuario . "', '" . $this->CC_Canal . "', '" . $this->CC_Estatus . "', "
                . " '" . $this->CK_prospectosID . "', '" . $this->CK_type_housing . "', '" . $this->CK_year_living . "', '" . $this->CK_referred_name . "', "
                . " '" . $this->CK_why_unemployed . "', '" . $this->CK_why_need_loan . "', '" . $this->CK_job . "', "
                . " '" . $this->CK_company_name . "', '" . $this->CK_company_phone . "', '" . $this->CK_salary . "', '" . $this->CK_address_company . "', "
                . " '" . $this->CK_status_credit_bureau . "', '" . $this->CK_proof_payroll . "');";
        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        if ($stmt->execute()) {
            $this->CA_Id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /*
     * Función para crear el registro
     */

    function generar() {
        // query to insert record
        $query = "INSERT INTO microfin.TB_SolicitudUnicaCT "
                . " (CB_ClienteID,CB_SolicitudID,CB_SolicitudEsta,CB_CreditoID,CB_PNombre, "
                . " CB_SNombre,CB_TNombre,CB_APaterno,CB_AMaterno,CB_Celular,CB_Correo, "
                . " CB_ProductoID,CB_Tasa,CB_Monto,CB_Plazo,CB_ActividadEco,CB_AntiguedadLab, "
                . " CB_Ingreso,CB_Egreso,CB_GradoEscolarID,CB_DependientesEco,CB_TDC,CB_NumeroTarjeta, "
                . " CB_Hipotecario,CB_Automotriz,CB_INEAn,CB_INERe,CB_INENum,CB_INEEmi,CB_INEVig, "
                . " CB_Comprobante,CB_Genero,CB_Titulo,CB_FNacimiento,CB_CURP,CB_RFC,CB_RFCOficial, "
                . " CB_LNacimiento,CB_ENacimiento,CB_EsMismaDir,CB_Calle,CB_Numero,CB_Numero_Int,CB_ColoniaID, "
                . " CB_ColoniaDes,CB_CP,CB_MunicipioID,CB_MunicipioDes,CB_EstadoID,CB_EstadoDes, "
                . " CB_FechaAlta, CB_Reclutador,CB_Patrocinador,CB_Observaciones,CC_Usuario, "
                . " CC_Canal,CC_Estatus,CK_prospectosID,CK_type_housing,CK_year_living,CK_referred_name, "
                . " CK_why_unemployed,CK_why_need_loan,CK_job,CK_company_name,CK_company_phone, "
                . " CK_salary,CK_address_company,CK_status_credit_bureau,CK_proof_payroll) "
                . " SELECT '0',    '0',    'N',    '0',    CB_PNombre, "
                . " CB_SNombre,    CB_TNombre,    CB_APaterno,    CB_AMaterno,    CB_Celular,    CB_Correo, "
                . " CB_ProductoID,    CB_Tasa,    CB_Monto,    CB_Plazo,    CB_ActividadEco,    CB_AntiguedadLab, "
                . " CB_Ingreso,    CB_Egreso,    CB_GradoEscolarID,    CB_DependientesEco,    CB_TDC,    CB_NumeroTarjeta, "
                . " CB_Hipotecario,    CB_Automotriz,    CB_INEAn,    CB_INERe,    CB_INENum,    CB_INEEmi,    CB_INEVig, "
                . " CB_Comprobante,    CB_Genero,    CB_Titulo,    CB_FNacimiento,    CB_CURP,    CB_RFC,    CB_RFCOficial, "
                . " CB_LNacimiento,    CB_ENacimiento,    CB_EsMismaDir,    CB_Calle,    CB_Numero,    CB_Numero_Int,    CB_ColoniaID, "
                . " CB_ColoniaDes,    CB_CP,    CB_MunicipioID,    CB_MunicipioDes,    CB_EstadoID,    CB_EstadoDes, "
                . " now(), CB_Reclutador,    CB_Patrocinador,    CB_Observaciones, " . $this->CC_Usuario . ", "
                . " CC_Canal,    '0',    CK_prospectosID,    CK_type_housing,    CK_year_living,    CK_referred_name, "
                . " CK_why_unemployed,    CK_why_need_loan,    CK_job,    CK_company_name,    CK_company_phone, "
                . " CK_salary,    CK_address_company,    CK_status_credit_bureau,    CK_proof_payroll "
                . " FROM microfin.TB_SolicitudUnicaCT "
                . " WHERE CA_Id = '" . $this->CA_Id . "'; ";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if ($stmt->execute()) {
            $this->CA_Id = $this->conn->lastInsertId();
            return true;
        }
        $this->Valor = $query;
        return false;
    }

    /*
     * Función para crear el registro
     */

    function createRenovCP() {
        // query to insert record
        $query = "INSERT INTO " . $this->TB_SolicitudUnicaCT
                . " (CB_ClienteID,CB_PNombre,CB_SNombre,CB_TNombre,CB_APaterno,CB_AMaterno, "
                . " CB_Celular,CB_Correo,CB_ProductoID,CB_Tasa,CB_Monto,CB_Plazo,CB_ActividadEco, "
                . " CB_AntiguedadLab,CB_Ingreso,CB_Egreso,CB_GradoEscolarID,CB_DependientesEco, "
                . " CB_TDC,CB_NumeroTarjeta,CB_Hipotecario,CB_Automotriz,CB_INEAn,CB_INERe, "
                . " CB_INENum,CB_INEEmi,CB_INEVig,CB_Comprobante,CB_Genero,CB_Titulo,CB_FNacimiento, "
                . " CB_CURP,CB_RFC,CB_RFCOficial,CB_LNacimiento,CB_ENacimiento,CB_EsMismaDir, "
                . " CB_Calle,CB_Numero,CB_Numero_Int,CB_ColoniaID,CB_ColoniaDes,CB_CP, "
                . " CB_MunicipioID,CB_MunicipioDes,CB_EstadoID,CB_EstadoDes,CB_FechaAlta, "
                . " CB_FechaModi,CB_Reclutador,CB_Patrocinador,CB_Observaciones,CC_Usuario, "
                . " CC_Canal,CC_Estatus,CK_prospectosID,CK_type_housing,CK_year_living,CK_referred_name, "
                . " CK_why_unemployed,CK_why_need_loan,CK_job,CK_company_name,CK_company_phone,CK_salary, "
                . " CK_address_company,CK_status_credit_bureau,CK_proof_payroll) "
                . " (SELECT CB_ClienteID,CB_PNombre,CB_SNombre,CB_TNombre,CB_APaterno,CB_AMaterno, "
                . " CB_Celular,CB_Correo,CB_ProductoID,'" . $this->CB_Tasa . "','" . $this->CB_Monto . "','" . $this->CB_Plazo . "',CB_ActividadEco, "
                . " CB_AntiguedadLab,CB_Ingreso,CB_Egreso,CB_GradoEscolarID,CB_DependientesEco, "
                . " CB_TDC,CB_NumeroTarjeta,CB_Hipotecario,CB_Automotriz,CB_INEAn,CB_INERe, "
                . " CB_INENum,CB_INEEmi,CB_INEVig,CB_Comprobante,CB_Genero,CB_Titulo,CB_FNacimiento, "
                . " CB_CURP,CB_RFC,CB_RFCOficial,CB_LNacimiento,CB_ENacimiento,CB_EsMismaDir, "
                . " CB_Calle,CB_Numero,CB_Numero_Int,CB_ColoniaID,CB_ColoniaDes,CB_CP, "
                . " CB_MunicipioID,CB_MunicipioDes,CB_EstadoID,CB_EstadoDes,CB_FechaAlta, "
                . " CB_FechaModi,CB_Reclutador,CB_Patrocinador,CB_Observaciones,CC_Usuario, "
                . " CC_Canal,'" . $this->CC_Estatus . "',CK_prospectosID,CK_type_housing,CK_year_living,CK_referred_name, "
                . " CK_why_unemployed,CK_why_need_loan,CK_job,CK_company_name,CK_company_phone,CK_salary, "
                . " CK_address_company,CK_status_credit_bureau,CK_proof_payroll "
                . " FROM TB_SolicitudUnicaCT WHERE CA_Id = '" . $this->CA_Id . "');";
        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function read() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT *, (SELECT COUNT(CA_Id) FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' ";
        $query .= ((int) $this->CC_Estatus >= 4 && (int)$this->Bandera == 0 ? " AND MONTH(CB_FechaModi) = MONTH(CURRENT_DATE()) AND YEAR(CB_FechaModi) = YEAR(CURRENT_DATE()) " : "");
        $query .= ((int)$this->Bandera == 1 ? " AND CB_FechaModi > (SELECT IF (MONTH(CURRENT_DATE()) - 1 = 0, CONCAT(YEAR(CURRENT_DATE()) - 1, '-', 12, '-20'), CONCAT(YEAR(CURRENT_DATE()), '-', LPAD(MONTH(CURRENT_DATE()) - 1, 2, 0), '-20')))" : "");
        $query .= " ) as total, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioOV, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioCT, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_CorreoE = tsu.CB_Correo AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCorreo, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = tsu.CB_Celular AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCelular, "
                . " coalesce((SELECT COUNT(*) c FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_Celular = tsu.CB_Celular AND CC_Estatus = '" . $this->CC_Estatus . "' "
                . " GROUP BY CB_Celular HAVING c > 1), 0) as ValDup,"
                . " coalesce((SELECT CC_FirmFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as DocFirma, "
                . " coalesce((SELECT CC_ExpFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as FechaExpDoc, "
                . " coalesce((SELECT MontoAutorizado FROM microfin." . $this->SOLICITUDCREDITO . " as sol "
                . " WHERE tsu.CB_SolicitudID = sol.SolicitudCreditoID AND tsu.CB_ClienteID = sol.ClienteID), 0) as MontoAutorizado, "
                . " coalesce((SELECT NombreInstit FROM microfin." . $this->INSTITNOMINA . " i "
                . " WHERE tsu.CB_InstitNominaID = i.InstitNominaID), 'CONVIVE FINANCIERA') Empresa"
                . " FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu "
                . " WHERE ";
        if ((int) $this->CC_Estatus === 3) {
            $query .= " CC_Estatus IN (3,12,22) ";
        } else if ((int) $this->CC_Estatus === 1) {
            $query .= " CC_Estatus IN (1, 20) ";
        } else if ((int) $this->CC_Estatus === 2) {
            $query .= " CC_Estatus IN (2, 21) ";
        } else if ((int) $this->CC_Estatus === 5) {
            $query .= " CC_Estatus IN (5, 24) ";
        } else if ((int) $this->CC_Estatus === 7) {
            $query .= " CC_Estatus IN (7, 25) ";
        } else {
            $query .= " CC_Estatus = '" . $this->CC_Estatus . "' ";
        }
        //validación si el usuario es de simsa entonces solo se muestra el producto 1100
        if ($this->alias === 'SIM') {
            $query .= " AND CB_ProductoID = 1100 ";
        } else if ($this->alias === 'FAH') {
            $query .= " AND CB_ProductoID = 1200 ";
        } else if ($this->alias === 'CAR') {
            $query .= " AND CB_ProductoID = 1300 ";
        }
        else if ($this->alias === 'LUX') {
            $query .= " AND CB_ProductoID = 1000 ";
        }
        $query .= ((int) $this->CC_Estatus >= 4 && (int)$this->Bandera == 0 ? " AND MONTH(CB_FechaModi) = MONTH(CURRENT_DATE()) AND YEAR(CB_FechaModi) = YEAR(CURRENT_DATE()) " : "");
        $query .= ((int)$this->Bandera == 1 ? " AND CB_FechaModi > (SELECT IF (MONTH(CURRENT_DATE()) - 1 = 0, CONCAT(YEAR(CURRENT_DATE()) - 1, '-', 12, '-20'), CONCAT(YEAR(CURRENT_DATE()), '-', LPAD(MONTH(CURRENT_DATE()) - 1, 2, 0), '-20')))" : "");
        $query .= " ORDER BY CA_Id DESC LIMIT " . $this->start . ", " . $this->limit;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    function read_filtro_emp() {
        $this->Valor = str_replace("|", "%", $this->Valor);
        # Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . "FROM ("
                . "SELECT *, (SELECT COUNT(CA_Id) FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' ";
        $query .= ((int) $this->CC_Estatus >= 4 && (int)$this->Bandera == 0 ? " AND MONTH(CB_FechaModi) = MONTH(CURRENT_DATE()) AND YEAR(CB_FechaModi) = YEAR(CURRENT_DATE()) " : "");
        $query .= ((int)$this->Bandera == 1 ? " AND CB_FechaModi > (SELECT IF (MONTH(CURRENT_DATE()) - 1 = 0, CONCAT(YEAR(CURRENT_DATE()) - 1, '-', 12, '-20'), CONCAT(YEAR(CURRENT_DATE()), '-', LPAD(MONTH(CURRENT_DATE()) - 1, 2, 0), '-20')))" : "");
        $query .= " ) as total, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioOV, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioCT, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_CorreoE = tsu.CB_Correo AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCorreo, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = tsu.CB_Celular AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCelular, "
                . " coalesce((SELECT COUNT(*) c FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_Celular = tsu.CB_Celular AND CC_Estatus = '" . $this->CC_Estatus . "' "
                . " GROUP BY CB_Celular HAVING c > 1), 0) as ValDup,"
                . " coalesce((SELECT CC_FirmFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as DocFirma, "
                . " coalesce((SELECT CC_ExpFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as FechaExpDoc, "
                . " coalesce((SELECT MontoAutorizado FROM microfin." . $this->SOLICITUDCREDITO . " as sol "
                . " WHERE tsu.CB_SolicitudID = sol.SolicitudCreditoID AND tsu.CB_ClienteID = sol.ClienteID), 0) as MontoAutorizado, "
                . " coalesce((SELECT NombreInstit FROM microfin." . $this->INSTITNOMINA . " i "
                . " WHERE tsu.CB_InstitNominaID = i.InstitNominaID), 'CONVIVE FINANCIERA') Empresa"
                . " FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu "
                . " WHERE ";
        if ((int) $this->CC_Estatus === 3) {
            $query .= " CC_Estatus IN (3,12,22) ";
        } else if ((int) $this->CC_Estatus === 1) {
            $query .= " CC_Estatus IN (1, 20) ";
        } else if ((int) $this->CC_Estatus === 2) {
            $query .= " CC_Estatus IN (2, 21) ";
        } else if ((int) $this->CC_Estatus === 5) {
            $query .= " CC_Estatus IN (5, 24) ";
        } else if ((int) $this->CC_Estatus === 7) {
            $query .= " CC_Estatus IN (7, 25) ";
        } else {
            $query .= " CC_Estatus = '" . $this->CC_Estatus . "' ";
        }
        //validación si el usuario es de simsa entonces solo se muestra el producto 1100
        if ($this->alias === 'SIM') {
            $query .= " AND CB_ProductoID = 1100 ";
        } else if ($this->alias === 'FAH') {
            $query .= " AND CB_ProductoID = 1200 ";
        } else if ($this->alias === 'CAR') {
            $query .= " AND CB_ProductoID = 1300 ";
        }
        $query .= ((int) $this->CC_Estatus >= 4 && (int)$this->Bandera == 0 ? " AND MONTH(CB_FechaModi) = MONTH(CURRENT_DATE()) AND YEAR(CB_FechaModi) = YEAR(CURRENT_DATE()) " : "");
        $query .= ((int)$this->Bandera == 1 ? " AND CB_FechaModi > (SELECT IF (MONTH(CURRENT_DATE()) - 1 = 0, CONCAT(YEAR(CURRENT_DATE()) - 1, '-', 12, '-20'), CONCAT(YEAR(CURRENT_DATE()), '-', LPAD(MONTH(CURRENT_DATE()) - 1, 2, 0), '-20')))" : "");
        $query .= " ORDER BY CA_Id DESC LIMIT " . $this->start . ", " . $this->limit . ") t WHERE Empresa LIKE '" . $this->Valor . "'";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }
    
    function autocompleteName() {
        #Constulka para filtrar los registros.
        $query = "SELECT DISTINCT concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(REPLACE(CB_SNombre, '_', '')) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(REPLACE(CB_TNombre, '_', '')) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')),' ',trim(REPLACE(CB_AMaterno, '_', ''))) as Nombre "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu "
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' AND " . ($this->CB_Reclutador == "0710" || $this->CB_Reclutador == "0711" || $this->CB_Reclutador == "0712" || $this->CB_Reclutador == "0713" || $this->CB_Reclutador == "0714" ? " CB_ProductoID = 1100 AND " : "")
                . " (concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(CB_SNombre) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(CB_TNombre) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')), ' ',trim(REPLACE(CB_AMaterno, '_', ''))) like '" . $this->Valor . "' COLLATE 'utf8_bin') ";
        if ((int) $this->CB_Reclutador >= 710 && (int) $this->CB_Reclutador <= 714) {
            $query .= " AND CB_ProductoID = 1100 ";
        }
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        $this->Valor = str_replace("|", "%", $this->Valor);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }
    
    function autocompleteEmpresa() {
        #Constulka para filtrar los registros.
        $this->Valor = str_replace("|", "%", $this->Valor);
        $query = "SELECT DISTINCT NombreInstit Nombre "
                . "FROM microfin." . $this->INSTITNOMINA . " WHERE NombreInstit LIKE '" . $this->Valor . "'";
        if ((int) $this->CB_Reclutador >= 710 && (int) $this->CB_Reclutador <= 714) {
            $query .= " AND CB_ProductoID = 1100 ";
        }
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);        
        # Ejecución de la consulta.
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function autocompleteCte() {
        $this->Valor = str_replace("|", "%", $this->Valor);
        #Constulka para filtrar los registros.
        $query = "SELECT DISTINCT concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(REPLACE(CB_SNombre, '_', '')) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(REPLACE(CB_TNombre, '_', '')) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')),' ',trim(REPLACE(CB_AMaterno, '_', ''))) as Nombre,  "
                . " CB_ClienteID as ClienteID, CB_Celular as Celular "
                . " FROM  . $this->TB_SolicitudUnicaCT tsu "
                . " WHERE CC_Estatus <= 4 AND "
                . " (concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(CB_SNombre) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(CB_TNombre) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')), ' ',trim(REPLACE(CB_AMaterno, '_', ''))) like '" . $this->Valor . "' COLLATE 'utf8_bin') "
                . " ORDER BY tsu.CA_Id DESC ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    function read_filtro() {
        $this->Valor = str_replace("|", "%", $this->Valor);
        #Constulka para filtrar los registros.
        $query = "SELECT *, (SELECT COUNT(CA_Id) FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu1"
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' AND "
                . " (concat( trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu1.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu1.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin')) as total, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioOV, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioCT, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_CorreoE = tsu.CB_Correo AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCorreo, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = tsu.CB_Celular AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCelular, "
                . " coalesce((SELECT COUNT(*) c FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_Celular = tsu.CB_Celular AND CC_Estatus = '" . $this->CC_Estatus . "' "
                . " GROUP BY CB_Celular HAVING c > 1), 0) as ValDup, "
                . " coalesce((SELECT CC_FirmFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as DocFirma, "
                . " coalesce((SELECT CC_ExpFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as FechaExpDoc, "
                . " coalesce((SELECT MontoAutorizado FROM microfin." . $this->SOLICITUDCREDITO . " as sol "
                . " WHERE tsu.CB_SolicitudID = sol.SolicitudCreditoID AND tsu.CB_ClienteID = sol.ClienteID), 0) as MontoAutorizado "
                . " FROM  microfin." . $this->TB_SolicitudUnicaEME . " tsu "
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' AND "
                . " (concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(REPLACE(CB_SNombre, '_', '')) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(REPLACE(CB_TNombre, '_', '')) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')),' ',trim(REPLACE(CB_AMaterno, '_', ''))) like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CA_Id = '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CB_Celular like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CB_ClienteID like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CB_SolicitudID like '" . $this->Valor . "'  COLLATE 'utf8_bin'"
                . " OR CB_CreditoID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR CB_ProductoID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR (SELECT Descripcion FROM microfin.PRODUCTOSCREDITO A "
                . " WHERE A.ProducCreditoID = tsu.CB_ProductoID  LIMIT 1) like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR (SELECT CB_Nombre FROM TB_CentroCapCT C "
                . " WHERE C.CB_Estados like CONCAT('%', tsu.CB_EstadoID , '%') LIMIT 1) like '" . $this->Valor . "' COLLATE 'utf8_bin') "
                . ($this->CB_Reclutador >= 710 && (int) $this->CB_Reclutador <= 712 ? " AND CB_ProductoID = 1100 " : "")
                . " ORDER BY CA_Id DESC LIMIT " . $this->start . ", " . $this->limit;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    function read_filtro_reg() {
        $this->Valor = str_replace("|", "%", $this->Valor);
        #Constulka para filtrar los registros.
        $query = "SELECT *, (SELECT COUNT(CA_Id) FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu1"
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' AND "
                . " (concat( trim(CB_PNombre),' ',trim(CB_SNombre),' ',trim(CB_TNombre),' ', "
                . " trim(CB_APaterno),' ',trim(CB_AMaterno)) like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu1.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu1.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "')) as total, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioOV, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioCT, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_CorreoE = tsu.CB_Correo AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCorreo, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = tsu.CB_Celular AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCelular, "
                . " coalesce((SELECT COUNT(*) c FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_Celular = tsu.CB_Celular AND CC_Estatus = '" . $this->CC_Estatus . "' "
                . " GROUP BY CB_Celular HAVING c > 1), 0) as ValDup, "
                . " coalesce((SELECT CC_FirmFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as DocFirma, "
                . " coalesce((SELECT CC_ExpFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as FechaExpDoc, "
                . " coalesce((SELECT MontoAutorizado FROM microfin." . $this->SOLICITUDCREDITO . " as sol "
                . " WHERE tsu.CB_SolicitudID = sol.SolicitudCreditoID AND tsu.CB_ClienteID = sol.ClienteID), 0) as MontoAutorizado "
                . " FROM  microfin." . $this->TB_SolicitudUnicaEME . " tsu "
                . " WHERE CC_Estatus = '" . $this->CC_Estatus . "' AND "
                //. " CB_Reclutador = '" . $this->CB_Reclutador . "' AND "
                . " (concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(REPLACE(CB_SNombre, '_', '')) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(REPLACE(CB_TNombre, '_', '')) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')),' ',trim(REPLACE(CB_AMaterno, '_', ''))) like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'SIN PATROCINADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR CA_Id = '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR CB_Celular like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR CB_ClienteID like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR CB_SolicitudID like '" . $this->Valor . "' COLLATE 'utf8_bin'"
                . " OR CB_CreditoID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR CB_ProductoID like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR (SELECT Descripcion FROM microfin.PRODUCTOSCREDITO A "
                . " WHERE A.ProducCreditoID = tsu.CB_ProductoID LIMIT 1) like '" . $this->Valor . "' COLLATE 'utf8_bin' "
                . " OR (SELECT CB_Nombre FROM TB_CentroCapCT C "
                . " WHERE C.CB_Estados like CONCAT('%', tsu.CB_EstadoID , '%') LIMIT 1) like '" . $this->Valor . "' COLLATE 'utf8_bin') "
                . ((int) $this->CB_Reclutador >= 710 && (int) $this->CB_Reclutador <= 714 ? " AND CB_ProductoID = 1100 " : "")
                . " ORDER BY CA_Id DESC LIMIT " . $this->start . ", " . $this->limit;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function read_Reg() {
        $querybyalias = "";
        if ((int) $this->CC_Estatus == 1) {
            $estatus = "IN (1, 20)";
        } else if ((int) $this->CC_Estatus == 2) {
            $estatus = "IN (2, 21)";
        } else if ((int) $this->CC_Estatus == 3) {
            $estatus = "IN (3, 22)";
        } else if ((int) $this->CC_Estatus == 4) {
            $estatus = "IN (4, 23)";
        } else if ((int) $this->CC_Estatus == 5) {
            $estatus = "IN (5, 24)";
        } else if ((int) $this->CC_Estatus == 7) {
            $estatus = "IN (7, 25)";
        } else {
            $estatus = " = 6";
        }

        //validación si el usuario es de simsa entonces solo se muestra el producto 1100
        if ($this->alias === 'SIM') {
            $querybyalias = " AND CB_ProductoID = 1100 ";
        } else if ($this->alias === 'FAH') {
            $querybyalias .= " AND CB_ProductoID = 1200 ";
        } else if ($this->alias === 'CAR') {
            $querybyalias .= " AND CB_ProductoID IN (1300, 1301)";
        }

        # Consulta para seleccionar todos los registros.
        $query = "SELECT *, (SELECT COUNT(CA_Id) FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CC_Estatus " . $estatus . " AND CB_Reclutador = " . $this->CB_Reclutador . ") as total, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'NO PATROCINADO') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'NO RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioOV, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_ClienteID), '0') as UsuarioCT, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_CorreoE = tsu.CB_Correo AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCorreo, "
                . " coalesce((SELECT CA_Id FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = tsu.CB_Celular AND tsu.CB_ProductoID NOT IN ('2002', '4000')), '0') as ValCelular, "
                . " coalesce((SELECT COUNT(*) c FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE CB_Celular = tsu.CB_Celular AND CC_Estatus " . $estatus . " "
                . " GROUP BY CB_Celular HAVING c > 1), 0) as ValDup, "
                . " coalesce((SELECT CC_FirmFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 ORDER BY doc.CA_Id DESC LIMIT 1), '-1') as DocFirma, "
                . " coalesce((SELECT CC_ExpFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE tsu.CB_SolicitudID = doc.CA_SolUni_Id AND tsu.CB_ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 ORDER BY doc.CA_Id DESC LIMIT 1), '-1') as FechaExpDoc, "
                . " coalesce((SELECT MontoAutorizado FROM microfin." . $this->SOLICITUDCREDITO . " as sol "
                . " WHERE tsu.CB_SolicitudID = sol.SolicitudCreditoID AND tsu.CB_ClienteID = sol.ClienteID), 0) as MontoAutorizado "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu "
                . " WHERE CC_Estatus " . $estatus //. " AND cast(CB_Reclutador as unsigned) = cast(" . $this->CB_Reclutador . " as unsigned) "
                . ($querybyalias)
                . " ORDER BY CA_Id DESC LIMIT " . $this->start . ", " . $this->limit;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        //$stmt->bindParam(":CB_Reclutador", $this->CB_Reclutador);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer un registro.
     */

    function read_one_Info() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CA_Id = '" . $this->CA_Id . "' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer las aprobadas para kubik
     */

    function obtenerAprob() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT A.CA_Id AS 'No', MID(A.CB_FechaModi,1,7) AS 'Mes', "
                . " A.CB_ActividadEco AS 'Ocupacion', A.CB_Genero AS 'Genero', "
                . " YEAR(Now())-YEAR(A.CB_FNacimiento) AS 'Edad', "
                . " A.CB_ColoniaDes AS 'Colonia', A.CB_MunicipioDes AS 'Municipio', "
                . " A.CB_Correo AS 'Correo', A.CB_Celular AS 'Telefono', A.CB_Observaciones "
                . " as Observaciones"
                . " FROM microfin.TB_SolicitudUnicaCT A"
                . " WHERE (A.CB_ProductoID=4000) AND (A.CC_Estatus=4) "
                . " ORDER BY CA_ID DESC;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer las rechazadas para kubik
     */

    function obtenerRecha() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT A.CA_Id AS 'No', "
                . " MID(A.CB_FechaModi,1,7) AS 'Mes', "
                . " A.CB_ActividadEco AS 'Ocupacion', "
                . " A.CB_Genero AS 'Genero', "
                . " YEAR(Now()) - YEAR(A.CB_FNacimiento) AS 'Edad', "
                . " A.CB_ColoniaDes AS 'Colonia', "
                . " A.CB_MunicipioDes AS 'Municipio', A.CB_Correo AS 'Correo', A.CB_Celular AS 'Telefono', "
                . " A.CB_Observaciones AS 'MotivoRechazo' "
                . " FROM microfin.TB_SolicitudUnicaCT A "
                . " WHERE A.CB_ProductoID = 4000 AND A.CC_Estatus = 5  "
                . " ORDER BY CA_ID DESC; ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer las canceladas para kubik
     */

    function obtenerCance() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT A.CA_Id AS 'No', "
                . " MID(A.CB_FechaModi,1,7) AS 'Mes', "
                . " A.CB_ActividadEco AS 'Ocupacion', "
                . " A.CB_Genero AS 'Genero', "
                . " YEAR(Now()) - YEAR(A.CB_FNacimiento) AS 'Edad', "
                . " A.CB_ColoniaDes AS 'Colonia', "
                . " A.CB_MunicipioDes AS 'Municipio',A.CB_Correo AS 'Correo', A.CB_Celular AS 'Telefono', "
                . " A.CB_Observaciones AS 'MotivoRechazo' "
                . " FROM microfin.TB_SolicitudUnicaCT A "
                . " WHERE A.CB_ProductoID = 4000 AND A.CC_Estatus IN (6,7) "
                . " ORDER BY CA_ID DESC;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer un registro.
     */

    function read_one() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT *, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Patrocinador), 'NO PATROCINADO') as PatNombre, "
                . " coalesce((SELECT CB_Nombre FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'NO RECLUTADOR') as RecNombre, "
                . " coalesce((SELECT CA_Id FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CA_Id < '" . $this->CA_Id . "' AND CC_Estatus = '" . $this->CC_Estatus . "' "
                . " ORDER BY CA_Id DESC LIMIT 1), '0') as Siguiente, "
                . " coalesce((SELECT CA_Id FROM microfin.TB_SolicitudUnicaEME "
                . " WHERE CA_Id > '" . $this->CA_Id . "' AND CC_Estatus = '" . $this->CC_Estatus . "' "
                . " LIMIT 1), '0') as Anterior, "
                . " (SELECT CC_EstatusVeri FROM TB_Verificacion WHERE CA_Id = '" . $this->CA_Id . "') AS 'EstatusVeri', "
                . " (SELECT CB_Obs2 FROM TB_Verificacion WHERE CA_Id = '" . $this->CA_Id . "') AS 'AreaVeri', "
                . " coalesce((SELECT NombreInstit FROM microfin." . $this->INSTITNOMINA
                . " WHERE InstitNominaID = tsu.CB_InstitNominaID), 'SIN EMPRESA') AS 'NombreInstit', "
                . " coalesce((SELECT Domicilio FROM microfin." . $this->INSTITNOMINA
                . " WHERE InstitNominaID = tsu.CB_InstitNominaID), 'SIN DOMICILIO') AS 'Domicilio', "
                . " coalesce((SELECT Descripcion FROM microfin." . $this->PRODUCTOSCREDITO
                . " WHERE ProducCreditoID = tsu.CB_ProductoID), 'SIN PRODUCTO') AS 'ProductoCredito', "
                . " coalesce((SELECT MontoAutorizado FROM microfin." . $this->SOLICITUDCREDITO . " as sol "
                . " WHERE tsu.CB_SolicitudID = sol.SolicitudCreditoID AND tsu.CB_ClienteID = sol.ClienteID), 0) as MontoAutorizado "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME . " tsu"
                . " WHERE CA_Id = '" . $this->CA_Id . "' ";
        if ($this->CC_Estatus == 3) {
            $query .= " AND CC_Estatus in (3, 12, 22)";
        } else if ($this->CC_Estatus == 1) {
            $query .= " AND CC_Estatus in (1, 20)";
        } else if ($this->CC_Estatus == 2) {
            $query .= " AND CC_Estatus in (2, 21)";
        } else {
            $query .= " AND CC_Estatus = " . $this->CC_Estatus;
        }
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        $this->CA_Id = $row['CA_Id'];
        $this->CB_ClienteID = $row['CB_ClienteID'];
        $this->CB_SolicitudID = $row['CB_SolicitudID'];
        $this->CB_SolicitudEsta = $row['CB_SolicitudEsta'];
        $this->CB_CreditoID = $row['CB_CreditoID'];
        $this->CB_PNombre = $row['CB_PNombre'];
        $this->CB_SNombre = $row['CB_SNombre'];
        $this->CB_TNombre = $row['CB_TNombre'];
        $this->CB_APaterno = $row['CB_APaterno'];
        $this->CB_AMaterno = $row['CB_AMaterno'];
        $this->CB_Celular = $row['CB_Celular'];
        $this->CB_Correo = $row['CB_Correo'];
        $this->CB_ProductoID = $row['CB_ProductoID'];
        $this->CB_Tasa = $row['CB_Tasa'];
        $this->CB_Monto = $row['CB_Monto'];
        $this->CB_Plazo = $row['CB_Plazo'];
        $this->CB_ActividadEco = $row['CB_ActividadEco'];
        $this->CB_AntiguedadLab = $row['CB_AntiguedadLab'];
        $this->CB_Ingreso = $row['CB_Ingreso'];
        $this->CB_Egreso = $row['CB_Egreso'];
        $this->CB_GradoEscolarID = $row['CB_GradoEscolarID'];
        $this->CB_DependientesEco = $row['CB_DependientesEco'];
        $this->CB_TDC = $row['CB_TDC'];
        $this->CB_NumeroTarjeta = $row['CB_NumeroTarjeta'];
        $this->CB_Hipotecario = $row['CB_Hipotecario'];
        $this->CB_Automotriz = $row['CB_Automotriz'];
        $this->CB_INEAn = $row['CB_INEAn'];
        $this->CB_INERe = $row['CB_INERe'];
        $this->CB_INENum = $row['CB_INENum'];
        $this->CB_INEEmi = $row['CB_INEEmi'];
        $this->CB_INEVig = $row['CB_INEVig'];
        $this->CB_Comprobante = $row['CB_Comprobante'];
        $this->CB_Genero = $row['CB_Genero'];
        $this->CB_Titulo = $row['CB_Titulo'];
        $this->CB_FNacimiento = $row['CB_FNacimiento'];
        $this->CB_CURP = $row['CB_CURP'];
        $this->CB_RFC = $row['CB_RFC'];
        $this->CB_RFCOficial = $row['CB_RFCOficial'];
        $this->CB_LNacimiento = $row['CB_LNacimiento'];
        $this->CB_ENacimiento = $row['CB_ENacimiento'];
        $this->CB_EsMismaDir = $row['CB_EsMismaDir'];
        $this->CB_Calle = $row['CB_Calle'];
        $this->CB_Numero = $row['CB_Numero'];
        $this->CB_Numero_Int = $row['CB_Numero_Int'];
        $this->CB_ColoniaID = $row['CB_ColoniaID'];
        $this->CB_ColoniaDes = $row['CB_ColoniaDes'];
        $this->CB_CP = $row['CB_CP'];
        $this->CB_MunicipioID = $row['CB_MunicipioID'];
        $this->CB_MunicipioDes = $row['CB_MunicipioDes'];
        $this->CB_EstadoID = $row['CB_EstadoID'];
        $this->CB_EstadoDes = $row['CB_EstadoDes'];
        $this->CB_FechaAlta = $row['CB_FechaAlta'];
        $this->CB_FechaModi = $row['CB_FechaModi'];
        $this->CB_Reclutador = $row['CB_Reclutador'];
        $this->CB_Patrocinador = $row['CB_Patrocinador'];
        $this->CB_Observaciones = $row['CB_Observaciones'];
        $this->CC_Estatus = $row['CC_Estatus'];
        $this->CB_TelefonoAval = $row['CB_TelefonoAval'];
        $this->CB_AvalID = $row['CB_AvalID'];
        $this->CB_Frecuencia = $row['CB_Frecuencia'];
        $this->CB_AntiguedadLabM = $row['CB_AntiguedadLabM'];
        $this->CB_InstitNominaID = $row['CB_InstitNominaID'];
        $this->CB_InstitNominaID = $row['CB_InstitNominaID'];
        $this->CB_TelefonoCasa = $row['CB_TelefonoCasa'];
        $this->CB_EntreCalle1 = $row['CB_EntreCalle1'];
        $this->CB_EntreCalle2 = $row['CB_EntreCalle2'];
        $this->CB_PagoDomicilio = $row['CB_PagoDomicilio'];
        /** Datos complementarios convive   * */
        $this->CK_prospectosID = $row['CK_prospectosID'];
        $this->CK_type_housing = $row['CK_type_housing'];
        $this->CK_year_living = $row['CK_year_living'];
        $this->CK_referred_name = $row['CK_referred_name'];
        $this->CK_why_unemployed = $row['CK_why_unemployed'];
        $this->CK_why_need_loan = $row['CK_why_need_loan'];
        $this->CK_job = $row['CK_job'];
        $this->CK_company_name = $row['CK_company_name'];
        $this->CK_company_phone = $row['CK_company_phone'];
        $this->CK_salary = $row['CK_salary'];
        $this->CK_address_company = $row['CK_address_company'];
        $this->CK_status_credit_bureau = $row['CK_status_credit_bureau'];
        $this->CK_proof_payroll = $row['CK_proof_payroll'];
        $this->RecNombre = $row['RecNombre'];
        $this->PatNombre = $row['PatNombre'];
        $this->Siguiente = $row['Siguiente'];
        $this->Anterior = $row['Anterior'];
        $this->EstatusVeri = $row['EstatusVeri'];
        $this->AreaVeri = $row['AreaVeri'];
        $this->NombreInstit = $row['NombreInstit'];
        $this->Domicilio = $row['Domicilio'];
        $this->ProductoCredito = $row['ProductoCredito'];
        $this->MontoAutorizado = $row['MontoAutorizado'];
        $this->CB_NoEmpleado = $row['CB_NoEmpleado'];

        $this->GiroID = $row['GiroID'];
        $this->RazonSocial = $row['RazonSocial'];
        $this->NombreNegocio = $row['NombreNegocio'];
        $this->CalleNegocio = $row['CalleNegocio'];
        $this->NumeroNegocio = $row['NumeroNegocio'];
        $this->CP_Negocio = $row['CP_Negocio'];
        $this->MunicipioNegocio = $row['MunicipioNegocio'];
        $this->EstadoNegocio = $row['EstadoNegocio'];
        $this->D1 = $row['D1'];
        $this->D2 = $row['D2'];

        $this->ComprobanteNegocio = $row['ComprobanteNegocio'];
    }

    /*
     * Función para buscar todas las empresas que son parte de un mismo cliente
     */

    function search_emp() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . " FROM microfin." . $this->INSTITNOMINA
                . " WHERE ClienteID = (SELECT ClienteID FROM microfin." . $this->INSTITNOMINA . " WHERE InstitNominaID = '" . $this->CB_InstitNominaID . "')";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetchAll();

        return $row;
    }

    /*
     * Función para leer un registro.
     */

    function read_ref() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . " FROM microfin." . $this->TB_SolicitudUnicaRefCT
                . " WHERE CB_CteCel = '" . $this->CB_Celular . "' "
                . " AND CB_Consecutivo = '" . $this->Consecutivo . "' "
                . " ORDER BY CA_Id LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        if ($stmt->execute()) {
            # get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            # set values to object properties
            $this->NombresRef = isset($row['CB_Nombres']) ? $row['CB_Nombres'] : '';
            $this->ApellidosRef = isset($row['CB_Apellidos']) ? $row['CB_Apellidos'] : '';
            $this->TelefonoRef = isset($row['CB_Telefono']) ? $row['CB_Telefono'] : '';
        } else {
            $this->NombresRef = '';
            $this->ApellidosRef = '';
            $this->TelefonoRef = '';
        }
    }

    /*
     * Función para leer un registro, para la renovación
     */

    function read_one4Renov() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT * "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' ORDER BY CA_Id DESC LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $result = $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        $this->CA_Id = $row['CA_Id'];
        $this->CB_ClienteID = $row['CB_ClienteID'];
        $this->CB_SolicitudID = $row['CB_SolicitudID'];
        $this->CB_CreditoID = $row['CB_CreditoID'];
        $this->CB_PNombre = $row['CB_PNombre'];
        $this->CB_SNombre = $row['CB_SNombre'];
        $this->CB_TNombre = $row['CB_TNombre'];
        $this->CB_APaterno = $row['CB_APaterno'];
        $this->CB_AMaterno = $row['CB_AMaterno'];
        $this->CB_Celular = $row['CB_Celular'];
        $this->CB_Correo = $row['CB_Correo'];
        $this->CB_ProductoID = $row['CB_ProductoID'];
        $this->CB_Tasa = $row['CB_Tasa'];
        $this->CB_Monto = $row['CB_Monto'];
        $this->CB_Plazo = $row['CB_Plazo'];
        $this->CB_ActividadEco = $row['CB_ActividadEco'];
        $this->CB_AntiguedadLab = $row['CB_AntiguedadLab'];
        $this->CB_Ingreso = $row['CB_Ingreso'];
        $this->CB_Egreso = $row['CB_Egreso'];
        $this->CB_GradoEscolarID = $row['CB_GradoEscolarID'];
        $this->CB_DependientesEco = $row['CB_DependientesEco'];
        $this->CB_TDC = $row['CB_TDC'];
        $this->CB_NumeroTarjeta = $row['CB_NumeroTarjeta'];
        $this->CB_Hipotecario = $row['CB_Hipotecario'];
        $this->CB_Automotriz = $row['CB_Automotriz'];
        $this->CB_INEAn = $row['CB_INEAn'];
        $this->CB_INERe = $row['CB_INERe'];
        $this->CB_INENum = $row['CB_INENum'];
        $this->CB_INEEmi = $row['CB_INEEmi'];
        $this->CB_INEVig = $row['CB_INEVig'];
        $this->CB_Comprobante = $row['CB_Comprobante'];
        $this->CB_Genero = $row['CB_Genero'];
        $this->CB_Titulo = $row['CB_Titulo'];
        $this->CB_FNacimiento = $row['CB_FNacimiento'];
        $this->CB_CURP = $row['CB_CURP'];
        $this->CB_RFC = $row['CB_RFC'];
        $this->CB_RFCOficial = $row['CB_RFCOficial'];
        $this->CB_LNacimiento = $row['CB_LNacimiento'];
        $this->CB_ENacimiento = $row['CB_ENacimiento'];
        $this->CB_EsMismaDir = $row['CB_EsMismaDir'];
        $this->CB_Calle = $row['CB_Calle'];
        $this->CB_Numero = $row['CB_Numero'];
        $this->CB_ColoniaID = $row['CB_ColoniaID'];
        $this->CB_ColoniaDes = $row['CB_ColoniaDes'];
        $this->CB_CP = $row['CB_CP'];
        $this->CB_MunicipioID = $row['CB_MunicipioID'];
        $this->CB_MunicipioDes = $row['CB_MunicipioDes'];
        $this->CB_EstadoID = $row['CB_EstadoID'];
        $this->CB_EstadoDes = $row['CB_EstadoDes'];
        $this->CB_FechaAlta = $row['CB_FechaAlta'];
        $this->CB_FechaModi = $row['CB_FechaModi'];
        $this->CB_Reclutador = $row['CB_Reclutador'];
        $this->CB_Patrocinador = $row['CB_Patrocinador'];
        $this->CB_Observaciones = $row['CB_Observaciones'];
        $this->CC_Estatus = $row['CC_Estatus'];

        return $result;
    }

    /*
     * Función para leer un registro, para la renovación
     */

    function read_one4RenovList() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CA_Id "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' ORDER BY CA_Id DESC LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $result = $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        $this->CA_Id = $row['CA_Id'];

        return $result;
    }

    function readxRegional() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT "
                . " coalesce(tsu.CB_Reclutador, '0000') as CB_Reclutador, "
                . " coalesce((SELECT CB_Nombre FROM TB_RelacionCT "
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 'SIN RECLUTADOR') as Nombre, "
                . " coalesce((SELECT CC_Estatus FROM TB_RegistroAppCT "
                . " WHERE CB_ClienteID = tsu.CB_Reclutador), 1) as Estatus, "
                . " SUM(if(tsu.CC_Estatus IN (1,2,3), 1, 0)) as Revision, "
                . " SUM(if(tsu.CC_Estatus IN (4), 1, 0)) as Aprobadas, "
                . " SUM(if(tsu.CC_Estatus IN (5), 1, 0)) as Rechazadas, "
                . " SUM(if(tsu.CC_Estatus IN (6, 7), 1, 0)) as NoObjetivas, "
                . " COUNT(CA_Id) as Total "
                . " FROM TB_SolicitudUnicaCT tsu "
                . " WHERE if(tsu.CC_Estatus IN (1,2,3), CB_FechaAlta, CB_FechaModi) BETWEEN '" . $this->fini . "' AND '" . $this->ffin . "' "
                . "	 AND CB_EstadoID " . $this->sqlvalor1 . " AND CC_Estatus IN ('1', '2', '3', '4', '5', '6', '7') "
                . " GROUP BY CB_Reclutador;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # bind params    
        # $stmt->bindParam(":CC_Estatus", $this->CC_Estatus);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    function readxRegionalS() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT  "
                . " '0000' as CB_Reclutador, "
                . " 'SIN IDENTIFICAR' as Nombre, "
                . " '1' as Estatus, "
                . "  ifnull(SUM(if(ts.CC_Estatus IN ('A', 'I', 'L'), 1, 0)), 0) as Revision, "
                . "  ifnull(SUM(if(ts.CC_Estatus IN ('D'), 1, 0)), 0) as Aprobadas, "
                . "  ifnull(SUM(if(ts.CC_Estatus IN ('R', 'C'), 1, 0)), 0) as Rechazadas, "
                . "  0 as NoObjetivas, "
                . " COUNT(CA_Id) as Total "
                . " FROM TB_SolicitudesCT ts "
                . " WHERE CB_ProductoID between 2000 and 2002 "
                . " 	 AND CB_FechaActual BETWEEN '" . $this->fini . "' AND '" . $this->ffin . "' "
                . " 	 AND CB_EstadoID " . $this->sqlvalor1 . " AND CC_Estatus IN ('I', 'L', 'A', 'D', 'R', 'C') "
                . " 	 AND CA_Id not IN (SELECT CB_SolicitudID "
                . " 	 FROM TB_SolicitudUnicaCT);";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # bind params    
        # $stmt->bindParam(":CC_Estatus", $this->CC_Estatus);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer un registro, para la renovación
     */

    function read_one4Renov2() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT COALESCE((SELECT CC_CreEsta FROM TB_RelacionCT "
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' ORDER BY CA_Id DESC LIMIT 1),-1) as CC_CreEsta";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        $this->CC_CreEsta = $row['CC_CreEsta'];
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update() {
        // update query
        $query = "UPDATE " . $this->TB_SolicitudUnicaCT
                . " SET "
                . " CB_ClienteID = '" . $this->CB_ClienteID . "', CB_SolicitudID = '" . $this->CB_SolicitudID . "', CB_CreditoID = '" . $this->CB_CreditoID . "', "
                . " CB_PNombre = '" . $this->CB_PNombre . "', CB_SNombre = '" . $this->CB_SNombre . "', CB_TNombre = '" . $this->CB_TNombre . "', "
                . " CB_APaterno = '" . $this->CB_APaterno . "', CB_AMaterno = '" . $this->CB_AMaterno . "', CB_Celular = '" . $this->CB_Celular . "', "
                . " CB_Correo = '" . $this->CB_Correo . "', CB_ProductoID = '" . $this->CB_ProductoID . "', CB_Monto = '" . $this->CB_Monto . "', "
                . " CB_Plazo = '" . $this->CB_Plazo . "', CB_ActividadEco = '" . $this->CB_ActividadEco . "', CB_AntiguedadLab = '" . $this->CB_AntiguedadLab . "', "
                . " CB_Ingreso = '" . $this->CB_Ingreso . "', CB_Egreso = '" . $this->CB_Egreso . "', CB_GradoEscolarID = '" . $this->CB_GradoEscolarID . "', "
                . " CB_DependientesEco = '" . $this->CB_DependientesEco . "', CB_TDC = '" . $this->CB_TDC . "', CB_NumeroTarjeta = '" . $this->CB_NumeroTarjeta . "', "
                . " CB_Hipotecario = '" . $this->CB_Hipotecario . "', CB_Automotriz = '" . $this->CB_Automotriz . "', CB_INEAn = '" . $this->CB_INEAn . "', "
                . " CB_INERe = '" . $this->CB_INERe . "', CB_INENum = '" . $this->CB_INENum . "', CB_INEEmi = '" . $this->CB_INEEmi . "', "
                . " CB_INEVig = '" . $this->CB_INEVig . "', CB_Comprobante = '" . $this->CB_Comprobante . "', CB_Genero = '" . $this->CB_Genero . "', "
                . " CB_Titulo = '" . $this->CB_Titulo . "', CB_FNacimiento = '" . $this->CB_FNacimiento . "', CB_CURP = '" . $this->CB_CURP . "', "
                . " CB_RFC = '" . $this->CB_RFC . "', CB_RFCOficial = '" . $this->CB_RFCOficial . "', CB_LNacimiento = '" . $this->CB_LNacimiento . "', "
                . " CB_ENacimiento = '" . $this->CB_ENacimiento . "', CB_EsMismaDir = '" . $this->CB_EsMismaDir . "', CB_Calle = '" . $this->CB_Calle . "', "
                . " CB_Numero = '" . $this->CB_Numero . "', CB_ColoniaID = '" . $this->CB_ColoniaID . "', CB_ColoniaDes = '" . $this->CB_ColoniaDes . "', "
                . " CB_CP = '" . $this->CB_CP . "', CB_MunicipioID = '" . $this->CB_MunicipioID . "', CB_MunicipioDes = '" . $this->CB_MunicipioDes . "', "
                . " CB_EstadoID = '" . $this->CB_EstadoID . "', CB_EstadoDes = '" . $this->CB_EstadoDes . "', CB_FechaAlta = '" . $this->CB_FechaAlta . "', "
                . " CB_FechaModi = '" . $this->CB_FechaModi . "', CB_Patrocinador = '" . $this->CB_Patrocinador . "', CC_Estatus = '" . $this->CC_Estatus . "' "
                . " WHERE CA_Id = '" . $this->CA_Id . "' ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_nombre() {
        // update query
        $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME
                . " SET "
                . " CB_PNombre = '" . $this->CB_PNombre . "', "
                . " CB_SNombre = '" . $this->CB_SNombre . "', "
                . " CB_TNombre = '" . $this->CB_TNombre . "', "
                . " CB_APaterno = '" . $this->CB_APaterno . "', "
                . " CB_AMaterno = '" . $this->CB_AMaterno . "' "
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_cp() {
        // update query
        $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME
                . " SET "
                . " CB_CP = '" . $this->CB_CP . "', "
                . " CB_ColoniaID = '" . $this->CB_ColoniaID . "', "
                . " CB_ColoniaDes = '" . $this->CB_ColoniaDes . "', "
                . " CB_MunicipioID = '" . $this->CB_MunicipioID . "', "
                . " CB_MunicipioDes = '" . $this->CB_MunicipioDes . "', "
                . " CB_EstadoID = '" . $this->CB_EstadoID . "', "
                . " CB_EstadoDes = '" . $this->CB_EstadoDes . "' "
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_producto() {
        // update query
        $query = "UPDATE " . $this->TB_SolicitudUnicaCT
                . " SET "
                . " CB_Tasa = '" . $this->CB_Tasa . "', "
                . " CB_Monto = '" . $this->CB_Monto . "', "
                . " CB_Plazo = '" . $this->CB_Plazo . "', "
                . " CB_Frecuencia = '" . $this->CB_Frecuencia . "' "
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_producto_microfin() {
        // update query
        $query = "UPDATE microfin.SOLICITUDCREDITO "
                . " SET MontoSolici = '" . $this->CB_Monto . "', "
                . " TasaFija = '" . $this->CB_Tasa . "', "
                . " NumAmortizacion = '" . $this->CB_Plazo . "', "
                . " FrecuenciaCap = '" . $this->CB_Frecuencia . "', "
                . " FrecuenciaInt = '" . $this->CB_Frecuencia . "' "
                . " WHERE SolicitudCreditoID = '" . $this->CB_SolicitudID . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_campo() {

        try {
            // update query
            $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME
                    . " SET " . $this->Campo . " = '" . $this->Valor . "' "
                    . " WHERE CA_Id = '" . $this->CA_Id . "'";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute the query
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return false;
        }
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_rec_pac() {
        // update query
        $query = "UPDATE " . $this->TB_RelacionCT
                . " SET "
                . " CB_Reclutador = '" . $this->CB_Reclutador . "', "
                . " CB_MadreID = '" . $this->CB_Patrocinador . "' "
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_ref_cel() {
        // update query
        $query = "UPDATE " . $this->TB_SolicitudUnicaRefCT
                . " SET "
                . " CB_CteCel = '" . $this->Valor . "' "
                . " WHERE CB_CteCel = '" . $this->ValorAnt . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_obs() {
        // update query
        $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME
                . " SET "
                . " CB_Observaciones = '" . $this->CB_Observaciones . "' "
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_status() {
        // update query
        $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME
                . " SET "
                . ($this->CC_Estatus == '2' ? "CB_SolicitudID = 0, " : "")
                . " CB_FechaModi = '" . $this->CB_FechaModi . "', "
                /* . " CB_Observaciones = concat(CB_Observaciones, ' - ', :CB_Observaciones), " */
                . " CC_Estatus = '" . $this->CC_Estatus . "' "
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        /* $stmt->bindParam(":CB_Observaciones", $this->CB_Observaciones); */
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro
     * @return boolean
     */
    function update_paided() {
        // update query
        $query = "UPDATE " . $this->TB_SolicitudUnicaCT . " A "
                . " SET "
                . " A.CB_FechaModi = '" . $this->CB_FechaModi . "', "
                . " A.CC_Estatus = '1' "
                . " WHERE A.CB_Celular =  "
                . " (SELECT CB_Telefono FROM TB_OrdenPagoCT WHERE CB_OrdenID = '" . $this->Valor . "') "
                . " AND CB_Correo = "
                . " (SELECT CB_Correo FROM TB_OrdenPagoCT WHERE CB_OrdenID = '" . $this->Valor . "') "
                . " AND CC_Estatus = 0";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Metodo para actualizar el estatus y las observaciones por el CA_Id
     */

    function update_estatus_obs() {
        #creación de la consulta
        $query = "UPDATE " . $this->TB_SolicitudUnicaCT
                . " SET CC_Estatus = '" . $this->CC_Estatus . "', CB_SolicitudEsta = '" . $this->CB_SolicitudEsta . "', CB_Observaciones = '" . $this->CB_Observaciones . "'"
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #se ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para actualizar diferentes registros en la tabla
     */

    function updateDatosSolicitud() {
        #creación de la consulta
        $query = "UPDATE " . $this->TB_SolicitudUnicaCT . " SET"
                . " CB_FechaModi = '" . $this->CB_FechaModi . "', CB_CURP = '" . $this->CB_CURP . "', CB_RFC = '" . $this->CB_RFC . "', "
                . " CB_RFCOficial = '" . $this->CB_RFCOficial . "', CB_ClienteID = '" . $this->CB_ClienteID . "'"
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #se ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para actualizar el estado de una solicitud a cancelada o rechazada
     */

    function updateCYR() {
        #creación del update
        $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME . " SET"
                . " CB_FechaModi = '" . $this->CB_FechaModi . "', CC_Estatus = '" . $this->CC_Estatus . "', CB_Observaciones = '" . $this->CB_Observaciones . "', "
                . " CB_SolicitudEsta = '" . $this->CB_SolicitudEsta . "'"
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #se ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función que actualiza el CC_Estatus y CB_SolicitudEsta de la solicitud
     */

    function updateEstatus() {
        #creación del update
        $query = "UPDATE microfin." . $this->TB_SolicitudUnicaEME . " SET"
                . " CC_Estatus = '" . $this->CC_Estatus . "', CB_SolicitudEsta = '" . $this->CB_SolicitudEsta . "'"
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #se ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para leer un registro.
     */

    function getSoliActs() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT COUNT(*) AS Activos "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' "
                . " AND CC_Estatus IN (1, 2, 3)";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['Activos'];
    }

    function getCelularxSolicitud() {
        #consulta
        $query = "SELECT CB_Celular"
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CA_Id = '" . $this->CA_Id . "'";
        #preparacion de la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function validaSol() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CA_Id, CC_Estatus "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->Valor . "' "
                . " AND cast(CC_Estatus as decimal) < 4 "
                . " ORDER BY  CA_Id, cast(CC_Estatus as decimal) DESC LIMIT 1;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function validaCteSol() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CA_Id, CC_Estatus, CB_FechaAlta, "
                . " CB_PNombre, CB_SNombre,CB_TNombre,CB_APaterno,CB_AMaterno, "
                . " concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(REPLACE(CB_SNombre, '_', '')) > 0, ' ', ''), "
                . " trim(REPLACE(CB_SNombre, '_', '')), if(length(REPLACE(CB_TNombre, '_', '')) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . " trim(REPLACE(CB_APaterno, '_', '')),' ',trim(REPLACE(CB_AMaterno, '_', ''))) as Nombre, "
                . " CB_Celular as Celular, CB_Correo as Correo, CB_ProductoID as ProductoID, "
                . " (SELECT if(Estatus != 'P', 0 , 1) FROM microfin.CREDITOS WHERE ClienteID = A.CB_ClienteID "
                . " AND ProductoCreditoID IN (5000, 5001, 5002, 5003, 5004, 5005) "
                . " ORDER BY CreditoID DESC LIMIT 1) as Renovacion "
                . " FROM " . $this->TB_SolicitudUnicaCT . " A "
                . " WHERE CB_Celular = '" . $this->Valor . "' "
                . " ORDER BY CA_Id DESC LIMIT 1;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function valiRenovCR() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT count(*) as cc "
                . " FROM microfin.CREDITOS "
                . " WHERE CreditoID = "
                . " (SELECT CB_CreditoID "
                . " FROM  TB_SolicitudUnicaCT "
                . " WHERE CB_Celular = '" . $this->Valor . "' "
                . " AND CB_ProductoID between 5000 and 5999 "
                . " AND CC_Estatus = 4 "
                . " ORDER BY CA_Id DESC LIMIT 1) "
                . " AND Estatus = 'P';";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getCCName() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_Nombre as cc "
                . " FROM " . $this->TB_CentroCapCT
                . " WHERE CB_Estados like "
                . " (SELECT CONCAT('%', "
                . " (SELECT  CB_EstadoID "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' "
                . " ORDER BY CA_Id LIMIT 1), '%'))";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getCteCel() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_Celular as cc "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' "
                . " ORDER BY CA_Id LIMIT 1 ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getCteId() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_ClienteID as cc "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->CB_Celular . "' "
                . " ORDER BY CA_Id LIMIT 1 ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getCteNom() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CONCAT(trim(CB_PNombre), ' ', trim(CB_SNombre), ' ', "
                . " trim(CB_TNombre), ' ', trim(CB_APaterno), ' ', trim(CB_AMaterno)) as Nombre "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' "
                . " ORDER BY CA_Id LIMIT 1 ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['Nombre'];
    }

    /*
     * Función para leer un registro.
     */

    function getPatroCel() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_CteCel as cc "
                . " FROM " . $this->TB_ProspectosCR
                . " WHERE CB_Celular = "
                . " (SELECT CB_Celular "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' "
                . " ORDER BY CA_Id LIMIT 1) ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            # get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            # set values to object properties
            return $row['cc'];
        } else {
            return 0;
        }
    }

    /*
     * Función para leer un registro.
     */

    function getPatroCelByCel() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_CteCel as cc "
                . " FROM " . $this->TB_ProspectosCR
                . " WHERE CB_Celular = '" . $this->CB_Celular . "' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getPatroCelById() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_Telefono as cc "
                . " FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = (SELECT CB_Reclutador "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_ClienteID = '" . $this->CB_ClienteID . "' "
                . " ORDER BY CA_Id LIMIT 1) "
                . " ORDER BY CA_Id LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function validaRegional() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_Tipo as cc "
                . " FROM " . $this->TB_RegistroAppCT
                . " WHERE CB_NoCelular = '" . $this->Patronum . "' ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function validaPatroID() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT ifnull( "
                . " (SELECT CB_ClienteID "
                . " FROM " . $this->TB_SolicitudUnicaCT
                . " WHERE CB_Celular = '" . $this->Patronum . "' "
                . " AND CC_Estatus = '4' "
                . " ORDER BY CA_Id DESC LIMIT 1), "
                . " ifnull((SELECT CB_ClienteID  "
                . " FROM " . $this->TB_RelacionCT
                . " WHERE CB_Telefono = '" . $this->Patronum . "' ), '_')) as cc";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function validaPatro() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT CB_ClienteID as cc "
                . " FROM microfin." . $this->TB_SolicitudUnicaCT
                . " WHERE "
                . " EXISTS( "
                . " SELECT CuentaAhoID FROM microfin.CUENTASAHO "
                . " WHERE (ClienteID = "
                . " (SELECT ClienteID FROM microfin.CLIENTES "
                . " WHERE TelefonoCelular = '" . $this->Patronum . "'"
                . " ORDER BY ClienteID DESC LIMIT 1) "
                . " AND TipoCuentaID = 3)) "
                . " AND CB_Celular = '" . $this->Patronum . "' "
                . " AND CB_ProductoID in (5000, 5001, 5002, 5003, 5004, 5005) "
                . " LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getUsrNomById() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT CB_Nombre as cc "
                . " FROM " . $this->TB_RelacionCT
                . " WHERE CB_ClienteID = '" . $this->CC_Usuario . "' "
                . " ORDER BY CA_Id LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /*
     * Función para leer un registro.
     */

    function getPatroNomByCel() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT if(( "
                . " SELECT count(*) "
                . "  FROM microfin." . $this->TB_SolicitudUnicaCT
                . "  WHERE CB_Celular = '" . $this->Patronum . "' "
                . "  ORDER BY CA_Id DESC LIMIT 1) > 0, "
                . "  (SELECT concat(trim(REPLACE(CB_PNombre, '_', '')), if(length(REPLACE(CB_SNombre, '_', '')) > 0, ' ', ''),  "
                . "  trim(REPLACE(CB_SNombre, '_', '')), if(length(REPLACE(CB_TNombre, '_', '')) > 0, ' ', ''),trim(REPLACE(CB_TNombre, '_', '')), if(length(CB_APaterno) > 0, ' ', ''), "
                . "  trim(REPLACE(CB_APaterno, '_', '')),' ',trim(REPLACE(CB_AMaterno, '_', ''))) as Nombre  "
                . "  FROM microfin." . $this->TB_SolicitudUnicaCT
                . "  WHERE CB_Celular = '" . $this->Patronum . "'  "
                . "  ORDER BY CA_Id DESC LIMIT 1),  "
                . "  if((SELECT count(*)  "
                . "  FROM microfin." . $this->TB_RelacionCT
                . "  WHERE CB_Telefono = '" . $this->Patronum . "' "
                . "  ORDER BY CA_Id DESC LIMIT 1) > 0, "
                . "  (SELECT CB_Nombre "
                . "  FROM microfin." . $this->TB_RelacionCT
                . "  WHERE CB_Telefono = '" . $this->Patronum . "' "
                . "  ORDER BY CA_Id DESC LIMIT 1), 'PATROCINADOR SIN REGISTRO')) as cc ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    /**
     * Función para obtener la solicitud del cliente 
     */
    function searchByCelOrCor() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT CC_Estatus as cc "
                . " FROM microfin.TB_SolicitudUnicaCT "
                . " WHERE ((CB_Celular = '" . $this->CB_Celular . "' AND CB_Correo = '" . $this->CB_Correo . "') "
                . " OR (CB_Celular = '" . $this->CB_Celular . "' OR CB_Correo = '" . $this->CB_Correo . "')) "
                . " AND CB_ProductoID = '6000' "
                . " ORDER BY CA_Id DESC LIMIT 1;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row['cc'];
    }

    function searchSolixName() {
        #consulta a ejecutar
        $query = "SELECT * "
                . " FROM " . $this->TB_SolicitudUnicaEME
                . " WHERE CB_PNombre LIKE CONCAT('" . $this->CB_PNombre . "', '%') AND CB_SNombre LIKE CONCAT('" . $this->CB_SNombre . "', '%') AND CB_APaterno LIKE "
                . " CONCAT('" . $this->CB_APaterno . "', '%') AND CB_AMaterno LIKE CONCAT('" . $this->CB_AMaterno . "', '%') LIMIT 1";
        #se prepara la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /* CONSULTAS PARA LA TABLA DE SOLICITUD UNICA DE MICROFIN */

    function searchEstatusByCelOrCor() {
        # Consulta para seleccionar todos los registros.
        $query = " SELECT CC_Estatus as cc "
                . " FROM microfin." . $this->TB_SolicitudUnicaEME
                . " WHERE ((CB_Celular = '" . $this->CB_Celular . "' AND CB_Correo = '" . $this->CB_Correo . "') "
                . " OR (CB_Celular = '" . $this->CB_Celular . "' OR CB_Correo = '" . $this->CB_Correo . "')) "
                . " AND CB_ProductoID = '1100' "
                . " ORDER BY CA_Id DESC LIMIT 1;";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        return $row;
    }

    /*
     * Función para buscar los clientes por producto
     */

    function searchClixProd() {
        # Consulta para seleccionar todos los registros.
        # Preparación de la consulta.
        $query = "SELECT c.ClienteID, c.NombreCompleto as Nombre, c.TelefonoCelular FROM clientes c, users u WHERE u.celular = c.TelefonoCelular AND u.empresaID = 67 AND (c.NombreCompleto LIKE '".$this->Valor."' OR c.TelefonoCelular LIKE '".$this->Valor."');";
        // echo $query;
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para buscar las tarjetas del cliente
     */

     function searchTarjxCli() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT b.NombreCompleto, a.TarjetaDebID FROM tarjetadebito a, clientes b WHERE a.ClienteID = b.ClienteID and a.Estatus IN (6,7) and a.ClienteID IN (SELECT ClienteID FROM clientes WHERE NombreCompleto LIKE '".$this->Valor."')";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function readSoliContrato() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT *,"
                . " coalesce((SELECT CC_FirmFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE sol.SolicitudCreditoID = doc.CA_SolUni_Id AND sol.ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as DocFirma, "
                . " coalesce((SELECT CC_ExpFMX FROM microfin." . $this->TB_DocumentsEME . " as doc "
                . " WHERE sol.SolicitudCreditoID = doc.CA_SolUni_Id AND sol.ClienteID = doc.CB_CustID AND doc.CB_TypeDoc = 22 "
                . " ORDER BY doc.CA_Id DESC LIMIT 1), -1) as FechaExpDoc "
                . " FROM microfin." . $this->SOLICITUDCREDITO . " sol "
                . " WHERE CC_Estatus = 4";

        //validación si el usuario es de simsa entonces solo se muestra el producto 1100
        if ((int) $this->CB_Reclutador >= 710 && (int) $this->CB_Reclutador <= 714) {
            $query .= " AND CB_ProductoID = 1100 ";
        }

        $query .= ((int) $this->CC_Estatus >= 4 ? " AND MONTH(CB_FechaModi) = MONTH(CURRENT_DATE()) AND YEAR(CB_FechaModi) = YEAR(CURRENT_DATE()) " : "");
        $query .= " ORDER BY CA_Id DESC LIMIT " . $this->start . ", " . $this->limit;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }

}
