<?php
class Comprobar
{
    public function comprueba(string $comp)
    {
        $lol = password_hash($comp,PASSWORD_DEFAULT);
        $lol = json_encode(['lol' => $lol]);
        echo $lol;
    }
}
$comprueba = new Comprobar();
$comprueba -> comprueba('lol');