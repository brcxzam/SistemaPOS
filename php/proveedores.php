<?php
include 'connectionDB.php';
/**
 * Clase CRUD para la tabla proveedores
 * @author David Brc Zamorano
 */
class Proveedores extends Connection
{
    /**
     * Función de creación
     * Inserta en la tabla la informacion recibida
     * @param proveedor nombre de la compañia
     * @param contacto nombre del ejecutivo de ventas
     * @param email correo electronico del ejecutivo
     * @param telefono numero telefonico del ejecutivo
     * @param direccion calle, entre calles y numero de la dirección
     * @param codigoPostal
     * @param colonia
     * @param municipio
     * @param estado
     * @param pais
     */
    public function create(string $proveedor,string $contacto,string $email,string $telefono,string $direccion,int $codigoPostal,string $colonia,string $municipio,string $estado,string $pais)
    {
        $mysqli = $this->mysqli;
        $mysqli->query("INSERT INTO proveedores (proveedor,contacto,email,telefono,direccion,codigo_postal,colonia,municipio,estado,pais) VALUES ('$proveedor','$contacto','$email','$telefono','$direccion','$codigoPostal','$colonia','$municipio','$estado','$pais')") or exit($mysqli -> error);
    }
    /**
     * Funcion de lectura
     * Realiza una consulta sql a la tabla proveedores
     * Almacena el resultado en un json
     */
    public function read()
    {
        $mysqli = $this->mysqli;
        $resultado = $mysqli -> query("SELECT * FROM proveedores ORDER BY proveedor ASC") or exit($mysqli -> error);
        if ($resultado) {
            while ($row = $resultado -> fetch_assoc()) {
                $json[] = [
                    'id' => $row['id'],
                    'proveedor' => $row['proveedor'],
                    'contacto' => $row['contacto'],
                    'email' => $row['email'],
                    'telefono' => $row['telefono'],
                    'direccion' => $row['direccion'],
                    'codigo_postal' => $row['codigo_postal'],
                    'colonia' => $row['colonia'],
                    'municipio' => $row['municipio'],
                    'estado' => $row['estado'],
                    'pais' => $row['pais']
                ];
            }
            echo json_encode($json);
        }
    }
    /**
     * Función de actualización
     * Actualiza la informacion del registro con los nuevos valores
     * @param id identificador del proveedor
     * @param proveedor nombre de la compañia
     * @param contacto nombre del ejecutivo de ventas
     * @param email correo electronico del ejecutivo
     * @param telefono numero telefonico del ejecutivo
     * @param direccion calle, entre calles y numero de la dirección
     * @param codigoPostal
     * @param colonia
     * @param municipio
     * @param estado
     * @param pais
     */
    function update(int $id,string $proveedor,string $contacto,string $email,string $telefono,string $direccion,int $codigoPostal,string $colonia,string $municipio,string $estado,string $pais)
    {
        $mysqli = $this->mysqli;
        $mysqli->query("UPDATE proveedores SET proveedor = '$proveedor', contacto = '$contacto', email = '$email', telefono = '$telefono', direccion = '$direccion', codigo_postal = '$codigoPostal', colonia = '$colonia', municipio = '$municipio', estado = '$estado', pais = '$pais' WHERE id = '$id'") or exit($mysqli -> error);
    }
    /**
     * Función de eliminación
     * Elimina el registro de un proveedor especifico
     * @param id identificador del proveedor
     */
    function delete(int $id)
    {
        $mysqli = $this->mysqli;
        $mysqli->query("DELETE FROM proveedores WHERE id = '$id'");
    }
}