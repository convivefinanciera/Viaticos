<?php

/**
 * Clase para definir la tabla de TarjetaDebito en el safi bancking
 */
class TarjetaDebito {
    # Conección a la base de datos y nombre de la tabla.
    private $conn;
    private $table_name1 = "TARJETADEBITO";
    private $table_name2 = "CREDITOS";
    private $table_name3 = "ESTATUSTD";
    private $table_name4 = "TARDEBEVENTOSTD";
    private $table_name5 = "CUENTASAHO";
    private $table_name6 = "TIPOSCUENTAS";
    private $table_name7 = "TB_Control_TDC";
    private $CLIENTES = "CLIENTES";
    private $TB_CODIGOS_PROSA = "TB_CODIGOS_PROSA";
    private $TARDEBBITACORAMOVS = "TARDEBBITACORAMOVS";
    # Propiedades del objeto
    public $TarjetaDebID;
    public $LoteDebitoID;
    public $FechaRegistro;
    public $FechaVencimiento;
    public $FechaActivacion;
    public $Estatus;
    public $ClienteID;
    public $CuentaAhoID;
    public $FechaBloqueo;
    public $MotivoBloqueo;
    public $FechaCancelacion;	
    public $MotivoCancelacion;
    public $NIP;
    public $NombreTarjeta;
    public $Relacion;
    public $SucursalID;
    public $TipoTarjetaDebID;
    public $NoDispoDiario;
    public $NoDispoMes;
    public $MontoDispoDiario;
    public $MontoDispoMes;
    public $NoConsulaSaldoMes;	
    public $NoCompraDiario;
    public $NoCompraMes;
    public $MontoCompraDiario;
    public $MontoCompraMes;
    public $TipoCobro;
    public $PagoComAnual;
    public $FPagoComAnual;
    public $EmpresaID;
    public $Usuario;
    public $FechaActual;
    public $DireccionIP;
    public $ProgramaID;
    public $Sucursal;
    public $NumTransaccion;
    # Propiedades extras
    public $valor;
    public $FechaInicio;
    public $FechaFin;

    /**
     * Constructor con la conección a la base de datos.
     * @param type $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /*
     * función para buscar por el número de tarjeta
     */
    function search() {
        #Creación de la consulta
        $query = "SELECT *"
                . " FROM " . $this->table_name1
                . " WHERE TarjetaDebID = :TarjetaDebID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los datos a la consulta   
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    /*
     * Metodo para activar y asignar una tarjeta
     */
    function activarAsignarTarjeta() {
        #Creación del update
        $query = "UPDATE " . $this->table_name1
                . " SET FechaActivacion = :FechaActivacion, Estatus = :Estatus,"
                . " ClienteID = :ClienteID, CuentaAhoID = :CuentaAhoID,"
                . " NombreTarjeta = :NombreTarjeta, Relacion = :Relacion, TipoCobro = :TipoCobro"
                . " WHERE TarjetaDebID = :TarjetaDebID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":FechaActivacion", $this->FechaActivacion);
        $stmt->bindParam(":Estatus", $this->Estatus);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        $stmt->bindParam(":CuentaAhoID", $this->CuentaAhoID);
        $stmt->bindParam(":NombreTarjeta", $this->NombreTarjeta);
        $stmt->bindParam(":Relacion", $this->Relacion);
        $stmt->bindParam(":TipoCobro", $this->TipoCobro);
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        #Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /*
     * Metodo para cancelar una tarjeta
     */
    function cancelarTarjeta() {
        #Creación del update
        $query = "UPDATE " . $this->table_name1
                . " SET Estatus = :Estatus, FechaCancelacion = :FechaCancelacion, MotivoCancelacion = :MotivoCancelacion"
                . " WHERE TarjetaDebID = :TarjetaDebID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        $stmt->bindParam(":Estatus", $this->Estatus);
        $stmt->bindParam(":FechaCancelacion", $this->FechaCancelacion);
        $stmt->bindParam(":MotivoCancelacion", $this->MotivoCancelacion);
        #Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /*
     * Metodo para limpiar las tarjetas activadas y asignadas
     */
    function limpiarTarjeta() {
        #Creación del update
        $query = "UPDATE " . $this->table_name1
                . " SET FechaActivacion = :FechaActivacion, Estatus = :Estatus, ClienteID = :ClienteID, CuentaAhoID = :CuentaAhoID,"
                . " NombreTarjeta = :NombreTarjeta, Relacion = :Relacion, TipoCobro = :TipoCobro"
                . " WHERE TarjetaDebID = :TarjetaDebID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":FechaActivacion", $this->FechaActivacion);
        $stmt->bindParam(":Estatus", $this->Estatus);
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        $stmt->bindParam(":CuentaAhoID", $this->CuentaAhoID);
        $stmt->bindParam(":NombreTarjeta", $this->NombreTarjeta);
        $stmt->bindParam(":Relacion", $this->Relacion);
        $stmt->bindParam(":TipoCobro", $this->TipoCobro);
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        #Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /*
     * Functión para saber si existe en la base de datos la tarjeta
    */
    function buscarTarjetaxCliente() {
        #Creación de la consulta
        $query = "SELECT TarjetaDebID"
                . " FROM " . $this->table_name1
                . " WHERE ClienteID = :ClienteID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los datos a la consulta   
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    /*
     * Función que busca todas las tarjetas del cliente
     */
    function buscarTarjetasxCli() {
        #Creación de la consulta
        $query = "SELECT * "
                . " FROM " . $this->table_name1
                . " WHERE ClienteID = :ClienteID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los datos a la consulta   
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
    
    /*
     * Regresa todas las tarjetas junto con el estatus que el cliente tiene
     */
    function getTarjetasEstatus() {
        #Creación del update
        $query = "SELECT t.TarjetaDebID, e.Descripcion"
                . " FROM " . $this->table_name1 . " as t,"
                . " " . $this->table_name3 . " as e"
                . " WHERE t.Estatus = e.EstatusID AND t.ClienteID = :ClienteID ORDER BY t.TarjetaDebID";        
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los valores
        $stmt->bindParam(":ClienteID", $this->ClienteID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
    
    /*
     * Metodo que trae el numero de tarjeta, cliente y credito
     */
    function getClienteCredito() {
        #Creación de la consulta
        $query = "SELECT td.TarjetaDebID, td.ClienteID, MAX(c.CreditoID)"
                . " FROM " . $this->table_name1 . " AS td"
                . " INNER JOIN " . $this->table_name2 . " AS c"
                . " ON td.ClienteID = c.ClienteID"
                . " WHERE td.TarjetaDebID = :TarjetaDebID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los datos a la consulta   
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    /*
     * Metodo para buscar el clienteID por un numero de tarjeta
     */
    function getClientexTarjeta() {
        $query = "SELECT ClienteID"
                . " FROM " . $this->table_name1
                . " WHERE TarjetaDebID = :TarjetaDebID";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los datos a la consulta   
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    function getTarjetaxValor() {
        $query = "SELECT TarjetaDebID, ClienteID"
                . " FROM " . $this->table_name1
                . " WHERE TarjetaDebID = :valor OR ClienteID = :valor"
                . " AND ESTATUS = 7";
        #Preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #Se pasan los datos a la consulta   
        $stmt->bindParam(":valor", $this->valor);
        #Ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    /*
     * Metodo para buscar el estatus de la tarjeta dada
     */
    function consultaEstatusTarjeta() {
        #estructura de la consulta
        $query = "SELECT te.Descripcion AS Estatus, t.ClienteID AS NumeroCliente, t.NombreTarjeta AS NombreCliente, c.CuentaAhoID AS CuentaAho,"
                . " tc.Descripcion AS TipoCuenta, c.Saldo AS Saldo, c.SaldoDispon AS SaldoDisponible, c.SaldoBloq AS SaldoBloqueado,"
                . " ctdc.CB_ProductoID AS NombreProducto"
                . " FROM " . $this->table_name1 . " AS t"
                    . " INNER JOIN " .$this->table_name4 . " AS te"
                        . " ON te.TipoEvenTDID = t.Estatus"
                    . " LEFT JOIN " . $this->table_name5 . " AS c"
                        . " ON t.CuentaAhoID = c.CuentaAhoID"
                    . " LEFT JOIN " . $this->table_name6 . " AS tc"
                        . " ON c.TipoCuentaID = tc.TipoCuentaID"
                    . " INNER JOIN " . $this->table_name7 . " AS ctdc"
                        . " ON t.TarjetaDebID = ctdc.CB_TDC"
                . " WHERE t.TarjetaDebID = :TarjetaDebID";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #se pasan los datos para la consulta
        $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
    
    /*
     * Metodo para buscar los movimientos hechos por el cliente, registrados por safi
     */
    function searchMovTDC() {
        #consulta
        $query = "SELECT A.TarjetaDebID AS TDC, A.MontoOpe AS Cargo, 0 AS Abono, D.Descripcion AS TipoMovimiento, E.Descripcion AS EstatusOperacion, A.FechaHrOpe AS FechaOperacion, "
                . "A.TerminalID AS NumeroTerminal, A.NombreUbicaTer AS Comercio "
                . "FROM " . $this->CLIENTES . " C, " . $this->table_name1 . " B, " . $this->TB_CODIGOS_PROSA . " E, " . $this->TARDEBBITACORAMOVS . " A "
                . "LEFT JOIN " . $this->TB_CODIGOS_PROSA . " D ON D.TipoOperacionID = A.TipoOperacionID "
                . "WHERE A.TarjetaDebID = B.TarjetaDebID AND E.TipoOperacionID = A.Estatus AND C.ClienteID = B.ClienteID AND D.NatMovimiento = 'C' AND ";
        if($this->TarjetaDebID != "" && $this->ClienteID != "" && $this->FechaInicio != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " AND C.ClienteID = " . $this->ClienteID . " AND A.FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        } else if($this->TarjetaDebID != "" && $this->ClienteID != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " AND C.ClienteID = " . $this->ClienteID . " ";            
        } else if($this->TarjetaDebID != "" && $this->FechaInicio != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " AND FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        } else if($this->ClienteID != "" && $this->FechaInicio != ""){
            $query .= "C.ClienteID = " . $this->ClienteID . " AND FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        } else if($this->TarjetaDebID != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " ";
        } else if($this->ClienteID != "") {
            $query .= "C.ClienteID = " . $this->ClienteID . " ";
        } else {
            $query .= "FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        }
        $query .= "UNION "
                . "SELECT A.TarjetaDebID AS TDC, 0 AS Cargo, A.MontoOpe AS Abono, D.Descripcion AS TipoMovimiento, E.Descripcion AS EstatusOperacion, A.FechaHrOpe AS FechaOperacion, "
                . "A.TerminalID AS NumeroTerminal, A.NombreUbicaTer AS Comercio "
                . "FROM " . $this->CLIENTES . " C, " . $this->table_name1 . " B, " . $this->TB_CODIGOS_PROSA . " E, " . $this->TARDEBBITACORAMOVS . " A "
                . "LEFT JOIN " . $this->TB_CODIGOS_PROSA . " D ON D.TipoOperacionID = A.TipoOperacionID "
                . "WHERE A.TarjetaDebID = B.TarjetaDebID AND E.TipoOperacionID = A.Estatus AND C.ClienteID = B.ClienteID AND D.NatMovimiento = 'A' AND ";
        if($this->TarjetaDebID != "" && $this->ClienteID != "" && $this->FechaInicio != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " AND C.ClienteID = " . $this->ClienteID . " AND A.FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        } else if($this->TarjetaDebID != "" && $this->ClienteID != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " AND C.ClienteID = " . $this->ClienteID . " ";            
        } else if($this->TarjetaDebID != "" && $this->FechaInicio != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " AND FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        } else if($this->ClienteID != "" && $this->FechaInicio != ""){
            $query .= "C.ClienteID = " . $this->ClienteID . " AND FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        } else if($this->TarjetaDebID != "") {
            $query .= "B.TarjetaDebID = " . $this->TarjetaDebID . " ";
        } else if($this->ClienteID != "") {
            $query .= "C.ClienteID = " . $this->ClienteID . " ";
        } else {
            $query .= "FechaHrOpe BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "' ";
        }
        $query .= "ORDER BY " . $this->valor;       
        
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
    
    function searchTDCBetween2Dates() {
        #consulta
        $query = "SELECT TarjetaDebID"
                . " FROM TARJETADEBITO"
                . " WHERE Estatus > :Estatus AND FechaActivacion BETWEEN '" . $this->FechaInicio . "' AND '" . $this->FechaFin . "'";
        #preparación de la consulta
        $stmt = $this->conn->prepare($query);
        #se pasan los datos para la consulta
        $stmt->bindParam(":Estatus", $this->Estatus);
        #ejecución de la consulta
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
}