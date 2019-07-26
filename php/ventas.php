<?php
include 'connectionDB.php';
/**
 * Clase para la lectura de ventas
 * @author David Brc Zamorano
 */
class Ventas extends Connection
{
    /**
     * Funcion de lectura
     * Realiza una consulta sql a la tabla ventas unida con ventas_detalladas, usuarios y productos
     * Almacena el resultado en un json
     */
    public function read()
    {
        $mysqli = $this->mysqli;
        $sql = "SELECT v.id, u.email, v.fecha, v.total, v.efectivo, v.cambio, p.producto, vd.cantidad, vd.subtotal, vd.impuesto, vd.total 
                FROM ventas AS v 
                LEFT JOIN ventas_detalladas AS vd ON v.id = vd.venta 
                LEFT JOIN usuarios AS u ON v.usuario = u.id 
                LEFT JOIN productos as p ON vd.producto = p.id
                ORDER BY v.id DESC";
        $result = $mysqli -> query($sql) or exit($mysqli -> error);
        if ($result) {
            while ($row = $result -> fetch_assoc()) {
                $json[] = [
                    'id' => $row['id'],
                    'email' => $row['email'],
                    'fecha' => $row['fecha'],
                    'total' => $row['total'],
                    'efectivo' => $row['efectivo'],
                    'cambio' => $row['cambio'],
                    'producto' => $row['producto'],
                    'cantidad' => $row['cantidad'],
                    'subtotal' => $row['subtotal'],
                    'impuesto' => $row['impuesto'],
                    'total' => $row['total']
                ];
            }
            echo json_encode($json);
        }
    }
}