<?php
include 'connectionDB.php';
/**
 * Clase CRUD para la tabla Categorias; Tabla que es requerida por la tabla productos
 * @author David Brc Zamorano
 */
class Categorias extends Connection
{
    /**
     * Función de creación
     * Inserta en la tabla la informacion recibida
     * @param categoria nombre de la categoria
     * @param descripcion descripcion de la categoria
     */
    public function create(string $categoria,string $descripcion)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("INSERT INTO categorias (categoria,descripcion) VALUES ('$categoria','$descripcion')") or exit($mysqli -> error);
    }
    /**
     * Funcion de lectura
     * Realiza una consulta sql a la tabla categorias
     * Almacena el resultado en un json
     */
    public function read()
    {
        $mysqli=$this->mysqli;
        $resultado = $mysqli->query("SELECT * FROM categorias ORDER BY id DESC") or exit($mysqli -> error);
        while ($row = $resultado -> fetch_assoc()) {
            $json[] = array(
                "id" => $row['id'],
                "categoria" => $row['categoria'],
                "descripcion" => $row['descripcion']
            );
        }
        echo json_encode($json);
    }
    /**
     * Función de actualización
     * Actualiza la informacion del registro con los nuevos valores
     * @param id identificador de la categoria
     * @param categoria nombre de la categoria
     * @param descripcion descripcion de la categoria
     */
    public function update(int $id,string $categoria,string $descripcion)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("UPDATE categorias SET categoria='$categoria',descripcion='$descripcion' WHERE id = '$id'") or exit($mysqli -> error);
    }
    /**
     * Función de eliminación
     * Elimina el registro de una categoria especifica
     * @param id identificador de la categoria
     */
    public function delete(int $id)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("DELETE FROM categorias WHERE id = '$id'") or exit($mysqli -> error);
    }
}