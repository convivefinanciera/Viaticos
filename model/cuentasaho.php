<?php
    /*
     * Clase para definir la tabla de CUENTASAHO en el safi bancking
     */
    class Cuentasaho {
        # Conección a la base de datos y nombre de la tabla.
        private $conn;
        private $CUENTASAHO = "CUENTASAHO";
        # Propiedades del objeto
        public $CuentaAhoID;
        public $SucursalID;
        public $ClienteID;
        public $Clabe;
        public $MonedaID;
        public $Gat;
        public $TipoCuentaID;
        public $FechaReg;
        public $FechaApertura;
        public $UsuarioApeID;
        public $Etiqueta;
        public $UsuarioCanID;
        public $FechaCan;
        public $MotivoCan;
        public $FechaBlo;
        public $UsuarioBloID;
        public $MotivoDesbloq;
        public $FechaDesbloq;
        public $UsuarioDesbID;
        public $Saldo;
        public $SaldoDispon;
        public $SaldoBloq;
        public $SaldoSBC;
        public $SaldoIniMes;
        public $CargosMes;
        public $AbonosMes;
        public $Comisiones;
        public $SaldoProm;
        public $TaseInteres;
        public $InteresesGen;
        public $ISR;
        public $TasaISR;
        public $SaldoIniDia;
        public $CargosDia;
        public $AbonosDia;
        public $Estatus;        
        public $EstadoCta;
        public $InstitucionID;
        public $EsPrincipal;
        public $GatReal;
        public $ISRReal;
        public $TelefonoCelular;
        public $MontoDepInicial;
        public $FechaDepInicial;
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
         * Metodo para sacar la cuentaaho y el tipo dado un cliente
         */
        function getCuentaAhoID() {
            #Creación de la consulta
            $query = "SELECT CuentaAhoID, TipoCuentaID"
                    . " FROM " . $this->CUENTASAHO
                    . " WHERE ClienteID = :ClienteID AND TipoCuentaID = 7";
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
         * función para obtener la cuentaaho por clienteid y tipocuentaid
         */
        function getCuentaAho() {
            #creación de la consulta
            $query = "SELECT *"
                    . " FROM " . $this->CUENTASAHO
                    . " WHERE ClienteID = :ClienteID AND TipoCuentaID = :TipoCuentaID";
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":ClienteID", $this->ClienteID);
            $stmt->bindParam(":TipoCuentaID", $this->TipoCuentaID);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
        
        /*
         * Función para sacar el último id de la tabla
         */
        function getSiguienteAhoID() {
            #creación de la consulta
            $query = "SELECT MAX(CuentaAhoID) + 8 as CuentaAhoID"
                    . " FROM " . $this->CUENTASAHO;
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Ejecución de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
        
        /*
         * Función para sacar el último id de la tabla, es para las cuentas aho EJE tipo 7
         */
        function getSiguienteAhoIDEje() {
            #creación de la consulta
            $query = "SELECT MAX(CuentaAhoID) + 9 as CuentaAhoID"
                    . " FROM " . $this->CUENTASAHO;
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Ejecución de la consulta
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
                    . " FROM " .$this->CUENTASAHO;
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #ejecuación de la consulta
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
        
        /*
         * función para hacer el insert en la tabla
         */
        function create() {
            #creación del insert
            $query = "INSERT INTO " . $this->CUENTASAHO
                    . " (CuentaAhoID, SucursalID, ClienteID, Clabe, MonedaID, Gat, TipoCuentaID, FechaReg, Etiqueta, Saldo, SaldoDispon, SaldoBloq, SaldoSBC, SaldoIniMes, CargosMes,"
                    . " AbonosMes, Comisiones, SaldoProm, TasaInteres, InteresesGen, ISR, TasaISR, SaldoIniDia, CargosDia, AbonosDia, Estatus, EstadoCta, InstitucionID, EsPrincipal,"
                    . " GatReal, ISRReal, TelefonoCelular, MontoDepInicial, FechaDepInicial, EmpresaID, Usuario, FechaActual, DireccionIP, ProgramaID, Sucursal, NumTransaccion)"
                    . " VALUES ("
                    . " :CuentaAhoID, :SucursalID, :ClienteID, :Clabe, :MonedaID, :Gat, :TipoCuentaID, :FechaReg, :Etiqueta, :Saldo, :SaldoDispon, :SaldoBloq, :SaldoSBC, :SaldoIniMes,"
                    . " :CargosMes, :AbonosMes, :Comisiones, :SaldoProm, :TasaInteres, :InteresesGen, :ISR, :TasaISR, :SaldoIniDia, :CargosDia, :AbonosDia, :Estatus, :EstadoCta,"
                    . " :InstitucionID, :EsPrincipal, :GatReal, :ISRReal, :TelefonoCelular, :MontoDepInicial, :FechaDepInicial, :EmpresaID, :Usuario, :FechaActual, :DireccionIP,"
                    . " :ProgramaID, :Sucursal, :NumTransaccion)";
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":CuentaAhoID", $this->CuentaAhoID);
            $stmt->bindParam(":SucursalID", $this->SucursalID);
            $stmt->bindParam(":ClienteID", $this->ClienteID);
            $stmt->bindParam(":Clabe", $this->Clabe);
            $stmt->bindParam(":MonedaID", $this->MonedaID);
            $stmt->bindParam(":Gat", $this->Gat);
            $stmt->bindParam(":TipoCuentaID", $this->TipoCuentaID);
            $stmt->bindParam(":FechaReg", $this->FechaReg);
            $stmt->bindParam(":Etiqueta", $this->Etiqueta);
            $stmt->bindParam(":Saldo", $this->Saldo);
            $stmt->bindParam(":SaldoDispon", $this->SaldoDispon);
            $stmt->bindParam(":SaldoBloq", $this->SaldoBloq);
            $stmt->bindParam(":SaldoSBC", $this->SaldoSBC);
            $stmt->bindParam(":SaldoIniMes", $this->SaldoIniMes);
            $stmt->bindParam(":CargosMes", $this->CargosMes);
            $stmt->bindParam(":AbonosMes", $this->AbonosMes);
            $stmt->bindParam(":Comisiones", $this->Comisiones);
            $stmt->bindParam(":SaldoProm", $this->SaldoProm);
            $stmt->bindParam(":TasaInteres", $this->TasaInteres);
            $stmt->bindParam(":InteresesGen", $this->InteresesGen);
            $stmt->bindParam(":ISR", $this->ISR);
            $stmt->bindParam(":TasaISR", $this->TasaISR);
            $stmt->bindParam(":SaldoIniDia", $this->SaldoIniDia);
            $stmt->bindParam(":CargosDia", $this->CargosDia);
            $stmt->bindParam(":AbonosDia", $this->AbonosDia);
            $stmt->bindParam(":Estatus", $this->Estatus);
            $stmt->bindParam(":EstadoCta", $this->EstadoCta);
            $stmt->bindParam(":InstitucionID", $this->InstitucionID);
            $stmt->bindParam(":EsPrincipal", $this->EsPrincipal);
            $stmt->bindParam(":GatReal", $this->GatReal);
            $stmt->bindParam(":ISRReal", $this->ISRReal);
            $stmt->bindParam(":TelefonoCelular", $this->TelefonoCelular);
            $stmt->bindParam(":MontoDepInicial", $this->MontoDepInicial);
            $stmt->bindParam(":FechaDepInicial", $this->FechaDepInicial);
            $stmt->bindParam(":EmpresaID", $this->EmpresaID);
            $stmt->bindParam(":Usuario", $this->Usuario);
            $stmt->bindParam(":FechaActual", $this->FechaActual);
            $stmt->bindParam(":DireccionIP", $this->DireccionIP);
            $stmt->bindParam(":ProgramaID", $this->ProgramaID);
            $stmt->bindParam(":Sucursal", $this->Sucursal);
            $stmt->bindParam(":NumTransaccion", $this->NumTransaccion);
            #Ejecución de la consulta
            if($stmt->execute()) {
                return true;
            }            
            return false;
        }
        
        /*
         * función para hacer el update en la tabla
         */
        function update() {
            #creación del update
            $query = "UPDATE " . $this->CUENTASAHO . " SET"
                    . " SucursalID = :SucursalID, Clabe = :Clabe, MonedaID = :MonedaID, Gat = :Gat, FechaReg = :FechaReg, Etiqueta = :Etiqueta, Saldo = :Saldo, SaldoDispon = :SaldoDispon,"
                    . " SaldoBloq = :SaldoBloq, SaldoSBC = :SaldoSBC, SaldoIniMes = :SaldoIniMes, CargosMes = :CargosMes, AbonosMes = :AbonosMes, Comisiones = :Comisiones,"
                    . " SaldoProm = :SaldoProm, TasaInteres = :TasaInteres, InteresesGen = :InteresesGen, ISR = :ISR, TasaISR = :TasaISR, SaldoIniDia = :SaldoIniDia,"
                    . " CargosDia = :CargosDia, AbonosDia = :AbonosDia, Estatus = :Estatus, EstadoCta = :EstadoCta, InstitucionID = :InstitucionID, EsPrincipal = :EsPrincipal,"
                    . " GatReal = :GatReal, ISRReal = :ISRReal, TelefonoCelular = :TelefonoCelular, MontoDepInicial = :MontoDepInicial,FechaDepInicial = :FechaDepInicial, "
                    . " EmpresaID = :EmpresaID, Usuario = :Usuario, FechaActual = :FechaActual, DireccionIP = :DireccionIP, ProgramaID = :ProgramaID, Sucursal = :Sucursal"
                    . " WHERE CuentaAhoID = :CuentaAhoID";
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #Se pasan los valores
            $stmt->bindParam(":SucursalID", $this->SucursalID);
            $stmt->bindParam(":Clabe", $this->Clabe);
            $stmt->bindParam(":MonedaID", $this->MonedaID);
            $stmt->bindParam(":Gat", $this->Gat);
            $stmt->bindParam(":FechaReg", $this->FechaReg);
            $stmt->bindParam(":Etiqueta", $this->Etiqueta);
            $stmt->bindParam(":Saldo", $this->Saldo);
            $stmt->bindParam(":SaldoDispon", $this->SaldoDispon);
            $stmt->bindParam(":SaldoBloq", $this->SaldoBloq);
            $stmt->bindParam(":SaldoSBC", $this->SaldoSBC);
            $stmt->bindParam(":SaldoIniMes", $this->SaldoIniMes);
            $stmt->bindParam(":CargosMes", $this->CargosMes);
            $stmt->bindParam(":AbonosMes", $this->AbonosMes);
            $stmt->bindParam(":Comisiones", $this->Comisiones);
            $stmt->bindParam(":SaldoProm", $this->SaldoProm);
            $stmt->bindParam(":TasaInteres", $this->TasaInteres);
            $stmt->bindParam(":InteresesGen", $this->InteresesGen);
            $stmt->bindParam(":ISR", $this->ISR);
            $stmt->bindParam(":TasaISR", $this->TasaISR);
            $stmt->bindParam(":SaldoIniDia", $this->SaldoIniDia);
            $stmt->bindParam(":CargosDia", $this->CargosDia);
            $stmt->bindParam(":AbonosDia", $this->AbonosDia);
            $stmt->bindParam(":Estatus", $this->Estatus);
            $stmt->bindParam(":EstadoCta", $this->EstadoCta);
            $stmt->bindParam(":InstitucionID", $this->InstitucionID);
            $stmt->bindParam(":EsPrincipal", $this->EsPrincipal);
            $stmt->bindParam(":GatReal", $this->GatReal);
            $stmt->bindParam(":ISRReal", $this->ISRReal);
            $stmt->bindParam(":TelefonoCelular", $this->TelefonoCelular);
            $stmt->bindParam(":MontoDepInicial", $this->MontoDepInicial);
            $stmt->bindParam(":FechaDepInicial", $this->FechaDepInicial);
            $stmt->bindParam(":EmpresaID", $this->EmpresaID);
            $stmt->bindParam(":Usuario", $this->Usuario);
            $stmt->bindParam(":FechaActual", $this->FechaActual);
            $stmt->bindParam(":DireccionIP", $this->DireccionIP);
            $stmt->bindParam(":ProgramaID", $this->ProgramaID);
            $stmt->bindParam(":Sucursal", $this->Sucursal);
            $stmt->bindParam(":CuentaAhoID", $this->CuentaAhoID);
            #Ejecución de la consulta
            if($stmt->execute()) {
                return true;
            }            
            return false;
        }
        
        function update_saldo() {
            #creación del update
            $query = "UPDATE " . $this->CUENTASAHO 
                    . " SET Saldo = (Saldo + " . $this->Saldo . "), SaldoDispon = (SaldoDispon + " . $this->SaldoDispon . ")"
                    . " WHERE CuentaAhoID = :CuentaAhoID";
            #preparación de la consulta
            $stmt = $this->conn->prepare($query);
            #se pasan los valores
            $stmt->bindParam(":CuentaAhoID", $this->CuentaAhoID);
            #ejecución de la consulta
            if($stmt->execute()) {
                return true;
            }            
            return false;
        }
    }