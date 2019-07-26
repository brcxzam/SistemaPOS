DROP DATABASE pos;
CREATE DATABASE POS;
USE pos;
---------------------------------------------------------------------------------------------------
CREATE TABLE permisos (
    id int auto_increment,
    Permiso varchar(255),
    primary key (id)
);

CREATE TABLE usuarios (
    id int auto_increment,
    email varchar(255),
    userpass varchar(255),
    nombre_s varchar(255),
    apellido_s varchar(255),
    permiso int,
    estado varchar(15) default 'Desactivado',
    primary key (id),
    foreign key (permiso) references permisos(id) on delete set null
);

CREATE TABLE proveedores (
    id int auto_increment,
    proveedor varchar(255),
    contacto varchar(255),
    email varchar(255),
    telefono varchar(255),
    direccion varchar(255),
    codigo_postal int,
    colonia varchar(255),
    municipio varchar(255),
    estado varchar(255),
    pais varchar(255),
    primary key (id)
);

CREATE TABLE categorias (
    id int auto_increment,
    categoria varchar(255) ,
    descripcion varchar(255),
    primary key (id)
);

CREATE TABLE productos (
    id int auto_increment,
    producto varchar(255),
    categoria int,
    proveedor int,
    stock int,
    stock_minimo int,
    stock_maximo int,
    precio_compra decimal(7,2),
    precio_venta decimal(7,2),
    primary key (id),
    foreign key (categoria) references categorias(id) on delete set null,
    foreign key (proveedor) references proveedores(id) on delete set null
);

CREATE TABLE ventas (
    id int auto_increment,
    usuario int,
    fecha date,
    total decimal(7,2),
    efectivo decimal(7,2),
    cambio decimal(7,2),
    primary key (id),
    foreign key (usuario) references usuarios(id) on delete set null
);

CREATE TABLE ventas_detalladas (
    id int auto_increment,
    venta int,
    producto int,
    cantidad int,
    subtotal decimal(7,2),
    impuesto decimal(7,2),
    total decimal(7,2),
    primary key (id),
    foreign key (venta) references ventas(id) on delete set null,
    foreign key (producto) references productos(id) on delete set null
);

CREATE TABLE venta_temp (
    venta int,
    producto int,
    cantidad int,
    subtotal decimal(7,2),
    impuesto decimal(7,2),
    total decimal(7,2)
);
---------------------------------------------------------------------------------------------------
INSERT INTO permisos (permiso) VALUES ("administrador");
INSERT INTO permisos (permiso) VALUES ("empleado");
INSERT INTO usuarios (email, userpass, nombre_s, apellido_s, permiso) VALUES ("brcxzam@pos.com","$2y$10$7mQME64L49fwLzFGnykMHeZpz5Co4k0HINrlbbtNcMHU1icf4mafC","David","Zamorano",1);
INSERT INTO usuarios (email, userpass, nombre_s, apellido_s, permiso) VALUES ("lolazo77@pos.com","$2y$10$brg/IE./W86OW0CYjyLQ..Y0t6ekRS5o3xKCF9bNY6d2JaSP07JMC","David","Zamorano",2);
UPDATE usuarios SET estado = 'Activado' WHERE id = 1;
---------------------------------------------------------------------------------------------------
delimiter //
CREATE OR REPLACE TRIGGER descproducts BEFORE INSERT ON ventas_detalladas
FOR EACH ROW
BEGIN
    DECLARE var_currentStock INT;
    DECLARE var_newStock INT;
    SELECT stock INTO var_currentStock FROM productos WHERE id = new.producto;
    SET var_newStock = var_currentStock - new.cantidad;
    UPDATE productos SET stock = var_newStock WHERE id = new.producto;
END;//
delimiter ;