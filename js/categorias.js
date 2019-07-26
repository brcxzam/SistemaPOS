"use strict";
var editar = false;
var deleteID = 0;
$(document).ready(function () {
    $("#barraNavegacion").load("components/navbar.html");
    $("#piePagina").load("components/footer.html");
    categorias();
});
$("#form").submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    if (editar) {
        data += "&script=categorias&function=update";
        updateCategoria(data);
    } else {
        data += "&script=categorias&function=create";
        createCategoria(data);
    }
});
$("#nuevo").click(function () {
    editar = false;
    $("#nuevo-editarTitle").text("Nueva Categoria");
    $("#form")[0].reset();
    $("#nuevo-editar").modal("show");
});
$("#elimina").click(function () {
    deleteCategoria();
});

function confirma(id, categoria) {
    $("#eliminar").text(categoria);
    deleteID = id;
    $("#eliminarRegistro").modal("show");
}

function createCategoria(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == "") {
                categorias();
                $("#form")[0].reset();
                mensaje('<i class="fas fa-check-double"></i>Registrado');
            }
        }
    });
}

function categorias() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=categorias&function=read",
        success: function (r) {
            var template = "";
            r.forEach(r => {
                var data = r.id + "|&|" + r.categoria + "|&|" + r.descripcion;
                template += `
                <tr>
                    <td>${r.categoria}</td>
                    <td>${r.descripcion}</td>
                    <td class="text-center">
                        <button type="button" class="btn update" onclick="modaleditar('${data}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn delete" onclick="confirma(${r.id},'${r.categoria}')">
                            <i class="fas fa-backspace"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $("#outputCategorias").html(template);
        },
        dataType: "json"
    });
}

function modaleditar(data) {
    editar = true;
    var edit = data.split("|&|");
    $("#id").val(edit[0]);
    $("#inputCategoria").val(edit[1]);
    $("#inputDescripcion").val(edit[2]);
    $("#nuevo-editarTitle").text("Editar Categoria");
    $("#nuevo-editar").modal("show");
}

function updateCategoria(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            categorias();
            $("#nuevo-editar").modal("hide");
        }
    });
}

function deleteCategoria() {
    var data = "id=" + deleteID + "&script=categorias&function=delete";
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            categorias();
            $("#eliminarRegistro").modal("hide");
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