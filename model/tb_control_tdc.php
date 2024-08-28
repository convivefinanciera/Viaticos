<?php
    /*
     * Clase para definir la tabla de TB_Control_TDC en el safi bancking
     */
    class TB_Control_TDC {
        # Conección a la base de datos y nombre de la tabla.
        private $conn;
        private $table_name = "TB_Control_TDC";
        # Propiedades del objeto
        public $CA_Id;
        public $CB_ProductoID;
        public $CB_FolioPayware;
        public $CB_FolioMyCard;
        public $CB_TDC;
        public $CB_Terminacion;
        public $CB_Cadena;
        public $CB_ArchivoOUT;
        public $CB_ClienteID;
        public $CB_Estatus;
        public $CB_Fecha;
        public $CB_Comentarios;        
        
        /**
         * Constructor con la conección a la base de datos.
         * @param type $db
        */
        public function __construct($db) {
            $this->conn = $db;
        }
        
        /*
         * Metodo para buscar el estatus deacuerdo a un número de tarjeta dada
         */
        function buscarEstatus() {
            #Creación del update
            $query = "SELECT CB_Estatus"
                    . " FROM " . $this->table_name
                    . " WHERE CB_TDC = :CB_TDC";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CB_TDC", $this->CB_TDC);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        
        /*
         * Metodo para buscar el número de tarjeta por el folio
         */
        function buscarTarjetaxFolio() {
            #Creación de la consulta
            $query = "SELECT CB_TDC"
                    . " FROM " . $this->table_name
                    . " WHERE CB_FolioMyCard = :CB_FolioMyCard";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CB_FolioMyCard", $this->CB_FolioMyCard);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        
        /*
         * Función para buscar los datos ment¿diante el CB_TDC
         */
        function buscarFolioxTarjeta() {
            #creación de la consulta
            $query = "SELECT *"
                    . " FROM " . $this->table_name
                    . " WHERE CB_TDC = :CB_TDC";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CB_TDC", $this->CB_TDC);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        
        /*
         * Metodo para sacar el producto mediante el CB_FolioMyCard
         */
        function getProducto() {
            #Creación del update
            $query = "SELECT CB_ProductoID"
                    . " FROM " . $this->table_name
                    . " WHERE CB_FolioMyCard = :CB_FolioMyCard";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CB_FolioMyCard", $this->CB_FolioMyCard);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        
        /*
         * Método para sacar el productoid por una tarjeta dada
         */
        function getProductoxTarjeta() {
            #Creación del update
            $query = "SELECT CB_ProductoID"
                    . " FROM " . $this->table_name
                    . " WHERE CB_TDC = :CB_TDC";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CB_TDC", $this->CB_TDC);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        
        /*
         * Metodo para actualizar el estatus y comentarios de una tarjeta
         */
        function update() {
            #Creación del update
            $query = "UPDATE " . $this->table_name
                    . " SET"
                    . " CB_Estatus = :CB_Estatus,"
                    . " CB_Comentarios = :CB_Comentarios"
                    . " WHERE CB_TDC = :CB_TDC";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CB_TDC", $this->CB_TDC);
            $stmt->bindParam(":CB_Estatus", $this->CB_Estatus);
            $stmt->bindParam(":CB_Comentarios", $this->CB_Comentarios);
            #Ejecución de la consulta
            if($stmt->execute()) {
                return true;
            }
            return false;
        }
    }