<?php
/*
 * Clase para crear la conección a la base de datos.
 */

class Database {
# Variables de conexión.
/*
    private $host = "localhost";
    private $username = "root";
    private $password = "15Admin01";
    private $db_name = "finanhnj_CrediTuDBp";
    
      private $host = "financierazafy.com.mx";
      private $username = "finanhnj_AdminDB";
      private $password = "15Admin01";*/
      private $host = "192.168.1.92";
      private $username = "root";
      private $password = "zafy2017";
      private $db_name = "microfin_pruebas"; //finanhnj_CrediTuDBp
     
    /*  VARIABLE DE CONEXIÓN    */
    public $conn;

    /*
     * Función para obtener la conección a la base de datos.
     */

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname="
                    . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

}

/*
 * Clase para crear la conección a la base de datos.
 */

class DatabaseLoc {
# Variables de conexión.

    private $host = "192.168.1.92";
    private $username = "root";
    private $password = "zafy2017";
    private $db_name = "microfin_pruebas";
    public $conn;

    /*
     * Función para obtener la conección a la base de datos.
     */

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname="
                    . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

}

/**
 * Clase para la conección a los web services
 */
class ClientVBC {
# Variables de conección.

    private $wsurlVBC = "http://192.168.1.91:8080/microfin/services/OperacionesVBC.wsdl";
    private $client;

    /*
     * Función para obtener la conección a la base de datos.
     */

    public function getClient() {
        $this->client = null;
        try {
            $this->client = new SoapClient($this->wsurlVBC);
        } catch (SoapFaultException $exception) {
            echo "Client error: " . $exception->getMessage();
        }
        return $this->client;
    }

}

/**
 * Clase para la conección a los web services
 */
class ClientSMS {
# Variables de conección.

    private $url = "http://192.168.1.45";
    private $port = "63333";
    public $client;
    public $errstr;
    public $errno;

    /*
     * Función para obtener la conección a la base de datos.
     */

    public function getClient() {
        $this->client = null;
        try {
            $this->client = fsockopen($this->url, $this->port, $this->errno, $this->errstr, 30);
        } catch (SoapFaultException $exception) {
            echo "Client error: " . $exception->getMessage();
        }
        return $this->client;
    }

}
