<?php
include 'connectionDB.php';
class Caja extends Connection
{
    public function open()
    {
        $mysqli=$this->mysqli;
        $mysqli -> query("UPDATE FROM caja SET estado_caja = 1");
    }
    
    public function close()
    {
        $mysqli=$this->mysqli;
        $mysqli -> query("UPDATE FROM caja SET estado_caja = 0");
    }
}
