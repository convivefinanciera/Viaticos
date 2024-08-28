<?php

/*
 * Clase para definir la tabla de CLIENTES en el safi bancking
 */

class Clientes {
    # Conección a la base de datos y nombre de la tabla.

    private $conn;
    private $CLIENTES = "CLIENTES";
    private $SOLICITUDUNICA = "TB_SolicitudUnicaCT";
    private $REGISTROAPP = "TB_RegistroAppCT";
    private $RELACION = "TB_RelacionCT";
    # Propiedades del objeto
    public $ClienteID;
    public $EmpresaID;
    public $SucursalOrigen;
    public $TipoPersona;
    public $Titulo;
    public $PrimerNombre;
    public $SegundoNombre;
    public $TercerNombre;
    public $ApellidoPaterno;
    public $ApellidoMaterno;
    public $FechaNacimiento;
    public $CURP;
    public $Nacion;
    public $PaisResidencia;
    public $GrupoEmpresarial;
    public $RazonSocial;
    public $TipoSociedadID;
    public $Fax;
    public $Correo;
    public $RFC;
    public $RFCpm;
    public $RFCOficial;
    public $SectorGeneral;
    public $ActividadBancoMX;
    public $ActividadINEGI;
    public $ActividadFR;
    public $ActividadFOMURID;
    public $SectorEconomico;
    public $Sexo;
    public $EstadoCivil;
    public $LugarNacimiento;
    public $EstadoID;
    public $OcupacionID;
    public $LugardeTrabajo;
    public $Puesto;
    public $DomicilioTrabajo;
    public $TelTrabajo;
    public $AntiguedadTra;
    public $TelefonoCelular;
    public $Telefono;
    public $Clasificacion;
    public $MotivoApertura;
    public $PagaISR;
    public $PagaIVA;
    public $PagaIDE;
    public $NivelRiesgo;
    public $PromotoInicial;
    public $PromotorActual;
    public $FechaAlta;
    public $Estatus;
    public $NombreCompleto;
    public $TipoInactiva;
    public $MotivoInactiva;
    public $EsMenorEdad;
    public $CorpRelacionado;
    public $CalificaCredito;
    public $RegistroHacienda;
    public $FechaBaja;
    public $Observaciones;
    public $NoEmpleado;
    public $TipoEmpleado;
    public $ExtTelefonoPart;
    public $ExtTelefonoTrab;
    public $EjecutivoCap;
    public $PromotorExtInv;
    public $TipoPuesto;
    public $FechaIniTrabajo;
    public $UbicaNegocioID;
    public $FechaConstitucion;
    public $FEA;
    public $PaisFEA;
    public $PaisConstitucionID;
    public $CorreoAlterPM;
    public $NombreNotario;
    public $NumNotario;
    public $InscripcionReg;
    public $EscrituraPubPM;
    public $SoloNombres;
    public $SoloApellidos;
    public $FechaSigEvalPLD;
    public $Usuario;
    public $FechaActual;
    public $DireccionIP;
    public $ProgramaID;
    public $Sucursal;
    public $NumTransaccion;
    public $Vendedor;
    public $Edad;
    public $Campo;
    public $Valor;

    /**
     * Constructor con la conección a la base de datos.
     * @param type $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /*
     * Función para leer los registros de la base de datos.
     */

    function read() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT *
            FROM " . $this->CLIENTES;
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }
    
    /*
     * Función para buscar el registro de un cliente
     */
    
    function search_one() {
        #creación de la consulta
        $query = "SELECT * FROM " . $this->CLIENTES . " WHERE ClienteID = :ClienteID";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # bind params    
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para leer un registro.
     */

    function read_one() {
        # Consulta para seleccionar todos los registros.
        $query = "SELECT *, "
                . " IF(exists(select CB_Nombre from microfin.TB_RelacionCT "    //  finanhnj_CrediTuDBp
                . " WHERE CB_ClienteID = "
                . " (SELECT CB_Reclutador FROM microfin.TB_SolicitudUnicaCT "   //  finanhnj_CrediTuDBp
                . " WHERE CB_ClienteID = A.ClienteID ORDER BY CA_Id DESC LIMIT 1)), "
                . " (select CB_Nombre from microfin.TB_RelacionCT "             //  finanhnj_CrediTuDBp
                . " WHERE CB_ClienteID = "
                . " (SELECT CB_Reclutador FROM microfin.TB_SolicitudUnicaCT "
                . " WHERE CB_ClienteID = A.ClienteID ORDER BY CA_Id DESC LIMIT 1)), "
                . " IF(exists(select NombreCompleto from microfin.CLIENTES "
                . " WHERE ClienteID = "
                . " (SELECT CB_Reclutador FROM microfin.TB_SolicitudUnicaCT "   //  finanhnj_CrediTuDBp
                . " WHERE CB_ClienteID = A.ClienteID ORDER BY CA_Id DESC LIMIT 1)), "
                . " (select NombreCompleto from microfin.CLIENTES "
                . " WHERE ClienteID = "
                . " (SELECT CB_Reclutador FROM microfin.TB_SolicitudUnicaCT "   //  finanhnj_CrediTuDBp
                . " WHERE CB_ClienteID = A.ClienteID ORDER BY CA_Id DESC LIMIT 1)), 'Registro WEB')) as Vendedor, "
                . " (TIMESTAMPDIFF(YEAR, A.FechaNacimiento, CURDATE())) as Edad "
                . " FROM microfin." . $this->CLIENTES . " A "
                . " WHERE ClienteID = :ClienteID ";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # bind params    
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        # Ejecución de la consulta.
        $stmt->execute();
        # get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        # set values to object properties
        $this->ClienteID = $row['ClienteID'];
        $this->FechaAlta = $row['FechaAlta'];
        $this->Estatus = $row['Estatus'];
        $this->FechaNacimiento = $row['FechaNacimiento'];
        $this->CURP = $row['CURP'];
        $this->RFC = $row['RFC'];
        $this->Sexo = $row['Sexo'];
        $this->TelefonoCelular = $row['TelefonoCelular'];
        $this->Correo = $row['Correo'];
        $this->Titulo = $row['Titulo'];
        $this->NombreCompleto = $row['NombreCompleto'];
        $this->Vendedor = $row['Vendedor'];
        $this->Edad = $row['Edad'];
    }

    function search() {
        #creación de la consulta
        $query = "SELECT *"
                . " FROM " . $this->CLIENTES
                . " WHERE CURP = :CURP";
        #prepparación de la consulta
        $stmt = $this->conn->prepare($query);
        #se pasan los datos
        $stmt->bindParam(":CURP", $this->CURP);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }    

    /*
     * Metodo para obtener el NombreCompleto de un cliente dado el clienteID
     */

    function getNombreCompleto() {
        #Creación del update
        $query = "SELECT NombreCompleto"
                . " FROM " . $this->CLIENTES
                . " WHERE ClienteID = :ClienteID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para obtener el último id de la tabla
     */

    function getSiguienteClienteId() {
        #creación de la consulta
        $query = "SELECT MAX(ClienteID) + 1 as ID"
                . " FROM " . $this->CLIENTES;
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #ejecuación de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }  

    /*
     * Función para obtener el último id de la tabla
     */

    function getSiguienteTransaccion() {
        #creación de la consulta
        $query = "SELECT MAX(NumTransaccion) + 1 as NumTransaccion"
                . " FROM " . $this->CLIENTES;
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #ejecuación de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Función para hacer el insert de un registro nuevo
     */

    function create() {
        #creación de la consulta
        $query = "INSERT INTO " . $this->CLIENTES . " ("
                . " ClienteID, NumTransaccion, FechaActual, FechaAlta, EmpresaID, SucursalOrigen, TipoPersona, Nacion, PaisResidencia, SectorGeneral, ActividadBancoMX,"
                . " ActividadINEGI, ActividadFR, ActividadFOMURID, SectorEconomico, EstadoCivil, OcupacionID, AntiguedadTra, Clasificacion, MotivoApertura, PagaISR,"
                . " PagaIVA, PagaIDE, NivelRiesgo, PromotorInicial, PromotorActual, Estatus, EsMenorEdad, CalificaCredito, RegistroHacienda, PaisConstitucionID, FechaBaja,"
                . " FechaIniTrabajo, FechaConstitucion, Usuario, DireccionIP, ProgramaID, Sucursal, LugardeTrabajo, Puesto, TelTrabajo, Sexo, Titulo, PrimerNombre,"
                . " SegundoNombre, TercerNombre,  ApellidoPaterno, ApellidoMaterno, FechaNacimiento, CURP, RFC, RFCOficial, LugarNacimiento, EstadoID, TelefonoCelular,"
                . " SoloNombres, SoloApellidos, NombreCompleto, Correo, DomicilioTrabajo)"
                . " VALUES ("
                . " :ClienteID, :NumTransaccion, :FechaActual, :FechaAlta, :EmpresaID, :SucursalOrigen, :TipoPersona, :Nacion, :PaisResidencia, :SectorGeneral, :ActividadBancoMX,"
                . " :ActividadINEGI, :ActividadFR, :ActividadFOMURID, :SectorEconomico, :EstadoCivil, :OcupacionID, :AntiguedadTra, :Clasificacion, :MotivoApertura, :PagaISR,"
                . " :PagaIVA, :PagaIDE, :NivelRiesgo, :PromotorInicial, :PromotorActual, :Estatus, :EsMenorEdad, :CalificaCredito, :RegistroHacienda, :PaisConstitucionID, :FechaBaja,"
                . " :FechaIniTrabajo, :FechaConstitucion, :Usuario, :DireccionIP, :ProgramaID, :Sucursal, :LugardeTrabajo, :Puesto, :TelTrabajo, :Sexo, :Titulo, :PrimerNombre,"
                . " :SegundoNombre, :TercerNombre, :ApellidoPaterno, :ApellidoMaterno, :FechaNacimiento, :CURP, :RFC, :RFCOficial, :LugarNacimiento, :EstadoID, :TelefonoCelular,"
                . " :SoloNombres, :SoloApellidos, :NombreCompleto, :Correo, :DomicilioTrabajo)";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        $stmt->bindParam(":NumTransaccion", $this->NumTransaccion);
        $stmt->bindParam(":FechaActual", $this->FechaActual);
        $stmt->bindParam(":FechaAlta", $this->FechaAlta);
        $stmt->bindParam(":EmpresaID", $this->EmpresaID);
        $stmt->bindParam(":SucursalOrigen", $this->SucursalOrigen);
        $stmt->bindParam(":TipoPersona", $this->TipoPersona);
        $stmt->bindParam(":Nacion", $this->Nacion);
        $stmt->bindParam(":PaisResidencia", $this->PaisResidencia);
        $stmt->bindParam(":SectorGeneral", $this->SectorGeneral);
        $stmt->bindParam(":ActividadBancoMX", $this->ActividadBancoMX);
        $stmt->bindParam(":ActividadINEGI", $this->ActividadINEGI);
        $stmt->bindParam(":ActividadFR", $this->ActividadFR);
        $stmt->bindParam(":ActividadFOMURID", $this->ActividadFOMURID);
        $stmt->bindParam(":SectorEconomico", $this->SectorEconomico);
        $stmt->bindParam(":EstadoCivil", $this->EstadoCivil);
        $stmt->bindParam(":OcupacionID", $this->OcupacionID);
        $stmt->bindParam(":AntiguedadTra", $this->AntiguedadTra);
        $stmt->bindParam(":Clasificacion", $this->Clasificacion);
        $stmt->bindParam(":MotivoApertura", $this->MotivoApertura);
        $stmt->bindParam(":PagaISR", $this->PagaISR);
        $stmt->bindParam(":PagaIVA", $this->PagaIVA);
        $stmt->bindParam(":PagaIDE", $this->PagaIDE);
        $stmt->bindParam(":NivelRiesgo", $this->NivelRiesgo);
        $stmt->bindParam(":PromotorInicial", $this->PromotorInicial);
        $stmt->bindParam(":PromotorActual", $this->PromotorActual);
        $stmt->bindParam(":Estatus", $this->Estatus);
        $stmt->bindParam(":EsMenorEdad", $this->EsMenorEdad);
        $stmt->bindParam(":CalificaCredito", $this->CalificaCredito);
        $stmt->bindParam(":RegistroHacienda", $this->RegistroHacienda);
        $stmt->bindParam(":PaisConstitucionID", $this->PaisConstitucionID);
        $stmt->bindParam(":FechaBaja", $this->FechaBaja);
        $stmt->bindParam(":FechaIniTrabajo", $this->FechaIniTrabajo);
        $stmt->bindParam(":FechaConstitucion", $this->FechaConstitucion);
        $stmt->bindParam(":Usuario", $this->Usuario);
        $stmt->bindParam(":DireccionIP", $this->DireccionIP);
        $stmt->bindParam(":ProgramaID", $this->ProgramaID);
        $stmt->bindParam(":Sucursal", $this->Sucursal);
        $stmt->bindParam(":LugardeTrabajo", $this->LugardeTrabajo);
        $stmt->bindParam(":Puesto", $this->Puesto);
        $stmt->bindParam(":TelTrabajo", $this->TelTrabajo);
        $stmt->bindParam(":Sexo", $this->Sexo);
        $stmt->bindParam(":Titulo", $this->Titulo);
        $stmt->bindParam(":PrimerNombre", $this->PrimerNombre);
        $stmt->bindParam(":SegundoNombre", $this->SegundoNombre);
        $stmt->bindParam(":TercerNombre", $this->TercerNombre);
        $stmt->bindParam(":ApellidoPaterno", $this->ApellidoPaterno);
        $stmt->bindParam(":ApellidoMaterno", $this->ApellidoMaterno);
        $stmt->bindParam(":FechaNacimiento", $this->FechaNacimiento);
        $stmt->bindParam(":CURP", $this->CURP);
        $stmt->bindParam(":RFC", $this->RFC);
        $stmt->bindParam(":RFCOficial", $this->RFCOficial);
        $stmt->bindParam(":LugarNacimiento", $this->LugarNacimiento);
        $stmt->bindParam(":EstadoID", $this->EstadoID);
        $stmt->bindParam(":TelefonoCelular", $this->TelefonoCelular);
        $stmt->bindParam(":SoloNombres", $this->SoloNombres);
        $stmt->bindParam(":SoloApellidos", $this->SoloApellidos);
        $stmt->bindParam(":NombreCompleto", $this->NombreCompleto);
        $stmt->bindParam(":Correo", $this->Correo);
        $stmt->bindParam(":DomicilioTrabajo", $this->DomicilioTrabajo);
        #ejecución de la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para actualizar los datos del cliente
     */

    function update() {
        #creación de la consulta
        $query = "UPDATE " . $this->CLIENTES . " SET "
                . " FechaActual = :FechaActual, Sexo = :Sexo, Titulo = :Titulo, PrimerNombre = :PrimerNombre, SegundoNombre = :SegundoNombre, TercerNombre = :TercerNombre,"
                . " ApellidoPaterno = :ApellidoPaterno, ApellidoMaterno = :ApellidoMaterno, FechaNacimiento = :FechaNacimiento, CURP = :CURP, RFC = :RFC,"
                . " RFCOficial = :RFCOficial, LugarNacimiento = :LugarNacimiento, EstadoID = :EstadoID, TelefonoCelular = :TelefonoCelular, Telefono = :TelefonoCelular,"
                . " SoloNombres = :SoloNombres, SoloApellidos = :SoloApellidos, NombreCompleto = :NombreCompleto, Correo = :Correo"
                . " WHERE ClienteID = :ClienteID";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":FechaActual", $this->FechaActual);
        $stmt->bindParam(":Sexo", $this->Sexo);
        $stmt->bindParam(":Titulo", $this->Titulo);
        $stmt->bindParam(":PrimerNombre", $this->PrimerNombre);
        $stmt->bindParam(":SegundoNombre", $this->SegundoNombre);
        $stmt->bindParam(":TercerNombre", $this->TercerNombre);
        $stmt->bindParam(":ApellidoPaterno", $this->ApellidoPaterno);
        $stmt->bindParam(":ApellidoMaterno", $this->ApellidoMaterno);
        $stmt->bindParam(":FechaNacimiento", $this->FechaNacimiento);
        $stmt->bindParam(":CURP", $this->CURP);
        $stmt->bindParam(":RFC", $this->RFC);
        $stmt->bindParam(":RFCOficial", $this->RFCOficial);
        $stmt->bindParam(":LugarNacimiento", $this->LugarNacimiento);
        $stmt->bindParam(":EstadoID", $this->EstadoID);
        $stmt->bindParam(":TelefonoCelular", $this->TelefonoCelular);
        $stmt->bindParam(":SoloNombres", $this->SoloNombres);
        $stmt->bindParam(":SoloApellidos", $this->SoloApellidos);
        $stmt->bindParam(":NombreCompleto", $this->NombreCompleto);
        $stmt->bindParam(":Correo", $this->Correo);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #ejecución de la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
/*
     * Función para actualizar los datos del cliente
     */

    function    update_fieldCE() {
        #creación de la consulta
        $query = "UPDATE microfin." . $this->CLIENTES . " SET "
                . $this->Campo . " = '" . $this->Valor . "'"
                . " WHERE ClienteID = :ClienteID ";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #ejecución de la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para actualizar los datos del cliente
     */

    function update_fieldSU() {
        #creación de la consulta
        $query = "UPDATE microfin." . $this->SOLICITUDUNICA . " SET "   //finanhnj_CrediTuDBp
                . $this->Campo . " = '" . $this->Valor . "'"
                . " WHERE CB_ClienteID = :ClienteID ";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #ejecución de la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*
     * Función para actualizar los datos del cliente
     */

    function update_fieldRA() {
        #creación de la consulta
        $query = "UPDATE microfin." . $this->REGISTROAPP . " SET "      //  finanhnj_CrediTuDBp
                . $this->Campo . " = '" . $this->Valor . "'"
                . " WHERE CB_ClienteID = :ClienteID ";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #ejecución de la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    function update_fieldRE() {
        #creación de la consulta
        $query = "UPDATE microfin." . $this->RELACION . " SET "     //  finanhnj_CrediTuDBp
                . $this->Campo . " = '" . $this->Valor . "'"
                . " WHERE CB_ClienteID = :ClienteID ";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #ejecución de la consulta
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
            $query = "UPDATE microfin." . $this->CLIENTES
                    . " SET " . $this->Campo . " = '" . $this->Valor . "' "
                    . " WHERE TelefonoCelular = '" . $this->TelefonoCelular . "' AND Correo = '" . $this->Correo . "'";
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
}
