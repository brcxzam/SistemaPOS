<?php
include 'connectionDB.php';
/**
 * Clase CRUD para la tabla productos
 * @author David Brc Zamorano
 */
class Productos extends Connection
{
    /**
     * Función de creación
     * Inserta en la tabla la información recibida
     * @param producto nombre del producto
     * @param categoria categoria a la que pertenece
     * @param proveedor proovedor que suministra el producto
     * @param stock cantidad actual del producto en el invetario
     * @param stock_minimo cantidad minima para ser suministrada
     * @param stock_maximo cantidad maxima que se puede tener del producto
     * @param precio_compra precio unitario del proveedor
     * @param precio_venta precio unitario para el cliente
     */
    public function create(string $producto,int $categoria,int $proveedor,int $stock,int $stock_minimo,int $stock_maximo,float $precio_compra,float $precio_venta)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("INSERT INTO productos (producto,categoria,proveedor,stock,stock_minimo,stock_maximo,precio_compra,precio_venta) VALUES ('$producto','$categoria','$proveedor','$stock','$stock_minimo','$stock_maximo','$precio_compra','$precio_venta')") or exit($mysqli -> error);
    }
    /**
     * Funcion de lectura
     * Realiza una consulta sql a la tabla productos
     * Almacena el resultado en un json
     */
    public function read()
    {
        $mysqli=$this->mysqli;
        $sql = "SELECT p.id,p.producto,p.categoria AS id_categoria,c.categoria,p.proveedor AS id_proveedor,pr.proveedor,p.stock,p.stock_minimo,p.stock_maximo,p.precio_compra,p.precio_venta
                FROM productos AS p 
                LEFT JOIN categorias AS c ON p.categoria = c.id 
                LEFT JOIN proveedores AS pr ON p.proveedor = pr.id
                ORDER BY p.id DESC";
        $resultado = $mysqli->query($sql) or exit($mysqli -> error);
        while ($row = $resultado -> fetch_assoc()) {
            $json[] = array(
                "id" => $row['id'],
                "producto" => $row['producto'],
                "id_categoria" => $row['id_categoria'],
                "categoria" => $row['categoria'],
                "id_proveedor" => $row['id_proveedor'],
                "proveedor" => $row['proveedor'],
                "stock" => $row['stock'],
                "stock_minimo" => $row['stock_minimo'],
                "stock_maximo" => $row['stock_maximo'],
                "precio_compra" => $row['precio_compra'],
                "precio_venta" => $row['precio_venta']
            );
        }
        echo json_encode($json);
    }
    /**
     * Función de actualización
     * Actualiza la informacion del registro con los nuevos valores
     * @param id identificador del producto
     * @param producto nombre del producto
     * @param categoria categoria a la que pertenece
     * @param proveedor proovedor que suministra el producto
     * @param stock cantidad actual del producto en el invetario
     * @param stock_minimo cantidad minima para ser suministrada
     * @param stock_maximo cantidad maxima que se puede tener del producto
     * @param precio_compra precio unitario del proveedor
     * @param precio_venta precio unitario para el cliente
     */
    public function update(int $id,string $producto,int $categoria,int $proveedor,int $stock,int $stock_minimo,int $stock_maximo,float $precio_compra,float $precio_venta)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("UPDATE productos SET producto='$producto',categoria='$categoria',proveedor='$proveedor',stock='$stock',stock_minimo='$stock_minimo',stock_maximo='$stock_maximo',precio_compra='$precio_compra',precio_venta='$precio_venta' WHERE id = '$id'") or exit($mysqli -> error);
    }
    /**
     * Función de eliminación
     * Elimina el registro de un producto especifico
     * @param id identificador del producto
     */
    public function delete(int $id)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("DELETE FROM productos WHERE id = '$id'") or exit($mysqli -> error);
    }
}