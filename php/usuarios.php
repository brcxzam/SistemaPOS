<?php
include 'connectionDB.php';
/**
 * Clase CRUD para la tabla usuarios
 * @author David Brc Zamorano
 */
class Usuarios extends Connection
{
    /**
     * Funcion de creación
     * Comprueba que no exista un usuario con el email ingresado; Si existe termina con el mensaje 'exist'
     * Encripta la contraseña
     * Inserta la informacion del usuario a la base de datos
     * @param email correo electronico
     * @param pass contraseña
     * @param nombre_s nombre o nombres
     * @param apellido_s apellido o apellidos
     * @param permiso permiso; Administrador(1)/Usuario(0)
     */
    public function create(string $email,string $pass,string $nombre_s,string $apellido_s,int $permiso)
    {
        $mysqli =  $this->mysqli;
        $result = $mysqli->query("SELECT COUNT(*) AS existente FROM usuarios WHERE email = '$email'");
        if ($result) {
            $row = $result -> fetch_assoc();
            $existente = ($row['existente'] == 0) ? true : false ;
            if ($existente) {
                $pass = password_hash($pass,PASSWORD_DEFAULT);
                $mysqli -> query("INSERT INTO usuarios (email,userpass,nombre_s,apellido_s,permiso) VALUES ('$email','$pass','$nombre_s','$apellido_s','$permiso')") or exit($mysqli -> error);
            } else {
                exit('exist');
            }
            
        }
    }
    /**
     * Funcion de lectura
     * Realiza una consulta sql a la tabla usuarios
     * Almacena el resultado en un json
     */
    public function read()
    {
        $mysqli = $this->mysqli;
        $result = $mysqli -> query ("SELECT id,email,nombre_s,apellido_s,permiso,estado FROM usuarios  ORDER BY id DESC") or exit($mysqli -> error);
        if ($result) {
            while ($row = $result -> fetch_assoc()) {
                $json[] = [
                    'id' => $row['id'],
                    'email' => $row['email'],
                    'nombre_s' => $row['nombre_s'],
                    'apellido_s' => $row['apellido_s'],
                    'permiso' => $row['permiso'],
                    'estado' => $row['estado']
                ];
            }
            echo json_encode($json);
        }
    }
    /**
     * Funcion de actualización
     * Comprueba que no exista un usuario con el email ingresado; Si existe termina con el mensaje 'exist'
     * Actualiza la informacion de la cuenta del usuario
     * @param id identificador del usuario
     * @param email correo electronico
     * @param nombre_s nombre o nombres
     * @param apellido_s apellido o apellidos
     * @param permiso permiso; Administrador(1)/Usuario(0)
     */
    public function update(int $id,string $email,string $nombre_s,string $apellido_s,int $permiso)
    {
        $mysqli = $this->mysqli;
        $result = $mysqli->query("SELECT COUNT(*) AS existente FROM usuarios WHERE email = '$email' AND id <> '$id'");
        if ($result) {
            $row = $result -> fetch_assoc();
            $existente = ($row['existente'] == 0) ? true : false ;
            if ($existente) {
                $mysqli -> query("UPDATE usuarios SET email='$email',nombre_s='$nombre_s',apellido_s='$apellido_s',permiso='$permiso' WHERE id = '$id'") or exit($mysqli -> error);
            } else {
                exit('exist');
            }
            
        }
    }
    /**
     * Funcion de eliminación
     * Elimina la cuenta del usuario
     * @param id identificador del usuario
     */
    public function delete(int $id)
    {
        $mysqli = $this->mysqli;
        $mysqli -> query("DELETE FROM usuarios WHERE id = '$id'") or exit($mysqli -> error);
    }
    /**
     * Funcion de cambio de estado
     * Actualiza el estado de la cuenta del usuario; Activado/Desactivado
     * @param id identificador del usuario
     * @param status nuevo estado
     */
    public function updateStatus(int $id,string $status)
    {
        $mysqli = $this->mysqli;
        $mysqli -> query("UPDATE usuarios SET estado = '$status' WHERE id = '$id'") or exit($mysqli -> error);
    }
}