<?php
    /*
     * Clase para definir la tabla de BITACORATARDEB en el safi bancking
     */
    class BitacoraTarDeb {
        # Conección a la base de datos y nombre de la tabla.
        private $conn;
        private $table_name = "BITACORATARDEB";
        # Propiedades del objeto
        public $TarjetaDebID;
        public $TipoEvenTDID;
        public $MotivoBloqID;
        public $DescripAdicio;
        public $Fecha;
        public $NombreCliente;
        public $EmpresaID;
        public $Usuario;
        public $FechaActual;
        public $DireccionIP;
        public $ProgramaID;
        public $Sucursal;
        public $NumTransaccion;
        
        /**
         * Constructor con la conección a la base de datos.
         * @param type $db
        */
        public function __construct($db) {
            $this->conn = $db;
        }
        
        /*
         * Metodo para crear una bitacora
         */
        function create() {
            # Creación del insert
            $query = "INSERT INTO " . $this->table_name . " SET TarjetaDebID = :TarjetaDebID, TipoEvenTDID = :TipoEvenTDID, MotivoBloqID = :MotivoBloqID, DescripAdicio = :DescripAdicio, Fecha = :Fecha,"
                    . " NombreCliente = :NombreCliente, EmpresaID = :EmpresaID, Usuario = :Usuario, FechaActual = :FechaActual, DireccionIP = :DireccionIP, ProgramaID = :ProgramaID, Sucursal = :Sucursal, NumTransaccion = :NumTransaccion";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
            $stmt->bindParam(":TipoEvenTDID", $this->TipoEvenTDID);
            $stmt->bindParam(":MotivoBloqID", $this->MotivoBloqID);
            $stmt->bindParam(":DescripAdicio", $this->DescripAdicio);
            $stmt->bindParam(":Fecha", $this->Fecha);
            $stmt->bindParam(":NombreCliente", $this->NombreCliente);
            $stmt->bindParam(":EmpresaID", $this->EmpresaID);
            $stmt->bindParam(":Usuario", $this->Usuario);
            $stmt->bindParam(":FechaActual", $this->FechaActual);
            $stmt->bindParam(":DireccionIP", $this->DireccionIP);
            $stmt->bindParam(":ProgramaID", $this->ProgramaID);
            $stmt->bindParam(":Sucursal", $this->Sucursal);
            $stmt->bindParam(":NumTransaccion", $this->NumTransaccion);
            #Ejecuta la consulta
            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
        
        function delete() {
            #creación de la consulta
            $query = "DELETE FROM " . $this->table_name
                    . " WHERE TipoEvenTDID = :TipoEvenTDID AND TarjetaDebID = :TarjetaDebID";
            #Preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":TipoEvenTDID", $this->TipoEvenTDID);
            $stmt->bindParam(":TarjetaDebID", $this->TarjetaDebID);
            #Ejecuta la consulta
            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
    }

