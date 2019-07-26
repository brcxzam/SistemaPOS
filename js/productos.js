"use strict";
var editar = false;
var deleteID = 0;
$(document).ready(function () {
    $("#barraNavegacion").load("components/navbar.html");
    $("#piePagina").load("components/footer.html");
    productos();
    categorias();
    proveedores();
});
$("#form").submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    if (editar) {
        data += "&script=productos&function=update";
        updateProducto(data);
    } else {
        data += "&script=productos&function=create";
        createProducto(data);
    }
});
$("#nuevo").click(function () {
    editar = false;
    $("#nuevo-editarTitle").text("Nuevo Producto");
    $("#form")[0].reset();
    $("#nuevo-editar").modal("show");
});
$("#elimina").click(function () {
    deleteProducto();
});

function categorias() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=categorias&function=read",
        success: function (r) {
            var template = '';
            r.forEach(r => {
                template += `
                <option value="${r.id}">${r.categoria}</option>
                `;
            });
            $('#inputCategoriaRP').html(template);
        },
        dataType: "json"
    });
}

function proveedores() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=proveedores&function=read",
        success: function (r) {
            var template = '';
            r.forEach(r => {
                template += `
                <option value="${r.id}">${r.proveedor}</option>
                `;
            });
            $('#inputProveedorRP').html(template);

        },
        dataType: "json"
    });
}

function confirma(id, producto) {
    $("#eliminar").text(producto);
    deleteID = id;
    $("#eliminarRegistro").modal("show");
}

function createProducto(data) {
    $.ajax({
        url: "php/main.php",
        type: "POST",
        data: data,
        success: function (r) {
            if (r == "") {
                productos();
                $("#form")[0].reset();
                mensaje('<i class="fas fa-check-double"></i>Registrado');
            }
        }
    });
}

function productos() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=productos&function=read",
        success: function (r) {
            var template = "";
            r.forEach(r => {
                var data =
                    r.id +
                    "|&|" +
                    r.producto +
                    "|&|" +
                    r.id_categoria +
                    "|&|" +
                    r.id_proveedor +
                    "|&|" +
                    r.stock +
                    "|&|" +
                    r.stock_minimo +
                    "|&|" +
                    r.stock_maximo +
                    "|&|" +
                    r.precio_compra +
                    "|&|" +
                    r.precio_venta;
                template += `
                <tr>
                    <th>${r.id}</th>
                    <td>${r.producto}</td>
                    <td>${r.categoria}</td>
                    <td>${r.proveedor}</td>
                    <td>${r.stock}</td>
                    <td>${r.stock_minimo}</td>
                    <td>${r.stock_maximo}</td>
                    <td>${r.precio_compra}</td>
                    <td>${r.precio_venta}</td>
                    <td class="text-center">
                        <button type="button" class="btn update" onclick="modalEditarProducto('${data}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn delete" onclick="confirma(${r.id},'${r.producto}',false)">
                            <i class="fas fa-backspace"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $("#outputProductos").html(template);
        },
        dataType: "json"
    });
}

function modalEditarProducto(data) {
    editar = true;
    var edit = data.split("|&|");
    $("#id").val(edit[0]);
    $("#inputProductoRP").val(edit[1]);
    $("#inputCategoriaRP").val(edit[2]);
    $('#inputProveedorRP').val(edit[3]);
    $("#inputStockRP").val(edit[4]);
    $("#inputStockMinRP").val(edit[5]);
    $("#inputStockMaxRP").val(edit[6]);
    $("#inputPrecioCompraRP").val(edit[7]);
    $("#inputPrecioVentaRP").val(edit[8]);
    $("#nuevo-editarTitle").text("Editar Producto");
    $("#nuevo-editar").modal("show");
}

function updateProducto(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == "") {
                productos();
                $("#nuevo-editar").modal("hide");
            }
        }
    });
}

function deleteProducto() {
    var data = "id=" + deleteID + "&script=productos&function=delete";
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == "") {
                productos();
                $("#eliminarRegistro").modal("hide");
            }
        }
    });
}

function mensaje(mensaje) {
    $('#mensaje').html(mensaje);
    $('#mensaje').css("color", "#17a2b8");
    $('#mensaje').animateCss('bounceIn', function () {
        $('#mensaje').animateCss('bounceOut', function () {
            $('#mensaje').html('');
        });
    });
}