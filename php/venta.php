<?php
include 'connectionDB.php';
/**
 * Clase para la realización de una venta
 * @author David Brc Zamorano
 */
class Venta extends Connection
{
    /**
     * Función de creación
     * Obtiene el total de la venta a realizar; Si se encuentra vacia termina la función
     * Comprueba que el efectivo ingresado sea mayor o igual al total de la venta; De no ser asi, manda el mensaje 'insufficient'
     * Obtiene el id del usuario que realiza la venta
     * Calcula el cambio a dar
     * Crea el registro de venta en la tabla ventas
     * Obtiene el id del registro en la tabla ventas
     * Selecciona todos los registros de la tabla venta_temp; Tabla donde se almacenan los productos de una manera temporal
     * Inserta todos los registros a la tabla ventas_detalladas para registrar la venta
     * Elimina los registros de la table venta_temp
     * Guarda el calculo del cambio en un json y lo muestra
     * @param efectivo cantidad de dinero con el que el cliente paga la venta
     */
    public function create(float $efectivo)
    {
        $mysqli=$this->mysqli;
        $resultado = $mysqli->query("SELECT SUM(total) AS total FROM venta_temp") or exit($mysqli -> error);
        if ($resultado) {
            $row = $resultado -> fetch_assoc();
            $total = $row['total'];
            if (empty($total)) {
                exit('non_existent');
            }
            if ($efectivo == 0 || $efectivo < $total) {
                exit('insufficient');
            }
            session_start();
            $usuario = $_SESSION['id'];
            $cambio = $efectivo - $total;
            $cambio = round( $cambio, 2, PHP_ROUND_HALF_ODD);
            $resultado = $mysqli -> query("INSERT INTO ventas (usuario,fecha,total,efectivo,cambio) VALUES ('$usuario',sysdate(),'$total','$efectivo','$cambio')") or exit($mysqli -> error);
            if ($resultado) {
                $resultado = $mysqli -> query("SELECT MAX(id) AS id FROM ventas") or exit($mysqli -> error);
                $row = $resultado -> fetch_assoc();
                $venta = $row['id'];
                $resultado = $mysqli -> query("SELECT * FROM venta_temp") or exit($mysqli -> error);
                while ($row = $resultado -> fetch_assoc()) {
                    $producto   = $row['producto'];
                    $cantidad   = $row['cantidad'];
                    $subtotal   = $row['subtotal'];
                    $impuesto   = $row['impuesto'];
                    $total      = $row['total'];
                    $mysqli -> query("INSERT INTO ventas_detalladas (venta,producto,cantidad,subtotal,impuesto,total) VALUES ($venta,$producto,$cantidad,$subtotal,$impuesto,$total)") or exit($mysqli -> error);
                }
                $this->delete();
                echo json_encode( ['cambio' => $cambio] );
            }
        }
    }
    /**
     * Función de lectura
     * Realiza una consulta sql a la tabla venta_temp unida con productos
     * Almacena el resultado en un json
     */
    public function read()
    {
        $mysqli=$this->mysqli;
        $sql = "SELECT vt.producto AS id_producto,p.producto, vt.cantidad, vt.subtotal, vt.impuesto, vt.total
                FROM venta_temp AS vt 
                LEFT JOIN productos AS p ON vt.producto = p.id";
        $resultado = $mysqli->query($sql) or exit($mysqli -> error);
        if (!empty($resultado -> num_rows)) {
            $v_total = $this->readTotalSale();
            while ($row = $resultado -> fetch_assoc()) {
                $json[] = array(
                    "id_producto"   => $row['id_producto'],
                    "producto"      => $row['producto'],
                    "cantidad"      => $row['cantidad'],
                    "subtotal"      => $row['subtotal'],
                    "impuesto"      => $row['impuesto'],
                    "total"         => $row['total'],
                    "v_total"       => $v_total
                );
            }
            echo json_encode($json);
        } else {
            echo json_encode(["status" => "empty"]);
        }
        
    }
    /**
     * Función de eliminación
     * Elemina los registros de la tabla venta_temp
     */
    public function delete()
    {
        $mysqli=$this->mysqli;
        $mysqli->query("DELETE FROM venta_temp") or exit($mysqli -> error);
    }
    /**
     * Función de agregar producto
     * Obtiene el precio de venta del producto
     * Comprueba si existe el producto en la tabla venta_temp
     * Si existe el producto en la tabla aumenta su cantidad y actualiza el total, subtotal e impuesto del producto en la venta
     * Si no existe el producto en la tabla lo inserta con la cantidad, total, subtotal e impuesto del producto
     * @param producto identificador del producto
     */
    public function addProduct(int $producto)
    {
        $mysqli=$this->mysqli;
        $resultado = $mysqli -> query("SELECT precio_venta FROM productos WHERE id = '$producto'") or exit($mysqli -> error);
        if ($resultado) {
            $row = $resultado -> fetch_assoc();
            $precio_venta = $row['precio_venta'];
            $resultado = $mysqli -> query("SELECT cantidad FROM venta_temp WHERE producto = '$producto'") or exit($mysqli -> error);
            if ($resultado) {
                $row = $resultado -> fetch_assoc();
                if (!empty($row['cantidad'])) {
                    $cantidad = $row['cantidad'] + 1;
                    $total      = $cantidad * $precio_venta;
                    $subtotal   = $total * 0.84;
                    $impuesto   = $total * 0.16;
                    $mysqli -> query("UPDATE venta_temp SET cantidad = '$cantidad', subtotal = '$subtotal', impuesto = '$impuesto', total = '$total' WHERE producto = '$producto'") or exit($mysqli -> error);
                } else {
                    $cantidad   = 1;
                    $total      = $cantidad * $precio_venta;
                    $subtotal   = $total * 0.84;
                    $impuesto   = $total * 0.16;
                    $mysqli -> query("INSERT INTO venta_temp VALUES (0,'$producto','$cantidad','$subtotal','$impuesto','$total')") or exit($mysqli -> error);
                }
            }
        }
    }
    /**
     * Función de lectura del total de la venta actual
     * Suma el total de cada registro
     * Almacena el resultado en un json y lo muestra
     */
    public function readTotalSale()
    {
        $mysqli=$this->mysqli;
        $resultado = $mysqli->query("SELECT SUM(total) AS total FROM venta_temp") or exit($mysqli -> error);
        if ($resultado) {
            $row = $resultado -> fetch_assoc();
            // if (empty($row['total'])) {
            //     $row['total'] = 0;
            // }
            // echo json_encode( ['total' => $row['total']] );
            return $row['total'];
        }
    }
    /**
     * Función de eliminacion de un producto
     * Elimina el producto especifico de la tambla venta_temp
     * Comprueba si hay registros existentes en la tabla venta_temp
     * Si se encuentra sin registros, muestra el mensaje 'delete'
     * @param producto identificador del producto
     */
    public function deleteProduct(int $producto)
    {
        $mysqli=$this->mysqli;
        $mysqli->query("DELETE FROM venta_temp WHERE producto = '$producto'") or exit($mysqli -> error);
        $resultado = $mysqli->query("SELECT SUM(total) AS total FROM venta_temp") or exit($mysqli -> error);
        if ($resultado) {
            $row = $resultado -> fetch_assoc();
            if (empty($row['total'])) {
                echo 'delete';
            }
        }
    }
    /**
     * Función de reducción de cantidad
     * Obtiene el precio de venta del producto
     * Obtiene la cantidad actual del producto en la venta
     * Disminuye la cantidad del producto y actualiza el total, subtotal e impuesto del producto en la venta
     * Si la nueva cantidad es 0 el producto es eliminado de la tabla venta_temp y muestra el mensaje 'delete'
     * @param producto identificador del producto
     */
    public function subsProduct(int $producto)
    {
        $mysqli=$this->mysqli;
        $resultado = $mysqli -> query("SELECT precio_venta FROM productos WHERE id = '$producto'") or exit($mysqli -> error);
        if ($resultado) {
            $row = $resultado -> fetch_assoc();
            $precio_venta = $row['precio_venta'];
            $resultado = $mysqli -> query("SELECT cantidad FROM venta_temp WHERE producto = '$producto'") or exit($mysqli -> error);
            if ($resultado) {
                $row = $resultado -> fetch_assoc();
                $cantidad = $row['cantidad'] - 1;
                if ($cantidad > 0) {
                    $total      = $cantidad * $precio_venta;
                    $subtotal   = $total * 0.84;
                    $impuesto   = $total * 0.16;
                    $mysqli -> query("UPDATE venta_temp SET cantidad = '$cantidad', subtotal = '$subtotal', impuesto = '$impuesto', total = '$total' WHERE producto = '$producto'") or exit($mysqli -> error);
                } else {
                    $this->deleteProduct($producto);
                }
            }
        }
    }
}