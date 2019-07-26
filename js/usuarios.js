'use strict'
var editar = false;
var deleteID = '';
$(document).ready(function () {
    $('#barraNavegacion').load('components/navbar.html');
    $('#piePagina').load('components/footer.html');
    read();
});
$('#formUsuario').submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    data = data.replace('%40', '@');
    if (editar) {
        data += '&script=usuarios&function=update';
        update(data);
    } else {
        data += '&script=usuarios&function=create';
        create(data);
    }
});
$('#nuevo').click(function () {
    editar = false;
    var template = `
    <div class="form-group col-md-6">
    <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
    </div>
    <div class="form-group col-md-6">
        <label for="password">Contraseña</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
    </div>
    `;
    $('#emailpass').html(template);
    $('#usuarioTitle').text('Nuevo Usuario');
    limpiar();
    $('#usuario').modal('show');
});

function create(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == '') {
                read();
                $('#mensaje').html('<i class="fas fa-check-double"></i> Usuario Registrado');
                $('#mensaje').css("color", "#17a2b8");
                $('#mensaje').css("border-bottom", "solid 5px #17a2b8");
                $('#mensaje').animateCss('bounceIn', function () {
                    $('#mensaje').animateCss('bounceOut', function () {
                        $('#mensaje').html('');
                        $('#mensaje').css("border-bottom", "none");
                    });
                });
                limpiar();
            } else if (r == 'exist') {
                $('#mensaje').html('<i class="fas fa-exclamation"></i> Usuario Existente');
                $('#mensaje').css("color", "red");
                $('#mensaje').css("border-bottom", "solid 5px red");
                $('#mensaje').css("background-color", "#6c757d");
                $('#mensaje').animateCss('bounceIn', function () {
                    $('#mensaje').animateCss('bounceOut', function () {
                        $('#mensaje').html('');
                        $('#mensaje').css("border-bottom", "none");
                        $('#mensaje').css("background-color", "transparent");
                    });
                });
            }
        }
    });
}

function read() {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: "script=usuarios&function=read",
        success: function (r) {
            var template = '';
            var clase = '';
            var icono = '';
            var estado = '';
            var permiso = '';
            r.forEach(r => {
                if (r.estado == 'Activado') {
                    clase = 'bg-info';
                    icono = 'fa-user-slash';
                    estado = 'Desactivado';
                } else {
                    clase = 'bg-secondary';
                    icono = 'fa-user-check';
                    estado = 'Activado';
                }
                if (r.permiso == 1) {
                    permiso = 'Administrador';
                } else {
                    permiso = 'Empleado';
                }
                var data = r.id + '|&|' + r.email + '|&|' + r.nombre_s + '|&|' + r.apellido_s + '|&|' + r.permiso + '|&|' + r.estado;
                template += `
                <tr>
                    <td class="${clase}">${r.email}</td>
                    <td class="text-capitalize">${r.nombre_s}</td>
                    <td class="text-capitalize">${r.apellido_s}</td>
                    <td class="text-capitalize">${permiso}</td>
                    <td class="text-capitalize">${r.estado}</td>
                    <td class="text-center">
                        <button type="button" class="btn" onclick="updateStatus('${r.id}','${estado}')">
                            <i class="fas ${icono}"></i>
                        </button>
                        <button type="button" class="btn update" onclick="modalEditar('${data}')">
                            <i class="fas fa-user-edit"></i>
                        </button>
                        <button type="button" class="btn delete" onclick="confirmar('${r.id}','${r.email}')">
                            <i class="fas fa-user-times"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $('#outputUsuarios').html(template);
        },
        dataType: "json"
    });
}

function update(data) {
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == '') {
                read();
                $('#usuario').modal('hide');
            } else if (r == 'exist') {
                $('#mensaje').html('<i class="fas fa-exclamation"></i> Usuario Existente');
                $('#mensaje').css("color", "red");
                $('#mensaje').css("border-bottom", "solid 5px red");
                $('#mensaje').css("background-color", "#6c757d");
                $('#mensaje').animateCss('bounceIn', function () {
                    $('#mensaje').animateCss('bounceOut', function () {
                        $('#mensaje').html('');
                        $('#mensaje').css("border-bottom", "none");
                        $('#mensaje').css("background-color", "transparent");
                    });
                });
            }
        }
    });
}

function modalEditar(datos) {
    editar = true;
    var template = `
    <div class="form-group col-md-12">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
    </div>
    `;
    $('#emailpass').html(template);
    $('#usuarioTitle').text('Editar Usuario');
    var edit = datos.split("|&|");
    $('#id').val(edit[0]);
    $('#email').val(edit[1]);
    $('#nombre_s').val(edit[2]);
    $('#apellido_s').val(edit[3]);
    $('#permiso').val(edit[4]);
    $('#usuario').modal('show');
}

function updateStatus(id, estadoAct) {
    var data = "script=usuarios&function=status&id=" + id + "&status=" + estadoAct;
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == '') {
                read();
            }
        }
    });
}

function confirmar(id, email) {
    $('#emailEliminar').text(email);
    $('#eliminarUsuario').modal('show');
    deleteID = id;
}

function deleteUsuario() {
    var data = "script=usuarios&function=delete&id=" + deleteID;
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r == '') {
                read();
                $('#eliminarUsuario').modal('hide');
            }
        }
    });
}

function limpiar() {
    $('#email').val('');
    $('#password').val('');
    $('#nombre_s').val('');
    $('#apellido_s').val('');
    $('#permiso').val(2);
}