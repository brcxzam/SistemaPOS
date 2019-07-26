<?php
/**
 * Clase de autenticacion
 * @author David Brc Zamorano
 */
class Authentication
{
    private $type = 'login';
    private $page = 'index.html';
    private $root = '/sistema-pos';

    /**
     * @param type tipo de pagina; admin,user o login
     * @param page nombre del archivo html
     */
    function __construct(string $type, string $page) {
        session_start();
        $this->type = $type;
        $this->page = $page;
        $this->authenticate();
    }

    /**
     * Funcion de autenticacion
     * Comprueba que el nombre y permiso del usuario existan; en caso de no existir se redirecciona al login
     * Comprueba el tipo de permiso; 1 administradores y 2 usuarios
     * Solo permite el acceso a las paginas de acuerdo a su permiso; si su permiso no es correcto es direccionado a la pagina que le corresponde
     */
    public function authenticate() {
        if (isset($_SESSION['usuario']) && isset($_SESSION['permiso'])) {
            if ($_SESSION['permiso'] == 1) {
                if ($this -> type == 'admin') {
                    include 'html/'.$this -> page;
                } else {
                    header('Location: ventas');
                }
            } elseif ($_SESSION['permiso'] == 2) {
                if ($this -> type == 'user') {
                    include 'html/'.$this -> page;
                } else {
                    header('Location: venta');
                }
            }
        } else {
            if ($this -> type == 'login') {
                include 'html/'.$this -> page;
            } else {
                header('Location: '.$this -> root);
            }
            
        }
        
    }
}