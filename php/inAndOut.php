<?php
include 'connectionDB.php';
/**
 * Clase de acceso y salida del sistema
 * @author David Brc Zamorano
 */
class InAndOut extends Connection
{
    function __construct() {
        parent::__construct();
        session_start();
    }
    /**
     * Funcion de acceso
     * Comprueba que el email exista; En caso de no existir no devuelve nada
     * Comprueba la contraseña; En caso de no coincidir no devuelve nada
     * Inicia una sesion
     * Inicializa id, usuarioNombre y permiso dentro de la sesion
     * Devuelve permiso y estado del usuario
     * @param email correo electronico del usuario
     * @param pass contraseña del usuario
     */
    public function logIn(string $email, string $pass)
    {
        $mysqli = $this->mysqli;
        $resultado = $mysqli -> query("SELECT id,userpass,nombre_s,permiso,estado FROM usuarios WHERE email = '$email'") or exit($mysqli -> error);
        if ($resultado) {
            $row = $resultado -> fetch_assoc();
            if (password_verify($pass, $row['userpass'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['usuario'] = $row['nombre_s'];
                $_SESSION['permiso'] = $row['permiso'];
                $json = [
                    'usuario' => $row['nombre_s'],
                    'permiso' => $row['permiso'],
                    'estado' => $row['estado']
                ];
                echo json_encode($json);
            }
        }
    }
    /**
     * Funcion de salida
     * Destruye la sesion y la informacion que contiene
     */
    public function logOut()
    {
        session_unset();
        session_destroy();
    }
}