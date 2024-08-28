<?php

/**
 * Clase para definir el objeto de configuración general
 */
class ConfiguracionGral {
    # Conección a la base de datos y nombre de la tabla.
    private $conn;
    private $table_name = "TB_ConfiguracionGral";
    # Propiedades del objeto
    public $action;
    public $token;
    public $mensaje;
    public $CA_Id;
    public $CB_Clave;
    public $CB_Campo;
    public $CB_Valor;
    public $CC_Estatus;
    public $CB_FechaActualizacion;
    public $CB_ClienteID;

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
            FROM " . $this->table_name . " 
            WHERE 
            (SELECT COUNT(*) FROM TB_RegistroAppCT WHERE CB_Token = ? 
        COLLATE utf8_bin AND CB_Token != '') > 0";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # bind id of product to be updated     
        $stmt->bindParam(1, $this->token);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt;
    }
    
    /*
     * Método usado para buscar los datos de usuarios y contraseas para buro y quien es quien
     * Trae los ultimos registros ingresados a la tabla
    */
    function getDatosWebServices()
    {
        # Consulta para seleccionar todos los registros.
        $query = "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'UPROBC' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'PBUROC' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'PPROBC' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'UPRUBC' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'PPRUBC' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'USUQEQ' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'PASQEQ' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'UWEBSA' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'PWEBSA' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'UWEBSB' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'UWEBSB' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'PWEBSB' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'USRPLD' order by CA_Id desc limit 1) UNION "
                . "(SELECT CB_Clave, CB_Campo, CB_Valor, CB_FechaActualizacion, CB_ClienteID FROM " . $this->table_name . " WHERE CB_Clave = 'USRPAS' order by CA_Id desc limit 1)";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /*
     * Metodo que retorna el usuario y contraseña para quien es quien y buró de crédito
     */
    function getCredenciales()
    {
        $query = "SELECT CB_Valor"
                . " FROM " . $this->table_name
                . " WHERE CB_Clave = '" . $this->CB_Clave . "'"
                . " ORDER BY CA_Id DESC LIMIT 1";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);       
        # Ejecución de la consulta.
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /*
     * Función para buscar las credenciales para Firmamex
     */
    function getCredencialesFirmamex() {
        #creación de la consulta
        $query = "SELECT CB_Valor FROM TB_ConfiguracionGral WHERE CB_Clave = '" . $this->CB_Clave . "'";
        # Preparación de la consulta.
        $stmt = $this->conn->prepare($query);
        # Ejecución de la consulta.
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /*
     * Metodo para crear
    */
    function create()
    {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name
                . " SET "
                . "CB_Clave = '" . $this->CB_Clave . "', CB_Campo = '" . $this->CB_Campo . "', CB_Valor = '" . $this->CB_Valor . "', "
                . " CC_Estatus = '" . $this->CC_Estatus . "', CB_FechaActualizacion = '" . $this->CB_FechaActualizacion . "', "
                . " CB_ClienteID = '" . $this->CB_ClienteID . "'";

        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /*
     * Metodo para actualizar
     */
    function update() {
        // query to update
        $query = "UPDATE " . $this->table_name
                . " SET"
                . " CB_Clave = '" . $this->CB_Clave . "', CB_Campo = '" . $this->CB_Campo . "', CB_Valor = '" . $this->CB_Valor . "', "
                . " CC_Estatus = '" . $this->CC_Estatus . "', CB_FechaActualizacion = '" . $this->CB_FechaActualizacion . "', "
                . " CB_ClienteID = '" . $this->CB_ClienteID . "'"
                . " WHERE CB_Clave = '" . $this->CB_Clave . "'";

        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}