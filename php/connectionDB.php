<?php
/**
 * Clase de conexion a la base de datos
 * @author David Brc Zamorano
 */
class Connection
{
    /**
     * Parametros de la conexión
     */
    private $server = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database ='pos';
    public $mysqli;
    /**
     * Inicializa la conexión
     */
    function __construct() {
        $this->mysqli = $this->connect();
    }
    /**
     * Función de conexión a la base de datos
     * Crea un objeto de conexión con los parametros establecidos
     * Al encontrar un error lo muesta; De lo contrario regresa el objeto con la conexión
     */
    public function connect()
    {
        $mysqli = new mysqli($this->server,$this->user,$this->password,$this->database);
        if ($mysqli->connect_errno) {
            echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        return $mysqli;
    }
    /**
     * Funcion de cierre de conexión
     */
    function __destruct() {
        $this->mysqli->close();
    }
}