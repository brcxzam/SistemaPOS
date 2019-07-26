<?php
if (!isset($_POST['script']) || !isset($_POST['function'])) {
    exit;
}
$script = $_POST['script'];
$function = $_POST['function'];
switch ($script) {
    case 'InAndOut':
        inAndOut($function);
        break;
    case 'ventas':
        ventas($function);
        break;
    case 'usuarios':
        usuarios($function);
        break;
    case 'proveedores':
        proveedores($function);
        break;
    case 'productos':
        productos($function);
        break;
    case 'categorias':
        categorias($function);
        break;
    case 'venta':
        venta($function);
        break;
    
    default:
        exit;
        break;
}
function inAndOut($function)
{
    include_once 'inAndOut.php';
    $inAndOut = new InAndOut();
    switch ($function) {
        case 'logIn':
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $inAndOut -> logIn($email,$pass);
            break;
        case 'logOut':
            $inAndOut -> logOut();
            break;
            
        default:
            exit;
            break;
    }
}
function ventas($function)
{
    include_once 'ventas.php';
    $ventas = new Ventas();
    switch ($function) {
        case 'read':
            $ventas -> read();
            break;
        
        default:
            exit;
            break;
    }
}
function usuarios($function)
{
    include_once 'usuarios.php';
    $usuarios = new Usuarios();
    switch ($function) {
        case 'create':
            $email = $_POST['email'];
            $userpass = $_POST['password'];
            $nombre_s = $_POST['nombre_s'];
            $apellido_S = $_POST['apellido_s'];
            $permiso = $_POST['permiso'];
            $usuarios -> create($email,$userpass,$nombre_s,$apellido_S,$permiso);
            break;
        case 'read':
            $usuarios -> read();
            break;
        case 'update':
            $id = $_POST['id'];
            $email = $_POST['email'];
            $nombre_s = $_POST['nombre_s'];
            $apellido_S = $_POST['apellido_s'];
            $permiso = $_POST['permiso'];
            $usuarios -> update($id,$email,$nombre_s,$apellido_S,$permiso);
            break;
        case 'delete':
            $id = $_POST['id'];
            $usuarios -> delete($id);
            break;
        case 'status':
            $id = $_POST['id'];
            $status = $_POST['status'];
            $usuarios -> updateStatus($id,$status);
            break;
        
        default:
            exit;
            break;
    }
}
function proveedores($function)
{
    include_once 'proveedores.php';
    $proveedores = new Proveedores();
    switch ($function) {
        case 'create':
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $codigo_postal = $_POST['codigo_postal'];
            $colonia = $_POST['colonia'];
            $municipio = $_POST['municipio'];
            $estado = $_POST['estado'];
            $pais = $_POST['pais'];
            $proveedores -> create($proveedor,$contacto,$email,$telefono,$direccion,$codigo_postal,$colonia,$municipio,$estado,$pais);
            break;
        case 'read':
            $proveedores -> read();
            break;
        case 'update':
            $id = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $codigo_postal = $_POST['codigo_postal'];
            $colonia = $_POST['colonia'];
            $municipio = $_POST['municipio'];
            $estado = $_POST['estado'];
            $pais = $_POST['pais'];
            $proveedores -> update($id,$proveedor,$contacto,$email,$telefono,$direccion,$codigo_postal,$colonia,$municipio,$estado,$pais);
            break;
        case 'delete':
            $id = $_POST['id'];
            $proveedores -> delete($id);
            break;
        
        default:
            exit;
            break;
    }
}
function productos($function)
{
    include_once 'productos.php';
    $productos = new Productos();
    switch ($function) {
        case 'create':
            $producto = $_POST['producto'];
            $categoria = $_POST['categoria'];
            $proveedor = $_POST['proveedor'];
            $stock = $_POST['stock'];
            $stock_minimo = $_POST['stock_minimo'];
            $stock_maximo = $_POST['stock_maximo'];
            $precio_compra = $_POST['precio_compra'];
            $precio_venta = $_POST['precio_venta'];
            $productos -> create($producto,$categoria,$proveedor,$stock,$stock_minimo,$stock_maximo,$precio_compra,$precio_venta);
            break;
        case 'read':
            $productos -> read();
            break;
        case 'update':
            $id = $_POST['id'];
            $producto = $_POST['producto'];
            $categoria = $_POST['categoria'];
            $proveedor = $_POST['proveedor'];
            $stock = $_POST['stock'];
            $stock_minimo = $_POST['stock_minimo'];
            $stock_maximo = $_POST['stock_maximo'];
            $precio_compra = $_POST['precio_compra'];
            $precio_venta = $_POST['precio_venta'];
            $productos -> update($id,$producto,$categoria,$proveedor,$stock,$stock_minimo,$stock_maximo,$precio_compra,$precio_venta);
            break;
        case 'delete':
            $id = $_POST['id'];
            $productos -> delete($id);
            break;
        
        default:
            exit;
            break;
    }
}
function categorias($function)
{
    include_once 'categorias.php';
    $categorias = new Categorias();
    switch ($function) {
        case 'create':
            $categoria = $_POST['categoria'];
            $descripcion = $_POST['descripcion'];
            $categorias -> create($categoria,$descripcion);
            break;
        case 'read':
            $categorias -> read();
            break;
        case 'update':
            $id = $_POST['id'];
            $categoria = $_POST['categoria'];
            $descripcion = $_POST['descripcion'];
            $categorias -> update($id,$categoria,$descripcion);
            break;
        case 'delete':
            $id = $_POST['id'];
            $categorias -> delete($id);
            break;
        
        default:
            exit;
            break;
    }
}
function venta($function)
{
    include_once 'venta.php';
    $venta = new Venta();
    switch ($function) {
        case 'create':
            $efectivo = $_POST['efectivo'];
            $venta -> create($efectivo);
            break;
        case 'read':
            $venta -> read();
            break;
        case 'addProduct':
            $producto = $_POST['producto'];
            $venta -> addProduct($producto);
            break;
        case 'delete':
            $venta -> delete();
            break;
        case 'subsProduct':
            $producto = $_POST['producto'];
            $venta -> subsProduct($producto);
            break;
        case 'deleteProduct':
            $producto = $_POST['producto'];
            $venta -> deleteProduct($producto);
            break;

        default:
            exit;
            break;
    }
}