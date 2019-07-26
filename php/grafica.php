<?php
require '../php/connectionDB.php';
class Graph extends Connection
{
    public function read($type,$start,$end)
    {
        $mysqli = $this->mysqli;
        $resultado = $mysqli -> query("SELECT fecha,$type FROM ventas WHERE fecha BETWEEN '$start' AND '$end' GROUP BY fecha ORDER BY fecha") or exit($mysqli -> error);
        $json = [];
        while ($row = $resultado -> fetch_assoc()) {
            $json[] = [
                'fecha' => $row['fecha'],
                'resultado' => $row[$type]
            ];
        }
        echo json_encode($json);
    }
}

$pruebas = new Graph();
$type = $_POST['type'];
$start = $_POST['start'];
$end = $_POST['end'];
// $type = 'count(*)';
// $start = '2018-12-30';
// $end = '2019-01-01';
$pruebas -> read($type,$start,$end);